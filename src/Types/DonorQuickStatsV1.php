<?php

namespace Awardspring\Types;

use Awardspring\Core\Json\JsonSerializableType;
use Awardspring\Core\Json\JsonProperty;
use DateTime;
use Awardspring\Core\Types\Date;

class DonorQuickStatsV1 extends JsonSerializableType
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
     * @var ?float $lifetimeTotal
     */
    #[JsonProperty('lifetime_total')]
    public ?float $lifetimeTotal;

    /**
     * @var ?int $lifetimeGiftCount
     */
    #[JsonProperty('lifetime_gift_count')]
    public ?int $lifetimeGiftCount;

    /**
     * @var ?float $yearTotal
     */
    #[JsonProperty('year_total')]
    public ?float $yearTotal;

    /**
     * @var ?int $yearGiftCount
     */
    #[JsonProperty('year_gift_count')]
    public ?int $yearGiftCount;

    /**
     * @var ?float $lastGift
     */
    #[JsonProperty('last_gift')]
    public ?float $lastGift;

    /**
     * @var ?DateTime $lastGiftDate
     */
    #[JsonProperty('last_gift_date'), Date(Date::TYPE_DATETIME)]
    public ?DateTime $lastGiftDate;

    /**
     * @var ?bool $includeSoftCredits
     */
    #[JsonProperty('include_soft_credits')]
    public ?bool $includeSoftCredits;

    /**
     * @param array{
     *   object?: ?string,
     *   lifetimeTotal?: ?float,
     *   lifetimeGiftCount?: ?int,
     *   yearTotal?: ?float,
     *   yearGiftCount?: ?int,
     *   lastGift?: ?float,
     *   lastGiftDate?: ?DateTime,
     *   includeSoftCredits?: ?bool,
     * } $values
     */
    public function __construct(
        array $values = [],
    ) {
        $this->object = $values['object'] ?? null;
        $this->lifetimeTotal = $values['lifetimeTotal'] ?? null;
        $this->lifetimeGiftCount = $values['lifetimeGiftCount'] ?? null;
        $this->yearTotal = $values['yearTotal'] ?? null;
        $this->yearGiftCount = $values['yearGiftCount'] ?? null;
        $this->lastGift = $values['lastGift'] ?? null;
        $this->lastGiftDate = $values['lastGiftDate'] ?? null;
        $this->includeSoftCredits = $values['includeSoftCredits'] ?? null;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }
}
