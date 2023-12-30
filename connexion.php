<!DOCTYPE html>
<html>


<head>
	<title>MMF / Liste d'utilisateurs</title>
	<meta charset="utf-8"/>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="connexion.css">
</head>

<body>

<nav id="barreNavigation">
	<a href="presentation.php"><p>Home Page</p></a>
	<a href="articles.php"><p>Articles/Forum</p></a>
	<a href="utilisateurs.php"><p>Users</p></a>
	<a href="enregistrement.php"><p>Register</p></a>
	<a href="connexion.php"><p>Log in</p></a>
</nav>

<form method="POST" id="formulaire_connexion">
	
	<p>Nom d'utilisateur :</p>
	<input type="text" name="connexionNomDUtilisateur"/>
	<p>Mot de passe :</p>
	<input type="password" name="connexionMotDePasse"/>

	<input type="submit" name="connexionActivation" id="bouton_connexion"/>

</form>


</body>

</html>