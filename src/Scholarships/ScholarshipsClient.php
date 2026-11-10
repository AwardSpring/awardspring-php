<?php

namespace Awardspring\Scholarships;

use Psr\Http\Client\ClientInterface;
use Awardspring\Core\Client\RawClient;
use Awardspring\Scholarships\Requests\ListScholarshipsRequest;
use Awardspring\Types\ScholarshipListItemV1ListResponse;
use Awardspring\Exceptions\AwardspringException;
use Awardspring\Exceptions\AwardspringApiException;
use Awardspring\Core\Json\JsonApiRequest;
use Awardspring\Environments;
use Awardspring\Core\Client\HttpMethod;
use JsonException;
use Psr\Http\Client\ClientExceptionInterface;
use Awardspring\Scholarships\Requests\CreateScholarshipV1Request;
use Awardspring\Types\CreateScholarshipV1Response;
use Awardspring\Types\ScholarshipV1;
use Awardspring\Scholarships\Requests\UpdateScholarshipV1Request;
use Awardspring\Scholarships\Requests\ListAvailableDollarsScholarshipsRequest;
use Awardspring\Types\ScholarshipAvailableDollarsV1ListResponse;
use Awardspring\Scholarships\Requests\GetAvailableDollarsScholarshipsRequest;
use Awardspring\Types\ScholarshipAvailableDollarsV1;
use Awardspring\Scholarships\Requests\ListAwardedStudentsScholarshipsRequest;
use Awardspring\Types\AwardedStudentV1ListResponse;

