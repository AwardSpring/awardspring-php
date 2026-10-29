<?php

namespace Awardspring\Reviewers;

use Psr\Http\Client\ClientInterface;
use Awardspring\Core\Client\RawClient;
use Awardspring\Reviewers\Requests\CreateReviewerV1Request;
use Awardspring\Types\ReviewerV1;
use Awardspring\Exceptions\AwardspringException;
use Awardspring\Exceptions\AwardspringApiException;
use Awardspring\Core\Json\JsonApiRequest;
use Awardspring\Environments;
use Awardspring\Core\Client\HttpMethod;
use JsonException;
use Psr\Http\Client\ClientExceptionInterface;

class ReviewersClient
{
    /**
     * @var array{
     *   baseUrl?: string,
     *   client?: ClientInterface,
     *   maxRetries?: int,
     *   timeout?: float,
     *   headers?: array<string, string>,
     * } $options @phpstan-ignore-next-line Property is used in endpoint methods via HttpEndpointGenerator
     */
    private array $options;

    /**
     * @var RawClient $client
     */
    private RawClient $client;

    /**
     * @param RawClient $client
     * @param ?array{
     *   baseUrl?: string,
     *   client?: ClientInterface,
     *   maxRetries?: int,
     *   timeout?: float,
     *   headers?: array<string, string>,
     * } $options
     */
    public function __construct(
        RawClient $client,
        ?array $options = null,
    ) {
        $this->client = $client;
        $this->options = $options ?? [];
    }

    /**
     * Adds one reviewer account, with the same rules as adding a reviewer from the Users list in the AwardSpring
     * admin interface: a first name, a last name and a well-formed email are required, each at most 50
     * characters. As in the admin interface, a new reviewer gets no welcome email.
     *
     * <b>An email that already has an account:</b> by default the email must not already be used by any account
     * at the institution, ignoring upper and lower case; if it is, this returns `400 email_already_exists`.
     * With `if_email_in_use` set to `add_role`, this does what the admin interface does instead, and no
     * second account is made. When an active account at the institution has the email, the reviewer role is added
     * to that account, which keeps its name, email and password, and this returns `200` with that account as
     * the reviewer. An account that already has the reviewer role is returned unchanged, also with `200`. The
     * role is never added to an account that holds an administrator role, and this returns
     * `400 email_already_exists` instead, as it does for an email whose account is deactivated, merged, or one
     * the admin interface would not change. When only an archived account has the email, that account gets no
     * role: a new reviewer is made with `201` and the email moves to them, as in the admin interface.
     * The `AwardSpring-Existing-Account` header says which of these happened: `role_added`,
     * `role_already_held` or `email_moved_from_archived`. It is absent for an ordinary create, and from
     * a response replayed for an `Idempotency-Key`.
     *
     * <b>Idempotency:</b> the request supports an optional `Idempotency-Key` header (any
     * printable-ASCII string, 1–255 chars — UUIDs work well). When supplied, the same key plus the same
     * request body within 24 hours returns the original response verbatim without creating a duplicate
     * reviewer. The same key with a different body returns `422 idempotency_key_request_mismatch`; a
     * concurrent retry while the first call is still executing returns `409 idempotency_key_in_flight`.
     *
     * Example:
     * ```php
     * $client->reviewers->create(
     *     new CreateReviewerV1Request([]),
     * );
     * ```
     *
     * @param CreateReviewerV1Request $request
     * @param ?array{
     *   baseUrl?: string,
     *   maxRetries?: int,
     *   timeout?: float,
     *   headers?: array<string, string>,
     *   queryParameters?: array<string, mixed>,
     *   bodyProperties?: array<string, mixed>,
     * } $options
     * @return ?ReviewerV1
     * @throws AwardspringException
     * @throws AwardspringApiException
     */
    public function create(CreateReviewerV1Request $request, ?array $options = null): ?ReviewerV1
    {
        $options = array_merge($this->options, $options ?? []);
        try {
            $response = $this->client->sendRequest(
                new JsonApiRequest(
                    baseUrl: $options['baseUrl'] ?? $this->client->options['baseUrl'] ?? Environments::UnitedStates->value,
                    path: "api/v1/reviewers",
                    method: HttpMethod::POST,
                    body: $request,
                ),
                $options,
            );
            $statusCode = $response->getStatusCode();
            if ($statusCode >= 200 && $statusCode < 400) {
                $json = $response->getBody()->getContents();
                if (empty($json)) {
                    return null;
                }
                return ReviewerV1::fromJson($json);
            }
        } catch (JsonException $e) {
            throw new AwardspringException(message: "Failed to deserialize response: {$e->getMessage()}", previous: $e);
        } catch (ClientExceptionInterface $e) {
            throw new AwardspringException(message: $e->getMessage(), previous: $e);
        }
        throw new AwardspringApiException(
            message: 'API request failed',
            statusCode: $statusCode,
            body: $response->getBody()->getContents(),
        );
    }
}
