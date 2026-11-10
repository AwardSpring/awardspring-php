<?php

namespace Awardspring\ReviewerAssignments\Requests;

use Awardspring\Core\Json\JsonSerializableType;
use Awardspring\ReviewerAssignments\Types\ListJobRowsReviewerAssignmentsRequestIncludeItem;

class ListJobRowsReviewerAssignmentsRequest extends JsonSerializableType
{
    /**
     * Required. The job's code (`?job_id=`), as in its `status_url`. A code that names no job at your
     * institution returns `404 assignment_job_not_found`.
     *
     * @var ?string $jobId
     */
    public ?string $jobId;

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
     * @var ?array<value-of<ListJobRowsReviewerAssignmentsRequestIncludeItem>> $include Optional extras to add to the response, comma-separated. `total_count` is the only supported value: it adds `total_count`, the number of records that match this request across all pages. The count is taken fresh at the moment of each request, so it can change while you page; ask for it on the first page. Without it, `total_count` is left out of the response. Asking or not never invalidates a cursor. Any other value returns `400 validation_failed`.
     */
    public ?array $include;

    /**
     * @param array{
     *   jobId?: ?string,
     *   limit?: ?int,
     *   startingAfter?: ?string,
     *   endingBefore?: ?string,
     *   include?: ?array<value-of<ListJobRowsReviewerAssignmentsRequestIncludeItem>>,
     * } $values
     */
    public function __construct(
        array $values = [],
    ) {
        $this->jobId = $values['jobId'] ?? null;
        $this->limit = $values['limit'] ?? null;
        $this->startingAfter = $values['startingAfter'] ?? null;
        $this->endingBefore = $values['endingBefore'] ?? null;
        $this->include = $values['include'] ?? null;
    }
}
