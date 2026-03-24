<?php
declare(strict_types=1);

// Bootstrap data for citation system — main() returns an array of Author objects
require_once __DIR__ . '/Entity/Author.php';
require_once __DIR__ . '/Entity/Citation.php';

use Entity\Author;
use Entity\Citation;

/**
 * Initialise un ensemble d'auteurs et de citations pour le système.
 * Retourne un tableau associatif ['authors' => Author[]]
 */
function main(): array
{
    // Create sample authors
    $a1 = new Author(1, 'Albert', 'Camus', 1913);
    $a2 = new Author(2, 'Simone', 'de Beauvoir', 1908);
    $a3 = new Author(3, 'Victor', 'Hugo', 1802);

    // Create sample citations
    $c1 = new Citation(1, "Le vrai génie, c'est d'avoir le coup d'oeil.", new DateTimeImmutable('1942-01-01'));
    $c2 = new Citation(2, "On ne naît pas femme : on le devient.", new DateTimeImmutable('1949-01-01'));
    $c3 = new Citation(3, "La liberté commence où l'ignorance finit.", new DateTimeImmutable('1862-01-01'));

    // Link citations to authors (bidirectional handled by setAuteur)
    $c1->setAuteur($a1);
    $c2->setAuteur($a2);
    $c3->setAuteur($a3);

    // return authors as an array
    return ['authors' => [$a1, $a2, $a3], 'nextCitationId' => 4, 'nextAuthorId' => 4];
}
