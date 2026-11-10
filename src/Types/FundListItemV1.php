<?php

namespace Awardspring\Types;

use Awardspring\Core\Json\JsonSerializableType;
use Awardspring\Core\Json\JsonProperty;

class FundListItemV1 extends JsonSerializableType
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
     * @var ?string $id The fund's stored ID. The school's own fund code is `fund_id`.
     */
    #[JsonProperty('id')]
    public ?string $id;

    /**
     * @var ?string $fundId
     */
    #[JsonProperty('fund_id')]
    public ?string $fundId;

    /**
     * The money left in the fund, in dollars, rounded to two decimal places: every deposit and gift into it, less
     * every disbursement and decided award paid from it, over the fund's whole history.
     * `0` when the fund has had no money in or out. Example: `1500.25`.
     *
     * @var ?float $remainingBalance
     */
    #[JsonProperty('remaining_balance')]
    public ?float $remainingBalance;

    /**
     * @var ?string $fundType
     */
    #[JsonProperty('fund_type')]
    public ?string $fundType;

    /**
     * @var ?bool $isEndowed
     */
    #[JsonProperty('is_endowed')]
    public ?bool $isEndowed;

    /**
     * @param array{
     *   object?: ?string,
     *   id?: ?string,
     *   fundId?: ?string,
     *   remainingBalance?: ?float,
     *   fundType?: ?string,
     *   isEndowed?: ?bool,
     * } $values
     */
    public function __construct(
        array $values = [],
    ) {
        $this->object = $values['object'] ?? null;
        $this->id = $values['id'] ?? null;
        $this->fundId = $values['fundId'] ?? null;
        $this->remainingBalance = $values['remainingBalance'] ?? null;
        $this->fundType = $values['fundType'] ?? null;
        $this->isEndowed = $values['isEndowed'] ?? null;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }
}
