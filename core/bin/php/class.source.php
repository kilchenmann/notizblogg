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

	function showTex($data, $access) {
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
			$inSource = New source();
			$inData = json_decode($inSource->getSource($data['crossref']['id'], $access), true);
			echo 'crossref = {<a href=\'?source=' . $data['crossref']['id'] . '\'>' . ($data['crossref']['name']) . '</a>},<br>';
			if($inData['editor'] == 1){
				echo 'editor = { ' . ($inData['author']['name']) . '},<br>';
			} else {
				echo 'author = {' . ($inData['author']['name']) . '},<br>';
			}
			echo 'booktitle = {' . ($inData['title']) . '},<br>';

			if($inData['subtitle'] != ''){
				echo 'booksubtitle = {' . ($inData['subtitle']) . '},<br>';
			}

			if($inData['location']['name'] != ''){
				echo 'location = {' . ($inData['location']['name']) . '},<br>';
			}

		}

		if(array_key_exists('detail', $data)) {
			$countDetail = count(array_keys($data['detail']));
			$i = 0;
			while ($countDetail > 0) {
				$detail = array_keys($data['detail']);
				if($detail[$i] === 'url') {
					echo $detail[$i] . ' = {<a target=\'_blank\' href=\'' . $data['detail'][$detail[$i]] . '\' >' . $data['detail'][$detail[$i]] . '</a>},<br>';
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

	}
	function showBib($data, $access) {
		if($data['bibTyp']['name'] !== '') {
			//echo '@' . $data['bibTyp']['name'] . '{' . $data['name'] . ',<br>';
			if($data['editor'] == 1){
				echo $data['author']['name'] . ' (Hg.):<br>';
			} else {
				echo $data['author']['name'] . ':<br>';
			}
			echo $data['title'] . '. ';

			if($data['subtitle'] != ''){
				echo $data['subtitle'] . '.<br>';
			}
			if(array_key_exists('crossref', $data)) {
				echo 'In: ';
				$inSource = New source();
				$inData = json_decode($inSource->getSource($data['crossref']['id'], $access), true);

				if($inData['editor'] == 1){
					echo $inData['author']['name'] . ' (Hg.):<br>';
				} else {
					echo $inData['author']['name'] . ':<br>';
				}
				echo '<a href=\'?source=' . $data['crossref']['id'] . '\'>' . $inData['title'] . '. </a>';

				if($inData['subtitle'] != ''){
					echo $inData['subtitle'] . '.<br>';
				}
				if($inData['location']['name'] != ''){
					echo $inData['location']['name'] . ', ';
				}
				if($inData['year'] != '0000'){
					echo $inData['year'] . '';
				}
			} else {
				if($data['location']['name'] != ''){
					echo $data['location']['name'] . ', ';
				}
				if($data['year'] != '0000'){
					echo $data['year'] . '';
				}
			}
			if(array_key_exists('detail', $data)) {
				$countDetail = count(array_keys($data['detail']));
				$i = 0;
				while ($countDetail > 0) {
					$detail = array_keys($data['detail']);
					switch ($detail[$i]) {
						case 'url';
							echo ', URL: <a target=\'_blank\' href=\'' . $data['detail'][$detail[$i]] . '\'>' . $data['detail'][$detail[$i]] . '</a> ';
							break;

						case 'urldate';
							echo '(Stand: ' . $data['detail'][$detail[$i]] . ').';
							break;

						case 'pages';
							echo ', S. ' . $data['detail'][$detail[$i]];

							break;

						default;
							echo $data['detail'][$detail[$i]];


					}

					$countDetail--;
					$i++;
				}
			}
			echo '.<br>';
		} else {
			echo $data['comment'];
		}



		//echo 'note = {' . $data['comment'] . '}}';

	}


	function showSource($id, $access) {
		$source = NEW source();
		$data = json_decode($source->getSource($id, $access), true);

		if($data['id'] !== 0) {
			echo '<div class=\'note topic s_' . $data['id'] . '\'>';
				echo '<div class=\'text\'>';
					echo '<p>';
						$source->showBib($data, $access);
					echo '</p>';

				echo '</div>';
				echo '<div class=\'tooltip\'>';
					$source->showTex($data, $access);
				echo '</div>';

				echo '<div class=\'tools\'>';
					echo '<div class=\'left\'>';
						echo '<p>' . $data['label']['name'] . '</p>';
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
