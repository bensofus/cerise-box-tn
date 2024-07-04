<?php
if(!@include 'approve.php') die( "approve.php was not found!");
echo '<!DOCTYPE html> 
<html lang="en"><head><meta charset="utf-8">
<script>
function newwin()
{ nwin = window.open("","_blank", "scrollbars,menubar,toolbar, status,resizable,location");
  content = document.body.innerHTML;
  if(nwin != null)
  { nwin.document.write("<html><head><meta http-equiv=\'Content-Type\' content=\'text/html; charset=utf-8\' /></head><body>"+content+"</body></html>");
    nwin.document.close();
  }
}
</script></head><body>';
echo '<a href="#" title="Show the content of this frame in a New Window" onclick="newwin(); return false;">NW</a> ';
$task = $_GET["task"];
$returnfunc = $_GET["returnfunc"];
$errors = "";
if($task == "blacklist_csv_add")
{ $blacklist_text = $_GET["blacklist_text"];
  $blacklist_rows = preg_split("/\r\n|\n|\r/", $blacklist_text);
  foreach($blacklist_rows AS $blacklist_row)
  { $fields = explode(";",$blacklist_row);
    if(sizeof($fields)>5)
	{ for ($i=5; $i<sizeof($fields); $i++)
		$fields[4] .= ";".$fields[$i];
	}
	if($fields[0]=="") continue; /* ignore empty lines */
	if (!filter_var($fields[0], FILTER_VALIDATE_EMAIL)) 
	{ $errors .= $fields[0]." is not a valid email\\n";
	  continue;
	}
	$query = "SELECT email FROM `"._DB_PREFIX_._PRESTOOLS_PREFIX_.'mail_blacklist` WHERE email="'.$fields[0].'"';
	$res=dbquery($query);
	$new = $valid = false;
	if(mysqli_num_rows($res) == 0)
	{ $iquery = "INSERT IGNORE into `"._DB_PREFIX_._PRESTOOLS_PREFIX_.'mail_blacklist` SET email="'.$fields[0].'"';
	  $ires=dbquery($iquery);
	  $new = true;
	}
	else
	{ $row = mysqli_fetch_array($res);
	}
	$updaters = "";
 	if((sizeof($fields)>1) && ($fields[1] != ""))
	  $updaters .= ', reason="'.mysqli_real_escape_string($conn,$fields[1]).'"';
 	if((sizeof($fields)>2) && ($fields[2] != ""))
	{ $parts = explode("-",$fields[2]);
	  if(checkdate($parts[1],$parts[2],$parts[0]))
	  { $updaters .= ', startdate="'.mysqli_real_escape_string($conn,$fields[2]).'"';
		$valid = true;
	  }
	  else $errors .= "Invalid Startdate!\n";
	}
	if($new && !$valid)
	  $updaters .= ', startdate="'.date("Y-m-d").'"';
 	if((sizeof($fields)>3) && ($fields[3] != ""))
	{ $parts = explode("-",$fields[3]);
	  if(checkdate($parts[1],$parts[2],$parts[0]))
	  { $updaters .= ', enddate="'.mysqli_real_escape_string($conn,$fields[3]).'"';
		$valid = true;
	  }
	  else $errors .= "Invalid Enddate!\n";
	}
	if(sizeof($fields)>4)
	  $updaters .= ', comment="'.mysqli_real_escape_string($conn,$fields[4]).'"';  
    if($updaters != "")
	{ $query = "UPDATE `"._DB_PREFIX_._PRESTOOLS_PREFIX_.'mail_blacklist` SET '.substr($updaters,1)." WHERE email='".$fields[0]."'";
  	  $res=dbquery($query);
	}	
  }
  $query = "SELECT count(*) AS blackcount FROM `"._DB_PREFIX_._PRESTOOLS_PREFIX_.'mail_blacklist`';
  $res=dbquery($query);
  list($blackcount) = mysqli_fetch_row($res); 
  $data = $blackcount;
}
  echo "<script>";
  if($errors != "")
	  echo "alert('".$errors."'); ";
  echo "parent.".$returnfunc."(".$data.");</script>";
?>

