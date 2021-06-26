<?php

require_once('./../users.php');

$user = new Users;
if($_REQUEST)
{
    if(@$_REQUEST['id'])
    {
        echo $user->getUserById($_REQUEST['id']);
    }else if(@$_REQUEST['email']){
        echo $user->getUserByEmail($_REQUEST['email']);
    }else{
        echo "Invalid User Details";
    }
}else{
    echo $user->getUsers();
}

?>