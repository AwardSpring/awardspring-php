<?php

namespace Awardspring\Types;

use Awardspring\Core\Json\JsonSerializableType;
use Awardspring\Core\Json\JsonProperty;

/**
 * One reviewer and applicant pair in a request. Name each person by email or by stored ID. When a person
 * has both, the ID is used.
 */
class ReviewerAssignmentRowV1 extends JsonSerializableType
{
    /**
     * @var ?string $reviewerId The reviewer's stored ID. Example: `"7HQ2KW9XR"`.
     */
    #[JsonProperty('reviewer_id')]
    public ?string $reviewerId;

    /**
     * @var ?string $reviewerEmail The reviewer's email, ignoring upper and lower case. Example: `"pat.lee@school.edu"`.
     */
    #[JsonProperty('reviewer_email')]
    public ?string $reviewerEmail;

    /**
     * @var ?string $applicantId The applicant's stored ID. Example: `"4MZ8RT2PW"`.
     */
    #[JsonProperty('applicant_id')]
    public ?string $applicantId;

    /**
     * @var ?string $applicantEmail The applicant's email, ignoring upper and lower case. Example: `"sam.ortiz@school.edu"`.
     */
    #[JsonProperty('applicant_email')]
    public ?string $applicantEmail;

    /**
     * @param array{
     *   reviewerId?: ?string,
     *   reviewerEmail?: ?string,
     *   applicantId?: ?string,
     *   applicantEmail?: ?string,
     * } $values
     */
    public function __construct(
        array $values = [],
    ) {
        $this->reviewerId = $values['reviewerId'] ?? null;
        $this->reviewerEmail = $values['reviewerEmail'] ?? null;
        $this->applicantId = $values['applicantId'] ?? null;
        $this->applicantEmail = $values['applicantEmail'] ?? null;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }
}
