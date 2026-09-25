import { test, expect } from '@playwright/test';
import { login, USERS } from './helpers';

test.describe('Auth', () => {
  test('page login visible', async ({ page }) => {
    await page.goto('/login');
    await expect(page.getByRole('heading', { name: /Connexion/i })).toBeVisible();
    await expect(page.locator('#username')).toBeVisible();
  });

  test('login admin OK → dashboard', async ({ page }) => {
    await login(page, 'admin');
    await expect(page.locator('.title-page')).toContainText('Dashboard');
  });

  test('login invalide', async ({ page }) => {
    await page.goto('/login');
    await page.locator('#username').fill(USERS.admin.username);
    await page.locator('#password').fill('WrongPass99!');
    await page.getByRole('button', { name: /Se connecter/i }).click();
    await expect(page.locator('[role="alert"]')).toBeVisible({ timeout: 10_000 });
    await expect(page).toHaveURL(/\/login/);
  });

  test('forgot password page', async ({ page }) => {
    await page.goto('/forgot-password');
    await expect(page.getByText(/Mot de passe oublié/i).first()).toBeVisible();
  });

  test('login agence → espace vols', async ({ page }) => {
    await login(page, 'agence');
    await expect(page.locator('.title-page')).toContainText(/Demandes/i);
  });

  test('route protégée redirige vers login', async ({ page }) => {
    await page.goto('/dashboard');
    await expect(page).toHaveURL(/\/login/);
  });
});
