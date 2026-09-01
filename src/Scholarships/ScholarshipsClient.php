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
     * Example:
     * ```php
     * $client->scholarships->create(
     *     new CreateScholarshipV1Request([
     *         'awardCycleId' => 1,
     *     ]),
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
     * $client->scholarships->listAvailableDollars(
     *     new ListAvailableDollarsScholarshipsRequest([
     *         'awardCycleId' => 1,
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
     * Example:
     * ```php
     * $client->scholarships->getAvailableDollars(
     *     1,
     *     new GetAvailableDollarsScholarshipsRequest([
     *         'awardCycleId' => 1,
     *     ]),
     * );
     * ```
     *
     * @param int $scholarshipId
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
    public function getAvailableDollars(int $scholarshipId, GetAvailableDollarsScholarshipsRequest $request, ?array $options = null): ?ScholarshipAvailableDollarsV1
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
     * Example:
     * ```php
     * $client->scholarships->listAwardedStudents(
     *     new ListAwardedStudentsScholarshipsRequest([
     *         'awardCycleId' => 1,
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
