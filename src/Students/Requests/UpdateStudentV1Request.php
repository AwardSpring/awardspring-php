<?php

namespace Awardspring\Students\Requests;

use Awardspring\Core\Json\JsonSerializableType;
use Awardspring\Core\Json\JsonProperty;

class UpdateStudentV1Request extends JsonSerializableType
{
    /**
     * The student's new first name, at most 250 characters. It cannot be blank: an empty value is refused rather
     * than clearing the name. Example: `"Jordan"`.
     *
     * @var ?string $firstName
     */
    #[JsonProperty('first_name')]
    public ?string $firstName;

    /**
     * The student's new last name, at most 250 characters. It cannot be blank: an empty value is refused rather
     * than clearing the name. Example: `"Ellis"`.
     *
     * @var ?string $lastName
     */
    #[JsonProperty('last_name')]
    public ?string $lastName;

    /**
     * The student's new email, which is also how they sign in. It must be a well-formed email of at most 250
     * characters, and not used by any other account at the institution, ignoring upper and lower case, including
     * an archived one; otherwise this returns `400 email_already_exists`. It cannot be blank. No email is
     * sent to the student about the change. Example: `"jordan.ellis@school.edu"`.
     *
     * @var ?string $email
     */
    #[JsonProperty('email')]
    public ?string $email;

    /**
     * The new student number the institution assigned (for example an SIS or campus id), at most 250 characters,
     * and not already on another account at the institution. An empty string clears it.
     * Example: `"20260142"`.
     *
     * @var ?string $studentId
     */
    #[JsonProperty('student_id')]
    public ?string $studentId;

    /**
     * The student's new phone number. Everything except digits and `+` is removed before it is saved, and
     * what remains can be at most 100 characters. An empty string clears it. Example: `"+1 (555) 123-4567"`,
     * saved as `"+15551234567"`.
     *
     * @var ?string $phone
     */
    #[JsonProperty('phone')]
    public ?string $phone;

    /**
     * @param array{
     *   firstName?: ?string,
     *   lastName?: ?string,
     *   email?: ?string,
     *   studentId?: ?string,
     *   phone?: ?string,
     * } $values
     */
    public function __construct(
        array $values = [],
    ) {
        $this->firstName = $values['firstName'] ?? null;
        $this->lastName = $values['lastName'] ?? null;
        $this->email = $values['email'] ?? null;
        $this->studentId = $values['studentId'] ?? null;
        $this->phone = $values['phone'] ?? null;
    }
}
