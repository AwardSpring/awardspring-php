<?php

namespace Awardspring\People;

use Psr\Http\Client\ClientInterface;
use Awardspring\Core\Client\RawClient;
use Awardspring\Types\PersonV1;
use Awardspring\Exceptions\AwardspringException;
use Awardspring\Exceptions\AwardspringApiException;
use Awardspring\Core\Json\JsonApiRequest;
use Awardspring\Environments;
use Awardspring\Core\Client\HttpMethod;
use JsonException;
use Psr\Http\Client\ClientExceptionInterface;
use Awardspring\People\Requests\ListPeopleRequest;
use Awardspring\Types\PersonV1ListResponse;

class PeopleClient
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
     * Archives the account of a student or reviewer at the institution your API key is issued for. The person
     * cannot sign in until the account is restored; their applications, reviews and everything else are kept.
     * An API key can archive students and reviewers only: a person who holds any other role, such as an
     * administrator or a donor, returns `400 validation_failed`, and is archived in AwardSpring instead.
     * Archiving a person who is already archived changes nothing and returns the person. The archive is
     * recorded under your API key. An `id` that names no person at the institution returns
     * `404 person_not_found`, naming `id` in `param`.
     *
     * Example:
     * ```php
     * $client->people->archive(
     *     'id',
     * );
     * ```
     *
     * @param string $id The person's id, as `GET /api/v1/people` returns it.
     * @param ?array{
     *   baseUrl?: string,
     *   maxRetries?: int,
     *   timeout?: float,
     *   headers?: array<string, string>,
     *   queryParameters?: array<string, mixed>,
     *   bodyProperties?: array<string, mixed>,
     * } $options
     * @return ?PersonV1
     * @throws AwardspringException
     * @throws AwardspringApiException
     */
    public function archive(string $id, ?array $options = null): ?PersonV1
    {
        $options = array_merge($this->options, $options ?? []);
        try {
            $response = $this->client->sendRequest(
                new JsonApiRequest(
                    baseUrl: $options['baseUrl'] ?? $this->client->options['baseUrl'] ?? Environments::UnitedStates->value,
                    path: "api/v1/people/{$id}/archive",
                    method: HttpMethod::POST,
                ),
                $options,
            );
            $statusCode = $response->getStatusCode();
            if ($statusCode >= 200 && $statusCode < 400) {
                $json = $response->getBody()->getContents();
                if (empty($json)) {
                    return null;
                }
                return PersonV1::fromJson($json);
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
     * Restores the archived account of a student or reviewer at the institution your API key is issued for, so
     * the person can sign in again. An API key can restore students and reviewers only: a person who holds any
     * other role returns `400 validation_failed`, and is restored in AwardSpring instead. Restoring a person
     * who is not archived changes nothing and returns the person. An `id` that names no person at the
     * institution returns `404 person_not_found`, naming `id` in `param`.
     *
     * Example:
     * ```php
     * $client->people->restore(
     *     'id',
     * );
     * ```
     *
     * @param string $id The person's id, as `GET /api/v1/people` returns it.
     * @param ?array{
     *   baseUrl?: string,
     *   maxRetries?: int,
     *   timeout?: float,
     *   headers?: array<string, string>,
     *   queryParameters?: array<string, mixed>,
     *   bodyProperties?: array<string, mixed>,
     * } $options
     * @return ?PersonV1
     * @throws AwardspringException
     * @throws AwardspringApiException
     */
    public function restore(string $id, ?array $options = null): ?PersonV1
    {
        $options = array_merge($this->options, $options ?? []);
        try {
            $response = $this->client->sendRequest(
                new JsonApiRequest(
                    baseUrl: $options['baseUrl'] ?? $this->client->options['baseUrl'] ?? Environments::UnitedStates->value,
                    path: "api/v1/people/{$id}/restore",
                    method: HttpMethod::POST,
                ),
                $options,
            );
            $statusCode = $response->getStatusCode();
            if ($statusCode >= 200 && $statusCode < 400) {
                $json = $response->getBody()->getContents();
                if (empty($json)) {
                    return null;
                }
                return PersonV1::fromJson($json);
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
     * Returns the people the institution's Users list shows, for the institution your API key is issued
     * for, in the standard list envelope (`object: "list"`, `has_more`, `next_cursor`,
     * `previous_cursor`, `data`). Each person carries their roles, the departments an
     * administrator is limited to, whether the account is archived, and an administrator's permissions.
     * Page forward by passing the returned `next_cursor` as `starting_after`. `limit`
     * defaults to 25 and is capped at 100. Results are ordered by id ascending.
     *
     *
     * Filter with `?role=` (`administrators`, `reviewers`, `students` or
     * `donors`), `?q=` (part of a name, the start of an email address or student number, or an
     * exact id) and `?status=` (`all`, `active` or `archived`; archived accounts are
     * listed by default, as on the Users list).
     *
     * Example:
     * ```php
     * $client->people->list(
     *     new ListPeopleRequest([]),
     * );
     * ```
     *
     * @param ListPeopleRequest $request
     * @param ?array{
     *   baseUrl?: string,
     *   maxRetries?: int,
     *   timeout?: float,
     *   headers?: array<string, string>,
     *   queryParameters?: array<string, mixed>,
     *   bodyProperties?: array<string, mixed>,
     * } $options
     * @return ?PersonV1ListResponse
     * @throws AwardspringException
     * @throws AwardspringApiException
     */
    public function list(ListPeopleRequest $request = new ListPeopleRequest(), ?array $options = null): ?PersonV1ListResponse
    {
        $options = array_merge($this->options, $options ?? []);
        $query = [];
        if ($request->role != null) {
            $query['role'] = $request->role;
        }
        if ($request->q != null) {
            $query['q'] = $request->q;
        }
        if ($request->status != null) {
            $query['status'] = $request->status;
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
                    path: "api/v1/people",
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
                return PersonV1ListResponse::fromJson($json);
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
