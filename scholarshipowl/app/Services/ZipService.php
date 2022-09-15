<?php

namespace App\Services;

use GuzzleHttp\Client;

class ZipService
{
    protected $apiUrl = 'https://maps.googleapis.com/maps/api/geocode';

    /**
     * @var Client
     */
    protected $httpClient;

    /**
     * @return Client
     */
    public function getHttpClient(): Client
    {
        if ($this->httpClient === null) {
            $this->httpClient = new Client();
        }

        return $this->httpClient;
    }

    /**
     * @param Client $httpClient
     *
     * @return $this
     */
    public function setHttpClient(Client $httpClient): self
    {
        $this->httpClient = $httpClient;

        return $this;
    }

    /**
     * Returns City and State by ZIP code for US only.
     *
     * @param int|string $zipCode numeric string
     * @return array
     */
    public function getData($zipCode): array
    {
        $result = [
            'state' => null,
            'city' => null
        ];

        if (strlen((string)$zipCode) !== 5) { // US zips only
            return $result;
        }

        try {
            $response = $this->getHttpClient()->get(
                $this->apiUrl . "/json",
                [
                    'query' => [
                        'address' => $zipCode,
                        'key' => config('services.zipService.api_key')
                    ]
                ]
            );
            $data = json_decode($response->getBody()->getContents(), true);

            if (!is_array($data) || !isset($data['results'][0]['address_components'])) {
                throw new \Exception("ZipService - failed to obtain State and Country by ZIP code: {$zipCode}");
            }

            $countryAbbr = '';
            $stateAbbr = '';
            $city = '';
            foreach ($data['results'][0]['address_components'] as $v) {
                if (in_array('administrative_area_level_1', $v['types'])) {
                    $stateAbbr = $v['short_name'];
                } else if (in_array('country', $v['types'])) {
                    $countryAbbr = $v['short_name'];
                } else if (in_array('locality', $v['types'])) {
                    $city = $v['long_name'];
                }
            }

            if (empty($stateAbbr)) {
                throw new \Exception("ZipService - failed to obtain State and Country by ZIP code: {$zipCode}");
            }

            if ($countryAbbr !== 'US') {
                throw new \Exception("ZipService - zip code is none US: {$zipCode}");
            }

            /** @var \App\Entity\State $state */
            $state = \EntityManager::getRepository(\App\Entity\State::class)
                ->findOneBy(['abbreviation' => $stateAbbr]);

            if (!$state) {
                throw new \Exception(
                    "ZipService - failed to fetch data \App\Entity\State by state abbreviation: {$stateAbbr}"
                );
            }

            $result['state'] = [
                'id' => $state->getId(),
                'name' => $state->getName(),
                'abbreviation' => $state->getAbbreviation()
            ];
            $result['city'] = ucwords(strtolower($city));
        }
        catch(\Exception $e) {
            \Log::error($e->getMessage());
        }

        return $result;
    }
}
