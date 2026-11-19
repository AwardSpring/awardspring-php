<?php

namespace Awardspring\Gifts\Requests;

use Awardspring\Core\Json\JsonSerializableType;
use Awardspring\Gifts\Types\ListGiftsRequestSort;
use Awardspring\Gifts\Types\ListGiftsRequestIncludeItem;

class ListGiftsRequest extends JsonSerializableType
{
    /**
     * @var ?string $donorId Optional donor scope (`?donor_id=`): the donor's stored ID. Omit for a tenant-wide list.
     */
    public ?string $donorId;

    /**
     * @var ?string $type Record-type filter (`?type=`): `gift` or `pledge`. Omit for both.
     */
    public ?string $type;

    /**
     * @var ?string $q Free-text search (`?q=`), case-insensitive: a substring of the subject or description, the start of words in the donor's name or organization (`mar ch` finds Margaret Chen's gifts), the start of the donor's email, or the start of words in the fund's name.
     */
    public ?string $q;

    /**
     * The order to list gifts in (`?sort=`): `date_desc` for the newest gift date first, or
     * `date_asc` for the oldest gift date first. Leave it out for the order the gifts were
     * recorded in. Any other value returns `400 validation_failed`.
     *
     * @var ?value-of<ListGiftsRequestSort> $sort
     */
    public ?string $sort;

    /**
     * Only gifts and pledges that changed at or after this time (`?changed_since=`), in UTC epoch
     * seconds, such as `1767225600`. The list is then in the order the gifts changed, oldest change
     * first. With no `type`, it also lists each gift or pledge removed since that time, with only its
     * `id`, `deleted` and `removed_at`; with `donor_id`, only that donor's. With
     * `donor_id` or `type`, a gift or pledge that moved to another donor or switched between gift
     * and pledge is listed that way too, at the time it changed, unless it matches the list again. Start the
     * next sync from the list's `complete_up_to`. A time in the future, or anything but a whole number
     * of seconds, returns `400 validation_failed`. It cannot be combined with `q` or `sort`.
     *
     * @var ?int $changedSince
     */
    public ?int $changedSince;

    /**
     * @var ?int $limit Page size. Defaults to 25. Values outside 1–100 are clamped rather than rejected.
     */
    public ?int $limit;

    /**
     * @var ?string $startingAfter Opaque cursor. Pass the `next_cursor` from the previous page to page forward. Send it or `ending_before`, never both: a request with both returns `400 validation_failed`.
     */
    public ?string $startingAfter;

    /**
     * @var ?string $endingBefore Opaque cursor. Pass the `previous_cursor` from the current page to page backward. Send it or `starting_after`, never both: a request with both returns `400 validation_failed`.
     */
    public ?string $endingBefore;

    /**
     * @var ?array<value-of<ListGiftsRequestIncludeItem>> $include Optional extras to add to the response, comma-separated. `total_count` is the only supported value: it adds `total_count`, the number of records that match this request across all pages. The count is taken fresh at the moment of each request, so it can change while you page; ask for it on the first page. Without it, `total_count` is left out of the response. Asking or not never invalidates a cursor. Any other value returns `400 validation_failed`.
     */
    public ?array $include;

    /**
     * @param array{
     *   donorId?: ?string,
     *   type?: ?string,
     *   q?: ?string,
     *   sort?: ?value-of<ListGiftsRequestSort>,
     *   changedSince?: ?int,
     *   limit?: ?int,
     *   startingAfter?: ?string,
     *   endingBefore?: ?string,
     *   include?: ?array<value-of<ListGiftsRequestIncludeItem>>,
     * } $values
     */
    public function __construct(
        array $values = [],
    ) {
        $this->donorId = $values['donorId'] ?? null;
        $this->type = $values['type'] ?? null;
        $this->q = $values['q'] ?? null;
        $this->sort = $values['sort'] ?? null;
        $this->changedSince = $values['changedSince'] ?? null;
        $this->limit = $values['limit'] ?? null;
        $this->startingAfter = $values['startingAfter'] ?? null;
        $this->endingBefore = $values['endingBefore'] ?? null;
        $this->include = $values['include'] ?? null;
    }
}
