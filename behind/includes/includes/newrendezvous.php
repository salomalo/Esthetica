<?php
$javascript	=	'';

if	(!$user->isLoggedIn())	{
				Session::flash('flash',	array('status'	=>	'warning',	'message'	=>	'<strong>Compte requis!</strong> Bienvenue! Vous devez créer un compte ou vous identifier avant de pouvoir prendre un rendez-vous!'));
				Redirect::to('login&rdv');
}

if	(Input::exists())	{
				if	(Token::check(Input::get('token')))	{
								$validation	=	new	Validation();

								$validation->check($_POST,	array(
												'inputDate'	=>	array(
																'required'	=>	true,
																'dateMin'	=>	'@'	.	strtotime("+2 days"),
																'fieldName'	=>	'Date et heure',
																'group'	=>	'dateGroup'
												),
												'inputServices'	=>	array(
																'requiredArray'	=>	true,
																'fieldName'	=>	'Services requis',
																'group'	=>	'servicesGroup'
												)
								));

								if	($validation->passed())	{
												$date	=	Input::get('inputDate');
												$services	=	Input::get('inputServices');
												if	(isset($date)	&&	isset($services))	{
																$newRendezVous	=	RendezVous::create(array(
																												'user_id'	=>	$user->data()->id,
																												'startDate'	=>	$date,
																												'employeeId'	=>	$user->data()->estheticienId,
																												'services'	=>	json_encode($services),
																												'confirmed'	=>	0
																));

																Session::flash('flash',	array('status'	=>	'success',	'message'	=>	'<strong>Rendez-vous créé!</strong> Vous avez bel et bien demandé un rendez-vous! Il est en attente d\'approbation par votre esthéticien(ne) et une notification vous sera transmise par courriel lors de la confirmation!'));
																Redirect::to('myrendezvous');
												}
								}	else	{
												$errors	=	'<div class="alert alert-danger"><strong>Oups!</strong> Veuillez corriger les problèmes suivants:<br /><br />';
												foreach	($validation->errors()	as	$key)	{
																$errors	.=	$key['message']	.	'<br />';
																$javascript	.=	'$(\'#'	.	$key['group']	.	'\').addClass(\'has-error\').delay(1000).effect("bounce"); ';
												}
												$errors	.=	'</div>';
								}
				}	else	{
								Redirect::to('rendezvous');
				}
}

$allowedServices	=	DB::getInstance()->get('services',	array('1',	'=',	'1'))->results();

$currentServices	=	array();
$token	=	Token::generate();
?>
<script type="text/javascript">
				$(document).ready(function (e) {
								$(document).attr('title', 'Prendre rendez-vous - Esthética');

								$('.date').datetimepicker({
												useSeconds: false,
												useCurrent: false,
												minuteStepping: 15,
												minDate: moment.unix(<?php	echo	strtotime("+2 days");	?>),
												language: 'fr',
												format: 'YYYY-MM-DD HH:mm',
												icons: {
																time: 'fa fa-clock-o',
																date: 'fa fa-calendar',
																up: 'fa fa-arrow-up',
																down: 'fa fa-arrow-down'
												}
								});
								$('.date').data('DateTimePicker').setDate(moment.unix(<?php	echo	strtotime("+2 days");	?>));
				});
</script>
<div class="col-md-12">
    <h1>Mon compte <small>Gestion de clientèle</small></h1>
    <div class="list-group col-md-3">
								<?php
								include	__DIR__	.	'/../back-sidebar.php';
								?>
    </div>

    <div class="tab-content col-md-9">
        <div class="tab-pane active" id="home">
            <h3>Prendre rendez-vous</h3>
												<?php
												echo	(empty($errors))	?	''	:	$errors;
												?>
            <form class="form-horizontal" role="form" action="index.php?action=rendezvous" method="post">
                <div id="dateGroup" class="form-group">
                    <label for="inputDate" class="col-md-3 col-md-offset-1 control-label">Date et heure demandée:</label>
                    <div class="col-md-4 input-group date">
                        <input type="text" class="form-control" id="inputDate" name="inputDate" placeholder="Date et heure demandée">
                        <span class="input-group-addon datepickerbutton"><span class="fa fa-calendar"></span>
                        </span>
                    </div>
																				<div class="col-md-6 col-md-offset-4">
																								<p class="help-block">Un minimum de deux jours de délai sont requis.</p>
																				</div>
                </div>
																<div id="servicesGroup" class="form-group">
																				<label for="inputServices" class="col-md-3 col-md-offset-1 control-label">Services requis:</label>
																				<div class="col-md-8">
																								<?php
																								foreach	($allowedServices	as	$allowedService)	{
																												echo	'<div class="checkbox">
																												<label>
																																<input type="checkbox" name="inputServices[]" id="'	.	strtolower($allowedService->type)	.	'Checkbox" value="'	.	$allowedService->type	.	'"'	.	((in_array($allowedService->type,	$currentServices))	?	' checked'	:	'')	.	'>
																																'	.	$allowedService->type	.	'
																												</label>
																								</div>';
																								}
																								?>
																				</div>
																</div>
                <div class="form-group">
                    <div class="col-md-offset-4 col-md-8">
                        <button type="submit" class="btn btn-primary"><span class="fa fa-mail-forward"></span> Envoyer la demande</button>
                    </div>
                </div>
                <input type="hidden" name="token" value="<?php	echo	$token;	?>" />
            </form>
        </div>
    </div>
</div>
<script>
				$(document).ready(function () {
<?php	echo	$javascript;	?>
				});
</script>