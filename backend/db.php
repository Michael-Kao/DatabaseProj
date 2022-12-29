<?php
    $user = 'root'; 
    $password = 'youwaiting'; 
    try {
        $db = new
        PDO('mysql:host=localhost;dbname=chatroom;charset=utf8', $user, $password);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    } catch (PDOException $e) { 
        print "ERROR!: " . $e->getMessage();
        die();
    }
?>