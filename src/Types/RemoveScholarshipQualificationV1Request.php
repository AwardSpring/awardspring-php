<?php

namespace Awardspring\Types;

use Awardspring\Core\Json\JsonSerializableType;
use Awardspring\Core\Json\JsonProperty;

/**
 * A qualification rule to remove from one group. Name the rule by `question_id`, or an institutional award
 * rule by `institutional_award_scholarship_id`, and always send `group`. To remove a question's rule from
 * several groups, send one entry per group.
 */
class RemoveScholarshipQualificationV1Request extends JsonSerializableType
{
    /**
     * @var ?string $questionId The stored ID of the question the rule checks, as the rule's `question_id` shows it. Example: `"4MZ8RT2QX"`.
     */
    #[JsonProperty('question_id')]
    public ?string $questionId;

    /**
     * Instead of `question_id`, to remove an institutional award rule: the stored ID of the scholarship the rule
     * names, as its `institutional_award_scholarship_id` shows it.
     *
     * @var ?string $institutionalAwardScholarshipId
     */
    #[JsonProperty('institutional_award_scholarship_id')]
    public ?string $institutionalAwardScholarshipId;

    /**
     * Required. The number of the group to remove the rule from, as the scholarship was last read (its
     * `qualification_groups`). Example: `1`.
     *
     * @var ?int $group
     */
    #[JsonProperty('group')]
    public ?int $group;

    /**
     * @param array{
     *   questionId?: ?string,
     *   institutionalAwardScholarshipId?: ?string,
     *   group?: ?int,
     * } $values
     */
    public function __construct(
        array $values = [],
    ) {
        $this->questionId = $values['questionId'] ?? null;
        $this->institutionalAwardScholarshipId = $values['institutionalAwardScholarshipId'] ?? null;
        $this->group = $values['group'] ?? null;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }
}
