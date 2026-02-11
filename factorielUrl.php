<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport"
content="width=device-width">
<title>Réception de paramètres
dans l'URL</title>
</head>
<body>
<p>
<?php
if (isset($_GET['n']))
    // afficher le factoriel d'un nombre n passé en paramètre dans l'URL
    {
        $n = $_GET['n'];
        $factoriel = 1;
        for ($i = 1; $i <= $n; $i++) {
            $factoriel *= $i;
        }
        echo $_GET['n'] . ' ! = ' . $factoriel;
    }
else
echo 'Il faut renseigner un numéro dans l\'URL'
?> </p>
</body>
</html>
