<?php

require __DIR__.'/classes/rest.php'; //Importing rest.php to import the class rest()

ini_set('session.gc_maxlifetime', 1800); //Setting the session lifetime to 1800 seconds or 30 minutes
session_start(); //Starting the session
$_SESSION['last_activity'] = time(); //Setting the last activity time, updated every time uses the program

if(!isset($_GET['login']) || !isset($_GET['pwd'])){ //if username or login are empty, it will exit the program
    echo "Incomplete request";
    exit();
}
$getlogin = $_GET['login']; 
$getpwd = $_GET['pwd'];
$rest = new rest(); //Creating object of class rest()
echo $rest->login($getlogin, $getpwd); //Calling the login() function

if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > ini_get('session.gc_maxlifetime'))) { //If the session is inactive for more than 30 minutes, it will destroy the session
    session_unset();
    session_destroy();
}


?>