<?php 
/* See copy_shopdate_readme.txt for explanation */
  $verbose="ON";
  if(!@include 'approve.php') die( "approve.php was not found! Please use this script together with Prestools that can be downloaded for free in the Free Modules & Themes section of the Prestashop forum");
   /* get old shop version: needed in config and functions */
//  if(!include 'copy_shopdata_config.php') die( "copy_shopdata_config.php was not found!");

  /* settings */
  define('_OLD_NAME_', 'cerised29'); /* make sure you use the same prefix as the original */
  define('_OLD_PREFIX_', 'mod615_'); /* please keep the prefix in both shops the same */
  $startnum = 0; /* use this to skip tables. When you start it should be 0. */
  $numtables = 1000; /* use a high number for all */
  
  $comparefiles = true; /* check for file differences  */
  $admindir = "admin8692"; /* must be same for old and new shop */
 

  /* general preparations */
  echo date("H:i:s", time())."<br>";
  echo "<br>Beware of timeouts: After finishing the last line on the screen should be \"** finished **\". If necessary increase the timeout at the start of this file. After a timeout you can increase startnum as you don't need to copy processed tables again.<p>";
  
  /* get language transformations for ps_configuration_lang table */
  $oldlangs = array();
  $query = "SELECT * FROM "._OLD_NAME_."."._OLD_PREFIX_."lang"; 
  $res = dbquery($query);
  while($row = mysqli_fetch_assoc($res))
	$oldlangs[$row["iso_code"]] = $row["id_lang"];

  $newlangs = array();
  $query = "SELECT * FROM "._DB_NAME_."."._DB_PREFIX_."lang"; 
  $res = dbquery($query);
  while($row = mysqli_fetch_assoc($res))
	$newlangs[$row["iso_code"]] = $row["id_lang"];
  
  $langtransforms = array();
  foreach($oldlangs AS $iso => $oldid)
  { if(!isset($newlangs[$iso]))
	  colordie("Both shops must have the same languages!");
    $newid = $newlangs[$iso];
	$langtransforms[$newid] = $oldid;
  }
  
  $oldprefixlen = strlen(_OLD_PREFIX_);
  $newprefixlen = strlen(_DB_PREFIX_);
  
  $query = "SHOW TABLES FROM "._OLD_NAME_;
  $res = dbquery($query); 
  while($row = mysqli_fetch_row($res))
  { $oldtables[] = substr($row[0], $oldprefixlen);
  }
  
  if(sizeof($oldtables) < 100)
    colordie("Insufficient old tables found. Are you sure you pointed to the rights database?");
	
  $query = "SHOW TABLES FROM "._DB_NAME_;
  $res = dbquery($query); 
  while($row = mysqli_fetch_row($res))
  { $newtables[] = substr($row[0], $newprefixlen);
  }
  
  if(!in_array("configuration",$newtables)) colordie("This is not a proper shop!");
  if(!in_array("configuration",$oldtables)) colordie("The old shop has a different prefix or another problem makes executing this script impossible!");

/* now start the main process */
echo "<i>Copying tables</i><br>";
if (_PS_VERSION_ >= "1.7.0")
{ $query = "SET foreign_key_checks = 0"; /* Ignore foreign keys. */
  $res = dbquery($query); 
}

