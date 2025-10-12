<?php

declare(strict_types=1);

namespace App\Services\DiscordApi\Interactions;

use App\Services\DiscordApi\Enums\ComponentType;
use App\Services\DiscordApi\Enums\TextInputStyle;
use App\Services\DiscordApi\InteractionResponse;
use App\Services\DiscordApi\InteractsWithAttachments;
use App\Services\DiscordApi\ModalHandler;
use App\Services\DiscordApi\ModalType;
use App\Services\DiscordApi\UserFacingInteractionException;
use App\Services\LevelSetUploadProcessor;
use Carbon\Carbon;
use Github\Api\Apps;
use Github\AuthMethod;
use Github\Client;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Validation\ValidationException;
use Lcobucci\JWT\Configuration;
use Lcobucci\JWT\Encoding\ChainedFormatter;
use Lcobucci\JWT\Signer\Key\InMemory;
use Lcobucci\JWT\Signer\Rsa\Sha256;
use Sentry\Laravel\Facade as Sentry;

class ExportLevelSet
{
    use InteractsWithAttachments;

    public static function handleApplicationCommand(array $json): JsonResponse
    {
        $message = array_first($json['data']['resolved']['messages']);
        $attachment = self::getAttachment($message);

        $modalId = ModalHandler::setUpTempData(ModalType::EXPORT_LEVEL_SET, [
            'message_url' => self::getMessageUrl($json['channel'], $message),
            'download_url' => $attachment['url'],
            'timestamp' => Carbon::parse($message['timestamp'])->unix(),
        ]);

        $components = array_filter([
            [
                'type' => ComponentType::LABEL,
                'label' => 'Name',
                'component' => [
                    'type' => ComponentType::TEXT_INPUT,
                    'custom_id' => 'name',
                    'style' => TextInputStyle::SHORT,
                    'value' => self::getNameFromAttachment($attachment),
                ],
            ],
            /*[
                'type' => ComponentType::TEXT_DISPLAY,
                'content' => '**Timestamp:** '.Carbon::parse($message['timestamp'])->toRfc3339String(),
            ],*/
            self::getGitHubRepoName() !== null ? [
                'type' => ComponentType::LABEL,
                'label' => 'Create GitHub issue?',
                'component' => [
                    'type' => ComponentType::STRING_SELECT,
                    'custom_id' => 'create_github_issue',
                    'options' => [
                        [
                            'label' => 'Yes',
                            'value' => 'yes',
                            'emoji' => ['name' => '✅'],
                        ],
                        [
                            'label' => 'No',
                            'value' => 'no',
                            'emoji' => ['name' => '❌'],
                            'default' => true,
                        ],
                    ],
                    'required' => false,
                ],
            ] : null,
            [
                'type' => ComponentType::LABEL,
                'label' => 'Publish to level catalog?',
                'component' => [
                    'type' => ComponentType::STRING_SELECT,
                    'custom_id' => 'publish_to_catalog',
                    'options' => [
                        [
                            'label' => 'Yes',
                            'value' => 'yes',
                            'emoji' => ['name' => '✅'],
                        ],
                        [
                            'label' => 'No',
                            'value' => 'no',
                            'emoji' => ['name' => '❌'],
                            'default' => true,
                        ],
                    ],
                    'required' => false,
                ],
            ],
        ]);

        return InteractionResponse::modal([
            'custom_id' => $modalId,
            'title' => 'Export level set',
            'components' => $components,
        ]);
    }

