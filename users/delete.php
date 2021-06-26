<?php

require_once('./../users.php');

$user = new Users;
echo $user->deleteUser($_REQUEST['id']);

?>