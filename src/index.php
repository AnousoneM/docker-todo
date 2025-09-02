<?php

// nous appelons toutes nos classes en amont à l'aide de require_once

require_once 'Character.php';
require_once 'Guerrier.php';
require_once 'Orc.php';

// Nous avons besoin des modèles avant l'utilisation des variables de session : le session start doit être après les "require" des classes
session_start();

require_once 'helpers.php';

// on lance notre logique uniquement lorsqu'il y a un POST via un form + button
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // on recherche la variable action dans la superglobal $_POST
    if (isset($_POST['action'])) {

        // On utilise un switch pour définir des actions en fonction des valeurs
        switch ($_POST['action']) {

            case 'create-guerrier':
                // on créé notre Guerrier qu'on va stocker dans une variable de session
                $_SESSION['guerrier']['carac'] = new Guerrier(350, 20, "Epee", 50, "Bouclier", 150);
                $_SESSION['guerrier']['name'] = $guerrierNames[rand(0, count($guerrierNames) - 1)];
                break;

            case 'create-orc':
                // on créé notre Orc qu'on va stocker dans une variable de session
                $_SESSION['orc']['carac'] = new Orc(666, 0, 100, 300);
                $_SESSION['orc']['name'] = $orcNames[rand(0, count($orcNames) - 1)];
                break;

            case 'decide':
                // nous bloquons le random quand nous avons trouvé qui commence = pas d'égalité :
                if (!isset($_SESSION['starter']) || $_SESSION['starter'] == "Egalité, relancer les dés !") {
                    // nous allons déterminer un nombre aléatoire entre 1 et 6, que nous allons attribuer respectivement : Guerrier et Orc
                    $_SESSION['diceGuerrier'] = rand(1, 6);
                    $_SESSION['diceOrc'] = rand(1, 6);

                    // nous allons donc déterminer qui commence :
                    if ($_SESSION['diceGuerrier'] == $_SESSION['diceOrc']) {
                        $_SESSION['starter'] = "Egalité, relancer les dés !";
                    } else if ($_SESSION['diceGuerrier'] > $_SESSION['diceOrc']) {
                        $_SESSION['starter'] = "Le Guerrier commence";
                    } else {
                        $_SESSION['starter'] = "L'Orc commence";
                    }
                }
                break;

            case 'battle':
                // nous appelons notre fonction battle() et nous mettons comme paramètres nos objets stockés dans notre $_SESSION
                battle($_SESSION['guerrier']['carac'], $_SESSION['orc']['carac']);
                break;

                // pas de defaut :) donc pas de DEFAULT 

        }
    }
}

