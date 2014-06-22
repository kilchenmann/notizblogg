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

		if($access === 'public') {
			$gpa = ' AND sourcePublic = 1';
			$editSource = '';
		} else {
			$gpa = '';
		}

		condb('open');

		//$sources[] = array();
		$source = new stdClass();
		$source->category = new stdClass();
		$source->project = new stdClass();
		$source->source = new stdClass();
		$sourceSql = mysql_query('SELECT * FROM source WHERE sourceID=\'' . $id . '\'' . $gpa . ';');

		while($row = mysql_fetch_object($sourceSql)) {
			// get the category
			$categoryName = getIndex('category', $row->sourceCategory);
			// get the project
			$projectName = getIndex('project', $row->sourceProject);
			// get the type
			$bibTyp = getIndex('bibTyp', $row->sourceTyp);
			// get the tags
			$authorNames = linkIndexMN('source','author', $id);


			$sources = array(
				'id' => $row->sourceID,
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
				'author' => array(
					'name' => $authorNames
				),
				'comment' => $row->sourceNote
			);
		}
		condb('close');

		return json_encode($sources);

		// or as another json:
		// return '{"sources":'.json_encode($sources).'}';	// <-- orig!
	}

	function showSource($id, $access) {
		$source = NEW source();
		$data = json_decode($source->getSource($id, $access), true);


		echo '<div class=\'text\'>';
		echo '<p>' . $data['author']['name'] . '</p>';
		echo '<h3>' . $data['title'] . '</h3>';
		echo '<p>' . makeurl($data['comment']) . '</p>';
		echo '</div>';

		echo '<div class=\'tools\'>';
		echo '<p><a href=\'?label=' . $data['category']['id'] . '\'>' . $data['category']['name'] . '</a></p>';

		echo '</div>';

	}

	function editSource($id) {


	}

	function saveSource($id) {


	}


}
