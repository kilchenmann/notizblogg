<?php
//
// some connection stuff
//
function condb($conart) {
    $mysqli = new mysqli($GLOBALS['nb']['host'], $GLOBALS['nb']['user'], $GLOBALS['nb']['pass'], $GLOBALS['nb']['db']);
    if ($mysqli->connect_errno) {
        die('Connect Error: ' . $mysqli->connect_errno);
    }
//    mysqli_set_charset($mysqli->connect,'utf8');
    if($conart == 'close') {
        mysqli_close($mysqli);
        $mysqli = null;
    }
    return $mysqli;
}

function conuser($token) {
    $user = array(
        'access' => 1,
        'name' => 'guest',
        'id' => '',
        'avatar' => ''
    );
    // check if the access is true and correct
    $token = (explode('-', $token . '-'));
    $mysqli = condb('open');
    $sql = $mysqli->query("SELECT user, userID, email FROM user WHERE userID = " . $token[1] . " AND token = '" . $token[0] . "';");
    condb('close');
    $num_results = mysqli_num_rows($sql);
    if ($num_results > 0) {
        while ($row = mysqli_fetch_object($sql)) {
            $avatar = __MEDIA_URL__ . '/user/' . $row->userID;
            if (@fopen($avatar, "r") == false) {
                $avatar = 'http://www.gravatar.com/avatar/' . md5($row->email);
                if (@fopen($avatar, "r") == false) {
                    $avatar = __MEDIA_URL__ . '/user/' . $row->userID;
                }
            }
            $user = array(
                'access' => 0,
                'name' => $row->user,
                'id' => $row->userID,
                'avatar' => $avatar
            );
        }
    }
    return ($user);
}

//
// some get functions
//

function getIndex($part, $id)
{
    $array = array(
        'id' => '0',
        'name' => ''
    );
    if ($id > 0) {
        $partID = $part . 'ID';
        $mysqli = condb('open');
        $sql = $mysqli->query('SELECT ' . $part . ' FROM ' . $part . ' WHERE `' . $partID . '` = \'' . $id . '\';');
        condb('close');
        // echo 'SELECT ' . $part . ' FROM ' . $part . ' WHERE `' . $partID . '` = \'' . $id . '\';';
        $num_results = mysqli_num_rows($sql);
        if ($num_results > 0) {
            while ($row = mysqli_fetch_object($sql)) {
                $array = array(
                    'id' => $id,
                    'name' => $row->$part
                );
            }
        }
    }
    return $array;
};

function getIndexMN($type, $part, $id)
{
    $array = array();
    $relTable = "rel_" . $type . "_" . $part;
    $partID = $part . "ID";
    $mysqli = condb('open');
    $sql = $mysqli->query('SELECT ' . $part . '.' . $partID . ', ' . $part . ' FROM ' . $part . ', ' . $relTable . ' WHERE ' . $part . '.' . $partID . ' = ' . $relTable . '.' . $partID . ' AND ' . $relTable . '.' . $type . 'ID = \'' . $id . '\' ORDER BY ' . $part);
    //echo '<br>getIndexMN: SELECT ' . $part . '.' . $partID . ', ' . $part . ' FROM ' . $part . ', ' . $relTable . ' WHERE ' . $part . '.' . $partID . ' = ' . $relTable . '.' . $partID . ' AND ' . $relTable . '.' . $type . 'ID = \'' . $id . '\' ORDER BY ' . $part . '<br>';

    $num_labels = mysqli_num_rows($sql);
    if ($num_labels > 0) {
        while ($row = mysqli_fetch_object($sql)) {
            // get number of notes with this value
            $num_results = mysqli_num_rows($mysqli->query('SELECT * FROM ' . $relTable . ' WHERE ' . $partID . ' = \'' . $row->$partID . '\';'));
            array_push($array, array('id' => $row->$partID, 'name' => $row->$part, 'num' => $num_results));
        }
    }
    condb('close');
    return $array;
};


