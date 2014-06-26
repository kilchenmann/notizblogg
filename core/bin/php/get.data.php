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
