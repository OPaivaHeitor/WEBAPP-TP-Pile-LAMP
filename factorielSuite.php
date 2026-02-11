<?php
// factorielSuite.php
// Affiche les factoriels de 0 à n (n passé dans l'URL : ?n=10)
// Optimisé en temps/ mémoire : on calcule de manière itérative en réutilisant
// la valeur précédente (f(k) = f(k-1) * k) et on ne garde qu'une seule valeur en mémoire.

// Configuration : limite raisonnable pour éviter DoS via URL
$MAX_N = 10000; // sécurité - ajustable selon vos besoins

// Récupération et validation de n
$raw = isset($_GET['n']) ? trim($_GET['n']) : '';
$error = '';
$n = null;
if ($raw === '') {
    $error = 'Paramètre manquant : veuillez passer n dans l\'URL, par exemple ?n=10';
} elseif (!preg_match('/^-?\d+$/', $raw)) {
    $error = 'Le paramètre n doit être un entier.';
} else {
    $n = (int)$raw;
    if ($n < 0) {
        $error = 'Veuillez fournir un entier positif ou zéro.';
    } elseif ($n > $MAX_N) {
        $error = "n est trop grand (max autorisé : $MAX_N).";
    }
}

// Detect available big-integer libraries
$has_gmp = function_exists('gmp_init') && function_exists('gmp_mul');
$has_bcmath = function_exists('bcmul');

?><!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Série de factoriels</title>
    <style>body{font-family:Arial,Helvetica,sans-serif;padding:1rem}pre{background:#f4f4f4;padding:1rem;border-radius:6px}</style>
</head>
<body>
    <h1>Factoriels de 0 à n</h1>

    <?php if ($error): ?>
        <p style="color:#a33"><?= htmlspecialchars($error, ENT_QUOTES|ENT_SUBSTITUTE, 'UTF-8') ?></p>
        <p>Exemple : <a href="?n=10">?n=10</a></p>
    <?php else: ?>

        <p>Calcul de 0! à <?= $n ?>! <?= $has_gmp ? '(GMP)' : ($has_bcmath ? '(BCMath)' : '(entiers natifs, précis jusqu\'à 20!)') ?></p>

        <pre>
<?php
        // Impression progressive — n'affecte pas la mémoire, on n'enregistre pas la série entière.
        // Nous utilisons la bibliothèque disponible pour supporter de grands entiers.

        // Cas GMP — très rapide et efficace en mémoire
        if ($has_gmp) {
            $cur = gmp_init(1);
            echo "0! = 1\n";
            for ($i = 1; $i <= $n; $i++) {
                $cur = gmp_mul($cur, $i);
                echo $i . '! = ' . gmp_strval($cur) . "\n";
                // flush pour afficher progressivement (utile pour très grands n en console/CLI)
                if (ob_get_length() !== false) { @ob_flush(); @flush(); }
            }

        // Cas BCMath — fonctionne sans extension C, manipule des chaînes (big-int)
        } elseif ($has_bcmath) {
            $cur = '1';
            echo "0! = 1\n";
            for ($i = 1; $i <= $n; $i++) {
                $cur = bcmul($cur, (string)$i);
                echo $i . '! = ' . $cur . "\n";
                if (ob_get_length() !== false) { @ob_flush(); @flush(); }
            }

        // Cas sans extensions : utiliser les entiers natifs tant que c'est précis (jusqu'à 20!)
        } else {
            if ($n > 20) {
                echo "La machine PHP ne dispose pas de BCMath ni de GMP.\n";
                echo "Les entiers natifs donnent des résultats corrects jusqu'à 20!.\n";
                echo "Affichage jusqu'à 20. Si vous voulez calculer au-delà, activez l'extension BCMath ou GMP.\n\n";
                $limit = 20;
            } else {
                $limit = $n;
            }

            $cur = 1;
            echo "0! = 1\n";
            for ($i = 1; $i <= $limit; $i++) {
                $cur *= $i;
                echo $i . '! = ' . $cur . "\n";
            }

            // Si n > 20, on informe l'utilisateur que les autres factorielles ne sont pas affichées
            if ($n > 20) {
                echo "\n(n > 20 non affiché car BCMath/GMP non disponibles)\n";
            }
        }
?>
        </pre>

    <?php endif; ?>

    <p><a href="factorielForm.php">Retour au formulaire</a></p>
</body>
</html>
