// flow-typed signature: c764f73e95196cfd504f1d76060f8f19
// flow-typed version: 6912183195/react-loadable_v5.x.x/flow_>=v0.201.x

declare type $Await<T, P: Promise<T>> = T;

declare module 'react-loadable' {
  declare type LoadingProps = {
    isLoading: boolean,
    pastDelay: boolean,
    timedOut: boolean,
    retry: () => void,
    error: ?Error,
    ...
  };

  declare type CommonOptions = {
    loading: React.ComponentType<LoadingProps>,
    delay?: number,
    timeout?: number,
    modules?: Array<string>,
    webpack?: () => Array<number>,
    ...
  };

  declare type OptionsWithoutRender<TProps> = {
    ...CommonOptions,
    loader(): Promise<React.ComponentType<TProps> | { +default: React.ComponentType<TProps>, ... }>,
    ...
  };

  declare type OptionsWithRender<TProps, TModule> = {
    ...CommonOptions,
    loader(): Promise<TModule>,
    render(loaded: TModule, props: TProps): React$Node,
    ...
  };

  declare type Options<TProps, TModule> = OptionsWithoutRender<TProps> | OptionsWithRender<TProps, TModule>;

  declare type MapOptions<TProps, TModules: { +[key: string]: any, ... }> = {
    ...CommonOptions,
    loader: { [key: $Keys<TModules>]: () => Promise<any>, ... },
    render(loaded: TModules, props: TProps): React$Node,
    ...
  };

  declare class LoadableComponent<TProps> extends React.Component<TProps> {
    static preload(): Promise<void>
  }

  declare type CaptureProps = { report(moduleName: string): void, ... };

  /**
  * A type level function like
  * <T>({ [string]: () => Promise<T> }) -> ({ [string]: T })
  * It would be helpful to apply type arguments to Loadable.Map<> like below.
  *
  * Loadable.Map<Props, typeof { A: () => import("a") }>({...});
  */
  // declare type MapModules<L> = $ObjMap<L, <P>(P) => $Await<any, $Call<P>>>;

  declare module.exports: {
    <TProps, TModule>(opts: Options<TProps, TModule>): Class<LoadableComponent<TProps>>,
    Map<TProps, TModules>(opts: MapOptions<TProps, TModules>): Class<LoadableComponent<TProps>>,
    Capture: React.ComponentType<CaptureProps>,
    preloadAll(): Promise<void>,
    preloadReady(): Promise<void>,
    ...
  };
}
