<?php

namespace Awardspring\Types;

use Awardspring\Core\Json\JsonSerializableType;
use Awardspring\Core\Json\JsonProperty;
use Awardspring\Core\Types\ArrayType;

/**
 * One person with an account at the institution, as the institution's Users list shows them.
 */
class PersonV1 extends JsonSerializableType
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
     * @var ?string $id The stored ID of the person's user record.
     */
    #[JsonProperty('id')]
    public ?string $id;

    /**
     * @var ?string $firstName
     */
    #[JsonProperty('first_name')]
    public ?string $firstName;

    /**
     * @var ?string $lastName
     */
    #[JsonProperty('last_name')]
    public ?string $lastName;

    /**
     * @var ?string $email
     */
    #[JsonProperty('email')]
    public ?string $email;

    /**
     * The person's main role: `super_administrator`, `administrator`, `department_admin`,
     * `reviewer`, `student`, `recommender`, `donor`, `organization` or
     * `client_service_admin`.
     *
     * @var ?value-of<PersonV1Role> $role
     */
    #[JsonProperty('role')]
    public ?string $role;

    /**
     * @var ?array<string> $roles Every role the person holds: the main role first, then any other role on the same account.
     */
    #[JsonProperty('roles'), ArrayType(['string'])]
    public ?array $roles;

    /**
     * The departments an administrator is limited to. An administrator with departments works only with what
     * belongs to those departments. An empty list means the person is not limited to departments, except for a
     * `department_admin`, who is always limited and reaches no department until one is listed here.
     *
     * @var ?array<PersonDepartmentV1> $departments
     */
    #[JsonProperty('departments'), ArrayType([PersonDepartmentV1::class])]
    public ?array $departments;

    /**
     * @var ?bool $isArchived True when the account is archived: the person cannot sign in until it is restored.
     */
    #[JsonProperty('is_archived')]
    public ?bool $isArchived;

    /**
     * What the administrator may use, as their Permissions tab shows it. Null for anyone the Permissions tab
     * does not manage: Super Administrators, people who are not administrators, and every person at an
     * institution where all administrators have full access.
     *
     * @var ?array<PersonPermissionCategoryV1> $permissions
     */
    #[JsonProperty('permissions'), ArrayType([PersonPermissionCategoryV1::class])]
    public ?array $permissions;

    /**
     * @param array{
     *   object?: ?string,
     *   id?: ?string,
     *   firstName?: ?string,
     *   lastName?: ?string,
     *   email?: ?string,
     *   role?: ?value-of<PersonV1Role>,
     *   roles?: ?array<string>,
     *   departments?: ?array<PersonDepartmentV1>,
     *   isArchived?: ?bool,
     *   permissions?: ?array<PersonPermissionCategoryV1>,
     * } $values
     */
    public function __construct(
        array $values = [],
    ) {
        $this->object = $values['object'] ?? null;
        $this->id = $values['id'] ?? null;
        $this->firstName = $values['firstName'] ?? null;
        $this->lastName = $values['lastName'] ?? null;
        $this->email = $values['email'] ?? null;
        $this->role = $values['role'] ?? null;
        $this->roles = $values['roles'] ?? null;
        $this->departments = $values['departments'] ?? null;
        $this->isArchived = $values['isArchived'] ?? null;
        $this->permissions = $values['permissions'] ?? null;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }
}
