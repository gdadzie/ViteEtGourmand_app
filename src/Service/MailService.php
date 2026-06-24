<?php

namespace Service;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class MailService
{
    public function envoyerMailCreationCompte(
        string $email,
        string $nomComplet,
        string $type = 'employe'
    ): bool
    {
        $mail = new PHPMailer(true);

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
            $mail->Password   = $_ENV['SMTP_PASSWORD']; // ⚠️ recommandé (pas en dur)
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 587;

            // ======================
            // EXPÉDITEUR
            // ======================
            $mail->setFrom('egsdigitalagency@gmail.com', 'Vite & Gourmand');
            $mail->addAddress($email, $nomComplet);

            // ======================
            // FORMAT EMAIL
            // ======================
            $mail->isHTML(true);

            // ======================
            // SUJET SELON TYPE
            // ======================
            if ($type === 'employe') {
                $mail->Subject = 'Création de votre compte employé Vite & Gourmand';
            } else {
                $mail->Subject = 'Bienvenue sur Vite & Gourmand';
            }

            // ======================
            // CONTENU HTML
            // ======================
            $mail->Body = "
                <h2 style='color: #8c5a20'>Bonjour {$nomComplet}</h2>

                <p>Félicitation, votre compte a été créé avec succès sur <strong>Vite & Gourmand</strong>.</p>

                <p>
                    Pour des raisons de sécurité, votre mot de passe ne vous est pas communiqué par email.<br>
                    Merci de contacter l'administrateur si nécessaire.
                </p>

                <p>
                    <a href='https://vite-et-gourmand-2026-5c40281b04d6.herokuapp.com/?page=connexion'>
                        Se connecter
                    </a>
                </p>

                <br>

                <p>Cordialement,<br>L'équipe Vite & Gourmand</p>
            ";

            $mail->send();
            return true;

        } catch (Exception $e) {
            error_log('Erreur envoi mail : ' . $mail->ErrorInfo);
            return false;
        }
    }
}