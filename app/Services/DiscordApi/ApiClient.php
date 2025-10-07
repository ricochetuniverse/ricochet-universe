<?php

declare(strict_types=1);

namespace App\Services\DiscordApi;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\RequestException;
use Illuminate\Http\Client\Response as HttpClientResponse;
use Illuminate\Support\Facades\Http;

class ApiClient
{
    private const string API_URL = 'https://discord.com/api/v10/';

    private static string $accessToken = '';

    /**
     * @throws RequestException
     * @throws ConnectionException
     */
    private static function getClientCredentials(): array
    {
        return Http::withBasicAuth(config('services.discord.client_id'), config('services.discord.client_secret'))
            ->asForm()
            ->post(self::API_URL.'oauth2/token', [
                'grant_type' => 'client_credentials',
                'scope' => 'applications.commands.update',
            ])
            ->throw()
            ->json();
    }

    private static function getAccessToken(): string
    {
        if (self::$accessToken !== '') {
            return self::$accessToken;
        }

        self::$accessToken = self::getClientCredentials()['access_token'];

        return self::$accessToken;
    }

    /**
     * @throws RequestException
     * @throws ConnectionException
     */
    public static function get(string $url): HttpClientResponse
    {
        return Http::withToken(self::getAccessToken())
            ->get(self::API_URL.$url)
            ->throw();
    }

    /**
     * @throws RequestException
     * @throws ConnectionException
     */
    public static function post(string $url, array $data = []): HttpClientResponse
    {
        return Http::withToken(self::getAccessToken())
            ->post(self::API_URL.$url, $data)
            ->throw();
    }

    /**
     * @throws RequestException
     * @throws ConnectionException
     */
    public static function patch(string $url, array $data = []): HttpClientResponse
    {
        return Http::withToken(self::getAccessToken())
            ->patch(self::API_URL.$url, $data)
            ->throw();
    }

    /**
     * @throws RequestException
     * @throws ConnectionException
     */
    public static function put(string $url, array $data = []): HttpClientResponse
    {
        return Http::withToken(self::getAccessToken())
            ->put(self::API_URL.$url, $data)
            ->throw();
    }
}
