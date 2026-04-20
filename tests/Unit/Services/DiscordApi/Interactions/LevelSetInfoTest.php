<?php

declare(strict_types=1);

namespace Tests\Unit\Services\DiscordApi\Interactions;

use App\LevelSet;
use App\Services\DiscordApi\Interactions\LevelSetInfo;
use Database\Seeders\ReflexiveLevelSetSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Tests\Unit\Services\DiscordApi\DiscordAPITestCase;

class LevelSetInfoTest extends DiscordAPITestCase
{
    use RefreshDatabase;

    public function test_no_levels_found(): void
    {
        $this->fakeEditInteractionResponse();

        LevelSetInfo::handleApplicationCommand(
            $this->getIncomingRequestJson('abc.RicochetI', 'abc')
        );

        Http::assertSent(function (Request $request) {
            return $request->method() === 'PATCH'
                && str_ends_with($request->url(), 'webhooks/'.config('services.discord.client_id').'/'.$this->getFakeToken().'/messages/@original')
                && str_contains($request->data()['content'], 'No level sets found matching “abc”');
        });
    }

    public function test_one_level_found(): void
    {
        $this->seed(ReflexiveLevelSetSeeder::class);
        $levelSet = LevelSet::where('name', 'Reflexive B Sides')->sole();
        $fileContents = file_get_contents(base_path('tests/fixtures/Reflexive B Sides.RicochetLW'));

        $this->fakeEditInteractionResponse();
        Http::fake([
            'https://cdn.discordapp.com/attachments/1476551769894027366/1483813720273588334/*' => $fileContents,
            $levelSet->alternate_download_url => $fileContents,
        ]);

        LevelSetInfo::handleApplicationCommand(
            $this->getIncomingRequestJson('Reflexive_B_Sides.RicochetLW', 'Reflexive B Sides')
        );

        Http::assertSent(function (Request $request) {
            if ($request->method() !== 'PATCH'
                || ! str_ends_with($request->url(), 'webhooks/'.config('services.discord.client_id').'/'.$this->getFakeToken().'/messages/@original')) {
                return false;
            }

            $json = $request->data();

            return $json['embeds'][0]['title'] === 'Reflexive B Sides'
                && $json['embeds'][0]['fields'][2]['name'] === 'File checksum match'
                && $json['embeds'][0]['fields'][2]['value'] === 'Yes';
        });
    }

    private function fakeEditInteractionResponse(): void
    {
        Http::fake([
            'https://discord.com/api/v10/webhooks/'.config('services.discord.client_id').'/'.$this->getFakeToken().'/messages/@original' => '{}',
        ]);
    }

    private function getIncomingRequestJson(string $filename, string $title): array
    {
        return json_decode(<<<EOF
{
  "app_permissions": "562949953863680",
  "application_id": "1312345678901234567",
  "attachment_size_limit": 10485760,
  "authorizing_integration_owners": { "1": "456789012345678901" },
  "channel": {
    "flags": 0,
    "guild_id": "1470123456789012345",
    "icon_emoji": { "id": null, "name": "👋" },
    "id": "1476551769894027366",
    "last_message_id": "1483813720546086923",
    "name": "general",
    "nsfw": false,
    "parent_id": "1471234567890123456",
    "permissions": "18014398509481983",
    "position": 0,
    "rate_limit_per_user": 0,
    "theme_color": null,
    "topic": null,
    "type": 0
  },
  "channel_id": "1476551769894027366",
  "context": 0,
  "data": {
    "id": "1425054610967498814",
    "name": "Level set info",
    "resolved": {
      "messages": {
        "1483813720546086923": {
          "attachments": [
            {
              "content_scan_version": 0,
              "filename": "{$filename}",
              "id": "1483813720273588334",
              "proxy_url": "https://media.discordapp.net/attachments/1476551769894027366/1483813720273588334/{$filename}?ex=69e77636&is=69e624b6&hm=a7262803474f4b9da563fbd9943e69493281d5e5f810d8b11e702c5f2e0ed634&",
              "size": 126694,
              "title": "{$title}",
              "url": "https://cdn.discordapp.com/attachments/1476551769894027366/1483813720273588334/{$filename}?ex=69e77636&is=69e624b6&hm=a7262803474f4b9da563fbd9943e69493281d5e5f810d8b11e702c5f2e0ed634&"
            }
          ],
          "author": {
            "avatar": "3c53c51c8a7d4d76606b448587fce6c0",
            "avatar_decoration_data": null,
            "clan": null,
            "collectibles": null,
            "discriminator": "0",
            "display_name_styles": null,
            "global_name": "user",
            "id": "456789012345678901",
            "primary_guild": null,
            "public_flags": 0,
            "username": "user"
          },
          "channel_id": "1476551769894027366",
          "components": [],
          "content": "",
          "edited_timestamp": null,
          "embeds": [],
          "flags": 0,
          "id": "1483813720546086923",
          "mention_everyone": false,
          "mention_roles": [],
          "mentions": [],
          "pinned": false,
          "timestamp": "2026-03-18T13:05:58.904000+00:00",
          "tts": false,
          "type": 0
        }
      }
    },
    "target_id": "1483813720546086923",
    "type": 3
  },
  "entitlement_sku_ids": [],
  "entitlements": [],
  "guild": { "features": [], "id": "1470123456789012345", "locale": "en-US" },
  "guild_id": "1470123456789012345",
  "guild_locale": "en-US",
  "id": "1495012345678901234",
  "locale": "en-US",
  "member": {
    "avatar": null,
    "banner": null,
    "communication_disabled_until": null,
    "deaf": false,
    "flags": 0,
    "joined_at": "2026-01-01T12:00:00.794000+00:00",
    "mute": false,
    "nick": null,
    "pending": false,
    "permissions": "18014398509481983",
    "premium_since": null,
    "roles": [],
    "unusual_dm_activity_until": null,
    "user": {
      "avatar": "3c53c51c8a7d4d76606b448587fce6c0",
      "avatar_decoration_data": null,
      "clan": null,
      "collectibles": null,
      "discriminator": "0",
      "display_name_styles": null,
      "global_name": "user",
      "id": "456789012345678901",
      "primary_guild": null,
      "public_flags": 0,
      "username": "user"
    }
  },
  "token": "{$this->getFakeToken()}",
  "type": 2,
  "version": 1
}
EOF, true, 512, JSON_THROW_ON_ERROR);
    }
}
