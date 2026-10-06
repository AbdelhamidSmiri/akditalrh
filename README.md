# MGR — Gestion des moyens généraux Akdital

Application web interne (PHP / MySQL) : hébergement, réservations d'hôtels, billets d'avion, logements et bénéficiaires.

## Installation

1. `cd app && composer install --no-dev --optimize-autoloader`
2. Copier `.env.example` vers `.env` et renseigner les valeurs (secrets, SMTP, `APP_DEBUG=0`, `FORCE_HTTPS=1` en HTTPS).
3. Créer `app/Config/database.php` (non versionné) avec les accès MySQL.
4. Rendre `app/tmp` et `app/storage` accessibles en écriture par le serveur web.
5. Pointer le `DocumentRoot` Apache sur `app/webroot` (`AllowOverride All`, modules `rewrite` et `headers`).
