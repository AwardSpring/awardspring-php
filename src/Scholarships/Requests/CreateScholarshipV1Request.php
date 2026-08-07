<?php

namespace Awardspring\Scholarships\Requests;

use Awardspring\Core\Json\JsonSerializableType;
use Awardspring\Core\Json\JsonProperty;
use Awardspring\Core\Types\ArrayType;

class CreateScholarshipV1Request extends JsonSerializableType
{
    /**
     * @var ?string $scholarshipName Human-readable name of the scholarship. Required, non-whitespace. Example: `"Spring 2026 STEM Award"`.
     */
    #[JsonProperty('scholarship_name')]
    public ?string $scholarshipName;

    /**
     * Optional external fund identifier (matches a `Fund.FundIdName` in the tenant's funds
     * directory). When Funds Management is enabled and the supplied ID does not match an existing
     * fund, a new fund row is created automatically. When it matches, the scholarship is linked to
     * that fund. Example: `"GEN-2026"`.
     *
     * @var ?string $fundId
     */
    #[JsonProperty('fund_id')]
    public ?string $fundId;

    /**
     * Free-form description of the scholarship. Required, non-whitespace. Shown to applicants in
     * the scholarship details panel. Example: `"Award for incoming STEM majors with financial need."`.
     *
     * @var ?string $scholarshipDescription
     */
    #[JsonProperty('scholarship_description')]
    public ?string $scholarshipDescription;

    /**
     * Whether this is a Special Funds scholarship (a tenant-controlled cap on how many of these
     * can exist per award cycle). Defaults to `false`. Cannot be combined with
     * IsInstitutionalAwardScholarship — a request with both set to `true` is
     * rejected with a `validation_failed` error.
     *
     * @var ?bool $isSpecialFunds
     */
    #[JsonProperty('is_special_funds')]
    public ?bool $isSpecialFunds;

    /**
     * @var int $awardCycleId AwardSpring award-cycle identifier this scholarship belongs to. Required. Example: `12`.
     */
    #[JsonProperty('award_cycle_id')]
    public int $awardCycleId;

    /**
     * ISO 8601 datetime when applicants can begin applying. Example: `"2026-08-01T00:00:00"`.
     * Interpreted in the tenant's configured time zone.
     *
     * @var ?string $applicationStartDate
     */
    #[JsonProperty('application_start_date')]
    public ?string $applicationStartDate;

    /**
     * ISO 8601 datetime when applications close. The persistence layer normalizes this to the end
     * of the chosen day. Example: `"2026-10-15T23:59:59"`. Must be after
     * ApplicationStartDate and within the parent award cycle's date range.
     *
     * @var ?string $applicationEndDate
     */
    #[JsonProperty('application_end_date')]
    public ?string $applicationEndDate;

    /**
     * ISO 8601 date of the first disbursement to awarded students. Required — the admin UI
     * requires it on every scholarship, and this endpoint enforces the same rule. Defaults are
     * not applied server-side; send the institution's intended first payment date. Example:
     * `"2027-01-15"`.
     *
     * @var ?string $disbursementDate
     */
    #[JsonProperty('disbursement_date')]
    public ?string $disbursementDate;

    /**
     * Optional label for the first disbursement's academic term, shown alongside the
     * disbursement date. Maximum 50 characters. Example: `"Spring 2027"`.
     *
     * @var ?string $firstDisbursementTermName
     */
    #[JsonProperty('first_disbursement_term_name')]
    public ?string $firstDisbursementTermName;

    /**
     * Optional total number of recipients to award. Combined with TotalScholarshipValue
     * to derive a per-award amount. Must be a positive integer (1 to 999,999) when supplied. Example: `10`.
     *
     * @var ?int $totalAwardsNumber
     */
    #[JsonProperty('total_awards_number')]
    public ?int $totalAwardsNumber;

    /**
     * Optional total dollar value of the scholarship across all recipients. Combined with
     * TotalAwardsNumber to derive a per-award amount. Must be non-negative when
     * supplied. Example: `50000.00`.
     *
     * @var ?float $totalScholarshipValue
     */
    #[JsonProperty('total_scholarship_value')]
    public ?float $totalScholarshipValue;

