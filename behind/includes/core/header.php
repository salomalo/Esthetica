<?php
// Is logged in?
// "^\(?([0-9]{3})\)?[-. ]?([0-9]{3})[-. ]?([0-9]{4})$"
// Convert for DB:
// preg_replace("/^\(?([0-9]{3})\)?[-. ]?([0-9]{3})[-. ]?([0-9]{4})$/", "$1$2$3", $input_lines);
// Convert to display:
// preg_replace("/^([0-9]{3})([0-9]{3})([0-9]{4})$/", "($1) $2-$3", $input_lines);

$user = new User();

if(!$user->isLoggedIn()) {
	$loginMessage = 'Invité';	
}
else {
	$loginMessage = $user->data()->firstName . ' ' . $user->data()->lastName;	
}
header('X-UA-Compatible: IE=edge,chrome=1');
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
	<meta name="description" content="">
	<meta name="author" content="">
	<link rel="icon" href="/favicon.ico" />
	
	<title>Accueil - Pose d'ongles Trycia</title>
	<link href="css/bootstrap.min.css" rel="stylesheet">
	<link href="css/custom.css" rel="stylesheet" type="text/css">
	<link href="//netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.min.css" rel="stylesheet">	
	<link href="css/social-buttons.css" rel="stylesheet">
	<!--[if lt IE 9]>
	<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
	<script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
	<![endif]-->
	<script src="js/jquery.min.js" type="text/javascript"></script>
	<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
	<script src="js/bootstrap.min.js" type="text/javascript"></script>
	<script>var __adobewebfontsappname__="dreamweaver"</script>
	<script src="http://use.edgefonts.net/sail:n4:default.js" type="text/javascript"></script>
</head>
<body>
	<div class="container">
		<a href="index.php" class="hidden-xs hidden-sm">
			<img id="logo" src="images/ongles-trycia.png" alt="Ongles Trycia" />
		</a>
		<a href="index.php" class="hidden-md hidden-lg">
			<img id="logo" src="images/ongles-trycia.png" alt="Ongles Trycia" style="max-height: 100px;" />
		</a>
		<div class="navbar navbar-default" role="navigation">
			<div class="navbar-header">
				<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
				<!--<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>-->
				<span class="menu-text">MENU</span>
				</button>
			</div>
			<div class="navbar-collapse collapse">
				<ul class="nav navbar-nav">
					<li class="<?php echo ($action == 'accueil.php' ? 'active' : ''); ?>"><a href="index.php"><span class="glyphicon glyphicon-home"></span> Accueil</a></li>
					<li class="<?php echo ($action == 'services.php' ? 'active' : ''); ?>"><a href="index.php?action=services"><span class="glyphicon glyphicon-list"></span> Nos services</a></li>
					<li class="<?php echo ($action == 'apropos.php' ? 'active' : ''); ?>"><a href="index.php?action=apropos"><span class="glyphicon glyphicon-question-sign"></span> À propos</a></li>
					<li class="<?php echo ($action == 'contacteznous.php' ? 'active' : ''); ?>"><a href="index.php?action=contacteznous"><span class="glyphicon glyphicon-earphone"></span> Contactez-nous</a></li>
				</ul>
				<ul class="nav navbar-nav navbar-right">
					<li class="dropdown">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown"><span class="glyphicon glyphicon-user"></span> Bienvenue, <?php echo $loginMessage; ?> <b class="caret"></b></a>
						<ul class="dropdown-menu">
						<?php
						if($user->isLoggedIn()) {
							echo '							
							<li role="presentation" class="dropdown-header">Téléphone: ' . preg_replace("/^\(?([0-9]{3})\)?[-. ]?([0-9]{3})[-. ]?([0-9]{4})$/", "($1) $2-$3", $user->data()->phone) . '</li>';
							echo ($user->data()->facebook_id != NULL) ? '<li role="presentation" class="dropdown-header">Connecté via Facebook</li>' : '';
							echo '
							<li><a href="index.php?action=myaccount"><span class="glyphicon glyphicon-info-sign"></span> Mes détails</a></li>
							<li><a href="index.php?action=update"><span class="glyphicon glyphicon-cog"></span> Paramètres</a></li>
							<li role="presentation" class="divider"></li>
							<li><a href="index.php?action=logout"><span class="glyphicon glyphicon-off"></span> Déconnexion</a></li>';	
						} 
						else {
							echo '
							<li><a href="index.php?action=login"><span class="glyphicon glyphicon-lock"></span> Connexion ou Inscription</a></li>';
						}
						?>
						
						</ul>
					</li>
				</ul>
			</div>
		</div>