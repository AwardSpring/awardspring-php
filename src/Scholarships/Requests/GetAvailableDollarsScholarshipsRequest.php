<?php

namespace Awardspring\Scholarships\Requests;

use Awardspring\Core\Json\JsonSerializableType;

class GetAvailableDollarsScholarshipsRequest extends JsonSerializableType
{
    /**
     * @var int $awardCycleId
     */
    public int $awardCycleId;

    /**
     * @param array{
     *   awardCycleId: int,
     * } $values
     */
    public function __construct(
        array $values,
    ) {
        $this->awardCycleId = $values['awardCycleId'];
    }
}
