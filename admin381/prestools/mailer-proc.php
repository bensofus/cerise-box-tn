<?php 
if(!@include 'approve.php') die( "approve.php was not found!");
$mode = "background";

//print_r($_POST);

	$query="select value, l.name from ". _DB_PREFIX_."configuration f, ". _DB_PREFIX_."lang l";
	$query .= " WHERE f.name='PS_LANG_DEFAULT' AND f.value=l.id_lang";
	$res=dbquery($query);
	$row = mysqli_fetch_array($res);
	$id_lang = $row['value'];

 /* Get the arguments */
if(isset($_POST['task']))
  $input = $_POST;
else 
  $input = $_GET;

if(!isset($input['task']))
  colordie("No task provided");
$task = $input["task"];

if($task != "add_list")
{ if(!isset($input['id_list']))
  { echo "No list provided!"; return;}
  $id_list = $input['id_list'];
}

if(isset($_SERVER['HTTP_REFERER']))
  $refscript = $_SERVER['HTTP_REFERER'];
else
  $refscript = "mailer.php";

if($task == "add_list")
{ if(!isset($input['list_name']))
  { echo "No listname provided!"; return;}
  else
  { $query="INSERT INTO ". _DB_PREFIX_._PRESTOOLS_PREFIX_."mail_list SET list_name='".mysqli_real_escape_string($conn, $input["list_name"])."'";
	$query .= ", list_comment='".mysqli_real_escape_string($conn, $input["list_comment"])."'";
	$res=dbquery($query);	  
  }
}

else if($task == "delete_list")
{ $query="DELETE FROM ". _DB_PREFIX_._PRESTOOLS_PREFIX_."mail_list WHERE id_list=".intval($input['id_list']);
  $res=dbquery($query);
  $query="DELETE FROM ". _DB_PREFIX_._PRESTOOLS_PREFIX_."mail_list_entry WHERE id_list=".intval($input['id_list']);
  $res=dbquery($query);
}

else if($task == "rename_list")
{ $query="UPDATE ". _DB_PREFIX_._PRESTOOLS_PREFIX_."mail_list SET list_name='".mysqli_real_escape_string($conn, $input["list_name"])."'";
  $query .= ", list_comment='".mysqli_real_escape_string($conn, $input["list_comment"])."'";
  $query .= " WHERE id_list=".intval($input['id_list']);
  $res=dbquery($query);	  
}

