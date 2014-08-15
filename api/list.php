<?php
/**
 * Created by IntelliJ IDEA.
 * User: ak
 * Date: 11.08.14
 * Time: 17:57
 */
header('Content-Type: application/json');

require '../core/bin/php/setting.php';

if(isset($_GET['label'])){
	$note = NEW get();
	$note->id = $_GET['label'];
	$note->type = 'label';
	echo $note->listData();
}
if(isset($_GET['author'])){
	$note = NEW get();
	$note->id = $_GET['author'];
	$note->type = 'author';
	echo $note->listData();
}

if(isset($_GET['new'])){
	// the number behind the query 'new' is to limit to numbers of notes
	// on the public site: get just the sources, order by dateCreated
	// on the private site: get all newest notes
	$note = NEW get();
	$note->id = $_GET['new'];
	$note->type = 'new';
	echo $note->listData();
}

