<?php

require_once('./../users.php');

$user = new Users;

// Takes raw data from the request
$json = file_get_contents('php://input');

// Converts it into a PHP object
$data = json_decode($json);

$email;
$password;

if($json)
{
    $email = $data->email;
    $password = $data->password;
}else{
    $email = $_POST['email'];
    $password = $_POST['password'];
}

echo $user->userLogin($email, $password);

?>