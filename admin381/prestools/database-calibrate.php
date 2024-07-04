<?php
if(!@include 'approve.php') die( "approve.php was not found!");

?><!DOCTYPE html> 
<html lang="en"><head><meta charset="utf-8">
<title>Prestashop Database calibrate</title>
<link rel="stylesheet" href="style1.css" type="text/css" />
<style>
</style>
<script src="utils8.js"></script>
<script src="sorter.js"></script>
<script>
</script>
</head><body>
<h1>Prestashop Database calibrate</h1>
This script shows the differences between two databases. The present database is shown with an "a:". The database to which the comparison runs is shown with a "c:". Missing properties as shown as "[missing]".
<br>Tables belonging to modules have been excluded - and if should be ignored when shown - as there is no check whether the modules in the shops have the same version.<p>
<?php
if(!isset($_POST["calibratedb"])) colordie("You didn't provide a database to compare with!");
$parts = explode(" (",$_POST["calibratedb"]);
$compdb = preg_replace('/[\(\)\s]/','',$parts[0]);
$prefix = preg_replace('/[\(\)\s]/','',$_POST["prefix"]);
$prefixlen = strlen($prefix);

echo "<table class='triplemain'>";
$query = "SHOW TABLES FROM ".mysqli_real_escape_string($conn, $compdb);
$res=dbquery($query);
while ($trow=mysqli_fetch_array($res))
{ $tabname = substr($trow[0],$prefixlen);
  $moduletables = array("themeconfigurator");
  if((substr($tabname,0,8) == "layered_") || in_array($tabname, $moduletables)) /* don't check module tables as they have their own versions */
	  continue;
  $line = "<tr><td>".$tabname."</td>";
  $cquery = "SHOW COLUMNS FROM `".$compdb."`.`".$trow[0]."`";
  $cres = dbquery($cquery);
  $cleanlist = array();
  while ($crow=mysqli_fetch_assoc($cres))
  { $cleanlist[$crow["Field"]] = $crow; 
  }

  $tres = dbquery('show tables like "'._DB_PREFIX_.$tabname.'"');
  if(mysqli_num_rows($tres) == 0)
  { $line .="<td colspan=2>table not in present shop</td></tr>";
	continue;
  }
  $equery = "SHOW COLUMNS FROM `"._DB_PREFIX_.$tabname."`";
  $eres = dbquery($equery);
  $elist = array();
  while ($erow=mysqli_fetch_assoc($eres))
	$elist[$erow["Field"]] = $erow;

  $first = true;
  $fielddiff = false;
  foreach($cleanlist AS $field => $props)
  { if(!isset($elist[$field]))
	{ if(!$first) 
		$line .='</tr><tr><td></td>';
	  $first = false;
	  $line .="<td colspan=2>field ".$field." not in present shop</td></tr>";
	  continue;
	}
	$tprops = $elist[$field];
	$diffs = "";
    foreach($props AS $name => $value)
	{ if(!isset($tprops[$name]) && ($props[$name]!=""))
		$diffs .= $name." a: [missing] c:".$props[$name]."<br>";
	  else if($tprops[$name] != $props[$name])
		$diffs .= $name." a:".$tprops[$name]." c:".$props[$name]."<br>";
	}
	if($diffs != "")
	{ if(!$first) 
		$line .='</tr><tr><td></td>';
	  $first = false;
	  $fielddiff = true;
	  $line .="<td>".$field."</td><td>".$diffs."</td></tr>";
	}
  }
 
  $vquery = "SHOW INDEXES FROM ".$trow[0]." FROM ".mysqli_real_escape_string($conn,$compdb);
  $vres = dbquery($vquery);
  $cindexes = array();
  $cindexrows = array();
  while ($vrow=mysqli_fetch_assoc($vres))
  { unset($vrow["Cardinality"]);
    if(!isset($cindexes[$vrow["Key_name"]]))
	  $cindexes[$vrow["Key_name"]] = array();
	$cindexes[$vrow["Key_name"]][] = $vrow["Column_name"];
	$cindexrows[$vrow["Key_name"]."-".$vrow["Column_name"]] = $vrow;
  }
  
  $yquery = "SHOW INDEXES FROM `"._DB_PREFIX_.$tabname."`";
  $yres = dbquery($yquery);
  $indexes = array();
  $indexrows = array();
  while ($yrow=mysqli_fetch_assoc($yres))
  { unset($yrow["Cardinality"]);
    if(!isset($indexes[$yrow["Key_name"]]))
	  $indexes[$yrow["Key_name"]] = array();
	$indexes[$yrow["Key_name"]][] = $yrow["Column_name"];
	$indexrows[$yrow["Key_name"]."-".$yrow["Column_name"]] = $yrow;
  }
 
  $missingindexes = array();
  foreach($cindexes AS $cindex => $cfields)
  { if(!isset($indexes[$cindex]))
	{ $indexes[$cindex] = array("[missing]");
	  $missingindexes[] = $cindex;
	}
    if(implode(",",$cfields) != implode(",",$indexes[$cindex]))
	{ if(!$first) 
		$line .='</tr><tr><td></td>';
	  $first = false;
	  $line .= '<td>Index '.$cindex.'</td><td>a:'.implode(",",$indexes[$cindex]);
	  $line .= ' c:'.implode(",",$cfields).'</td></tr>';
	}
  }

  foreach($cindexrows AS $crow => $cprops)
  { $pos = strpos($crow,"-");
    if(in_array(substr($crow,0, $pos), $missingindexes))
	  continue;
    if(!isset($indexrows[$crow]))
	  $indexrows[$crow] = array("[missing]");
    if(implode(",",$cprops) != implode(",",$indexrows[$crow]))
	{ if(!$first) 
		$line .='</tr><tr><td></td>';
	  $first = false;
	  $line .= '<td>Indexline '.$crow.'</td><td>a:'.implode(",",$indexrows[$crow]);
	  $line .= ' c:'.implode(",",$cprops).'</td></tr>';
	}
  }
  
  if($fielddiff)
	  echo $line;
}
echo "</table>";

// Array ( [Field] => id_advice [Type] => int(11) [Null] => NO [Key] => PRI [Default] => [Extra] => auto_increment ) 




