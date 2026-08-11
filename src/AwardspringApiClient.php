<?php

namespace Awardspring;

use Awardspring\AwardCycles\AwardCyclesClient;
use Awardspring\DonorActivities\DonorActivitiesClient;
use Awardspring\Donors\DonorsClient;
use Awardspring\Funds\FundsClient;
use Awardspring\Gifts\GiftsClient;
use Awardspring\Scholarships\ScholarshipsClient;
use Psr\Http\Client\ClientInterface;
use Awardspring\Core\Client\RawClient;

class AwardspringApiClient
{
    /**
     * @var AwardCyclesClient $awardCycles
     */
    public AwardCyclesClient $awardCycles;

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
     * @var ScholarshipsClient $scholarships
     */
    public ScholarshipsClient $scholarships;

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
            'X-Fern-SDK-Version' => '0.1.6',
            'User-Agent' => 'awardspring/awardspring-php/0.1.6',
        ];

        $this->options = $options ?? [];

        $this->options['headers'] = array_merge(
            $defaultHeaders,
            $this->options['headers'] ?? [],
        );

        $this->client = new RawClient(
            options: $this->options,
        );

        $this->awardCycles = new AwardCyclesClient($this->client, $this->options);
        $this->donorActivities = new DonorActivitiesClient($this->client, $this->options);
        $this->donors = new DonorsClient($this->client, $this->options);
        $this->funds = new FundsClient($this->client, $this->options);
        $this->gifts = new GiftsClient($this->client, $this->options);
        $this->scholarships = new ScholarshipsClient($this->client, $this->options);
    }
}
