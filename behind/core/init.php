<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);
date_default_timezone_set('America/Montreal');
setlocale(LC_ALL, 'fr_CA');
session_start();
header('Content-Type: text/html; charset=UTF-8');

$GLOBALS['config'] = array(
    'mysql' => array(
        'host' => 'sql.byethost5.org',
        'username' => 'gtasam_root',
        'password' => 'nobyyx02',
        'db' => 'gtasam_onglestrycia'
    ),
    'remember' => array(
        'cookie_name' => 'hash',
        'cookie_expiry' => 604800
    ),
    'session' => array(
        'session_name' => 'user',
        'token_name' => 'token'
    )
);

spl_autoload_register(function($class) {
    require_once 'behind/classes/' . $class . '.php';
});

require_once 'behind/functions/sanitize.php';

$Licensing = new Licensing();

$whmcsurl = rtrim("http://clients.mori7technologie.ca", "/") . "/";

$Licensing->whmcsurl = $whmcsurl;
$Licensing->secret_key = "f704d329d0j4728f9n5y4ri2cn748392nd7894nd789423n7d89n438729dn4892nf7894298gfn74238f4";
//$Licensing->license_key	=	"M7TRS-DEVb123a72ace96";
$Licensing->license_key = "M7TRS-3153a977c6fc927";
if (Input::get('clear_license') == "y") {
    Session::flash('flash', array('status' => 'warning', 'message' => '<strong>LICENSING MANAGEMENT:</strong> The local license will not be used and thus an online call was used to validate.'));
    $Licensing->local_key = '';
} else {
    $Licensing->local_key = @file_get_contents("license.txt");
}

$results = $Licensing->validateLicense();

if ($results["status"] == "Active") {
    if (isset($results["localkey"])) {
        $localkeydata = $results["localkey"];
        file_put_contents("license.txt", $results["localkey"]);
    }
} elseif ($results["status"] == "Invalid") {
    $copyright_show = true;
    $dev = 0;
    file_put_contents("license.txt", "");
    include('behind/includes/core/header.php');
    echo '
	<div class="panel panel-danger license-error">
		<div class="panel-heading">
			<h3 class="panel-title">Erreur</h3>
		</div>
		<div class="panel-body">
			Votre licence semble être invalide! Vous pouvez essayer d\'effectuer un <strong>REISSUE</strong> de celle-ci. Veuillez contacter le <a href="http://whmcs.godevz.com" target="_blank">support technique</a> si c\'est une erreur.
		</div>
	</div>';
    include('behind/includes/core/footer.php');
    die();
} else if ($results["status"] == "Expired") {
    $copyright_show = true;
    $dev = 0;
    file_put_contents("license.txt", "");
    include('behind/includes/core/header.php');
    echo '
	<div class="panel panel-danger license-error">
		<div class="panel-heading">
			<h3 class="panel-title">Erreur</h3>
		</div>
		<div class="panel-body">
			Votre licence est expirée! Veuillez la renouveller. Veuillez contacter le <a href="http://whmcs.godevz.com" target="_blank">support technique</a> pour de l\'aide supplémentaire.
		</div>
	</div>';
    include('behind/includes/core/footer.php');
    die();
} else if ($results["status"] == "Suspended") {
    $copyright_show = true;
    $dev = 0;
    file_put_contents("license.txt", "");
    include('behind/includes/core/header.php');
    echo '
	<div class="panel panel-danger license-error">
		<div class="panel-heading">
			<h3 class="panel-title">Erreur</h3>
		</div>
		<div class="panel-body">
			Votre licence a été suspendue! Veuillez contacter le <a href="http://whmcs.godevz.com" target="_blank">support technique</a>.
		</div>
	</div>';
    include('behind/includes/core/footer.php');
    die();
}

$dev = preg_match("(M7TRS-DEV\w{12})", $Licensing->license_key);
$copyright_show = 1;

if (Cookie::exists(Config::get('remember/cookie_name')) && !Session::exists(Config::get('session/session_name'))) {
    $hash = Cookie::get(Config::get('remember/cookie_name'));
    $hashCheck = DB::getInstance()->get('users_session', array('hash', '=', $hash));

    if ($hashCheck->count()) {
        $user = new User($hashCheck->first()->user_id);
        $user->login();
    }
}