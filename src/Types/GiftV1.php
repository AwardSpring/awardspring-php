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
     * @var ?int $id
     */
    #[JsonProperty('id')]
    public ?int $id;

    /**
     * @var ?int $donorId
     */
    #[JsonProperty('donor_id')]
    public ?int $donorId;

    /**
     * @var ?string $type
     */
    #[JsonProperty('type')]
    public ?string $type;

    /**
     * @var ?string $giftType
     */
    #[JsonProperty('gift_type')]
    public ?string $giftType;

    /**
     * @var ?float $amount
     */
    #[JsonProperty('amount')]
    public ?float $amount;

    /**
     * @var ?string $subject
     */
    #[JsonProperty('subject')]
    public ?string $subject;

    /**
     * @var ?string $description
     */
    #[JsonProperty('description')]
    public ?string $description;

    /**
     * @var ?string $fundId
     */
    #[JsonProperty('fund_id')]
    public ?string $fundId;

    /**
     * @var ?int $campaignId
     */
    #[JsonProperty('campaign_id')]
    public ?int $campaignId;

    /**
     * @var ?bool $isCompleted
     */
    #[JsonProperty('is_completed')]
    public ?bool $isCompleted;

    /**
     * @var ?bool $giftAcknowledgementSent
     */
    #[JsonProperty('gift_acknowledgement_sent')]
    public ?bool $giftAcknowledgementSent;

    /**
     * @var ?int $date
     */
    #[JsonProperty('date')]
    public ?int $date;

    /**
     * @var ?array<SoftCreditV1> $softCredits
     */
    #[JsonProperty('soft_credits'), ArrayType([SoftCreditV1::class])]
    public ?array $softCredits;

    /**
     * @param array{
     *   object?: ?string,
     *   id?: ?int,
     *   donorId?: ?int,
     *   type?: ?string,
     *   giftType?: ?string,
     *   amount?: ?float,
     *   subject?: ?string,
     *   description?: ?string,
     *   fundId?: ?string,
     *   campaignId?: ?int,
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
