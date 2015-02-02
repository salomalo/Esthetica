<?php
$javascript = '';

if (!$user->isLoggedIn()) {
    Redirect::to('login');
}

if (Input::exists()) {
    if (Token::check(Input::get('token'))) {
        $validation = new Validation();

        $validation->check($_POST, array(
            'inputDate' => array(
                'required' => true,
                'dateMin' => time(),
                'fieldName' => 'Date et heure',
                'group' => 'dateGroup'
            ),
            'inputServices' => array(
                'requiredArray' => true,
                'fieldName' => 'Services requis',
                'group' => 'servicesGroup'
            )
        ));

        if ($validation->passed()) {
            $date = Input::get('inputDate');
            $services = Input::get('inputServices');
            if (isset($date) && isset($services)) {
                $currentRendezvousEdition->changeServices($services);
                $currentRendezvousEdition->changeDate($date);

                Session::flash('flash', array('status' => 'success', 'message' => '<strong>Rendez-vous modifié!</strong> Attention, le rendez-vous sélectionné a été modifié à votre demande. Il est en attente d\'approbation par votre esthéticien(ne).'));
                Redirect::to('myrendezvous');
            }
        } else {
            $errors = '<div class="alert alert-danger"><strong>Oups!</strong> Veuillez corriger les problèmes suivants:<br /><br />';
            foreach ($validation->errors() as $key) {
                $errors .= $key['message'] . '<br />';
                $javascript .= '$(\'#' . $key['group'] . '\').addClass(\'has-error\').delay(1000).effect("bounce"); ';
            }
            $errors .= '</div>';
        }
    } else {
        Redirect::to('myrendezvous');
    }
}

$allowedServices = DB::getInstance()->get('services', array('1', '=', '1'))->results();

$currentServices = json_decode($currentRendezvousEdition->data()->services);
foreach ($currentServices as $i => $value) {
    $currentServices[$i] = removeAccents($value);
}
$token = Token::generate();
?>
<script type="text/javascript">
    $(document).ready(function (e) {
        $(document).attr('title', 'Modifier un rendez-vous - Esthética');

        $('.date').datetimepicker({
            useSeconds: false,
            useCurrent: false,
            minuteStepping: 15,
            minDate: moment.unix(<?php echo time(); ?>),
            language: 'fr',
            format: 'YYYY-MM-DD HH:mm',
            icons: {
                time: 'fa fa-clock-o',
                date: 'fa fa-calendar',
                up: 'fa fa-arrow-up',
                down: 'fa fa-arrow-down'
            }
        });
        $('.date').data('DateTimePicker').setDate(moment.unix(<?php echo strftime('%s', strtotime($currentRendezvousEdition->data()->startDate)); ?>));
    });
</script>
<div class="col-md-12">
    <h1>Mon compte <small>Gestion de clientèle</small></h1>
    <div class="list-group col-md-3">
        <?php
        include __DIR__ . '/../back-sidebar.php';
        ?>
    </div>

    <div class="tab-content col-md-9">
        <div class="tab-pane active" id="home">
            <h3>Modification d'un rendez-vous</h3>
            <?php
            echo (empty($errors)) ? '' : $errors;
            ?>
            <form class="form-horizontal" role="form" action="index.php?action=rendezvous&edit=1&id=<?php echo $currentRendezvousEdition->data()->id; ?>" method="post">
                <div id="currentDateGroup" class="form-group">
                    <label for="inputCurrentDate" class="col-md-3 col-md-offset-1 control-label">Date actuelle:</label>
                    <div class="col-md-8">
                        <p class="form-control-static"><?php echo date('Y-m-d G:i', strtotime($currentRendezvousEdition->data()->startDate)); ?>
                            <?php
                            if ($currentRendezvousEdition->data()->confirmed) {
                                echo '<span class="help-block"><i class="fa fa-check-circle"></i> Ce rendez-vous est actuellement confirmé!</span>';
                            }
                            ?>
                        </p>
                    </div>
                </div>
                <div id="dateGroup" class="form-group">
                    <label for="inputDate" class="col-md-3 col-md-offset-1 control-label">Nouvelle date:</label>
                    <div class="col-md-4 input-group date">
                        <input type="text" class="form-control" id="inputDate" name="inputDate" placeholder="Nouvelle date">
                        <span class="input-group-addon datepickerbutton"><span class="fa fa-calendar"></span>
                        </span>
                    </div>
                </div>
                <div id="servicesGroup" class="form-group">
                    <label for="inputServices" class="col-md-3 col-md-offset-1 control-label">Services requis:</label>
                    <div class="col-md-8">
                        <?php
                        foreach ($allowedServices as $allowedService) {
                            echo '<div class="checkbox">
                                    <label>
                                        <input type="checkbox" name="inputServices[]" id="' . strtolower(removeAccents($allowedService->type)) . 'Checkbox" value="' . removeAccents($allowedService->type) . '"' . ((in_array(removeAccents($allowedService->type), $currentServices)) ? ' checked' : '') . '>
                                        ' . $allowedService->type . '
                                    </label>
                                </div>';
                        }
                        ?>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-offset-4 col-md-8">
                        <button type="submit" class="btn btn-primary"><span class="fa fa-save"></span> Sauvegarder</button>
                    </div>
                </div>
                <input type="hidden" name="token" value="<?php echo $token; ?>" />
            </form>
        </div>
    </div>
</div>
<script>
    $(document).ready(function () {
<?php echo $javascript; ?>
    });
</script>