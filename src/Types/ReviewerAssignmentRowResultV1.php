<?php

namespace Awardspring\Types;

use Awardspring\Core\Json\JsonSerializableType;
use Awardspring\Core\Json\JsonProperty;

/**
 * What happened to one pair in a request.
 */
class ReviewerAssignmentRowResultV1 extends JsonSerializableType
{
    /**
     * The row's position in the request, starting at 0. Null for a pair a `replace` removes because the
     * request leaves it out.
     *
     * @var ?int $index
     */
    #[JsonProperty('index')]
    public ?int $index;

    /**
     * @var ?string $reviewerId The reviewer's stored ID. Null when the row names no active account.
     */
    #[JsonProperty('reviewer_id')]
    public ?string $reviewerId;

    /**
     * @var ?string $reviewerEmail
     */
    #[JsonProperty('reviewer_email')]
    public ?string $reviewerEmail;

    /**
     * @var ?string $applicantId The applicant's stored ID. Null when the row names no active account.
     */
    #[JsonProperty('applicant_id')]
    public ?string $applicantId;

    /**
     * @var ?string $applicantEmail
     */
    #[JsonProperty('applicant_email')]
    public ?string $applicantEmail;

    /**
     * What the row asks for: `add`, `remove` or `none` when the pair is already as asked. Null
     * for a turned-down row.
     *
     * @var ?string $action
     */
    #[JsonProperty('action')]
    public ?string $action;

    /**
     * `applied` when the pair now stands as the row asks, `not_applied` when the row passed but
     *             nothing was changed (the request was blocked or a dry run), `rejected` when the row was turned down,
     *             `waiting` for a background row not reached yet, and `failed` for a background row that could
     *             not be applied.
     *
     * @var ?string $status
     */
    #[JsonProperty('status')]
    public ?string $status;

    /**
     * Why a row was turned down: `person_missing`, `person_not_found`, `person_ambiguous`,
     * `reviewer_not_in_group`, `applicant_not_in_pool`, `own_application`, `duplicate_row`
     * or `review_started_or_finished`. Null otherwise.
     *
     * @var ?string $reason
     */
    #[JsonProperty('reason')]
    public ?string $reason;

    /**
     * @var ?string $message A plain sentence that names the email the row is about. Null unless the row was turned down or failed.
     */
    #[JsonProperty('message')]
    public ?string $message;

    /**
     * @param array{
     *   index?: ?int,
     *   reviewerId?: ?string,
     *   reviewerEmail?: ?string,
     *   applicantId?: ?string,
     *   applicantEmail?: ?string,
     *   action?: ?string,
     *   status?: ?string,
     *   reason?: ?string,
     *   message?: ?string,
     * } $values
     */
    public function __construct(
        array $values = [],
    ) {
        $this->index = $values['index'] ?? null;
        $this->reviewerId = $values['reviewerId'] ?? null;
        $this->reviewerEmail = $values['reviewerEmail'] ?? null;
        $this->applicantId = $values['applicantId'] ?? null;
        $this->applicantEmail = $values['applicantEmail'] ?? null;
        $this->action = $values['action'] ?? null;
        $this->status = $values['status'] ?? null;
        $this->reason = $values['reason'] ?? null;
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
