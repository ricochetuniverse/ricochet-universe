import {useState} from 'preact/hooks';
import type {z} from 'zod/mini';

import styles from './DiscordWidgetMember.module.scss';
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
        <li className={styles.member} key={member.id}>
            <div className={styles.avatar}>
                <img
                    alt={member.username + '’s avatar'}
                    className={styles.image}
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
                        member.status === 'online'
                            ? styles.statusOnline
                            : member.status === 'idle'
                              ? styles.statusIdle
                              : member.status === 'dnd'
                                ? styles.statusDnd
                                : ''
                    }
                />
            </div>
            <span className="text-truncate">{member.username}</span>
        </li>
    );
}
