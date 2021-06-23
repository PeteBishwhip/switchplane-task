<?php

namespace Task\Api;

use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\RequestOptions;

class Client extends HttpClient
{
    public const API_URL = 'https://task-api.switchplane.net/v1/';
    private const API_KEY = '3C6vXwVa3x';

    protected HttpClient $client;

    public static function factory(): self
    {
        return new self([
            'base_uri' => self::getApiUrl(),
        ]);
    }

    public static function getApiUrl(): string
    {
        return self::API_URL;
    }

    public function query($method, $path, $parameters)
    {
        try {
            $request = $this->request(
                $method,
                $path,
                [
                    RequestOptions::HEADERS => [
                        'Authorization' => 'Bearer ' . self::API_KEY,
                    ],
                    RequestOptions::QUERY => $parameters,
                    RequestOptions::HTTP_ERRORS => false,
                ]
            );
        } catch (\Throwable $e) {
            $request = [
                'error' => $e->getMessage(),
            ];
        }

        return $request;
    }
}