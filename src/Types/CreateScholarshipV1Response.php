<?php

namespace Awardspring\Types;

use Awardspring\Core\Json\JsonSerializableType;
use Awardspring\Core\Json\JsonProperty;

/**
 * The result of `POST /api/v1/scholarships` — the identifier of the scholarship just created.
 */
class CreateScholarshipV1Response extends JsonSerializableType
{
    /**
     * AwardSpring-assigned identifier of the newly created scholarship (`Opportunity.Id`).
     * Stable for the lifetime of the scholarship. Example: `1234`.
     *
     * @var ?int $scholarshipId
     */
    #[JsonProperty('scholarship_id')]
    public ?int $scholarshipId;

    /**
     * @param array{
     *   scholarshipId?: ?int,
     * } $values
     */
    public function __construct(
        array $values = [],
    ) {
        $this->scholarshipId = $values['scholarshipId'] ?? null;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }
}
