<?php
/**
 * Created by IntelliJ IDEA.
 * User: ak
 * Date: 25.06.14
 * Time: 16:50
 */

class get {
	var $id;	// id number
	var $access;	// do you have the rights to see notes and sources?
	var $data;

	function get() {


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
				condb('open');
				// get the category
				$categoryName = getIndex('category', $row->noteCategory);		// delete this query
				// get the project
				$projectName = getIndex('project', $row->noteProject);			// delete this query
				// get the labels and link them
				$labelNames = getIndexMN('note', 'label', $row->noteID, ', ');
				condb('close');
				$source2note = array();
				// get the source
				if($row->noteSource != 0) {
					$source = NEW get();
					$source->id = $row->noteSource;
					$sourceData = json_decode($source->getSource(), true);

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
					'setting' => array(
						'checkID' => $row->checkID,
						'date' => $row->date,
						'public' => $row->notePublic
					),
					'type' => 'note',
					'public' => $row->notePublic,
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
					'label' => $labelNames,
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

		//return $note;

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
		condb('close');
		$num_results = mysql_num_rows($sql);

		if($num_results > 0) {
			condb('open');
			while ($row = mysql_fetch_object($sql)) {
				// get the category
				$categoryName = getIndex('category', $row->sourceCategory); // delete this query
				// get the project
				$projectName = getIndex('project', $row->sourceProject); // delete this query
				// get the type
				$bibTyp = getIndex('bibTyp', $row->sourceTyp);
				// get the authors and link them
				$authorNames = getIndexMN('source', 'author', $row->sourceID, ', ');
				// get the locations and do not link them
				$locationNames = getIndexMN('source', 'location', $row->sourceID, ', ');
				// get the labels and link them
				$labelNames = getIndexMN('source', 'label', $row->sourceID, ', ');
			condb('close');
				$source = array(
					'type' => 'source',
					'public' => $row->sourcePublic,
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
				condb('open');
				$selectDetail = mysql_query('SELECT * FROM sourceDetail WHERE sourceID = ' . $this->id . ';');
				condb('close');
				$num_results = mysql_num_rows($selectDetail);
				if ($num_results > 0) {
					while ($row = mysql_fetch_object($selectDetail)) {
						$bibFieldID = $row->bibFieldID;
						$sourceDetailName = $row->sourceDetailName;
						condb('open');
						$selectField = mysql_query('SELECT bibFieldName FROM bibField WHERE bibFieldID = ' . $bibFieldID . ';');
						condb('close');

						while ($row = mysql_fetch_object($selectField)) {
							$bibFieldName = $row->bibFieldName;
							if ($bibFieldName == 'crossref') {
								$inSource = NEW get();
								$inSource->id = $sourceDetailName;
								$sourceData = json_decode($inSource->getSource(), true);

								if ($sourceData['bibTyp']['id'] != '') {
									$crossref = array(
										'id' => $sourceDetailName,
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
										'comment' => $sourceData['comment']
									);
									$source['crossref'] = $crossref;
								}


								/*
								$inSource = NEW get();
								$inSource->id = $sourceDetailName;
								$inSource->access = $this->access;
								$crossref = json_decode($inSource->getSource(), true);

								$source['crossref']['id'] = $crossref['id'];
								$source['crossref']['title'] = $crossref['title'];
*/


								/*
								$selectSource = mysql_query('SELECT sourceName FROM source WHERE sourceID = ' . $sourceDetailName . ';');
								while($inrow = mysql_fetch_object($selectSource)) {
									$sources['crossref'] = array('id' => $sourceDetailName, 'name' => $inrow->sourceName);
									// get the locations
									// $locationNames = linkIndexMN('source', 'location', $sourceDetailName, ',');
								}
								*/
							} else {
								if (!isset($source['detail'][$bibFieldName])) {
									$source['detail'][$bibFieldName] = $sourceDetailName;
								} else {
									$source['detail'][$bibFieldName] = $sourceDetailName;
								}
							}
						}
					}
				}
				//get also the noteIDs to this source
				condb('open');
				$noteSql = mysql_query("SELECT noteID FROM note WHERE noteSource=" . $this->id . " ORDER BY pageStart, noteTitle ASC");
				condb('close');
//				echo "SELECT noteID FROM note WHERE noteSource=" . $this->id . " ORDER BY pageStart, noteTitle ASC";
				$notes = array();
				$num_results = mysql_num_rows($noteSql);
				if ($num_results > 0) {
					while ($row = mysql_fetch_object($noteSql)) {
						array_push($notes, $row->noteID);
					}
				}
				$source['notes'] = $notes;
			}
		} else {
			$source = array(
				'id' => 0
			);
		}

		//return $source;



		$this->data = json_encode($source);

//		print_r($this->data);
		return $this->data;
	}
}