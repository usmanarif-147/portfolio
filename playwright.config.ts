import { defineConfig } from '@playwright/test';

export default defineConfig({
    testDir: './tests/e2e',
    timeout: 120_000,
    use: {
        baseURL: 'http://localhost:8021',
        screenshot: 'only-on-failure',
        trace: 'retain-on-failure',
    },
    reporter: [['list'], ['html', { outputFolder: 'playwright-report', open: 'never' }]],
});
