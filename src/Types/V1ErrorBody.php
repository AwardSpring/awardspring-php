<?php

namespace Awardspring\Types;

use Awardspring\Core\Json\JsonSerializableType;
use Awardspring\Core\Json\JsonProperty;

/**
 * Stripe-shaped error object for the `/api/v1/*` surface. Serialized snake_case (via
 * V1SnakeCaseNamingStrategy) and always nested under the `error` key of a
 * V1ErrorEnvelope:
 * ```{ "error": { "type": "...", "code": "...", "message": "...", "param": null, "doc_url": null, "recovery": "..." } }```
 */
class V1ErrorBody extends JsonSerializableType
{
    /**
     * @var ?string $type Coarse error category from V1ErrorType (e.g. `invalid_request_error`).
     */
    #[JsonProperty('type')]
    public ?string $type;

    /**
     * @var ?string $code Stable fine-grained machine code from ErrorCode (e.g. `donor_not_found`).
     */
    #[JsonProperty('code')]
    public ?string $code;

    /**
     * @var ?string $message Human-readable summary safe to surface to the caller.
     */
    #[JsonProperty('message')]
    public ?string $message;

    /**
     * @var ?string $param The request parameter the error relates to, when attributable to a single field; otherwise null.
     */
    #[JsonProperty('param')]
    public ?string $param;

    /**
     * @var ?string $docUrl Link to documentation for this error, when one exists; otherwise null.
     */
    #[JsonProperty('doc_url')]
    public ?string $docUrl;

    /**
     * Plain-language guidance on how to recover from this error, suitable for showing to the
     * person who triggered it. Not present on every error.
     *
     * @var ?string $recovery
     */
    #[JsonProperty('recovery')]
    public ?string $recovery;

    /**
     * Optional structured payload (e.g. a list of ValidationDetail for validation failures).
     * Omitted from the wire when null so the canonical Stripe shape stays minimal.
     *
     * @var mixed $details
     */
    #[JsonProperty('details')]
    public mixed $details;

    /**
     * @param array{
     *   type?: ?string,
     *   code?: ?string,
     *   message?: ?string,
     *   param?: ?string,
     *   docUrl?: ?string,
     *   recovery?: ?string,
     *   details?: mixed,
     * } $values
     */
    public function __construct(
        array $values = [],
    ) {
        $this->type = $values['type'] ?? null;
        $this->code = $values['code'] ?? null;
        $this->message = $values['message'] ?? null;
        $this->param = $values['param'] ?? null;
        $this->docUrl = $values['docUrl'] ?? null;
        $this->recovery = $values['recovery'] ?? null;
        $this->details = $values['details'] ?? null;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }
}
