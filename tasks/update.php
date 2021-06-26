<?php

require_once('./../tasks.php');

$task = new Tasks;

// Takes raw data from the request
$json = file_get_contents('php://input');

// Converts it into a PHP object
$data = json_decode($json);

$arr = array();

if(@$_REQUEST['id'])
{
    if(@$_REQUEST['status']){
        echo $task->updateTaskStatus($_REQUEST['id'],$_REQUEST['status']);
    }else{
        if($json)
        {
            $arr = array(
                'taskTitle' => $data->taskTitle, 
                'taskDetails' => $data->taskDetails, 
                'deadlineDate' => $data->deadlineDate, 
                'deadlineTime' => $data->deadlineTime
            );
        }else{
            $arr = array(
                'taskTitle' => $_REQUEST['taskTitle'],
                'taskDetails' => $_REQUEST['taskDetails'],
                'deadlineDate' => $_REQUEST['deadlineDate'],
                'deadlineTime' => $_REQUEST['deadlineTime']
            );
        }

        echo $task->updateTask($_REQUEST['id'], $arr);
    }
}

?>