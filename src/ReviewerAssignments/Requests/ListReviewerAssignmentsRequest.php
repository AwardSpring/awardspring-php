<?php

namespace Awardspring\ReviewerAssignments\Requests;

use Awardspring\Core\Json\JsonSerializableType;
use Awardspring\ReviewerAssignments\Types\ListReviewerAssignmentsRequestIncludeItem;

class ListReviewerAssignmentsRequest extends JsonSerializableType
{
    /**
     * The review group's stored ID (`?review_group_id=`). Send it or `review_group_name`; leaving
     * both out returns `400 validation_failed`. A group that does not exist at your institution returns
     * `404 review_group_not_found`.
     *
     * @var ?string $reviewGroupId
     */
    public ?string $reviewGroupId;

    /**
     * Optional, instead of `review_group_id`: the review group's name (`?review_group_name=`), matched
     * whole and ignoring case, or else as part of a name. A name that fits no group returns
     * `404 review_group_not_found`; one that fits more than one returns `400 validation_failed`
     * naming each, with its award cycle and stored ID.
     *
     * @var ?string $reviewGroupName
     */
    public ?string $reviewGroupName;

    /**
     * Optional, with `review_group_name` only: the stored ID of the award cycle to look for the name in
     * (`?award_cycle_id=`).
     *
     * @var ?string $awardCycleId
     */
    public ?string $awardCycleId;

    /**
     * Optional. Only this panel's pairs (`?panel=`): `1` or `2`. Leave it out on a group with one
     * panel, and to list both panels of a group with two. A panel the group does not have returns
     * `404 review_group_panel_not_found`.
     *
     * @var ?int $panel
     */
    public ?int $panel;

    /**
     * @var ?int $limit Page size. Defaults to 25. Values outside 1–100 are clamped rather than rejected.
     */
    public ?int $limit;

    /**
     * @var ?string $startingAfter Opaque cursor. Pass the `next_cursor` from the previous page to page forward. Mutually exclusive with `ending_before`; if both are supplied, `starting_after` wins.
     */
    public ?string $startingAfter;

    /**
     * @var ?string $endingBefore Opaque cursor. Pass the `previous_cursor` from the current page to page backward.
     */
    public ?string $endingBefore;

    /**
     * @var ?array<value-of<ListReviewerAssignmentsRequestIncludeItem>> $include Optional extras to add to the response, comma-separated. `total_count` is the only supported value: it adds `total_count`, the number of records that match this request across all pages. The count is taken fresh at the moment of each request, so it can change while you page; ask for it on the first page. Without it, `total_count` is left out of the response. Asking or not never invalidates a cursor. Any other value returns `400 validation_failed`.
     */
    public ?array $include;

    /**
     * @param array{
     *   reviewGroupId?: ?string,
     *   reviewGroupName?: ?string,
     *   awardCycleId?: ?string,
     *   panel?: ?int,
     *   limit?: ?int,
     *   startingAfter?: ?string,
     *   endingBefore?: ?string,
     *   include?: ?array<value-of<ListReviewerAssignmentsRequestIncludeItem>>,
     * } $values
     */
    public function __construct(
        array $values = [],
    ) {
        $this->reviewGroupId = $values['reviewGroupId'] ?? null;
        $this->reviewGroupName = $values['reviewGroupName'] ?? null;
        $this->awardCycleId = $values['awardCycleId'] ?? null;
        $this->panel = $values['panel'] ?? null;
        $this->limit = $values['limit'] ?? null;
        $this->startingAfter = $values['startingAfter'] ?? null;
        $this->endingBefore = $values['endingBefore'] ?? null;
        $this->include = $values['include'] ?? null;
    }
}
