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
        $body = '<h2>Bonjour ' . $this->escape($nomComplet) . '</h2>'
            . '<p>Votre compte  Vite &amp; Gourmand employé vient d’être créee et est désormais actif.</p>'
            . '<p>Vous pouvez dès maintenant vous connecter,et acceder à votre espace de gestion.</p>'
            . '<p>À bientôt,<br><strong>L’équipe Vite &amp; Gourmand</strong></p>';

        return $this->send($email, $nomComplet, $subject, $body);
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

    public function envoyerMailContact(string $email, string $title, string $message): bool
    {
        $recipient = $_ENV['CONTACT_RECIPIENT'] ?? $_ENV['SMTP_FROM'] ?? $_ENV['SMTP_USER'] ?? '';
        if ($recipient === '') {
            error_log('Contact email not sent: no recipient configured.');
            return false;
        }

        $body = '<h2>Nouveau message de contact</h2>'
            . '<p><strong>Expéditeur :</strong> ' . $this->escape($email) . '</p>'
            . '<p><strong>Objet :</strong> ' . $this->escape($title) . '</p>'
            . '<p>' . nl2br($this->escape($message)) . '</p>';

        return $this->send($recipient, 'Vite & Gourmand', 'Contact : ' . $title, $body, $email);
    }

    public function envoyerAccuseReceptionContact(string $email, string $title): bool
    {
        $body = '<h2>Bonjour,</h2>'
            . '<p>Nous avons bien reçu votre message concernant : <strong>' . $this->escape($title) . '</strong>.</p>'
            . '<p>Notre équipe vous répondra dans les meilleurs délais.</p>'
            . '<p>À bientôt,<br><strong>Vite &amp; Gourmand</strong></p>';

        return $this->send($email, 'Client Vite & Gourmand', 'Accusé de réception de votre message', $body);
    }

    public function envoyerReponseContact(string $email, string $title, string $response): bool
    {
        $body = '<h2>Bonjour,</h2>'
            . '<p>En réponse à votre message « <strong>' . $this->escape($title) . '</strong> » :</p>'
            . '<p>' . nl2br($this->escape($response)) . '</p>'
            . '<p>Cordialement,<br><strong>Vite &amp; Gourmand</strong></p>';

        return $this->send($email, 'Client Vite & Gourmand', 'Réponse à votre demande — Vite & Gourmand', $body);
    }

    private function send(string $email, string $name, string $subject, string $body, ?string $replyTo = null): bool
    {
        try {
            $mail = $this->mailer();
            $mail->addAddress($email, $name);
            if ($replyTo !== null && filter_var($replyTo, FILTER_VALIDATE_EMAIL)) {
                $mail->addReplyTo($replyTo);
            }
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
