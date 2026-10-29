<?php

namespace Awardspring\Types;

use Awardspring\Core\Json\JsonSerializableType;
use Awardspring\Core\Json\JsonProperty;

/**
 * One finer permission inside an area of the Permissions tab.
 */
class PersonPermissionV1 extends JsonSerializableType
{
    /**
     * Stripe-style resource discriminator. Stable, snake_case string naming the resource type.
     * Serialized first so it reads as the leading field of every V1 resource body.
     *
     * @var ?string $object
     */
    #[JsonProperty('object')]
    public ?string $object;

    /**
     * @var ?string $key The permission's stable key, for example `scholarships.qualifications`.
     */
    #[JsonProperty('key')]
    public ?string $key;

    /**
     * @var ?string $name The permission's name as the Permissions tab shows it.
     */
    #[JsonProperty('name')]
    public ?string $name;

    /**
     * The administrator's access: `none`, `view` or `edit` for a permission with access levels,
     * and `no` or `yes` for a permission that is simply on or off.
     *
     * @var ?value-of<PersonPermissionV1Access> $access
     */
    #[JsonProperty('access')]
    public ?string $access;

    /**
     * @param array{
     *   object?: ?string,
     *   key?: ?string,
     *   name?: ?string,
     *   access?: ?value-of<PersonPermissionV1Access>,
     * } $values
     */
    public function __construct(
        array $values = [],
    ) {
        $this->object = $values['object'] ?? null;
        $this->key = $values['key'] ?? null;
        $this->name = $values['name'] ?? null;
        $this->access = $values['access'] ?? null;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }
}
