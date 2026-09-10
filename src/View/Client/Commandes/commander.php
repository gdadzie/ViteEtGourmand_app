<?php if (isset($menu) && $menu !== null): ?>

    <?php
    // Exemple : utilisateur connecté
    $client = $_SESSION['utilisateur'] ?? null;
    ?>

    <h2>Commander : <?= htmlspecialchars($menu->getTitre()) ?></h2>

    <form method="post" action="index.php?page=commander_menu">

        <input type="hidden"
               name="id_menu"
               value="<?= htmlspecialchars($menu->getIdMenu()) ?>">

        <!-- Nom -->
        <div class="mb-3">
            <label class="form-label">Nom</label>

            <input type="text"
                   name="nom"
                   class="form-control"
                   required
                   value="<?= htmlspecialchars($client['nom'] ?? '') ?>">
        </div>

        <!-- Prénom -->
        <div class="mb-3">
            <label class="form-label">Prénom</label>

            <input type="text"
                   name="prenom"
                   class="form-control"
                   required
                   value="<?= htmlspecialchars($client['prenom'] ?? '') ?>">
        </div>

        <!-- Email -->
        <div class="mb-3">
            <label class="form-label">Email</label>

            <input type="email"
                   name="email"
                   class="form-control"
                   required
                   value="<?= htmlspecialchars($client['email'] ?? '') ?>">
        </div>

        <!-- Téléphone -->
        <div class="mb-3">
            <label class="form-label">GSM</label>

            <input type="text"
                   name="gsm"
                   class="form-control"
                   required
                   value="<?= htmlspecialchars($client['gsm'] ?? '') ?>">
        </div>

        <!-- Adresse -->
        <div class="mb-3">
            <label class="form-label">Adresse de livraison</label>

            <textarea name="adresse_livraison"
                      class="form-control"
                      rows="3"
                      required><?= htmlspecialchars($client['adresse'] ?? '') ?></textarea>
        </div>

        <!-- Date -->
        <div class="mb-3">
            <label class="form-label">Date de livraison</label>

            <input type="date"
                   name="date_livraison"
                   class="form-control"
                   required>
        </div>

        <!-- Heure -->
        <div class="mb-3">
            <label class="form-label">Heure de livraison</label>

            <input type="time"
                   name="heure_livraison"
                   class="form-control"
                   required>
        </div>

        <!-- Nombre de personnes -->
        <div class="mb-3">
            <label class="form-label">Nombre de personnes</label>

            <input type="number"
                   name="nombre_personnes"
                   min="<?= htmlspecialchars($menu->getNbPersonnesMin()) ?>"
                   class="form-control"
                   required>
        </div>


        <button type="submit"
                class="btn btn-success">
            Valider la commande
        </button>

    </form>

<?php else: ?>

    <div class="alert alert-danger">
        Menu introuvable.
    </div>

<?php endif; ?>