<?php
/**
 * Created by IntelliJ IDEA.
 * User: ak
 * Date: 01.07.14
 * Time: 23:23
 */
session_start ();
$access = 'public';
$user = '--';
$uid = '';

$protocol = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
require '../core/bin/php/setting.php';

if (!isset ($_SESSION["token"])) {
	$access = 'public';
	$user = '--';
	$uid = '';
} else {
	condb('open');
	$token = (explode("-",$_SESSION["token"]));
	$sql = mysql_query("SELECT username FROM user WHERE uid = " . $token[1] . " AND token = '" . $token[0] . "';");
	while($row = mysql_fetch_object($sql)){
		$user = $row->username;
	}
	condb('close');

	if($user != '') {
		$access = 'private';
		$uid = $token[1];
	} else {
		$user = '--';
	}
}

if (isset($_GET['id'])) {
	//echo 'The ID is: ' . $_GET['id'];
	$note = NEW get();
	$note->id = $_GET['id'];
	$note->access = 'private';
	$note->type = 'note';
	echo $note->getData();
}




// /*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*//*/*/*/*/*/*/*/*/*/*/*/*/*/*/
// /*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*//*/*/*/*/*/*/*/*/*/*/*/*/*/*/
// /*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*//*/*/*/*/*/*/*/*/*/*/*/*/*/*/
// /*/*/*/*/*/*/*/*/*/*/*/*/*/*/*/*//*/*/*/*/*/*/*/*/*/*/*/*/*/*/



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
