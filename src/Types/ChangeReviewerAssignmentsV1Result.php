<?php

namespace Awardspring\Types;

use Awardspring\Core\Json\JsonSerializableType;
use Awardspring\Core\Json\JsonProperty;
use Awardspring\Core\Types\ArrayType;

/**
 * The result of a request to change reviewer assignments. Carries `"object": "reviewer_assignment_change"`.
 * It is the body of a `200`, and the `details` of a `400 assignment_rows_rejected`.
 */
class ChangeReviewerAssignmentsV1Result extends JsonSerializableType
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
     * @var ?string $reviewGroupId
     */
    #[JsonProperty('review_group_id')]
    public ?string $reviewGroupId;

    /**
     * @var ?string $reviewGroupName
     */
    #[JsonProperty('review_group_name')]
    public ?string $reviewGroupName;

    /**
     * @var ?string $awardCycleId
     */
    #[JsonProperty('award_cycle_id')]
    public ?string $awardCycleId;

    /**
     * @var ?string $awardCycleName
     */
    #[JsonProperty('award_cycle_name')]
    public ?string $awardCycleName;

    /**
     * @var ?int $panel The panel changed. Null on a review group with one panel.
     */
    #[JsonProperty('panel')]
    public ?int $panel;

    /**
     * @var ?string $mode `add`, `remove` or `replace`.
     */
    #[JsonProperty('mode')]
    public ?string $mode;

    /**
     * @var ?bool $applied True when the pairs were written. False for a dry run and for a blocked request.
     */
    #[JsonProperty('applied')]
    public ?bool $applied;

    /**
     * @var ?bool $dryRun
     */
    #[JsonProperty('dry_run')]
    public ?bool $dryRun;

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
     * @var ?int $unchanged Pairs already as the request asked.
     */
    #[JsonProperty('unchanged')]
    public ?int $unchanged;

    /**
     * @var ?int $rejected Rows turned down.
     */
    #[JsonProperty('rejected')]
    public ?int $rejected;

    /**
     * @var ?int $finishedReviewsRemoved How many of the removed pairs had a finished review. Their scores were removed with them.
     */
    #[JsonProperty('finished_reviews_removed')]
    public ?int $finishedReviewsRemoved;

    /**
     * @var ?string $assignmentChoice How the panel picks reviewers: `manual`, `all` or `random`. A request never changes it.
     */
    #[JsonProperty('assignment_choice')]
    public ?string $assignmentChoice;

    /**
     * Set when `assignment_choice` is `all` or `random`: applying that choice again in
     * AwardSpring would replace the pairs loaded here. Null otherwise.
     *
     * @var ?string $warning
     */
    #[JsonProperty('warning')]
    public ?string $warning;

    /**
     * @var ?array<ReviewerAssignmentRowResultV1> $rows
     */
    #[JsonProperty('rows'), ArrayType([ReviewerAssignmentRowResultV1::class])]
    public ?array $rows;

    /**
     * @param array{
     *   object?: ?string,
     *   reviewGroupId?: ?string,
     *   reviewGroupName?: ?string,
     *   awardCycleId?: ?string,
     *   awardCycleName?: ?string,
     *   panel?: ?int,
     *   mode?: ?string,
     *   applied?: ?bool,
     *   dryRun?: ?bool,
     *   added?: ?int,
     *   removed?: ?int,
     *   unchanged?: ?int,
     *   rejected?: ?int,
     *   finishedReviewsRemoved?: ?int,
     *   assignmentChoice?: ?string,
     *   warning?: ?string,
     *   rows?: ?array<ReviewerAssignmentRowResultV1>,
     * } $values
     */
    public function __construct(
        array $values = [],
    ) {
        $this->object = $values['object'] ?? null;
        $this->reviewGroupId = $values['reviewGroupId'] ?? null;
        $this->reviewGroupName = $values['reviewGroupName'] ?? null;
        $this->awardCycleId = $values['awardCycleId'] ?? null;
        $this->awardCycleName = $values['awardCycleName'] ?? null;
        $this->panel = $values['panel'] ?? null;
        $this->mode = $values['mode'] ?? null;
        $this->applied = $values['applied'] ?? null;
        $this->dryRun = $values['dryRun'] ?? null;
        $this->added = $values['added'] ?? null;
        $this->removed = $values['removed'] ?? null;
        $this->unchanged = $values['unchanged'] ?? null;
        $this->rejected = $values['rejected'] ?? null;
        $this->finishedReviewsRemoved = $values['finishedReviewsRemoved'] ?? null;
        $this->assignmentChoice = $values['assignmentChoice'] ?? null;
        $this->warning = $values['warning'] ?? null;
        $this->rows = $values['rows'] ?? null;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }
}
