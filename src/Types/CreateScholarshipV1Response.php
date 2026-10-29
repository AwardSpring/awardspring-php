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
     * The stored ID of the newly created scholarship. Stable for the lifetime of the scholarship. Null on
     * a dry run, which creates nothing. Example: `"7HQ2KW9XR"`.
     *
     * @var ?string $scholarshipId
     */
    #[JsonProperty('scholarship_id')]
    public ?string $scholarshipId;

    /**
     * @param array{
     *   scholarshipId?: ?string,
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
