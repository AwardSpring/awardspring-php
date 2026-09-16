<?php

namespace Awardspring\Types;

use Awardspring\Core\Json\JsonSerializableType;
use Awardspring\Core\Json\JsonProperty;

/**
 * One student's application to one scholarship within an award cycle.
 */
class ApplicationV1 extends JsonSerializableType
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
     * @var ?int $id Application identifier — also the cursor anchor for the list.
     */
    #[JsonProperty('id')]
    public ?int $id;

    /**
     * @var ?int $userId The applicant's user record id — the same value as the student resource's `id` (see the Students endpoints).
     */
    #[JsonProperty('user_id')]
    public ?int $userId;

    /**
     * @var ?int $scholarshipId Identifier of the scholarship applied to (see the Scholarships endpoints).
     */
    #[JsonProperty('scholarship_id')]
    public ?int $scholarshipId;

    /**
     * @var ?string $scholarshipName Name of the scholarship applied to, denormalized onto the row for convenience.
     */
    #[JsonProperty('scholarship_name')]
    public ?string $scholarshipName;

    /**
     * @var ?int $awardCycleId Identifier of the award cycle the application belongs to (see the Award Cycles endpoints).
     */
    #[JsonProperty('award_cycle_id')]
    public ?int $awardCycleId;

    /**
     * @var ?string $awardCycleName Name of the award cycle the application belongs to, denormalized onto the row for convenience.
     */
    #[JsonProperty('award_cycle_name')]
    public ?string $awardCycleName;

    /**
     * Where the application stands: `applied` (submitted, no decision yet),
     * `awarded`, or `denied`.
     *
     * @var ?string $status
     */
    #[JsonProperty('status')]
    public ?string $status;

    /**
     * @var ?int $appliedDate When the application was submitted, as UTC epoch seconds.
     */
    #[JsonProperty('applied_date')]
    public ?int $appliedDate;

    /**
     * @param array{
     *   object?: ?string,
     *   id?: ?int,
     *   userId?: ?int,
     *   scholarshipId?: ?int,
     *   scholarshipName?: ?string,
     *   awardCycleId?: ?int,
     *   awardCycleName?: ?string,
     *   status?: ?string,
     *   appliedDate?: ?int,
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
        $this->status = $values['status'] ?? null;
        $this->appliedDate = $values['appliedDate'] ?? null;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }
}
