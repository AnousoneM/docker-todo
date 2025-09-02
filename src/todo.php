<?php

require_once 'classes/Task.php';

session_start();

?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TO DO LIST</title>

    <!-- bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">

    <!-- google fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cabin+Sketch:wght@400;700&display=swap" rel="stylesheet">

    <!-- feuille de style -->
    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body class="bg-success-subtle text-center cabin-sketch-regular">

    <div class="py-3 mb-3 text-white bg-success bg-gradient">
        <a href="index.php" class="text-decoration-none text-white">
            <h1 class="text-center cabin-sketch-bold">Tout Doux LISTE</h1>
        </a>
    </div>

    <h2>A faire !!!</h2>

    <div class="row justify-content-center mx-0 mb-3">
        <?php foreach (array_reverse($_SESSION['toutesLesTaches'], 1) as $index => $task) { ?>
            <div class="m-2">
                <a href="action.php?action=toggle&index=<?= $index ?>" class="btn text-success"><i class="bi bi-toggle-<?= $task->getIsDone() == false ? "off" : "on" ?> h2"></i></a>
                <button class="btn btn-<?= $task->getIsDone() == false ? "outline-" : "" ?>success col-lg-4 col-8 text-start"><?= $task->getTitle() ?></button>
                <a href="action.php?action=delete&index=<?= $index ?>" class="btn btn-danger"><i class="bi bi-trash3-fill"></i></a>
            </div>
        <?php } ?>
    </div>

    <a href="index.php" class="btn btn-success">Nouvelle tâche</a>

</body>