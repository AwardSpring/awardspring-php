<?php

namespace Awardspring\Types;

use Awardspring\Core\Json\JsonSerializableType;
use Awardspring\Core\Json\JsonProperty;
use Awardspring\Core\Types\ArrayType;

/**
 * One of the institution's scholarship custom fields, with this scholarship's value in it, as the scholarship's
 * Details tab shows it.
 */
class ScholarshipCustomFieldV1 extends JsonSerializableType
{
    /**
     * @var ?string $id The custom field's stored ID. Example: `"3KD8WQ2MZ"`.
     */
    #[JsonProperty('id')]
    public ?string $id;

    /**
     * @var ?string $name The custom field's name, as the Details tab shows it. Example: `"Award type"`.
     */
    #[JsonProperty('name')]
    public ?string $name;

    /**
     * What the field holds: `"text"`, `"number"`, `"one_choice"` (one of a list of choices) or
     * `"multiple_choice"` (any number of a list of choices).
     *
     * @var ?value-of<ScholarshipCustomFieldV1Type> $type
     */
    #[JsonProperty('type')]
    public ?string $type;

    /**
     * The value of a `text`, `number` or `one_choice` field (for a choice, the choice's name), or
     * null when it has none. Always null for a `multiple_choice` field, which uses `values`.
     *
     * @var ?string $value
     */
    #[JsonProperty('value')]
    public ?string $value;

    /**
     * The names of the chosen choices of a `multiple_choice` field, in the order the Details tab lists them;
     * empty when none is chosen. Always null for the other types, which use `value`.
     *
     * @var ?array<string> $values
     */
    #[JsonProperty('values'), ArrayType(['string'])]
    public ?array $values;

    /**
     * @param array{
     *   id?: ?string,
     *   name?: ?string,
     *   type?: ?value-of<ScholarshipCustomFieldV1Type>,
     *   value?: ?string,
     *   values?: ?array<string>,
     * } $values
     */
    public function __construct(
        array $values = [],
    ) {
        $this->id = $values['id'] ?? null;
        $this->name = $values['name'] ?? null;
        $this->type = $values['type'] ?? null;
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
