<?php
/**
 * Created by IntelliJ IDEA.
 * User: ak
 * Date: 01.07.14
 * Time: 23:23
 */

require '../core/bin/php/setting.php';

	// default values; in case of wrong queries; these variables would be overwritten in the right case
	if (isset($_GET['source'])) {
		$source = NEW get();
		$source->id = $_GET['source'];
		$source->access = 'private';

		echo $source->getSource();

	}
	if (isset($_GET['note'])) {
		$note = NEW get();
		$note->id = $_GET['note'];
		$note->access = 'private';

		echo $note->getNote();
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
