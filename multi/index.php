<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Accueil - Tables de multiplication</title>
    <style>body{font-family:Arial,Helvetica,sans-serif;padding:1rem}ul{line-height:1.8}</style>
</head>
<body>
    <h1>Accueil — Tables de multiplication</h1>

    <p><a href="tablesMultiplication.php">Voir les 10 premières tables (1..10) de taille 10</a></p>

    <h2>Liens vers les 10 premières tables (taille 10)</h2>
    <ul>
        <?php for ($i = 1; $i <= 10; $i++): ?>
            <li><a href="tableMultiplication.php?numero=<?= $i ?>&taille=10">Table de <?= $i ?> (taille 10)</a></li>
        <?php endfor; ?>
    </ul>

    <p><a href="tableTest.php">Page de test (tableTest.php)</a></p>
</body>
</html>
