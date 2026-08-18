import type {AppExports} from './DotNetType';

export default async function loadAssembly(
    loaderUrl: string,
    diagnosticTracing: boolean
) {
    const module = (await import(
        /* webpackIgnore: true */ loaderUrl
    )) as typeof import('@ricochetuniverse/nuvelocity-unpacker/dotnet/_framework/dotnet');

    // eslint-disable-next-line @typescript-eslint/unbound-method
    const {getAssemblyExports, getConfig} = await module.dotnet
        .withDiagnosticTracing(diagnosticTracing)
        .create();

    const {mainAssemblyName} = getConfig();
    if (!mainAssemblyName) {
        throw new Error('Missing main assembly name');
    }

    const exports = (await getAssemblyExports(
        mainAssemblyName
    )) as AppExports | null;
    if (!exports) {
        throw new Error('Missing assembly exports');
    }

    return {exports};
}
