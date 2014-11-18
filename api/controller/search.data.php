<?php
/**
 * Created by IntelliJ IDEA.
 * User: ak
 * Date: 24.06.14
 * Time: 01:28
 */

require_once 'settings.php';
if(isset($_GET['q']) && $_GET['q'] !== ''){

	header ('Location: ' . __SITE_URL__ . '/?q='.$_GET['q']);
} else {
	header ('Location: ' . __SITE_URL__ . '/');
}



//$query=$_POST('swq');



//print_r($_GET("swq"));