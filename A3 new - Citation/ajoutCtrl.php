<?php
// Include the classes from the Entity folder
require_once 'Entity/Author.php';
require_once 'Entity/Citation.php';

// Temporary storage for authors (this should ideally be from a database)
$authors = [
    new Author("Albert", "Einstein", 1879),
    new Author("Marie", "Curie", 1867)
];

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve form data
    $authorName = $_POST['authorName'] ?? '';
    $firstName = $_POST['firstName'] ?? '';
    $lastName = $_POST['lastName'] ?? '';
    $birthYear = $_POST['birthYear'] ?? '';
    $citationText = $_POST['citationText'] ?? '';
    $citationDate = $_POST['citationDate'] ?? '';

    // Check if author exists
    $author = null;
    foreach ($authors as $existingAuthor) {
        if ($existingAuthor->getFullName() == $authorName) {
            $author = $existingAuthor;
            break;
        }
    }

    // If the author does not exist, create a new one
    if (!$author && $firstName && $lastName && $birthYear) {
        $author = new Author($firstName, $lastName, $birthYear);
        $authors[] = $author; // Add new author to the list
    }

    // Add the citation if an author is selected or created
    if ($author && $citationText && $citationDate) {
        $citation = new Citation($citationText, $author, new DateTime($citationDate));
        $author->addCitation($citation);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Citation</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .form-group {
            margin-bottom: 10px;
        }
        .author-list {
            max-height: 100px;
            overflow-y: auto;
        }
    </style>
    <script>
        // JavaScript to show/hide new author fields
        function toggleNewAuthorFields() {
            var authorNameInput = document.getElementById('authorName');
            var newAuthorFields = document.getElementById('newAuthorFields');
            if (authorNameInput.value === '') {
                newAuthorFields.style.display = 'block';
            } else {
                newAuthorFields.style.display = 'none';
            }
        }

        // JavaScript to dynamically populate the author name suggestions
        function showAuthorSuggestions() {
            var input = document.getElementById('authorName').value.toLowerCase();
            var suggestions = document.getElementById('authorSuggestions');
            suggestions.innerHTML = '';

            <?php foreach ($authors as $author) { ?>
                var authorFullName = "<?php echo $author->getFullName(); ?>".toLowerCase();
                if (authorFullName.includes(input) && input !== '') {
                    var div = document.createElement('div');
                    div.textContent = "<?php echo $author->getFullName(); ?>";
                    div.onclick = function() {
                        document.getElementById('authorName').value = "<?php echo $author->getFullName(); ?>";
                        suggestions.innerHTML = '';
                    };
                    suggestions.appendChild(div);
                }
            <?php } ?>
        }
    </script>
</head>
<body>

<h1>Add a Citation</h1>

<form method="POST">
    <div class="form-group">
        <label for="authorName">Author Name</label>
        <input type="text" id="authorName" name="authorName" oninput="showAuthorSuggestions()" autocomplete="off" placeholder="Start typing author's name">
        <div class="author-list" id="authorSuggestions"></div>
    </div>

    <!-- New author fields that will appear if the user doesn't select an existing author -->
    <div id="newAuthorFields" style="display:none;">
        <div class="form-group">
            <label for="firstName">First Name</label>
            <input type="text" id="firstName" name="firstName" placeholder="First name">
        </div>
        <div class="form-group">
            <label for="lastName">Last Name</label>
            <input type="text" id="lastName" name="lastName" placeholder="Last name">
        </div>
        <div class="form-group">
            <label for="birthYear">Birth Year</label>
            <input type="text" id="birthYear" name="birthYear" placeholder="Year of birth">
        </div>
    </div>

    <div class="form-group">
        <label for="citationText">Citation Text</label>
        <textarea id="citationText" name="citationText" placeholder="Enter citation text"></textarea>
    </div>

    <div class="form-group">
        <label for="citationDate">Citation Date</label>
        <input type="date" id="citationDate" name="citationDate">
    </div>

    <div class="form-group">
        <button type="submit">Add Citation</button>
    </div>
</form>

<hr>

<h2>All Authors and Their Citations</h2>

<?php
// Display all authors and their citations
foreach ($authors as $author) {
    echo "<div><strong>" . $author->getFullName() . " (" . $author->getBirthYear() . ")</strong><br>";

    foreach ($author->getCitations() as $citation) {
        echo "<div>&#8220;" . $citation->getCitation() . "&#8221;</div>";
    }

    echo "</div><hr>";
}
?>

</body>
</html>