else if($task == "add_addresses_newsletter")
{   $query="SELECT id_customer, firstname,lastname,g.name AS gender,email,newsletter,birthday,date_add, s.name AS shopname";
    $query .= " FROM ". _DB_PREFIX_."customer c";
    $query .= " LEFT JOIN ". _DB_PREFIX_."gender_lang g ON c.id_gender=g.id_gender AND g.id_lang=".$id_lang;
	$query .= " LEFT JOIN ". _DB_PREFIX_."shop s ON c.id_shop=s.id_shop";
	$query .= " WHERE c.date_add < '2017-10-01' AND newsletter=1";
	$res=dbquery($query);	  
	while ($row=mysqli_fetch_array($res)) 	
	{ $equery="INSERT IGNORE INTO ". _DB_PREFIX_._PRESTOOLS_PREFIX_."mail_list_entry SET id_list=".intval($input['id_list']);
	  $equery .= ", email='".$row["email"]."'";
	  $eres=dbquery($equery);	
	  $aquery="INSERT INTO ". _DB_PREFIX_._PRESTOOLS_PREFIX_."mail_address SET ";
	  $aquery .= " email='".$row["email"]."'";
	  $aquery .= ", firstname='".mysqli_real_escape_string($conn, $row["firstname"])."'";
	  $aquery .= ", lastname='".mysqli_real_escape_string($conn, $row["lastname"])."'";
	  $aquery .= ", birthday='".$row["birthday"]."'";
	  $aquery .= ", id_customer='".$row["id_customer"]."'";
	  $aquery .= ", gender='".$row["gender"]."'";
	  $aquery .= ", shopname='".$row["shopname"]."'";
	  $aquery .= ", newsletter='".$row["newsletter"]."'";	
	  $aquery .= ", date_add='".$row["date_add"]."'";
	  $aquery .= " ON DUPLICATE KEY UPDATE ";
	  $aquery .= " firstname='".mysqli_real_escape_string($conn, $row["firstname"])."'";
	  $aquery .= ", lastname='".mysqli_real_escape_string($conn, $row["lastname"])."'";
	  $aquery .= ", birthday='".$row["birthday"]."'";
	  $aquery .= ", id_customer='".$row["id_customer"]."'";
	  $aquery .= ", gender='".$row["gender"]."'";
	  $aquery .= ", shopname='".$row["shopname"]."'";
	  $aquery .= ", newsletter='".$row["newsletter"]."'";	
	  $aquery .= ", date_add='".$row["date_add"]."'";
	  $ares=dbquery($aquery);	
	}
}
else if ($task == "add_addresses_customers")
{   if(!isset($input["prodsbought"]) || !isset($input["startdate"]) || !isset($input["enddate"]))
    { echo "Add_addresses_customers had not all arguments!"; return;}
    $query="SELECT c.id_customer, firstname,lastname,g.name AS gender,email,newsletter,birthday,c.date_add, s.name AS shopname";
	$query .= " FROM ". _DB_PREFIX_."customer c";
	$query .= " LEFT JOIN ". _DB_PREFIX_."gender_lang g ON c.id_gender=g.id_gender AND g.id_lang=".$id_lang;
	$query .= " LEFT JOIN ". _DB_PREFIX_."shop s ON c.id_shop=s.id_shop";
	$query .= " LEFT JOIN ". _DB_PREFIX_."orders o ON c.id_customer=o.id_customer";
	if(trim($input["prodsbought"]) != "")
	{ $query .= " LEFT JOIN ". _DB_PREFIX_."order_detail od ON o.id_order = od.id_order";
	  if (strpos($input["prodsbought"], 'c') !== false)
		 $query .= " LEFT JOIN ". _DB_PREFIX_."category_product cg ON od.product_id = cg.id_product";
	}
	$query .= " WHERE o.valid=1";
	if(trim($input["startdate"]) != "")
		$query .= " AND o.invoice_date > '".$input["startdate"]."'";
	if(trim($input["enddate"]) != "")
		$query .= " AND o.invoice_date < '".$input["enddate"]."'";
	if(trim($input["prodsbought"]) != "")
	{ $frags = explode(",", str_replace(' ', '', $input["prodsbought"]));
	  $prods = $cats = array();
	  foreach($frags AS $frag)
	  { if(substr($frag,0,1) == "c")
		  $cats[] = intval(substr($frag,1));
		else
		  $prods = intval($frag);
	  }
	  $query .= " AND (";
	  if(sizeof($prods)>0)
	  { $query .= " od.product_id IN (".implode(",",$prods).")";
		if(sizeof($cats)>0)
		  $query .= " OR";
	  }
	  if(sizeof($cats)>0)		
		$query .= " cg.id_category IN (".implode(",",$cats).")";
	  $query .= ")";
	}
	$query .= " GROUP BY o.id_customer";
	$res=dbquery($query);	  
	while ($row=mysqli_fetch_array($res)) 	
	{ $equery="INSERT IGNORE INTO ". _DB_PREFIX_._PRESTOOLS_PREFIX_."mail_list_entry SET id_list=".intval($input['id_list']);
	  $equery .= ", email='".$row["email"]."'";
	  $eres=dbquery($equery);	
	  $aquery="INSERT INTO ". _DB_PREFIX_._PRESTOOLS_PREFIX_."mail_address SET ";
	  $aquery .= " email='".$row["email"]."'";
	  $aquery .= ", firstname='".mysqli_real_escape_string($conn, $row["firstname"])."'";
	  $aquery .= ", lastname='".mysqli_real_escape_string($conn, $row["lastname"])."'";
	  $aquery .= ", birthday='".$row["birthday"]."'";
	  $aquery .= ", id_customer='".$row["id_customer"]."'";
	  $aquery .= ", gender='".$row["gender"]."'";
	  $aquery .= ", shopname='".$row["shopname"]."'";
	  $aquery .= ", newsletter='".$row["newsletter"]."'";	
	  $aquery .= ", date_add='".$row["date_add"]."'";
	  $aquery .= " ON DUPLICATE KEY UPDATE ";
	  $aquery .= " firstname='".mysqli_real_escape_string($conn, $row["firstname"])."'";
	  $aquery .= ", lastname='".mysqli_real_escape_string($conn, $row["lastname"])."'";
	  $aquery .= ", birthday='".$row["birthday"]."'";
	  $aquery .= ", id_customer='".$row["id_customer"]."'";
	  $aquery .= ", gender='".$row["gender"]."'";
	  $aquery .= ", shopname='".$row["shopname"]."'";
	  $aquery .= ", newsletter='".$row["newsletter"]."'";	
	  $aquery .= ", date_add='".$row["date_add"]."'";
	  $ares=dbquery($aquery);	
	}
}
else if($task == "add_addresses_csv")
{ $uploadOk = 1;
  $FileType = pathinfo($_FILES["fileToUpload"]["name"],PATHINFO_EXTENSION);

  if(isset($_POST["submit"])) 
	 echo "Uploading ".$_FILES["fileToUpload"]["name"]."<br>";
  else
     colordie("Error: No file provided");

  if ($_FILES["fileToUpload"]["size"] > 500000) // Check file size
	  colordie("Sorry, your file is too large.");
  if($FileType != "csv") 	
      colordie("Sorry, only CSV files are allowed.".$FileType);
  if ($uploadOk == 0) 
     colordie("Sorry, your file was not uploaded.");

  ini_set('auto_detect_line_endings',TRUE);
  if(!($fp = fopen($_FILES["fileToUpload"]["tmp_name"], "r")))
	 colordie("Error opening csv file");
  $header = fgetcsv($fp, 10000, $separator);
  if($header[0] != "id") 	
      colordie('The first column must contain the "id" column.');
  $csvblock = array();
  while (($row = fgetcsv($fp, 10000, $separator)) !== FALSE)
  {  $csvblock[] = array_combine_special($header, $row);
  }
  fclose($fp);
  ini_set('auto_detect_line_endings',FALSE);
  print_r($csvblock);
  
  $atgroup = (int)$_POST["attribute_group"];
  if($atgroup == 0) colordie("No attribute group");
  
  $query = "SELECT is_color_group FROM ". _DB_PREFIX_."attribute_group";
  $query .= " WHERE id_attribute_group=".$atgroup;
  $res = dbquery($query);
  $row=mysqli_fetch_assoc($res);
  $is_color_group = $row["is_color_group"];
  echo "colorgroup=".$is_color_group."<br>";
  
  $attributes = array();
  $query = "SELECT id_attribute, color,position FROM ". _DB_PREFIX_."attribute";
  $query .= " WHERE id_attribute_group=".$atgroup." ORDER BY position";
  $res = dbquery($query);
  $poscount = $attcount = mysqli_num_rows($res); /* used for positon with inserts */
  while ($row=mysqli_fetch_assoc($res)) 
  { $lquery = "SELECT id_lang, name FROM ". _DB_PREFIX_."attribute_lang";
    $lquery .= " WHERE id_attribute=".$row["id_attribute"]." ORDER BY id_lang";
    $lres = dbquery($lquery);
    while ($lrow=mysqli_fetch_assoc($lres))
	  $row["name-".$lrow['id_lang']] = $lrow['name'];
    if(!isset($row["name-".$default_lang])) colordie("Default name was not defined for lang ".$default_lang."-".print_r($row));
    if($row["name-".$default_lang] != "")
      $name = $row["name-".$default_lang];
    else
      $name = "id--".$row["id_attribute"];		
    $attributes[$name] = $row;
  }

  $shops = array();
  foreach($_POST["id_shop"] AS $shoppost)
    $shops[] = $shoppost;
  if(sizeof($shops)==0)
	  colordie("No shops");
  
  foreach($csvblock AS $line)
  {   $id_attribute = 0;
	  if(isset($attributes[$line["name"]]))
		$id_attribute = $attributes[$line["name"]]["id_attribute"];
	  else if((isset($line["id_attribute"])) && isset($attributes["ID--".$line["id_attribute"]]))
		$id_attribute = $line["id_attribute"];
	  if($id_attribute != 0)  /* if existing entry */
	  { if((isset($line["color"])) || (isset($line["position"])))
		{ $uquery = "UPDATE "._DB_PREFIX_."attribute SET ";
		  if(isset($line["color"])) $uquery .= " color='".mysqli_real_escape_string($conn, $line["color"])."'";
		  if((isset($line["color"])) && (isset($line["position"]))) $uquery .= ",";
		  if(isset($line["position"])) $uquery .= " position='".mysqli_real_escape_string($conn, $line["position"])."'";
		  $uquery .= " WHERE id_attribute=".$id_attribute;
		  $ures = dbquery($uquery);
		}
		foreach($languages AS $lang)
		{ $query = "SELECT * FROM ". _DB_PREFIX_."attribute_lang";
		  $query .= " WHERE id_attribute=".$id_attribute." AND id_lang=".$lang;
		  $res = dbquery($query);
		  if(mysqli_num_rows($res) > 0)
		  { if(isset($line["name-".$lang]) && ($line["name-".$lang] != ""))
			{ $uquery = "UPDATE ". _DB_PREFIX_."attribute_lang";
			  $uquery .= " SET name='".mysqli_real_escape_string($conn, $line["name-".$lang])."'";
			  $uquery .= " WHERE id_attribute=".$id_attribute." AND id_lang=".$lang;
			  $ures = dbquery($uquery);	
			}
		  }
		  else /* shouldn't happen */
		  { $iquery = "INSERT IntO ". _DB_PREFIX_."attribute_lang SET id_lang=".$lang;
			$iquery .= ", id_attribute=".$id_attribute.", name=";
			if(isset($line["name-".$lang]) && ($line["name-".$lang]!="")) $iquery .= "'".mysqli_real_escape_string($conn, $line["name-".$lang])."'";
		    else $iquery .= "'".mysqli_real_escape_string($conn, $line["name"])."'";
		    $ires = dbquery($iquery);		  
		  }
		}
	  }
	  else /* new attribute */
	  { 
	    $iquery = "INSERT INTO ". _DB_PREFIX_."attribute SET color=";
		if(isset($line["color"]) && ($line["color"] != "")) $iquery .= "'".mysqli_real_escape_string($conn, $line["color"])."'";
		else if($is_color_group) $iquery .= "'#000000'";
		else $iquery .= "''";
		$iquery .= ", id_attribute_group=".$atgroup.", position=";
		if(isset($line["position"]) && ($line["position"]!="")) $iquery .= "'".mysqli_real_escape_string($conn, $line["position"])."'";
		else $iquery .= "'".$poscount++."'";
		$ires = dbquery($iquery);
		$newid = mysqli_insert_id($conn);
		
		foreach($languages AS $lang)
		{ $iquery = "INSERT INTO ". _DB_PREFIX_."attribute_lang SET id_lang=".$lang;
		  $iquery .= ", id_attribute=".$newid.", name=";
	 	  if(isset($line["name-".$lang]) && ($line["name-".$lang]!="")) $iquery .= "'".mysqli_real_escape_string($conn, $line["name-".$lang])."'";
		  else $iquery .= "'".mysqli_real_escape_string($conn, $line["name"])."'";
		  $ires = dbquery($iquery);		  
		}
		foreach($shops AS $shop)
		{ $iquery = "INSERT INTO ". _DB_PREFIX_."attribute_shop SET id_shop=".$shop;
		  $iquery .= ", id_attribute=".$newid;
		  $ires = dbquery($iquery);		  
		}
	  }
  }
  $query = "SELECT * FROM ". _DB_PREFIX_."attribute WHERE id_attribute_group=".$atgroup;
  $query .= " ORDER BY position,id_attribute";
  $res = dbquery($query);
  $x=0;
  while ($row=mysqli_fetch_assoc($res)) 
  { if($row["position"] != $x)
	{ $uquery = "UPDATE ". _DB_PREFIX_."attribute";
  	  $uquery .= " SET position='".$x."'";
	  $uquery .= " WHERE id_attribute=".$row["id_attribute"];
   	  $ures = dbquery($uquery);	
	}
	$x++;
  }
}
else if($task == "add_addresses_window")
{ $query = "SELECT name FROM ". _DB_PREFIX_."shop WHERE active=1 LIMIT 1";
  $res=dbquery($query);
  $row=mysqli_fetch_array($res);
  $shopname = $row["name"];
  
  $lines = $input["addressadder"];
  foreach($lines AS $line)
  { $line = trim($line);
    $frags = explode(";",$line);
	if($frags[0] == "") /* addition */
	{ $query = 'SELECT * FROM '._DB_PREFIX_._PRESTOOLS_PREFIX_.'mail_address';
	  $query .= ' WHERE email="'.mescape($frags[1]).'"';
	  $res = dbquery($query);	
	  $row = mysqli_fetch_assoc($res);
	  if(!$row)
	  { if((sizeof($frags) < 4) || (strpos($frags[1],'@') <=0) || (trim($frags[2])=="") || (trim($frags[3])==""))
		  die("With insert you must provide at least a valid email address and first and last name.");
		$iquery = 'INSERT INTO '._DB_PREFIX_._PRESTOOLS_PREFIX_.'mail_address SET';
		$iquery .= ' email="'.mescape($frags[1]).'", firstname="'.mescape($frags[2]).'"';
		$iquery .= ', lastname="'.mescape($frags[3]).'", date_add=now()';
		if(isset($frags[4])) $iquery .= ', id_customer="'.intval($frags[4]).'"';
		if(isset($frags[5])) $iquery .= ', shopname="'.mescape($frags[5]).'"';
		else $iquery .= ', shopname="'.$shopname.'"';
		if(isset($frags[6])) $iquery .= ', newsletter="'.mescape($frags[6]).'"';
		if(isset($frags[7])) $iquery .= ', birthday="'.mescape($frags[7]).'"';
	    $ires = dbquery($iquery);	
	  }
	  else
		  $frags[0] = $row["id_mail"]; /* fall through to modify */
	}
	if($frags[0] != "") 
	{ if(!strcasecmp($flags[1], "delete"))
	  { $dquery = 'DELETE '._DB_PREFIX_._PRESTOOLS_PREFIX_.'mail_address WHERE id_mail='.intval($frags[0]);
	    $dres = dbquery($dquery);
	    continue;	
	  }
	  /* now modify */
	  $mquery = 'UPDATE '._DB_PREFIX_._PRESTOOLS_PREFIX_.'mail_address SET true';
	  if(isset($frags[1])	&& (strpos($frags[1],'@') >0)) $mquery .= ', email="'.mescape($frags[1]).'"';
	  if(isset($frags[2])	&& ($frags[2]!="")) $mquery .= ', firstname="'.mescape($frags[2]).'"';
	  if(isset($frags[3])	&& ($frags[3]!="")) $mquery .= ', lastname="'.mescape($frags[3]).'"';
	  if(isset($frags[4])	&& ($frags[4]!="")) $mquery .= ', gender="'.mescape($frags[4]).'"';
	  if(isset($frags[5])	&& ($frags[5]!="")) $mquery .= ', id_customer="'.intval($frags[5]).'"';
	  if(isset($frags[6])	&& ($frags[6]!="")) $mquery .= ', shopname="'.mescape($frags[6]).'"';
	  if(isset($frags[7])	&& ($frags[7]!="")) $mquery .= ', newsletter="'.mescape($frags[7]).'"';
	  if(isset($frags[8])	&& ($frags[8]!="")) $mquery .= ', birthday="'.mescape($frags[8]).'"';
	  $mquery .= ' WHERE id_mail='.intval($frags[0]);
	  $mres = dbquery($mquery);
	}
  }
}
else if($task == "empty_list")
{ if(!isset($input['id_list']))
  { echo "No list provided!"; return;}
  else
  { $query="DELETE FROM ". _DB_PREFIX_._PRESTOOLS_PREFIX_."mail_list_entry WHERE id_list=".intval($input['id_list']);
	$res=dbquery($query);
  }
}

