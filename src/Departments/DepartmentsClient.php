<?php

namespace Awardspring\Departments;

use Psr\Http\Client\ClientInterface;
use Awardspring\Core\Client\RawClient;
use Awardspring\Departments\Requests\ListDepartmentsRequest;
use Awardspring\Types\DepartmentV1ListResponse;
use Awardspring\Exceptions\AwardspringException;
use Awardspring\Exceptions\AwardspringApiException;
use Awardspring\Core\Json\JsonApiRequest;
use Awardspring\Environments;
use Awardspring\Core\Client\HttpMethod;
use JsonException;
use Psr\Http\Client\ClientExceptionInterface;
use Awardspring\Departments\Requests\CreateDepartmentV1Request;
use Awardspring\Types\DepartmentV1;

class DepartmentsClient
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
     * Returns the departments of the institution your API key is issued for, in the standard list envelope
     * (`object: "list"`, `has_more`, `next_cursor`, `previous_cursor`, `data`),
     * each with how many differently named scholarships and how many administrators belong to it. Page
     * forward by passing the returned `next_cursor` as `starting_after`. `limit` defaults to
     * 25 and is capped at 100. Results are ordered by id ascending. Filter with `?q=`
     * (case-insensitive substring match on the department name).
     *
     * Example:
     * ```php
     * $client->departments->list(
     *     new ListDepartmentsRequest([]),
     * );
     * ```
     *
     * @param ListDepartmentsRequest $request
     * @param ?array{
     *   baseUrl?: string,
     *   maxRetries?: int,
     *   timeout?: float,
     *   headers?: array<string, string>,
     *   queryParameters?: array<string, mixed>,
     *   bodyProperties?: array<string, mixed>,
     * } $options
     * @return ?DepartmentV1ListResponse
     * @throws AwardspringException
     * @throws AwardspringApiException
     */
    public function list(ListDepartmentsRequest $request = new ListDepartmentsRequest(), ?array $options = null): ?DepartmentV1ListResponse
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
                    path: "api/v1/departments",
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
                return DepartmentV1ListResponse::fromJson($json);
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
     * Adds one department, with the same rules as adding a department in the AwardSpring admin interface:
     * the name is required and must not already be used by another department at the institution, ignoring
     * upper and lower case and spaces at either end. A new department starts with the same settings the
     * admin interface gives one. Departments are not available on every plan; on a plan without them this
     * returns `403 permission_required`.
     *
     * <b>Idempotency:</b> the request supports an optional `Idempotency-Key` header (any
     * printable-ASCII string, 1–255 chars — UUIDs work well). When supplied, the same key plus the same
     * request body within 24 hours returns the original response verbatim without creating a duplicate
     * department. The same key with a different body returns `422 idempotency_key_request_mismatch`; a
     * concurrent retry while the first call is still executing returns `409 idempotency_key_in_flight`.
     *
     * Example:
     * ```php
     * $client->departments->create(
     *     new CreateDepartmentV1Request([]),
     * );
     * ```
     *
     * @param CreateDepartmentV1Request $request
     * @param ?array{
     *   baseUrl?: string,
     *   maxRetries?: int,
     *   timeout?: float,
     *   headers?: array<string, string>,
     *   queryParameters?: array<string, mixed>,
     *   bodyProperties?: array<string, mixed>,
     * } $options
     * @return ?DepartmentV1
     * @throws AwardspringException
     * @throws AwardspringApiException
     */
    public function create(CreateDepartmentV1Request $request, ?array $options = null): ?DepartmentV1
    {
        $options = array_merge($this->options, $options ?? []);
        try {
            $response = $this->client->sendRequest(
                new JsonApiRequest(
                    baseUrl: $options['baseUrl'] ?? $this->client->options['baseUrl'] ?? Environments::UnitedStates->value,
                    path: "api/v1/departments",
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
                return DepartmentV1::fromJson($json);
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
