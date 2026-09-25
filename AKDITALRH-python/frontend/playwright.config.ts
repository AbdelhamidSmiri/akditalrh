import { defineConfig, devices } from '@playwright/test';
import fs from 'fs';
import path from 'path';
import { fileURLToPath } from 'url';

const __dirname = path.dirname(fileURLToPath(import.meta.url));
const backendRoot = path.resolve(__dirname, '../backend');
const apiPort = process.env.E2E_API_PORT || '5002';
const webPort = process.env.E2E_WEB_PORT || '5174';
const baseURL = `http://127.0.0.1:${webPort}`;

/** Préfère le venv backend (Flask), sinon python3 système. */
function resolvePython(): string {
  const candidates = [
    path.join(backendRoot, '.venv', 'bin', 'python3'),
    path.join(backendRoot, '.venv', 'bin', 'python'),
    'python3',
  ];
  for (const c of candidates) {
    if (c === 'python3' || fs.existsSync(c)) return c;
  }
  return 'python3';
}

const pythonBin = resolvePython();

export default defineConfig({
  testDir: './e2e',
  fullyParallel: false,
  forbidOnly: !!process.env.CI,
  retries: process.env.CI ? 1 : 0,
  workers: 1,
  timeout: 60_000,
  expect: { timeout: 15_000 },
  reporter: [['list'], ['html', { open: 'never', outputFolder: 'playwright-report' }]],
  use: {
    baseURL,
    trace: 'on-first-retry',
    screenshot: 'only-on-failure',
    video: 'retain-on-failure',
    locale: 'fr-FR',
  },
  projects: [{ name: 'chromium', use: { ...devices['Desktop Chrome'] } }],
  webServer: [
    {
      command: `"${pythonBin}" e2e_server.py`,
      cwd: backendRoot,
      url: `http://127.0.0.1:${apiPort}/api/v1/health`,
      reuseExistingServer: !process.env.CI,
      timeout: 120_000,
      env: {
        ...process.env,
        E2E_API_PORT: apiPort,
        TESTING: '1',
      },
    },
    {
      command: `npx vite --host 127.0.0.1 --port ${webPort}`,
      cwd: __dirname,
      url: baseURL,
      reuseExistingServer: !process.env.CI,
      timeout: 120_000,
      env: {
        ...process.env,
        VITE_API_BASE_URL: `http://127.0.0.1:${apiPort}/api/v1`,
      },
    },
  ],
});
