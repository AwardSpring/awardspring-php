<?php

namespace Awardspring\Types;

use Awardspring\Core\Json\JsonSerializableType;
use Awardspring\Core\Json\JsonProperty;

/**
 * New award notification wording. A part left out, or sent as `null`, keeps its current wording. Merge fields
 * are written `{{FieldName}}`; one AwardSpring does not fill in is refused, naming it.
 */
class UpdateScholarshipAwardNotificationV1Request extends JsonSerializableType
{
    /**
     * @var ?string $subject The new email subject. It cannot be blank. Example: `"Congratulations, {{StudentFirstName}}!"`.
     */
    #[JsonProperty('subject')]
    public ?string $subject;

    /**
     * The new email body, as HTML. It cannot be blank.
     * Example: `"<p>You have been awarded the {{ScholarshipTitle}}.</p>"`.
     *
     * @var ?string $body
     */
    #[JsonProperty('body')]
    public ?string $body;

    /**
     * @param array{
     *   subject?: ?string,
     *   body?: ?string,
     * } $values
     */
    public function __construct(
        array $values = [],
    ) {
        $this->subject = $values['subject'] ?? null;
        $this->body = $values['body'] ?? null;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }
}
