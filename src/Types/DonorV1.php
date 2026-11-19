<?php

namespace Awardspring\Types;

use Awardspring\Core\Json\JsonSerializableType;
use Awardspring\Core\Json\JsonProperty;

class DonorV1 extends JsonSerializableType
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
     * The donor's stored ID: 9 characters, sent exactly as stored. A dry-run create returns
     * `null`, because no donor was saved.
     *
     * @var ?string $id
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
     * @var ?string $phone
     */
    #[JsonProperty('phone')]
    public ?string $phone;

    /**
     * Whether this is an individual donor or a donor organization: `Individual` or
     * `Organization`. Mirrors the `role` field on the create request.
     *
     * @var ?value-of<DonorV1Role> $role
     */
    #[JsonProperty('role')]
    public ?string $role;

    /**
     * Whether the donor's record is archived, the way AwardSpring shows it. `true` when the
     * account is not active, or when the person is a donor only through an extra Donor role and that role
     * is archived; `false` for an active donor, and again once the donor is restored. Archiving and
     * restoring each move `last_changed_at`, so a donors list requested with `changed_since`
     * lists an archived donor marked `true` and a restored one marked `false`; archiving never
     * removes a donor from that list.
     *
     * @var ?bool $archived
     */
    #[JsonProperty('archived')]
    public ?bool $archived;

    /**
     * @var ?int $dateOfBirth
     */
    #[JsonProperty('date_of_birth')]
    public ?int $dateOfBirth;

    /**
     * @var ?string $birthMonth
     */
    #[JsonProperty('birth_month')]
    public ?string $birthMonth;

    /**
     * @var ?int $birthDay
     */
    #[JsonProperty('birth_day')]
    public ?int $birthDay;

    /**
     * @var ?int $birthYear
     */
    #[JsonProperty('birth_year')]
    public ?int $birthYear;

    /**
     * @var ?string $address1
     */
    #[JsonProperty('address1')]
    public ?string $address1;

    /**
     * @var ?string $address2
     */
    #[JsonProperty('address2')]
    public ?string $address2;

    /**
     * @var ?string $city
     */
    #[JsonProperty('city')]
    public ?string $city;

    /**
     * @var ?string $state
     */
    #[JsonProperty('state')]
    public ?string $state;

    /**
     * @var ?string $county
     */
    #[JsonProperty('county')]
    public ?string $county;

    /**
     * @var ?string $zipCode
     */
    #[JsonProperty('zip_code')]
    public ?string $zipCode;

    /**
     * @var ?int $country
     */
    #[JsonProperty('country')]
    public ?int $country;

    /**
     * @var ?string $jobTitle
     */
    #[JsonProperty('job_title')]
    public ?string $jobTitle;

    /**
     * @var ?string $workEmail
     */
    #[JsonProperty('work_email')]
    public ?string $workEmail;

    /**
     * @var ?string $workPhone
     */
    #[JsonProperty('work_phone')]
    public ?string $workPhone;

    /**
     * @var ?string $companyName
     */
    #[JsonProperty('company_name')]
    public ?string $companyName;

    /**
     * @var ?string $organizationName
     */
    #[JsonProperty('organization_name')]
    public ?string $organizationName;

    /**
     * @var ?string $publicOrganizationName
     */
    #[JsonProperty('public_organization_name')]
    public ?string $publicOrganizationName;

    /**
     * @var ?bool $makeProfilePrivate
     */
    #[JsonProperty('make_profile_private')]
    public ?bool $makeProfilePrivate;

    /**
     * @var ?string $publicFirstName
     */
    #[JsonProperty('public_first_name')]
    public ?string $publicFirstName;

    /**
     * @var ?string $publicLastName
     */
    #[JsonProperty('public_last_name')]
    public ?string $publicLastName;

    /**
     * @var ?string $website
     */
    #[JsonProperty('website')]
    public ?string $website;

    /**
     * @var ?string $facebookUrl
     */
    #[JsonProperty('facebook_url')]
    public ?string $facebookUrl;

    /**
     * @var ?string $twitterUrl
     */
    #[JsonProperty('twitter_url')]
    public ?string $twitterUrl;

    /**
     * @var ?string $linkedInUrl
     */
    #[JsonProperty('linked_in_url')]
    public ?string $linkedInUrl;

    /**
     * @var ?string $photoUrl
     */
    #[JsonProperty('photo_url')]
    public ?string $photoUrl;

    /**
     * @var ?string $description
     */
    #[JsonProperty('description')]
    public ?string $description;

    /**
     * @var ?string $notes
     */
    #[JsonProperty('notes')]
    public ?string $notes;

    /**
     * @var ?bool $includeSoftCredits
     */
    #[JsonProperty('include_soft_credits')]
    public ?bool $includeSoftCredits;

    /**
     * When anything stored on the donor that the API returns last changed, in UTC epoch seconds: their
     * name, contact details, profile, whether their donor record is archived, the tags on them, their answers
     * to the school's donor custom fields, or which kinds of email they are opted out of. Renaming a tag, a
     * custom field or one of its choices does not count, because it changes the school's list, not the
     * donor. Adding the donor counts as
     * a change; their gifts and pledges do not. Null only on a dry-run create, because no donor was saved.
     * Use it with the donors list's `changed_since` to pick up only the donors that changed.
     *
     * @var ?int $lastChangedAt
     */
    #[JsonProperty('last_changed_at')]
    public ?int $lastChangedAt;

    /**
     * @param array{
     *   object?: ?string,
     *   id?: ?string,
     *   firstName?: ?string,
     *   lastName?: ?string,
     *   email?: ?string,
     *   phone?: ?string,
     *   role?: ?value-of<DonorV1Role>,
     *   archived?: ?bool,
     *   dateOfBirth?: ?int,
     *   birthMonth?: ?string,
     *   birthDay?: ?int,
     *   birthYear?: ?int,
     *   address1?: ?string,
     *   address2?: ?string,
     *   city?: ?string,
     *   state?: ?string,
     *   county?: ?string,
     *   zipCode?: ?string,
     *   country?: ?int,
     *   jobTitle?: ?string,
     *   workEmail?: ?string,
     *   workPhone?: ?string,
     *   companyName?: ?string,
     *   organizationName?: ?string,
     *   publicOrganizationName?: ?string,
     *   makeProfilePrivate?: ?bool,
     *   publicFirstName?: ?string,
     *   publicLastName?: ?string,
     *   website?: ?string,
     *   facebookUrl?: ?string,
     *   twitterUrl?: ?string,
     *   linkedInUrl?: ?string,
     *   photoUrl?: ?string,
     *   description?: ?string,
     *   notes?: ?string,
     *   includeSoftCredits?: ?bool,
     *   lastChangedAt?: ?int,
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
        $this->phone = $values['phone'] ?? null;
        $this->role = $values['role'] ?? null;
        $this->archived = $values['archived'] ?? null;
        $this->dateOfBirth = $values['dateOfBirth'] ?? null;
        $this->birthMonth = $values['birthMonth'] ?? null;
        $this->birthDay = $values['birthDay'] ?? null;
        $this->birthYear = $values['birthYear'] ?? null;
        $this->address1 = $values['address1'] ?? null;
        $this->address2 = $values['address2'] ?? null;
        $this->city = $values['city'] ?? null;
        $this->state = $values['state'] ?? null;
        $this->county = $values['county'] ?? null;
        $this->zipCode = $values['zipCode'] ?? null;
        $this->country = $values['country'] ?? null;
        $this->jobTitle = $values['jobTitle'] ?? null;
        $this->workEmail = $values['workEmail'] ?? null;
        $this->workPhone = $values['workPhone'] ?? null;
        $this->companyName = $values['companyName'] ?? null;
        $this->organizationName = $values['organizationName'] ?? null;
        $this->publicOrganizationName = $values['publicOrganizationName'] ?? null;
        $this->makeProfilePrivate = $values['makeProfilePrivate'] ?? null;
        $this->publicFirstName = $values['publicFirstName'] ?? null;
        $this->publicLastName = $values['publicLastName'] ?? null;
        $this->website = $values['website'] ?? null;
        $this->facebookUrl = $values['facebookUrl'] ?? null;
        $this->twitterUrl = $values['twitterUrl'] ?? null;
        $this->linkedInUrl = $values['linkedInUrl'] ?? null;
        $this->photoUrl = $values['photoUrl'] ?? null;
        $this->description = $values['description'] ?? null;
        $this->notes = $values['notes'] ?? null;
        $this->includeSoftCredits = $values['includeSoftCredits'] ?? null;
        $this->lastChangedAt = $values['lastChangedAt'] ?? null;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }
}
