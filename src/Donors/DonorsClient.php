<?php

namespace Awardspring\Donors;

use Psr\Http\Client\ClientInterface;
use Awardspring\Core\Client\RawClient;
use Awardspring\Donors\Requests\ListDonorsRequest;
use Awardspring\Core\Pagination\Pager;
use Awardspring\Types\DonorListItemV1;
use Awardspring\Core\Pagination\CursorPager;
use Awardspring\Types\DonorListItemV1ListResponse;
use Awardspring\Donors\Requests\CreateDonorV1Request;
use Awardspring\Types\DonorV1;
use Awardspring\Exceptions\AwardspringException;
use Awardspring\Exceptions\AwardspringApiException;
use Awardspring\Core\Json\JsonApiRequest;
use Awardspring\Environments;
use Awardspring\Core\Client\HttpMethod;
use JsonException;
use Psr\Http\Client\ClientExceptionInterface;
use Awardspring\Types\DonorDetailV1;
use Awardspring\Donors\Requests\UpdateDonorV1Request;
use Awardspring\Donors\Requests\UpdateDonorNotesV1Request;
use Awardspring\Types\DonorNotesV1;

class DonorsClient
{
    /**
     * @var array{
     *   baseUrl?: string,
     *   client?: ClientInterface,
     *   maxRetries?: int,
     *   timeout?: float,
     *   headers?: array<string, string>,
     * } $options @phpstan-ignore-next-line Property is used in endpoint methods via HttpEndpointGenerator
     */
    private array $options;

    /**
     * @var RawClient $client
     */
    private RawClient $client;

    /**
     * @param RawClient $client
     * @param ?array{
     *   baseUrl?: string,
     *   client?: ClientInterface,
     *   maxRetries?: int,
     *   timeout?: float,
     *   headers?: array<string, string>,
     * } $options
     */
    public function __construct(
        RawClient $client,
        ?array $options = null,
    ) {
        $this->client = $client;
        $this->options = $options ?? [];
    }

