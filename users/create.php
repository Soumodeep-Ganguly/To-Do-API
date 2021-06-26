<?php

require_once('./../users.php');

$user = new Users;

// Takes raw data from the request
$json = file_get_contents('php://input');

// Converts it into a PHP object
$data = json_decode($json);

$userName;
$email;
$password;

if($json)
{
    $userName = $data->userName;
    $email = $data->email;
    $password = $data->password;
}else{
    $userName = $_POST['userName'];
    $email = $_POST['email'];
    $password = $_POST['password'];
}

echo $user->signUpUser($userName, $email, $password);

?>