    /**
     * Optional number of payments each recipient receives. Must be 1 or greater when supplied.
     * Example: `2`.
     *
     * @var ?int $paymentsPerAward
     */
    #[JsonProperty('payments_per_award')]
    public ?int $paymentsPerAward;

    /**
     * Optional AwardSpring department ID the scholarship is owned by. Used for Department Admin
     * scoping. Example: `3`.
     *
     * @var ?int $departmentId
     */
    #[JsonProperty('department_id')]
    public ?int $departmentId;

    /**
     * Optional list of donor or donor-organization user IDs associated with the scholarship.
     * Example: `[101, 102]`.
     *
     * @var ?array<int> $donorIds
     */
    #[JsonProperty('donor_ids'), ArrayType(['integer'])]
    public ?array $donorIds;

    /**
     * Whether the scholarship is deactivated on create (rare — typically left `false`).
     * Example: `false`.
     *
     * @var ?bool $isDeactivateScholarship
     */
    #[JsonProperty('is_deactivate_scholarship')]
    public ?bool $isDeactivateScholarship;

    /**
     * Whether this is an Institutional Award scholarship (recipients selected by the institution
     * rather than through a public application flow). Cannot be combined with
     * IsSpecialFunds — a request with both set to `true` is rejected with a
     * `validation_failed` error. Example: `false`.
     *
     * @var ?bool $isInstitutionalAwardScholarship
     */
    #[JsonProperty('is_institutional_award_scholarship')]
    public ?bool $isInstitutionalAwardScholarship;

    /**
     * Optional internal notes for admin staff. Not shown to applicants. Example:
     * `"Cycle 2026 STEM partnership with ACME Corp."`.
     *
     * @var ?string $internalNotes
     */
    #[JsonProperty('internal_notes')]
    public ?string $internalNotes;

    /**
     * @param array{
     *   awardCycleId: int,
     *   scholarshipName?: ?string,
     *   fundId?: ?string,
     *   scholarshipDescription?: ?string,
     *   isSpecialFunds?: ?bool,
     *   applicationStartDate?: ?string,
     *   applicationEndDate?: ?string,
     *   disbursementDate?: ?string,
     *   firstDisbursementTermName?: ?string,
     *   totalAwardsNumber?: ?int,
     *   totalScholarshipValue?: ?float,
     *   paymentsPerAward?: ?int,
     *   departmentId?: ?int,
     *   donorIds?: ?array<int>,
     *   isDeactivateScholarship?: ?bool,
     *   isInstitutionalAwardScholarship?: ?bool,
     *   internalNotes?: ?string,
     * } $values
     */
    public function __construct(
        array $values,
    ) {
        $this->scholarshipName = $values['scholarshipName'] ?? null;
        $this->fundId = $values['fundId'] ?? null;
        $this->scholarshipDescription = $values['scholarshipDescription'] ?? null;
        $this->isSpecialFunds = $values['isSpecialFunds'] ?? null;
        $this->awardCycleId = $values['awardCycleId'];
        $this->applicationStartDate = $values['applicationStartDate'] ?? null;
        $this->applicationEndDate = $values['applicationEndDate'] ?? null;
        $this->disbursementDate = $values['disbursementDate'] ?? null;
        $this->firstDisbursementTermName = $values['firstDisbursementTermName'] ?? null;
        $this->totalAwardsNumber = $values['totalAwardsNumber'] ?? null;
        $this->totalScholarshipValue = $values['totalScholarshipValue'] ?? null;
        $this->paymentsPerAward = $values['paymentsPerAward'] ?? null;
        $this->departmentId = $values['departmentId'] ?? null;
        $this->donorIds = $values['donorIds'] ?? null;
        $this->isDeactivateScholarship = $values['isDeactivateScholarship'] ?? null;
        $this->isInstitutionalAwardScholarship = $values['isInstitutionalAwardScholarship'] ?? null;
        $this->internalNotes = $values['internalNotes'] ?? null;
    }
}
