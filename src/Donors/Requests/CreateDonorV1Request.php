<?php

namespace Awardspring\Donors\Requests;

use Awardspring\Core\Json\JsonSerializableType;
use Awardspring\Core\Json\JsonProperty;
use Awardspring\Donors\Types\CreateDonorV1RequestRole;

class CreateDonorV1Request extends JsonSerializableType
{
    /**
     * Optional email address — donors may have no email on file, matching the admin UI. When
     * provided it must be a valid email format, 250 characters or fewer, and not already in
     * use by another user in the institution. Note that email-less creates get no duplicate
     * protection from that uniqueness check, so retried requests should carry an
     * `Idempotency-Key` header to avoid creating the same donor twice.
     *
     * @var ?string $email
     */
    #[JsonProperty('email')]
    public ?string $email;

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
     * @var ?string $phone
     */
    #[JsonProperty('phone')]
    public ?string $phone;

    /**
     * @var ?string $organization
     */
    #[JsonProperty('organization')]
    public ?string $organization;

    /**
     * Whether this is an individual donor or a donor organization. Defaults to
     * `Individual` when omitted. Determines which name fields are required.
     *
     * @var ?value-of<CreateDonorV1RequestRole> $role
     */
    #[JsonProperty('role')]
    public ?string $role;

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
     * @var ?string $publicOrganizationName
     */
    #[JsonProperty('public_organization_name')]
    public ?string $publicOrganizationName;

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
     * @var ?bool $makeProfilePrivate
     */
    #[JsonProperty('make_profile_private')]
    public ?bool $makeProfilePrivate;

    /**
     * @var ?bool $includeSoftCredits
     */
    #[JsonProperty('include_soft_credits')]
    public ?bool $includeSoftCredits;

    /**
     * @param array{
     *   email?: ?string,
     *   firstName?: ?string,
     *   lastName?: ?string,
     *   phone?: ?string,
     *   organization?: ?string,
     *   role?: ?value-of<CreateDonorV1RequestRole>,
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
     *   publicFirstName?: ?string,
     *   publicLastName?: ?string,
     *   publicOrganizationName?: ?string,
     *   website?: ?string,
     *   facebookUrl?: ?string,
     *   twitterUrl?: ?string,
     *   linkedInUrl?: ?string,
     *   description?: ?string,
     *   notes?: ?string,
     *   makeProfilePrivate?: ?bool,
     *   includeSoftCredits?: ?bool,
     * } $values
     */
    public function __construct(
        array $values = [],
    ) {
        $this->email = $values['email'] ?? null;
        $this->firstName = $values['firstName'] ?? null;
        $this->lastName = $values['lastName'] ?? null;
        $this->phone = $values['phone'] ?? null;
        $this->organization = $values['organization'] ?? null;
        $this->role = $values['role'] ?? null;
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
        $this->publicFirstName = $values['publicFirstName'] ?? null;
        $this->publicLastName = $values['publicLastName'] ?? null;
        $this->publicOrganizationName = $values['publicOrganizationName'] ?? null;
        $this->website = $values['website'] ?? null;
        $this->facebookUrl = $values['facebookUrl'] ?? null;
        $this->twitterUrl = $values['twitterUrl'] ?? null;
        $this->linkedInUrl = $values['linkedInUrl'] ?? null;
        $this->description = $values['description'] ?? null;
        $this->notes = $values['notes'] ?? null;
        $this->makeProfilePrivate = $values['makeProfilePrivate'] ?? null;
        $this->includeSoftCredits = $values['includeSoftCredits'] ?? null;
    }
}
