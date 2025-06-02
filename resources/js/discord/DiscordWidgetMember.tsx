import {useState} from 'preact/hooks';
import type {z} from 'zod/v4-mini';

import {DiscordWidgetMemberSchema} from './DiscordWidgetMemberType';

type Props = Readonly<{
    member: z.infer<typeof DiscordWidgetMemberSchema>;
}>;

export default function DiscordWidgetMember({member}: Props) {
    const [isError, setIsError] = useState(false);

    if (isError) {
        return null;
    }

    return (
        <li className="discordWidget__member" key={member.id}>
            <div className="discordWidget__member__avatar">
                <img
                    alt={member.username + '’s avatar'}
                    className="discordWidget__member__avatar__image"
                    height={16}
                    loading="lazy"
                    onError={() => {
                        // default Discord avatars don't return Access-Control-Allow-Origin header
                        setIsError(true);
                    }}
                    src={member.avatar_url}
                    width={16}
                />
                <span
                    className={
                        'discordWidget__member__avatar__status discordMemberStatus--' +
                        member.status
                    }
                />
            </div>
            <span className="text-truncate">{member.username}</span>
        </li>
    );
}
