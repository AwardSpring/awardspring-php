<?php

namespace Awardspring\Types;

use Awardspring\Core\Json\JsonSerializableType;
use Awardspring\Core\Json\JsonProperty;
use Awardspring\Core\Types\ArrayType;

/**
 * One scholarship as returned by `GET /api/v1/scholarships/{scholarshipId}`. Carries
 * `"object": "scholarship"`. Every field the scholarship list shows keeps its list name and value.
 */
class ScholarshipV1 extends JsonSerializableType
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
     * @var ?string $id The scholarship's stored ID. Example: `"7HQ2KW9XR"`.
     */
    #[JsonProperty('id')]
    public ?string $id;

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
     * @var ?string $awardCycleId The stored ID of the award cycle the scholarship belongs to.
     */
    #[JsonProperty('award_cycle_id')]
    public ?string $awardCycleId;

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
     * @var ?string $departmentId The stored ID of the scholarship's department, or null when it has none.
     */
    #[JsonProperty('department_id')]
    public ?string $departmentId;

    /**
     * @var ?string $fundId The scholarship's fund code, or null when it has none. This is the same code the create call takes.
     */
    #[JsonProperty('fund_id')]
    public ?string $fundId;

    /**
     * @var ?array<string> $donorIds The stored IDs of the donors linked to the scholarship. Empty when there are none.
     */
    #[JsonProperty('donor_ids'), ArrayType(['string'])]
    public ?array $donorIds;

    /**
     * @var ?string $description The scholarship's description, as stored. Empty when there is none.
     */
    #[JsonProperty('description')]
    public ?string $description;

    /**
     * @var ?ScholarshipAwardNotificationV1 $awardNotification
     */
    #[JsonProperty('award_notification')]
    public ?ScholarshipAwardNotificationV1 $awardNotification;

    /**
     * Every custom field the scholarship's Details tab shows, in the tab's order, each with this scholarship's value.
     * A field with no value is listed with an empty value. A field that has not been given a stored ID yet is left
     * out.
     *
     * @var ?array<ScholarshipCustomFieldV1> $customFields
     */
    #[JsonProperty('custom_fields'), ArrayType([ScholarshipCustomFieldV1::class])]
    public ?array $customFields;

    /**
     * The scholarship's qualifications, in the Qualifications tab's groups and order. A student qualifies by
     * meeting every rule in at least one group: rules inside a group are all required, and the groups are
     * alternatives. Empty when the scholarship has no qualifications. Every `question` rule carries its
     * question's stored ID.
     *
     * @var ?array<ScholarshipQualificationGroupV1> $qualificationGroups
     */
    #[JsonProperty('qualification_groups'), ArrayType([ScholarshipQualificationGroupV1::class])]
    public ?array $qualificationGroups;

    /**
     * @param array{
     *   object?: ?string,
     *   id?: ?string,
     *   name?: ?string,
     *   totalAmount?: ?float,
     *   isActive?: ?bool,
     *   awardCycleId?: ?string,
     *   applicationStartDate?: ?int,
     *   applicationEndDate?: ?int,
     *   departmentId?: ?string,
     *   fundId?: ?string,
     *   donorIds?: ?array<string>,
     *   description?: ?string,
     *   awardNotification?: ?ScholarshipAwardNotificationV1,
     *   customFields?: ?array<ScholarshipCustomFieldV1>,
     *   qualificationGroups?: ?array<ScholarshipQualificationGroupV1>,
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
        $this->departmentId = $values['departmentId'] ?? null;
        $this->fundId = $values['fundId'] ?? null;
        $this->donorIds = $values['donorIds'] ?? null;
        $this->description = $values['description'] ?? null;
        $this->awardNotification = $values['awardNotification'] ?? null;
        $this->customFields = $values['customFields'] ?? null;
        $this->qualificationGroups = $values['qualificationGroups'] ?? null;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }
}
