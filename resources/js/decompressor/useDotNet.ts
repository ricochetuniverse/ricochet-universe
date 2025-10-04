// Based from https://github.com/nbelyh/article-demo-dotnet-react-app/blob/main/src/useDotNet.ts

import type {RuntimeAPI} from '@ricochetuniverse/nuvelocity-unpacker/dotnet/_framework/dotnet';
import {useCallback, useRef, useState} from 'preact/hooks';

async function loadAssembly(loaderUrl: string) {
    const module: typeof import('@ricochetuniverse/nuvelocity-unpacker/dotnet/_framework/dotnet') =
        await import(/* webpackIgnore: true */ loaderUrl);

    const {getAssemblyExports, getConfig} = await module.dotnet
        .withDiagnosticTracing(process.env.NODE_ENV === 'development')
        .create();

    const {mainAssemblyName} = getConfig();
    if (!mainAssemblyName) {
        throw new Error('Missing main assembly name');
    }

    return await getAssemblyExports(mainAssemblyName);
}

export default function useDotNet(loaderUrl: string) {
    // this is actually `any` :p
    const [dotNet, setDotNet] = useState<Awaited<
        ReturnType<RuntimeAPI['getAssemblyExports']>
    > | null>(null);

    const [loading, setLoading] = useState(true);
    const startedLoadingRef = useRef(false);

    const getDotNet = useCallback(async () => {
        if (dotNet) {
            return dotNet;
        }

        if (startedLoadingRef.current) {
            // If dotnet did finish loading, then we wouldn't be here
            return;
        }
        startedLoadingRef.current = true;

        try {
            const exports = await loadAssembly(loaderUrl);
            setDotNet(exports);
        } finally {
            setLoading(false);
        }

        return dotNet;
    }, [dotNet, loaderUrl]);

    return {
        getDotNet,
        loading,
    };
}
