<?php

function invoices_sort($invoices){
	$dates = array();
    foreach ($invoices as $key => $row) {
		$dates[$key]  = strtotime($row->date); 
		// of course, replace 0 with whatever is the date field's index
	}

	array_multisort($dates, SORT_ASC, $invoices);
	return $invoices;
}