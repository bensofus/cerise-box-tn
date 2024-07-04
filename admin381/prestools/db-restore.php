<?php 
if(!@include 'approve.php') die( "approve.php was not found!");
$input = $_GET;

/* get default language: we use this for the categories, manufacturers */
$query="select value from ". _DB_PREFIX_."configuration WHERE name='PS_LANG_DEFAULT'";
$res=dbquery($query);
$row = mysqli_fetch_array($res);
$id_lang = $row['value'];
?><!DOCTYPE html> 
<html lang="en"><head><meta charset="utf-8">
<title>Prestashop Database Restore</title>
<style>
.comment {background-color:#aabbcc}
</style>
<script type="text/javascript" src="utils8.js"></script>
<script type="text/javascript" src="sorter.js"></script>
<script>
function checkrestore()
{ if (restoreform.restoredb.selectedIndex == 0)
  { alert("You must select a database in which to restore!")
	return false;	
  }
  if (restoreform.restorefiles.selectedIndex == 0)
  { alert("You must select a backupped fileset to restore!")
	return false;	
  }
  restoreform.verbose.value = configform.verbose.checked;
  return true;
}

function checkimport()
{ if (importform.importdb.selectedIndex == 0)
  { alert("You must select a database in which to import!")
	return false;	
  }
  if (importform.importfile.selectedIndex == 0)
  { alert("You must select a sql file to import!")
	return false;	
  }
  importform.verbose.value = configform.verbose.checked;
  return true;
}

</script>
<link rel="stylesheet" href="style1.css" type="text/css" />
</head><body>
<?php print_menubar(); ?>
<div style="float:right; "><iframe name=tank width=230 height=93></iframe></div>
<h1>Restore database backup</h1>
This page is about importing data:<br>
- The first function imports a database backup into an empty table. Useful when your update failed 
and you find it hard to restore the database.<br>
 - The second function imports data from a sql file in your Prestools directory. Useful when a 
 normal import fails - for example because the file is too large.
<p>
<form name=configform><input type=checkbox name=verbose> verbose</form>
<hr><table class="triplemain"><tr><td style="text-align:center"><b>Retrieve backup database</b></td></tr><tr><td>
<?php
/* Note: would this be faster with "show databases" and "show tables"? */
  $res = dbquery("use information_schema");  
  $cquery = "select schema_name from `schemata` s";
  $cquery.=" left join `tables` t on s.schema_name = t.table_schema";
  $cquery.=" where t.table_name is null";
  $equery = "select table_schema, sum(data_length) Z from information_schema.tables";
  $equery .= " where table_schema not in ('information_schema','performance_schema')";
  $equery .= " group by table_schema having z=0;";
  $cres=dbquery($cquery);
  echo "This function retrieves a database backup from an upgrade into an empty database.<br>";
  echo "The unzipped file will be stored in a tmp subdirectory below your Prestools directory<br>";
  if(mysqli_num_rows($cres)==0)
	  echo "<i>You don't have empty databases available at the moment</i><br>";
  else
  { echo '<table><tr><td><form name="restoreform" action="shop-rescue-proc.php" method=post target=tank onsubmit="return checkrestore();">Select an empty database in which to restore the backup: ';
    echo '</td><td><select name="restoredb"><option>select an empty database</option>';
    while($crow = mysqli_fetch_array($cres))
		echo '<option>'.$crow["schema_name"].'</option>';
    echo '</select><input type=hidden name=verbose></td></tr>';
	echo '<tr><td>Select a backup to restore: </td><td>';
	$backuppath = "../autoupgrade/backup";
	/* now check for an arbitrary file in the admin directory */
	if(!file_exists("../ajax.php")) echo "You can only run this function from a directory below your admin!</td></tr></table></form>";
    else if(!($files = scandir($backuppath))) echo "No backup directory found</td></tr></table></form>";
    else if(sizeof($files) <= 3) echo "Backup directory is empty</td></tr></table></form>";
	else 
	{ echo '<select name="restorefiles"><option>select a backupset</option>';
	  foreach($files AS $file)
	  { if(($file == ".") || ($file=="..")) continue;
		if(!is_dir($backuppath."/".$file)) continue;
		if(substr($file,0,2) != "V1") continue;
		echo '<option>'.$file.'</option>';
	  }
	  echo '</select><input type=hidden name="subject" value="dbrestore"></td></tr>';
	  echo '<tr><td>Timeout</td><td><input name="timeout" value="1200" size=5>secs</td></tr>';
	  echo '<tr><td>Skip content of statistics tables (connections,connections_source,page_viewed,guest)?';
	  echo '</td><td><input type=checkbox name="skipstats"></td></tr>';
	  echo '<tr><td>Preserve unzipped sql files after completion?</td><td><input type=checkbox name="savesql"></td></tr></table>';
	  echo '</td></tr>';
	  echo '<tr><td style="text-align:center"><input type=submit value="Restore"></form></td></tr></table>';
	}
  }
?>
</td></tr></table>
<hr><table class="triplemain"><tr><td style="text-align:center"><b>Import SQL file</b></td></tr><tr><td>
<?php
  echo "This function imports a sql file exported by phpmyadmin or a similar program that you have uploaded to your Prestools directory.<br>";
  echo "As long as your timeout is long enough there is no limit to the size of the file.<br>";
  echo "It is your responsibility to make sure that the import doesn't clash with existing files.<br>";
  echo "This function is still experimental.<br>";
  echo '<table><tr><td><form name="importform" action="shop-rescue-proc.php" method=post target=tank onsubmit="return checkimport();">';
  echo 'Select a database in which to import: </td><td><select name="importdb"><option>Select database</option>';
    
  $dres = dbquery("SHOW DATABASES");
  while($drow = mysqli_fetch_array($dres))
  { if(in_array($drow[0],array("information_schema","mysql","performance_schema","phpmyadmin")))
	  continue;
    echo '<option>'.$drow[0].'</option>';
  }
  echo '</select><input type=hidden name=verbose></td></tr>';
  echo '<tr><td>Select a sql file to import: </td><td>';
  if(!($files = scandir('.'))) echo "No sql files found</td></tr></table>";
  else
  { echo '<select name="importfile"><option>select sql import file</option>';
    foreach($files AS $file)
    { if(($file == ".") || ($file=="..")) continue;
	  $pos = strrpos($file, ".");
	  if(substr($file,$pos+1) != "sql") continue;
	  echo '<option>'.$file.'</option>';
    }
	echo '</select><input type=hidden name="subject" value="dbimport"></td></tr>';
	echo '<tr><td>Timeout</td><td><input name="timeout" value="1200" size=5>secs</td></tr>';
	echo '<tr><td>Skip content of statistics tables (connections,connections_source,page_viewed,guest)?';
	echo '</td><td><input type=checkbox name="skipstats"></td></tr>';
	echo '<tr><td style="text-align:center"><input type=submit value="Import"></form></td></tr></table>';
  }
echo '<p>';
  include "footer1.php";
echo '</body></html>';

