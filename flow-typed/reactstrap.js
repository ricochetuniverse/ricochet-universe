// @flow strict

declare module 'reactstrap' {
    declare type TagPropType = string;

    declare class Alert
        extends
            React$Component<{|
                children?: React.Node,
                className?: string,
                closeClassName?: string,
                closeAriaLabel?: string,
                // cssModule?: PropTypes.object,
                color?: string,
                fade?: boolean,
                isOpen?: boolean,
                toggle?: () => mixed,
                tag?: TagPropType,
                // transition?: FadePropTypes,
                innerRef?: React.Ref<HTMLElement>,
            |}> {}

    declare class Button
        extends
            React$Component<{
                active?: boolean,
                'aria-label'?: string,
                block?: boolean,
                color?: string,
                disabled?: boolean,
                outline?: boolean,
                tag?: TagPropType,
                innerRef?: React.Ref<HTMLElement>,
                onClick?: () => /* e */ mixed,
                size?: string,
                children?: React.Node,
                className?: string,
                // cssModule?: PropTypes.object,
                close?: boolean,
                ...
            }> {}

    declare class Card
        extends
            React$Component<{
                tag?: TagPropType,
                inverse?: boolean,
                color?: string,
                body?: boolean,
                outline?: boolean,
                className?: string,
                // cssModule?: PropTypes.object,
                innerRef?: React.Ref<HTMLElement>,
                children?: React.Node, // not on PropTypes
                ...
            }> {}

    declare class CardBody
        extends
            React$Component<{
                tag?: TagPropType,
                className?: string,
                // cssModule?: PropTypes.object,
                innerRef?: React.Ref<HTMLElement>,
                children?: React.Node, // not on PropTypes
                ...
            }> {}

    declare class CardHeader
        extends
            React$Component<{
                tag?: TagPropType,
                className?: string,
                // cssModule?: PropTypes.object,
                children?: React.Node, // not on PropTypes
                ...
            }> {}

    declare type StringOrNumberProp = number | string;

    declare type ColumnProps =
        | boolean
        | number
        | string
        | {|
              size?: boolean | number | string,
              order?: StringOrNumberProp,
              offset?: StringOrNumberProp,
          |};

    declare class Col
        extends
            React$Component<{
                tag?: TagPropType,
                xs?: ColumnProps,
                sm?: ColumnProps,
                md?: ColumnProps,
                lg?: ColumnProps,
                xl?: ColumnProps,
                className?: string,
                // cssModule?: PropTypes.object,
                widths?: string[],
                children?: React.Node, // not on PropTypes
                ...
            }> {}

    declare class CustomInput
        extends
            React$Component<{
                className?: string,
                id: string | number,
                type: string,
                label?: React.Node,
                inline?: boolean,
                valid?: boolean,
                invalid?: boolean,
                bsSize?: string,
                htmlFor?: string,
                // cssModule?: PropTypes.object,
                children?: React.Node,
                innerRef?: React.Ref<HTMLElement>,
                ...
            }> {}

    declare class FormGroup
        extends
            React$Component<{|
                children?: React.Node,
                row?: boolean,
                check?: boolean,
                inline?: boolean,
                disabled?: boolean,
                tag?: TagPropType,
                className?: string,
                // cssModule?: PropTypes.object,
                widths?: string[], // not on PropTypes
            |}> {}

    declare type RowColsPropType = number | string;

    declare class Row
        extends
            React$Component<{
                tag?: TagPropType,
                noGutters?: boolean,
                className?: string,
                // cssModule?: PropTypes.object,
                form?: boolean,
                xs?: RowColsPropType,
                sm?: RowColsPropType,
                md?: RowColsPropType,
                lg?: RowColsPropType,
                xl?: RowColsPropType,
                children?: React.Node, // not on PropTypes
                ...
            }> {}

    declare type TargetPropType =
        | string
        | (() => Element)
        | Element
        | React.Node;

    declare class Modal
        extends
            React$Component<{|
                isOpen?: boolean,
                autoFocus?: boolean,
                centered?: boolean,
                scrollable?: boolean,
                size?: string,
                toggle?: () => /* e */ mixed,
                keyboard?: boolean,
                role?: string,
                labelledBy?: string,
                backdrop?: boolean | 'static',
                onEnter?: () => mixed,
                onExit?: () => mixed,
                onOpened?: () => mixed,
                onClosed?: () => mixed,
                children?: React.Node,
                className?: string,
                wrapClassName?: string,
                modalClassName?: string,
                backdropClassName?: string,
                contentClassName?: string,
                external?: React.Node,
                fade?: boolean,
                // cssModule?: PropTypes.object,
                zIndex?: number | string,
                // backdropTransition?: FadePropTypes,
                // modalTransition?: FadePropTypes,
                innerRef?: React.Node,
                unmountOnClose?: boolean,
                returnFocusAfterClose?: boolean,
                container?: TargetPropType,
            |}> {}

    declare class ModalBody
        extends
            React$Component<{
                tag?: TagPropType,
                className?: string,
                // cssModule?: PropTypes.object,
                ...
            }> {}

    declare class ModalFooter
        extends
            React$Component<{
                tag?: TagPropType,
                className?: string,
                // cssModule?: PropTypes.object,
                ...
            }> {}

    declare class ModalHeader
        extends
            React$Component<{
                tag?: TagPropType,
                wrapTag?: TagPropType,
                toggle?: () => mixed,
                className?: string,
                // cssModule?: PropTypes.object,
                children?: React.Node,
                closeAriaLabel?: string,
                charCode?: string | number,
                close?: React.Node, // PropType says PropTypes.object
                ...
            }> {}
}
