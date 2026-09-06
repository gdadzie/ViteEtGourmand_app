# Installation du projet en local

## Prérequis

Avant de lancer le projet, assurez-vous d’avoir installé sur votre machine :

* PHP (version 8 recommandée)
* Composer
* MySQL + phpMyAdmin (ou autre gestionnaire de base de données)
* MongoDB
* Un serveur local (XAMPP, Laragon, WAMP, ou serveur PHP intégré)

Un éditeur comme **PHPStorm** ou **VSCode** est conseillé pour travailler plus confortablement.

---

## Récupération du projet

Clonez le dépôt depuis GitHub :

```bash
git clone https://github.com/gdadzie/vite-et-gourmand
```

Puis placez-vous dans le dossier :

```bash
cd vite-et-gourmand
```

---

## Installation des dépendances

Si le projet utilise Composer :

```bash
composer install
```

Cela installera automatiquement les bibliothèques nécessaires.

---

## Configuration de l’environnement

Créer un fichier `.env` à la racine du projet (ou copier `.env.example` si présent).

Configurer ensuite :

* les informations de connexion MySQL
* les informations MongoDB
* l’URL du projet en local

Exemple :

```
DB_HOST=localhost
DB_NAME=vite-et-gourmand
DB_USER=root
DB_PASS=

MONGO_URI=mongodb://localhost:27017
```

---

## Création de la base de données

1. Ouvrir phpMyAdmin
2. Créer une base de données
3. Importer le fichier SQL présent dans le dossier `/database` ou `/sql`

---

## Lancer le projet

Deux possibilités :

### Serveur PHP intégré

```bash
php -S localhost:8000 -t public
```

Puis ouvrir :

```
http://localhost:8000
```

### Ou via votre serveur local (XAMPP / Laragon…)

Placez le projet dans le dossier `htdocs` ou `www` et accédez-y via votre navigateur.

---

## Comptes de test



* Admin : [admin@vitegourmand.test](mailto:admin@vite-et-gourmand.fr) / admin123
* Employé : [employe@vitegourmand.test](mailto:employe@vite-et-gourmand.fr) / employe123
* Client : [client@vitegourmand.test](mailto:utilisateur@test.fr) / utilisateur123

---

## Remarque

Si un problème survient au démarrage, vérifier :

* que PHP est bien dans le PATH
* que MySQL est lancé
* que MongoDB est actif
* que le fichier `.env` est correctement configuré

---

Ce projet a été réalisé dans le cadre du TP Développeur Web & Web Mobile.

https://vite-et-gourmand-2026-5c40281b04d6.herokuapp.com/index.php?page=home
username: gdadzie

* Utilisateur : qxrf1n0vk8zidlny
* Mot de passe : h92q60tuw0eweci1
* Serveur     : i943okdfa47xqzpy.cbetxkdyhwsb.us-east-1.rds.amazonaws.com
* Port        : 3306
* Base        : s7q9nxx2ltnznznc