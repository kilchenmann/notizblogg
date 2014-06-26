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
	var $show_media;
	var $show_text;
	var $show_latex;
	var $show_label;
	var $show_tools;

	var $open_note = '<div class=\'note\'>';
	var $close = '</div>';



	function show() {


	}

	function showMedia($media, $title)
	{
		$mediaFile = explode(".", $media);
		$fileName = $mediaFile[0];
		$extension = $mediaFile[1];
		$mediaInfo = __MEDIA_URL__ . "/pictures/" . $media;
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
					return '<img class=\'staticMedia\' src=\'' . __MEDIA_URL__ . '/pictures/ ' . $media . '\' alt=\'' . $fileName . '\' title=\'' . $title . '\' />';
					break;

				case "pdf";
					return '<p class=\'download\'>' . $media . ' <a href=\'' . __MEDIA_URL__ . '/documents/ ' . $media . '\' title=\'Download ' . $fileName . ' as pdf.\'>Open</a></p>';
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
//return $this->id, $this->data['media'], $this->data['title'];

		}
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
				if($this->data['id'] !== 0) {


					//echo '<div class=\'note\' id=\'' . $this->data['id'] . '\'>';
					// show media, if exist
					if ($this->data['media'] !== '') {
						$this->show_media = '<div class=\'media\'>';
						$this->show_media .= $this->showMedia($this->data['media'], $this->data['title']);
						echo '</div>';
					}
					// show text
					echo '<div class=\'text\'>';
					echo '<h3>' . $this->data['title'] . '</h3>';
					echo '<p>' . makeurl($this->data['content']) . '</p>';
					if($this->data['source']['id'] != 0 && $this->data['source']['bibTyp']['name'] != 'projcet'){
						$pages = "";
						if($this->data['page']['start'] != 0){
							$pages = $this->data['page']['start'];
							if($this->data['page']['end'] != 0) {
								$pages .= '-' . $this->data['page']['end'];
							}
						}
						echo '<p class=\'small\'>(' . $this->data['source']['author']['name'] .': <a href=\'?source=' . $this->data['source']['id'] . '\'>' . $this->data['source']['title'] . '</a>, S. ' . $pages . ')</p>';
//						echo '<p class=\'small\'>\cite[][' . $pages . ']{' . $sourceData['name'] . '}</p>';

					}
					echo '</div>';

					echo '<div class=\'latex\'>';
					echo '<h3>' . $this->data['title'] . '</h3>';
					echo '<p>``' . change4Tex(makeurl($this->data['content'])) . '\'\'</p>';
					if($this->data['source']['id'] != 0 && $this->data['source']['bibTyp']['name'] != 'projcet'){
//					$source = NEW source();
//					$sourceData = json_decode($source->getSource($this->data['source']['id'], $this->access), true);
//					if($sourceData['bibTyp']['name'] != 'project'){
						$pages = "";
						if($this->data['page']['start'] != 0){
							$pages = $this->data['page']['start'];
							if($this->data['page']['end'] != 0) {
								$pages .= '-' . $this->data['page']['end'];
							}
						}
						echo '<p class=\'small\'>\cite[][' . $pages . ']{' . $this->data['source']['name'] . '}</p>';
//					}
					}
					echo '</div>';
					if($this->data['label']['name'] != '') {
						echo '<div class=\'label\'>';
						echo '<p>' . $this->data['label']['name'] . '</p>';
						echo '</div>';
					}
					echo '<div class=\'tools\'>';
					echo '<div class=\'left\'>';
					if($this->access != 'public' && isset($_SESSION['token'])) {
						echo '<button class=\'btn grp_none toggle_edit\' id=\'edit_note_' . $this->id . '\'></button>';
					} else {
						echo '<button class=\'btn grp_none fake_btn\'></button>';
					}
					echo '</div>';
					echo '<div class=\'center\'>';
					if($this->data['source']['id'] != 0 && $this->data['source']['bibTyp']['name'] != 'projcet'){
						echo '<button class=\'btn grp_none toggle_cite\' id=\'cite_note_' . $this->id . '\'></button>';
					} else {
						echo '<button class=\'btn grp_none fake_btn\'></button>';
					}
					echo '</div>';
					echo '<div class=\'right\'>';
					echo '<button class=\'btn grp_none toggle_expand\' id=\'expand_note_' . $this->id . '\'></button>';
					echo '</div>';
					echo '</div>';
					echo '</div>';
				} else {
					// no results

				}

				break;

			default;		// source
				$this->data = json_decode($show->getSource(), true);
				$this->open_note = '<div class=\'note topic\'>';
		}



		$open_media = '<div class=\'media\'>';
		$open_text = '<div class=\'text\'>';
		$open_latex = '<div class=\'latex\'>';
		$open_label = '<div class=\'label\'>';
		$open_tools = '<div class=\'tools\'>';








		if($this->data['id'] != 0) {

		//	$media = showMedia(, $this->data['title']);

			echo $this->open_note;


			echo $open_media . $this->data['media'] . $close;
			echo $open_text . $this->data['title'] . '<br>' . $this->data['content']  . $close;
			echo $open_latex . ' ' . $close;
			echo $open_label . ' ' . $close;
			echo $open_tools . ' ' . $close;
			echo $close;	// close note element


		} else {
			// no results

		}




	}

} 