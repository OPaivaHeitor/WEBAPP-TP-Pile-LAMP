<?php
// Include the classes from the Entity folder
require_once 'Entity/Author.php';
require_once 'Entity/Citation.php';

// Create Authors
$author1 = new Author("Albert", "Einstein", 1879);
$author2 = new Author("Marie", "Curie", 1867);

// Create Citations
$citation1 = new Citation("Life is like riding a bicycle. To keep your balance you must keep moving.", $author1, new DateTime('1930-05-01'));
$citation2 = new Citation("Nothing in life is to be feared, it is only to be understood.", $author1, new DateTime('1929-06-01'));
$citation3 = new Citation("Nothing in life is to be feared, it is only to be understood.", $author2, new DateTime('1911-04-01'));

// Add citations to authors
$author1->addCitation($citation1);
$author1->addCitation($citation2);
$author2->addCitation($citation3);

// Retrieve all authors
$authors = [$author1, $author2];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Citations</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .author {
            margin-bottom: 20px;
        }
        .author-name {
            font-size: 20px;
            font-weight: bold;
        }
        .citation {
            margin-left: 20px;
        }
    </style>
</head>
<body>

    <h1>All Authors and Their Citations</h1>

    <?php
    // Loop through authors and display their citations
    foreach ($authors as $author) {
        echo "<div class='author'>";
        echo "<div class='author-name'>" . $author->getFullName() . " (" . $author->getBirthYear() . ")</div>";

        // Loop through citations for each author
        $citations = $author->getCitations();
        foreach ($citations as $citation) {
            echo "<div class='citation'>" . $citation->getCitation() . "</div>";
        }

        echo "</div>";
    }
    ?>

</body>
</html>