import {h} from 'preact';

import getDiscordMemberName from './getDiscordMemberName';

// member prop:
// avatar, avatar_url, discriminator, game, game.name, id, nick, status, username
export default function DiscordWidget(props) {
    if (props.loading) {
        return <div className="discordWidget__body">Loading...</div>;
    }

    if (props.error) {
        // Just show nothing, at least there's a Join button
        return null;
    }

    return (
        <div className="discordWidget__body">
            <div className="discordWidget__heading">
                {props.members.length} Members Online
            </div>

            <ul className="discordWidget__memberList">
                {props.members.map((member) => {
                    return (
                        <li className="discordWidget__member" key={member.id}>
                            <div className="discordWidget__member__avatar">
                                <img
                                    src={member.avatar_url}
                                    alt=""
                                    width={16}
                                    height={16}
                                    className="discordWidget__member__avatar__image"
                                />
                                <span
                                    className={
                                        'discordWidget__member__avatar__status discordMemberStatus--' +
                                        member.status
                                    }
                                />
                            </div>
                            <span className="text-truncate">
                                {getDiscordMemberName(member)}
                            </span>
                        </li>
                    );
                })}
            </ul>
        </div>
    );
}
