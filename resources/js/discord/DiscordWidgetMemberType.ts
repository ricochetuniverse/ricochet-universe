import {z} from 'zod';

export const DiscordWidgetMemberSchema = z.object({
    // avatar: null;
    avatar_url: z.string().url(),
    // discriminator: '0000';
    // game?: {
    //     name: string;
    // };
    id: z.string(),
    status: z.enum(['online', 'idle', 'dnd']),
    username: z.string(),
});

export const DiscordWidgetApiSchema = z.object({
    members: DiscordWidgetMemberSchema.array(),
    presence_count: z.number(),
});
