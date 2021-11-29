<?php
    include "src/class/users.php";

    $host = "192.168.65.93";
    $dbname = "carter";
    $login = "Root";
    $mdp = "Root";

    $bdd = new PDO('mysql:host='.$host.';dbname='.$dbname.';charset=utf8', $login, $mdp);
    $user = new user($bdd);
    
    
?>