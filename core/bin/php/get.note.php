<?php
require ('setting.php');

$note = NEW note();
$access = 'enable';

$nID = 126;
$note->getNote($nID, $access);

