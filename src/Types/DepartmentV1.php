<?php

namespace Awardspring\Types;

use Awardspring\Core\Json\JsonSerializableType;
use Awardspring\Core\Json\JsonProperty;

/**
 * A department at the institution, as the Departments list shows it.
 */
class DepartmentV1 extends JsonSerializableType
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
     * @var ?string $id The department's stored ID.
     */
    #[JsonProperty('id')]
    public ?string $id;

    /**
     * @var ?string $name
     */
    #[JsonProperty('name')]
    public ?string $name;

    /**
     * @var ?int $scholarshipCount How many differently named scholarships belong to the department.
     */
    #[JsonProperty('scholarship_count')]
    public ?int $scholarshipCount;

    /**
     * @var ?int $adminCount How many administrators are limited to the department.
     */
    #[JsonProperty('admin_count')]
    public ?int $adminCount;

    /**
     * @param array{
     *   object?: ?string,
     *   id?: ?string,
     *   name?: ?string,
     *   scholarshipCount?: ?int,
     *   adminCount?: ?int,
     * } $values
     */
    public function __construct(
        array $values = [],
    ) {
        $this->object = $values['object'] ?? null;
        $this->id = $values['id'] ?? null;
        $this->name = $values['name'] ?? null;
        $this->scholarshipCount = $values['scholarshipCount'] ?? null;
        $this->adminCount = $values['adminCount'] ?? null;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }
}
