<?php

namespace Awardspring;

use Awardspring\Applications\ApplicationsClient;
use Awardspring\AwardCycles\AwardCyclesClient;
use Awardspring\Awards\AwardsClient;
use Awardspring\Departments\DepartmentsClient;
use Awardspring\DonorActivities\DonorActivitiesClient;
use Awardspring\Donors\DonorsClient;
use Awardspring\Funds\FundsClient;
use Awardspring\Gifts\GiftsClient;
use Awardspring\PaymentSchedules\PaymentSchedulesClient;
use Awardspring\People\PeopleClient;
use Awardspring\Reviewers\ReviewersClient;
use Awardspring\Scholarships\ScholarshipsClient;
use Awardspring\Students\StudentsClient;
use Psr\Http\Client\ClientInterface;
use Awardspring\Core\Client\RawClient;

class AwardspringApiClient
{
    /**
     * @var ApplicationsClient $applications
     */
    public ApplicationsClient $applications;

    /**
     * @var AwardCyclesClient $awardCycles
     */
    public AwardCyclesClient $awardCycles;

    /**
     * @var AwardsClient $awards
     */
    public AwardsClient $awards;

    /**
     * @var DepartmentsClient $departments
     */
    public DepartmentsClient $departments;

    /**
     * @var DonorActivitiesClient $donorActivities
     */
    public DonorActivitiesClient $donorActivities;

    /**
     * @var DonorsClient $donors
     */
    public DonorsClient $donors;

    /**
     * @var FundsClient $funds
     */
    public FundsClient $funds;

    /**
     * @var GiftsClient $gifts
     */
    public GiftsClient $gifts;

    /**
     * @var PaymentSchedulesClient $paymentSchedules
     */
    public PaymentSchedulesClient $paymentSchedules;

    /**
     * @var PeopleClient $people
     */
    public PeopleClient $people;

    /**
     * @var ReviewersClient $reviewers
     */
    public ReviewersClient $reviewers;

    /**
     * @var ScholarshipsClient $scholarships
     */
    public ScholarshipsClient $scholarships;

    /**
     * @var StudentsClient $students
     */
    public StudentsClient $students;

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
     * @param string $apiKey The apiKey to use for authentication.
     * @param ?array{
     *   baseUrl?: string,
     *   client?: ClientInterface,
     *   maxRetries?: int,
     *   timeout?: float,
     *   headers?: array<string, string>,
     * } $options
     */
    public function __construct(
        string $apiKey,
        ?array $options = null,
    ) {
        $defaultHeaders = [
            'X-Spring-API-Key' => $apiKey,
            'X-Fern-Language' => 'PHP',
            'X-Fern-SDK-Name' => 'Awardspring',
            'X-Fern-SDK-Version' => '0.1.11',
            'User-Agent' => 'awardspring/awardspring-php/0.1.11',
        ];

        $this->options = $options ?? [];

        $this->options['headers'] = array_merge(
            $defaultHeaders,
            $this->options['headers'] ?? [],
        );

        $this->client = new RawClient(
            options: $this->options,
        );

        $this->applications = new ApplicationsClient($this->client, $this->options);
        $this->awardCycles = new AwardCyclesClient($this->client, $this->options);
        $this->awards = new AwardsClient($this->client, $this->options);
        $this->departments = new DepartmentsClient($this->client, $this->options);
        $this->donorActivities = new DonorActivitiesClient($this->client, $this->options);
        $this->donors = new DonorsClient($this->client, $this->options);
        $this->funds = new FundsClient($this->client, $this->options);
        $this->gifts = new GiftsClient($this->client, $this->options);
        $this->paymentSchedules = new PaymentSchedulesClient($this->client, $this->options);
        $this->people = new PeopleClient($this->client, $this->options);
        $this->reviewers = new ReviewersClient($this->client, $this->options);
        $this->scholarships = new ScholarshipsClient($this->client, $this->options);
        $this->students = new StudentsClient($this->client, $this->options);
    }
}
