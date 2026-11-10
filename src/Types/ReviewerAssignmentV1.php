<?php

namespace Awardspring\Types;

use Awardspring\Core\Json\JsonSerializableType;
use Awardspring\Core\Json\JsonProperty;

/**
 * One reviewer and applicant pair in a review group, as the Reviewer Assignment tab shows it. Carries
 * `"object": "reviewer_assignment"`.
 */
class ReviewerAssignmentV1 extends JsonSerializableType
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
     * @var ?string $reviewGroupId The stored ID of the review group.
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
     * @var ?int $panel The panel the pair is on, `1` or `2`. Null on a review group with one panel.
     */
    #[JsonProperty('panel')]
    public ?int $panel;

    /**
     * @var ?ReviewerAssignmentPersonV1 $reviewer
     */
    #[JsonProperty('reviewer')]
    public ?ReviewerAssignmentPersonV1 $reviewer;

    /**
     * @var ?ReviewerAssignmentPersonV1 $applicant
     */
    #[JsonProperty('applicant')]
    public ?ReviewerAssignmentPersonV1 $applicant;

    /**
     * @var ?string $reviewStatus Where the review stands: `not_started`, `in_progress` or `complete`.
     */
    #[JsonProperty('review_status')]
    public ?string $reviewStatus;

    /**
     * @param array{
     *   object?: ?string,
     *   reviewGroupId?: ?string,
     *   reviewGroupName?: ?string,
     *   awardCycleId?: ?string,
     *   awardCycleName?: ?string,
     *   panel?: ?int,
     *   reviewer?: ?ReviewerAssignmentPersonV1,
     *   applicant?: ?ReviewerAssignmentPersonV1,
     *   reviewStatus?: ?string,
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
        $this->reviewer = $values['reviewer'] ?? null;
        $this->applicant = $values['applicant'] ?? null;
        $this->reviewStatus = $values['reviewStatus'] ?? null;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }
}
