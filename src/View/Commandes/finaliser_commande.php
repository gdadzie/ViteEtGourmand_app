<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>
        Commander : <?= htmlspecialchars($menu->getTitre()) ?>
    </title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

<div class="container order-page my-3 my-md-5">

    <div class="row justify-content-center">

        <div class="col-12 col-xl-10">

            <div class="card shadow border-0">

                <div class="card-body p-3 p-md-5">

                    <h1 class="mb-4 text-center">
                        Commander : <?= htmlspecialchars($menu->getTitre()) ?>
                    </h1>

                    <!-- ========================= -->
                    <!-- MENU -->
                    <!-- ========================= -->

                    <div class="card mb-4 mb-md-5 bg-light border-0">
                        <div class="card-body">

                            <h3 class="mb-3">
                                <?= htmlspecialchars($menu->getTitre()) ?>
                            </h3>

                            <p>
                                <?= nl2br(htmlspecialchars($menu->getDescription())) ?>
                            </p>

                            <div class="row g-3">

                                <div class="col-md-4">
                                    <strong>Prix / personne :</strong><br>
                                    <?= number_format($prixParPersonne, 2, ',', ' ') ?> €
                                </div>

                                <div class="col-md-4">
                                    <strong>Minimum :</strong><br>
                                    <?= $minimumPersonnes ?> personne(s)
                                </div>

                                <div class="col-md-4">
                                    <strong>Stock :</strong><br>
                                    <?= $menu->getStockDisponible() ?>
                                </div>

                            </div>

                        </div>
                    </div>

                    <!-- ========================= -->
                    <!-- FORMULAIRE -->
                    <!-- ========================= -->

                    <form method="post" action="index.php?page=commander_menu">

                        <input type="hidden" name="id_menu" value="<?= htmlspecialchars($menu->getIdMenu()) ?>">

                        <!-- CLIENT -->
                        <h4 class="mb-3">Informations du client</h4>

                        <div class="row g-3">

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Nom</label>
                                <input type="text" name="nom" class="form-control" required
                                       value="<?= htmlspecialchars($nom) ?>">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Prénom</label>
                                <input type="text" name="prenom" class="form-control" required
                                       value="<?= htmlspecialchars($prenom) ?>">
                            </div>

                        </div>

                        <div class="row g-3">

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" name="email" class="form-control" required
                                       value="<?= htmlspecialchars($email) ?>">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Téléphone</label>
                                <input type="text" name="gsm" class="form-control" required
                                       value="<?= htmlspecialchars($telephone) ?>">
                            </div>

                        </div>

                        <hr>

                        <!-- PRESTATION -->
                        <h4 class="mb-3">Informations de la prestation</h4>

                        <div class="mb-3">
                            <label class="form-label">Mode de réception</label>
                            <select name="mode_reception" id="mode_reception" class="form-select" required>
                                <option value="livraison">Livraison</option>
                                <option value="sur_place">Retrait sur place</option>
                            </select>
                        </div>

                        <div class="mb-3" id="bloc_adresse">
                            <label class="form-label">Adresse de livraison</label>
                            <input
                                    type="text"
                                    name="adresse_livraison"
                                    id="adresse_livraison"
                                    class="form-control"
                                    value="<?= htmlspecialchars($numeroRue.' '.$nomRue. ' ' . $codePostal );?>">
                            <div class="mb-3">
                                <label class="form-label">Ville de livraison</label>
                            <select name="id_ville" id="id_ville" class="form-select">

                                <?php foreach ($villes as $ville): ?>

                                    <option value="<?= $ville['id_ville'] ?>">

                                        <?= htmlspecialchars($ville['nom_ville']) ?>

                                    </option>

                                <?php endforeach; ?>

                            </select>
                            </div>


                            <div class="form-text">
                                Livraison gratuite dans Bordeaux.
                                Hors Bordeaux : 5 € + 0,59 €/km.
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Date</label>
                            <input type="date" name="date_livraison" class="form-control"
                                   min="<?= $aujourdhui->format('Y-m-d') ?>"
                                   max="<?= $maxDate->format('Y-m-d') ?>"
                                   value="<?= $aujourdhui->format('Y-m-d') ?>"
                                   required>
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Heure</label>
                            <input type="time" name="heure_livraison" class="form-control"
                                   value="12:00" required>
                        </div>

                        <hr>

                        <!-- MENU -->
                        <h4 class="mb-3">Nombre de personnes</h4>

                        <input
                                type="number"
                                name="nombre_personnes"
                                id="nombre_personnes"
                                class="form-control"
                                min="<?= $minimumPersonnes ?>"
                                max="<?= $menu->getStockDisponible() ?>"
                                value="<?= $minimumPersonnes ?>"
                                required
                        >

                        <hr>

                        <!-- PAIEMENT -->
                        <h4 class="mb-3">Paiement</h4>

                        <select name="mode_paiement" class="form-select" required>
                            <option value="paiement_livraison">Paiement à la livraison</option>
                            <option value="paiement_sur_place">Paiement sur place</option>
                        </select>

                        <hr>

                        <!-- RESUME -->
                        <div class="alert alert-info order-summary">

                            <div class="d-flex justify-content-between">
                                <span>Menu</span>
                                <strong id="prix_menu">0 €</strong>
                            </div>

                            <div class="d-flex justify-content-between">
                                <span>Réduction</span>
                                <strong id="reduction">0 €</strong>
                            </div>

                            <div class="d-flex justify-content-between">
                                <span>Livraison</span>
                                <strong id="prix_livraison">0 €</strong>
                            </div>

                            <hr>

                            <div class="d-flex justify-content-between">
                                <span><strong>Total</strong></span>
                                <strong id="prix_total">0 €</strong>
                            </div>

                        </div>

                        <button type="submit" class="btn btn-success w-100 btn-lg">
                            Valider la commande
                        </button>

                    </form>

                </div>
            </div>

        </div>

    </div>

