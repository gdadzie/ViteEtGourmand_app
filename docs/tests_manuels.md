# Plan de tests manuels ECF

| Parcours | Action | Résultat attendu |
| --- | --- | --- |
| Menus | Utiliser les filtres | Les cartes visibles correspondent aux critères choisis, sans rechargement. |
| Commande client | Commander un menu | Prix, réduction et frais de livraison sont recalculés côté serveur ; e-mail de confirmation envoyé si la configuration SMTP est disponible. |
| Modification client | Modifier une commande `recue` | Les données et le prix sont mis à jour ; une trace est ajoutée à l’historique. |
| Annulation client | Annuler une commande `recue` | Le statut devient `annulee` ; la commande reste consultable dans l’historique. |
| Restriction client | Modifier ou annuler une commande acceptée | L’action est refusée avec un message clair. |
| Suivi équipe | Faire progresser les statuts | Seule l’étape suivante est disponible ; chaque changement est historisé. |
| Avis | Déposer un avis | Possible uniquement pour le propriétaire d’une commande `terminee`, une seule fois. |
| Avis équipe | Valider un avis | L’avis validé apparaît sur l’accueil. |
| Employé | Ouvrir les cartes de l’espace employé | Chaque carte mène à une route autorisée et fonctionnelle. |
| Admin | Désactiver un compte | Le compte ne peut plus se connecter ; une réactivation restaure l’accès. |
| Statistiques | Filtrer par menu et période | Les indicateurs, graphique et tableau sont alimentés par MongoDB. |
| Responsive | Tester mobile/tablette | Les formulaires restent lisibles ; les tableaux défilent dans leur conteneur sans casser la page. |
