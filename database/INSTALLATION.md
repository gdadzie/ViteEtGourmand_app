# Installation de la base de données

1. Importer le schéma et les données de démonstration de la base `vite_et_gourmand`.
2. Appliquer les migrations dans cet ordre :
   - `001_security.sql`
   - `002_menu_plat.sql`
   - `003_order_lifecycle.sql`
3. Configurer les accès MySQL dans `.env.local` pour le poste local, ou dans les Config Vars pour Heroku.

`003_order_lifecycle.sql` ajoute le statut `annulee`, nécessaire à l’annulation traçable d’une commande par le client avant acceptation.

Pour Heroku, le script `scripts/migrate_order_lifecycle.php` applique cette dernière migration à la base JawsDB :

```bash
heroku run php scripts/migrate_order_lifecycle.php --app vite-et-gourmand-2026
```
