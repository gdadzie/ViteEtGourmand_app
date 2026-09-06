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
        $mail->Host = $_ENV['SMTP_HOST'] ?? 'smtp.gmail.com';
        $mail->Port = (int) ($_ENV['SMTP_PORT'] ?? 587);
        $mail->SMTPAuth = true;
        $mail->Username = $_ENV['SMTP_USER'] ?? 'egsdigitalagency@gmail.com';
        $mail->Password = $_ENV['SMTP_PASSWORD'] ?? '';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->setFrom($_ENV['SMTP_FROM'] ?? $mail->Username, $_ENV['SMTP_NAME'] ?? 'Vite & Gourmand');
        return $mail;
    }

    public function envoyerMailCreationCompte(string $email, string $nomComplet, string $type = 'employe'): bool
    {
        $subject = $type === 'employe' ? 'Création de votre compte employé Vite & Gourmand' : 'Bienvenue chez Vite & Gourmand';
        return $this->send($email, $nomComplet, $subject, '<h2>Bonjour ' . $this->escape($nomComplet) . '</h2><p>Votre compte a été créé avec succès.</p>');
    }

    public function envoyerMailReinitialisation(string $email, string $nomComplet, string $token): bool
    {
        $url = rtrim($_ENV['APP_URL'] ?? '', '/') . '/index.php?page=reinitialiser_mot_de_passe&token=' . urlencode($token);
        $body = '<h2>Bonjour ' . $this->escape($nomComplet) . '</h2><p>Utilisez ce lien valable une heure :</p><p><a href="' . $this->escape($url) . '">Réinitialiser mon mot de passe</a></p>';
        return $this->send($email, $nomComplet, 'Réinitialisation de votre mot de passe', $body);
    }

    public function envoyerMailConfirmationCommande(string $email, string $nomComplet, string $nomMenu, int $nombrePersonnes, string $dateLivraison, string $heureLivraison, string $adresseLivraison, float $prixMenu, float $reduction, float $fraisLivraison, float $prixTotal): bool
    {
        $money = static fn (float $amount): string => number_format($amount, 2, ',', ' ') . ' €';
        $body = '<h2>Bonjour ' . $this->escape($nomComplet) . '</h2><p>Votre commande a bien été enregistrée.</p><ul>'
            . '<li><strong>Menu :</strong> ' . $this->escape($nomMenu) . '</li><li><strong>Personnes :</strong> ' . $nombrePersonnes . '</li>'
            . '<li><strong>Livraison :</strong> ' . $this->escape($dateLivraison) . ' à ' . $this->escape($heureLivraison) . '</li>'
            . '<li><strong>Adresse :</strong> ' . $this->escape($adresseLivraison) . '</li></ul>'
            . '<p>Menu : ' . $money($prixMenu) . '<br>Réduction : -' . $money($reduction) . '<br>Livraison : ' . $money($fraisLivraison) . '<br><strong>Total : ' . $money($prixTotal) . '</strong></p>';
        return $this->send($email, $nomComplet, 'Confirmation de votre commande — Vite & Gourmand', $body);
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

    private function escape(string $value): string
    {
        return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
    }
}