function getNote2Author($id) {
    $mysqli = condb('open');
    $sql = $mysqli->query('SELECT bib.noteID, note.notePublic FROM rel_bib_author, note, bib WHERE rel_bib_author.authorID = ' . $id . ' AND rel_bib_author.bibID = bib.bibID AND bib.noteID = note.noteID;');
    condb('close');
    $num_results = mysqli_num_rows($sql);
    $notes = array();
    if($num_results > 0) {
        $i=0;



        while($row = mysqli_fetch_object($sql)) {
//			$mysqli = condb('open');
//			$sql = $mysqli->query('SELECT noteID FROM bib WHERE bibID = ' . $brow->bibID . ';');
//			condb('close');
//			while($row = mysqli_fetch_object($sql)){
//				array_push($notes, $row->noteID);
//			}

            $notes[$i]['id'] = $row->noteID;
            $notes[$i]['ac'] = $row->notePublic;
            $i++;
        }
    }
    return $notes;
}


function getNote2Label($id) {
    $mysqli = condb('open');
    $sql = $mysqli->query('SELECT note.noteID, note.notePublic FROM rel_note_label, note WHERE rel_note_label.labelID = ' . $id . ' AND rel_note_label.noteID = note.noteID;');
    condb('close');
    $num_results = mysqli_num_rows($sql);
    $notes = array();
    if($num_results > 0) {
        $i=0;
        while($row = mysqli_fetch_object($sql)) {
            $notes[$i]['id'] = $row->noteID;
            $notes[$i]['ac'] = $row->notePublic;
            $i++;
        }
    }
    return $notes;
}


function getMedia($media) {
    $tmp_media = explode('/', $media . '/');
    $mediaType = $tmp_media[0];
    $mediaFile = $tmp_media[1];

    $tmp_file = explode('.', $mediaFile . '.');
    $name = $tmp_file[0];
    $ext = $tmp_file[1];

    $mediaURL = __MEDIA_URL__  . '/' . $media;
    $mediaPath = __MEDIA_PATH__  . '/' . $media;

    //if(file_exists($mediaURL)) echo 'file exists';

    if($media != '') {

    }
    $media_tag = '<span class=\'warning invisible\'>[The ' . $mediaType . ' file is missing!]</span>';

    if (@fopen($mediaPath, 'r') == true) {
        switch($mediaType) {
            case 'picture';
                $size = getimagesize($mediaURL);
                // ergibt mit $infoSize[0] für breite und $infoSize[1] für höhe
                $media_tag = '<img class=\'staticMedia\' src=\'' . $mediaURL . '\' alt=\'' . $mediaFile . '\' title=\'' . $mediaType . ': ' .$mediaFile . '\'>';
                break;
            case 'document';
        //		$media_tag = '<a href=\'' . $mediaURL . '\' title=\'Download ' . $media . '\'><img class=\'staticMedia\' src=\'' . __MEDIA_URL__ . "/documents/".$name."' title='".$media."' alt='".$media."'/></a>";
                break;
            case 'movie';
                $media_tag = '<video class=\'motionPicture\'>Motion Picture is not yet supported in Notizblogg</video>';
                break;
            case 'sound';
                $media_tag = '<audio class=\'motionPicture\'>Motion Picture is not yet supported in Notizblogg</audio>';
                break;
            default;
                $media_tag = '';
        }
    } else {
    //	$media_tag = '';
    }

    $media_arr = array(
            'type' => $mediaType,
            'file' => $mediaFile,
            'path' => $media,
            'html' => $media_tag
    );

    return $media_arr;
}

