<?php

require __DIR__.'/classes/rest.php'; //Importing rest.php to import the class rest()

ini_set('session.gc_maxlifetime', 1800); //Setting the session lifetime to 1800 seconds or 30 minutes
session_start(); //Starting the session
$_SESSION['last_activity'] = time(); //Setting the last activity time, updated every time uses the program

if(!isset($_POST['login']) || !isset($_POST['pwd'])){ //if username or login are empty, it will exit the program
    echo "Incomplete request";
    exit();
}
$getlogin = $_POST['login'];
$getpwd = $_POST['pwd'];
$rest = new rest(); //Creating object of class rest()
$rest->login($getlogin, $getpwd); //Calling the login() function, thereby creating a variable called token in the session
$token = $_SESSION["token"];
$user = $rest->read($token);
$user = json_decode($user, true);
$user[0]["password"] = $getpwd; //Changing the hashed encrypted password stored in the database to the original password for showing to the end user
if(isset($_POST['mod_name'])){ //Checking if the user has entered any data in the fields, and modifying the data accordingly
    $user[0]["name"] = $_POST['mod_name'];
}
if(isset($_POST['mod_num'])){
    $user[0]["mobile_number"] = $_POST['mod_num'];
}
if(isset($_POST['mod_email'])){
    $user[0]["email"] = $_POST['mod_email'];
}
if(isset($_POST['mod_address'])){
    $user[0]["address"] = $_POST['mod_address'];
}
if(isset($_POST['mod_login'])){
    $user[0]["login_name"] = $_POST['mod_login'];
}
if(isset($_POST['mod_pwd'])){
    $user[0]["password"] = $_POST['mod_pwd'];
}
echo $rest->update($token, $user[0]["name"], $user[0]["mobile_number"], $user[0]["email"], $user[0]["address"], $user[0]["login_name"], $user[0]["password"]); //Updates the data in the database

if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > ini_get('session.gc_maxlifetime'))) { //If the session is inactive for more than 30 minutes, it will destroy the session
    session_unset();
    session_destroy();
}

?>