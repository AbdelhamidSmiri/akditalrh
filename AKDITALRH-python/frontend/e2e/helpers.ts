import { expect, type Page } from '@playwright/test';

export const USERS = {
  admin: { username: 'admin@e2e.local', password: 'Admin123!' },
  agent: { username: 'agent@e2e.local', password: 'Agent123!' },
  agence: { username: 'agence@e2e.local', password: 'Agence123!' },
} as const;

export async function login(
  page: Page,
  user: keyof typeof USERS = 'admin',
) {
  const { username, password } = USERS[user];
  await page.goto('/login');
  await page.locator('#username').fill(username);
  await page.locator('#password').fill(password);
  await page.getByRole('button', { name: /Se connecter/i }).click();
  if (user === 'agence') {
    await expect(page).toHaveURL(/\/agence\/vols/, { timeout: 20_000 });
  } else {
    await expect(page).toHaveURL(/\/dashboard/, { timeout: 20_000 });
  }
}

export async function expectTitle(page: Page, title: string | RegExp) {
  await expect(page.locator('.title-page').first()).toContainText(title);
}

export async function openCreateModal(page: Page, buttonName: RegExp | string) {
  await page.getByRole('button', { name: buttonName }).first().click();
}