$count = 0;
foreach ($oldtables AS $table)
{ $count++;
  if(($count < $startnum) || ($count >= ($startnum + $numtables))) 
  { echo "<br><i>".$count." ";
    if($count == ($startnum + $numtables))
	  echo date("H:i:s", time())." ";
    echo $table." skipped</i>";
    continue;
  }
  echo "<br>".$count." ".date("H:i:s", time())." ";
  
  if(!in_array($table, $newtables)) 
  { copy_extra_table($table);  /* table that was not present in new shop */
  }
  else
  { copy_table($table);
    check_indexes($table);    
  }
}
echo "<br>".date("H:i:s", time())."<br>";

  if (_PS_VERSION_ >= "1.7.0")
  { $query = "SET foreign_key_checks = 1"; 
    $res = dbquery($query); 
	$query = "UPDATE "._DB_PREFIX_."product SET state=1;";
    $res = dbquery($query); 
  }
  
  /* table ps_currency_lang was introduced in PS 1.7.6. Missing its values causes a "missing locale" error */
  /* here we handle such cases as good as possible */
  if (_PS_VERSION_ >= "1.7.6")
  { $languages = array();
    $query = "SELECT * FROM "._DB_NAME_."."._DB_PREFIX_."lang"; 
    $res = dbquery($query);
	while($row = mysqli_fetch_assoc($res))
	    $languages[] = $row["id_lang"];
		
    if(!in_array(_DB_PREFIX_.'currency_lang', $oldtables))
	{ echo "<br>Truncating currency_lang<br>";
	  $query = "TRUNCATE "._DB_NAME_."."._DB_PREFIX_."currency_lang"; 
      $res = dbquery($query);

  	  $symbols = array("ALL"=>"L","CAD"=>"CA$","CHF"=>"fr","CZK"=>"Kč","EUR"=>"€","GBP"=>"£",
	  "HRK"=>"kn","HUF"=>"Ft","IDR"=>"Rp","ILS"=>"₪","INR"=>"₹","IRR"=>"﷼","MXN"=>"MX$","NOK"=>"kr",
	  "PLN"=>"zł","RON"=>"L","RSD"=>"din","RUB"=>"₽","SEK"=>"kr","TRY"=>"₺","USD"=>"$");  /* ISO 4217-code */

      $query = "SELECT * FROM "._DB_NAME_."."._DB_PREFIX_."currency"; 
      $res = dbquery($query);
	  while($row = mysqli_fetch_assoc($res))
	  { foreach($languages AS $lang)
		{ $name = $row["iso_code"];
		  $sign = $symbols[$row["iso_code"]];
 		  $iquery = 'INSERT INTO '._DB_PREFIX_.'currency_lang SET id_currency="'.$row["id_currency"];
		  $iquery .= '",id_lang="'.$lang.'",name="'.$name.'", symbol="'.$sign.'"';
	  	  $ires = dbquery($iquery); 
		}
	  }
	}
	else
	{ $currencies = array();
      $query = "SELECT * FROM "._DB_NAME_."."._DB_PREFIX_."currency"; 
      $res = dbquery($query);
	  while($row = mysqli_fetch_assoc($res))
	  { foreach($languages AS $lang)
		{ $lquery = "SELECT * FROM "._DB_NAME_."."._DB_PREFIX_."currency_lang"; 
		  $lres = dbquery($lquery);
		  if(mysqli_num_rows($lres) == 0)
		  { $name = $row["iso_code"];
		    $sign = $symbols[$row["iso_code"]];
 		    $iquery = 'INSERT INTO '._DB_PREFIX_.'currency_lang SET id_currency="'.$row["id_currency"];
		    $iquery .= '",id_lang="'.$lang.'",name="'.$name.'", symbol="'.$sign.'"';
	  	    $ires = dbquery($iquery); 
		  }
		}
	  }
	}

  }
  
  echo "<p>** finished copying **";
  
  if($comparefiles)
  { echo " ... now comparing **";
    clearstatcache(); /* make sure we get recent filesizes */

    $query = "SELECT * FROM "._OLD_NAME_."."._OLD_PREFIX_."shop_url"; 
	$res = dbquery($query); 
	$oldrow = mysqli_fetch_assoc($res);
	
	$query = "SELECT * FROM "._DB_NAME_."."._DB_PREFIX_."shop_url"; 
	$res = dbquery($query); 
	$row = mysqli_fetch_assoc($res);
	
    if($oldrow["domain"] != $row["domain"])
	  colordie("For file comparing the shops must use the same server: ".$oldrow["domain"]." != ".$row["domain"]."!");
    if($oldrow["physical_uri"] != $row["physical_uri"])
	  colordie("For file comparing the shops should be in different directories!");
  
    $cnt = substr_count($row["physical_uri"],'/');
	$oldpath = $triplepath;
	for($i=1; $i<$cnt; $i++)
		$oldpath = $oldpath."../";
	$oldpath .= substr($oldrow["physical_uri"],1);
  
    $folders = array('', "Adapter/", "app/", "bin/", "config/", "classes/", "controllers/", "Core/", "css/", "js/", "localization/", "tools/", "translations/", "webservice/", "src/", "themes/_libraries/", "themes/classic/", "vendor/", $admindir."/");
	foreach($folders AS $folder)
	  compare_folders($folder, 0);

  }
  
  echo "<p>** finished everything **";
    
