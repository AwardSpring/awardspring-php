<?php

namespace Awardspring\Gifts\Requests;

use Awardspring\Core\Json\JsonSerializableType;

class ListGiftsRequest extends JsonSerializableType
{
    /**
     * @var ?int $donorId Optional donor scope (`?donor_id=`). Omit for a tenant-wide list.
     */
    public ?int $donorId;

    /**
     * @var ?string $type Record-type filter (`?type=`): `gift` or `pledge`. Omit for both.
     */
    public ?string $type;

    /**
     * @var ?string $q Free-text search (`?q=`): case-insensitive substring match on subject or description.
     */
    public ?string $q;

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
     *   donorId?: ?int,
     *   type?: ?string,
     *   q?: ?string,
     *   limit?: ?int,
     *   startingAfter?: ?string,
     *   endingBefore?: ?string,
     * } $values
     */
    public function __construct(
        array $values = [],
    ) {
        $this->donorId = $values['donorId'] ?? null;
        $this->type = $values['type'] ?? null;
        $this->q = $values['q'] ?? null;
        $this->limit = $values['limit'] ?? null;
        $this->startingAfter = $values['startingAfter'] ?? null;
        $this->endingBefore = $values['endingBefore'] ?? null;
    }
}
