<?php

namespace Service;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class MailService
{
    // =========================================================
    // EMAIL CRÉATION DE COMPTE
    // =========================================================
    public function envoyerMailCreationCompte(
        string $email,
        string $nomComplet,
        string $type = 'employe'
    ): bool {

        try {

            // ======================
            // CONFIG SMTP
            // ======================
            $mail = new PHPMailer(true);

            $mail->CharSet = 'UTF-8';
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'egsdigitalagency@gmail.com';
            $mail->Password   = $_ENV['SMTP_PASSWORD'];
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 587;

            // ======================
            // EXPÉDITEUR
            // ======================
            $mail->setFrom(
                'egsdigitalagency@gmail.com',
                'Vite & Gourmand'
            );

            // ======================
            // DESTINATAIRE
            // ======================
            $mail->addAddress(
                $email,
                $nomComplet
            );

            // ======================
            // FORMAT
            // ======================
            $mail->isHTML(true);

            // ======================
            // SUJET
            // ======================
            if ($type === 'employe') {
                $mail->Subject =
                    'Création de votre compte employé Vite & Gourmand';
            } else {
                $mail->Subject =
                    'Bienvenue sur Vite & Gourmand';
            }

            // ======================
            // CONTENU
            // ======================
            $mail->Body = "
                <h2 style='color: #8c5a20'>
                    Bonjour {$nomComplet}
                </h2>

                <p>
                    Félicitation, votre compte a été créé avec succès
                    sur <strong>Vite & Gourmand</strong>.
                </p>

                <p>
                    Pour des raisons de sécurité, votre mot de passe
                    ne vous est pas communiqué par email.
                </p>

                <p>
                    Merci de contacter l'administrateur si nécessaire.
                </p>

                <p>
                    <a href='https://vite-et-gourmand-2026-5c40281b04d6.herokuapp.com/?page=connexion'>
                        Se connecter
                    </a>
                </p>

                <br>

                <p>
                    Cordialement,<br>
                    L'équipe Vite & Gourmand
                </p>
            ";

            // ======================
            // ENVOI
            // ======================
            $mail->send();

            return true;

        } catch (Exception $e) {

            error_log(
                'Erreur création compte : '
                . $mail->ErrorInfo
            );

            return false;
        }
    }


    // =========================================================
    // EMAIL CONFIRMATION COMMANDE
    // =========================================================
    public function envoyerMailConfirmationCommande(
        string $email,
        string $nomComplet,
        string $nomMenu,
        int $nombrePersonnes,
        string $dateLivraison,
        string $heureLivraison,
        string $adresseLivraison,
        float $prixMenu,
        float $reduction,
        float $fraisLivraison,
        float $prixTotal
    ): bool {

        try {

            // ======================
            // CONFIG SMTP
            // ======================
            $mail = new PHPMailer(true);

            $mail->CharSet = 'UTF-8';
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'egsdigitalagency@gmail.com';
            $mail->Password   = $_ENV['SMTP_PASSWORD'];
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 587;

            // ======================
            // EXPÉDITEUR
            // ======================
            $mail->setFrom(
                'egsdigitalagency@gmail.com',
                'Vite & Gourmand'
            );

            // ======================
            // DESTINATAIRE
            // ======================
            $mail->addAddress(
                $email,
                $nomComplet
            );

            // ======================
            // FORMAT EMAIL
            // ======================
            $mail->isHTML(true);

            // ======================
            // SUJET
            // ======================
            $mail->Subject =
                'Confirmation de votre commande - Vite & Gourmand';

            // ======================
            // CONTENU
            // ======================
            $mail->Body = "
                <h2 style='color: #8c5a20;'>
                    Bonjour {$nomComplet},
                </h2>

                <p>
                    Nous vous confirmons que votre commande a bien été
                    enregistrée sur <strong>Vite & Gourmand</strong>.
                </p>

                <h3>
                    Détails de votre commande
                </h3>

                <p>
                    <strong>Menu :</strong>
                    {$nomMenu}
                    <br>

                    <strong>Nombre de personnes :</strong>
                    {$nombrePersonnes}
                    <br>

                    <strong>Date :</strong>
                    {$dateLivraison}
                    <br>

                    <strong>Heure :</strong>
                    {$heureLivraison}
                    <br>

                    <strong>Adresse :</strong>
                    {$adresseLivraison}
                </p>

                <hr>

                <h3>
                    Détail du prix
                </h3>

                <p>
                    <strong>Prix du menu :</strong>
                    " . number_format(
                    $prixMenu,
                    2,
                    ',',
                    ' '
                ) . " €
                </p>

                <p>
                    <strong>Réduction :</strong>
                    -
                    " . number_format(
                    $reduction,
                    2,
                    ',',
                    ' '
                ) . " €
                </p>

                <p>
                    <strong>Frais de livraison :</strong>
                    " . number_format(
                    $fraisLivraison,
                    2,
                    ',',
                    ' '
                ) . " €
                </p>

                <h3>
                    Total :
                    " . number_format(
                    $prixTotal,
                    2,
                    ',',
                    ' '
                ) . " €
                </h3>

                <p>
                    Votre commande est actuellement
                    en attente de traitement.
                </p>

                <br>

                <p>
                    Cordialement,<br>
                    <strong>L'équipe Vite & Gourmand</strong>
                </p>
            ";

            // =================================================
            // TEST ENVOI
            // =================================================
            $mail->send();

            // Si on arrive ici, PHPMailer a réussi
            die(
                'MAIL ENVOYÉ À : '
                . htmlspecialchars($email)
            );

        } catch (Exception $e) {

            // =================================================
            // AFFICHER L'ERREUR POUR LE TEST
            // =================================================
            die(
                'ERREUR SMTP : '
                . ($mail->ErrorInfo ?? $e->getMessage())
            );
        }
    }
}
