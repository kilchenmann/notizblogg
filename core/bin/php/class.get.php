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
	var $type;
	var $part;
	var $query;
	var $json;

	function get() {


	}
	function checkNote() {
		$source = false;
		$mysqli = condb('open');
		$sql = $mysqli->query('SELECT bibID FROM bib WHERE noteID = ' . $this->id .';');
		if(mysqli_num_rows($sql) > 0) {
			// note is a source
			while($row = mysqli_fetch_object($sql)) {
				$source = $row->bibID;
			}
		}
		return $source;
	}

	function getNote() {
		// 1 set some variables and arrays
		$data = array();
		$mysqli = condb('open');
		// 2 get the data from db
		$sql = $mysqli->query('SELECT * FROM note WHERE noteID = ' . $this->id . ' AND notePublic >= ' . $this->access . ';');
		condb('close');
		// 3 prepare the result
		$num_results = mysqli_num_rows($sql);
		if($num_results > 0) {
			while ($row = mysqli_fetch_object($sql)) {
				// get the labels and set a link to other notes with the same label
				$label = getIndexMN('note', 'label', $row->noteID);
				// get the user info
				$user = getIndex('user', $row->userID);
				$media = getMedia($row->noteMedia);
				if($row->bibID != NULL) {
					$source = getIndex('bib', $row->bibID);
				} else {
					// the note is a source
					$source = array(
						'id' => '0',
						'name' => ''
					);
				}
				$source['link'] = $row->noteLink;
				$data = array(
					$this->type => array(
						'id' => $row->noteID,
						'checkID' => $row->checkID,
						'title' => $row->noteTitle,
						'subtitle' => $row->noteSubtitle,
						'comment' => makeurl($row->noteComment),
						'comment4tex' => change4Tex($row->noteComment),
						'label' => $label,
						'media' => $media,
						'source' => $source,
						'page' => array(
							'start' => $row->pageStart,
							'end' => $row->pageEnd
						),
						'date' => array(
							'year' => $row->dateYear,
							'created' => $row->dateCreated,
							'modified' => $row->dateModified
						),
						'user' => $user,
						'public' => $row->notePublic,
					),
				);
			}
		} else {
			$data = array(
				'note' => array(
					'id' => 0
				)
			);
		}
		return json_encode($data);
	}

	function getSource() {
		// 1 set some variables and arrays
		$data = array();
		$mysqli = condb('open');
		// 2 get the data from db
		$sql = $mysqli->query('SELECT * FROM bib WHERE bibID = ' . $this->id . ';');
		// 3 prepare the result
		$num_results = mysqli_num_rows($sql);
		if($num_results > 0) {
			while ($row = mysqli_fetch_object($sql)) {
				// bibTyp
				$bibTyp = getIndex('bibTyp', $row->bibTyp);
				// bibName
				$bibName = getIndex('bib', $this->id);
				// author
				$author = getIndexMN('bib', 'author', $this->id);
				// location
				$location = getIndexMN('bib', 'location', $this->id);
				// get more details --> 4
				$detail_sql = $mysqli->query('SELECT bibFieldID, bibDetail FROM bibDetail WHERE bibID = ' . $this->id . ';');
				$source_sql = $mysqli->query('SELECT * FROM note WHERE noteID = ' . $row->noteID . ';');
				$bibInfo = array();
				// 4 get the details from table bibDetail
				$num_details = mysqli_num_rows($detail_sql);
				if($num_details > 0) {
					while ($detail = mysqli_fetch_object($detail_sql)) {
						// get the bibField value
						$bibfield_sql = $mysqli->query('SELECT bibField FROM bibField WHERE bibFieldID = ' . $detail->bibFieldID . ';');
						while ($field = mysqli_fetch_object($bibfield_sql)) {
							$bibDetail = $detail->bibDetail;
							if($field->bibField == 'crossref') {
								$bib = NEW get();
								$bib->id = $detail->bibDetail;
								$bib->type = 'source';
								$bib->access = $this->access;
								$bibDetail = json_decode($bib->getSource());
//								$bibDetail = getIndex('bib', $detail->bibDetail);
//								$bibDetail['author'] = getIndexMN('bib', 'author', $detail->bibDetail);
//								$bibDetail['location'] = getIndexMN('bib', 'location', $detail->bibDetail);
							}
							$bibInfo['crossref'] = $bibDetail;
							$bibInfo[$field->bibField] = $bibDetail;
							//print_r($bibInfo);
						}
					}
				}
				// 5 get more details from table note
				while ($note = mysqli_fetch_object($source_sql)) {
					// get the labels and set a link to other notes with the same label
					$label = getIndexMN('note', 'label', $note->noteID);
					// get the user info
					$user = getIndex('user', $note->userID);
					$media = getMedia($note->noteMedia);
					$data = array(
						$this->type => array(
							'id' => $row->bibID,
							'checkID' => $note->checkID,
							'noteID' => $row->noteID,
							'bibTyp' => $bibTyp,
							'name' => $bibName['name'],
							'title' => $note->noteTitle,
							'subtitle' => $note->noteSubtitle,
							'editor' => $row->bibEditor,
							'author' => $author,
							'location' => $location,
							'comment' => makeurl($note->noteComment),
							'href' => $note->noteLink,
							'label' => $label,
							'media' => $media,
							'page' => array(
								'start' => $note->pageStart,
								'end' => $note->pageEnd
							),
							'date' => array(
								'year' => $note->dateYear,
								'created' => $note->dateCreated,
								'modified' => $note->dateModified
							),
							'user' => $user,
							'public' => $note->notePublic,
							'detail' => $bibInfo,
							'notes' => array()
						),
					);
					$notes_sql = $mysqli->query('SELECT noteID FROM note WHERE bibID = ' . $this->id . ' ORDER BY pageStart, pageEnd, noteID');
					while($notes = mysqli_fetch_object($notes_sql)){
						array_push($data['source']['notes'], $notes->noteID);
					}
				}
			}
		} else {
			$data = array(
				'source' => array(
					'id' => 0
				)
			);
		}
		return json_encode($data);
	}

	function getLabel() {


	}

	function getAuthor() {


	}


	function getData()
	{
		$bibInfo = NULL;
		$type = 'note';
		$sn_name = 'source2note';
		$data = array();

		// 2. get the data from the database
		condb('open');
		$sql = mysql_query('SELECT * FROM note WHERE noteID = ' . $this->id . ' AND notePublic >= ' . $this->access . ';');
		condb('close');

		$num_results = mysql_num_rows($sql);
		// 3. Does the note with this ID exist?
		if($num_results > 0) {
			while ($row = mysql_fetch_object($sql)) {
				condb('open');
				// get the labels and set a link to other notes with the same label
				$labelNames = getIndexMN('note', 'label', $row->noteID);
				// get the user info
				$userInfo = getIndex('user', $row->userID);
				$sn_value = getIndex('bib', $row->bibID);
				condb('close');
/* ------------------------------------------------------------------------- */
				/* differences between note and bib (source) */
				// 4. the note is a source if bibID = NULL; get the bibInfo!
				if ($row->bibID == NULL) {
					$type = 'source';
					$sn_name = 'note2source';
					$source2note = NULL;
					condb('open');
					$bibq = mysql_query('SELECT * FROM bib WHERE noteID =  ' . $this->id . ';');
					condb('close');
					$num_results = mysql_num_rows($bibq);
					// 5 The source is a source if there are some results from table 'bib'
					if($num_results > 0) {
						while ($bibr = mysql_fetch_object($bibq)) {
							$bibID = $bibr->bibID;
							condb('open');
							// bibTyp
							$bibTyp = getIndex('bibTyp', $bibr->bibTyp);
							// bibName
							$bibName = getIndex('bib', $bibID);
							// author
							$authorNames = getIndexMN('bib', 'author', $bibID);
							// location
							$locationNames = getIndexMN('bib', 'location', $bibID);
							// get more details
							$dsql = mysql_query('SELECT bibFieldID, bibDetail FROM bibDetail WHERE bibID = ' . $bibID . ';');
							condb('close');

							$bibInfo = array(
								'bibTyp' => $bibTyp,
								'author' => $authorNames,
								'editor' => $bibr->bibEditor,
								'location' => $locationNames
							);
							$bibInfo['id'] = $bibName['id'];
							$bibInfo['name'] = $bibName['name'];


							$num_details = mysql_num_rows($dsql);
							if($num_details > 0) {
								while ($detail = mysql_fetch_object($dsql)) {
									// get the bibField value
									condb('open');
									$fsql = mysql_query('SELECT bibField FROM bibField WHERE bibFieldID = ' . $detail->bibFieldID . ';');
									condb('close');
									while ($field = mysql_fetch_object($fsql)) {
										$bibDetail = $detail->bibDetail;
										if($field->bibField == 'crossref') {
											condb('open');
											$bibDetail = getIndex('bib', $detail->bibDetail);
											condb('close');
										}
										$bibInfo[$field->bibField] = $bibDetail;
									}
								}
							}

							condb('open');
							$nsql = mysql_query('SELECT noteID FROM note WHERE bibID = ' . $bibID);
							condb('close');
							$sn_value = array();
							while($nrow = mysql_fetch_object($nsql)){
								array_push($sn_value, $nrow->noteID);
							}
						}
					}

					/* _+_+_+_+_+_+_+_+_+_+_+_+_+_+_+_+_+_+_+_+_+_+_+_+_+_+_+_+_+_+_+_+

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
										'author' => $sourceData['author'],
										'location' => $sourceData['location'],
										'label' => $sourceData['label'],
										'comment' => $sourceData['comment']
									);
									$source['crossref'] = $crossref;
								}


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
$noteSql = mysql_query("SELECT noteID FROM note WHERE noteSource=" . $this->id . " ORDER BY pageStart, pageEnd, noteTitle ASC");
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

//get also other sourceIDs to this source
condb('open');
$getBibFieldID = mysql_query("SELECT bibFieldID FROM bibField WHERE bibFieldName = 'crossref'");
while ($row = mysql_fetch_object($getBibFieldID)) {
	$bibFieldID = $row->bibFieldID;			// should be 24
}

$sourceSql = mysql_query("SELECT sourceID FROM sourceDetail WHERE bibFieldID = " . $bibFieldID . " AND sourceDetailName = " . $this->id . " ORDER BY sourceID ASC");
condb('close');
//				echo "SELECT noteID FROM note WHERE noteSource=" . $this->id . " ORDER BY pageStart, noteTitle ASC";
$sources = array();
$num_results = mysql_num_rows($sourceSql);
if ($num_results > 0) {
	while ($row = mysql_fetch_object($sourceSql)) {
		array_push($sources, $row->sourceID);
	}
}
$source['sources'] = $sources;

					/* _+_+_+_+_+_+_+_+_+_+_+_+_+_+_+_+_+_+_+_+_+_+_+_+_+_+_+_+_+_+_+_+ */

				}

/* ------------------------------------------------------------------------- */
				$data = array(
					'type' => $type,
					'id' => $row->noteID,
					'checkID' => $row->checkID,
					'title' => $row->noteTitle,
					'subtitle' => $row->noteSubtitle,
					'biblio' => $bibInfo,
					'comment' => $row->noteComment,
					'link' => $row->noteLink,
					'label' => $labelNames,
					'media' => $row->noteMedia,
					$sn_name => $sn_value,
					'page' => array(
						'start' => $row->pageStart,
						'end' => $row->pageEnd
					),
					'date' => array(
						'year' => $row->dateYear,
						'created' => $row->dateCreated,
						'modified' => $row->dateModified
					),
					'user' => $userInfo,
					'public' => $row->notePublic
				);


			}
			// 3a YES

		// 3b NO
		} else {
			$data = array(
				'id' => 0
			);

			//die('ERROR #' . __LINE__ . ' (class: get)');

		}



		$this->json = json_encode($data);

		return $this->json;

	}

	// for search query: search in e.g. author and list the author data with the listData and the ids from the sql request

	function listData() {
		$typeName = '';
		$mysqli = condb('open');
		// get the name of the author, the label or etc.
		switch($this->type) {
			case 'label';
				$sql = $mysqli->query('SELECT label FROM label WHERE labelID=' . $this->id . ';');
				while($row = mysqli_fetch_object($sql)) {
					$typeName = $row->label;
				}
				$notes = getNote2Label($this->id);
				break;
			case 'author';
				$sql = $mysqli->query('SELECT author FROM author WHERE authorID=' . $this->id . ';');
				while($row = mysqli_fetch_object($sql)) {
					$typeName = $row->author;
				}
				$notes = getNote2Author($this->id);
				break;
			case 'new';
				$sql = $mysqli->query('SELECT noteID FROM note ORDER BY dateCreated DESC LIMIT 0, ' . $this->id . ';');
				$notes = array();
				$typeName = 'newest';
				while($row = mysqli_fetch_object($sql)) {
					array_push($notes, $row->noteID);
				}
				break;

			case 'notes';


				break;

			default;
				$sql = $mysqli->query('SELECT label FROM label WHERE labelID=' . $this->id . ';');
				while($row = mysqli_fetch_object($sql)) {
					$typeName = $row->label;
				}
				$notes = getNote2Label($this->id);
				break;
		}

		if($typeName != '') {

			$list = array(
				'type' => $this->type,
				'id' => $this->id,
				'name' => $typeName,
				'notes' => $notes
			);
		} else {
			$list = array(
				'id' => 0
			);
		}

		$this->json = json_encode($list);

		return $this->json;

	}


	function searchData()
	{
		$query = array(
			'query' => $this->query,
			'filter' => $this->part
		);

		$this->json = json_encode($query);

		return $this->json;
	}




	/*
	function getNote() {
		if($this->access == 'public' && $this->id == 'all') {
			$query = 'WHERE notePublic = 1';
		} else if($this->access == 'public' && $this->id != 'all') {
			$query = 'WHERE noteID=\'' . $this->id . '\' AND notePublic = 1';
		} else if($this->access != 'public' && $this->id != 'all') {
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
				$labelNames = getIndexMN('note', 'label', $row->noteID);

//				print_r($labelNames);
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
							'author' => $sourceData['author'],
							'location' => $sourceData['location'],
							'label' => $sourceData['label'],
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
		if($this->access == 'public' && $this->id == 'all') {
			$query = 'WHERE sourcePublic = 1';
		} else if($this->access == 'public' && $this->id != 'all') {
			$query = 'WHERE sourceID=\'' . $this->id . '\' AND sourcePublic = 1';
		} else if($this->access != 'public' && $this->id != 'all') {
			$query = 'WHERE sourceID=\'' . $this->id . '\'';
		} else { // ($this->access != 'public' && $this->id == 'all')
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
				$authorNames = getIndexMN('source', 'author', $row->sourceID);
				// get the locations and do not link them
				$locationNames = getIndexMN('source', 'location', $row->sourceID);
				// get the labels and link them
				$labelNames = getIndexMN('source', 'label', $row->sourceID);
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
					'author' => $authorNames,
					'location' => $locationNames,
					'label' => $labelNames,
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
										'author' => $sourceData['author'],
										'location' => $sourceData['location'],
										'label' => $sourceData['label'],
										'comment' => $sourceData['comment']
									);
									$source['crossref'] = $crossref;
								}

*/
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

	/*
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
				$noteSql = mysql_query("SELECT noteID FROM note WHERE noteSource=" . $this->id . " ORDER BY pageStart, pageEnd, noteTitle ASC");
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

				//get also other sourceIDs to this source
				condb('open');
				$getBibFieldID = mysql_query("SELECT bibFieldID FROM bibField WHERE bibFieldName = 'crossref'");
				while ($row = mysql_fetch_object($getBibFieldID)) {
					$bibFieldID = $row->bibFieldID;			// should be 24
				}

				$sourceSql = mysql_query("SELECT sourceID FROM sourceDetail WHERE bibFieldID = " . $bibFieldID . " AND sourceDetailName = " . $this->id . " ORDER BY sourceID ASC");
				condb('close');
//				echo "SELECT noteID FROM note WHERE noteSource=" . $this->id . " ORDER BY pageStart, noteTitle ASC";
				$sources = array();
				$num_results = mysql_num_rows($sourceSql);
				if ($num_results > 0) {
					while ($row = mysql_fetch_object($sourceSql)) {
						array_push($sources, $row->sourceID);
					}
				}
				$source['sources'] = $sources;
			}
		} else {
			$sources = array(
				'id' => 0
			);
		}

		//return $source;



		$this->data = json_encode($source);

//		print_r($this->data);
		return $this->data;
	}
	*/
}