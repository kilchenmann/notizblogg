<?php
header('Content-Type: application/json; charset=utf-8');

 // access public: true = 1
 // access !public = private = 0
session_start ();

$protocol = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";

require '../core/bin/php/settings.php'; require '../core/bin/php/class.get.php';

$user = array(
	'access' => 1,
	'name' => 'guest',
	'id' => '',
	'avatar' => ''
);

if (isset($_GET['token']) && ($_GET['token'] == $_SESSION['token'])) {
	// with the token in the url compared with the session, we don't have to check the user connection
	$user = array(
		'access' => 0
	);
} else if (isset($_SESSION["token"])) {
	// without the thoken, check the user with the session parameters
	$user = conuser($_SESSION['token']);
}

$note = NEW post();
$note->access = $user['access'];


foreach ($_GET as $key => $value){

	switch ($key) {
        case 'note';    // update
            echo 'note';

        break;

        case 'source';  // update

        break;

        default;        // new


    }

}