?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Guerrier Vs Orc</title>

    <!-- bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">

    <!-- fonts google -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Rubik+Doodle+Shadow&display=swap" rel="stylesheet">

    <!-- feuille de style -->
    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body class="bg-light">

    <nav class="navbar navbar-expand-lg border border-dark bg-white shadow">
        <div class="container-fluid">
            <a class="navbar-brand" href="#"><img src="assets/img/logo.png" class="img-logo" alt="logo du site"></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <p class="h1 font-title ms-3 display-2">Guerrier vs Orc</p>
            </div>
        </div>
    </nav>


    <div class="row justify-content-center m-0">

        <div class="col-lg-3 p-3">
            <!-- INTERFACE BOUTON -->
            <div class="d-grid gap-1">
                <form action="" method="POST">
                    <input type="hidden" name="action" value="create-guerrier">
                    <!-- en fonction de la création du bouton, le bouton changera : disabled, changement de texte ...  -->
                    <button class="btn btn-dark text-center w-100" <?= isset($_SESSION['guerrier']) ? 'disabled' : '' ?>>
                        <?= isset($_SESSION['guerrier']) ? 'Guerrier <i class="bi bi-check-lg"></i>' : 'Créer Guerrier' ?>
                    </button>
                </form>

                <form action="" method="POST">
                    <input type="hidden" name="action" value="create-orc">
                    <button class="btn btn-dark text-center w-100" <?= isset($_SESSION['orc']) ? 'disabled' : '' ?>>
                        <?= isset($_SESSION['orc']) ? 'Orc <i class="bi bi-check-lg"></i>' : 'Créer Orc' ?>
                    </button>
                </form>

                <?php
                // nous mettons une condtion pour faire apparaitre le bouton decider
                if (isset($_SESSION['orc']) && isset($_SESSION['guerrier'])) { ?>
                    <form action="" method="POST">
                        <input type="hidden" name="action" value="decide">
                        <button class="btn btn-primary text-center w-100" <?= isset($_SESSION['starter']) && $_SESSION['starter'] !== "Egalité, relancer les dés !" ? 'disabled' : '' ?>>
                            <?= $_SESSION['starter'] ?? "Qui commence ?" ?>
                        </button>
                    </form>
                <?php } ?>

                <?php
                // nous mettons une condtion pour faire apparaitre le bouton battle
                if (isset($_SESSION['orc']) && isset($_SESSION['guerrier']) && isset($_SESSION['starter']) && $_SESSION['starter'] !== "Egalité, relancer les dés !") { ?>
                    <form action="" method="POST">
                        <input type="hidden" name="action" value="battle">
                        <button class="btn btn-lg btn-outline-dark w-100">COMBAT !</button>
                    </form>
                <?php } ?>

                <a href="reset.php" class="btn btn-secondary mt-3">Reset</a>
            </div>
        </div>


        <div class="col-lg-3 p-3 d-flex flex-column justify-content-center">
            <!-- INTERFACE GUERRIER -->
            <?php if (isset($_SESSION['guerrier'])) { ?>
                <div class="row justify-content-center">
                    <div class="rounded border border-4 border-secondary shadow m-3 bg-light p-3">
                        <div class="text-center">
                            <p class="h2"><?= $_SESSION['guerrier']['name'] ?></p>
                            <p class="h4">Classe : Guerrier</p>
                        </div>
                        <div class="text-center fs-4">
                            <i class="bi bi-heart-fill text-danger"></i> : <span class="fw-bold text-danger"><?= $_SESSION['guerrier']['carac']->getHealth() <= 0 ? 'MORT' : $_SESSION['guerrier']['carac']->getHealth() ?></span> / <i class="bi bi-droplet-fill text-primary"></i> : <span class="fw-bold text-primary"><?= $_SESSION['guerrier']['carac']->getMana() ?></span>
                        </div>
                        <div class="text-center">
                            <p class="mt-2 mb-0"><i class="bi bi-cursor-fill fs-4"></i> : <b><?= $_SESSION['guerrier']['carac']->getWeapon() ?></b> (<?= $_SESSION['guerrier']['carac']->getWeaponDamage() ?>)</p>
                            <p class="my-0"><i class="bi bi-shield-shaded fs-4"></i> : <b><?= $_SESSION['guerrier']['carac']->getShield() ?></b> (<?= $_SESSION['guerrier']['carac']->getShieldValue() ?>)</p>
                        </div>
                    </div>
                </div>
                <div class="h4 text-center">Valeur du jet : <?= isset($_SESSION['diceGuerrier']) ? "<i class=\"bi bi-dice-" . $_SESSION['diceGuerrier'] . "-fill h4\"></i>" : "" ?></div>
            <?php } else { ?>
                <p class="text-center h4">En attente d'un guerrier !</p>
            <?php } ?>
        </div>

        <div class="col-lg-2 p-3 text-center display-1 d-flex flex-column justify-content-center">
            <!-- IMAGE COMBAT COMMENCE -->
            <i class="bi bi-lightning-fill text-warning"></i>
        </div>

        <div class="col-lg-3 p-3 d-flex flex-column justify-content-center">
            <!-- INTERFACE ORC -->
            <?php if (isset($_SESSION['orc'])) { ?>
                <div class="row justify-content-center">
                    <div class="rounded border border-4 border-secondary shadow m-3 bg-light p-3">
                        <div class="text-center">
                            <p class="h2"><?= $_SESSION['orc']['name'] ?></p>
                            <p class="h4">Classe : Orc</p>
                        </div>
                        <div class="text-center fs-4">
                            <i class="bi bi-heart-fill text-danger"></i> : <span class="fw-bold text-danger"><?= $_SESSION['orc']['carac']->getHealth() <= 0 ? 'MORT' : $_SESSION['orc']['carac']->getHealth() ?></span> / <i class="bi bi-droplet-fill text-primary"></i> : <span class="fw-bold text-primary"><?= $_SESSION['orc']['carac']->getMana() ?></span>
                        </div>
                        <div class="text-center">
                            <p class="mt-2 mb-0"><i class="bi bi-fire fs-4"></i> : <b>Attaque</b> (<?= $_SESSION['orc']['carac']->getDamageMin() ?> - <?= $_SESSION['orc']['carac']->getDamageMax() ?>)</p>
                            <p class="my-0 fs-4 text-light"><i class="bi bi-shield-shaded fs-4"></i></p>
                        </div>
                    </div>
                </div>
                <div class="h4 text-center">Valeur du jet : <?= isset($_SESSION['diceOrc']) ? "<i class=\"bi bi-dice-" . $_SESSION['diceOrc'] . "-fill h4\"></i>" : "" ?></div>
            <?php } else { ?>
                <p class="text-center h4">En attente d'un orc !</p>
            <?php } ?>
        </div>

    </div>

    <div class="row flex-column align-items-center justify-content-center m-0">
        <?php
        if (isset($_SESSION['battle_log'])) {
            foreach (array_reverse($_SESSION['battle_log']) as $index => $log) {
                echo '<div class="p-2 col-4 border border-dark shadow rounded mt-3">';
                echo '<span class="fw-bold">ROUND ' . count($_SESSION['battle_log']) - $index . '</span><br>';
                echo $log;
                echo '</div>';
            }
        }
        ?>
    </div>


</body>

</html>