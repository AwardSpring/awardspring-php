<?php

namespace Awardspring\Types;

use Awardspring\Core\Json\JsonSerializableType;
use Awardspring\Core\Json\JsonProperty;

class SoftCreditV1 extends JsonSerializableType
{
    /**
     * The name recorded for the credit, or `null` when none was recorded, as for a credit that names a
     * person through `user_id`.
     *
     * @var ?string $name
     */
    #[JsonProperty('name')]
    public ?string $name;

    /**
     * @var ?string $userId The stored ID of the person credited, or `null` for a name-only credit.
     */
    #[JsonProperty('user_id')]
    public ?string $userId;

    /**
     * @param array{
     *   name?: ?string,
     *   userId?: ?string,
     * } $values
     */
    public function __construct(
        array $values = [],
    ) {
        $this->name = $values['name'] ?? null;
        $this->userId = $values['userId'] ?? null;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }
}
