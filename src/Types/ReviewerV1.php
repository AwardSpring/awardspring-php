<?php

namespace Awardspring\Types;

use Awardspring\Core\Json\JsonSerializableType;
use Awardspring\Core\Json\JsonProperty;

/**
 * One reviewer account at the institution: a person who reads and scores applications.
 */
class ReviewerV1 extends JsonSerializableType
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
     * @var ?string $id The stored ID of the reviewer's user record. It is also their `id` in `GET /api/v1/people`.
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
     * @var ?int $createdDate When the reviewer account was created, as UTC epoch seconds.
     */
    #[JsonProperty('created_date')]
    public ?int $createdDate;

    /**
     * @param array{
     *   object?: ?string,
     *   id?: ?string,
     *   firstName?: ?string,
     *   lastName?: ?string,
     *   email?: ?string,
     *   createdDate?: ?int,
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
        $this->createdDate = $values['createdDate'] ?? null;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }
}
