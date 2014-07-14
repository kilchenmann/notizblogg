<?php
/**
 * Created by IntelliJ IDEA.
 * User: ak
 * Date: 24.06.14
 * Time: 01:28
 */

require_once 'setting.php';
if(isset($_GET['swq']) && $_GET['swq'] !== ''){

	header ('Location: ' . __SITE_URL__ . '/?q='.$_GET['swq']);
} else {
	header ('Location: ' . __SITE_URL__ . '/');
}



//$query=$_POST('swq');



//print_r($_GET("swq"));