class ScholarshipsClient
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
     * Returns the scholarships belonging to the institution your API key is issued for, in the list
     * envelope (`object: "list"`, `has_more`, `next_cursor`, `previous_cursor`,
     * `data`). Page forward by passing the returned `next_cursor` as `starting_after`;
     * the last page has `next_cursor: null`. `limit` defaults to 25 and is capped at 100.
     * Results are ordered by scholarship id ascending.
     * Filter with `?q=` (case-insensitive substring match on the scholarship name).
     *
     * Assistant capability: `springiq_list_scholarships`. Required permission: Scholarships (applies when called on behalf of a signed-in person).
     *
     * Example:
     * ```php
     * $client->scholarships->list(
     *     new ListScholarshipsRequest([]),
     * );
     * ```
     *
     * @param ListScholarshipsRequest $request
     * @param ?array{
     *   baseUrl?: string,
     *   maxRetries?: int,
     *   timeout?: float,
     *   headers?: array<string, string>,
     *   queryParameters?: array<string, mixed>,
     *   bodyProperties?: array<string, mixed>,
     * } $options
     * @return ?ScholarshipListItemV1ListResponse
     * @throws AwardspringException
     * @throws AwardspringApiException
     */
    public function list(ListScholarshipsRequest $request = new ListScholarshipsRequest(), ?array $options = null): ?ScholarshipListItemV1ListResponse
    {
        $options = array_merge($this->options, $options ?? []);
        $query = [];
        if ($request->q != null) {
            $query['q'] = $request->q;
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
                    path: "api/v1/scholarships",
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
                return ScholarshipListItemV1ListResponse::fromJson($json);
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
     * Creates a scholarship with its name, description, dates, financial totals, payment schedule,
     * department and donor associations, custom-field answers, and applicant-notification settings.
     * Errors come back with a stable `code`, a readable `message`, and per-field
     * `details`, so a caller can tell what to correct.
     *
     *
     * This endpoint enforces exactly the same rules as creating a scholarship in the AwardSpring
     * admin interface, so anything rejected here would also have been rejected there.
     *
     *
     * A typical call supplies the award's name, dates falling inside the active award cycle, a
     * budget total, and any donor association. The response returns the new
     * `scholarship_id`, which you can use in follow-up requests.
     *
     *
     * Set `dry_run=true` (query param or `Dry-Run: true` header) to validate without persisting.
     *
     * <b>Idempotency:</b> the request supports an optional `Idempotency-Key` header (any
     * printable-ASCII string, 1–255 chars — UUIDs work well). When supplied, the same key plus
     * the same request body within 24 hours returns the original response verbatim without
     * creating a duplicate scholarship. The same key with a different body returns
     * `422 idempotency_key_request_mismatch`; a concurrent retry while the first call is
     * still executing returns `409 idempotency_key_in_flight`. Only 2xx responses are
     * cached — 4xx/5xx leave the key available for retry with a corrected payload.
     *
     *
     * Assistant capability: `springiq_create_scholarship`. Required permission: Scholarships (applies when called on behalf of a signed-in person).
     *
     * Example:
     * ```php
     * $client->scholarships->create(
     *     new CreateScholarshipV1Request([]),
     * );
     * ```
     *
     * @param CreateScholarshipV1Request $request
     * @param ?array{
     *   baseUrl?: string,
     *   maxRetries?: int,
     *   timeout?: float,
     *   headers?: array<string, string>,
     *   queryParameters?: array<string, mixed>,
     *   bodyProperties?: array<string, mixed>,
     * } $options
     * @return ?CreateScholarshipV1Response
     * @throws AwardspringException
     * @throws AwardspringApiException
     */
    public function create(CreateScholarshipV1Request $request, ?array $options = null): ?CreateScholarshipV1Response
    {
        $options = array_merge($this->options, $options ?? []);
        try {
            $response = $this->client->sendRequest(
                new JsonApiRequest(
                    baseUrl: $options['baseUrl'] ?? $this->client->options['baseUrl'] ?? Environments::UnitedStates->value,
                    path: "api/v1/scholarships",
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
                return CreateScholarshipV1Response::fromJson($json);
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
     * Example:
     * ```php
     * $client->scholarships->get(
     *     'scholarshipId',
     * );
     * ```
     *
     * @param string $scholarshipId
     * @param ?array{
     *   baseUrl?: string,
     *   maxRetries?: int,
     *   timeout?: float,
     *   headers?: array<string, string>,
     *   queryParameters?: array<string, mixed>,
     *   bodyProperties?: array<string, mixed>,
     * } $options
     * @return ?ScholarshipV1
     * @throws AwardspringException
     * @throws AwardspringApiException
     */
    public function get(string $scholarshipId, ?array $options = null): ?ScholarshipV1
    {
        $options = array_merge($this->options, $options ?? []);
        try {
            $response = $this->client->sendRequest(
                new JsonApiRequest(
                    baseUrl: $options['baseUrl'] ?? $this->client->options['baseUrl'] ?? Environments::UnitedStates->value,
                    path: "api/v1/scholarships/{$scholarshipId}",
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
                return ScholarshipV1::fromJson($json);
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
     * Changes a scholarship's name, application open and close dates, department, fund, donors, description,
     * award notification wording, custom field values and qualifications (`add_qualifications` and
     * `remove_qualifications`), and returns the updated scholarship in the same shape as getting it. Only the
     * fields in the body change: a field left out, or sent as `null`, stays as it is, and an empty string
     * clears a field only where the scholarship's Details tab lets it be cleared (the department, the fund and a
     * custom field's value).
     *
     *
     * The same rules apply as when an administrator saves the scholarship's Details tab, so anything refused there
     * is refused here. Every field is checked before anything is saved, and the changes are saved together or not
     * at all: a request with one refused field saves nothing, and the error names each refused field in
     * `details`. A field this call does not change (for example `total_amount`) is refused the same way.
     *
     *
     * A date sent without a time is that calendar day in the institution's time zone, and the scholarship keeps
     * the time of day it opens or closes at now. A scholarship that starts taking applications again because of
     * the change has its student matching and auto-apply run shortly after the save, as on the Details tab.
     *
     * `remove_qualifications` and `add_qualifications` name groups by their `group_number` in the
     * scholarship's `qualification_groups` as last read, and the rules are saved by the Qualifications tab's own
     * save, so its checks apply. Qualifications change only while the scholarship is taking applications, and the
     * request is refused if either its stored close date has passed or the `application_end_date` it sends is
     * in the past: reopening a closed scholarship and changing its qualifications in one request is refused, so
     * reopen it first and change the qualifications in a second call. Students are re-checked against
     * changed qualifications shortly after the save. A question behind a qualification always carries its stored
     * ID; a question found without one is a server error (500).
     *
     *
     * A scholarship ID the institution does not have, or that is malformed, returns `404 scholarship_not_found`
     * naming `id`. The call needs an API key with write permission.
     *
     *
     * Set `dry_run=true` (query param or `Dry-Run: true` header) to check the changes and see the
     * scholarship as it would be, without saving anything.
     *
     * <b>Idempotency:</b> the request supports an optional `Idempotency-Key` header (any printable-ASCII
     * string, 1–255 chars). The same key plus the same request body within 24 hours returns the original response
     * without changing the scholarship again. The same key with a different body returns
     * `422 idempotency_key_request_mismatch`; a concurrent retry while the first call is still executing
     * returns `409 idempotency_key_in_flight`. Only 2xx responses are cached.
     *
     * Example:
     * ```php
     * $client->scholarships->update(
     *     'scholarshipId',
     *     new UpdateScholarshipV1Request([]),
     * );
     * ```
     *
     * @param string $scholarshipId The scholarship's stored ID.
     * @param UpdateScholarshipV1Request $request
     * @param ?array{
     *   baseUrl?: string,
     *   maxRetries?: int,
     *   timeout?: float,
     *   headers?: array<string, string>,
     *   queryParameters?: array<string, mixed>,
     *   bodyProperties?: array<string, mixed>,
     * } $options
     * @return ?ScholarshipV1
     * @throws AwardspringException
     * @throws AwardspringApiException
     */
    public function update(string $scholarshipId, UpdateScholarshipV1Request $request = new UpdateScholarshipV1Request(), ?array $options = null): ?ScholarshipV1
    {
        $options = array_merge($this->options, $options ?? []);
        try {
            $response = $this->client->sendRequest(
                new JsonApiRequest(
                    baseUrl: $options['baseUrl'] ?? $this->client->options['baseUrl'] ?? Environments::UnitedStates->value,
                    path: "api/v1/scholarships/{$scholarshipId}",
                    method: HttpMethod::PATCH,
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
                return ScholarshipV1::fromJson($json);
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
     * Assistant capability: `springiq_get_available_scholarship_dollars`. Required permission: Scholarships (applies when called on behalf of a signed-in person).
     *
     * Example:
     * ```php
     * $client->scholarships->listAvailableDollars(
     *     new ListAvailableDollarsScholarshipsRequest([
     *         'awardCycleId' => 'award_cycle_id',
     *     ]),
     * );
     * ```
     *
     * @param ListAvailableDollarsScholarshipsRequest $request
     * @param ?array{
     *   baseUrl?: string,
     *   maxRetries?: int,
     *   timeout?: float,
     *   headers?: array<string, string>,
     *   queryParameters?: array<string, mixed>,
     *   bodyProperties?: array<string, mixed>,
     * } $options
     * @return ?ScholarshipAvailableDollarsV1ListResponse
     * @throws AwardspringException
     * @throws AwardspringApiException
     */
    public function listAvailableDollars(ListAvailableDollarsScholarshipsRequest $request, ?array $options = null): ?ScholarshipAvailableDollarsV1ListResponse
    {
        $options = array_merge($this->options, $options ?? []);
        $query = [];
        $query['award_cycle_id'] = $request->awardCycleId;
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
                    path: "api/v1/scholarships/available-dollars",
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
                return ScholarshipAvailableDollarsV1ListResponse::fromJson($json);
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
     * Assistant capability: `springiq_get_available_scholarship_dollars`. Required permission: Scholarships (applies when called on behalf of a signed-in person).
     *
     * Example:
     * ```php
     * $client->scholarships->getAvailableDollars(
     *     'scholarshipId',
     *     new GetAvailableDollarsScholarshipsRequest([
     *         'awardCycleId' => 'award_cycle_id',
     *     ]),
     * );
     * ```
     *
     * @param string $scholarshipId
     * @param GetAvailableDollarsScholarshipsRequest $request
     * @param ?array{
     *   baseUrl?: string,
     *   maxRetries?: int,
     *   timeout?: float,
     *   headers?: array<string, string>,
     *   queryParameters?: array<string, mixed>,
     *   bodyProperties?: array<string, mixed>,
     * } $options
     * @return ?ScholarshipAvailableDollarsV1
     * @throws AwardspringException
     * @throws AwardspringApiException
     */
    public function getAvailableDollars(string $scholarshipId, GetAvailableDollarsScholarshipsRequest $request, ?array $options = null): ?ScholarshipAvailableDollarsV1
    {
        $options = array_merge($this->options, $options ?? []);
        $query = [];
        $query['award_cycle_id'] = $request->awardCycleId;
        try {
            $response = $this->client->sendRequest(
                new JsonApiRequest(
                    baseUrl: $options['baseUrl'] ?? $this->client->options['baseUrl'] ?? Environments::UnitedStates->value,
                    path: "api/v1/scholarships/{$scholarshipId}/available-dollars",
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
                return ScholarshipAvailableDollarsV1::fromJson($json);
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
     * Assistant capability: `springiq_get_awarded_students`. Required permission: Scholarships (applies when called on behalf of a signed-in person).
     *
     * Example:
     * ```php
     * $client->scholarships->listAwardedStudents(
     *     new ListAwardedStudentsScholarshipsRequest([
     *         'awardCycleId' => 'award_cycle_id',
     *     ]),
     * );
     * ```
     *
     * @param ListAwardedStudentsScholarshipsRequest $request
     * @param ?array{
     *   baseUrl?: string,
     *   maxRetries?: int,
     *   timeout?: float,
     *   headers?: array<string, string>,
     *   queryParameters?: array<string, mixed>,
     *   bodyProperties?: array<string, mixed>,
     * } $options
     * @return ?AwardedStudentV1ListResponse
     * @throws AwardspringException
     * @throws AwardspringApiException
     */
    public function listAwardedStudents(ListAwardedStudentsScholarshipsRequest $request, ?array $options = null): ?AwardedStudentV1ListResponse
    {
        $options = array_merge($this->options, $options ?? []);
        $query = [];
        $query['award_cycle_id'] = $request->awardCycleId;
        if ($request->scholarshipId != null) {
            $query['scholarship_id'] = $request->scholarshipId;
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
                    path: "api/v1/scholarships/awarded-students",
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
                return AwardedStudentV1ListResponse::fromJson($json);
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
