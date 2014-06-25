<?php
/**
 * Created by IntelliJ IDEA.
 * User: ak
 * Date: 25/04/14
 * Time: 13:27
 */

class data {
	var $type;	// note or source
	var $id;	// id number
	var $access;	// do you have the rights to see notes and sources?
	var $data;

	function data() {


	}

	function checkAccess() {



	}

	function getNote() {

		if($this->access === 'public' && $this->id === 'all') {
			$query = 'WHERE notePublic = 1';
		} else if($this->access === 'public' && $this->id !== 'all') {
			$query = 'WHERE noteID=\'' . $this->id . '\' AND notePublic = 1';
		} else if($this->access !== 'public' && $this->id !== 'all') {
			$query = 'WHERE noteID=\'' . $this->id . '\'';
		} else { // ($this->access !== 'public' && $this->id === 'all')
			$query = '';
		}
		$note = array();
		condb('open');
		$sql = mysql_query('SELECT * FROM note ' . $query . ';');
		condb('close');

		$num_results = mysql_num_rows($sql);
		if($num_results > 0) {
			while ($row = mysql_fetch_object($sql)) {
				// get the category
					$categoryName = getIndex('category', $row->noteCategory);		// delete this query
				// get the project
					$projectName = getIndex('project', $row->noteProject);			// delete this query
				// get the labels and link them
				$labelNames = getIndexMN('note', 'label', $this->id, ' | ', 'link');
				$source2note = array();
				// get the source
				if($row->noteSource != 0) {
					$source = NEW data();
					$source->id = $row->noteSource;
					$sourceData = json_decode($source->getSource(), true);
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
						$source2note = array(
							'id' => $row->noteSource,
							'bibTyp' => array(
								'name' => '',
								'id' => 0
							),
							'extern' => $row->noteSourceExtern
						);
					}
				}

				$note = array(
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
			$note = array(
				'id' => 0
			);
		}
		$this->data = json_encode($note);
		return $this->data;
		// or as another json:
		// return '{"notes":'.json_encode($notes).'}';	// <-- orig!
	}

	function getSource() {
		if($this->access === 'public' && $this->id === 'all') {
			$query = 'WHERE sourcePublic = 1';
		} else if($this->access === 'public' && $this->id !== 'all') {
			$query = 'WHERE sourceID=\'' . $this->id . '\' AND sourcePublic = 1';
		} else if($this->access !== 'public' && $this->id !== 'all') {
			$query = 'WHERE sourceID=\'' . $this->id . '\'';
		} else { // ($this->access !== 'public' && $this->id === 'all')
			$query = '';
		}

		$source = array();
		condb('open');
		$sql = mysql_query('SELECT * FROM source ' . $query . ';');
		$num_results = mysql_num_rows($sql);

		if($num_results > 0) {
			while ($row = mysql_fetch_object($sql)) {
				// get the category
				$categoryName = getIndex('category', $row->sourceCategory);		// delete this query
				// get the project
				$projectName = getIndex('project', $row->sourceProject);		// delete this query
				// get the type
				$bibTyp = getIndex('bibTyp', $row->sourceTyp);
				// get the authors and link them
				$authorNames = getIndexMN('source', 'author', $row->sourceID, ', ', 'link');
				// get the locations and do not link them
				$locationNames = getIndexMN('source', 'location', $row->sourceID, ', ', '');
				// get the labels and link them
				$labelNames = getIndexMN('source', 'label', $row->sourceID, ', ', 'link');

				$source = array(
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

				$selectDetail = mysql_query('SELECT * FROM sourceDetail WHERE sourceID = ' . $this->id . ';');
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
			$source = array(
				'id' => 0
			);
		}


		$this->data = json_encode($source);
		return $this->data;
	}


	function showData() {
		$show = NEW data();
		$show->id = $this->id;
		$show->access = $this->access;
		$open_note = '<div class=\'note\'>';

		$open_media = '<div class=\'media\'>';
		$open_text = '<div class=\'text\'>';
		$open_latex = '<div class=\'latex\'>';
		$open_label = '<div class=\'label\'>';
		$open_tools = '<div class=\'tools\'>';

		$close = '</div>';


		switch($this->type) {
			case 'source';
				$data = json_decode($show->getSource(), true);
				$open_note = '<div class=\'note topic\'>';
				break;

			case 'note';
				$data = json_decode($show->getNote(), true);
				break;

			default;
				$data = json_decode($show->getSource(), true);

		}


		if($data['id'] != 0) {
			echo $open_note;
				echo $open_media . ' ' . $close;
				echo $open_text . $this->id . $close;
				echo $open_latex . ' ' . $close;
				echo $open_label . ' ' . $close;
				echo $open_tools . ' ' . $close;
			echo $close;	// close note element


		} else {
			// no results

		}




	}


}
