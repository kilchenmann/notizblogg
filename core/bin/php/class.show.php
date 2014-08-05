<?php
/**
 * Created by IntelliJ IDEA.
 * User: ak
 * Date: 25.06.14
 * Time: 16:50
 */

class show {
	var $id;
	var $type;
	var $access;
	var $data;

	var $open_note = '<div class=\'note\'>';

	var $show_media = '<div class=\'media\'>';
	var $show_text = '<div class=\'text\'>';
	var $show_latex = '<div class=\'latex\'>';
	var $show_label = '<div class=\'label\'>';
	var $show_tools = '';

	var $close = '</div>';


	function show() {


	}

	function showMedia()
	{
		$mediaFile = explode(".", $this->data['media']);
		$fileName = $mediaFile[0];
		$extension = $mediaFile[1];
		$mediaInfo = __MEDIA_URL__ . "/pictures/" . $this->data['media'];
		if (@fopen($mediaInfo, "r") == false) {
			return '<p class=\'warning\' >[The media file is missing!]</p>';
		} else {

			switch ($extension) {
				case "jpg";
				case "png";
				case "gif";
				case "jpeg";
				case "tif";
					// $fileName = $mediaInfo['filename'];
					// $infoSize = getimagesize($mediaInfo);
					// ergibt mit $infoSize[0] für breite und $infoSize[1] für höhe
					return '<img class=\'staticMedia\' src=\'' . __MEDIA_URL__ . '/pictures/' . $this->data['media'] . '\' alt=\'' . $fileName . '\' title=\'' . $this->data['title'] . '\' />';
					break;

				case "pdf";
					return '<p class=\'download\'>' . $this->data['media'] . ' <a href=\'' . __MEDIA_URL__ . '/documents/ ' . $this->data['media'] . '\' title=\'Download ' . $fileName . ' as pdf.\'>Open</a></p>';
					break;

				case "mp4";
				case "webm";
					return '<p class=\'warning\' >[The media type \'video\' is not implemented yet.]</p>';
					/*
					 *
						echo "<video class='dynamicMedia' controls preload='auto' poster='".__MEDIA_URL__."/movies/".$fileName.".png'>";
						echo "<source src='".__MEDIA_URL__."/movies/".$fileName.".mp4' >";
						//type='video/mp4; codecs=\"avc1.42E01E, mp4a.40.2\"'
						echo "<source src='".__MEDIA_URL__."/movies/".$fileName.".webm' >";
						//type='video/webm; codecs=\"vp8, vorbis\"'
						echo "</video>";
					 *
					 */
					break;


				case "mp3";
				case "wav";
					return '<p class=\'warning\' >[The media type \'audio\' is not implemented yet.]</p>';
					/*
					 *
						echo "<audio class='dynamicMedia' controls preload='auto'>";
						echo "<source src='".__MEDIA_URL__."/sound/".$fileName.".mp3' type='audio/mpeg; codecs=mp3'>";
						echo "<source src='".__MEDIA_URL__."/sound/".$fileName.".wav' type='audio/wav; codecs=1'>";
						echo "</audio>";
					 *
					 */
					break;

				default;
					return '<p class=\'warning\' >[The media file with the extension \'' . $extension . '\' is not supported in notizblogg!?</p>';

			}
		}
	}
	function showBib() {
		// two returns: showBibtex and showBiblio
		if($this->data['bibTyp']['id'] != 0) {
			$authors = '';
			$locations = '';

			$showBibtex = '@' . $this->data['bibTyp']['name'] . '{' . $this->data['name'] . ',<br>';
			$showBiblio = ''; // $this->data['id'];

			// set the authors
			$i = 0;
			while ($i < count($this->data['author'])) {

				if($authors == ''){
					$authors = '<a href=\'' . __MAIN_FILE__ . '?author=' . $this->data['author'][$i]['id'] . '\'>' . $this->data['author'][$i]['name'] . '</a>';
				} else {
					$authors .= ', <a href=\'' . __MAIN_FILE__ . '?author=' . $this->data['author'][$i]['id'] . '\'>' . $this->data['author'][$i]['name'] . '</a>';
				}
				$i++;
			}
			// set the locations
			$i = 0;
			while ($i < count($this->data['location'])) {

				if($locations == ''){
					$locations = $this->data['location'][$i]['name'];
				} else {
					$locations .= ', ' . $this->data['location'][$i]['name'];
				}
				$i++;
			}

			//$this->show_text .= '<p class=\'small\'>(' . $authors . ': <a href=\'?source=' . $this->data['id'] . '\'>' . getLastChar($this->data['title']) . '</a> S. ' . $pages . ')</p>';


			if($this->data['editor'] == 1){
				$showBibtex .= 'editor = {' . $authors . '},<br>';
				$showBiblio .= $authors . ' (Hg.):<br>';
			} else {
				$showBibtex .= 'author = {' . $authors . '},<br>';
				$showBiblio .= $authors . ':<br>';
			}
			$showBibtex .= 'title = {' . ($this->data['title']) . '},<br>';
			if($this->data['bibTyp']['name'] == 'collection' || $this->data['bibTyp']['name'] == 'proceedings' || $this->data['bibTyp']['name'] == 'book') {
				$showBiblio .= '<a href=\'?collection=' . $this->data['id'] . '\' >'. getLastChar($this->data['title']) . '</a> ';
			} else {
				$showBiblio .= '<a href=\'?source=' . $this->data['id'] . '\' >'. getLastChar($this->data['title']) . '</a> ';
			}

			if($this->data['subtitle'] != ''){
				$showBibtex .= 'subtitle = {' . ($this->data['subtitle']) . '},<br>';
				$showBiblio .= getLastChar($this->data['subtitle']) . '<br>';
			}

			if(array_key_exists('crossref', $this->data)) {
				$crossAuthors = '';
				// set the authors
				$i = 0;
				while ($i < count($this->data['crossref']['author'])) {

					if($crossAuthors == ''){
						$crossAuthors = '<a href=\'' . __MAIN_FILE__ . '?author=' . $this->data['crossref']['author'][$i]['id'] . '\'>' . $this->data['crossref']['author'][$i]['name'] . '</a>';
					} else {
						$crossAuthors .= ', <a href=\'' . __MAIN_FILE__ . '?author=' . $this->data['crossref']['author'][$i]['id'] . '\'>' . $this->data['crossref']['author'][$i]['name'] . '</a>';
					}
					$i++;
				}
				$crossLocations = '';
				// set the locations
				$i = 0;
				while ($i < count($this->data['crossref']['location'])) {

					if($crossLocations == ''){
						$crossLocations = $this->data['crossref']['location'][$i]['name'];
					} else {
						$crossLocations .= ', ' . $this->data['crossref']['location'][$i]['name'];
					}
					$i++;
				}

				$showBibtex .= 'crossref = {<a href=\'?collection=' . $this->data['crossref']['id'] . '\'>' . ($this->data['crossref']['name']) . '</a>},<br>';

				$showBiblio .= 'In: ';
				if($this->data['crossref']['editor'] == 1){
					$showBibtex .= 'editor = {' . $crossAuthors . '},<br>';
					$showBiblio .= $crossAuthors . ' (Hg.):<br>';
				} else {
					$showBibtex .= 'author = {' . $crossAuthors . '},<br>';
					$showBiblio .= $crossAuthors . ':<br>';
				}
				$showBibtex .= 'booktitle = {' . ($this->data['crossref']['title']) . '},<br>';
				$showBiblio .= '<a href=\'?collection=' . $this->data['crossref']['id'] . '\'>' . getLastChar($this->data['crossref']['title']) . ' </a>';

				if($this->data['crossref']['subtitle'] != ''){
					$showBibtex .= 'booksubtitle = {' . ($this->data['crossref']['subtitle']) . '},<br>';
					$showBiblio .= getLastChar($this->data['crossref']['subtitle']) . '<br>';
				}

				if(!empty($this->data['crossref']['location'])){
					$showBibtex .= 'location = {' . $crossLocations . '},<br>';
					$showBiblio .= $crossLocations . ', ';
				}
				if($this->data['year'] != '0000'){
					$showBibtex .= 'year = {' . $this->data['crossref']['year'] . '},<br>';
					$showBiblio .= $this->data['crossref']['year'] . '';
				}

			} else {
				if(!empty($this->data['location'])) {
					$showBibtex .= 'location = {' . $locations . '},<br>';
					$showBiblio .= $locations . ', ';
				}
				if($this->data['year'] != '0000'){
					$showBibtex .= 'year = {' . $this->data['year'] . '},<br>';
					$showBiblio .= $this->data['year'] . '';
				}
			}
			if(array_key_exists('detail', $this->data)) {
				$countDetail = count(array_keys($this->data['detail']));
				$i = 0;
				while ($countDetail > 0) {
					$detail = array_keys($this->data['detail']);
					switch ($detail[$i]) {
						case 'url';
							$showBibtex .= $detail[$i] . ' = {<a target=\'_blank\' href=\'' . $this->data['detail'][$detail[$i]] . '\' >' . $this->data['detail'][$detail[$i]] . '</a>},<br>';
							$showBiblio .= ', URL: <a target=\'_blank\' href=\'' . $this->data['detail'][$detail[$i]] . '\'>' . $this->data['detail'][$detail[$i]] . '</a> ';
							break;

						case 'urldate';
							$showBiblio .=  '(Stand: ' . $this->data['detail'][$detail[$i]] . ').';
							break;

						case 'pages';
							$showBiblio .=  ', S. ' . $this->data['detail'][$detail[$i]];

							break;

						default;
							$showBibtex .= $detail[$i] . ' = {' . $this->data['detail'][$detail[$i]] . '},<br>';
							$showBiblio .= $this->data['detail'][$detail[$i]];
					}
					$countDetail--;
					$i++;

				}
			}
			$showBibtex .= 'note = {' . $this->data['comment'] . '}}';
			$showBiblio .= '.';
		} else {
			$showBibtex = 'The data are not yet ready to use in laTex.';
			$showBiblio = '<a href=\'?source=' . $this->data['id'] . '\' >'. $this->data['comment'] . '</a>';
		}

		return array($showBibtex,$showBiblio);

	}


