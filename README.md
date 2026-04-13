# E-boutique — Résumé des fonctionnalités

Cette e-boutique demandée est fournie dans `griche_eboutique/` (**Symfony**), thème **jeux vidéo / consoles**.

> Les dossiers `symfony-eboutique/` et `e-boutique/` restent présents dans ce repo mais ne sont pas la version finale demandée.

## Démarrer

- `cd griche_eboutique`
- `composer install`
- Configurer la base (`.env` / `.env.local`), puis :
  - `php bin/console doctrine:migrations:diff`
  - `php bin/console doctrine:migrations:migrate`
  - `php bin/console app:seed --reset` (données de démo)
- Lancer :
  - `symfony serve -d` (ou `php -S 127.0.0.1:8000 -t public`)

## Légende (cotation)

- **OK** : fonctionnalité opérationnelle
- **NOK** : fonctionnalité absente ou non opérationnelle
- **Légers bugs** : fonctionnalité présente mais avec anomalies mineures
- **Syntaxe en place mais non fonctionnelle** : écrans/structure présents, mais logique incomplète (non utilisable)

## Fonctionnalités

> Cotation basée sur l’implémentation dans `griche_eboutique/`.

| Fonctionnalité | Cotation | Notes |
|---|---|---|
| Login (connexion) | OK | Symfony Security (session). |
| Inscription + contrôle de majorité (date de naissance) | OK | Refus si `< 18 ans` (inscription + profil). |
| Parcours par catégorie | OK | Pages dédiées `/consoles` et `/offres` + page jeux `/`. |
| Parcours des articles | OK | Liste + fiche produit `/produit/{slug}`. |
| Mise au panier | OK | Panier en session. |
| Ajustement des quantités au panier + prix total | OK | Maj des quantités + total + transport. |
| Message “commande faite” | OK | Validation `/commande/valider` + flash “Commande effectuée.” |
| Ajout d'un nouveau type d'article proposé (stock non demandé) | OK | Back-office `/admin/types` (ROLE_ADMIN). |
| Ajout d'une nouvelle catégorie | OK | Back-office `/admin/categories` (ROLE_ADMIN). |
| Mise à jour du profil du client connecté | OK | `/compte/profil` (ROLE_USER). |
| Déploiement (AlwaysData) | Syntaxe en place mais non fonctionnelle | Structure Symfony + `public/.htaccess` ok, mais nécessite configuration AlwaysData + DB + `composer install` côté hébergeur. |
| Autres (“etc.”) | OK | Back-office produits `/admin/produits`, page `/nouveautes`, commande sans paiement, transport calculé. |

## Périmètre utilisateur

- Hors **inscription** et **mise à jour du profil**, la gestion des utilisateurs n’est **pas demandée** (bonus possible).
