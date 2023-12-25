<!DOCTYPE html>
<html>

	<head>
		<title>MMF / Liste des articles</title>
		<meta charset="utf-8"/>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<link href="PapierDuStyle1.css" rel="stylesheet" type="text/css">
	</head>

	<?php 

		function afficher_article($titre,$image,$contenu){
			echo("<div style='border:1px black solid;margin:4px;padding:4px;width:40%;margin:0 auto;font-family:Arial Narrow;display:flex;flex-direction:column;'>
				<b style='font-size:20px;'>$titre</b><br/>
				<div style='width:100%; background-color:black;display:flex;flex-direction:row;justify-content:center;'>
				<img src='troll1.jpg' style='border:1px black dotted;width:40%;'></img>
				</div>
				<p>$contenu</p>
				</div>");
		}

	?>

	<body>
		<p>La liste des articles</p>
		<?php

			afficher_article("Quelque chose d'important vient de se dérouler.","eee","Aujourd'hui nous avons mangé énormément de nourritures.");

		 ?>
	</body>

</html>