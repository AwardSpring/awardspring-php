<?php

namespace Awardspring\Types;

use Awardspring\Core\Json\JsonSerializableType;
use Awardspring\Core\Json\JsonProperty;
use Awardspring\Core\Types\ArrayType;

/**
 * One of the institution's donor custom fields, with this donor's answer.
 */
class DonorCustomFieldV1 extends JsonSerializableType
{
    /**
     * The donor's answer to a field that is not a `single_select` or `multiple_select`, or `null`
     * when the donor has no answer or the field takes choices.
     *
     * @var ?string $value
     */
    #[JsonProperty('value')]
    public ?string $value;

    /**
     * The choices the donor has for a `single_select` or `multiple_select` field, in the order the field
     * lists them. Empty when the donor has none or the field does not take choices.
     *
     * @var ?array<string> $selected
     */
    #[JsonProperty('selected'), ArrayType(['string'])]
    public ?array $selected;

    /**
     * @var ?string $name The field's name. Fields are identified by name.
     */
    #[JsonProperty('name')]
    public ?string $name;

    /**
     * The kind of answer the field takes: `text`, `number`, `date`, `single_select`,
     * `multiple_select`, or `other` for a kind the settings screen no longer offers.
     *
     * @var ?value-of<DonorCustomFieldV1Kind> $kind
     */
    #[JsonProperty('kind')]
    public ?string $kind;

    /**
     * The answers a `single_select` or `multiple_select` field offers, in the order the field lists them.
     * Empty for every other kind.
     *
     * @var ?array<string> $choices
     */
    #[JsonProperty('choices'), ArrayType(['string'])]
    public ?array $choices;

    /**
     * @param array{
     *   value?: ?string,
     *   selected?: ?array<string>,
     *   name?: ?string,
     *   kind?: ?value-of<DonorCustomFieldV1Kind>,
     *   choices?: ?array<string>,
     * } $values
     */
    public function __construct(
        array $values = [],
    ) {
        $this->value = $values['value'] ?? null;
        $this->selected = $values['selected'] ?? null;
        $this->name = $values['name'] ?? null;
        $this->kind = $values['kind'] ?? null;
        $this->choices = $values['choices'] ?? null;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }
}
