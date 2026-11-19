<?php

namespace Awardspring\Types;

use Awardspring\Core\Json\JsonSerializableType;
use Awardspring\Core\Json\JsonProperty;
use Awardspring\Core\Types\ArrayType;

/**
 * One student account at the institution.
 */
class StudentV1 extends JsonSerializableType
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
     * @var ?string $id The stored ID of the student's user record. Use it with `GET /api/v1/students/{id}` and as the `user_id` filter on the applications and awards lists.
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
     * The student number the institution assigned (for example an SIS or campus id). Not
     * guaranteed unique — two student accounts can carry the same number — and may be empty
     * when the institution has not recorded one.
     *
     * @var ?string $studentId
     */
    #[JsonProperty('student_id')]
    public ?string $studentId;

    /**
     * The student's enrollment standing with the institution: `registered` (an active
     * student account) or `prospective` (added ahead of enrollment, e.g. a recruit).
     *
     * @var ?string $enrollmentStatus
     */
    #[JsonProperty('enrollment_status')]
    public ?string $enrollmentStatus;

    /**
     * @var ?int $createdDate When the student account was created, as UTC epoch seconds.
     */
    #[JsonProperty('created_date')]
    public ?int $createdDate;

    /**
     * The options the student picked on the Major question of the current award cycle's general
     * application, as the option text, in the order the question lists them. A choice the institution has
     * since removed from the question still shows, after the ones it lists. The question can take
     * several answers, so this may hold more than one. The options are written by the institution, so
     * these are the institution's own words and not a fixed list. Empty when the current cycle has no
     * Major question, the student has not answered it, or the institution has no current award cycle.
     *
     * @var ?array<string> $majors
     */
    #[JsonProperty('majors'), ArrayType(['string'])]
    public ?array $majors;

    /**
     * @param array{
     *   object?: ?string,
     *   id?: ?string,
     *   firstName?: ?string,
     *   lastName?: ?string,
     *   email?: ?string,
     *   studentId?: ?string,
     *   enrollmentStatus?: ?string,
     *   createdDate?: ?int,
     *   majors?: ?array<string>,
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
        $this->studentId = $values['studentId'] ?? null;
        $this->enrollmentStatus = $values['enrollmentStatus'] ?? null;
        $this->createdDate = $values['createdDate'] ?? null;
        $this->majors = $values['majors'] ?? null;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }
}
