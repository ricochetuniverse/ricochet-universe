import {z} from 'zod/mini';

export const DiscordWidgetMemberSchema = z.object({
    // avatar: null;
    avatar_url: z.url(),
    // discriminator: '0000';
    // game?: {
    //     name: string;
    // };
    id: z.string(),
    status: z.enum(['online', 'idle', 'dnd']),
    username: z.string(),
});

export const DiscordWidgetApiSchema = z.object({
    members: z.array(DiscordWidgetMemberSchema),
    presence_count: z.number(),
});