/* input "varchar(10)" -> output array("varchar", "10"); */
function formatter($str)
{ $arr = explode("(", $str);
  if(sizeof($arr) == 1) /* for example with text */
  { $arr[1] = 0;
  }
  else
  { $pos = strpos($arr[1], ")");
    $arr[1] = substr($arr[1],0,$pos);
  }
  return($arr);
}

function copy_table($table)
{ global $conn;
  $oldfields = $newfields = array();
  $oldformats = $newformats = array();
  $query = "SHOW COLUMNS FROM "._OLD_NAME_."."._OLD_PREFIX_.$table;
  $res = dbquery($query); 
  if(!$res) /* should not happen as there is a previous test */
  { colordie("Warning: table <i>".$table."</i> was not found in the old database");
    return;
  }
  while($row = mysqli_fetch_array($res))
  { $oldfields[] = $row[0];
    $oldformats[$row[0]] = formatter($row[1]);
  }

  $query = "SHOW COLUMNS FROM "._DB_NAME_."."._DB_PREFIX_.$table;
  $res = dbquery($query); 
  while($row = mysqli_fetch_array($res))
  { $newfields[] = $row[0];
    $newformats[$row[0]] = formatter($row[1]);
  }
  
  $oldextras = array_diff($oldfields, $newfields);
  $newextras = array_diff($newfields, $oldfields);
  if((sizeof($oldextras) > 0) || (sizeof($newextras) > 0))
  { echo "<br><b>Structure different.</b>";
    if(sizeof($oldextras) > 0)
	{ echo " <b>Extra in old:</b> ";
	  foreach($oldextras AS $extra) 
	    echo $extra.", ";
	}
    $newextras = array_diff($newfields, $oldfields);
    if(sizeof($newextras) > 0)
	{ echo " <b>Extra in new:</b> ";
	  foreach($newextras AS $extra) 
	    echo $extra.", ";
	}
  }
  $changed = false;
  $arr = array_intersect_key($oldformats, $newformats);
  foreach ($arr AS $key => $val)
  { $str = $key.": ";
    if($oldformats[$key][0] != $newformats[$key][0])
	  $str .= "old=".$oldformats[$key][0]."; new=".$newformats[$key][0]."; "; 
	if($oldformats[$key][1] > $newformats[$key][1]) 
	  $str .= "oldsize=".$oldformats[$key][1]."; newsize=".$newformats[$key][1]."; ";
	if($str != $key.": ")
	{ if(!$changed)
	  { $changed = true;
	    echo "<br><b>Different field formats:</b> ";
	  }
	  echo $str;
	}
  }
  
  $arr = array_diff_key($oldformats, $newformats);
  if(sizeof($arr) > 0)
  {	// $res = dbquery("SET OPTION SQL_QUOTE_SHOW_CREATE=1");
    $res = dbquery("SHOW CREATE TABLE "._OLD_NAME_.".".$table);
    $row=mysqli_fetch_array($res);
	$cq = $row[1];
	
	$basic = false;
	if (mb_strpos($cq, "(\r\n ")) 
	  $lines = explode("\r\n", $cq);
    else if (mb_strpos($cq, "(\n "))
	  $lines = explode("\n", $cq);	
    else if (mb_strpos($cq, "(\r "))
	  $lines = explode("\r", $cq);
    else 
	{ $basic = true;
	  echo "<br>Using basic field addition! ";
	}
	if(!$basic)
	{ $len = sizeof($lines);
      for($i=0; $i<$len; $i++)
	  { $lines[$i] = ltrim($lines[$i]);
		$lines[$i] = substr($lines[$i],0,-1);
	  }
	}
  }

  foreach ($arr AS $key => $val)
  { if($basic)
	{ $qry = "ALTER TABLE "._DB_NAME_.".".$table." ADD COLUMN ".$key." ".$val[0];
      if($val[1] != "")
	    $qry .= "(".$val[1].")";
	  $rs = dbquery($qry);
	}
	else
	{ $ln = strlen($key);
	  for($i=0; $i<$len; $i++)
	  { if((substr($lines[$i],0,$ln+1) == $key." ") || (substr($lines[$i],0,$ln+2) == "`".$key."`"))
		{ if(substr($lines[$i],0,$ln+1) == $key." ")
			$suffix = substr($lines[$i],$ln);
		  else
			$suffix = substr($lines[$i],$ln+2);
		  $qry = "ALTER TABLE "._DB_NAME_.".".$table." ADD COLUMN ".$key." ".$suffix;
		  echo $qry;
		  $rs = dbquery($qry);
		  break;
		}
	  }
	}
	$newfields[] = $key;
  }

  $args = "`".implode("`,`", array_intersect($newfields, $oldfields))."`";
  if($table == "specific_price_priority")
	copy_specific_price_priority_direct($table, $args);
  else if($table == _DB_PREFIX_."configuration")
	merge_configurations($table, $args);
  else if($table == _DB_PREFIX_."hook")
	merge_hooks($table, $args);
  else if($table != _DB_PREFIX_."configuration_lang")  
    copy_table_direct($table, $args);

  echo "Copying ".$table;
}

