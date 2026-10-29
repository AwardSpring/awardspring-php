<?php

namespace Awardspring\Types;

use Awardspring\Core\Json\JsonSerializableType;
use Awardspring\Core\Json\JsonProperty;

/**
 * A single item on a donor's merged activity timeline. Fields a given source doesn't populate are
 * null/default.
 */
class DonorActivityV1 extends JsonSerializableType
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
     * The stored ID of the row the item came from (a logged activity, an email, a text message, an award
     * or a general application). Only a logged activity's ID opens the item on its own.
     *
     * @var ?string $id
     */
    #[JsonProperty('id')]
    public ?string $id;

    /**
     * @var ?value-of<DonorActivitySourceV1> $source
     */
    #[JsonProperty('source')]
    public ?string $source;

    /**
     * @var ?string $activityType Activity type label (e.g. `"LoggedEmail"`, `"Email"`, `"Sms"`, `"Award"`, `"GeneralApplication"`).
     */
    #[JsonProperty('activity_type')]
    public ?string $activityType;

    /**
     * @var ?string $subject Short human-readable title for the item.
     */
    #[JsonProperty('subject')]
    public ?string $subject;

    /**
     * @var ?string $description Free-form detail (populated only for logged activities and SMS; null otherwise).
     */
    #[JsonProperty('description')]
    public ?string $description;

    /**
     * @var ?int $date When the activity occurred, in the tenant's local time zone (UTC epoch seconds on the wire).
     */
    #[JsonProperty('date')]
    public ?int $date;

    /**
     * @var ?float $amount Monetary amount (logged monetary activities and awards); null otherwise.
     */
    #[JsonProperty('amount')]
    public ?float $amount;

    /**
     * @var ?string $fundId Fund the pledge/gift was directed toward (logged activities only); null otherwise.
     */
    #[JsonProperty('fund_id')]
    public ?string $fundId;

    /**
     * @var ?bool $isCompleted Completion flag (logged monetary activities); false otherwise.
     */
    #[JsonProperty('is_completed')]
    public ?bool $isCompleted;

    /**
     * @var ?bool $giftAcknowledgementSent Whether a gift acknowledgement was sent (logged gifts); false otherwise.
     */
    #[JsonProperty('gift_acknowledgement_sent')]
    public ?bool $giftAcknowledgementSent;

    /**
     * @var ?string $giftType Gift type label (logged activities); null otherwise.
     */
    #[JsonProperty('gift_type')]
    public ?string $giftType;

    /**
     * @var ?string $campaignId The stored ID of the campaign (logged monetary activities); null otherwise.
     */
    #[JsonProperty('campaign_id')]
    public ?string $campaignId;

    /**
     * @var ?string $assignedToUserId The stored ID of the staff member the activity is assigned to (logged activities); null otherwise.
     */
    #[JsonProperty('assigned_to_user_id')]
    public ?string $assignedToUserId;

    /**
     * @var ?string $senderName Sender display name (system emails only); null otherwise.
     */
    #[JsonProperty('sender_name')]
    public ?string $senderName;

    /**
     * @param array{
     *   object?: ?string,
     *   id?: ?string,
     *   source?: ?value-of<DonorActivitySourceV1>,
     *   activityType?: ?string,
     *   subject?: ?string,
     *   description?: ?string,
     *   date?: ?int,
     *   amount?: ?float,
     *   fundId?: ?string,
     *   isCompleted?: ?bool,
     *   giftAcknowledgementSent?: ?bool,
     *   giftType?: ?string,
     *   campaignId?: ?string,
     *   assignedToUserId?: ?string,
     *   senderName?: ?string,
     * } $values
     */
    public function __construct(
        array $values = [],
    ) {
        $this->object = $values['object'] ?? null;
        $this->id = $values['id'] ?? null;
        $this->source = $values['source'] ?? null;
        $this->activityType = $values['activityType'] ?? null;
        $this->subject = $values['subject'] ?? null;
        $this->description = $values['description'] ?? null;
        $this->date = $values['date'] ?? null;
        $this->amount = $values['amount'] ?? null;
        $this->fundId = $values['fundId'] ?? null;
        $this->isCompleted = $values['isCompleted'] ?? null;
        $this->giftAcknowledgementSent = $values['giftAcknowledgementSent'] ?? null;
        $this->giftType = $values['giftType'] ?? null;
        $this->campaignId = $values['campaignId'] ?? null;
        $this->assignedToUserId = $values['assignedToUserId'] ?? null;
        $this->senderName = $values['senderName'] ?? null;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }
}
