<?php

require_once('./../tasks.php');

$task = new Tasks;
if($_REQUEST)
{
    if(@$_REQUEST['id'])
    {
        echo $task->getTaskById($_REQUEST['id']);
    }else if(@$_REQUEST['user']){
        echo $task->getUserTasks($_REQUEST['user']);
    }else{
        echo "Invalid Task Details";
    }
}else{
    echo "Invalid Request Method";
}

?>