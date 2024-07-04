<?php
/* See copy_shopdate_readme.txt for explanation */
/* This is an auxiliary function to copy individual tables from one shop to another. */
/* The tables should be present in the old and the new database and have the same structure. There is no test for this */
/* The main purpose this function was thought for is the fast changing of tables in the case of testing to analyse problems. */
  if(!@include 'approve.php') die( "approve.php was not found! Please use this script together with Prestools that can be downloaded for free in the Free Modules & Themes section of the Prestashop forum");
if(!include 'copy_shopdata_config.php') die( "copy_shopdata_config.php was not found!");
if(!include 'copy_shopdata_functions.inc.php') die( "copy_shopdata_functions.inc.php was not found!");

$copy_tables = array("employee","emplooyee_shop"); /* specify here the table(s) that should be copied */

/* general preparations */
set_time_limit(1200); /* Set a long but not endless time limit */
echo date("H:i:s", time())."<br>";
if((_OLD_SERVER_ != _DB_SERVER_) || (_OLD_USER_ != _DB_USER_) || (_OLD_PASSWD_ != _DB_PASSWD_) || (_OLD_NAME_ != _DB_NAME_))
{ $oldconn = mysqli_connect(_OLD_SERVER_, _OLD_USER_, _OLD_PASSWD_) or die ("Could not connect to old database server!!!");
  mysqli_select_db($oldconn, _OLD_NAME_) or die ("Error selecting database");
  $query = "SET NAMES 'utf8'";
  $result = mysqli_query($oldconn, $query);
}
else 
  $oldconn = $conn;

  create_langtransform_table();
  
foreach($copy_tables AS $table)
{ echo "<br>".$table." "; 
  copy_table($table);
}
?> 
