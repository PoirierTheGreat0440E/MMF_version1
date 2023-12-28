<!DOCTYPE html>
<html>

<?php

		// function pour se connecter à phpMyAdmin et se connecter à une base de données.
		function connexion_base_de_donnees(string $nom_bdd, string $nom_hote = "localhost", string $nom_utilisateur = "root", string $mot_de_passe = "") :?array {
			// On essaye d'abord de se connecter à phpMyAdmin...
			$etape1 = mysqli_connect($nom_hote,$nom_utilisateur,$mot_de_passe);
			if ( $etape1 ){
				//afficher_remarque("Connection à phpMyAdmin réussie.");
			} else {
				//afficher_erreur("Connection à phpMyAdmin échouée.");
				return null;
			}
			// On essaye de rejoindre la base de données donnée en paramètre...
			$etape2 = mysqli_select_db($etape1,$nom_bdd);
			if ( $etape2 ){
				//afficher_remarque("Accès à la base de données réussie.");
			} else {
				//afficher_erreur("Accès à la base de données échouée.");
				return null;
			}
			// Si les deux étapes ont été réalisées avec succès, on crée un array contenant toutes les étapes.
			//($etape1 and $etape2) ? return array($etape1,$etape2);
			return array($etape1,$etape2);
		}


		// Sous-fonction pour lire les informations de l'article en focus...
		function lecture_article_focus($connection_bdd,$articleId){
			$requete = "SELECT * FROM mmf_ver1_articles WHERE article_id = $articleId";
			$test1 = mysqli_query($connection_bdd,$requete);
			if (!$test1){
				//afficher_erreur("Requête de lecture des articles échouée.");
				return null;
			} else {
				//afficher_remarque("Requête de lecture des articles réussie.");
				return $test1;
			}
		}

		if (isset($_GET["id"])){
			$array_connection = connexion_base_de_donnees("mmf_ver1");
			$lecture = lecture_article_focus($array_connection[0],$_GET["id"]);
			while($curseur = mysqli_fetch_assoc($lecture)){
				echo($curseur["article_titre"]);
			}
		} else {
			echo("Aucune id détectée.");
		}

?>

<head>
	<title>MMF / Article FOCUS</title>
	<meta charset="utf-8"/>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="style_article_focus.css">
</head>

<body>
	
</body>

</html>