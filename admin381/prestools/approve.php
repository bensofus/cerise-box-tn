<?php
$embedded = "1";
$prestools_starttime = time();
include("login1.php");
include("functions1.php");

/* check values from settings1.php file */
$initwarnings = "";
if($username == "demo@demo.com") 
	$initwarnings .= "Change the username in the file \\'settings1.php\\'!\\n\\n";
if($password == "opensecret") 
	$initwarnings .= "Change the password in the file \\'settings1.php\\'!\\n\\n";
if(rand(0,3)!=2)		/* show this 1 in 3 times */
	$initwarnings = "";
if((sizeof($ipadresses)==0) && (rand(0,10)==2))
	$initwarnings .= "For your safety is recommended to set safe IP addresses in the file \\'settings1.php\\'! You can use wildcards (\\'*\\').";

