<?php

namespace Awardspring\Types;

use Awardspring\Core\Json\JsonSerializableType;
use Awardspring\Core\Json\JsonProperty;
use Awardspring\Core\Types\ArrayType;

/**
 * One area on an administrator's Permissions tab, such as Scholarships or Users.
 */
class PersonPermissionCategoryV1 extends JsonSerializableType
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
     * @var ?string $category The area's stable key, for example `scholarships`.
     */
    #[JsonProperty('category')]
    public ?string $category;

    /**
     * @var ?string $name The area's name as the Permissions tab shows it.
     */
    #[JsonProperty('name')]
    public ?string $name;

    /**
     * @var ?bool $enabled True when the administrator may use this area at all.
     */
    #[JsonProperty('enabled')]
    public ?bool $enabled;

    /**
     * @var ?array<PersonPermissionV1> $items The finer permissions inside the area. Empty when the area has none.
     */
    #[JsonProperty('items'), ArrayType([PersonPermissionV1::class])]
    public ?array $items;

    /**
     * @param array{
     *   object?: ?string,
     *   category?: ?string,
     *   name?: ?string,
     *   enabled?: ?bool,
     *   items?: ?array<PersonPermissionV1>,
     * } $values
     */
    public function __construct(
        array $values = [],
    ) {
        $this->object = $values['object'] ?? null;
        $this->category = $values['category'] ?? null;
        $this->name = $values['name'] ?? null;
        $this->enabled = $values['enabled'] ?? null;
        $this->items = $values['items'] ?? null;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }
}
