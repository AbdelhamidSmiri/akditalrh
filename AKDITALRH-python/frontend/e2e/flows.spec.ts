import path from 'path';
import { fileURLToPath } from 'url';
import { test, expect } from '@playwright/test';
import { login, expectTitle } from './helpers';

const __dirname = path.dirname(fileURLToPath(import.meta.url));
const UPLOAD_IMAGE = path.resolve(
  __dirname,
  '../../e88a0fed-e9a1-4831-aa47-04d1943045fe.jpg',
);

test.describe('CRUD admin référentiels', () => {
  test.beforeEach(async ({ page }) => {
    await login(page, 'admin');
  });

  test('créer une ville', async ({ page }) => {
    await page.goto('/admin/villes');
    await expectTitle(page, /villes/i);
    await page.getByRole('button', { name: 'Ajouter' }).click();
    await page.locator('#ville').fill('TANGER-E2E');
    await page.getByRole('button', { name: 'Enregistrer' }).click();
    await expect(page.getByText('TANGER-E2E')).toBeVisible({ timeout: 10_000 });
  });

  test('créer un hôtel', async ({ page }) => {
    await page.goto('/hotels');
    await page.getByRole('button', { name: 'Ajouter' }).click();
    await page.locator('#nom').fill('Hotel Playwright');
    await page.locator('select#ville').selectOption({ index: 1 });
    await page.getByRole('button', { name: /Créer/i }).click();
    await expect(page).toHaveURL(/\/hotels\/\d+/, { timeout: 15_000 });
    await expect(page.locator('.title-page')).toContainText(/Hotel Playwright|Hôtel/);
  });

  test('créer un appartement', async ({ page }) => {
    await page.goto('/logement/appartements');
    await page.getByRole('button', { name: 'Ajouter' }).click();
    await page.locator('#désignation, #designation').first().fill('Résidence E2E');
    const villeSelect = page.locator('form#appart-form select').first();
    if (await villeSelect.count()) {
      await villeSelect.selectOption({ index: 1 });
    }
    const capacite = page.locator('#capacité, #capacite');
    if (await capacite.count()) await capacite.first().fill('3');
    await page.getByRole('button', { name: 'Enregistrer' }).click();
    await expect(page.getByText('Résidence E2E')).toBeVisible({ timeout: 10_000 });
  });

  test('créer une voiture', async ({ page }) => {
    await page.goto('/parc/voitures');
    await page.getByRole('button', { name: 'Ajouter' }).click();
    await page.locator('#immatriculation').fill('99999-E2E-1');
    await page.locator('#marque').fill('RENAULT');
    await page.locator('#modèle, #modele').first().fill('Clio');
    await page.getByRole('button', { name: 'Enregistrer' }).click();
    await expect(page.getByText('99999-E2E-1')).toBeVisible({ timeout: 10_000 });
  });

  test('créer rôle', async ({ page }) => {
    await page.goto('/admin/roles');
    await page.getByRole('button', { name: 'Ajouter' }).click();
    await page.locator('#nom-du-rôle, #nom-du-role').first().fill('RoleE2E');
    const plafond = page.locator('#plafond-hôtel, #plafond-hotel');
    if (await plafond.count()) await plafond.first().fill('500');
    await page.getByRole('button', { name: 'Enregistrer' }).click();
    await expect(page.getByText('RoleE2E')).toBeVisible({ timeout: 10_000 });
  });
});

test.describe('Flux métier', () => {
  test('demande vol via formulaire aligné API', async ({ page }) => {
    await login(page, 'admin');
    await page.goto('/vols/new');
    await expectTitle(page, /Demande de billet/);
    await page.locator('#départ, #depart').first().fill('Casablanca');
    await page.locator('#destination').fill('Paris');
    await page.locator('#date-aller').fill('2026-11-15');
    await page.getByRole('button', { name: /Envoyer|Enregistrer/i }).click();
    await expect(page).toHaveURL(/\/vols\/\d+/, { timeout: 15_000 });
  });

  test('upload image sur form réservation (étape 1)', async ({ page }) => {
    await login(page, 'admin');
    await page.goto('/reservations/new');
    await expectTitle(page, /Demande d.hôtel/);
    const fileInput = page.locator('input[type="file"]');
    if (await fileInput.count()) {
      await fileInput.setInputFiles(UPLOAD_IMAGE);
      await expect(page.getByText(/e88a0fed/i)).toBeVisible({ timeout: 5_000 });
    }
  });
});

test.describe('Permissions', () => {
  test('agence ne voit pas admin', async ({ page }) => {
    await login(page, 'agence');
    await page.goto('/admin/villes');
    await expect(page).not.toHaveURL(/\/admin\/villes/);
  });
});
