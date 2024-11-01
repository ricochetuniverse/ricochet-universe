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

declare module 'preact/hooks' {
    declare type MaybeCleanUpFn = void | (() => void);

    declare export function useContext<T>(
        context: React$Context<T>,
        observedBits: void | number | boolean
    ): T;

    declare export function useState<S>(
        initialState: (() => S) | S
    ): [S, (((S) => S) | S) => void];

    declare type Dispatch<A> = (A) => void;

    declare export function useReducer<S, A>(
        reducer: (S, A) => S,
        initialState: S
    ): [S, Dispatch<A>];

    declare export function useReducer<S, A>(
        reducer: (S, A) => S,
        initialState: S,
        init: void
    ): [S, Dispatch<A>];

    declare export function useReducer<S, A, I>(
        reducer: (S, A) => S,
        initialArg: I,
        init: (I) => S
    ): [S, Dispatch<A>];

    declare export function useRef<T>(initialValue: T): {|current: T|};

    declare export function useDebugValue(value: any): void;

    declare export function useEffect(
        create: () => MaybeCleanUpFn,
        inputs: ?$ReadOnlyArray<mixed>
    ): void;

    declare export function useLayoutEffect(
        create: () => MaybeCleanUpFn,
        inputs: ?$ReadOnlyArray<mixed>
    ): void;

    declare export function useCallback<
        T: (...args: $ReadOnlyArray<empty>) => mixed,
    >(
        callback: T,
        inputs: ?$ReadOnlyArray<mixed>
    ): T;

    declare export function useMemo<T>(
        create: () => T,
        inputs: ?$ReadOnlyArray<mixed>
    ): T;

    declare export function useImperativeHandle<T>(
        ref:
            | {current: T | null, ...}
            | ((inst: T | null) => mixed)
            | null
            | void,
        create: () => T,
        inputs: ?$ReadOnlyArray<mixed>
    ): void;
}
