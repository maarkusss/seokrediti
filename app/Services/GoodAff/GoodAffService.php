<?php

namespace App\Services\GoodAff;

use GuzzleHttp\Client;

class GoodAffService
{
    private $api_url;
    private $api_key;

    /**
     * @var Client
     */
    private $client;

    public function __construct()
    {
        $this->client =new Client([
            'verify' => false,
        ]);

        $this->api_url = config('services.goodaff.url');
        $this->api_key = config('services.goodaff.api_key');
    }

    public function getCampaignNames(): array
    {
        $endpoint = $this->getEndpoint('/campaigns/names');
        $endpoint .= '?' . http_build_query([
            'country' => config('services.goodaff.country'),
        ]);

        $options = [
            'headers' => [
                'apikey' => $this->api_key,
            ]
        ];

        $campaign_names = [];

        try {
            $response = $this->client->get($endpoint, $options);
            if (str_starts_with($response->getStatusCode(), '2')) {
                $data = json_decode($response->getBody());

                if (!empty($data)) {
                    foreach ($data as $id => $details) {
                        $campaign_names[$id] = [
                            'name' => $details->name,
                            'campaign_domain' => $details->campaign_domain,
                            'active' => $details->active,
                        ];
                    }
                }
            }
        } catch (\Exception $exception) {
            report($exception);
        }

        return $campaign_names;
    }

    protected function getEndpoint(string $endpoint): string
    {
        $base_url = $this->api_url;
        if (str_ends_with($base_url, '/')) {
            $base_url = substr($this->api_url, 0, -1);
        }

        if (str_starts_with($endpoint, '/')) {
            $endpoint = substr($endpoint, 1);
        }

        return $base_url . '/' . $endpoint;
    }
}
