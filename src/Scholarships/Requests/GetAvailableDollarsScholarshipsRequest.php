<?php

namespace Awardspring\Scholarships\Requests;

use Awardspring\Core\Json\JsonSerializableType;

class GetAvailableDollarsScholarshipsRequest extends JsonSerializableType
{
    /**
     * @var string $awardCycleId
     */
    public string $awardCycleId;

    /**
     * @param array{
     *   awardCycleId: string,
     * } $values
     */
    public function __construct(
        array $values,
    ) {
        $this->awardCycleId = $values['awardCycleId'];
    }
}
