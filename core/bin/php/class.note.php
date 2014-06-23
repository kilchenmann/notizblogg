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

		if($access === 'public' && $id === 'all') {
			$query = 'WHERE notePublic = 1';
		} else if($access === 'public' && $id !== 'all') {
			$query = 'WHERE noteID=\'' . $id . '\' AND notePublic = 1';
		} else if($access !== 'public' && $id !== 'all') {
			$query = 'WHERE noteID=\'' . $id . '\'';
		} else { // ($access !== 'public' && $id === 'all')
			$query = '';
		}

		condb('open');

		//$notes[] = array();
		$note = new stdClass();
		$note->category = new stdClass();
		$note->project = new stdClass();
		$note->source = new stdClass();
		$noteSql = mysql_query('SELECT * FROM note ' . $query . ';');

		$num_results = mysql_num_rows($noteSql);
		if($num_results > 0) {
			while ($row = mysql_fetch_object($noteSql)) {
				// get the category
				$categoryName = getIndex('category', $row->noteCategory);
				// get the project
				$projectName = getIndex('project', $row->noteProject);
				// get the tags
				$labelNames = getIndexMN('note', 'label', $id, ' | ', 'link');
				// get the labels
//				$labelNames = linkIndexMN('source', 'label', $id, '|');

				$notes = array(
					'id' => $row->noteID,
					'title' => $row->noteTitle,
					'content' => $row->noteContent,
					'category' => array(
						'name' => $categoryName,
						'id' => $row->noteCategory
					),
					'project' => array(
						'name' => $projectName,
						'id' => $row->noteProject
					),
					'label' => array(
						'name' => $labelNames
					),
					'media' => $row->noteMedia,
					'source' => array(
						'id' => $row->noteSource,
						'extern' => $row->noteSourceExtern
					),
					'page' => array(
						'start' => $row->pageStart,
						'end' => $row->pageEnd
					)
				);
			}
		} else {
			$notes = array(
				'id' => 0
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

		if($data['id'] !== 0) {
			echo '<div class=\'note n_' . $data['id'] . '\'>';
				if ($data['media'] !== '') {
					echo '<div class=\'media\'>';
					showMedia($id, $data['media'], $data['title']);
					echo '</div>';
				}

				echo '<div class=\'text\'>';
				echo '<h3>' . $data['title'] . '</h3>';
				echo '<p>' . makeurl($data['content']) . '</p>';
				if($data['source']['id'] != ''){
					$source = NEW source();
					$sourceData = json_decode($source->getSource($data['source']['id'], $access), true);
					if($sourceData['bibTyp']['name'] != 'project'){
						$pages = "";
						if($data['page']['start'] != 0){
							$pages = $data['page']['start'];
							if($data['page']['end'] != 0) {
								$pages .= '-' . $data['page']['end'];
							}
						}
						echo '<p class=\'small\'>\cite[][' . $pages . ']{' . $sourceData['name'] . '}</p>';
					}

				}



				echo '</div>';

				echo '<div class=\'tools\'>';
					echo '<div class=\'left\'>';
						echo '<p>' . $data['label']['name'] . '</p>';
					echo '</div>';
					echo '<div class=\'right\'>';
						if (isset ($_SESSION["token"]) && $access === 'private') {
							echo '<p>edit</p>';
						}
					echo '</div>';
				echo '</div>';
			echo '</div>';
		}

	}

	function editNote($id) {


	}

	function saveNote($id) {


	}


}
