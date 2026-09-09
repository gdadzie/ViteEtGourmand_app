# Architecture Vite et Gourmand

L'application suit une architecture MVC progressive. Le point d'entrée est `public/index.php` : il reçoit la route demandée, instancie les dépendances, puis délègue au contrôleur concerné.

## Organisation active

| Dossier | Rôle |
| --- | --- |
| `public/` | Point d'entrée, ressources CSS, JavaScript et images publiques. |
| `src/Controller/` | Gestion des requêtes HTTP, droits d'accès et redirections. |
| `src/Entity/` | Objets métier : utilisateurs, commandes, menus, plats, avis, villes et horaires. |
| `src/Repository/` | Accès MySQL et MongoDB. Les requêtes SQL y sont centralisées. |
| `src/Service/` | Règles métier : authentification, e-mails, menus, plats et avis. |
| `src/View/` | Pages HTML/PHP organisées par domaine fonctionnel. |
| `src/Core/` | Fonctions transverses : sécurité, session, protection CSRF et routage. |
| `Config/` | Connexion à la base de données et configuration. |

## Parcours d'une page

1. Le navigateur appelle une URL, par exemple `index.php?page=liste_des_plats`.
2. `public/index.php` sélectionne la route associée.
3. Le contrôleur vérifie la session et le rôle de l'utilisateur.
4. Le service applique les règles métier si nécessaire.
5. Le repository lit ou écrit les données dans MySQL ou MongoDB.
6. La vue affiche les informations avec les styles et scripts présents dans `public/assets/`.

## Vues actives par domaine

- `View/Admin/` : espace administrateur et statistiques.
- `View/Employes/` : espace employé.
- `View/Client/` et `View/Utilisateurs/` : espace client, profil et listes utilisateurs.
- `View/Menus/` et `View/Plats/` : catalogue et gestion des menus/plats.
- `View/Commandes/` : création, suivi, modification et gestion des commandes.
- `View/Contact/` et `View/Avis/` : messagerie et avis.
- `View/home/` : pages publiques, mentions légales et conditions générales.
- `View/Layout/` et `View/partials/` : gabarit commun, navigation et pied de page.

## Règle de maintenance

Pour toute nouvelle fonctionnalité, créer ou compléter un contrôleur, un repository ou service si besoin, puis une vue dans le dossier fonctionnel correspondant. Les anciens fichiers conservés hors de cette organisation restent temporairement en place uniquement pour préserver les routes déjà testées ; leur migration doit être faite après ajout de tests de non-régression.
