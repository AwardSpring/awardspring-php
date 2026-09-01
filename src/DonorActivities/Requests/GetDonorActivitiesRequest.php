<?php

namespace Awardspring\DonorActivities\Requests;

use Awardspring\Core\Json\JsonSerializableType;
use Awardspring\Types\DonorActivitySourceV1;

class GetDonorActivitiesRequest extends JsonSerializableType
{
    /**
     * @var ?value-of<DonorActivitySourceV1> $source
     */
    public ?string $source;

    /**
     * @param array{
     *   source?: ?value-of<DonorActivitySourceV1>,
     * } $values
     */
    public function __construct(
        array $values = [],
    ) {
        $this->source = $values['source'] ?? null;
    }
}
