<!DOCTYPE html>

<head>
	<meta charset="UTF-8"/>
	<title> Moi et Ma Famille Melun </title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link href="PapierDuStyle1.css" rel="stylesheet" type="text/css">
</head>

<?php 

	function validation_article_saisi(){
		// On vérifie d'abord si le titre et le contenu ont bien été saisis.
		if (empty($_POST["articleTitre"]) or empty($_POST["articleContenu"])){
			afficher_erreur("Titre et/ou contenu vide(s).");
		} else {
			// Dans le cas où les deux informations précédentes sont présentes, on
			// vérifie si le contenu est d'une longueur autorisée.
			$longueur = strlen($_POST["articleContenu"]);
			if ( $longueur > 10000 ){
				afficher_erreur("La longueur maximale est dépassée.");
			} else {
				// On vérifie si une image a été sélectionnée.
				if (!empty($_FILES["articleImage"]["name"])){
					afficher_remarque("Une image a été sélectionnée.");
				} else {
					afficher_remarque("Aucune image sélectionnée.");
				}
			}
		}
	}

	function afficher_erreur(string $message){
		echo("<div style='font-family:Arial Narrow;border-radius:4px;border:1px black solid;background-color:rgb(160,30,30);color:white;padding:3px;margin:3px;font-size:17px;width:30%;'><p style='margin:0 auto;'><b>ERREUR : </b>$message</p></div>");
	}

	function afficher_remarque(string $message){
		echo("<div style='font-family:Arial Narrow;border-radius:4px;border:1px black solid;background-color:rgb(30,30,160);color:white;padding:3px;margin:3px;font-size:17px;width:30%;'><p style='margin:0 auto;'><b>INFO : </b>$message</p></div>");
	}

?>

<body>

	<main>

	<!-- Formulaire de création pour concevoir un article -->
	<form action="#" method="POST" class="creationArticle" enctype="multipart/form-data">

		<?php validation_article_saisi(); ?>
		
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

	</main>

</body>

</html>