<?php

namespace Awardspring\Types;

use Awardspring\Core\Json\JsonSerializableType;
use Awardspring\Core\Json\JsonProperty;
use Awardspring\Core\Types\ArrayType;

/**
 * A qualification rule to add, on one application question named by its stored ID. Which value fields it takes
 * depends on the question's `question_type`: a number question takes `comparison` with `value`
 * (or `between` with `min` and `max`), a `number_range` question takes `min` and
 * `max`, and a choice question takes `answers`.
 */
class AddScholarshipQualificationV1Request extends JsonSerializableType
{
    /**
     * Required. The stored ID of an active application question at the institution that can back a qualification:
     * a general application question, or one of this scholarship's own Before Applying questions where the
     * institution allows them. Example: `"4MZ8RT2QX"`.
     *
     * @var ?string $questionId
     */
    #[JsonProperty('question_id')]
    public ?string $questionId;

    /**
     * @var ?string $title Required. The rule's title; it cannot be blank. Example: `"GPA 3.2 or higher"`.
     */
    #[JsonProperty('title')]
    public ?string $title;

    /**
     * Required on a `number`, `high_school_gpa`, `college_gpa` or `expense_ratio` question;
     * there is no default. One of `at_least`, `at_most`, `more_than`, `less_than`,
     * `equals` or `between`. A `number_range` question takes none, or `between`. Not
     * accepted on a choice question.
     *
     * @var ?value-of<AddScholarshipQualificationV1RequestComparison> $comparison
     */
    #[JsonProperty('comparison')]
    public ?string $comparison;

    /**
     * The number to compare against, as a JSON number, with any comparison but `between`. Stored to 4 decimal
     * places. A GPA must be above 0 and at most 6. Example: `3.2`.
     *
     * @var ?float $value
     */
    #[JsonProperty('value')]
    public ?float $value;

    /**
     * The lowest accepted number, with `between` or on a `number_range` question. It must be less than
     * `max`. A GPA must be above 0 and at most 6; an expense ratio at least -100.
     *
     * @var ?float $min
     */
    #[JsonProperty('min')]
    public ?float $min;

    /**
     * The highest accepted number, with `between` or on a `number_range` question. A GPA must be above 0
     * and at most 6; an expense ratio at most 100.
     *
     * @var ?float $max
     */
    #[JsonProperty('max')]
    public ?float $max;

    /**
     * On a choice question, the accepted answers by name, matched without regard to case; only the question's active
     * answers are accepted. A `one_choice` question takes 1 or more (and must itself have at least 2 active
     * answers), `multiple_choice` 2 or more, `multiple_choice_any` 1 or more, and `major`,
     * `degree` and `academic_status` 1 or more, or exactly 1 with `only_this_one`. Not accepted on a
     * number question. Example: `["Nursing"]`.
     *
     * @var ?array<string> $answers
     */
    #[JsonProperty('answers'), ArrayType(['string'])]
    public ?array $answers;

    /**
     * On a `major`, `degree` or `academic_status` question only: `any_of` (the default) or
     * `only_this_one`.
     *
     * @var ?value-of<AddScholarshipQualificationV1RequestAnswerSetup> $answerSetup
     */
    #[JsonProperty('answer_setup')]
    public ?string $answerSetup;

    /**
     * Where the rule goes. `every_group` (the default) adds it to every group that still has rules, so every
     * student must meet it; with no groups, it makes group 1. `new_group` adds a new group at the end, an
     * alternative to the others. `group` adds it to the one group named by `to_group`.
     *
     * @var ?value-of<AddScholarshipQualificationV1RequestPlacement> $placement
     */
    #[JsonProperty('placement')]
    public ?string $placement;

    /**
     * With `placement: "group"` only, and then required: the group's number as the scholarship was last read
     * (its `qualification_groups`). Example: `1`.
     *
     * @var ?int $toGroup
     */
    #[JsonProperty('to_group')]
    public ?int $toGroup;

    /**
     * @param array{
     *   questionId?: ?string,
     *   title?: ?string,
     *   comparison?: ?value-of<AddScholarshipQualificationV1RequestComparison>,
     *   value?: ?float,
     *   min?: ?float,
     *   max?: ?float,
     *   answers?: ?array<string>,
     *   answerSetup?: ?value-of<AddScholarshipQualificationV1RequestAnswerSetup>,
     *   placement?: ?value-of<AddScholarshipQualificationV1RequestPlacement>,
     *   toGroup?: ?int,
     * } $values
     */
    public function __construct(
        array $values = [],
    ) {
        $this->questionId = $values['questionId'] ?? null;
        $this->title = $values['title'] ?? null;
        $this->comparison = $values['comparison'] ?? null;
        $this->value = $values['value'] ?? null;
        $this->min = $values['min'] ?? null;
        $this->max = $values['max'] ?? null;
        $this->answers = $values['answers'] ?? null;
        $this->answerSetup = $values['answerSetup'] ?? null;
        $this->placement = $values['placement'] ?? null;
        $this->toGroup = $values['toGroup'] ?? null;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }
}
