// @flow strict

declare module 'preact' {
    declare export const h: React$CreateElement;
    declare export const Component: typeof React.Component;
    declare export const Fragment: typeof React.Fragment;

    declare export function render<ElementType: React$ElementType>(
        element: React$Element<ElementType>,
        container: Element,
        callback?: () => void
    ): React$ElementRef<ElementType>;

    declare export const createRef: typeof React.createRef;
}

declare module 'preact/compat' {
    declare export const forwardRef: typeof React.forwardRef;
}

declare module 'preact/hooks' {
    declare export const useState: typeof React.useState;
    declare export const useReducer: typeof React.useReducer;
    declare export const useEffect: typeof React.useEffect;
    declare export const useLayoutEffect: typeof React.useLayoutEffect;
    declare export const useRef: typeof React.useRef;
    declare export const useImperativeHandle: typeof React.useImperativeHandle;
    declare export const useMemo: typeof React.useMemo;
    declare export const useCallback: typeof React.useCallback;
    declare export const useContext: typeof React.useContext;
    declare export const useDebugValue: typeof React.useDebugValue;
    declare export function useErrorBoundary(
        callback?: (error: any, errorInfo: ErrorInfo) => Promise<void> | void
    ): [any, () => void];
    declare export const useId: typeof React.useId;
}

declare class ErrorInfo {
    componentStack?: string;
}
