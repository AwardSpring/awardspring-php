<?php

namespace Awardspring\DonorActivities\Requests;

use Awardspring\Core\Json\JsonSerializableType;

class GetDonorActivitiesRequest extends JsonSerializableType
{
    /**
     * @var ?int $source
     */
    public ?int $source;

    /**
     * @param array{
     *   source?: ?int,
     * } $values
     */
    public function __construct(
        array $values = [],
    ) {
        $this->source = $values['source'] ?? null;
    }
}
