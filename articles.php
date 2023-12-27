<!DOCTYPE html>
<html>

	<head>
		<title>MMF / Liste des articles</title>
		<meta charset="utf-8"/>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<link rel="stylesheet" type="text/css" href="PapierDuStyle1.css">
	</head>

	<?php

		// function pour se connecter à phpMyAdmin et se connecter à une base de données.
		function connexion_base_de_donnees(string $nom_bdd, string $nom_hote = "localhost", string $nom_utilisateur = "root", string $mot_de_passe = "") :?array {
			// On essaye d'abord de se connecter à phpMyAdmin...
			$etape1 = mysqli_connect($nom_hote,$nom_utilisateur,$mot_de_passe);
			if ( $etape1 ){
				afficher_remarque("Connection à phpMyAdmin réussie.");
			} else {
				afficher_erreur("Connection à phpMyAdmin échouée.");
				return null;
			}
			// On essaye de rejoindre la base de données donnée en paramètre...
			$etape2 = mysqli_select_db($etape1,$nom_bdd);
			if ( $etape2 ){
				afficher_remarque("Accès à la base de données réussie.");
			} else {
				afficher_erreur("Accès à la base de données échouée.");
				return null;
			}
			// Si les deux étapes ont été réalisées avec succès, on crée un array contenant toutes les étapes.
			//($etape1 and $etape2) ? return array($etape1,$etape2);
			return array($etape1,$etape2);
		}

		// Sous-fonction pour lire les articles présents dans la table des articles...
		function lecture_article($connection_bdd){
			$requete = "SELECT * FROM mmf_ver1_articles";
			$test1 = mysqli_query($connection_bdd,$requete);
			if (!$test1){
				afficher_erreur("Requête de lecture des articles échouée.");
				return null;
			} else {
				afficher_remarque("Requête de lecture des articles réussie.");
				return $test1;
			}
		}

		function afficher_erreur(string $message){
			echo("<div style='font-family:Arial Narrow;border-radius:4px;border:1px black solid;background-color:rgb(160,30,30);color:white;padding:3px;margin:3px;font-size:17px;width:30%;'><p style='margin:0 auto;'><b>ERREUR : </b>$message</p></div>");
		}

		function afficher_remarque(string $message){
			echo("<div style='font-family:Arial Narrow;border-radius:4px;border:1px black solid;background-color:rgb(30,30,160);color:white;padding:3px;margin:3px;font-size:17px;width:30%;'><p style='margin:0 auto;'><b>INFO : </b>$message</p></div>");
		} 

		function afficher_article($titre,$image,$contenu){
			echo("<div class='articles'>
				<b style='font-size:20px;'>$titre</b><br/>
				<div style='width:100%; background-color:black;display:flex;flex-direction:row;justify-content:center;'>
				<img src=$image style='border:1px black dotted;width:40%;'></img>
				</div>
				<i>$image</i>
				<p>$contenu</p>
				</div>");
		}

		$array_connection = connexion_base_de_donnees("mmf_ver1");
		$lecture = lecture_article($array_connection[0]);

	?>

	<body>
		<p>La liste des articles</p>

		<div class="liste_articles">
			
			<?php
			//afficher_article("Quelque chose d'important vient de se dérouler.","uploads/wow.jpg","Aujourd'hui nous avons mangé énormément de nourritures.");
		 	//afficher_article("Bonjour mdr","aaa","Prout Prout");
		 	while ($curseur = mysqli_fetch_assoc($lecture)){
				afficher_article($curseur["article_titre"],$curseur["article_image"],$curseur["article_contenu"]);
			}
		 	?>

		</div>

	</body>

</html>