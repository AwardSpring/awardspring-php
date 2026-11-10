<?php

namespace Awardspring\Types;

use Awardspring\Core\Json\JsonSerializableType;
use Awardspring\Core\Json\JsonProperty;
use Awardspring\Core\Types\ArrayType;

class GiftV1 extends JsonSerializableType
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
     * The gift's or pledge's stored ID: 9 characters, sent exactly as stored. A dry-run create
     * returns `null`, because nothing was saved.
     *
     * @var ?string $id
     */
    #[JsonProperty('id')]
    public ?string $id;

    /**
     * @var ?string $donorId The stored ID of the donor the gift or pledge is attributed to.
     */
    #[JsonProperty('donor_id')]
    public ?string $donorId;

    /**
     * @var ?string $type `gift` for money already given, or `pledge` for money promised.
     */
    #[JsonProperty('type')]
    public ?string $type;

    /**
     * How the gift was given: one of `None`, `Cash`, `Check`, `CreditOrDebit`,
     * `BankTransfer`, `StockOrProperty`, `InKind`, `PayrollDeduction`, `Online` or
     * `Other`. `None` when no payment type was recorded.
     *
     * @var ?string $giftType
     */
    #[JsonProperty('gift_type')]
    public ?string $giftType;

    /**
     * @var ?float $amount The amount given or pledged, or `null` when no amount was recorded.
     */
    #[JsonProperty('amount')]
    public ?float $amount;

    /**
     * @var ?string $subject The short title of the gift or pledge.
     */
    #[JsonProperty('subject')]
    public ?string $subject;

    /**
     * @var ?string $description Longer notes about the gift or pledge, or `null`.
     */
    #[JsonProperty('description')]
    public ?string $description;

    /**
     * @var ?string $fundId The school's own code for the fund the gift is directed toward, as plain text.
     */
    #[JsonProperty('fund_id')]
    public ?string $fundId;

    /**
     * @var ?string $campaignId The stored ID of the campaign the gift or pledge belongs to, or `null`.
     */
    #[JsonProperty('campaign_id')]
    public ?string $campaignId;

    /**
     * @var ?bool $isCompleted For a pledge, whether it has been fulfilled.
     */
    #[JsonProperty('is_completed')]
    public ?bool $isCompleted;

    /**
     * @var ?bool $giftAcknowledgementSent Whether a gift acknowledgement has been sent to the donor for this gift.
     */
    #[JsonProperty('gift_acknowledgement_sent')]
    public ?bool $giftAcknowledgementSent;

    /**
     * @var ?int $date The date of the gift or pledge, in the institution's local time zone (UTC epoch seconds on the wire).
     */
    #[JsonProperty('date')]
    public ?int $date;

    /**
     * Up to three people or names credited for the gift besides the donor. An empty list when there are
     * none.
     *
     * @var ?array<SoftCreditV1> $softCredits
     */
    #[JsonProperty('soft_credits'), ArrayType([SoftCreditV1::class])]
    public ?array $softCredits;

    /**
     * @param array{
     *   object?: ?string,
     *   id?: ?string,
     *   donorId?: ?string,
     *   type?: ?string,
     *   giftType?: ?string,
     *   amount?: ?float,
     *   subject?: ?string,
     *   description?: ?string,
     *   fundId?: ?string,
     *   campaignId?: ?string,
     *   isCompleted?: ?bool,
     *   giftAcknowledgementSent?: ?bool,
     *   date?: ?int,
     *   softCredits?: ?array<SoftCreditV1>,
     * } $values
     */
    public function __construct(
        array $values = [],
    ) {
        $this->object = $values['object'] ?? null;
        $this->id = $values['id'] ?? null;
        $this->donorId = $values['donorId'] ?? null;
        $this->type = $values['type'] ?? null;
        $this->giftType = $values['giftType'] ?? null;
        $this->amount = $values['amount'] ?? null;
        $this->subject = $values['subject'] ?? null;
        $this->description = $values['description'] ?? null;
        $this->fundId = $values['fundId'] ?? null;
        $this->campaignId = $values['campaignId'] ?? null;
        $this->isCompleted = $values['isCompleted'] ?? null;
        $this->giftAcknowledgementSent = $values['giftAcknowledgementSent'] ?? null;
        $this->date = $values['date'] ?? null;
        $this->softCredits = $values['softCredits'] ?? null;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }
}
