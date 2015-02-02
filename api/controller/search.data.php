<?php
/**
 * Created by IntelliJ IDEA.
 * User: ak
 * Date: 24.06.14
 * Time: 01:28
 */


require_once 'settings.php';
$query = '';
if(isset($_GET['q']) && $_GET['q'] !== ''){
	$query = 'q=' . $_GET['q'];
	if(isset($_GET['filter']) && $_GET['filter'] !== '') {
		$query = $query . '&filter=' . $_GET['filter'];
	}
}
header ('Location: ' . __SITE_URL__ . '/?' . $query);


//$query=$_POST('swq');



//print_r($_GET("swq"));
