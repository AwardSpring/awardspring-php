<?php

namespace Awardspring\Types;

use Awardspring\Core\Json\JsonSerializableType;
use Awardspring\Core\Json\JsonProperty;
use DateTime;
use Awardspring\Core\Types\Date;

/**
 * The activity that was created, returned by `POST /api/v1/donors/{donorId}/activities`.
 *
 *
 * Everything needed to follow up on the new activity is returned here, so a second request is not
 * required. Fields that do not apply to the activity type you logged come back as `null`.
 */
class CreateDonorActivityV1Response extends JsonSerializableType
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
     * @var ?int $id AwardSpring-assigned identifier of the newly created activity row. Stable for the lifetime of the activity.
     */
    #[JsonProperty('id')]
    public ?int $id;

    /**
     * @var ?string $activityType Echo of the activity type that was saved (e.g. `"LoggedEmail"`, `"LoggedPledge"`).
     */
    #[JsonProperty('activity_type')]
    public ?string $activityType;

    /**
     * @var ?string $subject Echo of the saved `Subject`.
     */
    #[JsonProperty('subject')]
    public ?string $subject;

    /**
     * @var ?string $description Echo of the saved `Description` (may be `null`).
     */
    #[JsonProperty('description')]
    public ?string $description;

    /**
     * @var ?DateTime $date The activity date (UTC epoch seconds on the wire).
     */
    #[JsonProperty('date'), Date(Date::TYPE_DATETIME)]
    public ?DateTime $date;

    /**
     * @var ?float $amount Pledge amount, or `null` for non-monetary activity types.
     */
    #[JsonProperty('amount')]
    public ?float $amount;

    /**
     * @var ?string $fundId Fund identifier the pledge was directed toward, or `null` for non-monetary activity types.
     */
    #[JsonProperty('fund_id')]
    public ?string $fundId;

    /**
     * @var ?int $campaignId Campaign association, or `null` for non-monetary activity types.
     */
    #[JsonProperty('campaign_id')]
    public ?int $campaignId;

    /**
     * @var ?int $assignedToUserId Staff member the activity was assigned to, or `null` when not assigned.
     */
    #[JsonProperty('assigned_to_user_id')]
    public ?int $assignedToUserId;

    /**
     * @var ?bool $isCompleted Completion flag — meaningful only for `LoggedPledge`. Always `false` for non-monetary types.
     */
    #[JsonProperty('is_completed')]
    public ?bool $isCompleted;

    /**
     * @param array{
     *   object?: ?string,
     *   id?: ?int,
     *   activityType?: ?string,
     *   subject?: ?string,
     *   description?: ?string,
     *   date?: ?DateTime,
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
        $this->object = $values['object'] ?? null;
        $this->id = $values['id'] ?? null;
        $this->activityType = $values['activityType'] ?? null;
        $this->subject = $values['subject'] ?? null;
        $this->description = $values['description'] ?? null;
        $this->date = $values['date'] ?? null;
        $this->amount = $values['amount'] ?? null;
        $this->fundId = $values['fundId'] ?? null;
        $this->campaignId = $values['campaignId'] ?? null;
        $this->assignedToUserId = $values['assignedToUserId'] ?? null;
        $this->isCompleted = $values['isCompleted'] ?? null;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }
}
