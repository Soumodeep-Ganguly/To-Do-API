<?php 

class Connect extends PDO
{
    public function __construct()
    {
        parent::__construct("mysql:host=localhost;dbname=faceback",'root','');
    }
}

?>