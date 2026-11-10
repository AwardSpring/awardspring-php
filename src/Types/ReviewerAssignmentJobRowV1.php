<?php

namespace Awardspring\Types;

use Awardspring\Core\Json\JsonSerializableType;
use Awardspring\Core\Json\JsonProperty;

/**
 * One row of a background reviewer assignment request and what has happened to it so far. Carries
 * `"object": "reviewer_assignment_job_row"`.
 */
class ReviewerAssignmentJobRowV1 extends JsonSerializableType
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
     * The row's position in the request, starting at 0. Null for a pair a `replace` removes because the
     * request leaves it out.
     *
     * @var ?int $index
     */
    #[JsonProperty('index')]
    public ?int $index;

    /**
     * @var ?string $reviewerEmail
     */
    #[JsonProperty('reviewer_email')]
    public ?string $reviewerEmail;

    /**
     * @var ?string $applicantEmail
     */
    #[JsonProperty('applicant_email')]
    public ?string $applicantEmail;

    /**
     * What the row asks for: `add`, `remove` or `none` when the pair is already as asked. Null
     * for a row turned down before the job started.
     *
     * @var ?string $action
     */
    #[JsonProperty('action')]
    public ?string $action;

    /**
     * `applied` when the pair now stands as the row asks, `rejected` when the row was turned down,
     *             `waiting` while the job has not reached it, and `failed` when it could not be applied. A row an
     *             all-or-nothing job never reached, because a pair failed its final check, stays `waiting`. When a job
     *             stops for any other reason, a row it did not change is `rejected` and its `message` says why.
     *
     * @var ?string $status
     */
    #[JsonProperty('status')]
    public ?string $status;

    /**
     * @var ?string $message A plain sentence saying why the row was turned down or failed. Null otherwise.
     */
    #[JsonProperty('message')]
    public ?string $message;

    /**
     * @param array{
     *   object?: ?string,
     *   index?: ?int,
     *   reviewerEmail?: ?string,
     *   applicantEmail?: ?string,
     *   action?: ?string,
     *   status?: ?string,
     *   message?: ?string,
     * } $values
     */
    public function __construct(
        array $values = [],
    ) {
        $this->object = $values['object'] ?? null;
        $this->index = $values['index'] ?? null;
        $this->reviewerEmail = $values['reviewerEmail'] ?? null;
        $this->applicantEmail = $values['applicantEmail'] ?? null;
        $this->action = $values['action'] ?? null;
        $this->status = $values['status'] ?? null;
        $this->message = $values['message'] ?? null;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }
}
