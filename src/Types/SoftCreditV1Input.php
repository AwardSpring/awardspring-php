<?php

namespace Awardspring\Types;

use Awardspring\Core\Json\JsonSerializableType;
use Awardspring\Core\Json\JsonProperty;

/**
 * A single soft-credit recipient on a gift. Supply either UserId (the stored ID of an
 * existing person at this institution) or a free-form Name. When both are given, the
 * user ID wins and the name is dropped — mirroring the internal gift-entry flow.
 */
class SoftCreditV1Input extends JsonSerializableType
{
    /**
     * @var ?string $name
     */
    #[JsonProperty('name')]
    public ?string $name;

    /**
     * @var ?string $userId
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
