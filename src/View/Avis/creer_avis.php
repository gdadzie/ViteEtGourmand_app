<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Détail commande</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">



    <style>
        .review-form-card{
            background:#fff;
            border-radius:18px;
            padding:35px;
            box-shadow:0 4px 15px rgba(0,0,0,0.06);
        }

        .custom-input{
            border-radius:12px;
            border:1px solid #e3e3e3;
            padding:12px 15px;
        }

        .custom-input:focus{
            border-color:#aa6d27;
            box-shadow:0 0 0 0.2rem rgba(170,109,39,0.15);
        }

        .btn-submit-review{
            background:#aa6d27;
            color:#fff;
            border:none;
            padding:12px 30px;
            border-radius:50px;
            font-weight:600;
            transition:.2s ease;
        }

        .btn-submit-review:hover{
            background:#915b20;
            color:#fff;
            transform:translateY(-2px);
        }
    </style>
</head>

<section class="section-slab bg-tint">
    <div class="container px-2 px-md-4">


        <div class="row justify-content-center">

            <div class="col-12 col-lg-8">

                <div class="review-form-card">

                    <h2 class="h-title text-center mb-2">
                        Laissez votre avis
                    </h2>

                    <p class="text-center text-muted mb-4">
                        Votre retour nous aide à améliorer nos services.
                    </p>

                    <form method="POST" action="/avis/create">

                        <div class="mb-4">

                            <label class="form-label fw-semibold">
                                Votre note
                            </label>

                            <select name="note"
                                    class="form-select custom-input"
                                    required>

                                <option value="">Choisir une note</option>
                                <option value="5">★★★★★ - Excellent</option>
                                <option value="4">★★★★☆ - Très bien</option>
                                <option value="3">★★★☆☆ - Bien</option>
                                <option value="2">★★☆☆☆ - Moyen</option>
                                <option value="1">★☆☆☆☆ - Décevant</option>

                            </select>

                        </div>

                        <div class="mb-4">

                            <label class="form-label fw-semibold">
                                Votre commentaire
                            </label>

                            <textarea name="commentaire"
                                      rows="5"
                                      class="form-control custom-input"
                                      placeholder="Partagez votre expérience..."
                                      required></textarea>

                        </div>

                        <div class="text-center">

                            <button type="submit"
                                    class="btn btn-submit-review">

                                <i class="bi bi-send-fill me-2"></i>
                                Publier mon avis

                            </button>

                        </div>

                    </form>

                </div>

            </div>

        </div>

    </div>


</section>
