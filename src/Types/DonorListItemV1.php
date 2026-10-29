<?php

namespace Awardspring\Types;

use Awardspring\Core\Json\JsonSerializableType;
use Awardspring\Core\Json\JsonProperty;

class DonorListItemV1 extends JsonSerializableType
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
     * @var ?string $id The donor's stored ID: 9 characters, sent exactly as stored.
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
     * @var ?string $organization
     */
    #[JsonProperty('organization')]
    public ?string $organization;

    /**
     * @var ?string $phone
     */
    #[JsonProperty('phone')]
    public ?string $phone;

    /**
     * Whether this is an individual donor or a donor organization: `Individual` or
     * `Organization`. Mirrors the `role` field on the create request.
     *
     * @var ?value-of<DonorListItemV1Role> $role
     */
    #[JsonProperty('role')]
    public ?string $role;

    /**
     * The donor's level: `Annual`, `Mid`, `Major` or `Principal`. It is the
     * level the institution's donor portal shows this donor, worked out from the total of every
     * gift recorded for them: under $1,000 is `Annual`, from $1,000 is `Mid`, from
     * $25,000 is `Major` and from $1,000,000 is `Principal`. When staff have set or held
     * the donor's level by hand, that level is shown instead. Null when the request is made as a
     * signed-in user who may not see donors' giving.
     *
     * @var ?value-of<DonorListItemV1Level> $level
     */
    #[JsonProperty('level')]
    public ?string $level;

    /**
     * When the donor was last contacted: the date of the newest email, phone call, meeting or
     * note logged with them before today, in the institution's time zone. Gifts, pledges and
     * emails sent by AwardSpring do not count. Null when no contact has been logged.
     *
     * @var ?int $lastContactedAt
     */
    #[JsonProperty('last_contacted_at')]
    public ?int $lastContactedAt;

    /**
     * The donor's lifetime giving, the same figure the donor's page in AwardSpring shows: every
     * gift recorded for them up to now, plus their soft credits when their record is set to
     * include soft credits. `0` for a donor with no gifts. Null when the request is made as
     * a signed-in user who may not see donors' giving.
     *
     * @var ?float $lifetimeGiving
     */
    #[JsonProperty('lifetime_giving')]
    public ?float $lifetimeGiving;

    /**
     * The donor's giving status: `Recurring`, `New` or `Lapsed`.
     * `Recurring` means the donor has a recurring gift that is still running (it has not been
     * cancelled or stopped, and its end date, if it has one, has not arrived) and at least one
     * payment from it has gone through. Otherwise the donor's gift dates decide: `New` means
     * their first gift was in the last 12 months, and `Lapsed` means their last gift was more
     * than 12 months ago. The 12 months count back from the moment of the request, not from the
     * start of a fiscal year. A donor who fits more than one is `Recurring` before `New`,
     * and `New` before `Lapsed`. Null for a donor who has never given, or whose first gift
     * was more than 12 months ago and who has given again within the last 12 months. Also null
     * when the request is made as a signed-in user who may not see donors' giving.
     *
     * @var ?value-of<DonorListItemV1GivingStatus> $givingStatus
     */
    #[JsonProperty('giving_status')]
    public ?string $givingStatus;

    /**
     * @param array{
     *   object?: ?string,
     *   id?: ?string,
     *   firstName?: ?string,
     *   lastName?: ?string,
     *   email?: ?string,
     *   organization?: ?string,
     *   phone?: ?string,
     *   role?: ?value-of<DonorListItemV1Role>,
     *   level?: ?value-of<DonorListItemV1Level>,
     *   lastContactedAt?: ?int,
     *   lifetimeGiving?: ?float,
     *   givingStatus?: ?value-of<DonorListItemV1GivingStatus>,
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
        $this->organization = $values['organization'] ?? null;
        $this->phone = $values['phone'] ?? null;
        $this->role = $values['role'] ?? null;
        $this->level = $values['level'] ?? null;
        $this->lastContactedAt = $values['lastContactedAt'] ?? null;
        $this->lifetimeGiving = $values['lifetimeGiving'] ?? null;
        $this->givingStatus = $values['givingStatus'] ?? null;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }
}
