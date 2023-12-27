<!DOCTYPE html>

<head>
	<meta charset="UTF-8"/>
	<title> Moi et Ma Famille Melun </title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link href="PapierDuStyle1.css" rel="stylesheet" type="text/css">
</head>

<?php

	// tentative de connection...
	// identifiant de connexion rendu.

	//$requete1 = mysqli_prepare($connection,"INSERT INTO mmf_ver1_articles (article_titre,article_image,article_contenu) VALUES (?,?,?)");
	//$prep = mysqli_stmt_bind_param($requete1,"sss",$_POST["articleTitre"],$_POST["articleImage"],$_POST["articleContenu"]);
	//$ok = mysqli_stmt_execute($requete1);

	// requête non préparées...
	// mysqli_query
	// mysqli_num_rows
	// mysqli_fetch_array
	// mysqli_fethc_assoc
	// mysqli_fetch_object
	// mysqli_affected_rows
	// mysqli_insert_id

	// requêtes préparées...
	// mysqli_prepare
	// mysqli_stmt_bind_param
	// mysqli_stmt_execute
	// mysqli_stmt_bind_result
	// mysqli_stmt_fetch
	// mysqli_stmt_close.

	// function pour se connecter à phpMyAdmin et se connecter à une base de données.
	function connexion_base_de_donnees(string $nom_bdd, string $nom_hote = "localhost", string $nom_utilisateur = "root", string $mot_de_passe = "") :?array {
		// On essaye d'abord de se connecter à phpMyAdmin...
		$etape1 = mysqli_connect($nom_hote,$nom_utilisateur,$mot_de_passe);
		if ( $etape1 ){
			afficher_remarque("Connection à phpMyAdmin réussie.");
		} else {
			afficher_erreur("Connection à phpMyAdmin échouée.");
			return null;
		}
		// On essaye de rejoindre la base de données donnée en paramètre...
		$etape2 = mysqli_select_db($etape1,$nom_bdd);
		if ( $etape2 ){
			afficher_remarque("Accès à la base de données réussie.");
		} else {
			afficher_erreur("Accès à la base de données échouée.");
			return null;
		}
		// Si les deux étapes ont été réalisées avec succès, on crée un array contenant toutes les étapes.
		//($etape1 and $etape2) ? return array($etape1,$etape2);
		return array($etape1,$etape2);
	}

	function preparation_requete_insertion($connection_bdd){
		$insertion = "INSERT INTO mmf_ver1_articles(article_titre,article_image,article_contenu) VALUES (?,?,?);";
		$requete1 = mysqli_prepare($connection_bdd[0],$insertion);
		if (!$requete1){
			afficher_erreur("Preparation de la requête d'insertion échouée.");
			return null;
		} else {
			afficher_remarque("Preparation de la requête d'insertion réussie.");
		}
		return $requete1;
	}

	function executer_requete_insertion($preparation,$titre,$image,$contenu){
		$test1 = mysqli_stmt_bind_param($preparation,"sss",$titre,$image,$contenu);
		if ( !$test1 ){
			afficher_erreur("Liaison des paramètres échouée.");
		} else {
			afficher_remarque("Liaison des paramètres réussie.");
			$execution = mysqli_stmt_execute($preparation);
			if ( !$execution ){
				afficher_erreur("Execution de la requête préparée d'insertion échouée.");
			} else {
				afficher_remarque("Execution de la requête préparée d'insertion réussie.");
			}
		}
	}

	function validation_article_saisi($titre,$image,$contenu) {
		// On vérifie d'abord si le titre et le contenu ont bien été saisis.
		if (empty($titre) or empty($contenu)){
			afficher_erreur("Titre et/ou contenu vide(s).");
			return null;
		} else {
			// Dans le cas où les deux informations précédentes sont présentes, on
			// vérifie si le contenu est d'une longueur autorisée.
			$longueur = strlen($contenu);
			if ( $longueur > 10000 ){
				afficher_erreur("La longueur maximale est dépassée.");
				return null;
			} else {
				// On vérifie si une image a été sélectionnée.
				if (!empty($image["name"])){
					afficher_remarque("Une image a été sélectionnée.");
					$nom = $image["name"];
					$format = $image["type"];
					$taille = $image["size"];
					afficher_remarque("Taille du fichier : $taille octets");
					$formats_acceptes = array("image/jpeg","image/gif","image/png");
					// On vérifie le fichier envoyé par le formulaire...
					// étape 1 : On vérifie le format du fichier et sa taille
					if ( (in_array($format, $formats_acceptes)) and ($taille < 10000000)  ){
						copy($_FILES["articleImage"]["tmp_name"],"C:/wamp64/www/MMF_version1/uploads/$nom");
						afficher_remarque("Le fichier rempli les critères !");	
						return 1;
					} else {
						afficher_erreur("Le fichier selectionné n'est pas au bon format et/ou est d'une taille supérieure à 1000000 d'octets.");
						return null;
					}
				} else {
					return 1;
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

	$array_connection = connexion_base_de_donnees("mmf_ver1");
	$preparation = preparation_requete_insertion($array_connection);


	if (isset($_POST["poster_article"])){
		$validation = validation_article_saisi($_POST["articleTitre"],$_FILES["articleImage"],$_POST["articleContenu"]);
		if ( $validation == 1 ){
			executer_requete_insertion($preparation,$_POST["articleTitre"],"uploads/".$_FILES['articleImage']['name'],$_POST["articleContenu"]);
			afficher_remarque("L'article a été posté !");
		} else {
			afficher_erreur("L'article n'est pas valide !");
		}
	}

?>

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

		<input type="submit" name="poster_article" value="Poster l'article" id="creationArticleBoutonPoste"/>
	
	</form>

	</main>

</body>

</html>