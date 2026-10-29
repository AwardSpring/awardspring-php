<?php

namespace Awardspring\Types;

use Awardspring\Core\Json\JsonSerializableType;
use Awardspring\Core\Json\JsonProperty;

/**
 * Available-dollars snapshot (budget, awarded, remaining totals) for a scholarship within an award cycle.
 */
class ScholarshipAvailableDollarsV1 extends JsonSerializableType
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
     * @var ?string $id The scholarship's stored ID.
     */
    #[JsonProperty('id')]
    public ?string $id;

    /**
     * @var ?string $name
     */
    #[JsonProperty('name')]
    public ?string $name;

    /**
     * @var ?float $totalFunds
     */
    #[JsonProperty('total_funds')]
    public ?float $totalFunds;

    /**
     * @var ?float $totalAwardedAmount
     */
    #[JsonProperty('total_awarded_amount')]
    public ?float $totalAwardedAmount;

    /**
     * @var ?float $totalAmountRemaining
     */
    #[JsonProperty('total_amount_remaining')]
    public ?float $totalAmountRemaining;

    /**
     * @param array{
     *   object?: ?string,
     *   id?: ?string,
     *   name?: ?string,
     *   totalFunds?: ?float,
     *   totalAwardedAmount?: ?float,
     *   totalAmountRemaining?: ?float,
     * } $values
     */
    public function __construct(
        array $values = [],
    ) {
        $this->object = $values['object'] ?? null;
        $this->id = $values['id'] ?? null;
        $this->name = $values['name'] ?? null;
        $this->totalFunds = $values['totalFunds'] ?? null;
        $this->totalAwardedAmount = $values['totalAwardedAmount'] ?? null;
        $this->totalAmountRemaining = $values['totalAmountRemaining'] ?? null;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }
}
