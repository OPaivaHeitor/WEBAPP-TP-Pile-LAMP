<?php
declare(strict_types=1);

require_once __DIR__ . '/bootstrap.php';
require_once __DIR__ . '/Entity/Author.php';
require_once __DIR__ . '/Entity/Citation.php';

use Entity\Author;
use Entity\Citation;

$data = main();
$authors = $data['authors'];

?><!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Toutes les citations</title>
    <style>body{font-family:Arial,Helvetica,sans-serif;padding:1rem}article{margin-bottom:1rem}blockquote{background:#f8f8f8;padding:8px;border-left:4px solid #ccc}</style>
</head>
<body>
    <h1>Liste des auteurs et de leurs citations</h1>

    <?php if (empty($authors)): ?>
        <p>Aucun auteur enregistré.</p>
    <?php else: ?>
        <?php foreach ($authors as $author): ?>
            <article>
                <header><h2><?= htmlspecialchars($author->getPrenom() . ' ' . $author->getNom()) ?> <?php if ($author->getAnneeNaissance()) echo '(' . $author->getAnneeNaissance() . ')'; ?></h2></header>
                <?php $cits = $author->getCitations(); if (empty($cits)): ?>
                    <p><em>Aucune citation</em></p>
                <?php else: ?>
                    <?php foreach ($cits as $cit): ?>
                        <blockquote>
                            <?= nl2br(htmlspecialchars($cit->getTexte())) ?>
                            <div style="font-size:.9em;color:#666">Le <?= htmlspecialchars($cit->getDateAjout()->format('Y-m-d')) ?></div>
                        </blockquote>
                    <?php endforeach; ?>
                <?php endif; ?>
            </article>
        <?php endforeach; ?>
    <?php endif; ?>

    <p><a href="ajout.php">Ajouter une citation</a></p>
</body>
</html>
