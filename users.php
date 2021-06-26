<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

require_once('config.php');

class Users {
    private $conn;

    public function __construct(){
        $this->conn = new Connect;
    }

    // Get All Users
    function getUsers() {
        $users_arr = array();
        
        $data = $this->conn->prepare("SELECT * FROM users");
        $data->execute();

        if($data->rowCount() > 0)
        {
            $users_arr['data'] = array();
            while($row = $data->fetch(PDO::FETCH_ASSOC)) {
                extract($row);
                $user = array(
                    'id' => $id,
                    'username' => $username,
                    'email' => $email,
                    'password' => $password
                );

                array_push($users_arr["data"], $user);
            }
            header("HTTP/1.1 200 SUCCESS");
        }else{
            header("HTTP/1.1 404 ERROR");
            $users_arr['error'] = "No Record Found";
        }
        return json_encode($users_arr);
    }

    // Get User By Id
    function getUserById($id) {
        $user_arr = array();

        $data = $this->conn->prepare("SELECT * FROM users WHERE id='$id'");
        $data->execute();

        if($data->rowCount() > 0)
        {
            $users_arr['data'] = array();
            while($row = $data->fetch(PDO::FETCH_ASSOC)) {
                extract($row);
                $user = array(
                    'id' => $id,
                    'username' => $username,
                    'email' => $email,
                    'password' => $password
                );

                array_push($users_arr["data"], $user);
            }
            header("HTTP/1.1 200 SUCCESS");
        }else{
            header("HTTP/1.1 404 ERROR");
            $users_arr['error'] = "No Record Found";
        }
        return json_encode($users_arr);
    }

    // Get User By Email
    function getUserByEmail($email) {
        $user_arr = array();

        $data = $this->conn->prepare("SELECT * FROM users WHERE email='$email'");
        $data->execute();

        if($data->rowCount() > 0)
        {
            $users_arr['data'] = array();
            while($row = $data->fetch(PDO::FETCH_ASSOC)) {
                extract($row);
                $user = array(
                    'id' => $id,
                    'username' => $username,
                    'email' => $email,
                    'password' => $password
                );

                array_push($users_arr["data"], $user);
            }
            header("HTTP/1.1 200 SUCCESS");
        }else{
            header("HTTP/1.1 404 ERROR");
            $users_arr['error'] = "No Record Found";
        }
        return json_encode($users_arr);
    }

    // Sign Up New User 
    function signUpUser($userName, $email, $password) {
        $user_arr = array();

        $select = $this->conn->prepare("SELECT * FROM users WHERE email='$email'");
        $select->execute();

        if($select->rowCount() > 0)
        {
            header("HTTP/1.1 404 ERROR");
            $user_arr['error'] = "User already exists";
        }else{
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $data = $this->conn->prepare("INSERT INTO users(username, email, password) VALUES(:userName,:email,:password)");

            try {
                $this->conn->beginTransaction();
                $data->execute(array(
                    'userName' => $userName,
                    'email' => $email,
                    'password' => $hashed_password
                ));
                $this->conn->commit();
                
                if($data) 
                {
                    $user_arr['success'] = "Successfully inserted";
                    $users_arr['data'] = array("id" => $this->conn->lastInsertId());
                    header("HTTP/1.1 200 OK");
                }else{
                    header("HTTP/1.1 400 ERROR");
                    $user_arr['error'] = "Unable to create user";
                }
            } catch(PDOExecption $e) {
                $this->conn->rollback();
                header("HTTP/1.1 400 ERROR");
                $user_arr['error'] = $e->getMessage();
            }
        }
        return json_encode($user_arr);
    }

    // Login User
    function userLogin($email, $pass) {
        $user_arr = array();

        $data = $this->conn->prepare("SELECT * FROM users WHERE email='$email'");
        $data->execute();

        if($data->rowCount() > 0)
        {
            $users_arr['data'] = array();
            while($row = $data->fetch(PDO::FETCH_ASSOC)) {
                extract($row);
                $user = array(
                    'id' => $id,
                    'username' => $username,
                    'email' => $email,
                    'password' => $password
                );

                array_push($users_arr["data"], $user);
            }
            if(password_verify($pass, $users_arr['data'][0]['password']))
            {
                header("HTTP/1.1 200 SUCCESS");
            }else{
                header("HTTP/1.1 404 ERROR");
                unset($users_arr['data']);
                $users_arr['error'] = "Invalid Password";
            }
        }else{
            header("HTTP/1.1 404 ERROR");
            $users_arr['error'] = "No Record Found";
        }
        return json_encode($users_arr);
    }

    // Update User Account Details
    function updateUser($id, $userName, $email, $password) {
        $user_arr = array();

        $select = $this->conn->prepare("SELECT * FROM users WHERE id='$id'");
        $select->execute();

        if($select->rowCount() > 0)
        {
            $data = $this->conn->prepare("UPDATE users SET username=:userName, email=:email, password=:password WHERE id=:id");

            try {
                $this->conn->beginTransaction();
                $data->execute(array(
                    'id' => $id,
                    'userName' => $userName,
                    'email' => $email,
                    'password' => $password
                ));
                $this->conn->commit();
                
                if($data) 
                {
                    $user_arr['success'] = "Successfully updated";
                    $users_arr['data'] = array("id" => $this->conn->lastInsertId());
                    header("HTTP/1.1 200 OK");
                }else{
                    header("HTTP/1.1 400 ERROR");
                    $user_arr['error'] = "Unable to update user";
                }
            } catch(PDOExecption $e) {
                $this->conn->rollback();
                header("HTTP/1.1 400 ERROR");
                $user_arr['error'] = $e->getMessage();
            }
        }else{
            header("HTTP/1.1 404 ERROR");
            $user_arr['error'] = "User does not exists";
        }
        return json_encode($user_arr);
    }

    // Delete User Account
    function deleteUser($id) {
        $user_arr = array();

        $select = $this->conn->prepare("SELECT * FROM users WHERE id='$id'");
        $select->execute();

        if($select->rowCount() > 0)
        {
            $data = $this->conn->prepare("DELETE FROM users WHERE id=:id");

            try {
                $this->conn->beginTransaction();
                $data->execute(array(
                    'id' => $id
                ));
                $this->conn->commit();
                
                if($data) 
                {
                    $user_arr['success'] = "Successfully deleted";
                    $users_arr['data'] = array("id" => $this->conn->lastInsertId());
                    header("HTTP/1.1 200 OK");
                }else{
                    header("HTTP/1.1 400 ERROR");
                    $user_arr['error'] = "Unable to delete user";
                }
            } catch(PDOExecption $e) {
                $this->conn->rollback();
                header("HTTP/1.1 400 ERROR");
                $user_arr['error'] = $e->getMessage();
            }
        }else{
            header("HTTP/1.1 404 ERROR");
            $user_arr['error'] = "User does not exists";
        }
        return json_encode($user_arr);
    }
}

?>