    /**
     * Returns one page at a time. With no `sort` and no `q`, donors are listed by
     * `id`, lowest first.
     *
     *
     * Sort with `sort`. `name` lists donors A to Z, ignoring case: an organization by its
     * organization name, and a person by last name, then first name. A person with no last name
     * is listed by first name, and a donor with no name at all comes last. `last_contacted`
     * lists the most recently contacted donors first, by `last_contacted_at`; donors who
     * have never been contacted come last. `longest_since_contact` is the other way round: donors
     * never contacted first, then the oldest contact. Donors who tie are listed by `id`: lowest
     * first for `name` and `longest_since_contact`, highest first for `last_contacted`.
     *
     *
     * Narrow the list with `level` to donors at one or more levels, for example
     * `level=major,principal`. A donor's level comes from their giving, so a request made as
     * a signed-in user who may not see donors' giving returns `403 permission_denied` when
     * it filters by level. Sorting is open to everyone who can list donors.
     *
     *
     * Narrow the list with `giving_status` to donors with one or more giving statuses, for
     * example `giving_status=recurring,new`. `Recurring`: the donor has a recurring gift
     * that is still running (not cancelled or stopped, and not past its end date) and at least one
     * payment from it has gone through. `New`: the donor's first gift was in the last 12
     * months. `Lapsed`: the donor's last gift was more than 12 months ago. The 12 months count
     * back from now, not from the start of a fiscal year. A donor who fits more than one takes the
     * first of `Recurring`, `New`, `Lapsed`. A donor who has never given has no giving
     * status, and neither does one whose first gift was more than 12 months ago and who has given
     * again within the last 12 months. With `level` as well, a donor must match both. A
     * request made as a signed-in user who may not see donors' giving returns
     * `403 permission_denied` when it filters by giving status.
     *
     *
     * Search with `q` (at least `2` characters). Every word you type must match the
     * start of a word in the donor's first name, last name or organization, or the start of
     * their email address, so `jane smi` finds Jane Smith. Matches inside a word are not
     * found: `co` finds `Coworking` but not `Waco`. Where digits and letters
     * touch, a word is also split there, so `25co` finds `25N Coworking`.
     *
     *
     * Without `sort`, search results come in three groups: first, donors whose full name or
     * organization starts with the search (for a search with no spaces, compared with the spaces
     * taken out, so `janesmith` finds Jane Smith); next, donors where every word starts a
     * word in their name or organization; last, donors matched only through their email address.
     * Within a group, donors are listed by `id`, lowest first, so the order holds from one
     * page to the next. With `sort`, the search only decides which donors are listed and
     * the sort decides their order.
     *
     * `sort`, `level`, `giving_status` and `q` work together. Send the same ones
     * on every page: a cursor sent with a different `sort`, `level`, `giving_status`
     * or `q` returns `400 invalid_cursor`. A search shorter than `2` characters, or an
     * unknown `level`, `giving_status` or `sort`, returns `400 validation_failed`
     * with `param` set to the parameter at fault.
     *
     *
     * To keep a copy in sync, send `changed_since`, a time in UTC epoch seconds. The list then holds
     * only the donors whose stored details changed at or after that time, in the order they changed,
     * oldest change first, by `last_changed_at`. A new gift does not count as a change to its donor.
     * Archiving or restoring a donor counts as a change: an archived donor stays in the list with
     * `archived: true`, including one who is a donor only through an extra Donor role, and comes back
     * with `archived: false` once restored. A donor removed since that time is listed too, at the time it was removed, with only
     * `object`, `id`, `deleted: true` and `removed_at`. That includes a person who stops
     * being a donor but keeps their account, for example when their Donor role is removed; their gifts are
     * not removed with it. If that person becomes a donor again, the same `id` is listed again as a
     * change, after its removal. A removed donor keeps no
     * giving, so a list narrowed by `level` or `giving_status` leaves removals out: keep one
     * sync with no other filter to learn of them. Every page carries `complete_up_to`, the same on
     * every page of one walk; start the next sync from it. A donor may appear in two syncs in a row, but
     * none is missed.
     *
     * `changed_since` works with `level` and `giving_status`. With `sort` or
     * `q` it returns `400 validation_failed` with `param` naming the parameter it cannot
     * be combined with. A time in the future, or anything but a whole number of seconds, returns
     * `400 validation_failed` with `param` set to `changed_since`. A cursor sent with a
     * different `changed_since` returns `400 invalid_cursor`.
     *
     *
     * Assistant capability: `springiq_list_donors`. Required permission: Donors (applies when called on behalf of a signed-in person).
     *
     * Example:
     * ```php
     * $client->donors->list(
     *     new ListDonorsRequest([]),
     * );
     * ```
     *
     * @param ListDonorsRequest $request
     * @param ?array{
     *   baseUrl?: string,
     *   maxRetries?: int,
     *   timeout?: float,
     *   headers?: array<string, string>,
     *   queryParameters?: array<string, mixed>,
     *   bodyProperties?: array<string, mixed>,
     * } $options
     * @return Pager<DonorListItemV1>
     */
    public function list(ListDonorsRequest $request = new ListDonorsRequest(), ?array $options = null): Pager
    {
        return new CursorPager(
            request: $request,
            getNextPage: fn (ListDonorsRequest $request) => $this->_list($request, $options),
            setCursor: function (ListDonorsRequest $request, ?string $cursor) {
                $request->startingAfter = $cursor;
            },
            /* @phpstan-ignore-next-line */
            getNextCursor: fn (?DonorListItemV1ListResponse $response) => $response?->nextCursor ?? null,
            /* @phpstan-ignore-next-line */
            getItems: fn (?DonorListItemV1ListResponse $response) => $response?->data ?? [],
        );
    }

