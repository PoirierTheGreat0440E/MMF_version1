<?php session_start(); ?>
<!DOCTYPE html>
<html>

	<head>
		<title>MMF / Liste des articles</title>
		<meta charset="utf-8"/>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<link rel="stylesheet" href="style_articles.css">
	</head>

	<?php

// Sous-fonction pour obtenir les informations d'un utilisateur à partir de son id.
	function informations_utilisateur($connection_bdd,$id){
		$requete = "SELECT utilisateur_nom,utilisateur_photo_de_profile FROM mmf_ver1_utilisateurs WHERE utilisateur_id = ?";
		$preparation = mysqli_prepare($connection_bdd[0],$requete);
		if (!$preparation){
			//afficher_erreur("Préparation de la requête échouée.");
			return null;
		} else {
			//afficher_remarque("Préparation de la requête réussie.");
			$ok0 = mysqli_stmt_bind_param($preparation,"i",$id);
			//(!$ok0) ? afficher_erreur("Liaison des Paramètres échouée") : afficher_remarque("OK0");
			$ok1 = mysqli_stmt_bind_result($preparation,$nom_lecture,$photo_lecture);
			//(!$ok1) ? afficher_erreur("Liaison des résultats échouée") : afficher_remarque("OK1");
			$ok3 = mysqli_stmt_execute($preparation);
			//(!$ok3) ? afficher_erreur("Execution de la requête échouée") : afficher_remarque("OK2");
			$ok2 = mysqli_stmt_store_result($preparation);
			//(!$ok2) ? afficher_erreur("Conservation des résultats échouée") : afficher_remarque("OK3");
			while (mysqli_stmt_fetch($preparation)){
				$informations = array($nom_lecture,$photo_lecture);
				return $informations;
			}
		}
	}

function detection_deconnexion(){
	if ( !empty($_POST["deconnexion_valeur"]) ){
		//afficher_remarque($_POST["deconnexion_valeur"]);
		$_SESSION["connected"] = "NON";
		$_SESSION["user_id"] = 0;
	} else {
		if (!isset($_SESSION["connected"]) or empty($_SESSION["connected"])){
			$_SESSION["connected"] = "NON";
			$_SESSION["user_id"] = 0;
		}
	}
}

function afficher_information_session(){
	afficher_remarque($_SESSION["user_id"]);
	afficher_remarque($_SESSION["connected"]);
}

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

		function afficher_erreur(string $message){
			echo("<div style='font-family:Arial Narrow;border-radius:4px;border:1px black solid;background-color:rgb(160,30,30);color:white;padding:3px;margin:3px;font-size:17px;width:30%;'><p style='margin:0 auto;'><b>ERREUR : </b>$message</p></div>");
		}

		function afficher_remarque(string $message){
			echo("<div style='font-family:Arial Narrow;border-radius:4px;border:1px black solid;background-color:rgb(30,30,160);color:white;padding:3px;margin:3px;font-size:17px;width:30%;'><p style='margin:0 auto;'><b>INFO : </b>$message</p></div>");
		} 

		function afficher_article($titre,$image,$contenu,$id){
			echo("<div class='articles'>
				<b style='font-size:20px;'>$titre</b><br/>
				<div style='width:100%; background-color:black;display:flex;flex-direction:row;justify-content:center;'>
				<img src=$image style='border:1px black dotted;width:80%;'></img>
				</div>
				<a href='article_focus.php?id=$id'><p class='bouton_commentaire'>Commenter</p></a>
				<i>$image</i>
				<p>$contenu</p>
				</div>");
		}

		function afficher_article_sans_image($titre,$contenu,$id){
			echo("<div class='articles'>
				<b style='font-size:20px;'>$titre</b><br/>
				<div style='width:100%; background-color:black;display:flex;flex-direction:row;justify-content:center;'>
				</div>
				<a href='article_focus.php?id=$id'><p class='bouton_commentaire'>Commenter</p></a>
				<p>$contenu</p>
				</div>");
		}

		function afficher_info_envoyeur_miniature($envoyeurNom = "?aucun_envoyeur?",$envoyeurPhotoDeProfile = "profile_pictures/no_sender.jpg",$dateEnvoi = "?aucune_date?"){
			echo("
				<div style='display:flex;flex-direction:row;width:90%;margin:0 4px;align-items:center;background-color:white;padding:4px;margin:0 auto;margin-bottom:4px;border-radius:5px;'>
				<img style='width:4%;height:4%;border:2px black solid;border-radius:5px;' src='$envoyeurPhotoDeProfile'/><b style='margin-left:15px;'>$envoyeurNom</b>
				<i style='margin-left:15px;'>$dateEnvoi</i>
				</div>
				");
		}

		$array_connection = connexion_base_de_donnees("mmf_ver1");
		$lecture = lecture_article($array_connection[0]);

		detection_deconnexion();

		//afficher_information_session();

	?>

	<body>

	<nav id="barreNavigation">
	<a href="presentation.php"><p>Home Page</p></a>
	<a href="articles.php"><p>Articles/Forum</p></a>
	<a href="utilisateurs.php"><p>Users</p></a>
	<?php if ($_SESSION["connected"] == "OUI"): ?>
		<a href="main.php"><p>Post an article</p></a>
		<form method="POST" action="articles.php"><input type="hidden" name="deconnexion_valeur" value="ActivationDeconnection"/><input type="submit" name="deconnection" value="Log off" id="log_off_button"/></form>
	<?php else: ?>
	<a href="enregistrement.php"><p>Register</p></a>
	<a href="connexion.php"><p>Log in</p></a>
	<?php endif; ?>
	</nav>

		<div class="liste_articles">
			
			<?php
			//afficher_article("Quelque chose d'important vient de se dérouler.","uploads/wow.jpg","Aujourd'hui nous avons mangé énormément de nourritures.");
		 	//afficher_article("Bonjour mdr","aaa","Prout Prout");
		 	while ($curseur = mysqli_fetch_assoc($lecture)){
		 		$infos_envoyeur = informations_utilisateur($array_connection,$curseur["article_id_envoyeur"]);
		 		afficher_info_envoyeur_miniature($infos_envoyeur[0],$infos_envoyeur[1],$curseur["article_date_envoi"]);
		 		if ( trim($curseur["article_image"]) == "uploads/" ){
		 			afficher_article_sans_image($curseur["article_titre"],$curseur["article_contenu"],$curseur["article_id"]);
		 		} else {
		 			afficher_article($curseur["article_titre"],$curseur["article_image"],$curseur["article_contenu"],$curseur["article_id"]);
		 		}
			}
		 	?>

		</div>

	</body>

</html>