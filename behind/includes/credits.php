<?php
if (!$user->isLoggedIn()) {
    Redirect::to('login');
}

$credits = new Credit();
$credits->findAll($user->data()->id);

if ($credits->exists()) {
    $results = $credits->data()->_results;
    foreach ($results as $key => $credit) {
        $results[$key]->total = Credit::total($credit);
    }
}
?>
<script type="text/javascript">
    $(document).ready(function (e) {
        $(document).attr('title', 'Mes notes de crédits - Esthética');
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
            <h3>Mes notes de crédit</h3>
            <table class="table table-striped table-hover table-responsive table-bordered">
                <thead>
                    <tr>
                        <th style="width: 10%;">#</th>
                        <th>Date</th>
                        <th style="width: 10%;" class="text-right">Montant</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if (!$credits->exists()) {
                        echo '
							<tr>
								<td colspan="3">Aucune note de crédit dans votre compte.</td>
							</tr>';
                    } else {
                        foreach ($results as $credit) {
                            echo '
							<tr>
								<td><strong><i class="fa fa-fw fa-file-o"></i> ' . $credit->id . '</strong></td>
								<td>' . substr($credit->date, 0, -9) . '</td>
								<td class="text-right">$' . substr(money_format("%i", (float) $credit->total), 0, -4) . '</td>
							</tr>
';
                        }
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>