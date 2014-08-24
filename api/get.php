<?php
/**
 * Created by IntelliJ IDEA.
 * User: ak
 * Date: 01.07.14
 * Time: 23:23
 */
header('Content-Type: application/json; charset=utf-8');

 // access public: true = 1
 // access !public = private = 0
session_start ();

$protocol = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";

require '../core/bin/php/setting.php'; require '../core/bin/php/class.get.php';

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

/*

// check the access
if (isset ($_SESSION["token"])) {
	$token = (explode("-",$_SESSION["token"]));

	$mysqli = condb('open');
	$sql = $mysqli->query("SELECT user FROM user WHERE userID = " . $token[1] . " AND token = '" . $token[0] . "';");
	condb('close');
	$num_results = mysqli_num_rows($sql);
	if($num_results == 1) {
		$access = 0;		// public access is false -> private access
		$uid = $token[1];
	}
}
*/

foreach ($_GET as $key => $value){
	switch ($key) {
		/* change the first two ones */
		case 'note';
			$note = NEW get();
			$note->id = $_GET['note'];
			$note->type = 'note';
			$note->access = $user['access'];
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
			$note = NEW get();
			$note->id = $_GET['source'];
			$note->type = 'source';
			$note->access = $user['access'];
			echo $note->getSource();
			break;
		/* -.-.-.-.-.-.-.-.-.-.-.-.-.-.-.-.-.-.-. */

		case 'label';
			$note = NEW get();
			$note->id = $_GET['label'];
			$note->type = 'label';
			echo $note->listData();
			break;

		case 'author';
			$note = NEW get();
			$note->id = $_GET['author'];
			$note->type = 'author';
			echo $note->listData();
			break;

		case 'new';
			// the number behind the query 'new' is to limit to numbers of notes
			// on the public site: get just the sources, order by dateCreated
			// on the private site: get all newest notes
			$note = NEW get();
			$note->id = $_GET['new'];
			$note->type = 'new';
			echo $note->listData();
			break;

		case 'q';
			$part = '';
			if (isset($_GET['part'])) {
				$part = $_GET['part'];
			}
			$note = NEW get();
			$note->query = $_GET['q'];
			$note->part = $part;
			echo $note->searchData();
			break;

		case 'id';
			$note = NEW get();
			$note->id = $_GET['id'];
			$note->type = 'note';
			$note->access = $user['access'];
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

	}
}


if (isset($_GET['id'])) {

}




// /*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*//*/*/*/*/*/*/*/*/*/*/*/*/*/*/
// /*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*//*/*/*/*/*/*/*/*/*/*/*/*/*/*/
// /*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*//*/*/*/*/*/*/*/*/*/*/*/*/*/*/
// /*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*//*/*/*/*/*/*/*/*/*/*/*/*/*/*/



/*
 * get.php old version
	// default values; in case of wrong queries; these variables would be overwritten in the right case
	if (isset($_GET['source'])) {
		$source = NEW get();
		$source->access = $access;

		if($_GET['source'] == 'all') {

			condb('open');
			$sql = mysql_query('SELECT sourceID, sourceName, sourcePublic FROM source ORDER BY sourceName');
			condb('close');
			$allSources = array('allSources' => array());
			$i = 0;
			while ($row = mysql_fetch_object($sql)) {
				$source = array(
					'type' => 'source',
					'public' => $row->sourcePublic,
					'id' => $row->sourceID,
					'name' => $row->sourceName
				);

				array_push($allSources['allSources'], $source);

				$i++;
			}
			echo json_encode($allSources);

		} else if ($_GET['source'] == 'last') {
			condb('open');
			$sql = mysql_query('SELECT sourceID, sourceName, sourcePublic FROM source ORDER BY date DESC LIMIT 0, 1');
			condb('close');
			$lastSources = array('lastSource' => array());
			$i = 0;
			while ($row = mysql_fetch_object($sql)) {
				$source = array(
					'type' => 'source',
					'public' => $row->sourcePublic,
					'id' => $row->sourceID,
					'name' => $row->sourceName
				);
				array_push($lastSources['lastSource'], $source);

				$i++;
			}
			echo json_encode($lastSources);


		} else {

			$source->id = $_GET['source'];

			echo $source->getSource();

		}


	}
	if (isset($_GET['note'])) {
		$note = NEW get();
		$note->id = $_GET['note'];
		$note->access = $access;
		$note->type = 'note';
		echo $note->getData();
	}
*/

	/*
	if (isset($_GET['label'])) {
		$type = 'label';
		$query = $_GET['label'];
	}
	if (isset($_GET['author'])) {
		$type = 'author';
		$query = $_GET['author'];
	}
	if (isset($_GET['search'])) {
		$type = 'search';
		$query = $_GET['search'];
	}
	*/
