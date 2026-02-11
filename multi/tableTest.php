<?php
require_once __DIR__ . '/function.php';
?>
<!DOCTYPE html>
<html lang="fr">
    <h1>Test : Table de multiplication (taille 15)</h1>

    <p>Affichage d'une table « quelconque » de taille 15 (ici numéro = 6).</p>

    <?= tableMult(6, 15) ?>

    <p><a href="index.php">Retour</a></p>
</html>
