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
     * The award's ID: the stored ID of the application it was awarded on, so it equals that application's
     * `id` in `GET /api/v1/applications`. It stays the same when the application is denied and awarded again.
     *
     * @var ?string $id
     */
    #[JsonProperty('id')]
    public ?string $id;

    /**
     * The 8-character award code AwardSpring issued when the application was awarded. Null when none was issued,
     * as on applications awarded before award codes existed.
     *
     * @var ?string $awardCode
     */
    #[JsonProperty('award_code')]
    public ?string $awardCode;

    /**
     * @var ?string $userId The stored ID of the recipient's user record: the same value as the student resource's `id` (see the Students endpoints).
     */
    #[JsonProperty('user_id')]
    public ?string $userId;

    /**
     * @var ?string $scholarshipId The stored ID of the awarded scholarship (see the Scholarships endpoints).
     */
    #[JsonProperty('scholarship_id')]
    public ?string $scholarshipId;

    /**
     * @var ?string $scholarshipName Name of the awarded scholarship, denormalized onto the row for convenience.
     */
    #[JsonProperty('scholarship_name')]
    public ?string $scholarshipName;

    /**
     * @var ?string $awardCycleId The stored ID of the award cycle the award belongs to (see the Award Cycles endpoints).
     */
    #[JsonProperty('award_cycle_id')]
    public ?string $awardCycleId;

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
     *   id?: ?string,
     *   awardCode?: ?string,
     *   userId?: ?string,
     *   scholarshipId?: ?string,
     *   scholarshipName?: ?string,
     *   awardCycleId?: ?string,
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
        $this->awardCode = $values['awardCode'] ?? null;
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
