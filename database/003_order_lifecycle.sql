-- Cycle de vie des commandes : annulation client avant acceptation.
-- À appliquer une seule fois sur MySQL / JawsDB.
ALTER TABLE commandes
    MODIFY statut ENUM(
        'recue',
        'acceptee',
        'payee',
        'en_preparation',
        'en_livraison',
        'livree',
        'attente_retour',
        'terminee',
        'annulee'
    ) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT 'recue';

-- Valeur de paiement cohérente avec le schéma existant.
ALTER TABLE commandes
    MODIFY statut_paiement ENUM('en attente', 'payé') NOT NULL DEFAULT 'en attente';
