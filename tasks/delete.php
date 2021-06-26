<?php

require_once('./../tasks.php');

$task = new Tasks;
echo $task->deleteTask($_REQUEST['id']);

?>