else if($task == "export_list")
{ if(!isset($input['id_list']))
  { echo "No list provided!"; return;
  }
  $query = "SELECT name FROM ". _DB_PREFIX_."shop WHERE active=1 LIMIT 1";
  $res=dbquery($query);
  $row=mysqli_fetch_array($res);
  $shopname = $row["name"];
  $fields = array("id","shop_name","gender","lastname","firstname","email","subscribed","subscribed_on");
  if(isset($input['separator']) &&($input['separator'] == "comma"))
  { $separator = ",";
	$subseparator = ";";
  }
  else 
  { $separator = ";";
	$subseparator = ",";
  }
    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename=mailer-'.date('Y-m-d-Gis').'.csv');
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
	$out = fopen('php://output', 'w');
    publish_csv_line($out, $fields, $separator);
	
	$query = "SELECT * FROM ". _DB_PREFIX_._PRESTOOLS_PREFIX_."mail_list_entry le";
	$query .= " LEFT JOIN ". _DB_PREFIX_._PRESTOOLS_PREFIX_."mail_address a ON a.email=le.email";	
	$query .= " WHERE id_list=".intval($input['id_list']);
	$res=dbquery($query);	  
	while ($row=mysqli_fetch_array($res))
	{ $csvline = array();
	  if(in_array("id", $fields))
		  $csvline[] = $row["id_customer"];
	  if(in_array("shop_name", $fields))
		  $csvline[] = $row["shopname"];
	  if(in_array("gender", $fields))
		  $csvline[] = $row["gender"];
	  if(in_array("lastname", $fields))
		  $csvline[] = $row["lastname"];
	  if(in_array("firstname", $fields))
		  $csvline[] = $row["firstname"];
	  if(in_array("email", $fields))
		  $csvline[] = $row["email"];
	  if(in_array("subscribed", $fields))
		  $csvline[] = $row["newsletter"];
	  if(in_array("subscribed_on", $fields))
		  $csvline[] = $row["date_add"];	
	  publish_csv_line($out, $csvline, $separator);	  
	}
	fclose($out);
}




echo "<br>Finished successfully!";
//if(($task == "add_list") || ($task == "delete_list") || ($task == "rename_list"))
if(true)
{ if(!isset($_POST['action'])) /* if submit all */
    echo "<p>Go back to <a href='".$refscript."'>Mailer page</a></body></html>";
  if($verbose!="true")
    echo "<script>location.href = '".$refscript."';</script>";
}
    
function publish_csv_line($out, $csvline, $separator)
{ fputcsv($out, $csvline, $separator);
}
?>
