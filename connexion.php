<?php session_start(); ?>
<!DOCTYPE html>
<html>


<head>
	<title>MMF / Liste d'utilisateurs</title>
	<meta charset="utf-8"/>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="connexion.css">
</head>

<?php

function detection_deconnexion(){
	if ( !empty($_POST["deconnexion_valeur"]) ){
		//afficher_remarque($_POST["deconnexion_valeur"]);
		$_SESSION["connected"] = "NON";
		$_SESSION["user_id"] = 0;
	}
}

function afficher_remarque(string $message){
		echo("<div style='font-family:Arial Narrow;border-radius:4px;border:1px black solid;background-color:rgb(30,30,160);color:white;padding:3px;margin:3px;font-size:17px;width:30%;'><p style='margin:0 auto;'><b>INFO : </b>$message</p></div>");
}

function afficher_erreur(string $message){
		echo("<div style='font-family:Arial Narrow;border-radius:4px;border:1px black solid;background-color:rgb(160,30,30);color:white;padding:3px;margin:3px;font-size:17px;width:30%;'><p style='margin:0 auto;'><b>ERREUR : </b>$message</p></div>");
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

function preparer_requete_connexion($connection_bdd){
	$requete = "SELECT utilisateur_id,utilisateur_mot_de_passe FROM mmf_ver1_utilisateurs WHERE utilisateur_nom = ?";
	$preparation = mysqli_prepare($connection_bdd[0],$requete);
	if ( $preparation ){
		//afficher_remarque("Préparation de la requête réussie.");
		return $preparation;
	} else {
		//afficher_erreur("Préparation de la requête échouée.");
		return null;
	}
}

function executer_requete_connexion($preparation,$utilisateurNom,$utilisateurMotDePasse){
	$prep1 = mysqli_stmt_bind_param($preparation,"s",$utilisateurNom);
	if (!$prep1){
		//echo("Liaison des paramètres échouée !");
		return 0;
	} else {
		$ok =  mysqli_stmt_bind_result($preparation,$id_lecture,$mdp_lecture);
		$resultat = mysqli_stmt_execute($preparation);
		mysqli_stmt_store_result($preparation);
		if ( $resultat ){
			$nombre_ligne = mysqli_stmt_num_rows($preparation);
			if ( $nombre_ligne > 1 ){
				//afficher_erreur("Plus d'un utilisateur est enregistré à ce nom d'utilisateur.");
				return 3;
			} elseif ( $nombre_ligne == 0 ){
				//afficher_erreur("Aucun utilisateur est enregistré à ce nom d'utilisateur.");
				return 3;
			} else {
				//echo("Un utilisateur est détecté !");
				// On vérifie si le mot de passe est correcte.
				while(mysqli_stmt_fetch($preparation)){
					//afficher_remarque($id_lecture);
					//afficher_remarque($mdp_lecture);
					if ( $mdp_lecture == $utilisateurMotDePasse ){
						//echo("Les mots de passe sont identiques !");
						$_SESSION["connected"] = "OUI";
						$_SESSION["user_id"] = $id_lecture;
						return 12;
					} else {
						//afficher_erreur("Les mots de passe sont différents !");
						return 13;
					}
				}
			}	
		} else {
			//echo("Execution de la requete de connexion échouée !");
			return 4;
		}
	}
}

// -------------------------------

$array_connection = connexion_base_de_donnees("mmf_ver1");

$requete_connexion = preparer_requete_connexion($array_connection);

if ( isset($_POST["connexionNomDUtilisateur"]) ){
	$nom = $_POST["connexionNomDUtilisateur"];
	$motdepasse = $_POST["connexionMotDePasse"];
	$resultat = executer_requete_connexion($requete_connexion,$nom,$motdepasse);
	if ( $resultat == 12 ){
		//afficher_remarque("Tous s'est bien passé mdr.");
	}
} else {
	//afficher_remarque(" Donnez un nom d'utilisateur SVP ");
}

detection_deconnexion();

afficher_information_session();

?>

<body>

<nav id="barreNavigation">
	<a href="presentation.php"><p>Home Page</p></a>
	<a href="articles.php"><p>Articles/Forum</p></a>
	<a href="utilisateurs.php"><p>Users</p></a>
	<?php if ($_SESSION["connected"] == "OUI"): ?>
		<form method="POST" action="connexion.php"><input type="hidden" name="deconnexion_valeur" value="ActivationDeconnection"/><input type="submit" name="deconnection" value="Log off" id="log_off_button"/></form>
	<?php else: ?>
	<a href="enregistrement.php"><p>Register</p></a>
	<a href="connexion.php"><p>Log in</p></a>
	<?php endif; ?>
</nav>

<form method="POST" id="formulaire_connexion" action="connexion.php">
	
	<p>Nom d'utilisateur :</p>
	<input type="text" name="connexionNomDUtilisateur"/>
	<p>Mot de passe :</p>
	<input type="password" name="connexionMotDePasse"/>

	<input type="submit" name="connexionActivation" id="bouton_connexion"/>

</form>


</body>

</html>