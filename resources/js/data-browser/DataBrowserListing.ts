import {z} from 'zod/mini';

export const DataBrowserListingSchema = z.array(
    z.object({
        prefix: z.string(),
        directories: z.array(z.string()),
        files: z.array(z.string()),
    })
);

export type DataBrowserListingType = z.infer<typeof DataBrowserListingSchema>;
