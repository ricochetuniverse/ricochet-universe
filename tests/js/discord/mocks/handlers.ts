import {http, HttpResponse} from 'msw';

import {WIDGET_API_URL} from '../../../../resources/js/discord/DiscordWidgetContainer';

export const handlers = [
    http.get(WIDGET_API_URL, () => {
        return HttpResponse.json({
            id: '295184393109110785',
            name: 'Ricochet Players',
            instant_invite: null,
            channels: [
                {
                    id: '412713243426029578',
                    name: 'General voice chat',
                    position: 0,
                },
            ],
            members: [
                {
                    id: '0',
                    username: 'a...',
                    discriminator: '0000',
                    avatar: null,
                    status: 'online',
                    avatar_url: 'https://cdn.discordapp.com/widget-avatars/xxx',
                },
            ],
            presence_count: 105,
        });
    }),
];
