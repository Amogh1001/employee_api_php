<?php

require __DIR__.'/classes/rest.php'; //Importing rest.php to import the class rest()

ini_set('session.gc_maxlifetime', 1800); //Setting the session lifetime to 1800 seconds or 30 minutes
session_start(); //Starting the session
$_SESSION['last_activity'] = time(); //Setting the last activity time, updated every time uses the program

if(!isset($_POST['name']) || !isset($_POST['num']) || !isset($_POST['email']) || !isset($_POST['address']) || !isset($_POST['login']) || !isset($_POST['pwd'])){ //if any of the fields are empty, it will exit the program
    echo "Incomplete data";
    exit();
}
$getName = $_POST['name'];
$getNum = $_POST['num'];
$getemail = $_POST['email'];
$getaddress = $_POST['address'];
$getlogin = $_POST['login'];
$getpwd = $_POST['pwd'];
$getdate = date("Y-m-d H:i:s");

$rest = new rest(); //Creating object of class rest()
$flag = $rest->create($getName, $getNum, $getemail, $getaddress, $getlogin, $getpwd, $getdate); //Calling the create() function
if($flag == 1){
    echo "Successfully created"; //If the record is created successfully, it will echo this message
} else {
    echo "Error: "; //If the record is not created successfully, it will echo this message
}

if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > ini_get('session.gc_maxlifetime'))) { //If the session is inactive for more than 30 minutes, it will destroy the session
    session_unset();
    session_destroy();
}

?>