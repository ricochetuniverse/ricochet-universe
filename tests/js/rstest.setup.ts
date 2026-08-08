import {afterEach} from '@rstest/core';
import {cleanup as cleanupPreact} from '@testing-library/preact';

afterEach(() => {
    cleanupPreact();
});
