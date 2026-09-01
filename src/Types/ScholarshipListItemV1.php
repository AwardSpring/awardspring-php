<?php

namespace Awardspring\Types;

use Awardspring\Core\Json\JsonSerializableType;
use Awardspring\Core\Json\JsonProperty;

/**
 * A scholarship as it appears in the `GET /api/v1/scholarships` list. Carries
 * `"object": "scholarship"`. This is a deliberately narrow summary shape so the list stays
 * fast — fetch a single scholarship's reporting endpoints for award and remaining-dollar detail.
 */
class ScholarshipListItemV1 extends JsonSerializableType
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
     * @var ?int $id The scholarship's identifier — also the cursor anchor used for paging.
     */
    #[JsonProperty('id')]
    public ?int $id;

    /**
     * @var ?string $name Scholarship name as configured by the institution.
     */
    #[JsonProperty('name')]
    public ?string $name;

    /**
     * @var ?float $totalAmount Total budgeted award value for the scholarship, or null when unset.
     */
    #[JsonProperty('total_amount')]
    public ?float $totalAmount;

    /**
     * @var ?bool $isActive True when the scholarship is active (visible/awardable); false when deactivated.
     */
    #[JsonProperty('is_active')]
    public ?bool $isActive;

    /**
     * @var ?int $awardCycleId The award cycle the scholarship belongs to.
     */
    #[JsonProperty('award_cycle_id')]
    public ?int $awardCycleId;

    /**
     * @var ?int $applicationStartDate When the scholarship's application window opens (UTC epoch seconds).
     */
    #[JsonProperty('application_start_date')]
    public ?int $applicationStartDate;

    /**
     * @var ?int $applicationEndDate When the scholarship's application window closes (UTC epoch seconds).
     */
    #[JsonProperty('application_end_date')]
    public ?int $applicationEndDate;

    /**
     * @param array{
     *   object?: ?string,
     *   id?: ?int,
     *   name?: ?string,
     *   totalAmount?: ?float,
     *   isActive?: ?bool,
     *   awardCycleId?: ?int,
     *   applicationStartDate?: ?int,
     *   applicationEndDate?: ?int,
     * } $values
     */
    public function __construct(
        array $values = [],
    ) {
        $this->object = $values['object'] ?? null;
        $this->id = $values['id'] ?? null;
        $this->name = $values['name'] ?? null;
        $this->totalAmount = $values['totalAmount'] ?? null;
        $this->isActive = $values['isActive'] ?? null;
        $this->awardCycleId = $values['awardCycleId'] ?? null;
        $this->applicationStartDate = $values['applicationStartDate'] ?? null;
        $this->applicationEndDate = $values['applicationEndDate'] ?? null;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }
}
