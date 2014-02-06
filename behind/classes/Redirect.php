<?php
class Redirect {
	public static function to($action) {
		header('Location: index.php?action=' . $action);
		exit();	
	}
	
	public static function outter($location) {
		header('Location: ' . $location);
		exit();
	}
}