function copy_table_direct($table, $args)
{ global $conn;

  $query = "TRUNCATE TABLE `"._DB_NAME_."`.`"._DB_PREFIX_.$table."`";
  $res = dbquery($query); 
  if(!$res) 
      sql_error($conn, $query);

  $query = "INSERT INTO `"._DB_NAME_."`.`"._DB_PREFIX_.$table."` (".$args.") SELECT ".$args." FROM `"._OLD_NAME_."`.`"._OLD_PREFIX_.$table."`";
  $res = dbquery($query); 
  if(!$res) 
      sql_error($conn, $query); 
}

/* in 1.6 the ps_specific_price_priority table has got an extra unique key that makes that you can only have one row per product */
/* the solution below will work for most shops */
function copy_specific_price_priority_direct($table, $args)
{ global $conn, $oldconn;
  $query = "TRUNCATE TABLE "._DB_PREFIX_.$table;
  $res = dbquery($query); 
  if(!$res) 
      sql_error($conn, $query);
  $query = "INSERT INTO `"._DB_NAME_."`.`"._DB_PREFIX_.$table."` (SELECT ".$args." FROM `"._OLD_NAME_."`.`"._OLD_PREFIX_.$table."`)";
  $query .= " ON DUPLICATE KEY UPDATE `"._DB_NAME_."`.`"._DB_PREFIX_. $table."`.id_product=`"._DB_NAME_."`.`"._DB_PREFIX_.$table."`.id_product";
  $res = dbxquery($oldconn, $query); 
  if(!$res) 
      sql_error($oldconn, $query); 
}

function copy_extra_table($table)
{ $query = "CREATE TABLE `"._DB_NAME_."`.`"._DB_PREFIX_.$table."` LIKE `"._OLD_NAME_."`.`"._OLD_PREFIX_.$table."`";
  $res = dbquery($query); 
  
  $query = "INSERT INTO `"._DB_NAME_."`.`"._DB_PREFIX_.$table."` SELECT * FROM `"._OLD_NAME_."`.`"._OLD_PREFIX_.$table."`";
  $res = dbquery($query);
  echo "Adding table ".$table;
}

