<?php

namespace Awardspring\DonorActivities;

use Psr\Http\Client\ClientInterface;
use Awardspring\Core\Client\RawClient;
use Awardspring\DonorActivities\Requests\ListDonorActivitiesRequest;
use Awardspring\Types\DonorActivityV1ListResponse;
use Awardspring\Exceptions\AwardspringException;
use Awardspring\Exceptions\AwardspringApiException;
use Awardspring\Core\Json\JsonApiRequest;
use Awardspring\Environments;
use Awardspring\Core\Client\HttpMethod;
use JsonException;
use Psr\Http\Client\ClientExceptionInterface;
use Awardspring\DonorActivities\Requests\CreateDonorActivityV1Request;
use Awardspring\Types\CreateDonorActivityV1Response;
use Awardspring\DonorActivities\Requests\GetDonorActivitiesRequest;
use Awardspring\Types\DonorActivityV1;

class DonorActivitiesClient
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
     * Example:
     * ```php
     * $client->donorActivities->list(
     *     1,
     *     new ListDonorActivitiesRequest([]),
     * );
     * ```
     *
     * @param int $donorId
     * @param ListDonorActivitiesRequest $request
     * @param ?array{
     *   baseUrl?: string,
     *   maxRetries?: int,
     *   timeout?: float,
     *   headers?: array<string, string>,
     *   queryParameters?: array<string, mixed>,
     *   bodyProperties?: array<string, mixed>,
     * } $options
     * @return ?DonorActivityV1ListResponse
     * @throws AwardspringException
     * @throws AwardspringApiException
     */
    public function list(int $donorId, ListDonorActivitiesRequest $request = new ListDonorActivitiesRequest(), ?array $options = null): ?DonorActivityV1ListResponse
    {
        $options = array_merge($this->options, $options ?? []);
        $query = [];
        if ($request->q != null) {
            $query['q'] = $request->q;
        }
        if ($request->activityType != null) {
            $query['activity_type'] = $request->activityType;
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
                    path: "api/v1/donors/{$donorId}/activities",
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
                return DonorActivityV1ListResponse::fromJson($json);
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
     * Records that an interaction with a donor took place — a call you just finished, a meeting, an
     * email, a note, or a verbal pledge. Errors come back with a stable `code`, a readable
     * `message`, and per-field `details`, so a caller can tell what to correct.
     *
     *
     * Supported activity types: `LoggedEmail`, `LoggedPhone`, `LoggedMeeting`, `LoggedNote`,
     * `LoggedPledge`. Gifts are recorded through the Gifts endpoints instead.
     *
     *
     * A typical use is working through a call list and logging each conversation as it ends, with the
     * summary and any follow-up owner attached.
     *
     *
     * Set `dry_run=true` (query param or `Dry-Run: true` header) to validate without persisting.
     *
     * <b>Idempotency:</b> the request supports an optional `Idempotency-Key` header (any printable-ASCII
     * string, 1–255 chars — UUIDs work well). When supplied, the same key + same request body within 24 hours
     * returns the original response verbatim without creating a duplicate activity. The same key with a
     * different body returns `422 idempotency_key_request_mismatch`; a concurrent retry while the first
     * call is still executing returns `409 idempotency_key_in_flight`. Only 2xx responses are cached —
     * 4xx/5xx leave the key available for retry with a corrected payload.
     *
     * Example:
     * ```php
     * $client->donorActivities->create(
     *     1,
     *     new CreateDonorActivityV1Request([]),
     * );
     * ```
     *
     * @param int $donorId AwardSpring donor (or donor-organization) user ID to attach the activity to.
     * @param CreateDonorActivityV1Request $request
     * @param ?array{
     *   baseUrl?: string,
     *   maxRetries?: int,
     *   timeout?: float,
     *   headers?: array<string, string>,
     *   queryParameters?: array<string, mixed>,
     *   bodyProperties?: array<string, mixed>,
     * } $options
     * @return ?CreateDonorActivityV1Response
     * @throws AwardspringException
     * @throws AwardspringApiException
     */
    public function create(int $donorId, CreateDonorActivityV1Request $request, ?array $options = null): ?CreateDonorActivityV1Response
    {
        $options = array_merge($this->options, $options ?? []);
        try {
            $response = $this->client->sendRequest(
                new JsonApiRequest(
                    baseUrl: $options['baseUrl'] ?? $this->client->options['baseUrl'] ?? Environments::UnitedStates->value,
                    path: "api/v1/donors/{$donorId}/activities",
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
                return CreateDonorActivityV1Response::fromJson($json);
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
     * $client->donorActivities->get(
     *     1,
     *     1,
     *     new GetDonorActivitiesRequest([]),
     * );
     * ```
     *
     * @param int $donorId
     * @param int $activityId
     * @param GetDonorActivitiesRequest $request
     * @param ?array{
     *   baseUrl?: string,
     *   maxRetries?: int,
     *   timeout?: float,
     *   headers?: array<string, string>,
     *   queryParameters?: array<string, mixed>,
     *   bodyProperties?: array<string, mixed>,
     * } $options
     * @return ?DonorActivityV1
     * @throws AwardspringException
     * @throws AwardspringApiException
     */
    public function get(int $donorId, int $activityId, GetDonorActivitiesRequest $request = new GetDonorActivitiesRequest(), ?array $options = null): ?DonorActivityV1
    {
        $options = array_merge($this->options, $options ?? []);
        $query = [];
        if ($request->source != null) {
            $query['source'] = $request->source;
        }
        try {
            $response = $this->client->sendRequest(
                new JsonApiRequest(
                    baseUrl: $options['baseUrl'] ?? $this->client->options['baseUrl'] ?? Environments::UnitedStates->value,
                    path: "api/v1/donors/{$donorId}/activities/{$activityId}",
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
                return DonorActivityV1::fromJson($json);
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
