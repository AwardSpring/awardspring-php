<?php

namespace Awardspring\People\Requests;

use Awardspring\Core\Json\JsonSerializableType;
use Awardspring\People\Types\ListPeopleRequestIncludeItem;

class ListPeopleRequest extends JsonSerializableType
{
    /**
     * Which tab of the Users list (`?role=`): `administrators`, `reviewers`, `students`
     * or `donors`. The reviewers tab also lists anyone on a review group in the current award cycle.
     *
     * @var ?string $role
     */
    public ?string $role;

    /**
     * Free-text search (`?q=`): matches part of a first or last name or organization, the start of
     * an email address or student number, or a person's exact `id`.
     *
     * @var ?string $q
     */
    public ?string $q;

    /**
     * Account status (`?status=`): `all` (the default, as the Users list shows), `active`
     * or `archived`.
     *
     * @var ?string $status
     */
    public ?string $status;

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
     * @var ?array<value-of<ListPeopleRequestIncludeItem>> $include Optional extras to add to the response, comma-separated. `total_count` is the only supported value: it adds `total_count`, the number of records that match this request across all pages. The count is taken fresh at the moment of each request, so it can change while you page; ask for it on the first page. Without it, `total_count` is left out of the response. Asking or not never invalidates a cursor. Any other value returns `400 validation_failed`.
     */
    public ?array $include;

    /**
     * @param array{
     *   role?: ?string,
     *   q?: ?string,
     *   status?: ?string,
     *   limit?: ?int,
     *   startingAfter?: ?string,
     *   endingBefore?: ?string,
     *   include?: ?array<value-of<ListPeopleRequestIncludeItem>>,
     * } $values
     */
    public function __construct(
        array $values = [],
    ) {
        $this->role = $values['role'] ?? null;
        $this->q = $values['q'] ?? null;
        $this->status = $values['status'] ?? null;
        $this->limit = $values['limit'] ?? null;
        $this->startingAfter = $values['startingAfter'] ?? null;
        $this->endingBefore = $values['endingBefore'] ?? null;
        $this->include = $values['include'] ?? null;
    }
}
