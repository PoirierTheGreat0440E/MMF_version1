<!DOCTYPE html>
<html>
	
<head>
	<title>MMF / Article FOCUS</title>
	<meta charset="utf-8"/>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="enregistrement.css">
</head>

<body>
	<p>Enregistrement d'un utilisateur</p>

	<form id="formulaire_inscription">
		<p>Nom d'utilisateur :</p>
		<input type="text" name="utilisateurNom"/>
		<p>Mot de passe :</p>
		<input type="password" name="utilisateurMotDePasse"/>
		<p>Confirmation du mot de passe :</p>
		<input type="password" name="utilisateurMotDePasseConfirmation"/>
		<p>Photo de profile (facultatif) :</p>
		<input type="file" name="utilisateurPhotoDeProfile"/>
		<p>Description (facultatif) :</p>
		<textarea id="enregistrement_description"></textarea>	
		<input id="bouton_enregistrement" type='submit' name="enregistrement" value='activation'/>
	</form>

</body>


</html>