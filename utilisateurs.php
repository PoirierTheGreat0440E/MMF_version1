<?php session_start(); require_once("outils_communs.php"); ?>
<!DOCTYPE html>
<html>

<head>
	<title>MMF / Liste d'utilisateurs</title>
	<meta charset="utf-8"/>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="utilisateurs.css">
	<link rel="stylesheet" href="style_navigation_et_barre.css">
</head>

<?php

function lecture_utilisateurs($connection_bdd){
	$requete = "SELECT * FROM mmf_ver1_utilisateurs";
	$lecture = mysqli_query($connection_bdd[0],$requete);
	if ($lecture) {
		return $lecture;
	} else {
		return null;
	}
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

detection_deconnexion();
$array_connection = connexion_base_de_donnees("mmf_ver1");
$resultat = lecture_utilisateurs($array_connection);

?>

<body>

<?php barre_navigation("utilisateurs.php"); ?>

<div id="liste_utilisateurs">
	
<?php 

while ( $curseur = mysqli_fetch_assoc($resultat) ){
	afficher_utilisateur($curseur["utilisateur_nom"],$curseur["utilisateur_photo_de_profile"],$curseur["utilisateur_description"]);
}

?>

</div>

</body>

</html>