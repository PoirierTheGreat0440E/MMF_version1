<!DOCTYPE html>
<html>

<?php 

function afficher_image_avec_texte($texte,$image){
	echo("<article class='image_with_text'>
		<p>$texte</p>
		<img src='$image'/>
		</article>");
}

?>

<head>
	<title>MMF / Presentation</title>
	<meta charset="utf-8"/>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="landing_page.css">
</head>
<body>
	<p class="presentation1">Test landing page</p>
	<nav id="menu_navigation">
		<a href="articles.php"><p>Articles et nouveautés</p></a>
		<a href="utilisateurs.php"><p>Utilisateurs</p></a>
	</nav>

	<?php afficher_image_avec_texte("Bonjour tout le monde","montagne.jpg") ?>

</body>
</html>