<?php
/**
 * Created by IntelliJ IDEA.
 * User: ak
 * Date: 25/04/14
 * Time: 13:27
 */

class source {

	function source() {
		/*
		dieses "function" heisst wie die Klasse
		sobald die Klasse mit NEW erstellt wird, wird diese function als erstes aufgerufen -
		ohne dass wir sie manuell starten
		*/

	}

	function getSource($id, $access) {



		if($access === 'public' && $id === 'all') {
			$query = 'WHERE sourcePublic = 1';
		} else if($access === 'public' && $id !== 'all') {
			$query = 'WHERE sourceID=\'' . $id . '\' AND sourcePublic = 1';
		} else if($access !== 'public' && $id !== 'all') {
			$query = 'WHERE sourceID=\'' . $id . '\'';
		} else { // ($access !== 'public' && $id === 'all')
			$query = '';
		}

		condb('open');

		//$sources = array();
		$source = new stdClass();
		$source->category = new stdClass();
		$source->project = new stdClass();
		$source->source = new stdClass();
		$sourceSql = mysql_query('SELECT * FROM source ' . $query . ';');

		$num_results = mysql_num_rows($sourceSql);

		if($num_results > 0) {
			while ($row = mysql_fetch_object($sourceSql)) {
				// get the category
				$categoryName = getIndex('category', $row->sourceCategory);
				// get the project
				$projectName = getIndex('project', $row->sourceProject);
				// get the type
				$bibTyp = getIndex('bibTyp', $row->sourceTyp);
				// get the authors
				$authorNames = getIndexMN('source', 'author', $row->sourceID, ', ', 'link');
				// get the locations
				$locationNames = getIndexMN('source', 'location', $row->sourceID, ', ', '');
				// get the labels
				$labelNames = getIndexMN('source', 'label', $row->sourceID, ', ', 'link');


				$sources = array(
					'id' => $row->sourceID,
					'name' => $row->sourceName,
					'title' => $row->sourceTitle,
					'subtitle' => $row->sourceSubtitle,
					'year' => $row->sourceYear,

					'bibTyp' => array(
						'name' => $bibTyp,
						'id' => $row->sourceTyp
					),
					'category' => array(
						'name' => $categoryName,
						'id' => $row->sourceCategory
					),
					'project' => array(
						'name' => $projectName,
						'id' => $row->sourceProject
					),
					'editor' => $row->sourceEditor,
					'author' => array(
						'name' => $authorNames
					),
					'location' => array(
						'name' => $locationNames
					),
					'label' => array(
						'name' => $labelNames
					),
					'comment' => $row->sourceNote,
				);

				$selectDetail = mysql_query('SELECT * FROM sourceDetail WHERE sourceID = ' . $id . ';');
				$num_results = mysql_num_rows($selectDetail);
				$i = 0;
				if($num_results > 0) {
					while($row = mysql_fetch_object($selectDetail)){
						$bibFieldID = $row->bibFieldID;
						$sourceDetailName = $row->sourceDetailName;
						$selectField = mysql_query('SELECT bibFieldName FROM bibField WHERE bibFieldID = ' . $bibFieldID . ';');

						while($row = mysql_fetch_object($selectField)) {
							$bibFieldName = $row->bibFieldName;
							if($bibFieldName === 'crossref'){
								$selectSource = mysql_query('SELECT sourceName FROM source WHERE sourceID = ' . $sourceDetailName . ';');
								while($inrow = mysql_fetch_object($selectSource)) {
									$sources['crossref'] = array('id' => $sourceDetailName, 'name' => $inrow->sourceName);
									// get the locations
									// $locationNames = linkIndexMN('source', 'location', $sourceDetailName, ',');
								}
							} else {
								if (!isset($sources['detail'][$bibFieldName])) {
									$sources['detail'][$bibFieldName] = $sourceDetailName;
								} else {
									$sources['detail'][$bibFieldName] = $sourceDetailName;
								}

								/*
								else if (!is_array($sources['detail'])) {
									echo '2' . $bibFieldName;
									$sources['detail'] = array($bibFieldName => $sourceDetailName);

								}
								*/
								//array_push($sources['detail'], array($bibFieldName => $sourceDetailName));
//								echo $bibFieldName . ' => ' . $sourceDetailName;

							}

//						$num_results--;
						}
						$i++;
					}
				}


			}
		} else {
			$sources = array(
				'id' => 0
			);
		}
		condb('close');

		return json_encode($sources);

		// or as another json:
		// return '{"sources":'.json_encode($sources).'}';	// <-- orig!
	}




	function showSource($id, $access) {
		$source = NEW source();
		$data = json_decode($source->getSource($id, $access), true);
		//print_r($data);

		if($data['id'] !== 0) {
			echo '<div class=\'note topic\' id=\'' . $data['id'] . '\'>';
				echo '<div class=\'text\'>';
					echo '<p>';
						$source->showBib($data, $access);
					echo '</p>';

				echo '</div>';
				echo '<div class=\'latex\'>';
					$source->showTex($data, $access);
				echo '</div>';

				if($data['label']['name'] != '') {
					echo '<div class=\'label\'>';
						echo '<p>' . $data['label']['name'] . '</p>';
					echo '</div>';
				}

				echo '<div class=\'tools\'>';
					echo '<div class=\'left\'>';
						if($access != 'public' && isset($_SESSION['token'])) {
							echo '<button class=\'btn grp_none toggle_edit\' id=\'edit_source_' . $id . '\'></button>';
						} else {
							echo '<button class=\'btn grp_none fake_btn\'></button>';
						}
					echo '</div>';
					echo '<div class=\'center\'>';
						if($data['id'] != 0 && $data['bibTyp']['name'] != 'projcet'){
							echo '<button class=\'btn grp_none toggle_cite\' id=\'cite_source_' . $id . '\'></button>';
						} else {
							echo '<button class=\'btn grp_none fake_btn\'></button>';
						}
					echo '</div>';
					echo '<div class=\'right\'>';
						echo '<button class=\'btn grp_none toggle_expand\' id=\'expand_source_' . $id . '\'></button>';
					echo '</div>';
				echo '</div>';
			echo '</div>';
		} else {
			// no results
		}
	}





	function editSource($id) {


	}

	function saveSource($id) {


	}


}
