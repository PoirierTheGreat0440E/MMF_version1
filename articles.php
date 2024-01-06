<?php session_start(); require_once("outils_communs.php"); ?>
<!DOCTYPE html>
<html>

	<head>
		<title>MMF / Liste des articles</title>
		<meta charset="utf-8"/>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<link rel="stylesheet" href="style_navigation_et_barre.css">
	</head>

	<?php


		// Sous-fonction pour lire les articles présents dans la table des articles...
		function lecture_article($connection_bdd){
			$requete = "SELECT * FROM mmf_ver1_articles";
			$test1 = mysqli_query($connection_bdd,$requete);
			if (!$test1){
				//afficher_erreur("Requête de lecture des articles échouée.");
				return null;
			} else {
				//afficher_remarque("Requête de lecture des articles réussie.");
				return $test1;
			}
		} 


		$array_connection = connexion_base_de_donnees("mmf_ver1");
		$lecture = lecture_article($array_connection[0]);

		detection_deconnexion();

	?>

	<body>

		<?php barre_navigation("articles.php"); ?>

		<div id="liste_articles1">
			
			<?php

		 	while ($curseur = mysqli_fetch_assoc($lecture)){

		 		$infos_envoyeur = informations_utilisateur($array_connection,$curseur["article_id_envoyeur"]);

		 		afficher_info_envoyeur_miniature($infos_envoyeur[0],$infos_envoyeur[1],$curseur["article_date_envoi"]);
		 		
		 		article($curseur["article_titre"],$curseur["article_image"],$curseur["article_date_envoi"],$curseur["article_contenu"],$curseur["article_id"]);
			}
		 	?>

		</div>

	</body>

</html>