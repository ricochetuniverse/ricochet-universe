<?php

namespace App\Enums\Discord;

/**
 * @see https://discord.com/developers/docs/interactions/receiving-and-responding#interaction-response-object-interaction-callback-type
 */
enum InteractionResponseType: int
{
    /** ACK a Ping */
    case PONG = 1;

    /** Respond to an interaction with a message */
    case CHANNEL_MESSAGE_WITH_SOURCE = 4;

    /** ACK an interaction and edit a response later, the user sees a loading state */
    case DEFERRED_CHANNEL_MESSAGE_WITH_SOURCE = 5;

    /** For components, ACK an interaction and edit the original message later; the user does not see a loading state */
    case DEFERRED_UPDATE_MESSAGE = 6;

    /** For components, edit the message the component was attached to */
    case UPDATE_MESSAGE = 7;

    /** Respond to an autocomplete interaction with suggested choices */
    case APPLICATION_COMMAND_AUTOCOMPLETE_RESULT = 8;

    /** Respond to an interaction with a popup modal */
    case MODAL = 9;

    /** Deprecated; respond to an interaction with an upgrade button, only available for apps with monetization enabled */
    case PREMIUM_REQUIRED = 10;

    /** Launch the Activity associated with the app. Only available for apps with Activities enabled */
    case LAUNCH_ACTIVITY = 12;
}
