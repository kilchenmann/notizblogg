<?php
/**
 * Created by IntelliJ IDEA.
 * User: ak
 * Date: 25/04/14
 * Time: 13:27
 */

class note {

	function note() {
		/*
		dieses "function" heisst wie die Klasse
		sobald die Klasse mit NEW erstellt wird, wird diese function als erstes aufgerufen -
		ohne dass wir sie manuell starten
		*/

	}

	function getNote($id, $access) {

		if($access === 'public') {
			$gpa = ' AND notePublic = 1';
			$editNote = '';
		} else {
			$gpa = '';
		}

		condb('open');

//		$notes = array();
		$note = new stdClass();
		$note->category = new stdClass();
		$note->project = new stdClass();
		$note->source = new stdClass();
		$noteSql = mysql_query('SELECT * FROM note WHERE noteID=\'' . $id . '\'' . $gpa . ';');
//		$row = array();



		while($row = mysql_fetch_object($noteSql)) {
			// get the category
			$categoryName = getIndex('category', $row->noteCategory);

			// get the project
			$projectName = getIndex('project', $row->noteProject);

			// get the tags
			$tagNames = getIndexMN('note','tag', $id);

//			$notes[] = $row;

			$note->id = $row->noteID;
			$note->title = $row->noteTitle;
			$note->content = $row->noteContent;
			$note->category->id = $row->noteCategory;
			$note->category->name = $categoryName;
			$note->project->id = $row->noteProject;
			$note->project->name = $projectName;
			$note->tags = $tagNames;
			$note->media = $row->noteMedia;
			$note->source->id = $row->noteSource;
			$note->source->start = $row->pageStart;
			$note->source->end = $row->pageEnd;
			$note->url = $row->noteSourceExtern;
			$note->access = $row->notePublic;

			echo '<div class="note">';
			if ($note->media !== '') {
				echo $note->media;
				//showMedia($noteID, $noteMedia, $noteTitle);
			}
			echo '<h3>' . $note->title . '</h3>';
			echo '<p class="content">';
			if ($note->source->id != 0) {
				echo "``".makeurl(nl2br($note->content))."''";
			} else {
				echo makeurl(nl2br($note->content));
			}
//			showSourceCite($note->source->name,$note->source->start,$note->source->end);
			echo '</p>';
			if ($note->url !== '') {
				echo '<p class="linkText"> --&gt; <a href="' . $note->url . '" title="extern">' . $note->url . '</a></p>';
			}
			echo '</div>';


			/*
			echo '
				{
				"id":"'.$row->noteID.'",
				"title":"'.$row->noteTitle.'",
				"content":"'.$row->noteContent.'"
				},';
			*/
			/*
			$id = array([
				'id' => $row->noteID,
				'title' => $row->noteTitle,
				'content' => $row->noteContent,
				'category' => $categoryName,
				'categoryID' => $row->noteCategory,
				'project' => $projectName,
				'projectID' => $row->noteProject,
/*
				'project' => array(
					array('proID' => $row->noteProject, 'proName' => $projectName)
				),
*/
			/*
				'source' => array(
					array(
						'extern' => $row->noteSourceExtern,
						'id' => $row->noteSource,
						'pageStart' => $row->pageStart,
						'pageEnd' => $row->pageEnd
					)
				),
				'tag' => $tagNames,
				'media' => $row->noteMedia,
				'access' => $row->notePublic
			]);
			*/
		}
		condb('close');


//		echo '<div class=\'note\'>';
//		echo json_encode($note);
//		echo '</div>';

//		echo '<div>note.title + '<br>' + note.content + '</div>



//		echo '{"notes":' . json_encode($var) . '}';
//		echo '{"notes":' . json_encode($id) . '}';
//		$notes = 'notes: [{' . json_encode($id) . '}]';
//		echo json_encode($notes);
//		echo ']}';
//		echo json_encode($id) . PHP_EOL;
//		$json = json_encode($note);
//		echo "notes: [" . $json . "] " . PHP_EOL;
/*
		echo "Decoded JSON (as associative array):" . PHP_EOL;
		print_r(json_decode($json, true)) . PHP_EOL;
		echo "Decoded JSON (as stdClass object):" . PHP_EOL;
		print_r(json_decode($json)) . PHP_EOL;
*/
	}

	function editNote($id) {


	}

	function saveNote($id) {


	}


}
