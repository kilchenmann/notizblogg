<?php
require ('class.note.php');
$note = NEW note();
$access = 'enable';

$nID = 126;

$note->getNote($nID, $access);