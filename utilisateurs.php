<!DOCTYPE html>
<html>

<head>
	<title>MMF / Liste d'utilisateurs</title>
	<meta charset="utf-8"/>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="utilisateurs.css">
</head>

<?php

function afficher_commentaire($utilisateurNom,$utilisateurPhotoDeProfil,$utilisateurDescription){
	echo("
	<article class='utilisateur'>
	<div class='utilisateur_profil'>
		<img src=$utilisateurPhotoDeProfil class='utilisateur_photo_de_profil'/>
		<p>$utilisateurNom</p>
	</div>
	<p class='utilisateur_description'>$utilisateurDescription</p>
	</article>"
	);
}

?>

<body>
	
<?php
	afficher_commentaire("GrosFessierJuteux","profile_pictures/NO_PFP.jpg","Coucou !");
	afficher_commentaire("GrosFessierJuteux","profile_pictures/NO_PFP.jpg","Coucou !"); 
?>

</body>

</html>