<?php

namespace Awardspring\Types;

use Awardspring\Core\Json\JsonSerializableType;
use Awardspring\Core\Json\JsonProperty;

/**
 * A review group: the reviewers and the applicants they read for one or more scholarships in an award
 * cycle. Carries `"object": "review_group"`.
 */
class ReviewGroupV1 extends JsonSerializableType
{
    /**
     * Stripe-style resource discriminator. Stable, snake_case string naming the resource type.
     * Serialized first so it reads as the leading field of every V1 resource body.
     *
     * @var ?string $object
     */
    #[JsonProperty('object')]
    public ?string $object;

    /**
     * The review group's stored ID. It is null only for a group that has not been given one yet; such a group
     * cannot be named in a request until it has.
     *
     * @var ?string $id
     */
    #[JsonProperty('id')]
    public ?string $id;

    /**
     * @var ?string $name
     */
    #[JsonProperty('name')]
    public ?string $name;

    /**
     * @var ?string $awardCycleId The stored ID of the award cycle the group belongs to.
     */
    #[JsonProperty('award_cycle_id')]
    public ?string $awardCycleId;

    /**
     * @var ?string $awardCycleName
     */
    #[JsonProperty('award_cycle_name')]
    public ?string $awardCycleName;

    /**
     * @var ?bool $hasTwoPanels True when the group reviews in two panels, numbered 1 and 2.
     */
    #[JsonProperty('has_two_panels')]
    public ?bool $hasTwoPanels;

    /**
     * @param array{
     *   object?: ?string,
     *   id?: ?string,
     *   name?: ?string,
     *   awardCycleId?: ?string,
     *   awardCycleName?: ?string,
     *   hasTwoPanels?: ?bool,
     * } $values
     */
    public function __construct(
        array $values = [],
    ) {
        $this->object = $values['object'] ?? null;
        $this->id = $values['id'] ?? null;
        $this->name = $values['name'] ?? null;
        $this->awardCycleId = $values['awardCycleId'] ?? null;
        $this->awardCycleName = $values['awardCycleName'] ?? null;
        $this->hasTwoPanels = $values['hasTwoPanels'] ?? null;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }
}
