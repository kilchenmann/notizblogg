<?php
/**
 * Created by IntelliJ IDEA.
 * User: ak
 * Date: 25/04/14
 * Time: 13:27
 */



class note {


	function note() {
		/*
		dieses "function" heisst wie die Klasse
		sobald die Klasse mit NEW erstellt wird, wird diese function als erstes aufgerufen -
		ohne dass wir sie manuell starten
		*/

	}

	function getNote($id, $access) {

		if($access === 'public') {
			$gpa = ' AND notePublic = 1';
			$editNote = '';
		} else {
			$gpa = '';
		}

		condb('open');

		$var = array();

		$noteSql = mysql_query('SELECT * FROM note WHERE noteID=\'' . $id . '\'' . $gpa . ';');


		while($row = mysql_fetch_object($noteSql)) {
			// get the category
			$categoryName = getIndex('category', $row->noteCategory);

			// get the project
			$projectName = getIndex('project', $row->noteProject);

			// get the tags
			$tagNames = getIndexMN('note','tag', $id);

			$var[] = $row;

			/*
			$id = array(
				'noteID' => $row->noteID,
				'noteTitle' => $row->noteTitle,
				'noteContent' => $row->noteContent,
				'noteCategory' => array(
					array('catID' => $row->noteCategory, 'catName' => $categoryName)
				),
				'noteProject' => array(
					array('proID' => $row->noteProject, 'proName' => $projectName)
				),
				'noteSource' => array(
					array('sourceExtern' => $row->noteSourceExtern),
					array('sourceID' => $row->noteSource),
					array('sourcePageStart' => $row->pageStart),
					array('sourcePageEnd' => $row->pageEnd)
				),
				'noteTag' => $tagNames,
				'noteMedia' => $row->noteMedia,
				'notePublic' => $row->notePublic
			);

			*/
		}


		echo '{"notes":' . json_encode($var) . '}';
//		echo '{"notes":' . json_encode($id) . '}';
//		echo json_encode($id) . PHP_EOL;

		condb('close');
	}

	function editNote($id) {


	}

	function saveNote($id) {


	}


}

/*
$note->editNote($nID);
$note->saveNote($nID);
*/