<?php

namespace SnapchatScraper\Clients;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Psr\Http\Message\ResponseInterface;

class HttpClient
{
    private Client $client;

    public function __construct(array $config = [])
    {
        $this->client = new Client($config);
    }

    /**
     * Perform a GET request to a URL.
     *
     * @param string $url The URL to fetch.
     * @return string|array The response body or an error array.
     */
    public function get(string $url): mixed
    {
        try {
            $response = $this->client->get($url);


            if ($response->getStatusCode() === 200) {
                return $response->getBody()->getContents();
            }


            return $this->handleErrorResponse($response);
        } catch (GuzzleException $e) {

            error_log("[ERROR] GuzzleException: " . $e->getMessage());
            return ['error' => 'Request failed due to a network or server issue.'];
        }
    }

    /**
     * Handles error responses by returning a structured error message.
     *
     * @param ResponseInterface $response
     * @return array
     */
    private function handleErrorResponse(ResponseInterface $response): array
    {
        return [
            'error' => 'Request failed with status: ' . $response->getStatusCode(),
            'message' => $response->getReasonPhrase(),
        ];
    }
}
