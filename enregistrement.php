<?php session_start(); ?>
<!DOCTYPE html>
<html>
	
<head>
	<title>MMF / Article FOCUS</title>
	<meta charset="utf-8"/>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="enregistrement.css">
</head>

<?php

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


// Courte sous-fonction pour déterminer l'égalité de deux mots de passe.
function verification_mot_de_passe($mdp1,$mdp2):bool{
	$resultat = $mdp1 == $mdp2 ? true :  false;
	return $resultat;
}

// Courte sous-fonction pour valider la taille et le format de la photo de profile.
function verification_photo_de_profile($photo):?string{
	if ( empty($photo["name"]) ){
		return true;
	} else {
		$formats_acceptes = array("image/jpeg","image/gif","image/png");
		if (in_array($photo["type"],$formats_acceptes)){
			return true;
		} else {
			return false;
		}
	}
}

// Courte sous-fonction pour valider le nom d'utilisateur donné en paramètre.
function verification_nom_utilisateur($connection_bdd,$nomUtilisateur):bool{
	$requete = "SELECT * FROM mmf_ver1_utilisateurs WHERE utilisateur_nom ='$nomUtilisateur'";
	$lecture = mysqli_query($connection_bdd[0],$requete);
	$nombreLigne = mysqli_num_rows($lecture);
	if ( $nombreLigne > 0 ){
		return false;
	}
	return true;
}

// Sous-fonction pour enregistrer les informations d'un nouveau utilisateur.
function preparation_requete_insertion($connection_bdd){
	$insertion = "INSERT INTO mmf_ver1_utilisateurs(utilisateur_nom,utilisateur_mot_de_passe,utilisateur_photo_de_profile,utilisateur_description,utilisateur_categorie) VALUES (?,?,?,?,?);";
	$requete1 = mysqli_prepare($connection_bdd[0],$insertion);
	if (!$requete1){
		afficher_erreur("Preparation de la requête d'insertion échouée.");
		return null;
	} else {
		afficher_remarque("Preparation de la requête d'insertion réussie.");
	}
	return $requete1;
}

function afficher_erreur(string $message){
		echo("<div style='font-family:Arial Narrow;border-radius:4px;border:1px black solid;background-color:rgb(160,30,30);color:white;padding:3px;margin:3px;font-size:17px;width:30%;'><p style='margin:0 auto;'><b>ERREUR : </b>$message</p></div>");
}

	function afficher_remarque(string $message){
		echo("<div style='font-family:Arial Narrow;border-radius:4px;border:1px black solid;background-color:rgb(30,30,160);color:white;padding:3px;margin:3px;font-size:17px;width:30%;'><p style='margin:0 auto;'><b>INFO : </b>$message</p></div>");
}

function validation_enregistrement($connection_bdd,$execution,$utilisateurNom,$utilisateurMotDePasse,$utilisateurMotDePasseConfirmation,$utilisateurPhotoDeProfile,$utilisateurDescription):bool{
	if (isset($execution)){
		// !!! la valeur booléenne true sera retournée si les trois vérifications sont réussies.
		// !!! sinon la valeur retournée vaudra false.
		afficher_remarque('Validation des valeurs saisies.');
		// On valide d'abord les mots de passe : ils doivent être identiques.
		$test1 = verification_mot_de_passe($utilisateurMotDePasse,$utilisateurMotDePasseConfirmation);
		if (!$test1){
			afficher_erreur("Les mots de passe saisis ne sont pas identiques.");
			return false;
		}
		// On valide ensuite la photo de profile : elle doit respecter les formats et être de taille autorisée.
		$test2 = verification_photo_de_profile($utilisateurPhotoDeProfile);
		if (!$test2){
			afficher_erreur("La photo de profile est trop grande et/ou possède un format interdit.");
			return false;
		}
		// On valide le nom d'utilisateur : on vérifie s'il n'existe pas dékà un utilisateur portant le nom saisi.
		$test3 = verification_nom_utilisateur($connection_bdd,$utilisateurNom);
		if (!$test3){
			afficher_erreur("Le nom d'utilisateur est déjà utilisé.");
			return false;
		}
		// On valide la description : elle ne doit pas dépasser les 2000 caractères de longueur.
		if ( strlen($utilisateurDescription) > 2000 ){
			return false;
		}
		// Fin de la vérification
		return true;
	} else {
		afficher_erreur('le paramètre $execution n\'est pas donné.');
		return false;
	}
}

function executer_requete_insertion($preparation,$utilisateurNom,$utilisateurMotDePasse,$utilisateurPhotoDeProfile,$utilisateurDescription,$categorie){
	$test1 = null;
	$test1 = mysqli_stmt_bind_param($preparation,"ssssi",$utilisateurNom,$utilisateurMotDePasse,$utilisateurPhotoDeProfile,$utilisateurDescription,$categorie);
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

$connection_array = connexion_base_de_donnees("mmf_ver1");

$preparation = preparation_requete_insertion($connection_array);

if (isset($_POST["utilisateurNom"])){
	//$verif1 = verification_nom_utlisateur($connection_array,$_POST["utilisateurNom"]);
	$verif1 = validation_enregistrement($connection_array,$_POST["utilisateurNom"],$_POST["utilisateurNom"],$_POST["utilisateurMotDePasse"],$_POST["utilisateurMotDePasseConfirmation"],$_FILES["utilisateurPhotoDeProfile"],$_POST["utilisateurDescription"]);
	if ($verif1){
		afficher_remarque("Les vérifications sont faites.");
		$nom = $_POST["utilisateurNom"];
		$mot_de_passe = $_POST["utilisateurMotDePasse"];
		$photo_de_profile = $_FILES["utilisateurPhotoDeProfile"]["name"];
		$description = $_POST["utilisateurDescription"];
		$categorie = 1;
		afficher_remarque($photo_de_profile);
		if ( empty($photo_de_profile) ){
			executer_requete_insertion($preparation,$nom,$mot_de_passe,"profile_pictures/NO_PFP.jpg",$description,$categorie);
		} else {
			executer_requete_insertion($preparation,$nom,$mot_de_passe,"profile_pictures/".$photo_de_profile,$description,$categorie);
			copy($_FILES["utilisateurPhotoDeProfile"]["tmp_name"],"profile_pictures/".$_FILES["utilisateurPhotoDeProfile"]["name"]);
		}
	} else {
		afficher_erreur("Les vérifications ne sont pas terminées.");
	}
}

?>

<body>
	<p>Enregistrement d'un utilisateur</p>

	<form id="formulaire_inscription" action="enregistrement.php" method="POST" enctype="multipart/form-data">
		<p>Nom d'utilisateur :</p>
		<input type="text" name="utilisateurNom"/>
		<p>Mot de passe :</p>
		<input type="password" name="utilisateurMotDePasse"/>
		<p>Confirmation du mot de passe :</p>
		<input type="password" name="utilisateurMotDePasseConfirmation"/>
		<p>Photo de profile (facultatif) :</p>
		<input type="file" name="utilisateurPhotoDeProfile"/>
		<p>Description (facultatif) :</p>
		<textarea id="enregistrement_description" name="utilisateurDescription"></textarea>	
		<input id="bouton_enregistrement" type='submit' name="enregistrement" value='activation'/>
	</form>

</body>


</html>