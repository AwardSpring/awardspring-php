<?php

namespace Awardspring\Types;

use Awardspring\Core\Json\JsonSerializableType;
use Awardspring\Core\Json\JsonProperty;

/**
 * The wording of the email sent to a student when they are awarded the scholarship.
 */
class ScholarshipAwardNotificationV1 extends JsonSerializableType
{
    /**
     * @var ?string $subject The email subject. Empty when the wording has no subject.
     */
    #[JsonProperty('subject')]
    public ?string $subject;

    /**
     * @var ?string $body The email body, with any merge fields left in place. Empty when the wording has no body.
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
