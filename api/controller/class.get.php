<?php

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
		if($this->id == 0) {
			// empty json data set
			$data = array(
				$this->type => array(
					'id' => '0',
					'checkID' => NULL,
					'title' => '',
					'subtitle' => '',
					'comment' => '',
					'comment4tex' => '',
					'comment2edit' => '',
					'label' => '',
					'media' => '',
					'source' => array(
						'id' => '',
						'name' => '',
						'link' => '',
						'value' => ''
					),
					'page' => array(
						'start' => '',
						'end' => ''
					),
					'date' => array(
						'year' => '',
						'created' => '',
						'modified' => ''
					),
					'user' => '',
					'public' => '0'
				),
			);
		} else {
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
						$source['value'] = getComment($row->bibID);
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
							'title' => html_entity_decode($row->noteTitle),
							'subtitle' => $row->noteSubtitle,
							'comment' => makeurl(html_entity_decode($row->noteComment)),
							'comment4tex' => html2tex(html_entity_decode($row->noteComment), 'cite'),
							'comment2edit' => html_entity_decode($row->noteComment),
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
							'public' => $row->notePublic
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

		}
		return json_encode($data);
	}

	function getSource() {
		// 1 set some variables and arrays
		$data = array();
		if($this->id == 0) {
			// empty json data set
			$data = array(
				$this->type => array(
					'id' => '0',
					'checkID' => NULL,
					'noteID' => '0',
					'bibTyp' => '',
					'name' => '',
					'title' => '',
					'subtitle' => '',
					'editor' => '',
					'author' => '',
					'location' => '',
					'comment' => '',
					'comment2edit' => '',
					'href' => '',
					'label' => '',
					'media' => '',
					'page' => array(
						'start' => '',
						'end' => ''
					),
					'date' => array(
						'year' => '',
						'created' => '',
						'modified' => ''
					),
					'user' => '',
					'public' => '0',
					'detail' => '',
					'detailPlus' => array(

					),
					'notes' => array(
						array(
							'id' => '0',
							'ac' => '0'
						)
					),
					'insource' => array()
				),
			);


		} else {
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
					$bibInfoPlus = array();
					// 4 get the details from table bibDetail
					$num_details = mysqli_num_rows($detail_sql);
					if($num_details > 0) {
						while ($detail = mysqli_fetch_object($detail_sql)) {
							// get the bibField value
							$bibfield_sql = $mysqli->query('SELECT bibField FROM bibField WHERE bibFieldID = ' . $detail->bibFieldID . ';');
							while ($field = mysqli_fetch_object($bibfield_sql)) {
								$bibDetail = html2tex(html_entity_decode($detail->bibDetail));
								if($field->bibField == 'crossref') {
									$bib = NEW get();
									$bib->id = $detail->bibDetail;
									$bib->type = 'source';
									$bib->access = $this->access;
									$bibDetail = json_decode($bib->getSource());
									$bibInfo['crossref'] = $bibDetail;
								}
								if($field->bibField != 'edition' && $field->bibField != 'organization' && $field->bibField != 'publisher' && $field->bibField != 'series' && $field->bibField != 'version' && $field->bibField != 'volume' && $field->bibField != 'volumes') {
									$bibInfo[$field->bibField] = $bibDetail;
								} else {
									$bibInfoPlus[$field->bibField] = $bibDetail;
								}
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
								'title' => html2tex(html_entity_decode($note->noteTitle)),
								'subtitle' => html2tex(html_entity_decode($note->noteSubtitle)),
								'editor' => $row->bibEditor,		// true or false
								'author' => $author,
								'location' => $location,
								'comment' => html2tex($note->noteComment),
								'comment2edit' => html_entity_decode($note->noteComment),
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
								'detailPlus' => $bibInfoPlus,
								'notes' => array(),
								'insource' => array()
							),
						);

						if($bibTyp['name'] == 'collection' || $bibTyp['name'] == 'book' || $bibTyp['name'] == 'proceedings') {
							// get the inbooks, incollections and in proceedings
							// id of crossref in bibField
							$bibf = $mysqli->query('SELECT bibFieldID FROM bibField WHERE bibField = \'crossref\';');
							while ($bibfid = mysqli_fetch_object($bibf)) {
				//				echo 'SELECT bibID FROM bibDetail WHERE bibFieldID = '.$bibfid->bibFieldID.' AND bibDetail = '.$this->id.';';
								$sql = $mysqli->query('SELECT bibID FROM bibDetail WHERE bibFieldID = '.$bibfid->bibFieldID.' AND bibDetail = '.$this->id.';');
								while ($row = mysqli_fetch_object($sql)) {
									array_push($data['source']['insource'], $row->bibID);
								}
							}
							// id of sources in sourceDetail


						}

						$notes_sql = $mysqli->query('SELECT noteID, notePublic FROM note WHERE bibID = ' . $this->id . ' ORDER BY pageStart, pageEnd, noteID');
						$i = 0;
						while($notes = mysqli_fetch_object($notes_sql)){
							$data['source']['notes'][$i]['id'] = $notes->noteID;
							$data['source']['notes'][$i]['ac'] = $notes->notePublic;
							$i++;
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
		}
		return json_encode($data);
	}

	function getData() {
		// one day, we can merge the getSource and the getNote function into this one here getData
	}

	function listData() {
		$typeName = '';
		$mysqli = condb('open');
		// get the name of the author, the label or etc.
		switch($this->type) {
			case 'label';
				$sql = $mysqli->query('SELECT label FROM label WHERE labelID=' . $this->id . ';');
				while($row = mysqli_fetch_object($sql)) {
					$typeName = ($row->label);
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
				if($this->access > 0) {		// = public access: show sources only, if they are public!
					$sql = $mysqli->query('SELECT note.noteID, note.bibID FROM note, bib WHERE note.noteID = bib.noteID AND note.notePublic >= '.$this->access.' ORDER BY note.dateModified DESC  LIMIT 0, ' . $this->id . ';');
				} else {
					$sql = $mysqli->query('SELECT noteID FROM note ORDER BY dateModified DESC LIMIT 0, ' . $this->id . ';');
				}

				$notes = array();
				$typeName = 'modified';
				while($row = mysqli_fetch_object($sql)) {
					array_push($notes, $row->noteID);
				}
				break;
			case 'recent';		// like 'new' but only for sources
					$sql = $mysqli->query('SELECT note.noteID, note.bibID, bib.bibID, bib.bib FROM note, bib WHERE note.noteID = bib.noteID AND note.notePublic >= '.$this->access.' ORDER BY note.dateModified DESC  LIMIT 0, ' . $this->id . ';');
				$notes = array();
				$typeName = 'modified';
				while($row = mysqli_fetch_object($sql)) {
					array_push($notes, $row->bibID. '::' . html_entity_decode($row->bib));
				}
				break;

			case 'list';
			$notes = array();
				switch ($this->id) {
					case 'note';
						$sql = $mysqli->query('SELECT noteID, noteTitle, noteSubtitle, noteComment FROM note ORDER BY noteTitle, noteSubtitle;');
						$typeName = 'all';
						while($row = mysqli_fetch_object($sql)) {
							array_push($notes, $row->noteID. '::' . html_entity_decode($row->noteTitle));
							array_push($notes, $row->noteID. '::' . html_entity_decode($row->noteSubtitle));
							array_push($notes, $row->noteID. '::' . html_entity_decode($row->noteComment));
						}
						break;

					case 'source';
						$sql = $mysqli->query('SELECT bibID, bib, noteID FROM bib ORDER BY bib;');
						$typeName = 'all';
						while($row = mysqli_fetch_object($sql)) {
							array_push($notes, $row->bibID. '::' . html_entity_decode($row->bib));
						}
						break;

					case 'bibtyp';
						$sql = $mysqli->query('SELECT bibTypID, bibTyp FROM bibTyp ORDER BY bibTyp;');
						$typeName = 'all';
						while($row = mysqli_fetch_object($sql)) {
							array_push($notes, $row->bibTypID. '::' . html_entity_decode($row->bibTyp));
						}
						break;

					case 'bibfield';
						$sql = $mysqli->query('SELECT bibFieldID, bibField FROM bibField WHERE bibField LIKE "edition" OR bibField LIKE "organization" OR bibField LIKE "publisher" OR bibField LIKE "series" OR bibField LIKE "version" OR bibField LIKE "volume%" ORDER BY bibField;');
						$typeName = 'all';
						while($row = mysqli_fetch_object($sql)) {
							array_push($notes, $row->bibFieldID. '::' . html_entity_decode($row->bibField));
						}
						break;

					case 'biblatex';
						for($i = 15; $i > 0; $i--){
							$sql = $mysqli->query('SELECT bibID, bib, noteID FROM bib WHERE bibTyp = ' . $i . ' ORDER BY bib;');
							while($row = mysqli_fetch_object($sql)) {
								array_push($notes, $row->bibID);
							}
						}
//						$sql = $mysqli->query('SELECT bibID, bib, noteID FROM bib ORDER BY bib;');
						$typeName = 'all';


						break;
					case 'label';
						$sql = $mysqli->query('SELECT labelID, label FROM label ORDER BY label;');
						$typeName = 'all';
						while($row = mysqli_fetch_object($sql)) {
							array_push($notes, $row->labelID. '::' . html_entity_decode($row->label));
						}
						break;
					case 'author';
						$sql = $mysqli->query('SELECT authorID, author FROM author ORDER BY author;');
						$typeName = 'all';
						while($row = mysqli_fetch_object($sql)) {
							array_push($notes, $row->authorID. '::' . html_entity_decode($row->author));
						}
						break;
					case 'location';
						$sql = $mysqli->query('SELECT locationID, location FROM location ORDER BY location;');
						$typeName = 'all';
						while($row = mysqli_fetch_object($sql)) {
							array_push($notes, $row->locationID. '::' . html_entity_decode($row->location));
						}
						break;
					case 'all';
						$sql = $mysqli->query('SELECT labelID, label FROM label ORDER BY label;');
						$typeName = 'all';
						while($row = mysqli_fetch_object($sql)) {
							array_push($notes, $row->labelID. '::' . html_entity_decode($row->label));
						}
						$sql = $mysqli->query('SELECT authorID, author FROM author ORDER BY author;');
						$typeName = 'all';
						while($row = mysqli_fetch_object($sql)) {
							array_push($notes, $row->authorID. '::' . html_entity_decode($row->author));
						}
						$sql = $mysqli->query('SELECT bibID, bib, noteID FROM bib ORDER BY bib;');
						$typeName = 'all';
						while($row = mysqli_fetch_object($sql)) {
							array_push($notes, $row->bibID. '::' . html_entity_decode($row->bib));
						}
						$sql = $mysqli->query('SELECT noteID, noteTitle, noteSubtitle, noteComment FROM note ORDER BY noteTitle, noteSubtitle;');
						$typeName = 'all';
						while($row = mysqli_fetch_object($sql)) {
							array_push($notes, $row->noteID. '::' . html_entity_decode($row->noteTitle));
							array_push($notes, $row->noteID. '::' . html_entity_decode($row->noteSubtitle));
							array_push($notes, $row->noteID. '::' . html_entity_decode($row->noteComment));
						}
						break;


					default;		// label
						$sql = $mysqli->query('SELECT labelID, label FROM label ORDER BY label;');
						$typeName = 'all';
						while($row = mysqli_fetch_object($sql)) {
							array_push($notes, $row->labelID. '::' . $row->label);
						}
				}
				break;
			case 'bibtyp';
				if($this->id === 'book') $this->id = 2;
				if($this->id === 'collection') $this->id = 5;
				if($this->id === 'proceedings') $this->id = 11;
				$sql = $mysqli->query('SELECT bibTyp FROM bibTyp WHERE bibTypID = ' . $this->id . ';');
				while($row = mysqli_fetch_object($sql)) {
					$typeName = $row->bibTyp;
				}
				$sql = $mysqli->query('SELECT bibID, bib, noteID FROM bib WHERE bibTyp = ' . $this->id . ' ORDER BY bib;');

				$notes = array();
				while($row = mysqli_fetch_object($sql)) {
					array_push($notes, $row->bibID. '::' . $row->bib);
				}
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
		//$q = htmlentities($this->query, ENT_QUOTES, 'UTF-8');
		$q = $this->query;
		$f = $this->part;		// filter

		$qs = array();

		// search parameters:
		// one word e.g.			: luhmann						result:
		// more than one word e.g.	: luhmann zettelkasten			result: find one or the other or both
		// one sentence e.g.		: 'luhmann zettelkasten'		result: exactly this somwhere in the content
		//

		$typeName = 'note';
		$results = array();
		$resultsInNote = array();
		$resultsInLabel = array();
		$resultsInAuthor = array();
//echo 'SELECT * FROM note WHERE notePublic >= ' . $this->access . ' AND MATCH(noteTitle, noteSubtitle, noteComment) AGAINST (\''.$q.'\' IN BOOLEAN MODE);';
		$mysqli = condb('open');
		switch($f){
			case 'note';
				$sql = $mysqli->query('SELECT * FROM note WHERE notePublic >= ' . $this->access . ' AND MATCH(note.noteTitle, note.noteSubtitle, note.noteComment, note.noteMedia) AGAINST (\''.$q.'\' IN BOOLEAN MODE);');	//AND
				while($row = mysqli_fetch_object($sql)) {
					$results[] = $row->noteID;
				}
				break;

			case 'source';
				$sql = $mysqli->query('SELECT * FROM note, bib WHERE notePublic >= ' . $this->access . ' AND note.noteID = bib.noteID AND MATCH(noteTitle, noteSubtitle, noteComment, bib) AGAINST (\''.$q.'\' IN BOOLEAN MODE);');	//AND
				while($row = mysqli_fetch_object($sql)) {
					$results[] = $row->noteID;
				}
				break;

			case 'author';
				$asql = $mysqli->query('SELECT author.authorID, rel_bib_author.bibID FROM author, rel_bib_author WHERE author.author LIKE \'%'.$q.'%\' AND author.authorID = rel_bib_author.authorID');
				while($arow = mysqli_fetch_object($asql)) {
					$sql = $mysqli->query('SELECT note.noteID FROM bib, note WHERE bib.bibID = ' . $arow->bibID . ' AND bib.noteID = note.noteID AND note.notePublic >= ' . $this->access . ';' );
					while($row = mysqli_fetch_object($sql)) {
						$results[] = $row->noteID;
					}
				}
				$f = 'source';
				break;

			case 'label';
				$asql = $mysqli->query('SELECT label.labelID, rel_note_label.noteID FROM label, rel_note_label WHERE label.label LIKE \'%'.$q.'%\' AND label.labelID = rel_note_label.labelID');
				while($arow = mysqli_fetch_object($asql)) {
					$sql = $mysqli->query('SELECT note.noteID FROM note WHERE note.noteID = ' . $arow->noteID . ' AND note.notePublic >= ' . $this->access . ';');
					while($row = mysqli_fetch_object($sql)) {
						$results[] = $row->noteID;
					}
				}
				$f = 'note';
				break;

			default;		// search everywhere
				$sql = $mysqli->query('SELECT * FROM note WHERE notePublic >= ' . $this->access . ' AND MATCH(note.noteTitle, note.noteSubtitle, note.noteComment, note.noteMedia) AGAINST (\''.$q.'\' IN BOOLEAN MODE);');	//AND
				while($row = mysqli_fetch_object($sql)) {
//					$results[] = $row->noteID;
					$resultsInNote[] = $row->noteID;
				}
				// label
				$lasql = $mysqli->query('SELECT label.labelID, rel_note_label.noteID FROM label, rel_note_label WHERE label.label LIKE \'%'.$q.'%\' AND label.labelID = rel_note_label.labelID');
				while($arow = mysqli_fetch_object($lasql)) {
					$sql = $mysqli->query('SELECT note.noteID FROM note WHERE note.noteID = ' . $arow->noteID . ' AND note.notePublic >= ' . $this->access . ';');
					while($row = mysqli_fetch_object($sql)) {
//						$results[] = $row->noteID;
						$resultsInLabel[] = $row->noteID;
					}
				}
				// author
				$asql = $mysqli->query('SELECT author.authorID, rel_bib_author.bibID FROM author, rel_bib_author WHERE author.author LIKE \'%'.$q.'%\' AND author.authorID = rel_bib_author.authorID');
				while($arow = mysqli_fetch_object($asql)) {
					$sql = $mysqli->query('SELECT note.noteID FROM bib, note WHERE bib.bibID = ' . $arow->bibID . ' AND bib.noteID = note.noteID AND note.notePublic >= ' . $this->access . ';' );
					while($row = mysqli_fetch_object($sql)) {
//						$results[] = $row->noteID;
						$resultsInAuthor[] = $row->noteID;
					}
				}
				$f = 'note';
				$results = array_unique(array_merge($resultsInNote,$resultsInLabel,$resultsInAuthor));
		}
		$list = array(
			'type' => $f,
			//'id' => $this->id,
			'query' => $q,
			'filter' => $this->part,
			'notes' => $results
		);
		//print_r($list);

		$this->json = json_encode($list);

		return $this->json;
	}
}
