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

			$showBibtex = '@' . $this->data['bibTyp']['name'] . '{' . $this->data['name'] . ',<br>';
			$showBiblio = ''; // $this->data['id'];

			if($this->data['editor'] == 1){
				$showBibtex .= 'editor = { ' . ($this->data['author']['name']) . '},<br>';
				$showBiblio .= $this->data['author']['name'] . ' (Hg.):<br>';
			} else {
				$showBibtex .= 'author = {' . ($this->data['author']['name']) . '},<br>';
				$showBiblio .= $this->data['author']['name'] . ':<br>';
			}
			$showBibtex .= 'title = {' . ($this->data['title']) . '},<br>';
			$showBiblio .= '<a href=\'?source=' . $this->data['id'] . '\' >'. $this->data['title'] . '</a>. ';

			if($this->data['subtitle'] != ''){
				$showBibtex .= 'subtitle = {' . ($this->data['subtitle']) . '},<br>';
				$showBiblio .= $this->data['subtitle'] . '.<br>';
			}

			if(array_key_exists('crossref', $this->data)) {

				$showBibtex .= 'crossref = {<a href=\'?source=' . $this->data['crossref']['id'] . '\'>' . ($this->data['crossref']['name']) . '</a>},<br>';

				$showBiblio .= 'In: ';
				if($this->data['crossref']['editor'] == 1){
					$showBibtex .= 'editor = { ' . ($this->data['crossref']['author']['name']) . '},<br>';
					$showBiblio .= $this->data['crossref']['author']['name'] . ' (Hg.):<br>';
				} else {
					$showBibtex .= 'author = {' . ($this->data['crossref']['author']['name']) . '},<br>';
					$showBiblio .= $this->data['crossref']['author']['name'] . ':<br>';
				}
				$showBibtex .= 'booktitle = {' . ($this->data['crossref']['title']) . '},<br>';
				$showBiblio .= '<a href=\'?source=' . $this->data['crossref']['id'] . '\'>' . $this->data['crossref']['title'] . '. </a>';

				if($this->data['crossref']['subtitle'] != ''){
					$showBibtex .= 'booksubtitle = {' . ($this->data['crossref']['subtitle']) . '},<br>';
					$showBiblio .= $this->data['crossref']['subtitle'] . '.<br>';
				}

				if($this->data['crossref']['location']['name'] != ''){
					$showBibtex .= 'location = {' . ($this->data['crossref']['location']['name']) . '},<br>';
					$showBiblio .= $this->data['crossref']['location']['name'] . ', ';
				}
				if($this->data['year'] != '0000'){
					$showBibtex .= 'year = {' . $this->data['crossref']['year'] . '},<br>';
					$showBiblio .= $this->data['crossref']['year'] . '';
				}

			} else {
				if($this->data['location']['name'] != ''){
					$showBibtex .= 'location = {' . ($this->data['location']['name']) . '},<br>';
					$showBiblio .= $this->data['location']['name'] . ', ';
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

		// is it public
		if($this->data['public'] > 0) {
			$class_public = 'public';
		} else {
			$class_public = '';
		}

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

		return '<div class=\'tools ' . $class_public . '\' id=\'' . $id_expand . '\'></div>';


	}

	function showData() {
		$show = NEW get();
		$show->id = $this->id;
		$show->access = $this->access;

		switch($this->type) {
			case 'author';

				break;

			case 'note';
				$this->data = json_decode($show->getNote(), true);
				if ($this->data['id'] !== 0) {
					$this->open_note = '<div class=\'note\' id=\'' . $show->id . '\'>';
					// show media, if exist
					if ($this->data['media'] != '') {
						$this->show_media .= $this->showMedia();
					}
					$this->show_media .= $this->close;
					// show text
					// set title
					$this->show_text .= '<h3>' . $this->data['title'] . '</h3>';
					// set content
					$this->show_text .= '<p>' . makeurl($this->data['content']) . '</p>';
					// set source, if exists
					if(!empty($this->data['source'])) {
						//print_r(json_encode($this->data));
						if ($this->data['source']['id'] != 0 && $this->data['source']['bibTyp']['name'] != 'projcet') {
							$pages = "";
							if ($this->data['page']['start'] != 0) {
								$pages = $this->data['page']['start'];
								if ($this->data['page']['end'] != 0) {
									$pages .= '-' . $this->data['page']['end'];
								}
							}
							$this->show_text .= '<p class=\'small\'>(' . $this->data['source']['author']['name'] . ': <a href=\'?source=' . $this->data['source']['id'] . '\'>' . $this->data['source']['title'] . '</a>, S. ' . $pages . ')</p>';
						}
					}
					$this->show_text .= $this->close;

					// show text with quotation marks. to use in latex later
					$this->show_latex .= '<h3>' . $this->data['title'] . '</h3>';
					$this->show_latex .= '<p>``' . change4Tex(makeurl($this->data['content'])) . '\'\'</p>';
					if(!empty($this->data['source'])) {
						if ($this->data['source']['id'] != 0 && $this->data['source']['bibTyp']['name'] != 'projcet') {
							$pages = "";
							$this->show_latex .= '<p class=\'small\'>\cite[][' . $pages . ']{' . $this->data['source']['name'] . '}</p>';
						}
					}
					$this->show_latex .= $this->close;

					// show labels
					if ($this->data['label']['name'] != '') {
						$this->show_label .= '<p>' . $this->data['label']['name'] . '</p>';
					}
					$this->show_label .= $this->close;

					$this->show_tools .= $this->showTools();

				} else {
					// no results

				}

				break;

		//	case 'source';

		//		break;

			default; // source
				$this->data = json_decode($show->getSource(), true);
				if ($this->data['id'] !== 0) {
					$this->open_note = '<div class=\'note topic\' id=\'' . $show->id . '\'>';
					$biblio = $this->showBib();
					//$biblio = array('LATEX','BIBLIO');
					$this->show_media .= $this->close;
					// show biblio
					$this->show_text .= $biblio[1] . $this->close;
					// show biblio in latex style
					$this->show_latex .= $biblio[0] . $this->close;
					// show labels
					$this->show_label .= '<p>' . $this->data['label']['name'] . '</p>' . $this->close;
					// and get the tools
					$this->show_tools .= $this->showTools();
				} else {
					// no results
				}

		}


		if($this->data['id'] != 0) {
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

		if($this->data['notes'] != '') {
			$i = 0;
			$count = count($this->data['notes']);
			while ($count > 0) {
				$note = NEW show();
				$note->id = $this->data['notes'][$i];
				$note->access = $this->access;
				$note->type = 'note';
				$note->showData();
				$i++;
				$count--;
			}


		}



	}

}