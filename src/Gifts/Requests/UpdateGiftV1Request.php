<?php

namespace Awardspring\Gifts\Requests;

use Awardspring\Core\Json\JsonSerializableType;
use Awardspring\Core\Json\JsonProperty;

class UpdateGiftV1Request extends JsonSerializableType
{
    /**
     * The new amount. Must be greater than zero; zero is allowed only on a gift paid `InKind`. Cannot be
     * cleared. Refused on a gift given online.
     *
     * @var ?float $amount
     */
    #[JsonProperty('amount')]
    public ?float $amount;

    /**
     * The new date of the gift or pledge, as an ISO 8601 date such as `"2026-04-03"`. The date is the
     * institution's own calendar day; a time sent with it is ignored. Cannot be cleared. Refused on a gift
     * given online.
     *
     * @var ?string $giftDate
     */
    #[JsonProperty('gift_date')]
    public ?string $giftDate;

    /**
     * The new payment type of a gift: one of `Cash`, `Check`, `CreditOrDebit`,
     * `BankTransfer`, `StockOrProperty`, `InKind`, `PayrollDeduction` or `Other`.
     * `Online` is set only on gifts given online and cannot be chosen. Cannot be cleared. Refused on a
     * pledge, which has no payment type, and on a gift given online.
     *
     * @var ?string $giftType
     */
    #[JsonProperty('gift_type')]
    public ?string $giftType;

    /**
     * The institution's own code for the fund the gift is directed toward, as the gift returns it in
     * `fund_id`. When the institution uses Funds Management it must name one of its funds. Send an
     * empty string to clear it.
     *
     * @var ?string $fundId
     */
    #[JsonProperty('fund_id')]
    public ?string $fundId;

    /**
     * @var ?string $campaignId The stored ID of a campaign at this institution. Send an empty string to clear it.
     */
    #[JsonProperty('campaign_id')]
    public ?string $campaignId;

    /**
     * @var ?string $subject The new short title of the gift or pledge. Cannot be cleared.
     */
    #[JsonProperty('subject')]
    public ?string $subject;

    /**
     * @var ?string $description The new notes about the gift or pledge. Replaces the notes whole. Send an empty string to clear them.
     */
    #[JsonProperty('description')]
    public ?string $description;

    /**
     * @param array{
     *   amount?: ?float,
     *   giftDate?: ?string,
     *   giftType?: ?string,
     *   fundId?: ?string,
     *   campaignId?: ?string,
     *   subject?: ?string,
     *   description?: ?string,
     * } $values
     */
    public function __construct(
        array $values = [],
    ) {
        $this->amount = $values['amount'] ?? null;
        $this->giftDate = $values['giftDate'] ?? null;
        $this->giftType = $values['giftType'] ?? null;
        $this->fundId = $values['fundId'] ?? null;
        $this->campaignId = $values['campaignId'] ?? null;
        $this->subject = $values['subject'] ?? null;
        $this->description = $values['description'] ?? null;
    }
}
