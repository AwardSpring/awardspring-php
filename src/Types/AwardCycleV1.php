<?php

namespace Awardspring\Types;

use Awardspring\Core\Json\JsonSerializableType;
use Awardspring\Core\Json\JsonProperty;

/**
 * An award cycle — the institution's scholarship season, for example "2025-2026". Carries
 * `"object": "award_cycle"`. The same shape is returned both as a list item from
 * `GET /api/v1/award-cycles` and on its own from `GET /api/v1/award-cycles/current`.
 */
class AwardCycleV1 extends JsonSerializableType
{
    /**
     * Stripe-style resource discriminator. Stable, snake_case string naming the resource type.
     * Serialized first so it reads as the leading field of every V1 resource body.
     *
     * @var ?string $object
     */
    #[JsonProperty('object')]
    public ?string $object;

    /**
     * @var ?int $id AwardSpring award-cycle identifier — also the cursor anchor for paging.
     */
    #[JsonProperty('id')]
    public ?int $id;

    /**
     * @var ?string $name Award-cycle name as configured by the institution (e.g. "2025-2026").
     */
    #[JsonProperty('name')]
    public ?string $name;

    /**
     * @var ?bool $isCurrent True when this is the cycle the institution has marked current.
     */
    #[JsonProperty('is_current')]
    public ?bool $isCurrent;

    /**
     * @var ?bool $isNext True when this is the cycle the institution has marked next/upcoming.
     */
    #[JsonProperty('is_next')]
    public ?bool $isNext;

    /**
     * @var ?int $applicationStartDate When the cycle's application window opens (UTC epoch seconds), or null when unset.
     */
    #[JsonProperty('application_start_date')]
    public ?int $applicationStartDate;

    /**
     * @var ?int $applicationEndDate When the cycle's application window closes (UTC epoch seconds), or null when unset.
     */
    #[JsonProperty('application_end_date')]
    public ?int $applicationEndDate;

    /**
     * @var ?int $reviewStartDate When the cycle's review window opens (UTC epoch seconds), or null when unset.
     */
    #[JsonProperty('review_start_date')]
    public ?int $reviewStartDate;

    /**
     * @var ?int $reviewEndDate When the cycle's review window closes (UTC epoch seconds), or null when unset.
     */
    #[JsonProperty('review_end_date')]
    public ?int $reviewEndDate;

    /**
     * @param array{
     *   object?: ?string,
     *   id?: ?int,
     *   name?: ?string,
     *   isCurrent?: ?bool,
     *   isNext?: ?bool,
     *   applicationStartDate?: ?int,
     *   applicationEndDate?: ?int,
     *   reviewStartDate?: ?int,
     *   reviewEndDate?: ?int,
     * } $values
     */
    public function __construct(
        array $values = [],
    ) {
        $this->object = $values['object'] ?? null;
        $this->id = $values['id'] ?? null;
        $this->name = $values['name'] ?? null;
        $this->isCurrent = $values['isCurrent'] ?? null;
        $this->isNext = $values['isNext'] ?? null;
        $this->applicationStartDate = $values['applicationStartDate'] ?? null;
        $this->applicationEndDate = $values['applicationEndDate'] ?? null;
        $this->reviewStartDate = $values['reviewStartDate'] ?? null;
        $this->reviewEndDate = $values['reviewEndDate'] ?? null;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }
}
