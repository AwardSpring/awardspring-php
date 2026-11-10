<?php

namespace Awardspring\ReviewerAssignments\Requests;

use Awardspring\Core\Json\JsonSerializableType;
use Awardspring\Core\Json\JsonProperty;
use Awardspring\Types\ReviewerAssignmentRowV1;
use Awardspring\Core\Types\ArrayType;

class ChangeReviewerAssignmentsV1Request extends JsonSerializableType
{
    /**
     * Required. The review group's stored ID. Leaving it out returns `400 validation_failed`; a group that
     * does not exist at your institution returns `404 review_group_not_found`.
     *
     * @var ?string $reviewGroupId
     */
    #[JsonProperty('review_group_id')]
    public ?string $reviewGroupId;

    /**
     * Required. `add` adds the pairs, `remove` removes them, and `replace` makes the panel hold
     * exactly these pairs: pairs not listed are removed and new ones are added. Any other value returns
     * `400 validation_failed`.
     *
     * @var ?string $mode
     */
    #[JsonProperty('mode')]
    public ?string $mode;

    /**
     * Optional. The panel to change, `1` or `2`. Leave it out on a review group with one panel. On a
     * group with two panels, leaving it out means panel 1. A panel the group does not have returns
     * `404 review_group_panel_not_found`.
     *
     * @var ?int $panel
     */
    #[JsonProperty('panel')]
    public ?int $panel;

    /**
     * @var ?array<ReviewerAssignmentRowV1> $rows Required. At least one pair and at most 20,000.
     */
    #[JsonProperty('rows'), ArrayType([ReviewerAssignmentRowV1::class])]
    public ?array $rows;

    /**
     * Optional, default `false`. When `true`, the pairs that pass are applied and each turned-down row
     * is listed. When `false`, a single turned-down row changes nothing.
     *
     * @var ?bool $applyGoodRowsOnly
     */
    #[JsonProperty('apply_good_rows_only')]
    public ?bool $applyGoodRowsOnly;

    /**
     * Optional, default `false`. When `false`, a `replace` that would remove a pair whose review
     * is started or finished turns that pair down. When `true` it removes the pair and its scores.
     * `remove` mode always removes the pairs it lists, as Unassign does on the tab.
     *
     * @var ?bool $allowRemovingStartedReviews
     */
    #[JsonProperty('allow_removing_started_reviews')]
    public ?bool $allowRemovingStartedReviews;

    /**
     * @param array{
     *   reviewGroupId?: ?string,
     *   mode?: ?string,
     *   panel?: ?int,
     *   rows?: ?array<ReviewerAssignmentRowV1>,
     *   applyGoodRowsOnly?: ?bool,
     *   allowRemovingStartedReviews?: ?bool,
     * } $values
     */
    public function __construct(
        array $values = [],
    ) {
        $this->reviewGroupId = $values['reviewGroupId'] ?? null;
        $this->mode = $values['mode'] ?? null;
        $this->panel = $values['panel'] ?? null;
        $this->rows = $values['rows'] ?? null;
        $this->applyGoodRowsOnly = $values['applyGoodRowsOnly'] ?? null;
        $this->allowRemovingStartedReviews = $values['allowRemovingStartedReviews'] ?? null;
    }
}