	function showTools() {

		if($this->data['type'] == 'note'){
			// if it's a note and there is also a source connected with it,
			// we need the sourceID for our expand (booklet) button
			if(!empty($this->data['source'])) {
				$id_expand = $this->data['source']['id'];
			} else {
				$id_expand = '';
			}
		} else {
			// else it's a source
			$id_expand = $this->data['id'];
		}

		// finally return: <div class='tools [public]' id='noteID OR sourceID'></div>
		// class 'public' is needed for the edit button; the id is needed for the expand (booklet) button

		return '<div class=\'tools\' id=\'' . $id_expand . '\'></div>';


	}

	function showData() {
		$show = NEW get();
		$show->id = $this->id;
		$show->access = $this->access;

		switch($this->type) {
			case 'author';

				break;

			case 'note';
				$authors = '';
				$labels = '';
				$this->data = json_decode($show->getNote(), true);
				if ($this->data['id'] != 0) {
					$this->open_note = '<div class=\'note item\' id=\'' . $show->id . '\'>';
					// show media, if exist
					if(!empty($this->data['media'])) {
						$this->show_media .= $this->showMedia() . $this->close;
					} else {
						$this->show_media .= '&nbsp;' . $this->close;
					}
					// show text
					// set title
					$this->show_text .= '<h3>' . $this->data['title'] . '</h3>';
					// set content
					$this->show_text .= '<p>' . makeurl($this->data['content']) . '</p>';
					// set source, if exists
					if(!empty($this->data['source'])) {
						//print_r(json_encode($this->data));
						if ($this->data['source']['id'] != 0 && $this->data['source']['bibTyp']['name'] != 'project') {
							$pages = '';

							if ($this->data['page']['start'] != 0) {
								$pages = ', S. ' . $this->data['page']['start'];
								if ($this->data['page']['end'] != 0) {
									$pages .= '-' . $this->data['page']['end'];
								}
								$pages .= '.';
							}
							$i = 0;
							while ($i < count($this->data['source']['author'])) {
								if($authors == ''){
									$authors = '<a href=\'' . __MAIN_FILE__ . '?author=' . $this->data['source']['author'][$i]['id'] . '\'>' . $this->data['source']['author'][$i]['name'] . '</a>';
								} else {
									$authors .= ', <a href=\'' . __MAIN_FILE__ . '?author=' . $this->data['source']['author'][$i]['id'] . '\'>' . $this->data['source']['author'][$i]['name'] . '</a>';
								}
							$i++;
							}
							if($authors == '' && $this->data['source']['title'] == ''){
								if($this->data['source']['bibTyp']['name'] == 'collection' || $this->data['source']['bibTyp']['name'] == 'proceedings' || $this->data['source']['bibTyp']['name'] == 'book') {
									$sourcename = '<a href=\'?collection=' . $this->data['source']['id'] . '\' >'. $this->data['source']['name'] . '</a>';
								} else {
									$sourcename = '<a href=\'?source=' . $this->data['source']['id'] . '\' >'. $this->data['source']['name'] . '</a>';
								}
							} else {
								if($this->data['source']['bibTyp']['name'] == 'collection' || $this->data['source']['bibTyp']['name'] == 'proceedings' || $this->data['source']['bibTyp']['name'] == 'book') {
									$sourcename = '<a href=\'?collection=' . $this->data['source']['id'] . '\' >'. $this->data['source']['title'] . '</a>';
								} else {
									$sourcename = '<a href=\'?source=' . $this->data['source']['id'] . '\' >'. $this->data['source']['title'] . '</a>';
								}
							}
							if($authors != '') {
								$this->show_text .= '<p class=\'small\'>' . $authors . ': ' . $sourcename . $pages . '</a></p>';
							} else {
								$this->show_text .= '<p class=\'small\'>' . $sourcename . $pages . '</a></p>';
							}
						}
					}
					$this->show_text .= $this->close;

					// show text with quotation marks. to use in latex later
					if(!empty($this->data['source'])) {
						$this->show_latex .= '<h3>' . $this->data['title'] . '</h3>';
						$this->show_latex .= '<p>``' . change4Tex(makeurl($this->data['content'])) . '\'\'</p>';
						if ($this->data['source']['id'] != 0 && $this->data['source']['bibTyp']['name'] != 'project') {
							$pages = "";
							if ($this->data['page']['start'] != 0) {
								$pages = $this->data['page']['start'];
								if ($this->data['page']['end'] != 0) {
									$pages .= '-' . $this->data['page']['end'];
								}
							}
							$this->show_latex .= '<p class=\'small\'>\cite[][' . $pages . ']{<a href=\'?source=' . $this->data['source']['id'] . '\' >' . $this->data['source']['name'] . '</a>}</p>';
						}
						$this->show_latex .= $this->close;
					} else {
						$this->show_latex = '<div></div>';
					}

					// show labels
					// is it public?
					if($this->data['public'] == 0) {
						$this->show_label = '<div class=\'label private\'>';
					}
					if (!empty($this->data['label'])) {
						$i = 0;
						while ($i < count($this->data['label'])) {

							if($labels == ''){
								$labels = '<a href=\'?label=' . $this->data['label'][$i]['id'] . '\' title=\'#notes with this label: ' . $this->data['label'][$i]['number'] . '\'>' . $this->data['label'][$i]['name'] . '</a>';
							} else {
								$labels .= ' | <a href=\'?label=' . $this->data['label'][$i]['id'] . '\' title=\'#notes with this label: ' . $this->data['label'][$i]['number'] . '\'>' . $this->data['label'][$i]['name'] . '</a>';
							}
							$i++;
						}
						$this->show_label .= '<p class=\'small\'>' . $labels . '</p>';
					}
					$this->show_label .= $this->close;

					$this->show_tools .= $this->showTools();

				} else {
					// no results
					$this->open_note = '';

					$this->show_media = '';
					$this->show_text = '';
					$this->show_latex = '';
					$this->show_label = '';
					$this->show_tools = '';

					$this->close = '';

				}

				break;

		//	case 'source';

		//		break;

			default; // source
				$this->data = json_decode($show->getSource(), true);
				if (!empty($this->data)) {
					$this->open_note = '<div class=\'note topic item\' id=\'' . $show->id . '\'>';
					$biblio = $this->showBib();
					//$biblio = array('LATEX','BIBLIO');
					if(!empty($this->data['media'])) {
						$this->show_media .= $this->showMedia() . $this->close;
					} else {
						$this->show_media .= '&nbsp;' . $this->close;
					}
					// show biblio
					$this->show_text .= $biblio[1] . $this->close;
					// show biblio in latex style
					$this->show_latex .= $biblio[0] . $this->close;
					// show labels
					if($this->data['public'] == 0) {
						$this->show_label = '<div class=\'label private\'>';
					}
					if (!empty($this->data['label'])) {
						$this->show_label .= '<p>';

						$i = 0;
						while ($i < count($this->data['label'])) {
							$this->show_label .= '<span><a href=\'?label=' . $this->data['label'][$i]['id'] . '\' title=\'#notes with this label: ' . $this->data['label'][$i]['number'] . '\'>' . $this->data['label'][$i]['name'] . '</a></span>';
							$i++;
						}
						$this->show_label .= '</p>';
					}
					$this->show_label .= $this->close;
					// and get the tools
					$this->show_tools .= $this->showTools();
				} else {
					// no results
					$this->open_note = '';

					$this->show_media = '';
					$this->show_text = '';
					$this->show_latex = '';
					$this->show_label = '';
					$this->show_tools = '';

					$this->close = '';
				}

		}


		if(!empty($this->data)) {
			echo $this->open_note;
				echo $this->show_media;
				echo $this->show_text;
				echo $this->show_latex;
				echo $this->show_label;
				echo $this->show_tools;
			echo $this->close;	// close note element


		} else {
			// no results
		}
	}

	function showSourceWithNotes() {
		$this->showData();

		if(!empty($this->data)) {
			$i = 0;
			$count = count($this->data['notes']);
			while ($i < $count) {
				$note = NEW show();
				$note->id = $this->data['notes'][$i];
				$note->access = $this->access;
				$note->type = 'note';
				$note->showData();
				$i++;
			}


		}



	}

	function showCollectionWithSources() {
		$this->showData();

		if(!empty($this->data)) {
			$i = 0;
			$count = count($this->data['sources']);
			while ($i < $count) {
				$source = NEW show();
				$source->id = $this->data['sources'][$i];
				$source->access = $this->access;
				$source->type = 'source';
				$source->showSourceWithNotes();
				$i++;
			}


		}



	}

}