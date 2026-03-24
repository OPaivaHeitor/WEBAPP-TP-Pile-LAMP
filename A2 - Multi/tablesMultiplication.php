<?php
require_once __DIR__ . '/function.php';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Tables de multiplication (1 à 10)</title>
    <style>
        body{font-family:Arial,Helvetica,sans-serif;padding:1rem}
        .grid{display:flex;flex-wrap:wrap;gap:1rem}
        .cell{flex:1 1 220px;min-width:180px}
        .mult-table{width:100%;border-collapse:collapse}
        .mult-table td{border:1px solid #ddd;padding:4px}
        .mult-table caption{font-weight:bold;margin-bottom:6px}
    </style>
</head>
<body>
    <h1>Tables de multiplication — 1 à 10 (taille 10)</h1>
    <p>Affichage compact des 10 premières tables (1..10), chacune de 1 à 10.</p>

    <div class="grid">
        <?php for ($num = 1; $num <= 10; $num++): ?>
            <div class="cell">
                <?= tableMult($num, 10) ?>
            </div>
        <?php endfor; ?>
    </div>

    <p><a href="index.php">Retour à l'accueil</a></p>
</body>
</html>
