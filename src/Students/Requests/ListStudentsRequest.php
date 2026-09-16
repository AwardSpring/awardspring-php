<?php

namespace Awardspring\Students\Requests;

use Awardspring\Core\Json\JsonSerializableType;

class ListStudentsRequest extends JsonSerializableType
{
    /**
     * @var ?string $q Free-text search (`?q=`): splits on spaces and matches each term against first name, last name, or email, case-insensitively.
     */
    public ?string $q;

    /**
     * Institution-assigned student number (`?student_id=`): exact, case-insensitive
     * match. Student numbers are not guaranteed unique, so this can return more than one student.
     *
     * @var ?string $studentId
     */
    public ?string $studentId;

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
     *   studentId?: ?string,
     *   limit?: ?int,
     *   startingAfter?: ?string,
     *   endingBefore?: ?string,
     * } $values
     */
    public function __construct(
        array $values = [],
    ) {
        $this->q = $values['q'] ?? null;
        $this->studentId = $values['studentId'] ?? null;
        $this->limit = $values['limit'] ?? null;
        $this->startingAfter = $values['startingAfter'] ?? null;
        $this->endingBefore = $values['endingBefore'] ?? null;
    }
}
