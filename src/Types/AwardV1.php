<?php

namespace Awardspring\Types;

use Awardspring\Core\Json\JsonSerializableType;
use Awardspring\Core\Json\JsonProperty;

/**
 * One scholarship award granted to one student within an award cycle.
 */
class AwardV1 extends JsonSerializableType
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
     * @var ?int $id Award identifier — also the cursor anchor for the list.
     */
    #[JsonProperty('id')]
    public ?int $id;

    /**
     * @var ?int $userId The recipient's user record id — the same value as the student resource's `id` (see the Students endpoints).
     */
    #[JsonProperty('user_id')]
    public ?int $userId;

    /**
     * @var ?int $scholarshipId Identifier of the awarded scholarship (see the Scholarships endpoints).
     */
    #[JsonProperty('scholarship_id')]
    public ?int $scholarshipId;

    /**
     * @var ?string $scholarshipName Name of the awarded scholarship, denormalized onto the row for convenience.
     */
    #[JsonProperty('scholarship_name')]
    public ?string $scholarshipName;

    /**
     * @var ?int $awardCycleId Identifier of the award cycle the award belongs to (see the Award Cycles endpoints).
     */
    #[JsonProperty('award_cycle_id')]
    public ?int $awardCycleId;

    /**
     * @var ?string $awardCycleName Name of the award cycle the award belongs to, denormalized onto the row for convenience.
     */
    #[JsonProperty('award_cycle_name')]
    public ?string $awardCycleName;

    /**
     * @var ?int $awardedDate When the award decision was made, as UTC epoch seconds. Null when no decision date was recorded.
     */
    #[JsonProperty('awarded_date')]
    public ?int $awardedDate;

    /**
     * @var ?float $awardedAmount Awarded dollar amount, rounded to two decimal places.
     */
    #[JsonProperty('awarded_amount')]
    public ?float $awardedAmount;

    /**
     * @param array{
     *   object?: ?string,
     *   id?: ?int,
     *   userId?: ?int,
     *   scholarshipId?: ?int,
     *   scholarshipName?: ?string,
     *   awardCycleId?: ?int,
     *   awardCycleName?: ?string,
     *   awardedDate?: ?int,
     *   awardedAmount?: ?float,
     * } $values
     */
    public function __construct(
        array $values = [],
    ) {
        $this->object = $values['object'] ?? null;
        $this->id = $values['id'] ?? null;
        $this->userId = $values['userId'] ?? null;
        $this->scholarshipId = $values['scholarshipId'] ?? null;
        $this->scholarshipName = $values['scholarshipName'] ?? null;
        $this->awardCycleId = $values['awardCycleId'] ?? null;
        $this->awardCycleName = $values['awardCycleName'] ?? null;
        $this->awardedDate = $values['awardedDate'] ?? null;
        $this->awardedAmount = $values['awardedAmount'] ?? null;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }
}
