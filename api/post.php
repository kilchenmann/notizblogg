<?php

header('Content-Type: application/json; charset=utf-8');

 // access public: true = 1
 // access !public = private = 0
session_start ();

$protocol = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";

require '../core/bin/php/settings.php';
require '../core/bin/php/class.post.php';

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
} else if (isset($_SESSION['token'])) {
	// without the token, check the user with the session parameters
	$user = conuser($_SESSION['token']);
}


if((!empty($_POST) || !empty($_FILES)) && $user['access'] == 0) {

	// the user has the right to edit, to create and to upload media files

	$note = NEW post();
	$note->access = $user['access'];
	$note->user = $user['id'];

	foreach ($_GET as $key => $value) {

		switch ($key) {
			case 'note';
				$note->id = $_GET['note'];
				$note->data = $_POST;
				echo $note->updateNote();

				break;

			case 'source';


				break;


			case 'media';
				$note->data = $_FILES;
				echo $note->uploadMedia();


				break;

			default;


		}
	}

} else {
	echo json_encode(array(
		'error' => true,
		'msg'   => "Something went totally wrong!"
	));
	exit;
}



/*



	$checkID = $_POST['checkID'];
	$noteID = $_POST['noteID'];
	$sourceID = $_POST['sourceID'];
	$tmp_title = explode('//', $_POST['title']);
		$title = $tmp_title[0];
		if(isset($tmp_title[1])) {
			$subtitle = $tmp_title[1];
		} else {
			$subtitle = null;
		}
	$comment = htmlentities($_POST['comment'], ENT_QUOTES, 'UTF-8');
	$labels = explode(',', $_POST['label']);
	$tmp_pages = explode('-', $_POST['pages']);

	if(strpos($_POST['pages'], '-') !== false) {
		$page_start = $tmp_pages[0];
		$page_end = $tmp_pages[1];
		if($page_end != '') {
			if($page_end < $page_start) $page_end = '0';
		} else {
			$page_end = '0';
		}
	} else {
		$page_start = $_POST['pages'];
		$page_end = '0';
	}


	//$media =


	if(!empty($comment) && !empty($sourceID))
	{
		// some checks and request first
		$mysqli = condb('open');
		//$bibsql = $mysqli->query('SELECT bibID ')


		if($checkID == '') {

		}



		// update the data
		$sql = $mysqli->query('UPDATE note SET ' .
								'noteTitle=\'' . $title . '\', ' .
								'noteSubtitle=\'' . $subtitle . '\', ' .
								'noteComment=\'' . $comment . '\', ' .
								'bibID=\'' . $sourceID . '\', ' .
								'pageStart=\'' . $page_start. '\', ' .
								'pageEnd=\'' . $page_end. '\', ' .
								'userID=\'' . $user['id']. '\' ' .
								'WHERE noteID = ' . $noteID . ';');



			//WHERE noteID = ' . $this->id . ' AND notePublic >= ' . $this->access . ';');
		condb('close');

		echo json_encode(array(
			'error' => false,
		));
		exit;






	}else{
		echo json_encode(array(
			'error' => true,
			'msg'   => "Something went totally wrong!"
		));
		exit;
	}


}




/*

$note = NEW post();
$note->access = $user['access'];

//$note->updateNote($_POST['data']);
if(isset($_POST['data'])) {
	foreach ($_GET as $key => $value){

		switch ($key) {
			case 'note';	// update
				$note->id = $_GET['note'];
				$note->type = 'note';
				$source = $note->checkNote();
				if($source != false) {
					$note->id = $source;
					$note->type = 'source';
					echo $note->updateSource($_POST['data']);
					// or redirect to the right request
				} else {
					echo $note->updateNote($_POST['data']);
				}
			break;

			case 'source';  // update
				$note->id = $_GET['source'];
				$note->type = 'source';
				$note->access = $user['access'];
			//	echo $note->updateSource();
			break;

			default;		// new
		}

	}
} else {
	$data = array(
		'message' => "no post"
	);
	return json_encode($data);
}
*/