function check_indexes($table)
{ $oldlevel1s = $oldlevel2s = $oldlevel3s = array();
  $newlevel1s = $newlevel2s = $newlevel3s = array();
  $query = "SHOW INDEXES FROM `"._OLD_NAME_."`.`"._OLD_PREFIX_.$table."`";
  $res = dbquery($query);
  while ($row=mysqli_fetch_assoc($res))
  { if(!in_array($row["Key_name"], $oldlevel1s))
	{ $oldlevel1s[] = $row["Key_name"];
      $oldlevel2s[$row["Key_name"]] = array();
      $oldlevel2s[$row["Key_name"]] = array();
	}
	$oldlevel2s[$row["Key_name"]][] = $row["Column_name"];
	$oldlevel3s[$row["Key_name"]][$row["Column_name"]] = $row;
	$oldlevel3s[$row["Key_name"]][0] = $row;	
  }
 
  $query = "SHOW INDEXES FROM `"._DB_NAME_."`.`"._DB_PREFIX_.$table."`";
  $res = dbquery($query);
  while ($row=mysqli_fetch_array($res))
  { if(!in_array($row["Key_name"], $newlevel1s))
	{ $newlevel1s[] = $row["Key_name"];
      $newlevel2s[$row["Key_name"]] = array();
      $newlevel2s[$row["Key_name"]] = array();
	}
	$newlevel2s[$row["Key_name"]][] = $row["Column_name"];
	$newlevel3s[$row["Key_name"]][$row["Column_name"]] = $row;
	$newlevel3s[$row["Key_name"]][0] = $row;
  }
 
  if(sizeof($oldlevel1s) != sizeof($newlevel1s))
    echo "<br>".$table.": Old has ".sizeof($oldlevel1s)." indexes and new ".sizeof($newlevel1s).". ";
  foreach($newlevel1s AS $key)
  { if(!in_array($key, $oldlevel1s))
	  continue;
    $newset = implode(",",$newlevel2s[$key]);
    $oldset = implode(",",$oldlevel2s[$key]);
	if($oldset != $newset)
		echo "<br>".$table." index ".$key." differs: old=".$oldset."; new=".$newset.";";

	if($oldlevel3s[$key][0]["Non_unique"] != $newlevel3s[$key][0]["Non_unique"])
	{ echo "<br>".$table." index ".$key." differs:";
	  if($oldlevel3s[$key][0]["Non_unique"] == 0) echo " old"; else echo "new";
	  echo " is unique. The other not.";
	}
  }
}


function merge_configurations($table, $args)
{ global $conn, $langtransforms;
  $confrows = $clangrows = array();
  $query = "SELECT * FROM `"._DB_NAME_."`.`"._DB_PREFIX_.$table."`";
  $res = dbquery($query);
  while ($row=mysqli_fetch_assoc($res))
    $confrows[] = $row;

  $query = "SELECT * FROM `"._DB_NAME_."`.`"._DB_PREFIX_.$table."_lang`";
  $res = dbquery($query);
  while ($row=mysqli_fetch_assoc($res))
  { if(!isset($clangrows[$row["id_configuration"]]))
	  $clangrows[$row["id_configuration"]] = array();
    $clangrows[$row["id_configuration"]][] = $row;
  }

  copy_table_direct($table, $args);
  $args = "`id_configuration`,`id_lang`,`value`,`date_upd`";
  copy_table_direct($table."_lang", $args);
  $x=0;
  foreach($confrows AS $confrow)
  { $qry = "SELECT * FROM `"._DB_NAME_."`.`"._DB_PREFIX_.$table."` WHERE name='".$confrow["name"]."'";
	if($confrow["id_shop"] == NULL)
	  $qry .= " AND id_shop IS NULL";
	else
	  $qry .= " AND id_shop='".$confrow["id_shop"]."'";
	if($confrow["id_shop_group"] == NULL)
	  $qry .= " AND id_shop_group IS NULL";
	else
	  $qry .= " AND id_shop_group='".$confrow["id_shop_group"]."'";
    $rs = dbquery($qry);
	
	if(mysqli_num_rows($rs) == 0)
	{ echo " add-".$confrow["name"];
      $iquery = "INSERT INTO `"._DB_NAME_."`.`"._DB_PREFIX_.$table."` SET name='".$confrow["name"]."',value='".$confrow["value"]."',date_add=NOW(),date_upd=NOW()";
	  if($confrow["id_shop"] == NULL)
		$iquery .= ",id_shop=NULL";
	  else
		$iquery .= ",id_shop='".$confrow["id_shop"]."'";
	  if($confrow["id_shop_group"] == NULL)
		$iquery .= ",id_shop_group=NULL";
	  else
		$iquery .= ",id_shop_group='".$confrow["id_shop_group"]."'";
      $ires = dbquery($iquery);
	  
	  if(isset($clangrows[$confrow["id_configuration"]]))
	  { $id = mysqli_insert_id($conn);
		if(!$id) 
			colordie("Error getting new id.");
		foreach($clangrows[$confrow["id_configuration"]] AS $crow)
		{ if(!isset($langtransforms[$crow["id_lang"]])) continue; /* skip languages that are no longer in ps_lang */
		  $iquery = "INSERT INTO `"._DB_NAME_."`.`"._DB_PREFIX_.$table."_lang` SET id_configuration='".$id."',value='".$crow["value"]."',id_lang='".$langtransforms[$crow["id_lang"]]."'";
		  $ires = dbquery($iquery);
		}
	  }
	}
  }
  echo " ";
}

