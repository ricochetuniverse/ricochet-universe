// @flow strict

declare module 'preact' {
    declare export var h: React$CreateElement;
    declare export var Component: typeof React$Component;
    declare export var Fragment: ({children?: React$Node, ...}) => React$Node;

    declare export function render<ElementType: React$ElementType>(
        element: React$Element<ElementType>,
        container: Element,
        callback?: () => void
    ): React$ElementRef<ElementType>;

    declare export function createRef<T>(): {|current: null | T|};
}

declare module 'preact/compat' {
    declare export function forwardRef<Config, Instance>(
        render: (
            props: Config,
            ref: {current: null | Instance, ...} | ((null | Instance) => mixed)
        ) => React$Node
    ): React$AbstractComponent<Config, Instance>;
}
