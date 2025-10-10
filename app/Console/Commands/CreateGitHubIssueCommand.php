<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Github\AuthMethod;
use Github\Client;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Lcobucci\JWT\Configuration;
use Lcobucci\JWT\Encoding\ChainedFormatter;
use Lcobucci\JWT\Signer\Key\InMemory;
use Lcobucci\JWT\Signer\Rsa\Sha256;

class CreateGitHubIssueCommand extends Command
{
    protected $signature = 'ricochet:create-github-issue';

    protected $description = 'Create a GitHub issue for testing';

    public function handle(): void
    {
        $repoName = explode('/', config('ricochet.discord_interaction_export_github_repo'));
        if (count($repoName) !== 2) {
            throw new \InvalidArgumentException('GitHub repo name is not set up');
        }

        $client = new Client;
        $client->authenticate($this->getInstallationToken(), '', AuthMethod::JWT);

        $issue = $client->issues()->create($repoName[0], $repoName[1], [
            'title' => 'test creating from laravel',
            'body' => "test **markdown** \n \n [Google url](https://google.com)",
        ]);
        print_r($issue);
    }

    /**
     * @see https://github.com/KnpLabs/php-github-api/blob/master/doc/security.md#authenticating-as-an-integration
     */
    private function getInstallationToken(): string
    {
        // One hour expiration: https://docs.github.com/en/apps/creating-github-apps/authenticating-with-a-github-app/authenticating-as-a-github-app-installation#using-an-installation-access-token-to-authenticate-as-an-app-installation
        return Cache::remember('github_installation_token', 3600, static function () {
            $config = Configuration::forSymmetricSigner(
                new Sha256,
                InMemory::file(config('services.github.signing_key_file'))
            );

            $now = new \DateTimeImmutable;
            $jwt = $config->builder(ChainedFormatter::withUnixTimestampDates())
                ->issuedBy(config('services.github.integration_id'))
                ->issuedAt($now)
                ->expiresAt($now->modify('+1 minute'))
                ->getToken($config->signer(), $config->signingKey());

            $client = new Client;
            $client->authenticate($jwt->toString(), null, AuthMethod::JWT);
            $token = new \Github\Api\Apps($client)->createInstallationToken(config('services.github.installation_id'));

            return $token['token'];
        });
    }
}
