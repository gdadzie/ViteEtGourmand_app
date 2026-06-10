<?php

namespace Service;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class MailService
{
    public function envoyerMailCreationCompte(string $email, string $nomComplet): bool
    {
        $mail = new PHPMailer(true);

        try {
            // ======================
            // CONFIGURATION SMTP
            // ======================
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'egsdigitalagency@gmail.com'; // ← À MODIFIER
            $mail->Password   = 'zgfvsiquymarqvug';  // ← À MODIFIER
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 587;

            // ======================
            // EXPÉDITEUR / DESTINATAIRE
            // ======================
            $mail->setFrom('TON_EMAIL_GMAIL@gmail.com', 'Vite & Gourmand');
            $mail->addAddress($email, $nomComplet);

            // ======================
            // CONTENU DU MAIL
            // ======================
            $mail->isHTML(false);
            $mail->Subject = 'Création de votre compte employé';

            $mail->Body =
                "Bonjour $nomComplet,

Un compte employé a été créé pour vous sur l'application Vite & Gourmand.

Pour des raisons de sécurité, votre mot de passe ne vous est pas communiqué par email.
Merci de vous rapprocher de l'administrateur afin de l'obtenir.
<a href='https://vite-et-gourmand-35a20c0f19db.herokuapp.com/index.php?page=home'>Cliquez ici pour vous connecter.</a>
Cordialement,
L'équipe Vite & Gourmand";

            $mail->send();
            return true;

        } catch (Exception $e) {
            error_log('Erreur envoi mail : ' . $mail->ErrorInfo);
            return false;
        }
    }
}
