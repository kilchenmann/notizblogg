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
				// get the source
				if($row->noteSource != 0) {
					$source = NEW source();
					$sourceData = json_decode($source->getSource($row->noteSource, $access), true);

					//print_r($sourceData);
					if ($sourceData['bibTyp']['id'] != '') {
						$source2note = array(
							'id' => $row->noteSource,
							'name' => $sourceData['name'],
							'title' => $sourceData['title'],
							'subtitle' => $sourceData['subtitle'],
							'year' => $sourceData['year'],

							'bibTyp' => array(
								'name' => $sourceData['bibTyp']['name'],
								'id' => $sourceData['bibTyp']['id']
							),
							'category' => array(
								'name' => $sourceData['category']['name'],
								'id' => $sourceData['category']['id']
							),
							'project' => array(
								'name' => $sourceData['project']['name'],
								'id' => $sourceData['project']['id']
							),
							'editor' => $sourceData['editor'],
							'author' => array(
								'name' => $sourceData['author']['name']
							),
							'location' => array(
								'name' => $sourceData['location']['name']
							),
							'label' => array(
								'name' => $sourceData['label']['name']
							),
							'comment' => $sourceData['comment'],
							'extern' => $row->noteSourceExtern
						);

					} else {

						//		} else {
						$source2note = array(
							'id' => $row->noteSource,
							'bibTyp' => array(
								'name' => 'project',
								'id' => 0
							),
							'extern' => $row->noteSourceExtern
						);
					}
				}



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
					'source' => $source2note,
					'page' => array(
						'start' => $row->pageStart,
						'end' => $row->pageEnd
					),
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
		//print_r($data);

		if($data['id'] !== 0) {
			echo '<div class=\'note\' id=\'' . $data['id'] . '\'>';
				// show media, if exist
				if ($data['media'] !== '') {
					echo '<div class=\'media\'>';
					showMedia($id, $data['media'], $data['title']);
					echo '</div>';
				}
				// show text
				echo '<div class=\'text\'>';
				echo '<h3>' . $data['title'] . '</h3>';
				echo '<p>' . makeurl($data['content']) . '</p>';
				if($data['source']['id'] != 0 && $data['source']['bibTyp']['name'] != 'projcet'){
						$pages = "";
						if($data['page']['start'] != 0){
							$pages = $data['page']['start'];
							if($data['page']['end'] != 0) {
								$pages .= '-' . $data['page']['end'];
							}
						}
						echo '<p class=\'small\'>(' . $data['source']['author']['name'] .': <a href=\'?source=' . $data['source']['id'] . '\'>' . $data['source']['title'] . '</a>, S. ' . $pages . ')</p>';
//						echo '<p class=\'small\'>\cite[][' . $pages . ']{' . $sourceData['name'] . '}</p>';

				}
				echo '</div>';

				echo '<div class=\'latex\'>';
				echo '<h3>' . $data['title'] . '</h3>';
				echo '<p>``' . change4Tex(makeurl($data['content'])) . '\'\'</p>';
				if($data['source']['id'] != 0 && $data['source']['bibTyp']['name'] != 'projcet'){
//					$source = NEW source();
//					$sourceData = json_decode($source->getSource($data['source']['id'], $access), true);
//					if($sourceData['bibTyp']['name'] != 'project'){
						$pages = "";
						if($data['page']['start'] != 0){
							$pages = $data['page']['start'];
							if($data['page']['end'] != 0) {
								$pages .= '-' . $data['page']['end'];
							}
						}
						echo '<p class=\'small\'>\cite[][' . $pages . ']{' . $data['source']['name'] . '}</p>';
//					}
				}
				echo '</div>';
				if($data['label']['name'] != '') {
					echo '<div class=\'label\'>';
						echo '<p>' . $data['label']['name'] . '</p>';
					echo '</div>';
				}
				echo '<div class=\'tools\'>';
						echo '<div class=\'left\'>';
							if($access != 'public' && isset($_SESSION['token'])) {
								echo '<button class=\'btn grp_none toggle_edit\' id=\'edit_note_' . $id . '\'></button>';
							} else {
								echo '<button class=\'btn grp_none fake_btn\'></button>';
							}
						echo '</div>';
							echo '<div class=\'center\'>';
							if($data['source']['id'] != 0 && $data['source']['bibTyp']['name'] != 'projcet'){
								echo '<button class=\'btn grp_none toggle_cite\' id=\'cite_note_' . $id . '\'></button>';
							} else {
								echo '<button class=\'btn grp_none fake_btn\'></button>';
							}
							echo '</div>';
						echo '<div class=\'right\'>';
							echo '<button class=\'btn grp_none toggle_expand\' id=\'expand_note_' . $id . '\'></button>';
						echo '</div>';
					echo '</div>';
				echo '</div>';
		} else {
			// no results

		}

	}

	function editNote($id) {


	}

	function saveNote($id) {


	}


}
