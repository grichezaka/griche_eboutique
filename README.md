# Projet d’e-boutique réalisé avec **Symfony** 

Le code du projet se trouve dans `griche_eboutique/`.

## Cotation (OK / NOK / Légers bugs / Syntaxe en place mais non fonctionnelle)

- **OK** : fonctionnalité opérationnelle
- **NOK** : fonctionnalité absente / non opérationnelle
- **Légers bugs** : fonctionnalité présente mais avec anomalies mineures
- **Syntaxe en place mais non fonctionnelle** : écrans/structure présents, mais logique incomplète

## Fonctionnalités (résumé)

| Fonctionnalité demandée | Cotation | Détails |

| Login (connexion) | OK | Connexion par email/mot de passe, session Symfony. |
| Inscription avec un contrôle de majorité sur la date de naissance | OK | Refus si l’utilisateur a moins de 18 ans. |
| Parcours par catégorie | OK | Pages : Tous les jeux / Consoles / Offres / Nouveautés. |
| Parcours des articles | OK | Liste + fiche produit (détails). |
| Mise au panier | OK | Panier en session. |
| Ajustement des quantités au panier avec le prix total | OK | Quantités modifiables + total + frais de transport. |
| Message de commande faite | OK | Validation de commande + message de confirmation. |
| Ajout d'un nouveau type d'article proposé (stock non demandé) | OK | Back-office : gestion des types. |
| Ajout d'une nouvelle catégorie | OK | Back-office : gestion des catégories. |
| Mise à jour du profil du client connecté | OK | Profil modifiable (adresse, infos, etc.). |
| Gestion des utilisateurs (hors inscription / mise à jour) | OK | Non implémentée (non demandée). |
| etc. | OK | Back-office produits + commande sans paiement (pas de tunnel). |

## griche_eboutique

`http://127.0.0.1:8000`.