    /**
     * @throws UserFacingInteractionException
     */
    public static function handleModalSubmit(array $request, array $tempData): JsonResponse
    {
        $name = '';
        $create_github_issue = false;
        $publish_to_catalog = false;

        $message_url = (string) $tempData['message_url'];
        $download_url = (string) $tempData['download_url'];
        $timestamp = (int) $tempData['timestamp'];

        foreach ($request['data']['components'] as $component) {
            switch ($component['component']['custom_id']) {
                case 'name':
                    $name = (string) $component['component']['value'];
                    break;

                case 'create_github_issue':
                    if ($component['component']['values'][0] === 'yes' && count($component['component']['values']) === 1) {
                        $create_github_issue = true;
                    }
                    break;

                case 'publish_to_catalog':
                    if ($component['component']['values'][0] === 'yes' && count($component['component']['values']) === 1) {
                        $publish_to_catalog = true;
                    }
                    break;

                default:
                    break;
            }
        }

        $actions = [];
        if ($create_github_issue) {
            try {
                $actions[] = '📝 Created GitHub issue '.self::createGitHubIssue(name: $name, message_url: $message_url);
            } catch (\Exception $exception) {
                Sentry::captureException($exception);
                $actions[] = '⚠️ Cannot create GitHub issue due to unknown error';
            }
        }

        if ($publish_to_catalog) {
            try {
                self::publishToCatalog(name: $name, download_url: $download_url, timestamp: $timestamp);
                $actions[] = '✅ Published to level catalog';
            } catch (ValidationException $exception) {
                $actions[] = '⚠️ Cannot publish to level catalog: '.$exception->getMessage();
            } catch (\Exception $exception) {
                Sentry::captureException($exception);
                $actions[] = '⚠️ Cannot publish to level catalog due to unknown error';
            }
        }

        if (count($actions) === 0) {
            throw new UserFacingInteractionException('No action is performed');
        }

        return InteractionResponse::ephemeralMessage(implode("\n", $actions));
    }

    private static function createGitHubIssue(string $name, string $message_url): string
    {
        $repoName = self::getGitHubRepoName();
        if (! $repoName) {
            throw new \InvalidArgumentException('GitHub repo name is not set up');
        }

        $client = new Client;
        $client->authenticate(self::getGitHubInstallationToken(), '', AuthMethod::JWT);

        $issue = $client->issues()->create($repoName[0], $repoName[1], [
            'title' => 'New level set: '.$name,
            'body' => 'Go to Discord message: '.$message_url,
        ]);

        return $issue['html_url'];
    }

    private static function publishToCatalog(string $name, string $download_url, int $timestamp): void
    {
        $processor = new LevelSetUploadProcessor;
        $processor->url = $download_url;
        $processor->name = $name;
        $processor->datePosted = Carbon::createFromTimestampUTC($timestamp);
        $processor->postToDiscord = true;

        // todo better error processing
        $levelSet = $processor->process();
    }

    private static function getMessageUrl(array $channel, array $message): string
    {
        return 'https://discord.com/channels/'.$channel['guild_id'].'/'.$message['channel_id'].'/'.$message['id'];
    }

    /**
     * @return array{string, string}|null
     */
    private static function getGitHubRepoName(): ?array
    {
        $repoName = explode('/', config('ricochet.discord_interaction_export_github_repo'));

        return count($repoName) === 2 ? $repoName : null;
    }

    /**
     * @see https://github.com/KnpLabs/php-github-api/blob/master/doc/security.md#authenticating-as-an-integration
     */
    private static function getGitHubInstallationToken(): string
    {
        // One hour expiration: https://docs.github.com/en/apps/creating-github-apps/authenticating-with-a-github-app/authenticating-as-a-github-app-installation#using-an-installation-access-token-to-authenticate-as-an-app-installation
        return Cache::remember('github_installation_token', 3600, static function () {
            $config = Configuration::forSymmetricSigner(
                new Sha256,
                InMemory::file(config('services.github.signing_key_file'))
            );

            $now = Carbon::now();
            $jwt = $config->builder(ChainedFormatter::withUnixTimestampDates())
                ->issuedBy(config('services.github.integration_id'))
                ->issuedAt($now->toDateTimeImmutable())
                ->expiresAt($now->addMinute()->toDateTimeImmutable())
                ->getToken($config->signer(), $config->signingKey());

            $client = new Client;
            $client->authenticate($jwt->toString(), null, AuthMethod::JWT);
            $token = new Apps($client)->createInstallationToken(config('services.github.installation_id'));

            return $token['token'];
        });
    }
}
