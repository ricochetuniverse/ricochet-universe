// noinspection ES6UnusedImports
import {Component, h} from 'preact';
import {Button} from 'reactstrap';

import getDiscordMemberName from './getDiscordMemberName';

// member prop:
// avatar, avatar_url, discriminator, game, game.name, id, nick, status, username
export default function DiscordWidget(props) {
    return (
        <div>
            <a href={props.inviteLink} className="discordWidget__header">
                <div className="discordWidget__logo">
                    <span className="sr-only">Discord</span>
                </div>

                <Button tag="span" outline color="secondary">
                    Join
                </Button>
            </a>

            {getWidgetBody(props)}
        </div>
    );
}

function getWidgetBody(props) {
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
                        <li className="discordWidget__member">
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
                            <span className="text-nowrap">
                                {getDiscordMemberName(member)}
                            </span>
                        </li>
                    );
                })}
            </ul>
        </div>
    );
}
