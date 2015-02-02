<?php
// Is logged in?
// "^\(?([0-9]{3})\)?[-. ]?([0-9]{3})[-. ]?([0-9]{4})$"
// Convert for DB:
// preg_replace("/^\(?([0-9]{3})\)?[-. ]?([0-9]{3})[-. ]?([0-9]{4})$/", "$1$2$3", $input_lines);
// Convert to display:
// preg_replace("/^([0-9]{3})([0-9]{3})([0-9]{4})$/", "($1) $2-$3", $input_lines);

$user = new User();

if (!$user->isLoggedIn()) {
    $loginMessage = 'Invité';
} else {
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

        <title>Accueil - Esthética</title>
        <link href="css/bootstrap.css" rel="stylesheet">
        <link href="//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">
        <link href="css/bootstrap-social.css" rel="stylesheet">
        <link href="css/bootstrap-datetimepicker.min.css" rel="stylesheet" type="text/css"/>
        <link href="css/custom.css" rel="stylesheet" type="text/css">

        <script src="js/jquery/jquery.min.js" type="text/javascript"></script>
        <script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
        <script src="js/bootstrap.min.js" type="text/javascript"></script>
        <script src="js/bootstrap-confirmation.js" type="text/javascript"></script>
        <script src="js/moment-with-locales.min.js" type="text/javascript"></script>
        <script src="js/bootstrap-datetimepicker.min.js" type="text/javascript"></script>
    </head>
    <body>
        <div class="logo" class="container">
            <a href="index.php" title="Esthética">
                <img src="images/logo-site.png" alt="Esthética" />
            </a>
        </div>
        <div class="navbar navbar-inverse navbar-static-top" role="navigation">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                    <span class="menu-text">MENU</span>
                </button>
            </div>

            <div class="container">
                <div class="navbar-collapse collapse">
                    <ul class="nav navbar-nav">
                        <li class="<?php echo ($action == 'accueil.php' ? 'active' : ''); ?>"><a href="index.php"><i class="fa fa-fw fa-home"></i> Accueil</a></li>
                        <li class="<?php echo ($action == 'services.php' ? 'active' : ''); ?>"><a href="index.php?action=services"><i class="fa fa-list"></i> Nos services</a></li>
                        <li class="<?php echo ($action == 'apropos.php' ? 'active' : ''); ?>"><a href="index.php?action=apropos"><i class="fa fa-question-circle"></i> À propos</a></li>
                        <li class="<?php echo ($action == 'contacteznous.php' ? 'active' : ''); ?>"><a href="index.php?action=contacteznous"><i class="fa fa-phone"></i> Contactez-nous</a></li>
                    </ul>
                    <ul class="nav navbar-nav navbar-right">
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-user"></i> Bienvenue, <?php echo $loginMessage; ?> <b class="caret"></b></a>
                            <ul class="dropdown-menu">
                                <?php
                                if ($user->isLoggedIn()) {
                                    echo '
								<li role="presentation" class="dropdown-header">Téléphone: ' . preg_replace("/^\(?([0-9]{3})\)?[-. ]?([0-9]{3})[-. ]?([0-9]{4})$/", "($1) $2-$3", $user->data()->phone) . '</li>';
                                    echo ($user->data()->facebook_id != NULL) ? '<li role="presentation" class="dropdown-header">Connecté via Facebook</li>' : '';
                                    echo '
								<li><a href="index.php?action=myaccount"><i class="fa fa-fw fa-info-circle"></i> Mes détails</a></li>
								<li><a href="index.php?action=update"><i class="fa fa-fw fa-cogs"></i> Paramètres</a></li>
								<li role="presentation" class="divider"></li>
								<li><a href="index.php?action=invoices"><i class="fa fa-fw fa-shopping-cart"></i> Mes factures</a></li>
								<li><a href="index.php?action=credits"><i class="fa fa-fw fa-dollar"></i> Mes notes de crédit</a></li>
								<li><a href="index.php?action=myrendezvous"><i class="fa fa-fw fa-calendar"></i> Mes rendez-vous</a></li>
								<li role="presentation" class="divider"></li>
								<li><a href="index.php?action=logout"><i class="fa fa-fw fa-sign-out"></i> Déconnexion</a></li>';
                                } else {
                                    echo '
								<li><a href="index.php?action=login"><i class="fa fa-sign-in"></i> Connexion ou Inscription</a></li>';
                                }
                                ?>

                            </ul>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <?php
        if (Session::exists('flash')) {
            $flash = Session::flash('flash');
            switch ($flash['status']) {
                case 'success':
                    $css = 'alert alert-success';
                    $icon = 'fa fa-2x fa-check-circle';
                    break;
                case 'error':
                    $css = 'alert alert-danger';
                    $icon = 'fa fa-2x fa-ban';
                    break;
                case 'warning':
                    $css = 'alert alert-warning';
                    $icon = 'fa fa-2x fa-exclamation-circle';
                    break;
                case 'info':
                    $css = 'alert alert-info';
                    $icon = 'fa fa-2x fa-info-circle';
                    break;
            }

            $flash['status'] = $css;
            unset($css);
            echo '<div class="' . $flash['status'] . ' alert-static-top">
		<div class="container"><span class="' . $icon . ' pull-left"></span>
			' . $flash['message'] . '
		</div>
	</div>';
        }
        ?>
        <div class="container">
            <div class="row">