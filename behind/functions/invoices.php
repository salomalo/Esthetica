<?php

function invoices_sort($invoices) {
    $dates = array();
    foreach ($invoices as $key => $row) {
        $dates[$key] = strtotime($row->date);
    }

    array_multisort($dates, SORT_ASC, $invoices);
    return $invoices;
}
