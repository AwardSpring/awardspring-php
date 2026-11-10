<?php

namespace Awardspring\ReviewerAssignments;

use Psr\Http\Client\ClientInterface;
use Awardspring\Core\Client\RawClient;
use Awardspring\ReviewerAssignments\Requests\ListReviewerAssignmentsRequest;
use Awardspring\Types\ReviewerAssignmentV1ListResponse;
use Awardspring\Exceptions\AwardspringException;
use Awardspring\Exceptions\AwardspringApiException;
use Awardspring\Core\Json\JsonApiRequest;
use Awardspring\Environments;
use Awardspring\Core\Client\HttpMethod;
use JsonException;
use Psr\Http\Client\ClientExceptionInterface;
use Awardspring\ReviewerAssignments\Requests\ChangeReviewerAssignmentsV1Request;
use Awardspring\Types\ChangeReviewerAssignmentsV1Result;
use Awardspring\Types\ReviewerAssignmentJobV1;
use Awardspring\ReviewerAssignments\Requests\ListJobRowsReviewerAssignmentsRequest;
use Awardspring\Types\ReviewerAssignmentJobRowV1ListResponse;

class ReviewerAssignmentsClient
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
     * Returns one row per pair, as the Reviewer Assignment tab shows them, in the standard list envelope. Each row
     * has the group's name and award cycle, the panel number on a group with two panels, and the reviewer and the
     * applicant with name, email and stored ID. Rows are ordered by panel, then reviewer, then applicant.
     *
     *
     * Name the group by `review_group_id`, its stored ID, or by `review_group_name`, optionally with
     * `award_cycle_id`; leaving both out returns `400 validation_failed`. A group that does not exist at
     * your institution, including one from another institution, returns `404 review_group_not_found`, and so
     * does a name that fits no group. A name that fits more than one group returns `400 validation_failed`
     * naming each. Leave `panel` out to list every panel; send `panel` to list one. A panel the group
     * does not have returns `404 review_group_panel_not_found`. Send the same filters on every page: they are
     * recorded in the cursor, and a page request whose filters do not match the cursor's is refused.
     *
     *
     * Assistant capability: `springiq_list_reviewer_assignments`.
     *
     * Example:
     * ```php
     * $client->reviewerAssignments->list(
     *     new ListReviewerAssignmentsRequest([]),
     * );
     * ```
     *
     * @param ListReviewerAssignmentsRequest $request
     * @param ?array{
     *   baseUrl?: string,
     *   maxRetries?: int,
     *   timeout?: float,
     *   headers?: array<string, string>,
     *   queryParameters?: array<string, mixed>,
     *   bodyProperties?: array<string, mixed>,
     * } $options
     * @return ?ReviewerAssignmentV1ListResponse
     * @throws AwardspringException
     * @throws AwardspringApiException
     */
    public function list(ListReviewerAssignmentsRequest $request = new ListReviewerAssignmentsRequest(), ?array $options = null): ?ReviewerAssignmentV1ListResponse
    {
        $options = array_merge($this->options, $options ?? []);
        $query = [];
        if ($request->reviewGroupId != null) {
            $query['review_group_id'] = $request->reviewGroupId;
        }
        if ($request->reviewGroupName != null) {
            $query['review_group_name'] = $request->reviewGroupName;
        }
        if ($request->awardCycleId != null) {
            $query['award_cycle_id'] = $request->awardCycleId;
        }
        if ($request->panel != null) {
            $query['panel'] = $request->panel;
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
                    path: "api/v1/reviewer-assignments",
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
                return ReviewerAssignmentV1ListResponse::fromJson($json);
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
     * Changes the pairs on one panel of a review group, with the same rules as the Reviewer Assignment tab.
     * `review_group_id` in the body is required and is the review group's stored ID. Name each person by
     * email or stored ID. Set `mode` to `add`, `remove` or `replace`; `replace` makes the
     * panel hold exactly the pairs you send, removing the others.
     *
     * <b>Checks on every row.</b> The reviewer must already be a reviewer in the group. The applicant must have
     * applied to a scholarship in the group, and on panel 2 must have been moved up to it. A reviewer is never
     * given their own application. A pair listed twice, or a person who matches no active account or more than
     * one, is turned down. A `replace` that would remove a pair whose review is started or finished turns
     * that pair down unless `allow_removing_started_reviews` is `true`; removing a pair also removes
     * that reviewer's scores for it, as Unassign does on the tab. Only a pair the tab shows is already in place
     * or there to remove: adding a pair the tab does not show is turned down, and removing one leaves it as it
     * is and counts it as `unchanged`.
     *
     * <b>All or nothing.</b> If any row is turned down, nothing changes and the answer is
     * `400 assignment_rows_rejected`, whose `details` list every row with its reason. Rows that pass
     * show `not_applied`. With `apply_good_rows_only` set to `true`, the rows that pass are
     * applied and each turned-down row is listed in the `200`. A `replace` is the exception: one row
     * whose reviewer or applicant matches no active account, or more than one, refuses the whole request with
     * `400 assignment_rows_rejected` even with `apply_good_rows_only`, because that row could name a
     * pair the panel holds and a `replace` removes every pair it does not list.
     *
     * <b>Large requests.</b> A request with more than 500 rows, or more than 500 pairs to change, is accepted at
     * once with `202` and a `reviewer_assignment_job`, which names the review group and its award
     * cycle as every answer does. Read `status_url` to follow it. Under
     * all-or-nothing, a pair that becomes invalid while the job runs stops the job: batches already written
     * stay written, and the job says so. At most 20,000 rows are accepted in one request, and a request may
     * cover at most 20,000 pairs counting the pairs a `replace` removes because the request leaves them out;
     * a larger one is refused with `400 validation_failed`.
     *
     * <b>The panel's assignment choice is never changed.</b> Every answer shows it as `assignment_choice`.
     * When it is `all` or `random`, the answer carries a `warning`: applying that choice again in
     * AwardSpring would replace the pairs you loaded.
     *
     *
     * Each pair added is recorded as added by your API key. Send `dry_run` to check the request without
     * changing anything, and an `Idempotency-Key` header so a retry returns the original answer; for a
     * background job, the same job. Sending the same request again also ends in the same pairs.
     *
     * Example:
     * ```php
     * $client->reviewerAssignments->change(
     *     new ChangeReviewerAssignmentsV1Request([]),
     * );
     * ```
     *
     * @param ChangeReviewerAssignmentsV1Request $request
     * @param ?array{
     *   baseUrl?: string,
     *   maxRetries?: int,
     *   timeout?: float,
     *   headers?: array<string, string>,
     *   queryParameters?: array<string, mixed>,
     *   bodyProperties?: array<string, mixed>,
     * } $options
     * @return ?ChangeReviewerAssignmentsV1Result
     * @throws AwardspringException
     * @throws AwardspringApiException
     */
    public function change(ChangeReviewerAssignmentsV1Request $request = new ChangeReviewerAssignmentsV1Request(), ?array $options = null): ?ChangeReviewerAssignmentsV1Result
    {
        $options = array_merge($this->options, $options ?? []);
        try {
            $response = $this->client->sendRequest(
                new JsonApiRequest(
                    baseUrl: $options['baseUrl'] ?? $this->client->options['baseUrl'] ?? Environments::UnitedStates->value,
                    path: "api/v1/reviewer-assignments",
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
                return ChangeReviewerAssignmentsV1Result::fromJson($json);
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
     * Shows how a background request is going: `status`, how many of its rows are done, and the counts so
     * far. Like every answer about pairs, it names the review group and its award cycle by stored ID and name.
     * The job carries totals only; read `rows_url` for each row's result. `completed` means every
     * row was applied. A job that stopped early, or has any turned-down or failed row, including rows turned down
     * when the request was accepted, is `partially_completed` and says why in `notices`. A job that
     * applied nothing because it could not run, or because every row it reached was turned down or failed, is
     * `failed`. A job code that names no job at your institution, including another institution's, returns
     * `404 assignment_job_not_found`.
     *
     * Example:
     * ```php
     * $client->reviewerAssignments->getJob(
     *     'job_id',
     * );
     * ```
     *
     * @param string $jobId
     * @param ?array{
     *   baseUrl?: string,
     *   maxRetries?: int,
     *   timeout?: float,
     *   headers?: array<string, string>,
     *   queryParameters?: array<string, mixed>,
     *   bodyProperties?: array<string, mixed>,
     * } $options
     * @return ?ReviewerAssignmentJobV1
     * @throws AwardspringException
     * @throws AwardspringApiException
     */
    public function getJob(string $jobId, ?array $options = null): ?ReviewerAssignmentJobV1
    {
        $options = array_merge($this->options, $options ?? []);
        try {
            $response = $this->client->sendRequest(
                new JsonApiRequest(
                    baseUrl: $options['baseUrl'] ?? $this->client->options['baseUrl'] ?? Environments::UnitedStates->value,
                    path: "api/v1/reviewer-assignment-jobs/{$jobId}",
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
                return ReviewerAssignmentJobV1::fromJson($json);
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
     * Returns a background request's rows in request order, in the standard list envelope, each with what has
     * happened to it so far: `waiting` until the job reaches it, then `applied`, `rejected` or
     * `failed` with a `message` saying why. A row an all-or-nothing job never reached, because a pair
     * failed its final check, stays `waiting`. When a job stops for any other reason, such as the API key
     * losing its write access or the job being unable to finish, a row it did not change is `rejected`
     * with a `message` saying why, and a row whose pair already stands as asked is `applied`. Pairs a
     * `replace` removes because the request leaves them out come after the
     * request's own rows and have no `index`.
     *
     * `job_id` is required and is the job's code from its `status_url`. A code that names no job at your
     * institution, including another institution's, returns `404 assignment_job_not_found`.
     *
     * Example:
     * ```php
     * $client->reviewerAssignments->listJobRows(
     *     new ListJobRowsReviewerAssignmentsRequest([]),
     * );
     * ```
     *
     * @param ListJobRowsReviewerAssignmentsRequest $request
     * @param ?array{
     *   baseUrl?: string,
     *   maxRetries?: int,
     *   timeout?: float,
     *   headers?: array<string, string>,
     *   queryParameters?: array<string, mixed>,
     *   bodyProperties?: array<string, mixed>,
     * } $options
     * @return ?ReviewerAssignmentJobRowV1ListResponse
     * @throws AwardspringException
     * @throws AwardspringApiException
     */
    public function listJobRows(ListJobRowsReviewerAssignmentsRequest $request = new ListJobRowsReviewerAssignmentsRequest(), ?array $options = null): ?ReviewerAssignmentJobRowV1ListResponse
    {
        $options = array_merge($this->options, $options ?? []);
        $query = [];
        if ($request->jobId != null) {
            $query['job_id'] = $request->jobId;
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
                    path: "api/v1/reviewer-assignment-job-rows",
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
                return ReviewerAssignmentJobRowV1ListResponse::fromJson($json);
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
