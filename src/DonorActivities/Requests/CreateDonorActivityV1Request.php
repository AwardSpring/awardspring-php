<?php

namespace Awardspring\DonorActivities\Requests;

use Awardspring\Core\Json\JsonSerializableType;
use Awardspring\DonorActivities\Types\CreateDonorActivityV1RequestActivityType;
use Awardspring\Core\Json\JsonProperty;

class CreateDonorActivityV1Request extends JsonSerializableType
{
    /**
     * The kind of activity being logged. Supported values:
     * <list type="bullet"><item><description>`LoggedEmail` — an email exchanged with the donor (manually logged after the fact). Example: `"LoggedEmail"`.</description></item><item><description>`LoggedPhone` — a phone conversation with the donor. Example: `"LoggedPhone"`.</description></item><item><description>`LoggedMeeting` — an in-person or virtual meeting. Example: `"LoggedMeeting"`.</description></item><item><description>`LoggedNote` — a free-form note attached to the donor record. Example: `"LoggedNote"`.</description></item><item><description>`LoggedPledge` — a recorded pledge of a future gift; requires Amount. Example: `"LoggedPledge"`.</description></item></list>
     * Gifts (`LoggedGift`) are not supported by this endpoint — they are recorded through a separate gift-entry flow.
     *
     * @var ?value-of<CreateDonorActivityV1RequestActivityType> $activityType
     */
    #[JsonProperty('activity_type')]
    public ?string $activityType;

    /**
     * ISO 8601 date or date-time the activity occurred (or is scheduled for, when in the future). A full
     * date is required — examples: `"2026-04-03"`, `"2026-04-03T10:00:00Z"`, `"2026-04-03T10:00:00-05:00"`.
     * Time-of-day is optional. The date is interpreted in the tenant's configured time zone and persisted in UTC.
     *
     * @var ?string $activityDate
     */
    #[JsonProperty('activity_date')]
    public ?string $activityDate;

    /**
     * Short human-readable title for the activity. Required, non-whitespace. Example: `"Quarterly stewardship call"`.
     * Surfaced in donor activity lists in the AwardSpring admin UI.
     *
     * @var ?string $subject
     */
    #[JsonProperty('subject')]
    public ?string $subject;

    /**
     * @var ?string $description Optional free-form details about the activity. Example: `"Discussed plans for the spring scholarship gala; donor expressed interest in funding a new STEM award."`.
     */
    #[JsonProperty('description')]
    public ?string $description;

    /**
     * Monetary amount for pledge activities. Required and greater than zero when
     * `activity_type` is `LoggedPledge`; ignored for non-monetary activity
     * types. Example: `5000.00`.
     *
     * @var ?float $amount
     */
    #[JsonProperty('amount')]
    public ?float $amount;

    /**
     * Optional fund identifier the pledge is directed toward. Only meaningful when ActivityType
     * is `LoggedPledge`. When the tenant has Funds Management enabled, the value must match an existing
     * fund's `FundIdName`; otherwise a `fund_not_found` error is returned. When Funds Management
     * is disabled, the value is stored as a free-form label without validation. Example: `"GEN-2026"`.
     *
     * @var ?string $fundId
     */
    #[JsonProperty('fund_id')]
    public ?string $fundId;

    /**
     * Optional campaign association for pledge activities. Only meaningful when ActivityType
     * is `LoggedPledge`; ignored for non-monetary activity types. Example: `42`.
     *
     * @var ?int $campaignId
     */
    #[JsonProperty('campaign_id')]
    public ?int $campaignId;

    /**
     * Optional AwardSpring user ID of the staff member the activity is assigned to (e.g., the donor manager
     * who should follow up). Pass `null` or omit when no assignment is intended. Example: `12`.
     *
     * @var ?int $assignedToUserId
     */
    #[JsonProperty('assigned_to_user_id')]
    public ?int $assignedToUserId;

    /**
     * Whether the activity is completed. For non-monetary activity types this is ignored (treated as `false`).
     * For `LoggedPledge`, `true` indicates the pledge has been fulfilled. Defaults to `false`.
     *
     * @var ?bool $isCompleted
     */
    #[JsonProperty('is_completed')]
    public ?bool $isCompleted;

    /**
     * @param array{
     *   activityType?: ?value-of<CreateDonorActivityV1RequestActivityType>,
     *   activityDate?: ?string,
     *   subject?: ?string,
     *   description?: ?string,
     *   amount?: ?float,
     *   fundId?: ?string,
     *   campaignId?: ?int,
     *   assignedToUserId?: ?int,
     *   isCompleted?: ?bool,
     * } $values
     */
    public function __construct(
        array $values = [],
    ) {
        $this->activityType = $values['activityType'] ?? null;
        $this->activityDate = $values['activityDate'] ?? null;
        $this->subject = $values['subject'] ?? null;
        $this->description = $values['description'] ?? null;
        $this->amount = $values['amount'] ?? null;
        $this->fundId = $values['fundId'] ?? null;
        $this->campaignId = $values['campaignId'] ?? null;
        $this->assignedToUserId = $values['assignedToUserId'] ?? null;
        $this->isCompleted = $values['isCompleted'] ?? null;
    }
}
