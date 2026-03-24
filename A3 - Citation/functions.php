<?php
	function display($citation,$auteur,$login,$date){
		echo "<article><header><h1>Citation enregistré</h1></header>";
		echo "<p>$citation</p>
			<b>$auteur</b>
			proposée par $login le <time>$date</time></article> ";	
	}

?>