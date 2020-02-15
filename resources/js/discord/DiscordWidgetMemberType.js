// @flow strict

export type DiscordWidgetMemberType = {|
    avatar: null,
    avatar_url: string,
    discriminator: '0000',
    game?: {|
        name: string,
    |},
    id: string,
    status: 'online' | 'idle' | 'dnd',
    username: string,
|};
