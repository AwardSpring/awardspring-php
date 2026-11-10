<?php

namespace Awardspring\Types;

use Awardspring\Core\Json\JsonSerializableType;
use Awardspring\Core\Json\JsonProperty;

/**
 * A reviewer or an applicant on a reviewer assignment.
 */
class ReviewerAssignmentPersonV1 extends JsonSerializableType
{
    /**
     * @var ?string $id The stored ID of the person's user record. It is also their `id` in `GET /api/v1/people`.
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
     * @param array{
     *   id?: ?string,
     *   firstName?: ?string,
     *   lastName?: ?string,
     *   email?: ?string,
     * } $values
     */
    public function __construct(
        array $values = [],
    ) {
        $this->id = $values['id'] ?? null;
        $this->firstName = $values['firstName'] ?? null;
        $this->lastName = $values['lastName'] ?? null;
        $this->email = $values['email'] ?? null;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }
}
