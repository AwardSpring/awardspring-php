<?php

namespace Awardspring\Reviewers\Requests;

use Awardspring\Core\Json\JsonSerializableType;
use Awardspring\Core\Json\JsonProperty;
use Awardspring\Reviewers\Types\CreateReviewerV1RequestIfEmailInUse;

class CreateReviewerV1Request extends JsonSerializableType
{
    /**
     * @var ?string $firstName The reviewer's first name. Required, at most 50 characters. Example: `"Pat"`.
     */
    #[JsonProperty('first_name')]
    public ?string $firstName;

    /**
     * @var ?string $lastName The reviewer's last name. Required, at most 50 characters. Example: `"Lee"`.
     */
    #[JsonProperty('last_name')]
    public ?string $lastName;

    /**
     * The reviewer's email, which is also how they sign in. Required, at most 50 characters, and not already
     * used by any account at the institution, ignoring upper and lower case, unless `if_email_in_use` is
     * `add_role`. Example: `"pat.lee@school.edu"`.
     *
     * @var ?string $email
     */
    #[JsonProperty('email')]
    public ?string $email;

    /**
     * Optional. What to do when an account at the institution already has the email. `error`, the default,
     * returns `400 email_already_exists`. `add_role` does what the Users list's Add user window does:
     * the reviewer role is added to that account instead of making a second one. Any other value returns
     * `400 validation_failed`.
     *
     * @var ?value-of<CreateReviewerV1RequestIfEmailInUse> $ifEmailInUse
     */
    #[JsonProperty('if_email_in_use')]
    public ?string $ifEmailInUse;

    /**
     * @param array{
     *   firstName?: ?string,
     *   lastName?: ?string,
     *   email?: ?string,
     *   ifEmailInUse?: ?value-of<CreateReviewerV1RequestIfEmailInUse>,
     * } $values
     */
    public function __construct(
        array $values = [],
    ) {
        $this->firstName = $values['firstName'] ?? null;
        $this->lastName = $values['lastName'] ?? null;
        $this->email = $values['email'] ?? null;
        $this->ifEmailInUse = $values['ifEmailInUse'] ?? null;
    }
}
