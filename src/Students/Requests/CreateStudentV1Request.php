<?php

namespace Awardspring\Students\Requests;

use Awardspring\Core\Json\JsonSerializableType;
use Awardspring\Core\Json\JsonProperty;
use Awardspring\Students\Types\CreateStudentV1RequestIfEmailInUse;

class CreateStudentV1Request extends JsonSerializableType
{
    /**
     * @var ?string $firstName The student's first name. Required, at most 50 characters. Example: `"Jordan"`.
     */
    #[JsonProperty('first_name')]
    public ?string $firstName;

    /**
     * @var ?string $lastName The student's last name. Required, at most 50 characters. Example: `"Ellis"`.
     */
    #[JsonProperty('last_name')]
    public ?string $lastName;

    /**
     * The student's email, which is also how they sign in. Required, at most 50 characters, and not already
     * used by any account at the institution, ignoring upper and lower case, unless `if_email_in_use` is
     * `add_role`. Example: `"jordan.ellis@school.edu"`.
     *
     * @var ?string $email
     */
    #[JsonProperty('email')]
    public ?string $email;

    /**
     * Optional. The student number the institution assigned (for example an SIS or campus id), at most 50
     * characters, and not already on another account at the institution. Example: `"20260142"`.
     *
     * @var ?string $studentId
     */
    #[JsonProperty('student_id')]
    public ?string $studentId;

    /**
     * Optional. What to do when an account at the institution already has the email. `error`, the default,
     * returns `400 email_already_exists`. `add_role` does what the Users list's Add user window does:
     * the student role is added to that account instead of making a second one. Any other value returns
     * `400 validation_failed`.
     *
     * @var ?value-of<CreateStudentV1RequestIfEmailInUse> $ifEmailInUse
     */
    #[JsonProperty('if_email_in_use')]
    public ?string $ifEmailInUse;

    /**
     * @param array{
     *   firstName?: ?string,
     *   lastName?: ?string,
     *   email?: ?string,
     *   studentId?: ?string,
     *   ifEmailInUse?: ?value-of<CreateStudentV1RequestIfEmailInUse>,
     * } $values
     */
    public function __construct(
        array $values = [],
    ) {
        $this->firstName = $values['firstName'] ?? null;
        $this->lastName = $values['lastName'] ?? null;
        $this->email = $values['email'] ?? null;
        $this->studentId = $values['studentId'] ?? null;
        $this->ifEmailInUse = $values['ifEmailInUse'] ?? null;
    }
}
