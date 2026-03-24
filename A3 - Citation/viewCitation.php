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
