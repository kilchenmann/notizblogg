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

	function getSource($id) {

		condb('open');

		$source = new stdClass();
		$sourceSql = mysql_query('SELECT * FROM source WHERE sourceID=\'' . $id . '\';');

		while($row = mysql_fetch_object($sourceSql)) {
			// get the category
			$categoryName = getIndex('category', $row->sourceCategory);

			// get the project
			$projectName = getIndex('project', $row->sourceProject);

			// get the authors
			$authorNames = getIndexMN('source','author', $id);

			$source->id = $row->noteID;
			$source->name = $row->sourceName;
			$source->title = $row->sourceTitle;
			$source->subtitle = $row->sourceSubtitle;
			$source->year = $row->sourceYear;
			$source->type->id = $row->sourceTyp;
			$source->editor = $row->sourceEditor;
			$source->note = $row->sourceNote;
			$source->category->id = $row->noteCategory;
			$source->category->name = $categoryName;
			$source->project->id = $row->noteProject;
			$source->project->name = $projectName;
			$source->authors = $authorNames;
		}
		condb('close');

		echo json_encode($source);

	}

	function editSource($id) {


	}

	function saveSource($id) {


	}


}
