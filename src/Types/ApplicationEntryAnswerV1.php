<?php

namespace Awardspring\Types;

use Awardspring\Core\Json\JsonSerializableType;
use Awardspring\Core\Json\JsonProperty;

/**
 * One answer to an entry question on the student's general application for the award cycle.
 */
class ApplicationEntryAnswerV1 extends JsonSerializableType
{
    /**
     * @var ?string $section The name of the application section the entry question sits in. Always Entry Questions.
     */
    #[JsonProperty('section')]
    public ?string $section;

    /**
     * @var ?string $questionId The stored ID of the entry question.
     */
    #[JsonProperty('question_id')]
    public ?string $questionId;

    /**
     * @var ?string $question The question as this award cycle's application words it.
     */
    #[JsonProperty('question')]
    public ?string $question;

    /**
     * @var ?string $answer What the student answered. Chosen options are joined with a comma and a space and a number is given as the student entered it.
     */
    #[JsonProperty('answer')]
    public ?string $answer;

    /**
     * @param array{
     *   section?: ?string,
     *   questionId?: ?string,
     *   question?: ?string,
     *   answer?: ?string,
     * } $values
     */
    public function __construct(
        array $values = [],
    ) {
        $this->section = $values['section'] ?? null;
        $this->questionId = $values['questionId'] ?? null;
        $this->question = $values['question'] ?? null;
        $this->answer = $values['answer'] ?? null;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }
}
