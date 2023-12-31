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
	$requete = "SELECT utilisateur_mot_de_passe FROM mmf_ver1_utilisateurs WHERE utilisateur_nom = ?";
	$preparation = mysqli_prepare($connection_bdd[0],$requete);
	if ( $preparation ){
		return $preparation;
	} else {
		return null;
	}
}

function executer_requete_connexion($preparation,$utilisateurNom,$utilisateurMotDePasse){
	$prep1 = mysqli_stmt_bind_param($preparation,"s",$utilisateurNom);
	if (!$prep1){
		//echo("Liaison des paramètres échouée !");
		return 0;
	} else {
		$ok =  mysqli_stmt_bind_result($preparation,$mdp_lecture);
		$resultat = mysqli_stmt_execute($preparation);
		mysqli_stmt_store_result($preparation);
		if ( $resultat ){
			$nombre_ligne = mysqli_stmt_num_rows($preparation);
			if ( $nombre_ligne > 1 ){
				//echo("Plus d'un utilisateur est enregistré à ce nom d'utilisateur.");
				return 3;
			} elseif ( $nombre_ligne == 0 ){
				//echo("Aucun utilisateur est enregistré à ce nom d'utilisateur.");
				return 3;
			} else {
				echo("Un utilisateur est détecté !");
				// On vérifie si le mot de passe est correcte.
				while(mysqli_stmt_fetch($preparation)){
					if ( $mdp_lecture == $utilisateurMotDePasse ){
						echo("Les mots de passe sont identiques !");
						return 12;
					} else {
						echo("Les mots de passe sont différents !");
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
	echo($resultat);
} else {
	echo(" Donnez un nom d'utilisateur SVP ");
}

?>

<body>

<nav id="barreNavigation">
	<a href="presentation.php"><p>Home Page</p></a>
	<a href="articles.php"><p>Articles/Forum</p></a>
	<a href="utilisateurs.php"><p>Users</p></a>
	<a href="enregistrement.php"><p>Register</p></a>
	<a href="connexion.php"><p>Log in</p></a>
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