//
// insert new entries
//
/* *************************************************
/* Formular and post functions
/* *********************************************** */
// for label, for author, for locations
// table: label, author, location
// tableID: labelID, authorID, locationID
// value:	label, author, location
// rel_table: rel_note_label, rel_bib_author, rel_bib_location
// relID: noteID, bibID, bibID
function insertMN($name, $rel, $data, $id) {
    $data = trim($data);
    $tableID = $name . 'ID';
    $rel_table = 'rel_' . $rel . '_' . $name;
    $relID = $rel . 'ID';
    if($data != '') {
        $d = explode('/', $data);
        foreach($d as $n) {
            // set the name value (n)
            $n = trim($n);
            $n = htmlentities($n, ENT_QUOTES, 'UTF-8');
//            echo 'INSERT INTO ' . $name . ' (' . $name . ') VALUES (\'' . $n . '\');';
            $mysqli = condb('open');
            // set the query string (qs)
            $sql = $mysqli->query('SELECT ' . $tableID . ' FROM ' . $name . ' WHERE ' . $name . ' = \'' . $n . '\';');
            $num_results = mysqli_num_rows($sql);
            if($num_results == 1) {
                while($row = mysqli_fetch_object($sql)) {
                    $relIDs[] = $row->$tableID;
                }
            } else {
                // new data
                if($n != '') {
//                    $value = htmlentities($n, ENT_QUOTES, 'UTF-8');
                    $newsql = $mysqli->query('INSERT INTO ' . $name . ' (' . $name . ') VALUES (\'' . $n . '\');');
                    $relIDs[] = $mysqli->insert_id;
                }
            }
            foreach($relIDs as $rid) {
                $mysqli->query('INSERT INTO ' . $rel_table . ' (' . $tableID . ', ' . $relID . ') VALUES (\'' . $rid . '\', \'' . $id . '\');');
            }
            $mysqli = condb('close');
        }
    }
}

//
// update (edit) old entries
//

function updateMN($name, $rel, $data, $id) {
    $data = trim($data);
    $tableID = $name . 'ID';
    $rel_table = 'rel_' . $rel . '_' . $name;
    $relID = $rel . 'ID';
    deleteMN($name, $rel, $data, $id);
    if($data != '') {
        // first: delete the relation and set it as new
        insertMN($name, $rel, $data, $id);
    }
}

function deleteMN($name, $rel, $data, $id) {
    //function getIndexMN($type, $part, $id)
    $tableID = $name . 'ID';                        // e.g. labelID
    $rel_table = 'rel_' . $rel . '_' . $name;       // e.g. rel_note_label
    $relID = $rel . 'ID';                           // e.g. noteID

    $mysqli = condb('open');
    $sql = $mysqli->query('SELECT ' . $tableID . ' FROM ' . $rel_table . ' WHERE ' . $relID . ' = ' . $id . ';');
    while($row = mysqli_fetch_object($sql)) { // check the numbers of e.g. labels in rel_note_label with this labelID
        $checksql = $mysqli->query('SELECT * FROM ' . $rel_table . ' WHERE ' . $tableID . ' = ' . $row->$tableID . ';');
        $num_results = mysqli_num_rows($checksql);
        if ($num_results <= 1) {
            $mysqli->query('DELETE FROM ' . $name . ' WHERE ' . $tableID . ' = ' . $row->$tableID . ';');
        }
        $mysqli->query('DELETE FROM ' . $rel_table . ' WHERE ' . $relID . ' = ' . $id . ';');
    }
}


function insertDetail($prop, $val, $id) {
    $mysqli = condb('open');
    $sql = $mysqli->query('SELECT bibFieldID FROM bibField WHERE bibField = \'' . $prop . '\';');
    while($row = mysqli_fetch_object($sql)) {
        $bibFieldID = $row->bibFieldID;
    }
    $val = trim($val);
    $val = htmlentities($val, ENT_QUOTES, 'UTF-8');
    $newsql = $mysqli->query('INSERT INTO bibDetail ( bibID, bibFieldID, bibDetail ) VALUES (\'' . $id . '\', \'' . $bibFieldID . '\', \'' . $val . '\');');
    $mysqli = condb('close');
}

function updateDetail($prop, $val, $id) {
    $mysqli = condb('open');
    $mysqli->query('DELETE FROM bibDetail WHERE bibID = ' . $id . ';');
    $mysqli = condb('close');
}



//
// delete functions
//
