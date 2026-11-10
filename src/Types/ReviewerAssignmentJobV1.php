<?php

namespace Awardspring\Types;

use Awardspring\Core\Json\JsonSerializableType;
use Awardspring\Core\Json\JsonProperty;
use Awardspring\Core\Types\ArrayType;

/**
 * A request too big to answer at once, running in the background. Carries
 * `"object": "reviewer_assignment_job"`. It is the body of the `202` that accepts the request and of
 * every read of `status_url`, and names the review group and its award cycle as every answer does.
 */
class ReviewerAssignmentJobV1 extends JsonSerializableType
{
    /**
     * Stripe-style resource discriminator. Stable, snake_case string naming the resource type.
     * Serialized first so it reads as the leading field of every V1 resource body.
     *
     * @var ?string $object
     */
    #[JsonProperty('object')]
    public ?string $object;

    /**
     * @var ?string $id The job's code, used in `status_url`. It is not a record ID.
     */
    #[JsonProperty('id')]
    public ?string $id;

    /**
     * `queued`, `running`, `completed`, `partially_completed` or `failed`.
     *             `completed` means every row was applied. A job that stopped early, or has any turned-down or failed
     *             row, including rows turned down when the request was accepted, is `partially_completed`. A job that
     *             applied nothing because it could not run, or because every row it reached was turned down or failed, is
     *             `failed`.
     *
     * @var ?string $status
     */
    #[JsonProperty('status')]
    public ?string $status;

    /**
     * @var ?string $reviewGroupId The stored ID of the review group the request changes.
     */
    #[JsonProperty('review_group_id')]
    public ?string $reviewGroupId;

    /**
     * @var ?string $reviewGroupName
     */
    #[JsonProperty('review_group_name')]
    public ?string $reviewGroupName;

    /**
     * @var ?string $awardCycleId The stored ID of the review group's award cycle.
     */
    #[JsonProperty('award_cycle_id')]
    public ?string $awardCycleId;

    /**
     * @var ?string $awardCycleName
     */
    #[JsonProperty('award_cycle_name')]
    public ?string $awardCycleName;

    /**
     * @var ?string $statusUrl The path to read this job again.
     */
    #[JsonProperty('status_url')]
    public ?string $statusUrl;

    /**
     * @var ?int $total How many rows the job has.
     */
    #[JsonProperty('total')]
    public ?int $total;

    /**
     * @var ?int $done Rows finished one way or another: applied, turned down or failed.
     */
    #[JsonProperty('done')]
    public ?int $done;

    /**
     * @var ?int $added
     */
    #[JsonProperty('added')]
    public ?int $added;

    /**
     * @var ?int $removed
     */
    #[JsonProperty('removed')]
    public ?int $removed;

    /**
     * @var ?int $unchanged
     */
    #[JsonProperty('unchanged')]
    public ?int $unchanged;

    /**
     * @var ?int $finishedReviewsRemoved
     */
    #[JsonProperty('finished_reviews_removed')]
    public ?int $finishedReviewsRemoved;

    /**
     * @var ?int $turnedDown
     */
    #[JsonProperty('turned_down')]
    public ?int $turnedDown;

    /**
     * @var ?int $failed
     */
    #[JsonProperty('failed')]
    public ?int $failed;

    /**
     * @var ?array<string> $notices Plain sentences about the run as a whole, such as why it stopped early.
     */
    #[JsonProperty('notices'), ArrayType(['string'])]
    public ?array $notices;

    /**
     * @var ?string $assignmentChoice The panel's assignment choice. Set on the `202` only.
     */
    #[JsonProperty('assignment_choice')]
    public ?string $assignmentChoice;

    /**
     * @var ?string $warning Set on the `202` when `assignment_choice` is `all` or `random`.
     */
    #[JsonProperty('warning')]
    public ?string $warning;

    /**
     * @var ?int $createdAt When the request was accepted, as UTC epoch seconds.
     */
    #[JsonProperty('created_at')]
    public ?int $createdAt;

    /**
     * @var ?int $startedAt
     */
    #[JsonProperty('started_at')]
    public ?int $startedAt;

    /**
     * @var ?int $completedAt
     */
    #[JsonProperty('completed_at')]
    public ?int $completedAt;

    /**
     * The path to read the job's rows a page at a time, each with its result so far. The job itself carries only
     * totals.
     *
     * @var ?string $rowsUrl
     */
    #[JsonProperty('rows_url')]
    public ?string $rowsUrl;

    /**
     * @param array{
     *   object?: ?string,
     *   id?: ?string,
     *   status?: ?string,
     *   reviewGroupId?: ?string,
     *   reviewGroupName?: ?string,
     *   awardCycleId?: ?string,
     *   awardCycleName?: ?string,
     *   statusUrl?: ?string,
     *   total?: ?int,
     *   done?: ?int,
     *   added?: ?int,
     *   removed?: ?int,
     *   unchanged?: ?int,
     *   finishedReviewsRemoved?: ?int,
     *   turnedDown?: ?int,
     *   failed?: ?int,
     *   notices?: ?array<string>,
     *   assignmentChoice?: ?string,
     *   warning?: ?string,
     *   createdAt?: ?int,
     *   startedAt?: ?int,
     *   completedAt?: ?int,
     *   rowsUrl?: ?string,
     * } $values
     */
    public function __construct(
        array $values = [],
    ) {
        $this->object = $values['object'] ?? null;
        $this->id = $values['id'] ?? null;
        $this->status = $values['status'] ?? null;
        $this->reviewGroupId = $values['reviewGroupId'] ?? null;
        $this->reviewGroupName = $values['reviewGroupName'] ?? null;
        $this->awardCycleId = $values['awardCycleId'] ?? null;
        $this->awardCycleName = $values['awardCycleName'] ?? null;
        $this->statusUrl = $values['statusUrl'] ?? null;
        $this->total = $values['total'] ?? null;
        $this->done = $values['done'] ?? null;
        $this->added = $values['added'] ?? null;
        $this->removed = $values['removed'] ?? null;
        $this->unchanged = $values['unchanged'] ?? null;
        $this->finishedReviewsRemoved = $values['finishedReviewsRemoved'] ?? null;
        $this->turnedDown = $values['turnedDown'] ?? null;
        $this->failed = $values['failed'] ?? null;
        $this->notices = $values['notices'] ?? null;
        $this->assignmentChoice = $values['assignmentChoice'] ?? null;
        $this->warning = $values['warning'] ?? null;
        $this->createdAt = $values['createdAt'] ?? null;
        $this->startedAt = $values['startedAt'] ?? null;
        $this->completedAt = $values['completedAt'] ?? null;
        $this->rowsUrl = $values['rowsUrl'] ?? null;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }
}
