<?php

namespace Awardspring\Types;

use Awardspring\Core\Json\JsonSerializableType;
use Awardspring\Core\Json\JsonProperty;

/**
 * One applicant's award of one scholarship within an award cycle (one flattened row per award).
 */
class AwardedStudentV1 extends JsonSerializableType
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
     * @var ?string $id The award's stored ID (its application's stored ID).
     */
    #[JsonProperty('id')]
    public ?string $id;

    /**
     * @var ?string $awardCode The award's 8-character award code. May be empty on awards made before award codes existed.
     */
    #[JsonProperty('award_code')]
    public ?string $awardCode;

    /**
     * @var ?string $scholarshipId The scholarship's stored ID.
     */
    #[JsonProperty('scholarship_id')]
    public ?string $scholarshipId;

    /**
     * @var ?string $scholarshipName
     */
    #[JsonProperty('scholarship_name')]
    public ?string $scholarshipName;

    /**
     * @var ?string $fundId
     */
    #[JsonProperty('fund_id')]
    public ?string $fundId;

    /**
     * @var ?string $studentId
     */
    #[JsonProperty('student_id')]
    public ?string $studentId;

    /**
     * @var ?string $firstName
     */
    #[JsonProperty('first_name')]
    public ?string $firstName;

    /**
     * @var ?string $lastName
     */
    #[JsonProperty('last_name')]
    public ?string $lastName;

    /**
     * @var ?string $email
     */
    #[JsonProperty('email')]
    public ?string $email;

    /**
     * @var ?int $awardedDate
     */
    #[JsonProperty('awarded_date')]
    public ?int $awardedDate;

    /**
     * @var ?float $awardedAmount
     */
    #[JsonProperty('awarded_amount')]
    public ?float $awardedAmount;

    /**
     * @param array{
     *   object?: ?string,
     *   id?: ?string,
     *   awardCode?: ?string,
     *   scholarshipId?: ?string,
     *   scholarshipName?: ?string,
     *   fundId?: ?string,
     *   studentId?: ?string,
     *   firstName?: ?string,
     *   lastName?: ?string,
     *   email?: ?string,
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
        $this->scholarshipId = $values['scholarshipId'] ?? null;
        $this->scholarshipName = $values['scholarshipName'] ?? null;
        $this->fundId = $values['fundId'] ?? null;
        $this->studentId = $values['studentId'] ?? null;
        $this->firstName = $values['firstName'] ?? null;
        $this->lastName = $values['lastName'] ?? null;
        $this->email = $values['email'] ?? null;
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
