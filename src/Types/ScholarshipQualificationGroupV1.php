<?php

namespace Awardspring\Types;

use Awardspring\Core\Json\JsonSerializableType;
use Awardspring\Core\Json\JsonProperty;
use Awardspring\Core\Types\ArrayType;

/**
 * One of a scholarship's qualification groups. A student qualifies by meeting every rule in at least one group:
 * rules inside a group are all required, and the groups are alternatives.
 */
class ScholarshipQualificationGroupV1 extends JsonSerializableType
{
    /**
     * The group's number, counting from 1 in the order the scholarship's Qualifications tab shows the groups. The
     * update call's `to_group` and `group` mean these numbers. Example: `1`.
     *
     * @var ?int $groupNumber
     */
    #[JsonProperty('group_number')]
    public ?int $groupNumber;

    /**
     * @var ?array<ScholarshipQualificationRuleV1> $rules The group's rules, in the Qualifications tab's order. A group always has at least one.
     */
    #[JsonProperty('rules'), ArrayType([ScholarshipQualificationRuleV1::class])]
    public ?array $rules;

    /**
     * @param array{
     *   groupNumber?: ?int,
     *   rules?: ?array<ScholarshipQualificationRuleV1>,
     * } $values
     */
    public function __construct(
        array $values = [],
    ) {
        $this->groupNumber = $values['groupNumber'] ?? null;
        $this->rules = $values['rules'] ?? null;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }
}
