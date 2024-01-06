<?php

function barre_navigation($url="#"){
	if ( ($_SESSION["connected"] == "NON") or ($_SESSION["user_id"] == 0) ){
		echo("<nav id='navigation_bar1'>
			<img id='navigation_bar1_image' src='image_logo.png'/>
			<a href='index.php'><p>Accueil</p></a>
			<a href='articles.php'><p>Articles</p></a>
			<a href='utilisateurs.php'><p>Utilisateurs</p></a>
			<a href='enregistrement.php'><p>S'enregistrer</p></a>
			<a href='connexion.php'><p>Se connecter</p></a>
			</nav>");
	} else {
		echo("<nav id='navigation_bar1'>
			<img id='navigation_bar1_image' src='image_logo.png'/>
			<a href='index.php'><p>Accueil</p></a>
			<a href='articles.php'><p>Articles</p></a>
			<a href='main.php'><p>Publier</p></a>
			<a href='utilisateurs.php'><p>Utilisateurs</p></a>
			<form method='POST' action=$url><input type='hidden' name='deconnexion_valeur' value='ActivationDeconnection'/><input type='submit' name='deconnection' value='Log off' id='log_off_button'/></form>
			</nav>");
	}
}

// --------------------------------------------------

function article($titre = "!!! Titre ici !!!",$image="uploads/",$date="!!!date!!!",$contenu="!!! Contenu ici !!!",$id){
	if ( trim($image) == "uploads/" ){
		echo("<article class='post'>
			<b class='post_titre'>$titre</b>
			<i>$date</i>
			<a class='post_commenter' href='article_focus.php?id=$id'><p>Commenter</p></a>
			<p>$contenu</p>
			</article>");
	}else{
		echo("<article class='post'>
			<b class='post_titre'>$titre</b>
			<div class='post_image'><img src='$image'/></div>
			<i>$date</i>
			<a class='post_commenter' href='article_focus.php?id=$id'><p>Commenter</p></a>
			<p>$contenu</p>
			</article>");
	}
}

// --------------------------------------------------

function afficher_info_envoyeur_miniature($envoyeurNom = "?aucun_envoyeur?",$envoyeurPhotoDeProfile = "profile_pictures/no_sender.jpg",$dateEnvoi = "?aucune_date?"){
	echo("
		<div class='informations_utilisateurs_general'>
		<img class='informations_utilisateurs_miniature' src='$envoyeurPhotoDeProfile'/><b style='margin-left:15px;'>$envoyeurNom</b>
		<i style='margin-left:15px;'>$dateEnvoi</i>
		</div>
		");
}

// --------------------------------------------------

function afficher_erreur(string $message){
	echo("<div style='font-family:Arial Narrow;border-radius:4px;border:1px black solid;background-color:rgb(160,30,30);color:white;padding:3px;margin:3px;font-size:17px;width:30%;'><p style='margin:0 auto;'><b>ERREUR : </b>$message</p></div>");
}

// --------------------------------------------------

function afficher_remarque(string $message){
	echo("<div style='font-family:Arial Narrow;border-radius:4px;border:1px black solid;background-color:rgb(30,30,160);color:white;padding:3px;margin:3px;font-size:17px;width:30%;'><p style='margin:0 auto;'><b>INFO : </b>$message</p></div>");
}

// --------------------------------------------------

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

// --------------------------------------------------

function afficher_information_session(){
	afficher_remarque($_SESSION["user_id"]);
	afficher_remarque($_SESSION["connected"]);
}

// --------------------------------------------------

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

// --------------------------------------------------

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

?>