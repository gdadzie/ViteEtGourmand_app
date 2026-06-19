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
                $mail->Subject = 'Création de votre compte employé';
            } else {
                $mail->Subject = 'Bienvenue sur Vite & Gourmand';
            }

            // ======================
            // CONTENU HTML
            // ======================
            $mail->Body = "
                <h2>Bonjour {$nomComplet}</h2>

                <p>Votre compte a été créé avec succès sur <strong>Vite & Gourmand</strong>.</p>

                <p>
                    Pour des raisons de sécurité, votre mot de passe ne vous est pas communiqué par email.<br>
                    Merci de contacter l'administrateur si nécessaire.
                </p>

                <p>
                    <a href='https://vite-et-gourmand-35a20c0f19db.herokuapp.com/index.php?page=connexion'>
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