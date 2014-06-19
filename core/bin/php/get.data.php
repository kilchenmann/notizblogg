<?php
/**
 * Created by IntelliJ IDEA.
 * User: ak
 * Date: 18/06/14
 * Time: 15:43
 */

//$indexTitle = "note";
if($access == 'public'){
	$gpa = "AND notePublic = 1";
} else {
	$gpa = "";
}




switch($type){
	case 'source':
		echo $type . ": " . $query . PHP_EOL;
		break;

	case 'note':
		echo $type . ": " . $query . PHP_EOL;
		break;

	case 'label':
		echo $type . ": " . $query . PHP_EOL;
		break;

	case 'search':
		echo $type . ": " . $query . PHP_EOL;
		break;

	default:
		echo 'source: ALL';
}



