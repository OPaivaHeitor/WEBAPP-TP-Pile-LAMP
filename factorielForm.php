<!DOCTYPE html>
<html lang="fr">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width,initial-scale=1">
	<title>Calcul du factoriel</title>
	<style>
		body { font-family: Arial, Helvetica, sans-serif; padding: 1rem; }
		form { max-width: 420px; background:#f7f7f7; padding:1rem; border-radius:6px }
		label { display:block; margin-bottom:.5rem }
		input[type="number"] { width:100%; padding:.5rem; font-size:1rem }
		.error { color: #a33 }
		.result { margin-top:1rem; padding:.75rem; background:#e9ffe9; border:1px solid #b6ebb6 }
	</style>
</head>
<body>
	<h1>Calcul du factoriel</h1>

	<?php
	$error = '';
	$result = null;

	if ($_SERVER['REQUEST_METHOD'] === 'POST') {
		// read and sanitize
		$raw = isset($_POST['n']) ? trim($_POST['n']) : '';

		// basic validation: non-empty, integer, >= 0 and <= 20 (prevent huge results)
		if ($raw === '') {
			$error = 'Veuillez saisir un nombre.';
		} elseif (!preg_match('/^-?\d+$/', $raw)) {
			$error = 'Le nombre doit être un entier.';
		} else {
			$n = (int)$raw;
			if ($n < 0) {
				$error = 'Veuillez saisir un entier positif ou zéro.';
			} elseif ($n > 20) {
				$error = 'Le nombre est trop grand (max 20) pour un affichage sûr.';
			} else {
				// compute factorial iteratively
				$factorial = 1;
				for ($i = 1; $i <= $n; $i++) {
					$factorial *= $i;
				}
				$result = "$n ! = $factorial";
			}
		}
	}
	?>

	<?php if ($error): ?>
		<p class="error"><?= htmlspecialchars($error, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></p>
	<?php endif; ?>

	<form method="post" action="<?= htmlspecialchars($_SERVER['PHP_SELF'], ENT_QUOTES, 'UTF-8') ?>">
		<label for="n">Entier (0–20) :</label>
		<input id="n" name="n" type="number" min="0" max="20" step="1" inputmode="numeric" value="<?= isset($_POST['n']) ? htmlspecialchars($_POST['n'], ENT_QUOTES, 'UTF-8') : '' ?>" required>
		<div style="margin-top:.75rem">
			<button type="submit">Calculer</button>
			<a style="margin-left:1rem" href="factorielUrl.php?n=5">Exemple via URL (n=5)</a>
		</div>
	</form>

	<?php if ($result !== null): ?>
		<div class="result"><?= htmlspecialchars($result, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></div>
	<?php endif; ?>

	<hr>
	<p>Affichez aussi la <a href="date.php">page de date</a> fournie dans le projet.</p>

</body>
</html>

