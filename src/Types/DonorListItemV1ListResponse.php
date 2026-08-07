<?php

namespace Awardspring\Types;

use Awardspring\Core\Json\JsonSerializableType;
use Awardspring\Core\Json\JsonProperty;
use Awardspring\Core\Types\ArrayType;

/**
 * The envelope every list endpoint returns: one page of results in `data`, plus the cursors
 * needed to reach the rest.
 * ```
 * {
 *   "object": "list",
 *   "url": "/api/v1/…",
 *   "has_more": true,
 *   "next_cursor": "…",
 *   "previous_cursor": null,
 *   "data": [ … ]
 * }
 * ````url` echoes the path the collection was served from. To page forward, send
 * `next_cursor` back as `starting_after`; on the last page `has_more` is
 * `false` and `next_cursor` is null.
 */
class DonorListItemV1ListResponse extends JsonSerializableType
{
    /**
     * @var ?string $object Discriminator constant. Always `"list"`, mirroring Stripe's list objects.
     */
    #[JsonProperty('object')]
    public ?string $object;

    /**
     * @var ?string $url The request path this collection was served from (e.g. `/api/v1/scholarships`).
     */
    #[JsonProperty('url')]
    public ?string $url;

    /**
     * @var ?bool $hasMore True when another page exists in the current paging direction.
     */
    #[JsonProperty('has_more')]
    public ?bool $hasMore;

    /**
     * @var ?string $nextCursor Opaque cursor for the next page (pass as `starting_after`). Null when there is none.
     */
    #[JsonProperty('next_cursor')]
    public ?string $nextCursor;

    /**
     * @var ?string $previousCursor Opaque cursor for the previous page (pass as `ending_before`). Null when there is none.
     */
    #[JsonProperty('previous_cursor')]
    public ?string $previousCursor;

    /**
     * @var ?array<DonorListItemV1> $data The page of items.
     */
    #[JsonProperty('data'), ArrayType([DonorListItemV1::class])]
    public ?array $data;

    /**
     * @param array{
     *   object?: ?string,
     *   url?: ?string,
     *   hasMore?: ?bool,
     *   nextCursor?: ?string,
     *   previousCursor?: ?string,
     *   data?: ?array<DonorListItemV1>,
     * } $values
     */
    public function __construct(
        array $values = [],
    ) {
        $this->object = $values['object'] ?? null;
        $this->url = $values['url'] ?? null;
        $this->hasMore = $values['hasMore'] ?? null;
        $this->nextCursor = $values['nextCursor'] ?? null;
        $this->previousCursor = $values['previousCursor'] ?? null;
        $this->data = $values['data'] ?? null;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }
}