    /**
     * Creates either an individual or an organization, depending on `role`. Send
     * `dry_run` to validate the request without saving it, and an `Idempotency-Key`
     * header so a retry cannot create the same donor twice.
     *
     * Assistant capability: `springiq_create_donor`. Required permission: Donors (applies when called on behalf of a signed-in person).
     *
     * Example:
     * ```php
     * $client->donors->create(
     *     new CreateDonorV1Request([]),
     * );
     * ```
     *
     * @param CreateDonorV1Request $request
     * @param ?array{
     *   baseUrl?: string,
     *   maxRetries?: int,
     *   timeout?: float,
     *   headers?: array<string, string>,
     *   queryParameters?: array<string, mixed>,
     *   bodyProperties?: array<string, mixed>,
     * } $options
     * @return ?DonorV1
     * @throws AwardspringException
     * @throws AwardspringApiException
     */
    public function create(CreateDonorV1Request $request = new CreateDonorV1Request(), ?array $options = null): ?DonorV1
    {
        $options = array_merge($this->options, $options ?? []);
        try {
            $response = $this->client->sendRequest(
                new JsonApiRequest(
                    baseUrl: $options['baseUrl'] ?? $this->client->options['baseUrl'] ?? Environments::UnitedStates->value,
                    path: "api/v1/donors",
                    method: HttpMethod::POST,
                    body: $request,
                ),
                $options,
            );
            $statusCode = $response->getStatusCode();
            if ($statusCode >= 200 && $statusCode < 400) {
                $json = $response->getBody()->getContents();
                if (empty($json)) {
                    return null;
                }
                return DonorV1::fromJson($json);
            }
        } catch (JsonException $e) {
            throw new AwardspringException(message: "Failed to deserialize response: {$e->getMessage()}", previous: $e);
        } catch (ClientExceptionInterface $e) {
            throw new AwardspringException(message: $e->getMessage(), previous: $e);
        }
        throw new AwardspringApiException(
            message: 'API request failed',
            statusCode: $statusCode,
            body: $response->getBody()->getContents(),
        );
    }

    /**
     * Includes giving totals for the donor's lifetime and the current year, their most recent
     * gift, their notes, the tags on the donor, and their answers to the institution's donor custom
     * fields. `id` is the donor's stored ID. Returns `404 donor_not_found`
     * naming `id` if no such donor belongs to this institution.
     *
     * Assistant capability: `springiq_get_donor_profile`. Required permission: Donors (applies when called on behalf of a signed-in person).
     *
     * Example:
     * ```php
     * $client->donors->get(
     *     'id',
     * );
     * ```
     *
     * @param string $id
     * @param ?array{
     *   baseUrl?: string,
     *   maxRetries?: int,
     *   timeout?: float,
     *   headers?: array<string, string>,
     *   queryParameters?: array<string, mixed>,
     *   bodyProperties?: array<string, mixed>,
     * } $options
     * @return ?DonorDetailV1
     * @throws AwardspringException
     * @throws AwardspringApiException
     */
    public function get(string $id, ?array $options = null): ?DonorDetailV1
    {
        $options = array_merge($this->options, $options ?? []);
        try {
            $response = $this->client->sendRequest(
                new JsonApiRequest(
                    baseUrl: $options['baseUrl'] ?? $this->client->options['baseUrl'] ?? Environments::UnitedStates->value,
                    path: "api/v1/donors/{$id}",
                    method: HttpMethod::GET,
                ),
                $options,
            );
            $statusCode = $response->getStatusCode();
            if ($statusCode >= 200 && $statusCode < 400) {
                $json = $response->getBody()->getContents();
                if (empty($json)) {
                    return null;
                }
                return DonorDetailV1::fromJson($json);
            }
        } catch (JsonException $e) {
            throw new AwardspringException(message: "Failed to deserialize response: {$e->getMessage()}", previous: $e);
        } catch (ClientExceptionInterface $e) {
            throw new AwardspringException(message: $e->getMessage(), previous: $e);
        }
        throw new AwardspringApiException(
            message: 'API request failed',
            statusCode: $statusCode,
            body: $response->getBody()->getContents(),
        );
    }

