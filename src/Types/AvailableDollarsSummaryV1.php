<?php

namespace Awardspring\Types;

use Awardspring\Core\Json\JsonSerializableType;
use Awardspring\Core\Json\JsonProperty;

/**
 * The available dollars of a whole award cycle: the budget, awarded and remaining amounts of every active
 * scholarship in the cycle added together: the same scholarships and amounts the per-scholarship
 * available-dollars list reports for the cycle.
 */
class AvailableDollarsSummaryV1 extends JsonSerializableType
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
     * @var ?string $awardCycleId The award cycle's stored ID.
     */
    #[JsonProperty('award_cycle_id')]
    public ?string $awardCycleId;

    /**
     * @var ?int $scholarshipCount How many active scholarships the totals cover.
     */
    #[JsonProperty('scholarship_count')]
    public ?int $scholarshipCount;

    /**
     * @var ?float $totalFunds The scholarships' budgets added together, rounded to 2 decimals.
     */
    #[JsonProperty('total_funds')]
    public ?float $totalFunds;

    /**
     * @var ?float $totalAwardedAmount Everything awarded from the scholarships, added together, rounded to 2 decimals.
     */
    #[JsonProperty('total_awarded_amount')]
    public ?float $totalAwardedAmount;

    /**
     * The money left across the cycle: `total_funds` less `total_awarded_amount`. An over-awarded
     * scholarship counts against it, so its overage is already subtracted. Negative when the cycle as a
     * whole has awarded more than its budgets hold.
     *
     * @var ?float $totalAmountRemaining
     */
    #[JsonProperty('total_amount_remaining')]
    public ?float $totalAmountRemaining;

    /**
     * How many of the scholarships have awarded more than their budget (a negative
     * `total_amount_remaining` on the per-scholarship list). They are included in every total above.
     *
     * @var ?int $overAwardedScholarshipCount
     */
    #[JsonProperty('over_awarded_scholarship_count')]
    public ?int $overAwardedScholarshipCount;

    /**
     * @param array{
     *   object?: ?string,
     *   awardCycleId?: ?string,
     *   scholarshipCount?: ?int,
     *   totalFunds?: ?float,
     *   totalAwardedAmount?: ?float,
     *   totalAmountRemaining?: ?float,
     *   overAwardedScholarshipCount?: ?int,
     * } $values
     */
    public function __construct(
        array $values = [],
    ) {
        $this->object = $values['object'] ?? null;
        $this->awardCycleId = $values['awardCycleId'] ?? null;
        $this->scholarshipCount = $values['scholarshipCount'] ?? null;
        $this->totalFunds = $values['totalFunds'] ?? null;
        $this->totalAwardedAmount = $values['totalAwardedAmount'] ?? null;
        $this->totalAmountRemaining = $values['totalAmountRemaining'] ?? null;
        $this->overAwardedScholarshipCount = $values['overAwardedScholarshipCount'] ?? null;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }
}
