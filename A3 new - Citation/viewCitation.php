<!DOCTYPE html >
<html lang="fr">
	<head>
		<title>Citation du jour</title>
		<meta charset="UTF-8">
		 <link rel = "icon" href = "favicon.png" type = "image/x-icon"> 
	</head>
	<body>
        <main>
			<article>
				<?php
				
				function main() {
					// Create Authors
					$author1 = new Author("Albert", "Einstein", 1879);
					$author2 = new Author("Marie", "Curie", 1867);
					
					// Create Citations
					$citation1 = new Citation("Life is like riding a bicycle. To keep your balance you must keep moving.", $author1, new DateTime('1930-05-01'));
					$citation2 = new Citation("Nothing in life is to be feared, it is only to be understood.", $author1, new DateTime('1929-06-01'));
					$citation3 = new Citation("Nothing in life is to be feared, it is only to be understood.", $author2, new DateTime('1911-04-01'));

					// Display Citations
					echo $citation1->getCitation() . "\n";
					echo $citation2->getCitation() . "\n";
					echo $citation3->getCitation() . "\n";
				}
				main();

				if(isset($_POST['login'])){
					foreach ($_POST as $key => $value) {
						$_POST[$key]=htmlentities(stripcslashes($value));
					}
					$login=$_POST['login'];
					$citation=$_POST['citation'];
					$auteur=$_POST['auteur'];
					$date=$_POST['date'];
					echo "<header><h1>Citation du jour</h1></header>";
					echo "<p>$citation</p>
					<b>$auteur</b>
					proposée par $login le <time>$date</time> ";				
				}
				else
				{				
					echo "<header><h1>Pas de citation</h1></header>";
				}
				?>
			<article>
        </main>
  </body>
</html>
