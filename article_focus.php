<?php session_start(); require_once("outils_communs.php"); ?>
<!DOCTYPE html>
<html>

<head>
	<title>MMF / Article FOCUS</title>
	<meta charset="utf-8"/>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="article_focus.css">
	<link rel="stylesheet" href="style_navigation_et_barre.css">
</head>

<?php

$article_focalise = null;


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

	// Sous-fonction pour lire les informations de l'article en focus...
	function lecture_commentaire($connection_bdd,$id_article){
		$requete = "SELECT * FROM mmf_ver1_commentaires WHERE id_article = $id_article";
		$test1 = mysqli_query($connection_bdd,$requete);
		if (!$test1){
			//afficher_erreur("Requête de lecture des articles échouée.");
			return null;
		} else {
			//afficher_remarque("Requête de lecture des articles réussie.");
			return $test1;
		}
	}

	function preparation_requete_insertion_commentaire($connection_bdd){
		$insertion = "INSERT INTO mmf_ver1_commentaires(commentaire_contenu,id_article,id_envoyeur) VALUES (?,?,?);";
		$requete1 = mysqli_prepare($connection_bdd[0],$insertion);
		if (!$requete1){
			afficher_erreur("Preparation de la requête d'insertion échouée.");
			return null;
		} else {
			afficher_remarque("Preparation de la requête d'insertion réussie.");
		}
		return $requete1;
	}

	function executer_requete_insertion_commentaire($preparation,$contenu,$id_article,$id_envoyeur){
		$test1 = mysqli_stmt_bind_param($preparation,"sss",$contenu,$id_article,$id_envoyeur);
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

	function afficher_commentaire($connection_bdd,$envoyeurId,$commentaireContenu){
		$informations = informations_utilisateur($connection_bdd,$envoyeurId);
		if ($informations[0] and $informations[1]){
			echo("<article class='commentaires'>
			<div class='commentaire_info_utilisateur'><p>$informations[0]</p><img class='commentaire_pfp' src=$informations[1] style='width:80%;margin-bottom:5px;'/></div>
			<p class='commentaire_contenu'>$commentaireContenu</p>
			</article>");
		} else {

		}
	}

	$array_connection = connexion_base_de_donnees("mmf_ver1");

		
	if ( isset($_GET["id"]) ){
		$article_focalise = $_GET["id"];
		//afficher_remarque($_GET["id"]);
		$lecture = lecture_article_focus($array_connection[0],$_GET["id"]);
		$commentaires = lecture_commentaire($array_connection[0],$_GET["id"]);
	} else {
		if ( isset($_POST["id"]) ){
			$article_focalise = $_POST["id"];
			//afficher_remarque($_POST["id"]);
			$preparation2 = preparation_requete_insertion_commentaire($array_connection);
			executer_requete_insertion_commentaire($preparation2,htmlspecialchars($_POST["commentaireContenu"]),$_POST["id"],$_SESSION["user_id"]);
			header("Location: article_focus.php?id=$article_focalise", true, 303);
			//$lecture = lecture_article_focus($array_connection[0],$_POST["id"]);
			//$commentaires = lecture_commentaire($array_connection[0],$_POST["id"]);
		} else {
			echo("Aucune id détectée.");
		}
	}

	informations_utilisateur($array_connection,2);

	detection_deconnexion();

?>

<body>

<?php barre_navigation("article_focus.php?id=$article_focalise"); ?>

<div id="liste_articles1">
			
	<?php

		while($curseur = mysqli_fetch_assoc($lecture)){
			$infos_envoyeur = informations_utilisateur($array_connection,$curseur["article_id_envoyeur"]);
			afficher_info_envoyeur_miniature($infos_envoyeur[0],$infos_envoyeur[1],$curseur["article_date_envoi"]);
			article($curseur["article_titre"],$curseur["article_image"],$curseur["article_date_envoi"],$curseur["article_contenu"],$curseur["article_id"]);
		}

	?>

	<div id="zoneCommentaireForm">

		<p>Envoyez un commentaire :</p>

		<form id='CommentaireFormulaire' method='POST' action='article_focus.php'>

			<?php 

			if (($_SESSION["user_id"] != 0) and ($_SESSION["connected"] == "OUI")){

				if (isset($_GET["id"])){

					$id_get = $_GET["id"];

					echo("

						<input type='hidden' value=$id_get name='id'/>

						<textarea name='commentaireContenu' id='commentaireContenu'></textarea>

						<input type='submit' name='commentaireEnvoi' value='Envoyer' id='commentaireBoutonEnvoi'/>

						");

				} elseif (isset($_POST["id"])){

					$id_post = $_POST["id"];

					echo("

						<input type='hidden' value=$id_post name='id'/>

						<textarea name='commentaireContenu' id='commentaireContenu'></textarea>

						<input type='submit' name='commentaireEnvoi' value='Envoyer' id='commentaireBoutonEnvoi'/>

						");

				}

			} else {

				afficher_erreur("Vous devez vous connecter pour écrire un commentaire.");

			}

			?>

		</form>

	</div>

	<?php 

	// On va afficher tous les commentaires.
	while($curseur_commentaire = mysqli_fetch_assoc($commentaires)){
		afficher_commentaire($array_connection,$curseur_commentaire["id_envoyeur"],$curseur_commentaire["commentaire_contenu"]);
	}

	?>

</div>
	
</body>

</html>