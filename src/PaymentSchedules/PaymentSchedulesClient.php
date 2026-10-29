<?php

namespace Awardspring\PaymentSchedules;

use Psr\Http\Client\ClientInterface;
use Awardspring\Core\Client\RawClient;
use Awardspring\PaymentSchedules\Requests\ListPaymentSchedulesRequest;
use Awardspring\Types\PaymentScheduleV1ListResponse;
use Awardspring\Exceptions\AwardspringException;
use Awardspring\Exceptions\AwardspringApiException;
use Awardspring\Core\Json\JsonApiRequest;
use Awardspring\Environments;
use Awardspring\Core\Client\HttpMethod;
use JsonException;
use Psr\Http\Client\ClientExceptionInterface;

class PaymentSchedulesClient
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
     * Returns the payment schedules belonging to the institution your API key is issued for, in the list
     * envelope (`object: "list"`, `has_more`, `next_cursor`, `previous_cursor`,
     * `data`). Page forward by passing the returned `next_cursor` as `starting_after`;
     * the last page has `next_cursor: null`. `limit` defaults to 25 and is capped at 100.
     * Results are ordered by payment-schedule id ascending; sort by `payment_date` to get the
     * order `payment_schedule_ids` expects. Filter with `?award_cycle_id=` to list only the
     * schedules configured on that award cycle; an award cycle the institution does not have returns
     * `404 award_cycle_not_found`.
     *
     * Assistant capability: `springiq_list_payment_schedules`.
     *
     * Example:
     * ```php
     * $client->paymentSchedules->list(
     *     new ListPaymentSchedulesRequest([]),
     * );
     * ```
     *
     * @param ListPaymentSchedulesRequest $request
     * @param ?array{
     *   baseUrl?: string,
     *   maxRetries?: int,
     *   timeout?: float,
     *   headers?: array<string, string>,
     *   queryParameters?: array<string, mixed>,
     *   bodyProperties?: array<string, mixed>,
     * } $options
     * @return ?PaymentScheduleV1ListResponse
     * @throws AwardspringException
     * @throws AwardspringApiException
     */
    public function list(ListPaymentSchedulesRequest $request = new ListPaymentSchedulesRequest(), ?array $options = null): ?PaymentScheduleV1ListResponse
    {
        $options = array_merge($this->options, $options ?? []);
        $query = [];
        if ($request->awardCycleId != null) {
            $query['award_cycle_id'] = $request->awardCycleId;
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
                    path: "api/v1/payment-schedules",
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
                return PaymentScheduleV1ListResponse::fromJson($json);
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
