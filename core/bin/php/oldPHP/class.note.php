<?php
/**
 * Created by IntelliJ IDEA.
 * User: ak
 * Date: 25/04/14
 * Time: 13:27
 */

class note {
	var $id;
	var $access;

	function note() {
		/*
		dieses "function" heisst wie die Klasse
		sobald die Klasse mit NEW erstellt wird, wird diese function als erstes aufgerufen -
		ohne dass wir sie manuell starten
		*/

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

		condb('open');

		//$notes[] = array();
		$note = new stdClass();
		$note->category = new stdClass();
		$note->project = new stdClass();
		$note->source = new stdClass();
		$notes = array();
		$noteSql = mysql_query('SELECT * FROM note ' . $query . ';');


		$num_results = mysql_num_rows($noteSql);
		if($num_results > 0) {
			while ($row = mysql_fetch_object($noteSql)) {
				// get the category
				$categoryName = getIndex('category', $row->noteCategory);
				// get the project
				$projectName = getIndex('project', $row->noteProject);
				// get the tags
				$labelNames = getIndexMN('note', 'label', $this->id, ' | ', 'link');
				// get the labels
//				$labelNames = linkIndexMN('source', 'label', $this->id, '|');
				// get the source
				if($row->noteSource != 0) {
					$source = NEW source();
					$sourceData = json_decode($source->getSource($row->noteSource, $this->access), true);

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

	function showNote() {
		$note = NEW note();
		$note->id = $this->id;
		$note->access =  $this->access;
		$data = json_decode($note->getNote(), true);
		//print_r($data);



	}

	function editNote() {


	}

	function saveNote() {


	}


}
