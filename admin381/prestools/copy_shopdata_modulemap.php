<?php 
/* See copy_shopdate_modulemap.php compares the active modules of two shops */

  if(!@include 'approve.php') die( "approve.php was not found! Please use this script together with Prestools that can be downloaded for free in the Free Modules & Themes section of the Prestashop forum");

if(strcasecmp("/copy_shopdata.php", substr($_SERVER["PHP_SELF"],strlen($_SERVER["PHP_SELF"])-18)))
{ if(!include 'copy_shopdata_config.php') die( "copy_shopdata_config.php was not found!");
  if(!include 'copy_shopdata_functions.inc.php') die( "copy_shopdata_functions.inc.php was not found!");

  /* if you copy tables over themselves they will get emptied. So the following check is very important */
  if((_OLD_SERVER_ == _DB_SERVER_) && (_OLD_USER_ == _DB_USER_) && (_OLD_PASSWD_ == _DB_PASSWD_) && (_OLD_NAME_ == _DB_NAME_) && (_OLD_PREFIX_ == _DB_PREFIX_))
    die("You cannot copy a webshop upon itself. Did you install the script on the new shop?");
    
  if((_OLD_SERVER_ != _DB_SERVER_) || (_OLD_USER_ != _DB_USER_) || (_OLD_PASSWD_ != _DB_PASSWD_) || (_OLD_NAME_ != _DB_NAME_))
  { $oldconn = mysqli_connect(_OLD_SERVER_, _OLD_USER_, _OLD_PASSWD_) or die ("Could not connect to old database server!!!");
    mysqli_select_db($oldconn, _OLD_NAME_) or die ("Error selecting database");
    $query = "SET NAMES 'utf8'";
    $result = dbxquery($oldconn, $query);
  }
  else 
    $oldconn = $conn;
}
echo '<style>
table.triplemain, table.triplemain td {
	margin: 1px;
	padding: 4px;
    border: 2px solid #c3c3c3;
	border-collapse: collapse;
}
</style>';
  
if (_PS_VERSION_ >= "1.6.0.0")
{ $query="SELECT m.id_module,name FROM ". _OLD_PREFIX_."module m";
  $query .= " LEFT JOIN ". _OLD_PREFIX_."module_shop ms ON m.id_module=ms.id_module";
  $query .= " GROUP BY m.id_module";
  $query .= " ORDER BY name";
  $res=dbxquery($oldconn, $query);
  
  $oldmodules = array();
  while ($datarow=mysqli_fetch_array($res))
  { $oldmodules[] = $datarow["name"];
  }
  
  $query="SELECT m.id_module,name FROM ". _DB_PREFIX_."module m";
  $query .= " LEFT JOIN ". _DB_PREFIX_."module_shop ms ON m.id_module=ms.id_module";
  $query .= " GROUP BY m.id_module";
  $query .= " ORDER BY name";
  $res=dbxquery($conn, $query);
  
  $newmodules = array();
  while ($datarow=mysqli_fetch_array($res))
  { if(is_dir($triplepath.'modules/'.$datarow['name']))
	{	$newmodules[] = $datarow["name"];
    }
  }
  
  $totmodules = array_unique(array_merge($oldmodules,$newmodules));
  sort($totmodules);
  
  echo '<script>function flipchange(elt)
  { var mytab = document.getElementById("fliptable");
    var mytablen = mytab.rows.length;
    for(var i=2; i<mytablen; i++)
	{ var oldfld = mytab.rows[i].cells[3].innerHTML;
	  var newfld = mytab.rows[i].cells[4].innerHTML;
	  if(((oldfld==newfld) && (flipform.flipcheck1.checked)) || ((oldfld=="X") && (flipform.flipcheck2.checked)) || ((newfld=="X") && (flipform.flipcheck3.checked)))	
		 mytab.rows[i].style.display="none";
	  else mytab.rows[i].style.display="table-row";
	}
  }
  </script>';
  echo '<form name=flipform>
  <input type=checkbox name="flipcheck1" onchange=flipchange(this)> Hide when present in both<br>
  <input type=checkbox name="flipcheck2" onchange=flipchange(this)> Hide when only present in old<br>
  <input type=checkbox name="flipcheck3" onchange=flipchange(this)> Hide when only present in new</form>';
  echo '<table id=fliptable class="triplemain"><tr><td></td><td>Name</td><td></td><td>old shop</td><td>new shop</td></tr>';
  echo '<tr><td colspan=5><i>NB: Some old modules listed as active may not be present</i></td></tr>';
  $x = 1;
  foreach($totmodules AS $totmodule)
  { echo '<tr><td>'.$x++.'</td><td>'.$totmodule.'</td><td></td><td>';
    if(in_array($totmodule,$oldmodules)) echo 'X'; else echo '-';
	echo '</td><td>';
    if(in_array($totmodule,$newmodules)) echo 'X'; else echo '-';	
	echo '</td></tr>';
  }
  echo '</table>';
}
else
	echo "<br><b>Modulemap is only supported for Prestashop 1.6 and higher</b><br>";
mysqli_close($conn);
