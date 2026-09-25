import { test, expect } from '@playwright/test';
import { login, expectTitle } from './helpers';

const ADMIN_PAGES: { path: string; title: string | RegExp }[] = [
  { path: '/dashboard', title: 'Dashboard' },
  { path: '/reservations', title: /Réservations/ },
  { path: '/reservations/new', title: /Demande d.hôtel/ },
  { path: '/vols', title: /billets d.avion/i },
  { path: '/vols/new', title: /Demande de billet/ },
  { path: '/hotels', title: /hôtels/i },
  { path: '/hotels/1', title: /Hotel E2E|Hôtel/ },
  { path: '/logement/appartements', title: /Disponibilités/ },
  { path: '/logement/beneficiaires', title: /Historique logements/ },
  { path: '/parc/voitures', title: /véhicules/i },
  { path: '/parc/calendrier', title: /Calendrier/ },
  { path: '/parc/demandes', title: /Demandes véhicules/ },
  { path: '/parc/referentiels', title: /Référentiel/ },
  { path: '/parc/requetes', title: /Requêtes/ },
  { path: '/carburant', title: /carburant/i },
  { path: '/jawaz', title: /Jawaz/i },
  { path: '/admin/users', title: /utilisateurs/i },
  { path: '/admin/users/new', title: /Nouvel utilisateur/ },
  { path: '/admin/roles', title: /rôles/i },
  { path: '/admin/sites', title: /sites/i },
  { path: '/admin/villes', title: /villes/i },
];

test.describe('Smoke pages Admin', () => {
  test.beforeEach(async ({ page }) => {
    await login(page, 'admin');
  });

  for (const p of ADMIN_PAGES) {
    test(`charge ${p.path}`, async ({ page }) => {
      const errors: string[] = [];
      page.on('pageerror', (err) => errors.push(err.message));
      await page.goto(p.path);
      await expectTitle(page, p.title);
      expect(errors, `JS errors on ${p.path}: ${errors.join('; ')}`).toEqual([]);
    });
  }
});

test.describe('Smoke pages Agence', () => {
  test('espace agence vols', async ({ page }) => {
    await login(page, 'agence');
    await page.goto('/agence/vols');
    await expectTitle(page, /Demandes/);
  });
});

test.describe('Smoke pages Agent', () => {
  test('dashboard + mes vols', async ({ page }) => {
    await login(page, 'agent');
    await page.goto('/dashboard');
    await expectTitle(page, 'Dashboard');
    await page.goto('/vols');
    await expectTitle(page, /billets/i);
  });

  test('admin users interdit → dashboard', async ({ page }) => {
    await login(page, 'agent');
    await page.goto('/admin/users');
    await expect(page).toHaveURL(/\/dashboard/);
  });
});
