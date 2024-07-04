<?php

if(!@include 'approve.php') die( "approve.php was not found!");

?><!DOCTYPE html> 
<html lang="en"><head><meta charset="utf-8">
<title>Prestashop Shops overview</title>
<style>
</style>
<script type="text/javascript" src="utils8.js"></script>
<script type="text/javascript" src="sorter.js"></script>
<link rel="stylesheet" href="style1.css" type="text/css" />
</head><body>
<?php print_menubar(); ?>
<table class="triplehome" cellpadding="0" cellspacing="0"><tr><td width="80%">
<a href="shoplist.php" style="text-decoration:none; font-size:160%"><b><center>Prestashop Shops Overview</center></b></a>
<center>This page lists all the Prestashop webshops in your database with some basic information.
<br>For each shop you see the following information: id_shop active name domain/path languages products/active-products - valid orders
</center>
</td><td><iframe name=tank width=230 height=93></iframe></td></tr></table>
<?php
$squery = "SELECT TABLE_SCHEMA,TABLE_NAME FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_NAME LIKE '%configuration_lang'";
$sres = dbquery($squery); 
echo "<table class='triplemain'><tr><td>database</td><td>shops</td><td>b.o.lang</td><td>ASM</td></tr>";
while ($srow=mysqli_fetch_array($sres))   
{ $prefix = str_replace("configuration_lang","",$srow["TABLE_NAME"]);

  $query = 'SELECT TABLE_NAME FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA="'.$srow["TABLE_SCHEMA"].'" AND TABLE_NAME="'.$prefix.'shop"';
  $res = dbquery($query); 
  if(mysqli_num_rows($res) == 0) 
	  echo '<tr><td>'.$srow["TABLE_SCHEMA"].'</td><td>X</td>'; /* unfortunately I have an installation where ps_shop is missing */
  else
  {
    $query = 'SELECT s.id_shop, name, s.active, domain, physical_uri';
    $query .= ' FROM '.$srow["TABLE_SCHEMA"].'.'.$prefix."shop s";
    $query .= ' LEFT JOIN '.$srow["TABLE_SCHEMA"].'.'.$prefix."shop_url su ON s.id_shop=su.id_shop";
    $query .= ' ORDER BY s.id_shop';
    $res = dbquery($query); 
    echo '<tr><td>'.$srow["TABLE_SCHEMA"].'</td><td><table>';
    while ($row=mysqli_fetch_array($res)) 
    { $langquery = 'SELECT GROUP_CONCAT(iso_code) AS langs FROM '.$srow["TABLE_SCHEMA"].'.'.$prefix."lang l";
      $langquery .= ' LEFT JOIN '.$srow["TABLE_SCHEMA"].'.'.$prefix.'lang_shop ls ON l.id_lang=ls.id_lang AND ls.id_shop='.$row["id_shop"];
      $langres = dbquery($langquery); 
      $langrow=mysqli_fetch_array($langres);
	  
	  echo '<tr><td>'.$row['id_shop'].' '.$row['active'].' '.$row['name'].'</td><td>'.$row['domain'].$row['physical_uri'].'</td>';
	  echo '<td>'.$langrow["langs"].'</td>';
	  
	  $pquery = 'SELECT COUNT(*) AS prodcount FROM '.$srow["TABLE_SCHEMA"].'.'.$prefix."product_shop";
      $pquery .= ' WHERE id_shop='.$row['id_shop'];
      $pres = dbquery($pquery); 
      $prow=mysqli_fetch_array($pres);
	  
	  $aquery = 'SELECT COUNT(*) AS activecount FROM '.$srow["TABLE_SCHEMA"].'.'.$prefix."product_shop";
      $aquery .= ' WHERE id_shop='.$row['id_shop'].' AND active=1';
      $ares = dbquery($aquery); 
      $arow=mysqli_fetch_array($ares);
	  
	  $oquery = 'SELECT COUNT(*) AS ordercount FROM '.$srow["TABLE_SCHEMA"].'.'.$prefix."orders";
      $oquery .= ' WHERE id_shop='.$row['id_shop'].' AND valid=1';
      $ores = dbquery($oquery); 
      $orow=mysqli_fetch_array($ores);
	  echo '<td>'.$prow["prodcount"].'/'.$arow["activecount"].' - '.$orow["ordercount"].'</td>';
	  echo '</tr>';
    }
    echo '</table></td>';
  }
  
  $boquery = 'SELECT GROUP_CONCAT(DISTINCT iso_code) AS langs FROM '.$srow["TABLE_SCHEMA"].'.'.$prefix.'employee e';
  $boquery .= ' LEFT JOIN '.$srow["TABLE_SCHEMA"].'.'.$prefix.'lang l ON e.id_lang=l.id_lang';  
  $bores = dbquery($boquery); 
  $borow=mysqli_fetch_array($bores);
  echo '<td>'.$borow["langs"].'</td>';
  
  $query = 'SELECT value FROM '.$srow["TABLE_SCHEMA"].'.'.$prefix."configuration".' WHERE name="PS_ADVANCED_STOCK_MANAGEMENT"';
  $res = dbquery($query); 
  $row=mysqli_fetch_array($res);
  echo '<td>'.$row["value"].'</td></tr>';

}
echo "</table>";
echo '<p>';
  include "footer1.php";
echo '</body></html>';