    /**
     * Only the fields present in the request body change; anything omitted is left alone. Send
     * `dry_run` to validate the request without saving it. `id` takes any donor
     * `GET /api/v1/donors/{id}` returns, an archived one included; the donor stays archived.
     *
     * <b>Administrator accounts:</b> a program with an API key never changes an account that holds an
     * administrator role. A donor who is also an administrator returns `400 validation_failed` naming
     * `id`, and nothing is saved; change them in AwardSpring instead.
     *
     * Example:
     * ```php
     * $client->donors->update(
     *     'id',
     *     new UpdateDonorV1Request([]),
     * );
     * ```
     *
     * @param string $id
     * @param UpdateDonorV1Request $request
     * @param ?array{
     *   baseUrl?: string,
     *   maxRetries?: int,
     *   timeout?: float,
     *   headers?: array<string, string>,
     *   queryParameters?: array<string, mixed>,
     *   bodyProperties?: array<string, mixed>,
     * } $options
     * @return ?DonorV1
     * @throws AwardspringException
     * @throws AwardspringApiException
     */
    public function update(string $id, UpdateDonorV1Request $request = new UpdateDonorV1Request(), ?array $options = null): ?DonorV1
    {
        $options = array_merge($this->options, $options ?? []);
        try {
            $response = $this->client->sendRequest(
                new JsonApiRequest(
                    baseUrl: $options['baseUrl'] ?? $this->client->options['baseUrl'] ?? Environments::UnitedStates->value,
                    path: "api/v1/donors/{$id}",
                    method: HttpMethod::PUT,
                    body: $request,
                ),
                $options,
            );
            $statusCode = $response->getStatusCode();
            if ($statusCode >= 200 && $statusCode < 400) {
                $json = $response->getBody()->getContents();
                if (empty($json)) {
                    return null;
                }
                return DonorV1::fromJson($json);
            }
        } catch (JsonException $e) {
            throw new AwardspringException(message: "Failed to deserialize response: {$e->getMessage()}", previous: $e);
        } catch (ClientExceptionInterface $e) {
            throw new AwardspringException(message: $e->getMessage(), previous: $e);
        }
        throw new AwardspringApiException(
            message: 'API request failed',
            statusCode: $statusCode,
            body: $response->getBody()->getContents(),
        );
    }

    /**
     * Replaces the donor's free-text notes. `id` is the donor's stored ID, and takes any donor
     * `GET /api/v1/donors/{id}` returns, an archived one included. Returns
     * `404 donor_not_found` naming `id` if no such donor belongs to this institution.
     *
     * <b>Administrator accounts:</b> a program with an API key never changes an account that holds an
     * administrator role. A donor who is also an administrator returns `400 validation_failed` naming
     * `id`, and the notes are not saved; change them in AwardSpring instead.
     *
     * Example:
     * ```php
     * $client->donors->updateNotes(
     *     'id',
     *     new UpdateDonorNotesV1Request([]),
     * );
     * ```
     *
     * @param string $id
     * @param UpdateDonorNotesV1Request $request
     * @param ?array{
     *   baseUrl?: string,
     *   maxRetries?: int,
     *   timeout?: float,
     *   headers?: array<string, string>,
     *   queryParameters?: array<string, mixed>,
     *   bodyProperties?: array<string, mixed>,
     * } $options
     * @return ?DonorNotesV1
     * @throws AwardspringException
     * @throws AwardspringApiException
     */
    public function updateNotes(string $id, UpdateDonorNotesV1Request $request = new UpdateDonorNotesV1Request(), ?array $options = null): ?DonorNotesV1
    {
        $options = array_merge($this->options, $options ?? []);
        try {
            $response = $this->client->sendRequest(
                new JsonApiRequest(
                    baseUrl: $options['baseUrl'] ?? $this->client->options['baseUrl'] ?? Environments::UnitedStates->value,
                    path: "api/v1/donors/{$id}/notes",
                    method: HttpMethod::PUT,
                    body: $request,
                ),
                $options,
            );
            $statusCode = $response->getStatusCode();
            if ($statusCode >= 200 && $statusCode < 400) {
                $json = $response->getBody()->getContents();
                if (empty($json)) {
                    return null;
                }
                return DonorNotesV1::fromJson($json);
            }
        } catch (JsonException $e) {
            throw new AwardspringException(message: "Failed to deserialize response: {$e->getMessage()}", previous: $e);
        } catch (ClientExceptionInterface $e) {
            throw new AwardspringException(message: $e->getMessage(), previous: $e);
        }
        throw new AwardspringApiException(
            message: 'API request failed',
            statusCode: $statusCode,
            body: $response->getBody()->getContents(),
        );
    }

