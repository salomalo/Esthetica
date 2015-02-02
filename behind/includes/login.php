<?php
$javascript	=	'';

if	($user->isLoggedIn())	{
				Redirect::to('myaccount');
}

$rdv	=	$_GET['rdv'];
if	(Input::exists())	{
				if	(Token::check(Input::get('token')))	{
								$inputUserName	=	Input::get('inputUserName');
								$inputPassword	=	Input::get('inputPassword');
								if	(!empty($inputPassword)	&&	!empty($inputPassword))	{

												$remember	=	(Input::get('remember')	===	'on')	?	true	:	false;
												$login	=	$user->login(Input::get('inputUserName'),	Input::get('inputPassword'),	$remember);

												if	($login)	{
																$user->update(array('status'	=>	'Actif'));
																Session::flash('flash',	array('status'	=>	'success',	'message'	=>	'<strong>Succès!</strong> Bienvenue '	.	$user->data()->firstName	.	', vous vous êtes connecté à votre compte avec succès.'));

																if	(isset($rdv))	{
																				Redirect::to('rendezvous');
																}
																Redirect::to('myaccount');
												}	else	{
																$errors	=	'<div class="alert alert-danger"><strong>Oups!</strong> Les informations ne sont pas correctes. Veuillez réessayer.</div>';
																$javascript	.=	'$(\'#groupUserName\').addClass(\'has-error\').delay(500).effect("bounce"); ';
																$javascript	.=	'$(\'#groupPassword\').addClass(\'has-error\').delay(500).effect("bounce"); ';
												}
								}	else	{
												$errors	=	'<div class="alert alert-danger"><strong>Oups!</strong> Veuillez entrer vos informations complètes pour la connexion.</div>';
												$javascript	.=	'$(\'#groupUserName\').addClass(\'has-error\').delay(500).effect("bounce"); ';
												$javascript	.=	'$(\'#groupPassword\').addClass(\'has-error\').delay(500).effect("bounce"); ';
								}
				}	else	{
								$errors	=	'<div class="alert alert-danger"><strong>Oups!</strong> Assurez-vous de soumettre le formulaire avec le bouton Continuer.</div>';
				}
}
?>
<script type="text/javascript">
				$(document).ready(function (e) {
								$(document).attr('title', 'Connexion - Esthética');
				});
</script>
<div class="row">
    <div class="col-md-offset-1 col-md-5">
        <h1>Connexion</h1>
        <p>Utilisez vos informations personnelles pour vous connecter à votre compte.</p>
								<?php	echo	(empty($errors))	?	''	:	$errors;	?>
        <form class="form-horizontal" action="index.php?action=login<?php
								if	(isset($rdv))	{
												echo	'&rdv';
								}
								?>" method="post" role="form">
            <div class="form-group" id="groupUserName">
                <label for="inputUserName" class="col-md-4 control-label">Nom d'utilisateur</label>
                <div class="col-md-8">
                    <input type="text" class="form-control" name="inputUserName" placeholder="Nom d'utilisateur" value="<?php	echo	escape(Input::get('inputUserName'));	?>">
                </div>
            </div>
            <div class="form-group" id="groupPassword">
                <label for="inputPassword" class="col-md-4 control-label">Mot de passe</label>
                <div class="col-md-8">
                    <input type="password" class="form-control" name="inputPassword" placeholder="Mot de passe">
                </div>
            </div>
            <div class="form-group">
                <div class="col-md-offset-4 col-md-5">
                    <div class="checkbox">
                        <label>
                            <input name="remember" type="checkbox"> Se souvenir de moi
                        </label>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="col-md-offset-1 col-md-11 center-block text-center">
                    <input type="hidden" name="token" value="<?php	echo	Token::generate();	?>" />
                    <button type="submit" class="btn btn-primary">Connexion</button> ou
                    <a class="btn btn-facebook" href="index.php?action=facebookLogin<?php
																				if	(isset($rdv))	{
																								echo	'&rdv';
																				}
																				?>"><i class="fa fa-facebook"></i> | Se connecter</a>
                </div>
            </div>
        </form>
    </div>
    <div class="col-md-5">
        <h1>Inscription</h1>
        <p>Créez un compte à l'aide de vos informations personnelles et ainsi bénéficier des avantages suivants:</p>
        <ul>
            <li>Gérer vos rendez-vous <em>en ligne</em></li>
            <li>Visionner les détails de vos factures</li>
            <li>Voir le solde de votre compte
                <ul>
                    <li>Acheter des cartes cadeau</li>
                    <li>Voir vos crédits de parrainage</li>
                </ul>
            </li>
        </ul>
        <div class="col-md-offset-1 col-md-12 center-block text-center">
            <a href="index.php?action=register" class="btn btn-primary" role="button">Commencer »</a> ou <a class="btn btn-facebook" href="index.php?action=facebookCreate"><i class="fa fa-facebook"></i> | S'enregistrer</a>
        </div>
    </div>
</div>
<script>
				$(this).ready(function () {
<?php	echo	$javascript;	?>
				});
</script>