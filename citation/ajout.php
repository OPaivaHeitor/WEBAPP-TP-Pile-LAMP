<!DOCTYPE html >
<html lang="fr">
	<head>
		<title>ajout de citation </title>
		<meta charset="UTF-8">
	</head>
	<body>
		<main>
			<article>
				<header><h1>Formulaire de création de citations</h1></header>
								<?php
								// Load bootstrap to provide a datalist of existing authors
								require_once __DIR__ . '/bootstrap.php';
								$data = main();
								$existingAuthors = $data['authors'];
								?>

								<form method="post" name="FrameCitation" action="ajoutCtrl.php">
				  <table border="1" bgcolor="#ccccff" frame="above">
					<tbody>
						<tr>
							<th><label for="login">Login</label></th> 
							<td><input name="login" maxlength="64" size="32"></td>
						</tr>
						<tr>
							<th><label for="citation">Citation</label></th>
							<td><textarea cols="128" rows="5" name="citation"></textarea></td>
						</tr>
						<tr>
							<th><label for="auteur">Auteur</label></th>
							<td>
								<input list="authorsList" name="auteur" maxlength="128" size="64" placeholder="Prénom | Nom [année]">
								<datalist id="authorsList">
									<?php foreach ($existingAuthors as $auth): ?>
										<?php $label = htmlspecialchars($auth->getPrenom() . ' | ' . $auth->getNom() . ($auth->getAnneeNaissance() ? ' [' . $auth->getAnneeNaissance() . ']' : '')) ?>
										<option value="<?= $label ?>"></option>
									<?php endforeach; ?>
								</datalist>
							</td>
						</tr>
						<tr>
							<th><label for="date">Date</label></th>
							<td><input id="datePicker" name="date" type="date"></td>
						</tr>
						<tr>
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