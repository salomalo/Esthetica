<?php
if (!$user->isLoggedIn()) {
    Redirect::to('login');
}

/* $rdv1 = RendezVous::create(array(
  'user_id' => $user->data()->id,
  'startDate' => date('Y-m-d G:i:s'),
  'employeeId' => $user->data()->estheticienId,
  'data' => json_encode(array())
  ));
  print_r($rdv1); */
?>

<script type="text/javascript">
    $(document).ready(function (e) {
        $(document).attr('title', 'Mes rendez-vous - Esthética');

        $('a.cancel-btn[data-toggle="confirmation"]').confirmation({
            title: "Êtes-vous certain<?php echo ($user->data()->gender == 1 ? '' : 'e'); ?>?",
            container: 'body'
        });
    });
</script>
<div class="col-md-12">
    <h1>Mon compte <small>Gestion de clientèle</small></h1>
    <div class="list-group col-md-3">
        <?php
        include 'back-sidebar.php';
        ?>
    </div>

    <div class="tab-content col-md-9">
        <div class="tab-pane active" id="home">
            <div class="row">
                <h3 class="col-xs-8">Mes rendez-vous</h3>
                <div class="col-xs-4 top-options-buttons">
                    <a href="index.php?action=rendezvous" class="btn btn-primary">Prendre rendez-vous</a>
                    <a href="#" class="btn btn-default">?</a>
                </div>
            </div>
            <table class="table table-striped table-hover table-responsive table-bordered">
                <thead>
                    <tr>
                        <th>Date et heure</th>
                        <th>Durée</th>
                        <th>Employé</th>
                        <th style="width: 30%;">Options</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if (!$rendezvous->exists()) {
                        echo '
								<tr>
									<td colspan="4">Aucun rendez-vous dans votre compte.</td>
								</tr>';
                    } else {
                        $token = Token::generate();

                        foreach ($rendezvousResults as $key => $currentRendezvous) {
                            $rendezvousResults[$key]->passed = false;
                            if (strtotime($currentRendezvous->startDate) < time()) {
                                $rendezvousResults[$key]->passed = true;
                            }

                            /**
                             * Confirmation:
                             *   0 means awaiting approval from employees
                             *   1 means approved 
                             */
                            echo '
								<tr' . ($currentRendezvous->passed ? ' class="danger"' : '') . '' . (!$currentRendezvous->confirmed ? ' class="warning"' : ' class="success"') . '>
									<td><i class="fa fa-fw fa-clock-o"></i> ' . utf8_encode(strftime('%e %b %y', strtotime($currentRendezvous->startDate))) . ' à ' . strftime('%H:%M', strtotime($currentRendezvous->startDate)) . '</td>
									<td>' . implode('<br />', json_decode($currentRendezvous->services)) . '</td>
                                                                        <td><a href="mailto:' . escape($currentRendezvous->email) . '">' . ($currentRendezvous->gender == 1 ? '<i class="fa fa-lg fa-male"></i>' : '<i class="fa fa-lg fa-female"></i>') . ' ' . escape($currentRendezvous->firstName) . ' ' . escape($currentRendezvous->lastName) . '</a></td>
                                                                        <td><div class="btn-group btn-group-justified">';
                            if (!$currentRendezvous->passed) {
                                echo '

                                                                            <a href="index.php?action=rendezvous&id=' . $currentRendezvous->id . '&edit=1&token=' . $token . '" class="btn btn-sm btn-primary"><i class="fa fa-fw fa-cog"></i> Modifier</a>
                                                                            <a data-href="index.php?action=rendezvous&id=' . $currentRendezvous->id . '&cancel=1&token=' . $token . '" class="btn btn-sm btn-danger cancel-btn" data-toggle="confirmation"><i class="fa fa-fw fa-times"></i> Annuler</a>'
                                . '                                     </td>';
                            } else {
                                echo '
                                                                            <a class="btn btn-sm btn-primary disabled"><i class="fa fa-fw fa-cog"></i> Modifier</a>
                                                                            <a class="btn btn-sm btn-danger disabled"><i class="fa fa-fw fa-times"></i> Annuler</a>
                                                                        </td>';
                            }
                            echo '
									</div></td>
                                                                </tr>';
                        }
                    }
                    ?>
                </tbody>
            </table>
            <div class="row center-block" style="width: 75%">
                <table class="table table-hover table-bordered table-condensed table-responsive text-center" id="legend">
                    <tr>
                        <th class="text-center" colspan="3">LÉGENDE</th>
                    </tr>
                    <tr>
                        <td class="success">À venir/Accepté</td>
                        <td class="warning">Non confirmé/En attente</td>
                        <td class="danger">Passé</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>