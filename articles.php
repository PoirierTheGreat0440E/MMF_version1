<!DOCTYPE html>
<html>

	<head>
		<title>MMF / Liste des articles</title>
		<meta charset="utf-8"/>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<link rel="stylesheet" type="text/css" href="PapierDuStyle1.css">
	</head>

	<?php 

		function afficher_article($titre,$image,$contenu){
			echo("<div class='articles'>
				<b style='font-size:20px;'>$titre</b><br/>
				<div style='width:100%; background-color:black;display:flex;flex-direction:row;justify-content:center;'>
				<img src=$image style='border:1px black dotted;width:40%;'></img>
				</div>
				<p>$contenu</p>
				</div>");
		}

	?>

	<body>
		<p>La liste des articles</p>

		<div class="liste_articles">
			
			<?php
			afficher_article("Quelque chose d'important vient de se dérouler.","troll1.jpg","Aujourd'hui nous avons mangé énormément de nourritures.");
		 	afficher_article("Bonjour mdr","aaa","Prout Prout");
		 	?>

		</div>

	</body>

</html>