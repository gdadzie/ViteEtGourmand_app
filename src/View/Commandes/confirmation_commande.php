
<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$e = fn($v) => htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8');
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Commande confirmée - Vite & Gourmand</title>

    <style>
        :root {
            --brand: #aa6d27;
            --brand-dark: #935f22;
            --bg: #f5f5f5;
            --card: #ffffff;
            --text: #212529;
            --muted: #6c757d;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: var(--bg);
            color: var(--text);
        }

        .confirmation-container {
            min-height: 80vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 30px 15px;
        }

        .confirmation-card {
            width: 100%;
            max-width: 600px;
            background: var(--card);
            border-radius: 12px;
            padding: 45px 35px;
            text-align: center;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
        }

        .check {
            width: 70px;
            height: 70px;
            margin: 0 auto 20px;
            border-radius: 50%;
            background: #e8f5e9;
            color: #2e7d32;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 40px;
            font-weight: bold;
        }

        h1 {
            color: var(--brand);
            margin-bottom: 15px;
        }

        p {
            line-height: 1.6;
            color: var(--muted);
        }

        .email-info {
            margin: 25px 0;
            padding: 15px;
            background: #f8f8f8;
            border-radius: 8px;
        }

        .email-info strong {
            color: var(--text);
        }

        .buttons {
            margin-top: 30px;
            display: flex;
            justify-content: center;
            gap: 15px;
            flex-wrap: wrap;
        }

        .btn {
            display: inline-block;
            padding: 12px 22px;
            border-radius: 6px;
            text-decoration: none;
            font-weight: bold;
        }

        .btn-primary {
            background: var(--brand);
            color: white;
        }

        .btn-primary:hover {
            background: var(--brand-dark);
        }

        .btn-secondary {
            border: 1px solid var(--brand);
            color: var(--brand);
            background: white;
        }
    </style>
</head>

<body>

<div class="confirmation-container">

    <div class="confirmation-card">

        <div class="check">✓</div>

        <h1>Commande confirmée !</h1>

        <p>
            Votre commande a bien été enregistrée.
        </p>

        <p>
            Un e-mail de confirmation contenant le récapitulatif
            de votre commande vous a été envoyé.
        </p>

        <?php if (!empty($_SESSION['email_confirmation'])): ?>

            <div class="email-info">
                E-mail envoyé à :
                <strong>
                    <?= $e($_SESSION['email_confirmation']) ?>
                </strong>
            </div>

        <?php endif; ?>

        <p>
            Merci d'avoir choisi <strong>Vite & Gourmand</strong>.
        </p>

        <div class="buttons">

            <a href="?page=espace_utilisateur" class="btn btn-primary">
                Voir mes commandes
            </a>

            <a href="?page=accueil" class="btn btn-secondary">
                Retour à l'accueil
            </a>

        </div>

    </div>

</div>

</body>
</html>

