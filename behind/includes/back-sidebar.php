<?php
$invoices = new Invoice();
$invoices->findAll($user->data()->id);

$invoicesCount = 0;
$invoicesResults = array();
if ($invoices->exists()) {
    $invoicesResults = $invoices->data()->_results;
    foreach ($invoicesResults as $key => $invoice) {
        $invoicesResults[$key]->total = Invoice::total($invoice, $user);
        $invoicesResults[$key]->totalDue = Invoice::totalDue($invoice, $user);

        /**
         * Let's fix issues here.
         */
        if (($invoice->totalDue == 0 || $invoice->total == 0) && $invoice->status != "Payée") {
            $invoicesResults[$key]->status = "Payée";
            Invoice::markPaidWithId($invoice->id, array('type' => 'Correction automatique (problème de base de donnée)', 'amount' => $invoice->totalDue));
        }
        if ($invoice->totalDue != 0 && $invoice->status == "Payée") {
            echo 'Total du n\'est pas 0 et c\'est marqué payé? WTF';
            $invoicesResults[$key]->status = "Impayée";
            Invoice::markUnpaidWithId($invoice->id);
        }
        /*         * ************************** */

        if ($invoice->status == "Impayée") {
            $invoicesCount++;
        }
    }
}

$credits = new Credit();
$credits->findAll($user->data()->id);

$creditsAmount = 0.00;
$creditsResults = array();
if ($credits->exists()) {
    $creditsResults = $credits->data()->_results;
    foreach ($creditsResults as $key => $credit) {
        $creditsAmount += Credit::total($credit);
    }
}

$rendezvous = RendezVous::findAll($user->data()->id);
$rendezvousCount = 0;
$rendezvousResults = array();
if ($rendezvous->exists()) {
    $rendezvousResults = $rendezvous->data()->_results;
    foreach ($rendezvousResults as $key => $currentRendezvous) {
        $rendezvousResults[$key]->passed = false;
        if (strtotime($currentRendezvous->startDate) < time()) {
            $rendezvousResults[$key]->passed = true;
        }
        if (!$currentRendezvous->passed) {
            $rendezvousCount++;
        }
    }
}
?>
<a href="index.php?action=myaccount" class="list-group-item<?php echo $action == 'myaccount.php' ? ' active' : ''; ?>"><span class="fa fa-fw fa-info-circle"></span> Mes détails</a>
<a href="index.php?action=update" class="list-group-item<?php echo $action == 'update.php' ? ' active' : ''; ?>"><span class="fa fa-fw fa-cogs"></span> Paramètres</a>
<p>&nbsp;</p>
<a href="index.php?action=invoices" class="list-group-item<?php echo ($action == 'invoices.php' || $action == 'invoice.php') ? ' active' : ''; ?>"><span class="badge"><?php echo $invoicesCount; ?> impayée<?php if ($invoicesCount > 1) echo 's'; ?></span><span class="fa fa-fw fa-shopping-cart"></span> Mes factures</a>
<a href="index.php?action=credits" class="list-group-item<?php echo ($action == 'credits.php' || $action == 'credit.php') ? ' active' : ''; ?>"><span class="badge">$<?php echo substr(money_format("%i", (float) $creditsAmount), 0, -4); ?></span><span class="fa fa-fw fa-dollar"></span> Mes notes de crédit</a>
<a href="index.php?action=myrendezvous" class="list-group-item<?php echo ($action == 'myrendezvous.php' || $action == 'rendezvous.php') ? ' active' : ''; ?>"><?php if ($rendezvousCount > 0) echo '<span class="badge">' . $rendezvousCount . ' à venir</span>'; ?><span class="fa fa-fw fa-calendar"></span> Mes rendez-vous</a>
<p>&nbsp;</p>
<a href="index.php?action=logout" class="list-group-item<?php echo $action == 'logout.php' ? ' active' : ''; ?>"><span class="fa fa-fw fa-sign-out"></span> Déconnexion</a>