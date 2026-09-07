# Guide de démonstration — Vite & Gourmand

## Objectif

Vite & Gourmand est une application web de gestion d’un service traiteur. Elle permet aux clients de consulter et commander des menus, et à l’équipe de gérer les commandes, les menus, les horaires, les avis et les comptes.

## Comptes et rôles

- **Client** : consultation des menus, commande, suivi, modification ou annulation avant acceptation, avis après commande terminée.
- **Employé** : suivi des commandes, progression des statuts, gestion des menus, horaires et avis.
- **Administrateur** : gestion des employés, activation/désactivation des comptes, statistiques MongoDB et accès à l’ensemble des fonctionnalités de gestion.

## Parcours de démonstration conseillé

1. Ouvrir l’accueil, vérifier les horaires, les avis validés, le contact et les liens légaux.
2. Ouvrir « Menus », utiliser les filtres par prix, thème, régime et nombre de personnes.
3. Consulter le détail d’un menu et sa composition en plats.
4. Se connecter comme client, commander un menu puis consulter « Mes commandes ».
5. Tant que le statut est « Reçue », montrer les boutons **Modifier** et **Annuler**.
6. Se connecter comme employé et faire évoluer une commande : reçue → acceptée → payée → en préparation → livrée → attente retour → terminée.
7. Revenir côté client : ouvrir l’historique, puis déposer un avis sur la commande terminée.
8. Se connecter comme administrateur : désactiver/réactiver un compte et ouvrir la page **Statistiques** pour filtrer les graphiques MongoDB.

## Déploiement

L’application est déployée sur Heroku. Les variables de configuration sont définies dans Heroku Config Vars ; elles ne sont pas stockées dans Git.

## Sécurité mise en œuvre

- mots de passe hachés ;
- contrôle des rôles et de la propriété des commandes ;
- jeton CSRF sur les formulaires POST ;
- comptes désactivés refusés à la connexion ;
- variables sensibles exclues du dépôt (`.env`) ;
- règles métier revalidées côté serveur.
