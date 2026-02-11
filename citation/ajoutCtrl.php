<?php
declare(strict_types=1);

// controller that builds Entity instances and shows form / results
// No output must happen before declare(strict_types=1)

require_once __DIR__ . '/bootstrap.php';
require_once __DIR__ . '/Entity/Author.php';
require_once __DIR__ . '/Entity/Citation.php';

use Entity\Author;
use Entity\Citation;

$erreur = [];
$data = main();
$authors = $data['authors'];
$nextCitationId = $data['nextCitationId'] ?? 1;
$nextAuthorId = $data['nextAuthorId'] ?? (count($authors) + 1);

// helpers
function findAuthor(array $authors, string $input): ?Author
{
	$input = trim($input);
	if ($input === '') return null;

	// format: Prenom | Nom [year]
	if (preg_match('/^\s*([^|]+)\|\s*([^\[]+)(?:\s*\[(\d{4})\])?\s*$/', $input, $m)) {
		$prenom = trim($m[1]);
		$nom = trim($m[2]);
		$year = isset($m[3]) ? (int)$m[3] : null;
		foreach ($authors as $a) {
			if (strcasecmp($a->getPrenom(), $prenom) === 0 && strcasecmp($a->getNom(), $nom) === 0) {
				return $a;
			}
		}
		return new Author(null, $prenom, $nom, $year);
	}

	// try matching "Prenom Nom"
	if (preg_match('/^\s*(\S+)\s+(\S[\S ]*)\s*$/', $input, $m)) {
		$prenom = trim($m[1]);
		$nom = trim($m[2]);
		foreach ($authors as $a) {
			if (strcasecmp($a->getPrenom(), $prenom) === 0 && strcasecmp($a->getNom(), $nom) === 0) {
				return $a;
			}
		}
		return new Author(null, $prenom, $nom, null);
	}

	return null;
}

if (isset($_POST['login']) && $_SERVER['REQUEST_METHOD'] === 'POST') {
	$login = trim(htmlentities($_POST['login'] ?? ''));
	$texte = trim(htmlentities($_POST['citation'] ?? ''));
	$auteurInput = trim($_POST['auteur'] ?? '');
	$dateInput = trim($_POST['date'] ?? '');

	if ($login === '') $erreur['login'] = "Le login n'est pas renseigné";
	if ($texte === '') $erreur['citation'] = "La citation ne peut pas être vide";
	// validate date
	if ($dateInput !== '') {
		try { $d = new DateTimeImmutable($dateInput); }
		catch (Exception $e) { $erreur['date'] = 'Date invalide'; }
		if (empty($erreur['date']) && $d > new DateTimeImmutable()) { $erreur['date'] = 'La date ne peut pas être dans le futur'; }
	} else {
		$d = new DateTimeImmutable();
	}

	if (count($erreur) === 0) {
		// find or create author
		$found = findAuthor($authors, $auteurInput);
		if ($found === null) {
			// If separate fields provided, attempt to read them
			$prenom = trim($_POST['prenom'] ?? '');
			$nom = trim($_POST['nom'] ?? '');
			$annee = isset($_POST['annee']) && $_POST['annee'] !== '' ? (int)$_POST['annee'] : null;
			if ($prenom !== '' && $nom !== '') {
				$found = new Author(null, $prenom, $nom, $annee);
			} else {
				// fallback: create a generic author from the raw input
				$found = new Author(null, $auteurInput ?: 'Inconnu', '');
			}
		}

		// ensure created authors get an id and are registered
		if ($found->getId() === null) {
			$reflection = new ReflectionClass($found);
			// set id property via reflection (since it's private)
			$prop = $reflection->getProperty('id');
			$prop->setAccessible(true);
			$prop->setValue($found, $nextAuthorId);
			$nextAuthorId++;
			$authors[] = $found;
		}

		// create citation
		$citation = new Citation($nextCitationId, $texte, $d);
		$nextCitationId++;
		$citation->setAuteur($found);

		// After successful addition, show all authors and citations
		// We'll render the view below by setting $showAll = true
		$showAll = true;
	}
} else {
	$login = $texte = $auteurInput = $dateInput = '';
}

// expose variables expected by the HTML below
$citation = $texte;
$auteur = $auteurInput;
$date = $dateInput;

?>
<!DOCTYPE html >
<html lang="fr">
	<head>
		<title>Ajout de citation </title>
		<meta charset="UTF-8">
	</head>
	<body>
		<main>
			<article>
				<header><h1>Formulaire de création de citations</h1></header>
				<form method="post" name="FrameCitation" action="<?php echo $_SERVER['PHP_SELF'];?>">
				  <table border="0" bgcolor="#ccccff" frame="above">
					<tbody>
						<tr>
							<th><label for="login">Login</label></th> 
							<td><input name="login" maxlength="64" size="32" value="<?php echo $login;?>"></td>
							<td><?php if(!(empty($erreur['login']))) echo $erreur['login'] ?></td> 
						</tr>
						<tr>
							<th><label for="citation">Citation</label></th>
							<td><textarea cols="64" rows="5" name="citation"><?php echo $citation;?></textarea></td>
							<td><?php if(!(empty($erreur['citation']))) echo $erreur['citation']; ?></td> 
						</tr>
						<tr>
							<th><label for="auteur">Auteur</label></th>
							<td><input name="auteur" maxlength="128" size="64"value="<?php echo $auteur;?>"></td>
						</tr>
						<tr>
							<th><label for="date">Date</label></th>
							<td><input id="datePicker" name="date" type="date"value="<?php echo $date;?>"></td>
							<td><?php if(!(empty($erreur['date']))) echo $erreur['date'] ?></td> 
						</tr>
						<tr>
							<input type="hidden" name="step" value="1"/>
							<td colspan="2" align="center"><input name="Envoyer" value="Enregistrer la citation" type="submit"><input name="Effacer" value="Annuler" type="reset"></td>
						</tr>
					</tbody>
				  </table>
				</form>
			</article>
		</main>
 		<script>
		function convertToISO(timebit) {
	timebit.setHours(0, -timebit.getTimezoneOffset(), 0, 0);
	// remove GMT offset
	var isodate = timebit.toISOString().slice(0,10);
	// format convert and take first 10 characters of result
	return isodate;
	}
		document.getElementById('datePicker').value = convertToISO(new Date());</script>
 </body>
</html>
