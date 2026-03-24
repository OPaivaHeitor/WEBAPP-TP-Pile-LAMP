<?php
require_once __DIR__ . '/function.php';

// Récupération et validation des paramètres
$rawNum = isset($_GET['numero']) ? trim($_GET['numero']) : '';
$rawSize = isset($_GET['taille']) ? trim($_GET['taille']) : '';
$error = '';
$numero = null;
$taille = null;

if ($rawNum === '' || $rawSize === '') {
    $error = 'Veuillez fournir les paramètres numero et taille dans l\'URL, par exemple ?numero=6&taille=10';
} elseif (!preg_match('/^-?\d+$/', $rawNum) || !preg_match('/^-?\d+$/', $rawSize)) {
    $error = 'Les paramètres doivent être des entiers.';
} else {
    $numero = (int)$rawNum;
    $taille = max(1, (int)$rawSize);
    if ($numero < 0) {
        $error = 'Le numero doit être positif.';
    }
}

?><!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Table de multiplication</title>
    <style>body{font-family:Arial,Helvetica,sans-serif;padding:1rem}.mult-table{border-collapse:collapse}</style>
</head>
<body>
    <h1>Table de multiplication</h1>

    <?php if ($error): ?>
        <p style="color:#a33"><?= htmlspecialchars($error, ENT_QUOTES|ENT_SUBSTITUTE, 'UTF-8') ?></p>
        <p>Exemples : <a href="?numero=6&taille=10">?numero=6&taille=10</a> — <a href="?numero=12&taille=15">?numero=12&taille=15</a></p>
    <?php else: ?>
        <p>Affichage de la table de <?= htmlspecialchars((string)$numero, ENT_QUOTES|ENT_SUBSTITUTE, 'UTF-8') ?> de 1 à <?= htmlspecialchars((string)$taille, ENT_QUOTES|ENT_SUBSTITUTE, 'UTF-8') ?> :</p>
        <?= tableMult($numero, $taille) ?>
    <?php endif; ?>

    <p><a href="index.php">Retour</a></p>
</body>
</html>
