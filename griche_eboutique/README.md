# griche_eboutique (Symfony)

E-boutique **Symfony** thème **jeux vidéo / consoles** :

- Catalogue (Jeux / Consoles / Offres) + page Nouveautés
- Panier en **session**
- Commande sans paiement (pas de tunnel d’achat)
- Connexion / Inscription (avec contrôle majorité 18+) / Profil
- Back-office (ROLE_ADMIN) : catégories, types, produits (pas de stock)

## Lancer en local

```bash
cd griche_eboutique
composer install
php bin/console doctrine:migrations:diff
php bin/console doctrine:migrations:migrate
php bin/console app:seed --reset
symfony serve
```

Ouvre `http://127.0.0.1:8000`.

## Admin (back-office)

1. Crée un compte via `/inscription`
2. Promeut-le admin :

```bash
php bin/console app:make-admin tonmail@example.com
```

3. Va sur `/admin`

## Déploiement AlwaysData (Apache)

1. Déployer le code (Git/FTP).
2. Mettre le **répertoire racine** sur `www/griche_eboutique/public`.
3. Variables d’environnement :
   - `APP_ENV=prod`
   - `APP_SECRET=...`
   - `DATABASE_URL=mysql://USER:PASSWORD@HOST:3306/griche_eboutique?serverVersion=8.0`
4. En SSH (dans `griche_eboutique/`) :

```bash
composer install --no-dev --optimize-autoloader
php bin/console doctrine:migrations:migrate --no-interaction
php bin/console app:seed --reset
```

Le fichier `public/.htaccess` est présent (rewrite Symfony).

