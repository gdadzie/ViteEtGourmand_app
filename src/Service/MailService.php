<?php
declare(strict_types=1);

namespace Service;

use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;

final class MailService
{
    private function mailer(): PHPMailer
    {
        $mail = new PHPMailer(true);
        $mail->CharSet = 'UTF-8';
        $mail->isSMTP();
        $mail->Host = $_ENV['SMTP_HOST'] ?? '';
        $mail->Port = (int) ($_ENV['SMTP_PORT'] ?? 587);
        $mail->SMTPAuth = true;
        $mail->Username = $_ENV['SMTP_USER'] ?? '';
        $mail->Password = $_ENV['SMTP_PASSWORD'] ?? '';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->setFrom($_ENV['SMTP_FROM'] ?? $mail->Username, $_ENV['SMTP_NAME'] ?? 'Vite & Gourmand');
        return $mail;
    }

    public function envoyerMailCreationCompte(string $email, string $nomComplet, string $type = 'employe'): bool
    {
        $subject = $type === 'employe' ? 'Création de votre compte employé Vite & Gourmand' : 'Bienvenue chez Vite & Gourmand';
        $body = '<h2>Bonjour ' . htmlspecialchars($nomComplet, ENT_QUOTES, 'UTF-8') . '</h2><p>Votre compte a été créé. Pour des raisons de sécurité, votre mot de passe n’est jamais communiqué par e-mail.</p>';
        return $this->send($email, $nomComplet, $subject, $body);
    }

    public function envoyerMailReinitialisation(string $email, string $nomComplet, string $token): bool
    {
        $url = rtrim($_ENV['APP_URL'] ?? '', '/') . '/index.php?page=reinitialiser_mot_de_passe&token=' . urlencode($token);
        $body = '<h2>Bonjour ' . htmlspecialchars($nomComplet, ENT_QUOTES, 'UTF-8') . '</h2><p>Pour définir un nouveau mot de passe, utilisez ce lien valable une heure :</p><p><a href="' . htmlspecialchars($url, ENT_QUOTES, 'UTF-8') . '">Réinitialiser mon mot de passe</a></p><p>Si vous n’êtes pas à l’origine de cette demande, ignorez cet e-mail.</p>';
        return $this->send($email, $nomComplet, 'Réinitialisation de votre mot de passe', $body);
    }

    private function send(string $email, string $name, string $subject, string $body): bool
    {
        try {
            $mail = $this->mailer();
            $mail->addAddress($email, $name);
            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body = $body;
            $mail->send();
            return true;
        } catch (Exception $exception) {
            error_log('Email delivery failed: ' . $exception->getMessage());
            return false;
        }
    }
}
