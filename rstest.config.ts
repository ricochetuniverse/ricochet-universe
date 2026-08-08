import {pluginPreact} from '@rsbuild/plugin-preact';
import {pluginSass} from '@rsbuild/plugin-sass';
// import {withRspackConfig} from '@rstest/adapter-rspack';
import {defineConfig} from '@rstest/core';

export default defineConfig({
    // extends: async (user) => {
    //     const config = await withRspackConfig()(user);
    //     delete config.tools;
    //     delete config.plugins;
    //     return config;
    // },
    plugins: [pluginPreact(), pluginSass()],
    setupFiles: ['tests/js/rstest.setup.ts'],
    testEnvironment: 'jsdom', // happy-dom is not fully supported
});
