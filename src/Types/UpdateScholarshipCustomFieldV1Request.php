<?php

namespace Awardspring\Types;

use Awardspring\Core\Json\JsonSerializableType;
use Awardspring\Core\Json\JsonProperty;
use Awardspring\Core\Types\ArrayType;

/**
 * A new value for one scholarship custom field, named by the field's stored ID. Send exactly one of
 * `value` and `values`.
 */
class UpdateScholarshipCustomFieldV1Request extends JsonSerializableType
{
    /**
     * @var ?string $id The custom field's stored ID, as the scholarship's `custom_fields` shows it. Example: `"3KD8WQ2MZ"`.
     */
    #[JsonProperty('id')]
    public ?string $id;

    /**
     * The new value. For a `text` field, any text; for a `number` field, a number such as `"3.5"`;
     * for a `one_choice` or `multiple_choice` field, the name of one of its active choices (matched
     * without regard to case). An empty string clears the field.
     *
     * @var ?string $value
     */
    #[JsonProperty('value')]
    public ?string $value;

    /**
     * The names of the choices to choose, for a choice field: this replaces every choice it has now. A
     * `one_choice` field takes at most one. An empty list clears the field. Not accepted for a `text` or
     * `number` field. Example: `["Merit", "Need"]`.
     *
     * @var ?array<string> $values
     */
    #[JsonProperty('values'), ArrayType(['string'])]
    public ?array $values;

    /**
     * @param array{
     *   id?: ?string,
     *   value?: ?string,
     *   values?: ?array<string>,
     * } $values
     */
    public function __construct(
        array $values = [],
    ) {
        $this->id = $values['id'] ?? null;
        $this->value = $values['value'] ?? null;
        $this->values = $values['values'] ?? null;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }
}
