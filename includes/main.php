<?php
setlocale(LC_ALL, 'tr_TR');
setlocale(LC_ALL, 'tr_TR.UTF-8');
ob_start();
error_reporting(E_ALL);
ini_set('display_errors', 0);
/*
 * Include the libraries
 */
require_once "idiorm.php";
require_once "User.class.php";
require_once "functions.php";
/**
 * Configure Idiorm
 */



$db_host = '127.0.0.1';
$db_name = 'cekilis';
$db_user = 'root';
//$db_pass = '';
$db_pass = '';

$db_host = '10.17.103.161';
$db_name = 'cekilis';
$db_user = 'dbadmin';
//$db_pass = '';
$db_pass = '*ayd99+Digital-';

ORM::configure("mysql:host=$db_host;dbname=$db_name");
ORM::configure("username", $db_user);
ORM::configure("password", $db_pass);

// Set the database connection to UTF-8
ORM::configure('driver_options', array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'));
/**
 * Configure the session
 */
//session_name('aymob');

// Uncomment to keep people logged in for a week
// session_set_cookie_params(60 * 60 * 24 * 7);
session_start();
define("_SITEURL", "http://www.cekilis.local/");

if(!$fromEmail){
	// This is only used if you haven't filled an email address in $fromEmail
	$fromEmail = 'noreply@'.$_SERVER['SERVER_NAME'];
}