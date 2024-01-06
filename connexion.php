<?php session_start(); require_once("outils_communs.php"); ?>
<!DOCTYPE html>
<html>


<head>
	<title>MMF / Liste d'utilisateurs</title>
	<meta charset="utf-8"/>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="connexion.css">
	<link rel="stylesheet" href="style_navigation_et_barre.css">
</head>

<?php

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
		header("Location:articles.php");
	}
} else {
	//afficher_remarque(" Donnez un nom d'utilisateur SVP ");
}

detection_deconnexion();


?>

<body>

<?php barre_navigation("connexion.php"); ?>

<form method="POST" id="formulaire_connexion" action="connexion.php">
	
	<p>Nom d'utilisateur :</p>
	<input type="text" name="connexionNomDUtilisateur"/>
	<p>Mot de passe :</p>
	<input type="password" name="connexionMotDePasse"/>

	<input type="submit" name="connexionActivation" id="bouton_connexion"/>

</form>


</body>

</html>