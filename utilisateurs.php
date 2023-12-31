<!DOCTYPE html>
<html>

<head>
	<title>MMF / Liste d'utilisateurs</title>
	<meta charset="utf-8"/>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="utilisateurs.css">
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

function afficher_utilisateur($utilisateurNom,$utilisateurPhotoDeProfil,$utilisateurDescription){
	echo("
	<article class='utilisateur'>
	<div class='utilisateur_profil'>
		<img src=$utilisateurPhotoDeProfil class='utilisateur_photo_de_profil'/>
		<p class='utilisateur_nom'>$utilisateurNom</p>
	</div>
	<p class='utilisateur_description'>$utilisateurDescription</p>
	</article>"
	);
}

function lecture_utilisateurs($connection_bdd){
	$requete = "SELECT * FROM mmf_ver1_utilisateurs";
	$lecture = mysqli_query($connection_bdd[0],$requete);
	if ($lecture) {
		return $lecture;
	} else {
		return null;
	}
}

function afficher_erreur(string $message){
		echo("<div style='font-family:Arial Narrow;border-radius:4px;border:1px black solid;background-color:rgb(160,30,30);color:white;padding:3px;margin:3px;font-size:17px;width:30%;'><p style='margin:0 auto;'><b>ERREUR : </b>$message</p></div>");
}

	function afficher_remarque(string $message){
		echo("<div style='font-family:Arial Narrow;border-radius:4px;border:1px black solid;background-color:rgb(30,30,160);color:white;padding:3px;margin:3px;font-size:17px;width:30%;'><p style='margin:0 auto;'><b>INFO : </b>$message</p></div>");
}

$array_connection = connexion_base_de_donnees("mmf_ver1");
$resultat = lecture_utilisateurs($array_connection);

?>

<body>

<nav id="barreNavigation">
	<a href="presentation.php"><p>Home Page</p></a>
	<a href="articles.php"><p>Articles/Forum</p></a>
	<a href="utilisateurs.php"><p>Users</p></a>
	<a href="enregistrement.php"><p>Register</p></a>
	<a href="connexion.php"><p>Log in</p></a>
</nav>

<div id="liste_utilisateurs">
	
<?php 

while ( $curseur = mysqli_fetch_assoc($resultat) ){
	afficher_utilisateur($curseur["utilisateur_nom"],$curseur["utilisateur_photo_de_profile"],$curseur["utilisateur_description"]);
}

?>

</div>

</body>

</html>