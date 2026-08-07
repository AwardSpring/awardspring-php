<?php

namespace Awardspring\Donors\Requests;

use Awardspring\Core\Json\JsonSerializableType;
use Awardspring\Core\Json\JsonProperty;

class UpdateDonorNotesV1Request extends JsonSerializableType
{
    /**
     * @var ?string $notes
     */
    #[JsonProperty('notes')]
    public ?string $notes;

    /**
     * @param array{
     *   notes?: ?string,
     * } $values
     */
    public function __construct(
        array $values = [],
    ) {
        $this->notes = $values['notes'] ?? null;
    }
}
