<?php

namespace Awardspring\Students;

use Psr\Http\Client\ClientInterface;
use Awardspring\Core\Client\RawClient;
use Awardspring\Students\Requests\ListStudentsRequest;
use Awardspring\Types\StudentV1ListResponse;
use Awardspring\Exceptions\AwardspringException;
use Awardspring\Exceptions\AwardspringApiException;
use Awardspring\Core\Json\JsonApiRequest;
use Awardspring\Environments;
use Awardspring\Core\Client\HttpMethod;
use JsonException;
use Psr\Http\Client\ClientExceptionInterface;
use Awardspring\Students\Requests\CreateStudentV1Request;
use Awardspring\Types\StudentV1;

class StudentsClient
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
     * Returns one page at a time, covering every student account at the institution — both
     * registered and prospective. Search with `q`: it splits on spaces and matches each
     * term against first name, last name, or email. Filter with `student_id` to look a
     * student up by the number the institution assigned; numbers are not guaranteed unique, so
     * the match can return more than one student.
     *
     * Assistant capability: `springiq_list_students`. Required permission: Users (applies when called on behalf of a signed-in person).
     *
     * Example:
     * ```php
     * $client->students->list(
     *     new ListStudentsRequest([]),
     * );
     * ```
     *
     * @param ListStudentsRequest $request
     * @param ?array{
     *   baseUrl?: string,
     *   maxRetries?: int,
     *   timeout?: float,
     *   headers?: array<string, string>,
     *   queryParameters?: array<string, mixed>,
     *   bodyProperties?: array<string, mixed>,
     * } $options
     * @return ?StudentV1ListResponse
     * @throws AwardspringException
     * @throws AwardspringApiException
     */
    public function list(ListStudentsRequest $request = new ListStudentsRequest(), ?array $options = null): ?StudentV1ListResponse
    {
        $options = array_merge($this->options, $options ?? []);
        $query = [];
        if ($request->q != null) {
            $query['q'] = $request->q;
        }
        if ($request->studentId != null) {
            $query['student_id'] = $request->studentId;
        }
        if ($request->limit != null) {
            $query['limit'] = $request->limit;
        }
        if ($request->startingAfter != null) {
            $query['starting_after'] = $request->startingAfter;
        }
        if ($request->endingBefore != null) {
            $query['ending_before'] = $request->endingBefore;
        }
        if ($request->include != null) {
            $query['include'] = $request->include;
        }
        try {
            $response = $this->client->sendRequest(
                new JsonApiRequest(
                    baseUrl: $options['baseUrl'] ?? $this->client->options['baseUrl'] ?? Environments::UnitedStates->value,
                    path: "api/v1/students",
                    method: HttpMethod::GET,
                    query: $query,
                ),
                $options,
            );
            $statusCode = $response->getStatusCode();
            if ($statusCode >= 200 && $statusCode < 400) {
                $json = $response->getBody()->getContents();
                if (empty($json)) {
                    return null;
                }
                return StudentV1ListResponse::fromJson($json);
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

    /**
     * Adds one student account, with the same rules as adding a student from the Users list in the AwardSpring
     * admin interface: a first name, a last name and a well-formed email are required, each at most 50
     * characters. An optional `student_id` must not already be on another account. The new student gets the
     * same welcome email the admin interface sends, with a link to set a password.
     *
     * <b>An email that already has an account:</b> by default the email must not already be used by any account
     * at the institution, ignoring upper and lower case; if it is, this returns `400 email_already_exists`.
     * With `if_email_in_use` set to `add_role`, this does what the admin interface does instead, and no
     * second account is made. When an active account at the institution has the email, the student role is added
     * to that account, which keeps its name, email, password and student ID (a `student_id` sent with the request
     * is still checked against other accounts but is not saved), and this returns `200` with that account as
     * the student. An account that already has the student role is returned unchanged, also with `200`. The
     * role is never added to an account that holds an administrator role, and this returns
     * `400 email_already_exists` instead, as it does for an email whose account is deactivated, merged, or one
     * the admin interface would not change. When only an archived account has the email, that account gets no
     * role: a new student is made with `201` and the email moves to them, as in the admin interface.
     * The `AwardSpring-Existing-Account` header says which of these happened: `role_added`,
     * `role_already_held` or `email_moved_from_archived`. It is absent for an ordinary create.
     *
     * <b>Welcome email:</b> the response carries an `AwardSpring-Welcome-Email` header. It is `queued`
     * when the email is on its way, `not_sent` when it could not be sent, and `none` when the role went
     * onto an account that already had the email (`if_email_in_use` set to `add_role`), which gets no
     * welcome email. The student is created either way; for
     * `not_sent`, send them a set-password link with Reset Password on their account in the admin
     * interface. A response replayed for an `Idempotency-Key` carries neither header.
     *
     * <b>Idempotency:</b> the request supports an optional `Idempotency-Key` header (any
     * printable-ASCII string, 1–255 chars — UUIDs work well). When supplied, the same key plus the same
     * request body within 24 hours returns the original response verbatim without creating a duplicate
     * student. The same key with a different body returns `422 idempotency_key_request_mismatch`; a
     * concurrent retry while the first call is still executing returns `409 idempotency_key_in_flight`.
     *
     * Example:
     * ```php
     * $client->students->create(
     *     new CreateStudentV1Request([]),
     * );
     * ```
     *
     * @param CreateStudentV1Request $request
     * @param ?array{
     *   baseUrl?: string,
     *   maxRetries?: int,
     *   timeout?: float,
     *   headers?: array<string, string>,
     *   queryParameters?: array<string, mixed>,
     *   bodyProperties?: array<string, mixed>,
     * } $options
     * @return ?StudentV1
     * @throws AwardspringException
     * @throws AwardspringApiException
     */
    public function create(CreateStudentV1Request $request, ?array $options = null): ?StudentV1
    {
        $options = array_merge($this->options, $options ?? []);
        try {
            $response = $this->client->sendRequest(
                new JsonApiRequest(
                    baseUrl: $options['baseUrl'] ?? $this->client->options['baseUrl'] ?? Environments::UnitedStates->value,
                    path: "api/v1/students",
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
                return StudentV1::fromJson($json);
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

    /**
     * Fetches one student by their `id` from the students list. Returns
     * `404 student_not_found`, naming `id` in `param`, if no such student belongs to this
     * institution.
     *
     * Assistant capability: `springiq_get_student`. Required permission: Users (applies when called on behalf of a signed-in person).
     *
     * Example:
     * ```php
     * $client->students->get(
     *     'id',
     * );
     * ```
     *
     * @param string $id
     * @param ?array{
     *   baseUrl?: string,
     *   maxRetries?: int,
     *   timeout?: float,
     *   headers?: array<string, string>,
     *   queryParameters?: array<string, mixed>,
     *   bodyProperties?: array<string, mixed>,
     * } $options
     * @return ?StudentV1
     * @throws AwardspringException
     * @throws AwardspringApiException
     */
    public function get(string $id, ?array $options = null): ?StudentV1
    {
        $options = array_merge($this->options, $options ?? []);
        try {
            $response = $this->client->sendRequest(
                new JsonApiRequest(
                    baseUrl: $options['baseUrl'] ?? $this->client->options['baseUrl'] ?? Environments::UnitedStates->value,
                    path: "api/v1/students/{$id}",
                    method: HttpMethod::GET,
                ),
                $options,
            );
            $statusCode = $response->getStatusCode();
            if ($statusCode >= 200 && $statusCode < 400) {
                $json = $response->getBody()->getContents();
                if (empty($json)) {
                    return null;
                }
                return StudentV1::fromJson($json);
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