</div>

<!-- ========================= -->
<!-- SCRIPT -->
<!-- ========================= -->

<script>

    const modeReception = document.getElementById('mode_reception');
    const blocAdresse = document.getElementById('bloc_adresse');
    const adresseInput = document.getElementById('adresse_livraison');
    const nombrePersonnesInput = document.getElementById('nombre_personnes');
    const villeSelect = document.getElementById('id_ville');

    const prixMenu = document.getElementById('prix_menu');
    const reduction = document.getElementById('reduction');
    const prixLivraison = document.getElementById('prix_livraison');
    const prixTotal = document.getElementById('prix_total');

    const prixParPersonne = <?= json_encode($prixParPersonne) ?>;
    const minimumPersonnes = <?= json_encode($minimumPersonnes) ?>;

    const villes = <?= json_encode($villes) ?>;

    // =====================================================
    // GESTION ADRESSE
    // =====================================================
    function gererAdresse() {

        if (modeReception.value === 'sur_place') {

            blocAdresse.style.display = 'none';
            adresseInput.required = false;
            adresseInput.value = '';

        } else {

            blocAdresse.style.display = 'block';
            adresseInput.required = true;
        }
    }

    // =====================================================
    // CALCUL LIVRAISON (DYNAMIQUE)
    // =====================================================
    function calculLivraison() {

        if (modeReception.value !== 'livraison') {
            return 0;
        }

        const idVille = villeSelect.value;

        const ville = villes.find(v => v.id_ville == idVille);

        if (!ville) return 0;

        if (ville.nom_ville.toLowerCase() === 'bordeaux') {
            return 0;
        }

        return 5 + (parseFloat(ville.distance_km) * 0.59);
    }

    // =====================================================
    // CALCUL GLOBAL
    // =====================================================
    function calculerPrix() {

        let nb = parseInt(nombrePersonnesInput.value);

        if (isNaN(nb) || nb < minimumPersonnes) {
            nb = minimumPersonnes;
        }

        let totalMenu = prixParPersonne * nb;

        let montantReduction = 0;

        if (nb >= (minimumPersonnes + 5)) {
            montantReduction = totalMenu * 0.10;
        }

        let livraison = calculLivraison();

        let total = totalMenu - montantReduction + livraison;

        prixMenu.innerText = totalMenu.toFixed(2) + ' €';
        reduction.innerText = '-' + montantReduction.toFixed(2) + ' €';
        prixLivraison.innerText = livraison.toFixed(2) + ' €';
        prixTotal.innerText = total.toFixed(2) + ' €';
    }

    // =====================================================
    // EVENTS
    // =====================================================
    modeReception.addEventListener('change', function () {
        gererAdresse();
        calculerPrix();

        if (modeReception.value === 'sur_place') {
            adresseInput.value = '';
        }
    });

    nombrePersonnesInput.addEventListener('input', calculerPrix);
    villeSelect.addEventListener('change', calculerPrix);

    // =====================================================
    // INIT
    // =====================================================
    gererAdresse();
    calculerPrix();

</script>

</body>
</html>
