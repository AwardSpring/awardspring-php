<?php

namespace Awardspring\Departments\Requests;

use Awardspring\Core\Json\JsonSerializableType;
use Awardspring\Core\Json\JsonProperty;

class CreateDepartmentV1Request extends JsonSerializableType
{
    /**
     * The department's name. Required, and not already used by another department at the institution,
     * ignoring upper and lower case and spaces at either end. Example: `"School of Nursing"`.
     *
     * @var ?string $name
     */
    #[JsonProperty('name')]
    public ?string $name;

    /**
     * @param array{
     *   name?: ?string,
     * } $values
     */
    public function __construct(
        array $values = [],
    ) {
        $this->name = $values['name'] ?? null;
    }
}
