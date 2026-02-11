<?php
// multi/function.php
// Fournit la fonction tableMult(numero, taille) qui renvoie le HTML
// d'une table de multiplication de 'numero' de 1 à 'taille'.

/**
 * Construit une table de multiplication HTML
 *
 * @param int $numero le numéro de la table (ex: 6)
 * @param int $taille nombre de lignes de la table (ex: 20)
 * @return string HTML fragment contenant la table
 */
function tableMult(int $numero, int $taille): string
{
    $numero = (int)$numero;
    $taille = max(1, (int)$taille);

    $numEsc = htmlspecialchars((string)$numero, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    $sizeEsc = htmlspecialchars((string)$taille, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');

    $html = "<table class=\"mult-table\" border=\"1\" cellpadding=\"6\" cellspacing=\"0\">";
    $html .= "<caption>Table de $numEsc (1 à $sizeEsc)</caption>";
    for ($i = 1; $i <= $taille; $i++) {
        $left = $numero . ' × ' . $i;
        $right = $numero * $i;
        $html .= '<tr><td>' . htmlspecialchars($left, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') . '</td><td>' . htmlspecialchars((string)$right, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') . '</td></tr>';
    }
    $html .= '</table>';
    return $html;
}
