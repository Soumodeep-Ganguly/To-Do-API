<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

require_once('config.php');

class Tasks {
    private $conn;

    public function __construct(){
        $this->conn = new Connect;
    }

    // Get User's All Tasks
    function getUserTasks($id) {
        $tasks_arr = array();
        
        $data = $this->conn->prepare("SELECT * FROM tasks WHERE userId='$id'");
        $data->execute();

        if($data->rowCount() > 0)
        {
            $tasks_arr['data'] = array();
            while($row = $data->fetch(PDO::FETCH_ASSOC)) {
                extract($row);
                $task = array(
                    'id' => $id,
                    'userId' => $userId,
                    'taskTitle' => $taskTitle,
                    'taskDetails' => $taskDetails,
                    'deadlineDate' => $deadlineDate,
                    'deadlineTime' => $deadlineTime,
                    'status' => $status,
                    'createdAt' => $createdAt,
                    'updatedAt' => $updatedAt
                );

                array_push($tasks_arr["data"], $task);
            }
            header("HTTP/1.1 200 SUCCESS");
        }else{
            header("HTTP/1.1 404 ERROR");
            $tasks_arr['error'] = "No Record Found";
        }
        return json_encode($tasks_arr);
    }

    // Get Task By Id
    function getTaskById($id) {
        $task_arr = array();

        $data = $this->conn->prepare("SELECT * FROM tasks WHERE id='$id'");
        $data->execute();

        if($data->rowCount() > 0)
        {
            $tasks_arr['data'] = array();
            while($row = $data->fetch(PDO::FETCH_ASSOC)) {
                extract($row);
                $task = array(
                    'id' => $id,
                    'userId' => $userId,
                    'taskTitle' => $taskTitle,
                    'taskDetails' => $taskDetails,
                    'deadlineDate' => $deadlineDate,
                    'deadlineTime' => $deadlineTime,
                    'status' => $status,
                    'createdAt' => $createdAt,
                    'updatedAt' => $updatedAt
                );

                array_push($tasks_arr["data"], $task);
            }
            header("HTTP/1.1 200 SUCCESS");
        }else{
            header("HTTP/1.1 404 ERROR");
            $tasks_arr['error'] = "No Record Found";
        }
        return json_encode($tasks_arr);
    }

    // Sign Up New Task 
    function createTask($id, $task) {
        $user_arr = array();

        $data = $this->conn->prepare("INSERT INTO tasks(userId, taskTitle, taskDetails, deadlineDate, deadlineTime) VALUES(:userId, :taskTitle, :taskDetails, :deadlineDate, :deadlineTime)");

        try {
            $this->conn->beginTransaction();
            $data->execute(array(
                'userId' => $id, 
                'taskTitle' => $task['taskTitle'], 
                'taskDetails' => $task['taskDetails'], 
                'deadlineDate' => $task['deadlineDate'], 
                'deadlineTime' => $task['deadlineTime']
            ));
            $this->conn->commit();
            
            if($data) 
            {
                $user_arr['success'] = "Successfully inserted";
                $tasks_arr['data'] = array("id" => $this->conn->lastInsertId());
                header("HTTP/1.1 201 OK");
            }else{
                header("HTTP/1.1 400 ERROR");
                $user_arr['error'] = "Unable to create user";
            }
        } catch(PDOExecption $e) {
            $this->conn->rollback();
            header("HTTP/1.1 400 ERROR");
            $user_arr['error'] = $e->getMessage();
        }
        return json_encode($user_arr);
    }

    // Update Task
    function updateTask($id, $task) {
        $task_arr = array();

        $select = $this->conn->prepare("SELECT * FROM tasks WHERE id='$id'");
        $select->execute();

        if($select->rowCount() > 0)
        {
            $data = $this->conn->prepare("UPDATE tasks SET taskTitle=:taskTitle, taskDetails=:taskDetails, deadlineDate=:deadlineDate, deadlineTime=:deadlineTime, updatedAt=:updatedAt WHERE id=:id");

            try {
                $this->conn->beginTransaction();
                $data->execute(array(
                    'id' => $id,
                    'taskTitle' => $task['taskTitle'], 
                    'taskDetails' => $task['taskDetails'], 
                    'deadlineDate' => $task['deadlineDate'], 
                    'deadlineTime' => $task['deadlineTime'],
                    'updatedAt' => date("Y-m-d H:i:s");
                ));
                $this->conn->commit();
                
                if($data) 
                {
                    $task_arr['success'] = "Successfully updated";
                    $tasks_arr['data'] = array("id" => $this->conn->lastInsertId());
                    header("HTTP/1.1 200 OK");
                }else{
                    header("HTTP/1.1 400 ERROR");
                    $task_arr['error'] = "Unable to update task";
                }
            } catch(PDOExecption $e) {
                $this->conn->rollback();
                header("HTTP/1.1 400 ERROR");
                $task_arr['error'] = $e->getMessage();
            }
        }else{
            header("HTTP/1.1 404 ERROR");
            $task_arr['error'] = "Task does not exists";
        }
        return json_encode($task_arr);
    }

    // Update Task Status
    function updateTaskStatus($id, $status) {
        $task_arr = array();

        $select = $this->conn->prepare("SELECT * FROM tasks WHERE id='$id'");
        $select->execute();

        if($select->rowCount() > 0)
        {
            $data = $this->conn->prepare("UPDATE tasks SET status=:status, updatedAt=:updatedAt WHERE id=:id");

            try {
                $this->conn->beginTransaction();
                $data->execute(array(
                    'id' => $id,
                    'status' => $status,
                    'updatedAt' => date("Y-m-d H:i:s");
                ));
                $this->conn->commit();
                
                if($data) 
                {
                    $task_arr['success'] = "Successfully updated";
                    header("HTTP/1.1 200 OK");
                }else{
                    header("HTTP/1.1 400 ERROR");
                    $task_arr['error'] = "Unable to update task";
                }
            } catch(PDOExecption $e) {
                $this->conn->rollback();
                header("HTTP/1.1 400 ERROR");
                $task_arr['error'] = $e->getMessage();
            }
        }else{
            header("HTTP/1.1 404 ERROR");
            $task_arr['error'] = "Task does not exists";
        }
        return json_encode($task_arr);
    }

    // Delete Task
    function deleteTask($id) {
        $task_arr = array();

        $select = $this->conn->prepare("SELECT * FROM tasks WHERE id='$id'");
        $select->execute();

        if($select->rowCount() > 0)
        {
            $data = $this->conn->prepare("DELETE FROM tasks WHERE id='$id'");

            try {
                $data->execute();
                if($data) 
                {
                    $task_arr['success'] = "Successfully deleted";
                    header("HTTP/1.1 200 OK");
                }else{
                    header("HTTP/1.1 400 ERROR");
                    $task_arr['error'] = "Unable to delete task";
                }
            } catch(PDOExecption $e) {
                $this->conn->rollback();
                header("HTTP/1.1 400 ERROR");
                $task_arr['error'] = $e->getMessage();
            }
        }else{
            header("HTTP/1.1 404 ERROR");
            $task_arr['error'] = "Task does not exists";
        }
        return json_encode($task_arr);
    }
}

?>