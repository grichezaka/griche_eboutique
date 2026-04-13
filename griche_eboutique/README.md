# Pixel Jeux — griche_eboutique (Symfony)

E-boutique Symfony (thème : **jeux vidéo / consoles**).

## URLs

- **Site (production)** : http://griche.alwaysdata.net
- **Code (GitHub)** : (à compléter) https://github.com/<user>/<repo>

## Fonctionnalités (cotation)

- **OK** : fonctionnalité opérationnelle
- **NOK** : fonctionnalité absente / non opérationnelle
- **Légers bugs** : fonctionnalité présente mais avec anomalies mineures
- **Syntaxe en place mais non fonctionnelle** : écrans/structure présents, mais logique incomplète

| Fonctionnalité demandée | Cotation | Détails |
|---|---|---|
| Login (connexion) | OK | Connexion par email/mot de passe, session Symfony. |
| Inscription avec un contrôle de majorité sur la date de naissance | OK | Refus si l’utilisateur a moins de 18 ans. |
| Parcours par catégorie | OK | Pages : Tous les jeux / Consoles / Offres / Nouveautés. |
| Parcours des articles | OK | Liste + fiche produit (détails). |
| Mise au panier | OK | Panier en session. |
| Ajustement des quantités au panier avec le prix total | OK | Quantités modifiables + total + frais de transport. |
| Message de commande faite | OK | Validation de commande + message de confirmation. |
| Ajout d'un nouveau type d'article proposé (stock non demandé) | OK | Back-office : gestion des types. |
| Ajout d'une nouvelle catégorie | OK | Back-office : gestion des catégories. |
| Mise à jour du profil du client connecté | OK | Profil modifiable. |
| Gestion des utilisateurs (hors inscription / mise à jour) | NOK | Non implémentée (non demandée). |
| etc. | OK | Back-office produits + commande sans paiement (pas de tunnel). |

## Lancer en local

```bash
composer install
php bin/console doctrine:migrations:migrate --no-interaction
php bin/console app:seed --reset
symfony serve
```

Ouvre `http://127.0.0.1:8000`.

## Back-office (admin)

1) Crée un compte : `/inscription`  
2) Passe-le admin :

```bash
php bin/console app:make-admin tonmail@example.com
```

3) Va sur : `/admin`

## Déploiement AlwaysData (Apache)

1) Déployer le dossier `griche_eboutique/` sur le serveur.  
2) Configurer le site AlwaysData pour pointer sur : `www/griche_eboutique/public`  
3) Variables d’environnement :
- `APP_ENV=prod`
- `APP_SECRET=...`
- `DATABASE_URL=mysql://USER:PASSWORD@HOST:3306/griche_eboutique?serverVersion=8.0`

4) En SSH (dans `griche_eboutique/`) :

```bash
composer install --no-dev --optimize-autoloader
php bin/console doctrine:migrations:migrate --no-interaction
php bin/console app:seed --reset
```

Le fichier `public/.htaccess` est déjà présent.
