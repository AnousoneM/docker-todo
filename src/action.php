<?php

// on appelle notre classe Task à l'aide d'un require_once
require_once 'classes/Task.php';

session_start();

// si nous n'avons pas de variable de session $_SESSION['toutesLesTaches'], on en créé une qui contiendra un tableau vide
if (!isset($_SESSION['toutesLesTaches'])) {
    $_SESSION['toutesLesTaches'] = [];
};

// action = add
if (isset($_GET['action']) && $_GET['action'] == 'add') {
    // 1 on créer un objet selon la classe Task : on lui donne le post title comme titre recuperé en POST
    $monObjet = new Task($_POST['title']);

    // 2 on stocke cet objet dans ma variable de session toutesLesTaches : $_SESSION['toutesLesTaches']
    // il s'agira d'un tableau, c'est pour cela que nous allons utiliser le raccourci de arrayPush()

    // array_push($_SESSION['toutesLesTaches'], $monObjet);
    $_SESSION['toutesLesTaches'][] = $monObjet;

    // 3 il faut rediriger vers la page todo.php
    header('Location: todo.php');
}

// action = delete
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['index'])) {
    // 1 je recupère l'index
    $index = $_GET['index'];

    // 2 j'efface l'élément de mon tableau à l'aide de l'index recupéré plus haut
    // pour cela j'utilise la fonction unset() de PHP
    unset($_SESSION['toutesLesTaches'][$index]);

    // 3 il faut rediriger vers la page todo.php
    header('Location: todo.php');
}

// action = toggle
if (isset($_GET['action']) && $_GET['action'] == 'toggle' && isset($_GET['index'])) {
    // 1 je recupère l'index
    $index = $_GET['index'];

    // 2 je recupère mon objet à l'aide de son index, puis j'applique la méthode toggle()
    $_SESSION['toutesLesTaches'][$index]->toggle();

    // 3 il faut rediriger vers la page todo.php
    header('Location: todo.php');
}

