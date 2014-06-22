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
				$authorNames = linkIndexMN('source', 'author', $row->sourceID, ',');
				// get the locations
				$locationNames = linkIndexMN('source', 'location', $row->sourceID, ',');


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

//		echo '<div class="note">';
//		print_r ($source->getSource($id, $access));
//		echo '</div>';

		if($data['id'] !== 0) {
			echo '<div class=\'note ' . $data['id'] . '\'>';
			echo '<div class=\'text\'>';
			if($data['bibTyp']['id'] !== '') {
				echo '@' . $data['bibTyp']['name'] . '{' . $data['name'] . ',<br>';
			}
			if($data['editor'] == 1){
				echo 'editor = { ' . ($data['author']['name']) . '},<br>';
			} else {
				echo 'author = {' . ($data['author']['name']) . '},<br>';
			}
			echo 'title = {' . ($data['title']) . '},<br>';

			if($data['subtitle'] != ''){
				echo 'subtitle = {' . ($data['subtitle']) . '},<br>';
			}
			if(array_key_exists('crossref', $data)) {
				echo 'crossref = {<a href=\'?source=' . $data['crossref']['id'] . '\'>' . ($data['crossref']['name']) . '</a>},<br>';
			}
			if(array_key_exists('detail', $data)) {
				$countDetail = count(array_keys($data['detail']));
				$i = 0;
				while ($countDetail > 0) {
					$detail = array_keys($data['detail']);
					if($detail[$i] === 'url') {
						echo $detail[$i] . ' = {<a href=\'' . $data['detail'][$detail[$i]] . '\' target=\'_blank\'>' . $data['detail'][$detail[$i]] . '</a>},<br>';
					} else {
						echo $detail[$i] . ' = {' . $data['detail'][$detail[$i]] . '},<br>';
					}
					$countDetail--;
					$i++;
				}
			}

			if($data['location']['name'] != ''){
				echo 'location = {' . ($data['location']['name']) . '},<br>';
			}
			if($data['year'] != '0000'){
				echo 'year = {' . $data['year'] . '},<br>';
			}
			echo 'note = {' . $data['comment'] . '}}';

/*
					echo '<p>' . $data['author']['name'] . '</p>';
					echo '<h3>' . $data['title'] . '</h3>';
					echo '<p>' . $data['subtitle'] . '</p>';
					echo '<p>' . makeurl($data['comment']) . '</p>';
			*/
				echo '</div>';
				echo '<div class=\'tools\'>';
					echo '<div class=\'left\'>';
						echo '<p><a href=\'?label=' . $data['category']['id'] . '\'>' . $data['category']['name'] . '</a></p>';
						echo '<p><a href=\'?label=' . $data['project']['id'] . '\'>' . $data['project']['name'] . '</a></p>';
					echo '</div>';
					echo '<div class=\'right\'>';
						echo '<p>info</p>';
						if (isset ($_SESSION["token"]) && $access === 'private') {
							echo '<p>edit</p>';
						}
					echo '</div>';
				echo '</div>';
			echo '</div>';
		}
	}

	function editSource($id) {


	}

	function saveSource($id) {


	}


}
