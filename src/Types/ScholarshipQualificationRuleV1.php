<?php

namespace Awardspring\Types;

use Awardspring\Core\Json\JsonSerializableType;
use Awardspring\Core\Json\JsonProperty;
use Awardspring\Core\Types\ArrayType;

/**
 * One qualification rule. A `question` rule checks the student's answer to one application question; an
 * `institutional_award` rule checks whether the student has won another scholarship.
 */
class ScholarshipQualificationRuleV1 extends JsonSerializableType
{
    /**
     * @var ?value-of<ScholarshipQualificationRuleV1Kind> $kind `"question"`, or `"institutional_award"` (the student must have won another scholarship).
     */
    #[JsonProperty('kind')]
    public ?string $kind;

    /**
     * @var ?string $title The rule's title, as the Qualifications tab shows it. Example: `"GPA 3.0 or higher"`.
     */
    #[JsonProperty('title')]
    public ?string $title;

    /**
     * On an `institutional_award` rule, the stored ID of the scholarship the student must have won. Null on a
     * `question` rule. Example: `"9QW3ZK7HD"`.
     *
     * @var ?string $institutionalAwardScholarshipId
     */
    #[JsonProperty('institutional_award_scholarship_id')]
    public ?string $institutionalAwardScholarshipId;

    /**
     * The stored ID of the application question the rule checks. Always set on a `question` rule; null on an
     * `institutional_award` rule. Example: `"4MZ8RT2QX"`.
     *
     * @var ?string $questionId
     */
    #[JsonProperty('question_id')]
    public ?string $questionId;

    /**
     * @var ?string $question The question's wording, for people reading the answer. Null on an `institutional_award` rule. Never accepted on input.
     */
    #[JsonProperty('question')]
    public ?string $question;

    /**
     * The kind of question, which says which of the fields below apply: `one_choice`, `multiple_choice`
     * (the student must have chosen every listed answer), `multiple_choice_any` (any one listed answer is
     * enough), `major`, `degree`, `academic_status`, `number`, `number_range`,
     * `high_school_gpa`, `college_gpa` or `expense_ratio`. Null on an `institutional_award`
     * rule. Never accepted on input.
     *
     * @var ?value-of<ScholarshipQualificationRuleV1QuestionType> $questionType
     */
    #[JsonProperty('question_type')]
    public ?string $questionType;

    /**
     * A number rule's comparison: `at_least` (>=), `at_most` (<=), `more_than` (>),
     * `less_than` (<), `equals` (=) or `between` (`min` to `max`). Null on a choice
     * rule.
     *
     * @var ?value-of<ScholarshipQualificationRuleV1Comparison> $comparison
     */
    #[JsonProperty('comparison')]
    public ?string $comparison;

    /**
     * @var ?float $value The number compared against, when `comparison` is not `between`. Null otherwise. Example: `3.0`.
     */
    #[JsonProperty('value')]
    public ?float $value;

    /**
     * @var ?float $min The lowest accepted number, with `between` or on a `number_range` question. Null otherwise.
     */
    #[JsonProperty('min')]
    public ?float $min;

    /**
     * @var ?float $max The highest accepted number, with `between` or on a `number_range` question. Null otherwise.
     */
    #[JsonProperty('max')]
    public ?float $max;

    /**
     * A choice rule's accepted answers, by name, in the question's order. Null on a number rule. Example:
     * `["Nursing", "Nursing (RN to BSN)"]`.
     *
     * @var ?array<string> $answers
     */
    #[JsonProperty('answers'), ArrayType(['string'])]
    public ?array $answers;

    /**
     * For `major`, `degree` and `academic_status` rules only: `any_of` (any listed answer
     * qualifies) or `only_this_one` (exactly one answer is listed). Null on every other rule.
     *
     * @var ?value-of<ScholarshipQualificationRuleV1AnswerSetup> $answerSetup
     */
    #[JsonProperty('answer_setup')]
    public ?string $answerSetup;

    /**
     * True when the question has been retired since the rule was made, which the Qualifications tab shows as a
     * conflict. Always false on an `institutional_award` rule.
     *
     * @var ?bool $questionInactive
     */
    #[JsonProperty('question_inactive')]
    public ?bool $questionInactive;

    /**
     * @param array{
     *   kind?: ?value-of<ScholarshipQualificationRuleV1Kind>,
     *   title?: ?string,
     *   institutionalAwardScholarshipId?: ?string,
     *   questionId?: ?string,
     *   question?: ?string,
     *   questionType?: ?value-of<ScholarshipQualificationRuleV1QuestionType>,
     *   comparison?: ?value-of<ScholarshipQualificationRuleV1Comparison>,
     *   value?: ?float,
     *   min?: ?float,
     *   max?: ?float,
     *   answers?: ?array<string>,
     *   answerSetup?: ?value-of<ScholarshipQualificationRuleV1AnswerSetup>,
     *   questionInactive?: ?bool,
     * } $values
     */
    public function __construct(
        array $values = [],
    ) {
        $this->kind = $values['kind'] ?? null;
        $this->title = $values['title'] ?? null;
        $this->institutionalAwardScholarshipId = $values['institutionalAwardScholarshipId'] ?? null;
        $this->questionId = $values['questionId'] ?? null;
        $this->question = $values['question'] ?? null;
        $this->questionType = $values['questionType'] ?? null;
        $this->comparison = $values['comparison'] ?? null;
        $this->value = $values['value'] ?? null;
        $this->min = $values['min'] ?? null;
        $this->max = $values['max'] ?? null;
        $this->answers = $values['answers'] ?? null;
        $this->answerSetup = $values['answerSetup'] ?? null;
        $this->questionInactive = $values['questionInactive'] ?? null;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }
}
