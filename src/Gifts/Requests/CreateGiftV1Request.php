<?php

namespace Awardspring\Gifts\Requests;

use Awardspring\Core\Json\JsonSerializableType;
use Awardspring\Core\Json\JsonProperty;
use Awardspring\Gifts\Types\CreateGiftV1RequestType;
use Awardspring\Gifts\Types\CreateGiftV1RequestGiftType;
use Awardspring\Types\SoftCreditV1Input;
use Awardspring\Core\Types\ArrayType;

class CreateGiftV1Request extends JsonSerializableType
{
    /**
     * @var ?string $donorId The stored ID of the donor (or donor organization) the gift is attributed to. Required.
     */
    #[JsonProperty('donor_id')]
    public ?string $donorId;

    /**
     * `gift` or `pledge`. Defaults to `gift` when omitted. Soft credits are only
     *             honored on gifts.
     *
     * @var ?value-of<CreateGiftV1RequestType> $type
     */
    #[JsonProperty('type')]
    public ?string $type;

    /**
     * Gift instrument — one of `Cash`, `Check`, `CreditOrDebit`, `BankTransfer`,
     * `StockOrProperty`, `InKind`, `PayrollDeduction`, `Online`, `Other`.
     * Optional; defaults to `None`. Ignored for pledges.
     *
     * @var ?value-of<CreateGiftV1RequestGiftType> $giftType
     */
    #[JsonProperty('gift_type')]
    public ?string $giftType;

    /**
     * Monetary amount. Required for both gifts and pledges and must be greater than zero;
     * a zero amount is allowed only when GiftType is `InKind`.
     *
     * @var ?float $amount
     */
    #[JsonProperty('amount')]
    public ?float $amount;

    /**
     * @var ?string $subject Short human-readable title. Required, non-whitespace.
     */
    #[JsonProperty('subject')]
    public ?string $subject;

    /**
     * @var ?string $description
     */
    #[JsonProperty('description')]
    public ?string $description;

    /**
     * ISO 8601 date the gift/pledge occurred (e.g. `"2026-04-03"` or `"2026-04-03T10:00:00Z"`).
     * Required. Interpreted in the tenant's time zone and persisted in UTC.
     *
     * @var ?string $giftDate
     */
    #[JsonProperty('gift_date')]
    public ?string $giftDate;

    /**
     * Fund the gift is directed toward. When the tenant has Funds Management enabled the value must
     * match an existing fund's identifier; otherwise it is stored as a free-form label.
     *
     * @var ?string $fundId
     */
    #[JsonProperty('fund_id')]
    public ?string $fundId;

    /**
     * @var ?string $campaignId The stored ID of a campaign at this institution to attribute the gift to. Optional.
     */
    #[JsonProperty('campaign_id')]
    public ?string $campaignId;

    /**
     * @var ?bool $isCompleted Only meaningful for pledges — marks the pledge fulfilled. Ignored for gifts.
     */
    #[JsonProperty('is_completed')]
    public ?bool $isCompleted;

    /**
     * The stored ID of the officer the record is assigned to. Must be a person at this institution when
     * supplied.
     *
     * @var ?string $assignedToUserId
     */
    #[JsonProperty('assigned_to_user_id')]
    public ?string $assignedToUserId;

    /**
     * @var ?array<SoftCreditV1Input> $softCredits Up to three soft-credit recipients. Honored on gifts only; ignored for pledges.
     */
    #[JsonProperty('soft_credits'), ArrayType([SoftCreditV1Input::class])]
    public ?array $softCredits;

    /**
     * @param array{
     *   donorId?: ?string,
     *   type?: ?value-of<CreateGiftV1RequestType>,
     *   giftType?: ?value-of<CreateGiftV1RequestGiftType>,
     *   amount?: ?float,
     *   subject?: ?string,
     *   description?: ?string,
     *   giftDate?: ?string,
     *   fundId?: ?string,
     *   campaignId?: ?string,
     *   isCompleted?: ?bool,
     *   assignedToUserId?: ?string,
     *   softCredits?: ?array<SoftCreditV1Input>,
     * } $values
     */
    public function __construct(
        array $values = [],
    ) {
        $this->donorId = $values['donorId'] ?? null;
        $this->type = $values['type'] ?? null;
        $this->giftType = $values['giftType'] ?? null;
        $this->amount = $values['amount'] ?? null;
        $this->subject = $values['subject'] ?? null;
        $this->description = $values['description'] ?? null;
        $this->giftDate = $values['giftDate'] ?? null;
        $this->fundId = $values['fundId'] ?? null;
        $this->campaignId = $values['campaignId'] ?? null;
        $this->isCompleted = $values['isCompleted'] ?? null;
        $this->assignedToUserId = $values['assignedToUserId'] ?? null;
        $this->softCredits = $values['softCredits'] ?? null;
    }
}
