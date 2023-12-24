<!DOCTYPE html>

<head>
	<meta charset="UTF-8"/>
	<title> Moi et Ma Famille Melun </title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link href="PapierDuStyle1.css" rel="stylesheet" type="text/css">
</head>

<body>

	<main>

	<!-- Formulaire de création pour concevoir un article -->
	<form action="#" method="POST" class="creationArticle" enctype="multipart/form-data">
		
		<!-- Insertion d'un titre pour l'article -->
		<span for="titreArticle" class="creation_article_police"><b>Etape #1 :</b> Ecrire le titre de l'article :</span>
		<input type="text" name="articleTitre" class="largeur_proportion_60 creationArticle_details_communs" id="titreArticle"/>

		<!-- Insertion facultative d'une image pour illustrer un article -->
		<span for="articleImage" class="creation_article_police"><b>(Facultatif) :</b> Mettre une image d'illustration :</span>
		<input type="file" id="articleImage" name="articleImage" />

		<!-- Champ de texte pour insérer le contenu de l'article -->
		<span for="contenuArticle" class="creation_article_police"><b>Etape #2 :</b> Ecrire le contenu de l'article :</span>
		<textarea name="articleContenu" class="largeur_proportion_60 creationArticle_details_communs champ_de_texte1" id="contenuArticle"></textarea>

		<input type="submit" value="Poster l'article" id="creationArticleBoutonPoste"/>
	</form>

	<?php 


	?>

	</main>

</body>

</html>