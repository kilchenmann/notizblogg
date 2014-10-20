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

$note = NEW get();
$note->access = $user['access'];

foreach ($_GET as $key => $value){

	switch ($key) {
		/* change the first two ones */
		case 'note';
			$note->id = $_GET['note'];
			$note->type = 'note';
			$source = $note->checkNote();
			if($source != false) {
				$note->id = $source;
				$note->type = 'source';
				echo $note->getsource();
				// or redirect to the right request
			} else {
				echo $note->getNote();
			}
			break;

		case 'source';
			$note->id = $_GET['source'];
			$note->type = 'source';
			$note->access = $user['access'];
			echo $note->getSource();
			break;

		case 'collection';
			$note->id = $_GET['source'];
			$note->type = 'collection';
			$note->access = $user['access'];
			echo $note->getSource();
			break;
		/* -.-.-.-.-.-.-.-.-.-.-.-.-.-.-.-.-.-.-. */

		case 'label';
			$note->id = $_GET['label'];
			$note->type = 'label';
			echo $note->listData();
			break;

		case 'author';
			$note->id = $_GET['author'];
			$note->type = 'author';
			echo $note->listData();
			break;

		case 'new';
			// the number behind the query 'new' is to limit the number of notes
			// on the public site: get just the sources, order by dateCreated
			// on the private site: get all newest notes
			$note->id = $_GET['new'];
			$note->type = 'new';
			echo $note->listData();
			break;

		case 'recent';
			// the number behind the query 'new' is to limit the number of notes
			// on the public site: get just the sources, order by dateCreated
			// on the private site: get all newest notes
			$note->id = $_GET['recent'];
			$note->type = 'recent';
			echo $note->listData();
			break;

		case 'list';
			// the number behind the query 'list' is to limit the number of sources
			$note->id = $_GET['list'];
			$note->type = 'list';
			echo $note->listData();
			break;

		case 'bibtyp';
			$note->id = $_GET['bibtyp'];
			$note->type = 'bibtyp';
			echo $note->listData();

			break;

		case 'q';
			$part = '';
			if (isset($_GET['filter'])) {
				$part = $_GET['filter'];
			}
			$note->query = $_GET['q'];
			$note->part = $part;
			echo $note->searchData();
			break;

		case 'id';
			$note->id = $_GET['id'];
			$note->type = 'note';
			$source = $note->checkNote();
			if($source != false) {
				$note->id = $source;
				$note->type = 'source';
				echo $note->getsource();
				// or redirect to the right request
			} else {
				echo $note->getNote();
			}
			break;

		default;
			$note->id = $_GET['note'];
			$note->type = 'note';
			$source = $note->checkNote();
			if($source != false) {
				$note->id = $source;
				$note->type = 'source';
				echo $note->getsource();
				// or redirect to the right request
			} else {
				echo $note->getNote();
			}
	}
}


if (isset($_GET['id'])) {

}
