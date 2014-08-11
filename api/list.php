<?php
/**
 * Created by IntelliJ IDEA.
 * User: ak
 * Date: 11.08.14
 * Time: 17:57
 */

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
