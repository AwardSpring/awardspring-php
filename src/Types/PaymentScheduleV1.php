<?php

namespace Awardspring\Types;

use Awardspring\Core\Json\JsonSerializableType;
use Awardspring\Core\Json\JsonProperty;
use Awardspring\Core\Types\ArrayType;

/**
 * A payment schedule — one dated payment an institution configures and attaches to award cycles.
 * Carries `"object": "payment_schedule"`. A scholarship with two or more payments per award names
 * one of its award cycle's schedules for each payment after the first, in `payment_schedule_ids`.
 */
class PaymentScheduleV1 extends JsonSerializableType
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
     * @var ?int $id AwardSpring payment-schedule identifier — also the cursor anchor for paging. Example: `12`.
     */
    #[JsonProperty('id')]
    public ?int $id;

    /**
     * @var ?string $name Payment schedule name as configured by the institution (e.g. "Spring payment").
     */
    #[JsonProperty('name')]
    public ?string $name;

    /**
     * @var ?int $paymentDate When this payment is made (UTC epoch seconds), or null when unset.
     */
    #[JsonProperty('payment_date')]
    public ?int $paymentDate;

    /**
     * The stored IDs of the award cycles this schedule is configured on. A schedule can be configured on
     * more than one cycle. Example: `["4MZ8QW2TC"]`.
     *
     * @var ?array<string> $awardCycleIds
     */
    #[JsonProperty('award_cycle_ids'), ArrayType(['string'])]
    public ?array $awardCycleIds;

    /**
     * @param array{
     *   object?: ?string,
     *   id?: ?int,
     *   name?: ?string,
     *   paymentDate?: ?int,
     *   awardCycleIds?: ?array<string>,
     * } $values
     */
    public function __construct(
        array $values = [],
    ) {
        $this->object = $values['object'] ?? null;
        $this->id = $values['id'] ?? null;
        $this->name = $values['name'] ?? null;
        $this->paymentDate = $values['paymentDate'] ?? null;
        $this->awardCycleIds = $values['awardCycleIds'] ?? null;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }
}
