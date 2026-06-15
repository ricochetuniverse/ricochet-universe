import type {z} from 'zod/mini';

import styles from './DiscordWidget.module.scss';
import DiscordWidgetMember from './DiscordWidgetMember';
import {DiscordWidgetMemberSchema} from './DiscordWidgetMemberType';

type Props = Readonly<{
    loading: boolean;
    error: boolean;
    members: z.infer<typeof DiscordWidgetMemberSchema>[];
    presenceCount: number;
}>;

export default function DiscordWidget(props: Props) {
    if (props.loading) {
        return <div className={styles.body}>Loading...</div>;
    }

    if (props.error) {
        // Just show nothing, at least there's a Join button
        return null;
    }

    return (
        <div className={styles.body}>
            <div className={styles.heading}>
                {props.presenceCount} Members Online
            </div>

            <ul className={styles.memberList} data-testid="members">
                {props.members.map((member) => {
                    return (
                        <DiscordWidgetMember key={member.id} member={member} />
                    );
                })}
            </ul>
        </div>
    );
}
