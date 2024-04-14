<?php

require __DIR__.'/classes/rest.php'; //Importing rest.php to import the class rest()

ini_set('session.gc_maxlifetime', 1800); //Setting the session lifetime to 1800 seconds or 30 minutes
session_start(); //Starting the session
$_SESSION['last_activity'] = time(); //Setting the last activity time, updated every time uses the program

if ($_SERVER['REQUEST_METHOD'] === 'DELETE') { //Checking if the request method is DELETE
    if (!isset($_SESSION['token'])) { //Checking if the user is logged in, if they are not, it will exit the program
        echo("You are not logged in.");
    } else {
        if (isset($_GET['id'])) {
            $getid = $_GET['id'];
            $rest = new rest();
            $user = $rest->read($_SESSION['token']); //Calling the read() function using the session token
            $user = json_decode($user, true);
            if ($user[0]["id"]!=$_GET["id"]){ //Checking if the user is authorized to delete the user
                echo("You are not authorized to delete this user.");
                session_unset();
                session_destroy();
                exit();
            }
            $stat = $rest->delete($getid);
            if ($stat == 1) { //If the record is deleted successfully, it will echo this message and destroy the session
                echo("Deleted successfully.");
                session_unset();
                session_destroy();
            } else {
                echo ("Error: ");
            }
        } else { //If the user does not enter an ID, it will exit the program
            echo("ID not found");
            session_unset();
            session_destroy();
        }
    }
}
?>