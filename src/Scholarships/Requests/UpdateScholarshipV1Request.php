<?php

namespace Awardspring\Scholarships\Requests;

use Awardspring\Core\Json\JsonSerializableType;
use Awardspring\Core\Json\JsonProperty;
use Awardspring\Core\Types\ArrayType;
use Awardspring\Types\UpdateScholarshipAwardNotificationV1Request;
use Awardspring\Types\UpdateScholarshipCustomFieldV1Request;
use Awardspring\Types\RemoveScholarshipQualificationV1Request;
use Awardspring\Types\AddScholarshipQualificationV1Request;

class UpdateScholarshipV1Request extends JsonSerializableType
{
    /**
     * The scholarship's new name. It cannot be blank, and no other scholarship in the same award cycle may have it.
     * Example: `"Smith Family Nursing Scholarship"`.
     *
     * @var ?string $name
     */
    #[JsonProperty('name')]
    public ?string $name;

    /**
     * The new day the application window opens. A date without a time (`"2026-09-01"`) is that calendar day
     * in the institution's time zone, and the scholarship keeps the time of day it opens at now. A date and time
     * with an offset (`"2026-09-01T08:00:00-04:00"`) is used exactly as sent. It cannot be cleared, and it
     * must fall before the close date and inside the award cycle, as on the scholarship's Details tab.
     *
     * @var ?string $applicationStartDate
     */
    #[JsonProperty('application_start_date')]
    public ?string $applicationStartDate;

    /**
     * The new day the application window closes. A date without a time (`"2026-12-01"`) is that calendar day
     * in the institution's time zone, and the scholarship keeps the time of day it closes at now, so a
     * scholarship that closes at the end of the day closes at the end of the new day. A date and time with an
     * offset (`"2026-12-01T17:00:00-05:00"`) is used exactly as sent. It cannot be cleared, and it must fall
     * after the open date and inside the award cycle, as on the scholarship's Details tab.
     *
     * @var ?string $applicationEndDate
     */
    #[JsonProperty('application_end_date')]
    public ?string $applicationEndDate;

    /**
     * The stored ID of the department the scholarship moves to. An empty string removes its department.
     * Example: `"4MZ8RT2QX"`.
     *
     * @var ?string $departmentId
     */
    #[JsonProperty('department_id')]
    public ?string $departmentId;

    /**
     * The fund code the scholarship is paid from, as the create call takes it, at most 128 characters. A code the
     * institution does not have yet adds that fund, as the Details tab does. An empty string removes the fund.
     * Example: `"NURS-2026"`.
     *
     * @var ?string $fundId
     */
    #[JsonProperty('fund_id')]
    public ?string $fundId;

    /**
     * The stored IDs of donors to link to the scholarship. Each must be a donor or organization at the institution
     * that is not archived and not already linked. Example: `["9QW3ZK7HD"]`.
     *
     * @var ?array<string> $addDonorIds
     */
    #[JsonProperty('add_donor_ids'), ArrayType(['string'])]
    public ?array $addDonorIds;

    /**
     * The stored IDs of donors to unlink from the scholarship. Each must be linked to it now, and none may also be
     * in `add_donor_ids`. Example: `["2HX6PC4MT"]`.
     *
     * @var ?array<string> $removeDonorIds
     */
    #[JsonProperty('remove_donor_ids'), ArrayType(['string'])]
    public ?array $removeDonorIds;

    /**
     * @var ?string $description The scholarship's new description, replacing the whole description students see. It cannot be blank.
     */
    #[JsonProperty('description')]
    public ?string $description;

    /**
     * @var ?UpdateScholarshipAwardNotificationV1Request $awardNotification
     */
    #[JsonProperty('award_notification')]
    public ?UpdateScholarshipAwardNotificationV1Request $awardNotification;

    /**
     * New values for custom fields, each named by its stored ID; a field not listed keeps its value. The same rules
     * as the Details tab apply: a choice field takes only its active choices, and a value outside them is refused
     * naming the field. Each field may be listed once.
     * Example: `[{"id": "3KD8WQ2MZ", "value": "Merit"}, {"id": "7RT4HX9NC", "values": ["Nursing", "Biology"]}]`.
     *
     * @var ?array<UpdateScholarshipCustomFieldV1Request> $customFields
     */
    #[JsonProperty('custom_fields'), ArrayType([UpdateScholarshipCustomFieldV1Request::class])]
    public ?array $customFields;

    /**
     * Qualification rules to remove, each from one group. Removals are applied before additions. A removal that
     * matches no rule is refused. Group numbers are the scholarship's `qualification_groups` as last read; a
     * group left with no rules is dropped at the end and the groups after it move up. Refused once the application
     * window has closed. Example: `[{"question_id": "4MZ8RT2QX", "group": 1}]`.
     *
     * @var ?array<RemoveScholarshipQualificationV1Request> $removeQualifications
     */
    #[JsonProperty('remove_qualifications'), ArrayType([RemoveScholarshipQualificationV1Request::class])]
    public ?array $removeQualifications;

    /**
     * Qualification rules to add, applied in list order after the removals. To change a rule, remove it and add
     * the new one in the same request. A question can back at most one rule per group; a scholarship can have at
     * most 10 groups, or 1 on AwardSpring Now. Refused once the application window has closed. Students are
     * re-checked in the background after the save, so their qualified state changes shortly after the call
     * returns, not at once.
     * Example: `[{"question_id": "4MZ8RT2QX", "title": "GPA 3.2 or higher", "comparison": "at_least", "value": 3.2}]`.
     *
     * @var ?array<AddScholarshipQualificationV1Request> $addQualifications
     */
    #[JsonProperty('add_qualifications'), ArrayType([AddScholarshipQualificationV1Request::class])]
    public ?array $addQualifications;

    /**
     * @param array{
     *   name?: ?string,
     *   applicationStartDate?: ?string,
     *   applicationEndDate?: ?string,
     *   departmentId?: ?string,
     *   fundId?: ?string,
     *   addDonorIds?: ?array<string>,
     *   removeDonorIds?: ?array<string>,
     *   description?: ?string,
     *   awardNotification?: ?UpdateScholarshipAwardNotificationV1Request,
     *   customFields?: ?array<UpdateScholarshipCustomFieldV1Request>,
     *   removeQualifications?: ?array<RemoveScholarshipQualificationV1Request>,
     *   addQualifications?: ?array<AddScholarshipQualificationV1Request>,
     * } $values
     */
    public function __construct(
        array $values = [],
    ) {
        $this->name = $values['name'] ?? null;
        $this->applicationStartDate = $values['applicationStartDate'] ?? null;
        $this->applicationEndDate = $values['applicationEndDate'] ?? null;
        $this->departmentId = $values['departmentId'] ?? null;
        $this->fundId = $values['fundId'] ?? null;
        $this->addDonorIds = $values['addDonorIds'] ?? null;
        $this->removeDonorIds = $values['removeDonorIds'] ?? null;
        $this->description = $values['description'] ?? null;
        $this->awardNotification = $values['awardNotification'] ?? null;
        $this->customFields = $values['customFields'] ?? null;
        $this->removeQualifications = $values['removeQualifications'] ?? null;
        $this->addQualifications = $values['addQualifications'] ?? null;
    }
}
