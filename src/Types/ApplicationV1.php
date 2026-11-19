<?php

namespace Awardspring\Types;

use Awardspring\Core\Json\JsonSerializableType;
use Awardspring\Core\Json\JsonProperty;
use Awardspring\Core\Types\ArrayType;

/**
 * One student's application to one scholarship within an award cycle.
 */
class ApplicationV1 extends JsonSerializableType
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
     * @var ?string $id The application's stored ID. An award of this application has the same `id`.
     */
    #[JsonProperty('id')]
    public ?string $id;

    /**
     * @var ?string $userId The stored ID of the applicant's user record: the same value as the student resource's `id` (see the Students endpoints).
     */
    #[JsonProperty('user_id')]
    public ?string $userId;

    /**
     * @var ?string $scholarshipId The stored ID of the scholarship applied to (see the Scholarships endpoints).
     */
    #[JsonProperty('scholarship_id')]
    public ?string $scholarshipId;

    /**
     * @var ?string $scholarshipName Name of the scholarship applied to, denormalized onto the row for convenience.
     */
    #[JsonProperty('scholarship_name')]
    public ?string $scholarshipName;

    /**
     * @var ?string $awardCycleId The stored ID of the award cycle the application belongs to (see the Award Cycles endpoints).
     */
    #[JsonProperty('award_cycle_id')]
    public ?string $awardCycleId;

    /**
     * @var ?string $awardCycleName Name of the award cycle the application belongs to, denormalized onto the row for convenience.
     */
    #[JsonProperty('award_cycle_name')]
    public ?string $awardCycleName;

    /**
     * Where the application stands: `applied` (submitted, no decision yet),
     * `awarded`, or `denied`.
     *
     * @var ?string $status
     */
    #[JsonProperty('status')]
    public ?string $status;

    /**
     * @var ?int $appliedDate When the application was submitted, as UTC epoch seconds.
     */
    #[JsonProperty('applied_date')]
    public ?int $appliedDate;

    /**
     * The student's entry-gate result on this award cycle's general application: `qualified`,
     * `blocked_at_entry`, `pending`, or null when the application has no entry questions or
     * the student was never evaluated.
     *
     * @var ?string $entryOutcome
     */
    #[JsonProperty('entry_outcome')]
    public ?string $entryOutcome;

    /**
     * @var ?array<ApplicationEntryAnswerV1> $entryAnswers The student's answers to this award cycle's entry questions in the order the application asks them. Each one names the section Entry Questions. Null when the award cycle has no entry questions.
     */
    #[JsonProperty('entry_answers'), ArrayType([ApplicationEntryAnswerV1::class])]
    public ?array $entryAnswers;

    /**
     * The options the student picked on the Major question of this award cycle's general application,
     * as the option text, in the order the question lists them. A choice the institution has since removed
     * from the question still shows, after the ones it lists. The question can take several answers,
     * so this may hold more than one. The options are written by the institution, so these are the
     * institution's own words and not a fixed list. Empty when this award cycle has no Major question
     * or the student has not answered it.
     *
     * @var ?array<string> $majors
     */
    #[JsonProperty('majors'), ArrayType(['string'])]
    public ?array $majors;

    /**
     * @param array{
     *   object?: ?string,
     *   id?: ?string,
     *   userId?: ?string,
     *   scholarshipId?: ?string,
     *   scholarshipName?: ?string,
     *   awardCycleId?: ?string,
     *   awardCycleName?: ?string,
     *   status?: ?string,
     *   appliedDate?: ?int,
     *   entryOutcome?: ?string,
     *   entryAnswers?: ?array<ApplicationEntryAnswerV1>,
     *   majors?: ?array<string>,
     * } $values
     */
    public function __construct(
        array $values = [],
    ) {
        $this->object = $values['object'] ?? null;
        $this->id = $values['id'] ?? null;
        $this->userId = $values['userId'] ?? null;
        $this->scholarshipId = $values['scholarshipId'] ?? null;
        $this->scholarshipName = $values['scholarshipName'] ?? null;
        $this->awardCycleId = $values['awardCycleId'] ?? null;
        $this->awardCycleName = $values['awardCycleName'] ?? null;
        $this->status = $values['status'] ?? null;
        $this->appliedDate = $values['appliedDate'] ?? null;
        $this->entryOutcome = $values['entryOutcome'] ?? null;
        $this->entryAnswers = $values['entryAnswers'] ?? null;
        $this->majors = $values['majors'] ?? null;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }
}
