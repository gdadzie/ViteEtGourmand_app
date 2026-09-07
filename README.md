# Vite & Gourmand

Application web de gestion de menus et de commandes pour un traiteur. Réalisée dans le cadre du titre professionnel Développeur Web et Web Mobile.

## Fonctionnalités

- Consultation et filtrage dynamique des menus.
- Création de comptes, connexion, réinitialisation de mot de passe et gestion des comptes actifs.
- Commande de menus, suivi des statuts et notifications par e-mail.
- Espaces client, employé et administrateur.
- Gestion des menus, plats, horaires, avis et employés.
- Tableau de bord administrateur : statistiques de commandes et chiffre d'affaires par menu via MongoDB.

## Stack technique

- PHP 8.1+, PDO et Composer.
- MySQL pour les données métier.
- MongoDB pour la projection analytique des commandes.
- Bootstrap 5, HTML, CSS et JavaScript.
- Déploiement Heroku.

## Installation locale

1. Cloner le dépôt puis installer les dépendances :

   ```bash
   git clone https://github.com/gdadzie/ViteEtGourmand_app.git
   cd ViteEtGourmand_app
   composer install
   ```

2. Créer une base MySQL puis importer les scripts du dossier `database/`.

3. Créer un fichier `.env.local` à la racine, jamais versionné :

   ```dotenv
   DB_HOST=localhost
   DB_NAME=vite_et_gourmand
   DB_USER=root
   DB_PASS=

   MONGODB_URI=mongodb://localhost:27017
   MONGODB_DATABASE=vite_et_gourmand

   APP_URL=http://localhost
   ```

4. Démarrer Apache/WAMP et ouvrir `http://localhost/index.php?page=home`.

## MongoDB et statistiques

MySQL reste la source de vérité. Lors de l'ouverture du tableau de bord administrateur et après une mise à jour de statut, les commandes sont synchronisées vers la collection MongoDB `commande_analytics`. Cette collection sert uniquement aux comparaisons par menu et au calcul du chiffre d'affaires filtré par menu ou période.

## Déploiement Heroku

Configurer les variables d'environnement de production dans **Settings → Config Vars** : paramètres MySQL, `MONGODB_URI`, `MONGODB_DATABASE`, paramètres SMTP et `APP_URL`.

Déployer la branche principale :

```bash
git push heroku main
```

## Sécurité

- Les secrets sont conservés uniquement dans les variables d'environnement.
- Les mots de passe sont hachés.
- Les formulaires protégés utilisent un jeton CSRF.
- Les accès administrateur et employé sont contrôlés côté serveur.
- Un compte désactivé ne peut pas se connecter.

## Livrables ECF à remettre

- Dépôt GitHub public et application déployée.
- Manuel d'utilisation PDF, avec comptes de démonstration créés pour le jury.
- Charte graphique PDF et six maquettes (trois bureau, trois mobile).
- Documentation de gestion de projet.
- Documentation technique : choix techniques, environnement, MCD/UML, cas d'utilisation, séquence et déploiement.

> Ne jamais placer de mot de passe, URL de base de données ou clé SMTP dans ce dépôt.