function merge_hooks($table, $args)
{ $hookrows = array();
  $query = "SELECT * FROM `"._DB_NAME_."`.`"._DB_PREFIX_.$table."`";
  $res = dbquery($query);
  while ($row=mysqli_fetch_assoc($res))
    $hookrows[] = $row;
  copy_table_direct($table, $args);
  foreach($hookrows AS $hookrow)
  { $qry = "SELECT * FROM `"._DB_NAME_."`.`"._DB_PREFIX_.$table."` WHERE name='".$hookrow["name"]."'";
    $rs = dbquery($qry);
	if(mysqli_num_rows($rs) == 0)
	{ $iquery = "INSERT INTO `"._DB_NAME_."`.`"._DB_PREFIX_.$table."` SET name='".$hookrow["name"]."',title='".$hookrow["title"]."',description='".$hookrow["description"]."',position='".$hookrow["position"]."'";
	  if(version_compare(_PS_VERSION_ , "1.7.0", "<")) 
		 $iquery .= ",live_edit=".$hookrow["live_edit"];
      $ires = dbquery($iquery);
	  echo " i-".$hookrow["name"];
	}
  } 
  echo " ";  
}

function compare_folders($path, $level)
{ global $oldpath, $triplepath;
  if($level==15)
  { echo "<br>Too many levels ".$path."<br>";
    return;
//	die("END");
  }
  if($level == 0)
  { if((!file_exists($oldpath.$path)) && (!file_exists($triplepath.$path)))
	  return;
    if(!file_exists($oldpath.$path))
	{ echo "<br>Directory ".$path." missing in old shop.";
	  return;
	}
    if(!file_exists($triplepath.$path))
	{ echo "<br>Directory ".$path." missing in new shop.";
	  return;
	}
  }
  
  for($i=0; $i<$level; $i++)
		echo "  ";
  $subdirs = array();
  $files = scandir($triplepath.$path);
  $cleanPath = rtrim($path, '/'). '/';
  $newfiles = array();
  natcasesort($files);
  foreach($files as $t) 
  {     if (($t==".") || ($t=="..")) continue;
        $currentFile = $cleanPath . $t;
		$str = "";
		for($i=0; $i<$level; $i++)
			$str .= "  ";
		$str .= $t;
		if(strlen($str) < 30)
			$str = str_pad($str, 30);
        if (is_dir($triplepath.$currentFile))
		{ if(!file_exists($oldpath.$currentFile) AND ($path!=''))
			echo "<br>Directory ".$currentFile." doesn't exist on old shop";
		  else
		    $subdirs[] = $currentFile;
		}
		else 
		{ $newsize = filesize($triplepath.$currentFile);
		  if(!file_exists($oldpath.$currentFile))
			echo "<br>".$currentFile." doesn't exist on old shop";
		  else
		  { $oldsize = filesize($oldpath.$currentFile);
		    if($oldsize != $newsize)
			{ if((($path == '') && (($t == ".htaccess") || ($t == "robots.txt")))
				|| (($path == 'app/config') && ($t == "parameters.php")))
			  {}
			  else
			    echo "<br>".$currentFile.": old=".$oldsize."; new=".$newsize;
			}
		  }
        }
    }
	if($path == '') return; 
	foreach($subdirs AS $subdir)
    {// if(($level == 0) && !$includeimgs && (substr($subdir,-4)=="/img")) continue;
	  compare_folders($subdir, $level+1);
	}
}
