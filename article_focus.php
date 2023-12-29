<!DOCTYPE html>
<html>

<head>
	<title>MMF / Article FOCUS</title>
	<meta charset="utf-8"/>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="article_focus.css">
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


		// Sous-fonction pour lire les informations de l'article en focus...
		function lecture_article_focus($connection_bdd,$articleId){
			$requete = "SELECT * FROM mmf_ver1_articles WHERE article_id = $articleId";
			$test1 = mysqli_query($connection_bdd,$requete);
			if (!$test1){
				//afficher_erreur("Requête de lecture des articles échouée.");
				return null;
			} else {
				//afficher_remarque("Requête de lecture des articles réussie.");
				return $test1;
			}
		}

		function afficher_article_focus($titre,$image,$contenu){
			echo("<div class='articles'>
				<b style='font-size:20px;'>$titre</b><br/>
				<div style='width:100%; background-color:black;display:flex;flex-direction:row;justify-content:center;'>
				<img src=$image style='border:1px black dotted;width:80%;'></img>
				</div>
				<i>$image</i>
				<p>$contenu</p>
				</div>");
		}

		if (isset($_GET["id"])){
			$array_connection = connexion_base_de_donnees("mmf_ver1");
			$lecture = lecture_article_focus($array_connection[0],$_GET["id"]);
		} else {
			echo("Aucune id détectée.");
		}

?>

<body>

	<nav id="barreNavigation">
			<a href="presentation.php"><p>Home Page</p></a>
			<a href="articles.php"><p>Articles/Forum</p></a>
			<a href="#"><p>Users</p></a>
	</nav>	

<div class="liste_articles">
			
			<?php

			while($curseur = mysqli_fetch_assoc($lecture)){
				afficher_article_focus($curseur["article_titre"],$curseur["article_image"],$curseur["article_contenu"]);
			}

		 	?>

</div>

<div id="zoneCommentaireForm">

	<p>Envoyez un commentaire :</p>

	<?php echo("<form id='CommentaireFormulaire' method='GET' action='article_focus.php'>"); ?>

	<form id="CommentaireFormulaire" action="article_focus.php?id=">

		<?php echo(" <input type='hidden' value='".$_GET["id"]."' name='id'/> "); ?>

		<textarea name="commentaireContenu" id="commentaireContenu">
		</textarea>

		<input type="submit" name="commentaireEnvoi" value="Envoyer" id="commentaireBoutonEnvoi"/>

	</form>

</div>
	
</body>

</html>