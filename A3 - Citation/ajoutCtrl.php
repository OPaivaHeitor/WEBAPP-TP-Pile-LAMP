<?php
declare(strict_types=1);

require_once __DIR__ . '/bootstrap.php';
require_once __DIR__ . '/config/db-functions.php';
require_once __DIR__ . '/Entity/Author.php';
require_once __DIR__ . '/Entity/Citation.php';

use Entity\Author;
use Entity\Citation;

// Load authors from database
$rawAuthors = db_get_authors();
$authors = [];
foreach ($rawAuthors as $row) {
    $annee = $row['birthDate'] ? (int)date('Y', strtotime($row['birthDate'])) : null;
    $authors[] = new Author((int)$row['idAuteur'], $row['firstName'], $row['lastName'], $annee);
}

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

			try {
				if ($prenom !== '' && $nom !== '') {
					$found = new Author(null, $prenom, $nom, $annee);
				} elseif ($auteurInput !== '') {
					// try to split the single input into prenom and nom
					$parts = preg_split('/\s+/', $auteurInput, 2, PREG_SPLIT_NO_EMPTY);
					if (count($parts) === 2) {
						$found = new Author(null, $parts[0], $parts[1], $annee);
					} else {
						// single token provided: use it as prenom and fallback nom to 'Inconnu'
						$found = new Author(null, $parts[0], 'Inconnu', $annee);
					}
				} else {
					// no author info provided: create a generic placeholder author
					$found = new Author(null, 'Inconnu', 'Inconnu', null);
				}
			} catch (\InvalidArgumentException $ex) {
				// convert constructor errors to validation errors
				$erreur['auteur'] = $ex->getMessage();
				// stop processing further
				$found = null;
			}
		}

		// ensure created authors get an id and are inserted into DB
		if ($found->getId() === null) {
			$birthDate = $found->getAnneeNaissance() ? $found->getAnneeNaissance() . '-01-01' : null;
			$idAuteur = db_insert_author($found->getPrenom(), $found->getNom(), $birthDate);
			$reflection = new ReflectionClass($found);
			$prop = $reflection->getProperty('id');
			$prop->setAccessible(true);
			$prop->setValue($found, $idAuteur);
			$authors[] = $found;
		} else {
			$idAuteur = $found->getId();
		}

		// insert citation into DB
		$dateCitation = $d->format('Y-m-d');
		db_insert_citation($login, $texte, $dateCitation, $idAuteur);

		// Success message
		$success = "Citation ajoutée avec succès.";

		// Reset form
		$login = $texte = $auteurInput = $dateInput = '';
	}
	}
else {
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
				<?php if (!empty($success)) echo "<p style='color:green;'>$success</p>"; ?>
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

				<?php if (!empty($showAll) && $showAll): ?>
					<section>
						<h2>Liste des auteurs et de leurs citations</h2>
						<?php if (empty($authors)): ?>
							<p>Aucun auteur enregistré.</p>
						<?php else: ?>
							<?php foreach ($authors as $author): ?>
								<article>
									<header>
										<h3><?= htmlspecialchars($author->getPrenom() . ' ' . $author->getNom(), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?> <?php if ($author->getAnneeNaissance()) echo '('.htmlspecialchars((string)$author->getAnneeNaissance(), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8').')'; ?></h3>
									</header>
									<?php $cits = $author->getCitations(); if (empty($cits)): ?>
										<p><em>Aucune citation</em></p>
									<?php else: ?>
										<?php foreach ($cits as $cit): ?>
											<blockquote><?= nl2br(htmlspecialchars($cit->getTexte(), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8')) ?>
												<div style="font-size:.9em;color:#666">Le <?= htmlspecialchars($cit->getDateAjout()->format('Y-m-d'), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></div>
											</blockquote>
										<?php endforeach; ?>
									<?php endif; ?>
								</article>
							<?php endforeach; ?>
						<?php endif; ?>
					</section>
				<?php endif; ?>
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
