<?php

namespace Awardspring\DonorActivities\Requests;

use Awardspring\Core\Json\JsonSerializableType;

class ListDonorActivitiesRequest extends JsonSerializableType
{
    /**
     * @var ?string $q Free-text search (`?q=`): case-insensitive substring match on subject or description.
     */
    public ?string $q;

    /**
     * @var ?string $activityType Activity-type filter (`?activity_type=`): case-insensitive exact match on the activity type label.
     */
    public ?string $activityType;

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
     * @param array{
     *   q?: ?string,
     *   activityType?: ?string,
     *   limit?: ?int,
     *   startingAfter?: ?string,
     *   endingBefore?: ?string,
     * } $values
     */
    public function __construct(
        array $values = [],
    ) {
        $this->q = $values['q'] ?? null;
        $this->activityType = $values['activityType'] ?? null;
        $this->limit = $values['limit'] ?? null;
        $this->startingAfter = $values['startingAfter'] ?? null;
        $this->endingBefore = $values['endingBefore'] ?? null;
    }
}
