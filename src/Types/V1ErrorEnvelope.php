<?php

namespace Awardspring\Types;

use Awardspring\Core\Json\JsonSerializableType;
use Awardspring\Core\Json\JsonProperty;

/**
 * Top-level Stripe-shaped error envelope for the `/api/v1/*` surface: a single `error`
 * key wrapping a V1ErrorBody. This is the NEW v1 error type and is distinct from the
 * legacy flat StructuredErrorResponse, which remains in use by hardened legacy
 * `api/...` endpoints and must not change shape.
 */
class V1ErrorEnvelope extends JsonSerializableType
{
    /**
     * @var ?V1ErrorBody $error
     */
    #[JsonProperty('error')]
    public ?V1ErrorBody $error;

    /**
     * @param array{
     *   error?: ?V1ErrorBody,
     * } $values
     */
    public function __construct(
        array $values = [],
    ) {
        $this->error = $values['error'] ?? null;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }
}
