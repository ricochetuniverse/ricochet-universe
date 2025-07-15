import {z} from 'zod/mini';

export const RoundInfoSchema = z.partial(
    z.object({
        name: z.string(),
        author: z.string(),
        note1: z.string(),
        note2: z.string(),
        note3: z.string(),
        note4: z.string(),
        note5: z.string(),
        source: z.string(),
        imageUrl: z.url(),
    })
);