    /**
     * Returns one page at a time. With no `sort` and no `q`, donors are listed by
     * `id`, lowest first.
     *
     *
     * Sort with `sort`. `name` lists donors A to Z, ignoring case: an organization by its
     * organization name, and a person by last name, then first name. A person with no last name
     * is listed by first name, and a donor with no name at all comes last. `last_contacted`
     * lists the most recently contacted donors first, by `last_contacted_at`; donors who
     * have never been contacted come last. `longest_since_contact` is the other way round: donors
     * never contacted first, then the oldest contact. Donors who tie are listed by `id`: lowest
     * first for `name` and `longest_since_contact`, highest first for `last_contacted`.
     *
     *
     * Narrow the list with `level` to donors at one or more levels, for example
     * `level=major,principal`. A donor's level comes from their giving, so a request made as
     * a signed-in user who may not see donors' giving returns `403 permission_denied` when
     * it filters by level. Sorting is open to everyone who can list donors.
     *
     *
     * Narrow the list with `giving_status` to donors with one or more giving statuses, for
     * example `giving_status=recurring,new`. `Recurring`: the donor has a recurring gift
     * that is still running (not cancelled or stopped, and not past its end date) and at least one
     * payment from it has gone through. `New`: the donor's first gift was in the last 12
     * months. `Lapsed`: the donor's last gift was more than 12 months ago. The 12 months count
     * back from now, not from the start of a fiscal year. A donor who fits more than one takes the
     * first of `Recurring`, `New`, `Lapsed`. A donor who has never given has no giving
     * status, and neither does one whose first gift was more than 12 months ago and who has given
     * again within the last 12 months. With `level` as well, a donor must match both. A
     * request made as a signed-in user who may not see donors' giving returns
     * `403 permission_denied` when it filters by giving status.
     *
     *
     * Search with `q` (at least `2` characters). Every word you type must match the
     * start of a word in the donor's first name, last name or organization, or the start of
     * their email address, so `jane smi` finds Jane Smith. Matches inside a word are not
     * found: `co` finds `Coworking` but not `Waco`. Where digits and letters
     * touch, a word is also split there, so `25co` finds `25N Coworking`.
     *
     *
     * Without `sort`, search results come in three groups: first, donors whose full name or
     * organization starts with the search (for a search with no spaces, compared with the spaces
     * taken out, so `janesmith` finds Jane Smith); next, donors where every word starts a
     * word in their name or organization; last, donors matched only through their email address.
     * Within a group, donors are listed by `id`, lowest first, so the order holds from one
     * page to the next. With `sort`, the search only decides which donors are listed and
     * the sort decides their order.
     *
     * `sort`, `level`, `giving_status` and `q` work together. Send the same ones
     * on every page: a cursor sent with a different `sort`, `level`, `giving_status`
     * or `q` returns `400 invalid_cursor`. A search shorter than `2` characters, or an
     * unknown `level`, `giving_status` or `sort`, returns `400 validation_failed`
     * with `param` set to the parameter at fault.
     *
     *
     * To keep a copy in sync, send `changed_since`, a time in UTC epoch seconds. The list then holds
     * only the donors whose stored details changed at or after that time, in the order they changed,
     * oldest change first, by `last_changed_at`. A new gift does not count as a change to its donor.
     * Archiving or restoring a donor counts as a change: an archived donor stays in the list with
     * `archived: true`, including one who is a donor only through an extra Donor role, and comes back
     * with `archived: false` once restored. A donor removed since that time is listed too, at the time it was removed, with only
     * `object`, `id`, `deleted: true` and `removed_at`. That includes a person who stops
     * being a donor but keeps their account, for example when their Donor role is removed; their gifts are
     * not removed with it. If that person becomes a donor again, the same `id` is listed again as a
     * change, after its removal. A removed donor keeps no
     * giving, so a list narrowed by `level` or `giving_status` leaves removals out: keep one
     * sync with no other filter to learn of them. Every page carries `complete_up_to`, the same on
     * every page of one walk; start the next sync from it. A donor may appear in two syncs in a row, but
     * none is missed.
     *
     * `changed_since` works with `level` and `giving_status`. With `sort` or
     * `q` it returns `400 validation_failed` with `param` naming the parameter it cannot
     * be combined with. A time in the future, or anything but a whole number of seconds, returns
     * `400 validation_failed` with `param` set to `changed_since`. A cursor sent with a
     * different `changed_since` returns `400 invalid_cursor`.
     *
     *
     * Assistant capability: `springiq_list_donors`. Required permission: Donors (applies when called on behalf of a signed-in person).
     *
     * @param ListDonorsRequest $request
     * @param ?array{
     *   baseUrl?: string,
     *   maxRetries?: int,
     *   timeout?: float,
     *   headers?: array<string, string>,
     *   queryParameters?: array<string, mixed>,
     *   bodyProperties?: array<string, mixed>,
     * } $options
     * @return ?DonorListItemV1ListResponse
     * @throws AwardspringException
     * @throws AwardspringApiException
     */
    private function _list(ListDonorsRequest $request = new ListDonorsRequest(), ?array $options = null): ?DonorListItemV1ListResponse
    {
        $options = array_merge($this->options, $options ?? []);
        $query = [];
        if ($request->q != null) {
            $query['q'] = $request->q;
        }
        if ($request->level != null) {
            $query['level'] = $request->level;
        }
        if ($request->givingStatus != null) {
            $query['giving_status'] = $request->givingStatus;
        }
        if ($request->sort != null) {
            $query['sort'] = $request->sort;
        }
        if ($request->changedSince != null) {
            $query['changed_since'] = $request->changedSince;
        }
        if ($request->limit != null) {
            $query['limit'] = $request->limit;
        }
        if ($request->startingAfter != null) {
            $query['starting_after'] = $request->startingAfter;
        }
        if ($request->endingBefore != null) {
            $query['ending_before'] = $request->endingBefore;
        }
        if ($request->include != null) {
            $query['include'] = $request->include;
        }
        try {
            $response = $this->client->sendRequest(
                new JsonApiRequest(
                    baseUrl: $options['baseUrl'] ?? $this->client->options['baseUrl'] ?? Environments::UnitedStates->value,
                    path: "api/v1/donors",
                    method: HttpMethod::GET,
                    query: $query,
                ),
                $options,
            );
            $statusCode = $response->getStatusCode();
            if ($statusCode >= 200 && $statusCode < 400) {
                $json = $response->getBody()->getContents();
                if (empty($json)) {
                    return null;
                }
                return DonorListItemV1ListResponse::fromJson($json);
            }
        } catch (JsonException $e) {
            throw new AwardspringException(message: "Failed to deserialize response: {$e->getMessage()}", previous: $e);
        } catch (ClientExceptionInterface $e) {
            throw new AwardspringException(message: $e->getMessage(), previous: $e);
        }
        throw new AwardspringApiException(
            message: 'API request failed',
            statusCode: $statusCode,
            body: $response->getBody()->getContents(),
        );
    }
}
