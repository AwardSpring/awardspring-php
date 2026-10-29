<?php

namespace Awardspring\Scholarships\Requests;

use Awardspring\Core\Json\JsonSerializableType;
use Awardspring\Scholarships\Types\ListAwardedStudentsScholarshipsRequestIncludeItem;

class ListAwardedStudentsScholarshipsRequest extends JsonSerializableType
{
    /**
     * @var string $awardCycleId
     */
    public string $awardCycleId;

    /**
     * @var ?string $scholarshipId
     */
    public ?string $scholarshipId;

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
     * @var ?array<value-of<ListAwardedStudentsScholarshipsRequestIncludeItem>> $include Optional extras to add to the response, comma-separated. `total_count` is the only supported value: it adds `total_count`, the number of records that match this request across all pages. The count is taken fresh at the moment of each request, so it can change while you page; ask for it on the first page. Without it, `total_count` is left out of the response. Asking or not never invalidates a cursor. Any other value returns `400 validation_failed`.
     */
    public ?array $include;

    /**
     * @param array{
     *   awardCycleId: string,
     *   scholarshipId?: ?string,
     *   limit?: ?int,
     *   startingAfter?: ?string,
     *   endingBefore?: ?string,
     *   include?: ?array<value-of<ListAwardedStudentsScholarshipsRequestIncludeItem>>,
     * } $values
     */
    public function __construct(
        array $values,
    ) {
        $this->awardCycleId = $values['awardCycleId'];
        $this->scholarshipId = $values['scholarshipId'] ?? null;
        $this->limit = $values['limit'] ?? null;
        $this->startingAfter = $values['startingAfter'] ?? null;
        $this->endingBefore = $values['endingBefore'] ?? null;
        $this->include = $values['include'] ?? null;
    }
}
