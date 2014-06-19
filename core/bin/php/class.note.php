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

		//$notes[] = array();
		$note = new stdClass();
		$note->category = new stdClass();
		$note->project = new stdClass();
		$note->source = new stdClass();
		$noteSql = mysql_query('SELECT * FROM note WHERE noteID=\'' . $id . '\'' . $gpa . ';');

		while($row = mysql_fetch_object($noteSql)) {
			// get the category
			$categoryName = getIndex('category', $row->noteCategory);
			// get the project
			$projectName = getIndex('project', $row->noteProject);
			// get the tags
			$tagNames = getIndexMN('note','tag', $id);
			// set the content correct
			$noteContent = ($row->noteContent);

			$notes = array(
				'id' => $row->noteID,
				'title' => $row->noteTitle,
				'content' => $noteContent,
				'category' => array(
					'name' => $categoryName,
					'id' => $row->noteCategory
				),
				'project' => array(
					'name' => $projectName,
					'id' => $row->noteProject
				),
				'tag' => array(
					'name' => $tagNames
				),
				'media' => $row->noteMedia
			);
		}
		condb('close');

		return json_encode($notes);

		// or as another json:
		// return '{"notes":'.json_encode($notes).'}';	// <-- orig!
	}

	function showNote($id, $access) {
		$note = NEW note();
		$data = json_decode($note->getNote($id, $access), true);

		if($data['media'] !== ''){
			echo '<div class=\'media\'>';
				showMedia($id, $data['media'], $data['title']);
			echo '</div>';
		}

		echo '<div class=\'text\'>';
			echo '<h3>' . $data['title'] . '</h3>';
			echo '<p>' . makeurl($data['content']) . '</p>';
		echo '</div>';

		echo '<div class=\'tools\'>';
		echo '<p><a href=\'?label=' . $data['category']['id'] . '\'>' . $data['category']['name'] . '</a></p>';

		echo '</div>';

	}

	function editNote($id) {


	}

	function saveNote($id) {


	}


}
