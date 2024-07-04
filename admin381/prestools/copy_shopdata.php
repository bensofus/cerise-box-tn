<?php 
/* See copy_shopdate_readme.txt for explanation */
  if(!@include 'approve.php') die( "approve.php was not found! Please use this script together with Prestools that can be downloaded for free in the Free Modules & Themes section of the Prestashop forum");
   /* get old shop version: needed in config and functions */
  if(!include 'copy_shopdata_functions.inc.php') die( "copy_shopdata_functions.inc.php was not found!");
  if(!include 'copy_shopdata_config.php') die( "copy_shopdata_config.php was not found!");

  /* general preparations */
  echo "Copy_shopdata version 0.69 starting at ".date("H:i:s", time())."<br>";
  $tottables = sizeof($copied_tables) + sizeof($module_tables);
  if(($tottables - $startnum + 1) < $numtables) $numtables = $tottables - $startnum + 1;
  echo "You are copying tables ".$startnum."-".($startnum+$numtables-1)." of ".$tottables." tables.";
  echo "<br>Beware of timeouts: After finishing the last line on the screen should be \"** finished **\". If necessary increase the timeout at the start of this file. After a timeout you can increase startnum as you don't need to copy processed tables again.<p>";
  
  /* if you copy tables over themselves they will get emptied. So the following check is very important */
  if((_OLD_SERVER_ == _DB_SERVER_) && (_OLD_USER_ == _DB_USER_) && (_OLD_PASSWD_ == _DB_PASSWD_) && (_OLD_NAME_ == _DB_NAME_) && (_OLD_PREFIX_ == _DB_PREFIX_))
    colordie("You cannot copy a webshop upon itself. Did you install the script on the new shop?");
    
  $tmp1 = strtolower(_OLD_SERVER_); $tmp2 = strtolower(_DB_SERVER_);
  if((_OLD_SERVER_ != _DB_SERVER_) && in_array($tmp1,array("localhost","127.0.0.1","::1")) 
		 && in_array($tmp1,array("localhost","127.0.0.1","::1")))
	 echo "It looks like you have both shops on localhost but use different servernames. For example localhost and 127.0.01. Please correct that for better results!<br>";
		
  if((_OLD_SERVER_ != _DB_SERVER_) || (_OLD_USER_ != _DB_USER_) || (_OLD_PASSWD_ != _DB_PASSWD_) || (_OLD_NAME_ != _DB_NAME_))
  { $oldconn = @mysqli_connect(_OLD_SERVER_, _OLD_USER_, _OLD_PASSWD_) or colordie ("Could not connect to old database server!!! Did you fill in the credentials of the old shop correctly in the configuration file?");
    mysqli_select_db($oldconn, _OLD_NAME_) or colordie("Error selecting database");
    $query = "SET NAMES utf8";
    $result = dbxquery($oldconn, $query);
  }
  else 
    $oldconn = $conn;
	
  $create_table = 'CREATE TABLE IF NOT EXISTS '._DB_PREFIX_._PRESTOOLS_PREFIX_.'copy_shopdata( tablename VARCHAR(200) NOT NULL, date_upd DATETIME, PRIMARY KEY(tablename))';
  $create_tbl = dbquery($create_table);
	
  echo "oldconn = "._OLD_SERVER_."."._OLD_NAME_." = ".$oldconn->thread_id."<br>";
  echo "new conn = "._DB_SERVER_."."._DB_NAME_." = ".$conn->thread_id."<br>";
   
  $oldversion = 0;
  $qquery = 'SELECT value FROM '._OLD_PREFIX_.'configuration WHERE name = "PS_VERSION_DB"'; 
  $qres = mysqli_query($oldconn, $qquery);
  if(mysqli_num_rows($qres)!=0) list($oldversion) = mysqli_fetch_row($qres);
  if($oldversion == 0) 
  { $oldversion = "9.9.9";
    echo "<br>Couldn't determine old version. No patching will be done<br>";
  }
   
  $oldtables = $wrongprefixtables = array();
  $query = "SHOW TABLES";
  $res = dbxquery($oldconn, $query); 
  $len = strlen(_OLD_PREFIX_);
  while($row = mysqli_fetch_row($res))
  { if(substr($row[0],0,$len) == _OLD_PREFIX_)
	  $oldtables[] = substr($row[0], $len);
    else
	  $wrongprefixtables[] = $row[0];
  }
  
  if(sizeof($oldtables) < 100)
    colordie("Insufficient old tables found. Are you sure the old prefix is indeed "._OLD_PREFIX_."?");

  if(sizeof($wrongprefixtables) > 0)
  { echo "<br>Tables without prefix '"._OLD_PREFIX_."' will not be copied. In your setting this applies to the following ".sizeof($wrongprefixtables)." tables: ";
    echo implode(",", $wrongprefixtables)."<p>";
  }
    
  $newtables = array();
   
  $res = dbxquery($conn, $query); 
  $len = strlen(_DB_PREFIX_);
  while($row = mysqli_fetch_row($res))
  { if(substr($row[0],0,$len) == _DB_PREFIX_)
	  $newtables[] = substr($row[0], $len);
  }  

  create_langtransform_table();
  create_currencymap();

  /* now update the basic settings of the shop */
  /* this is only done once. If you run copy_shopdata a second time this is skipped */
  /* The idea is that you run it once on the fresh shop to get a lot of things settled. After that you 
     don't want to run it again as it would interfere with setting up the shop */
  $qquery = 'SELECT value FROM '._DB_PREFIX_.'configuration WHERE name = "COPYSHOPDATA_INITIALIZED"'; 
  $qres = dbxquery($conn, $qquery);
  if(mysqli_num_rows($qres)!=0)
	 $qrow = mysqli_fetch_array($qres);
  if(($do_initialization == "2") || (($do_initialization == "1") && ((!isset($qrow)) || (!isset($qrow["value"])) || ($qrow["value"] != "1"))))
  { echo "<i>Initialisation:</i><br>";
    echo "Copying configuration table values (unchanged values will be ignored):";
    foreach($conf_values AS $conf)
    { update_config_value($conf, true);  /* insert when not present */
    }
  
    foreach($conf_update_values AS $conf)
    { update_config_value($conf, false);  /* skip when not present */
    }
	
    foreach($conf_notvalidated AS $conf)
    { update_config_value($conf, true);  /* insert when not present */
    }
    /* here we try to copy from the old shop the home category settings in the configuration table */
	/* these values were updated previously. The function serves here to check whether these values are present in the old shop */
    $res1 = update_config_value("HOME_FEATURED_CAT", true);  /* insert when not present */
    $res2 = update_config_value("PS_HOME_CATEGORY", true);  /* insert when not present */
    if(!$res1 && !$res2)
    { $query = "SELECT id_category FROM "._OLD_PREFIX_."shop ORDER BY id_shop"; 
      $res = dbxquery($oldconn, $query); 
      if(mysqli_num_rows($res) > 0)
	  { $row = mysqli_fetch_array($res);
	    $query = "UPDATE "._DB_PREFIX_."configuration SET value='".$row['id_category']."' WHERE name = 'HOME_FEATURED_CAT'";
	    $res = dbxquery($conn, $query);
	  }
	  else
	    echo "<br><b>Could not update home category in configuration table</b>";
    }  
   
    /* Now handle modules. We take a list of the modules that are enabled in a default 1.6.1.7 installation */
	/* We check whether they are enabled in the old shop. If not, we disable them in the new one */
	/* This section is deliberately kept simple. If the old installation had special settings for */
	/*   multishop or groups we do nothing */
    if(version_compare(_PS_VERSION_ , "1.7.0", ">=") && version_compare($oldversion , "1.7.0", ">="))
	  $conf_modules = $conf_modules17;
    else if(version_compare(_PS_VERSION_ , "1.7.0", "<") && version_compare($oldversion , "1.7.0", "<")
	  && version_compare(_PS_VERSION_ , "1.5.0", ">=") && version_compare($oldversion , "1.5.0", ">="))
	  $conf_modules = $conf_modules16;
	else 
	  $conf_modules = array();
	if (sizeof($conf_modules) > 0)
	{ echo "<br>Disabling modules: ";
	  foreach($conf_modules AS $module)
	  { $query = "SELECT id_module FROM "._OLD_PREFIX_."module WHERE name='".$module."'"; 
        $res = dbxquery($oldconn, $query); /* not found here = not installed */
	    if(mysqli_num_rows($res) > 0) 
	    { $row = mysqli_fetch_array($res);
		  $id_module = $row["id_module"];
		  $squery = "SELECT * FROM "._OLD_PREFIX_."module_shop WHERE id_module='".$id_module."'"; 
          $sres = dbxquery($oldconn, $squery); /* not found here = not enabled */
	    }
	    /* disable the module if it was not found or was disabled */
		if((mysqli_num_rows($res)==0) || (mysqli_num_rows($sres)==0))
		{ $uquery = "SELECT id_module FROM "._DB_PREFIX_."module WHERE name='".$module."'"; 
          $ures = dbxquery($conn, $uquery); /* not found: do nothing */
	      if(mysqli_num_rows($ures) > 0) 
	      { $urow = mysqli_fetch_array($ures);
		    $id_module_new = $urow["id_module"];
			$vquery = "SELECT * FROM "._DB_PREFIX_."module_shop WHERE id_module='".$id_module_new."'"; 
            $vres =dbxquery($conn, $vquery); /* not found: do nothing */
			if(mysqli_num_rows($vres)>0)
			{ $dquery = "DELETE FROM "._DB_PREFIX_."module_shop WHERE id_module='".$id_module_new."'"; 
              $dres = dbxquery($conn, $dquery); /* not found here = not enabled */
			  echo $module." ";
			}
	      }		  
		}
	  }
	}
	
	/* Now set the flag so that we don't initialize a second time */
	  if(mysqli_num_rows($qres)!=0)  /* the query from the beginning of the initialization */
      { $query = 'UPDATE '._DB_PREFIX_.'configuration SET value="1"';
	    $query .= ', date_upd="'.date("Y-m-d H:i:s", time()).'"';
		$query .= ' WHERE name = "COPYSHOPDATA_INITIALIZED"';
	  }
	  else 
      { $query = 'INSERT INTO '._DB_PREFIX_.'configuration SET value="1", name = "COPYSHOPDATA_INITIALIZED", date_add="'.date("Y-m-d H:i:s", time()).'",date_upd="'.date("Y-m-d H:i:s", time()).'"';
      }	
      $res = dbxquery($conn, $query);
	  echo "<br>END of initialization<p>";
  } /* end of initialization */
  else
  { if($do_initialization == "0") 
	  echo "Initialization skipped because your config setting<br>";
    else if($do_initialization == "1") 	
	  echo "Initialization skipped because it had already run<br>";
    else
	  echo "Unknown initialization setting - initialization skipped<br>";
  }

/* MySQL needs absolute paths for exporting to and importing from files. We create that here. We also create the Export subdirectory. */
$basepath = str_replace("copy_shopdata_config.php","", realpath('copy_shopdata_config.php')); // the mysql functions won't work without an absolute path 
$basepath = str_replace("\\", "/", $basepath);  
$exportpath = "export";
if(!is_dir($exportpath))
{ if(mkdir($exportpath))
    $basepath .= $exportpath."/";
}

/* now start the main process */
echo "<i>Copying tables</i><br>";
if (_PS_VERSION_ >= "1.7.0")
{ $query = "SET foreign_key_checks = 0"; /* Ignore foreign keys. */
  $res = dbxquery($conn, $query); 
}
$count = 0;
foreach ($copied_tables AS $table)
{ $count++;
  if(($count < $startnum) || ($count >= ($startnum + $numtables))) /* else this will be handled by the copy_table function */
  { echo "<br>".$count." ".$table." skipped";
    continue;
  }
  
  $msg = $table." ";
  $oldmissing = $newmissing = false;
  if(!in_array($table, $oldtables)) /* does the table exist in the old database? */
  { $msg .= " <b>table doesn't exist in old database</b>";
    $oldmissing = true;
  }
  if(!in_array($table, $newtables))
  { $msg .= " <b>table doesn't exist in new database</b>";
    $newmissing = true;
  } 
  if($oldmissing && $newmissing) continue; /* do not show the table names if they are in neither table */
  if($oldmissing || $newmissing) 
  { echo "<br>".$msg;
	continue;
  }
  $query = "SELECT COUNT(*) AS mycount FROM "._OLD_PREFIX_.$table;
  $res = dbxquery($oldconn, $query);
  if(!$res) 
    sql_error($oldconn, $query);
  $row = mysqli_fetch_assoc($res);
  echo "<br>".$count." ".$msg." ".$row["mycount"];
  if($row["mycount"] == 0)
  { $query = "TRUNCATE "._DB_PREFIX_.$table;  /* empty new table */
    $res = dbxquery($conn, $query); 
    continue;
  }
  copy_table($table);

}
echo "<br>".date("H:i:s", time())."<br>";

echo "<i>The following tables from the module tables were checked and copied when present:</i>";
foreach ($module_tables AS $table)
{ $count++;
  if(($count < $startnum) || ($count >= ($startnum + $numtables))) 
  { echo "<br>".$count." ".$table." skipped";
    continue;
  }
  echo "<br>".$count." ".$table." ";
  if(!in_array($table, $newtables))
  { echo " <b>not yet installed</b>";
    continue;
  }
  if(!in_array($table, $oldtables))
  { echo " <b>not in old database</b>";
    continue;
  }
  $query = "SELECT COUNT(*) AS mycount FROM "._OLD_PREFIX_.$table;
  $res = dbxquery($oldconn, $query); 
  $row = mysqli_fetch_assoc($res);
  echo $row["mycount"];
  copy_table($table);
}

  /* At the end we give some suggestions which tables might be added */
  /* get list of new tables */
  $combi_tables = array_merge($copied_tables, $system_tables, $module_tables, $rights_tables, $log_and_stats_tables, $search_tables, $init_tables);
  $missing_tables = array_diff($newtables, $combi_tables);
  if(sizeof($missing_tables) > 0)
  { echo "<p><i>The following tables of the new shop are not covered:</i> ";
    $first = true;
	foreach($missing_tables AS $miss)
    { if($first) $first=false; else echo ", ";
	  echo $miss;
	}
  }

  /* now get list of old tables */
  $extra_tables = array_diff($oldtables, $newtables);
  if(sizeof($extra_tables) > 0)
  { echo "<p>The following tables of the old shop are not copied - consider to add them to the addon array: ";
    $first = true;
	foreach($extra_tables AS $extra)
      if(!in_array($extra, $module_tables))
      { if($first) $first=false; else echo ", ";
	    echo $extra;
	  }
  }

  if (_PS_VERSION_ >= "1.7.0")
  { $query = "SET foreign_key_checks = 1"; 
    $res = dbxquery($conn, $query); 
	$query = "UPDATE "._DB_PREFIX_."product SET state=1;";
    $res = dbxquery($conn, $query); 
  }
  
  /* handle new product_type field */
  if (($oldversion < "1.7.8") && (_PS_VERSION_ >= "1.7.8"))
  { $query = "UPDATE "._DB_PREFIX_."product SET product_type='standard'";
    $query .= " WHERE is_virtual=0;";
    $res = dbxquery($conn, $query); 
	$query = "UPDATE "._DB_PREFIX_."product SET product_type='virtual'";
    $query .= " WHERE is_virtual=1;";
    $res = dbxquery($conn, $query); 
//	$query = "UPDATE "._DB_PREFIX_."product SET product_type='combinations'";
//    $query .= " WHERE id_product IN (SELECT DISTINCT(id_product) FROM "._DB_PREFIX_."product_attribute)";
    $query = "UPDATE "._DB_PREFIX_."product SET product_type='combinations' WHERE `cache_default_attribute` != 0";
    $res = dbxquery($conn, $query); 
//	$query = "UPDATE "._DB_PREFIX_."product SET product_type='pack'";
//    $query .= " WHERE id_product IN (SELECT DISTINCT(id_product_pack) FROM "._DB_PREFIX_."pack)";
	$query = "UPDATE "._DB_PREFIX_."product SET product_type='pack' WHERE `cache_is_pack` = 1";
    $res = dbxquery($conn, $query); 
  }  
  
  if (($oldversion < "8.1.0") && (_PS_VERSION_ >= "8.1.0"))
  { $query = "UPDATE `"._DB_PREFIX_."product` SET redirect_type = 'default' WHERE redirect_type = '404' OR redirect_type = '' OR redirect_type IS NULL";
    $res = dbxquery($conn, $query); 
    $query = "UPDATE `"._DB_PREFIX_."product_shop` SET redirect_type = 'default' WHERE redirect_type = '404' OR redirect_type = '' OR redirect_type IS NULL";
    $res = dbxquery($conn, $query); 
  }
    
  /* Prestashop 8 rejects some ps_product values when NULL; Yet that is the default */
  /* borrowed from https://github.com/PrestaShop/autoupgrade/pull/605/files */
if(version_compare(_PS_VERSION_ , "1.6.0.9", ">="))
{	/* Normalize some older database records that should not be NULL, prevents errors in new code. */
	/* Mainly concerns people coming all the way from 1.6, but will fix many inconsitencies. */
	$res = dbquery("UPDATE `"._DB_PREFIX_."address` SET `address2`='' WHERE `address2` IS NULL");
	$res = dbquery("UPDATE `"._DB_PREFIX_."address` SET `company`='' WHERE `company` IS NULL");
	$res = dbquery("UPDATE `"._DB_PREFIX_."address` SET `dni`='' WHERE `dni` IS NULL");
	$res = dbquery("UPDATE `"._DB_PREFIX_."address` SET `id_state`='0' WHERE `id_state` IS NULL");
	$res = dbquery("UPDATE `"._DB_PREFIX_."address` SET `other`='' WHERE `other` IS NULL");
	$res = dbquery("UPDATE `"._DB_PREFIX_."address` SET `phone_mobile`='' WHERE `phone_mobile` IS NULL");
	$res = dbquery("UPDATE `"._DB_PREFIX_."address` SET `phone`='' WHERE `phone` IS NULL");
	$res = dbquery("UPDATE `"._DB_PREFIX_."address` SET `postcode`='' WHERE `postcode` IS NULL");
	$res = dbquery("UPDATE `"._DB_PREFIX_."address` SET `vat_number`='' WHERE `vat_number` IS NULL");
	$res = dbquery("UPDATE `"._DB_PREFIX_."attachment_lang` SET `description`='' WHERE `description` IS NULL");
	$res = dbquery("UPDATE `"._DB_PREFIX_."carrier_lang` SET `delay`='' WHERE `delay` IS NULL");
	$res = dbquery("UPDATE `"._DB_PREFIX_."carrier` SET `external_module_name`='' WHERE `external_module_name` IS NULL");
	$res = dbquery("UPDATE `"._DB_PREFIX_."carrier` SET `url`='' WHERE `url` IS NULL");
	$res = dbquery("UPDATE `"._DB_PREFIX_."cart_rule` SET `description`='' WHERE `description` IS NULL");
	$res = dbquery("UPDATE `"._DB_PREFIX_."cart` SET `gift_message`='' WHERE `gift_message` IS NULL");
	if(version_compare(_PS_VERSION_ , "8.0", ">="))
	  $res = dbquery("UPDATE `"._DB_PREFIX_."category_lang` SET `additional_description`='' WHERE `additional_description` IS NULL");
	$res = dbquery("UPDATE `"._DB_PREFIX_."category_lang` SET `description`='' WHERE `description` IS NULL");
	$res = dbquery("UPDATE `"._DB_PREFIX_."category_lang` SET `meta_description`='' WHERE `meta_description` IS NULL");
	$res = dbquery("UPDATE `"._DB_PREFIX_."category_lang` SET `meta_keywords`='' WHERE `meta_keywords` IS NULL");
	$res = dbquery("UPDATE `"._DB_PREFIX_."category_lang` SET `meta_title`='' WHERE `meta_title` IS NULL");
	$res = dbquery("UPDATE `"._DB_PREFIX_."cms_category_lang` SET `description`='' WHERE `description` IS NULL");
	$res = dbquery("UPDATE `"._DB_PREFIX_."cms_category_lang` SET `meta_description`='' WHERE `meta_description` IS NULL");
	$res = dbquery("UPDATE `"._DB_PREFIX_."cms_category_lang` SET `meta_keywords`='' WHERE `meta_keywords` IS NULL");
	$res = dbquery("UPDATE `"._DB_PREFIX_."cms_category_lang` SET `meta_title`='' WHERE `meta_title` IS NULL");
	$res = dbquery("UPDATE `"._DB_PREFIX_."cms_lang` SET `content`='' WHERE `content` IS NULL");
	if(version_compare(_PS_VERSION_ , "1.7.5", ">="))
	  $res = dbquery("UPDATE `"._DB_PREFIX_."cms_lang` SET `head_seo_title`='' WHERE `head_seo_title` IS NULL");
	$res = dbquery("UPDATE `"._DB_PREFIX_."cms_lang` SET `meta_description`='' WHERE `meta_description` IS NULL");
	$res = dbquery("UPDATE `"._DB_PREFIX_."cms_lang` SET `meta_keywords`='' WHERE `meta_keywords` IS NULL");
	$res = dbquery("UPDATE `"._DB_PREFIX_."configuration_kpi_lang` SET `date_upd`=CURRENT_TIMESTAMP WHERE `date_upd` IS NULL");
	$res = dbquery("UPDATE `"._DB_PREFIX_."configuration_kpi_lang` SET `value`='' WHERE `value` IS NULL");
	$res = dbquery("UPDATE `"._DB_PREFIX_."configuration_lang` SET `date_upd`=CURRENT_TIMESTAMP WHERE `date_upd` IS NULL");
	$res = dbquery("UPDATE `"._DB_PREFIX_."configuration_lang` SET `value`='' WHERE `value` IS NULL");
	$res = dbquery("UPDATE `"._DB_PREFIX_."connections_source` SET `http_referer`='' WHERE `http_referer` IS NULL");
	$res = dbquery("UPDATE `"._DB_PREFIX_."connections_source` SET `keywords`='' WHERE `keywords` IS NULL");
	$res = dbquery("UPDATE `"._DB_PREFIX_."connections` SET `http_referer`='' WHERE `http_referer` IS NULL");
	$res = dbquery("UPDATE `"._DB_PREFIX_."connections` SET `ip_address`='0' WHERE `ip_address` IS NULL");
	$res = dbquery("UPDATE `"._DB_PREFIX_."contact_lang` SET `description`='' WHERE `description` IS NULL");
	if(version_compare(_PS_VERSION_ , "1.7.7", ">="))
	  $res = dbquery("UPDATE `"._DB_PREFIX_."currency_lang` SET `pattern`='' WHERE `pattern` IS NULL");
	$res = dbquery("UPDATE `"._DB_PREFIX_."customer_message` SET `file_name`='' WHERE `file_name` IS NULL");
	$res = dbquery("UPDATE `"._DB_PREFIX_."customer_message` SET `id_employee`='0' WHERE `id_employee` IS NULL");
	$res = dbquery("UPDATE `"._DB_PREFIX_."customer_message` SET `ip_address`='' WHERE `ip_address` IS NULL");
	$res = dbquery("UPDATE `"._DB_PREFIX_."customer_message` SET `user_agent`='' WHERE `user_agent` IS NULL");
	$res = dbquery("UPDATE `"._DB_PREFIX_."customer_thread` SET `id_order`='0' WHERE `id_order` IS NULL");
	$res = dbquery("UPDATE `"._DB_PREFIX_."customer_thread` SET `id_product`='0' WHERE `id_product` IS NULL");
	$res = dbquery("UPDATE `"._DB_PREFIX_."customer` SET `birthday`='0000-00-00' WHERE `birthday` IS NULL");
	$res = dbquery("UPDATE `"._DB_PREFIX_."customer` SET `newsletter_date_add`='0000-00-00 00:00:00' WHERE `newsletter_date_add` IS NULL");
	if(version_compare(_PS_VERSION_ , "1.7.0", ">="))
	  $res = dbquery("UPDATE `"._DB_PREFIX_."customer` SET `reset_password_validity`='0000-00-00 00:00:00' WHERE `reset_password_validity` IS NULL");
	$res = dbquery("UPDATE `"._DB_PREFIX_."employee` SET `bo_css`='' WHERE `bo_css` IS NULL");
	if(version_compare(_PS_VERSION_ , "1.7.0", ">="))
	  $res = dbquery("UPDATE `"._DB_PREFIX_."employee` SET `reset_password_validity`='0000-00-00 00:00:00' WHERE `reset_password_validity` IS NULL");
	$res = dbquery("UPDATE `"._DB_PREFIX_."employee` SET `stats_compare_from`='0000-00-00' WHERE `stats_compare_from` IS NULL");
	$res = dbquery("UPDATE `"._DB_PREFIX_."employee` SET `stats_compare_to`='0000-00-00' WHERE `stats_compare_to` IS NULL");
	$res = dbquery("UPDATE `"._DB_PREFIX_."employee` SET `stats_date_from`=CURRENT_TIMESTAMP WHERE `stats_date_from` IS NULL");
	$res = dbquery("UPDATE `"._DB_PREFIX_."employee` SET `stats_date_to`=CURRENT_TIMESTAMP WHERE `stats_date_to` IS NULL");
	$res = dbquery("UPDATE `"._DB_PREFIX_."feature_value` SET `custom`='0' WHERE `custom` IS NULL");
	$res = dbquery("UPDATE `"._DB_PREFIX_."guest` SET `accept_language`='' WHERE `accept_language` IS NULL");
	$res = dbquery("UPDATE `"._DB_PREFIX_."guest` SET `adobe_director`='0' WHERE `adobe_director` IS NULL");
	$res = dbquery("UPDATE `"._DB_PREFIX_."guest` SET `adobe_flash`='0' WHERE `adobe_flash` IS NULL");
	$res = dbquery("UPDATE `"._DB_PREFIX_."guest` SET `apple_quicktime`='0' WHERE `apple_quicktime` IS NULL");
	$res = dbquery("UPDATE `"._DB_PREFIX_."guest` SET `id_customer`='0' WHERE `id_customer` IS NULL");
	$res = dbquery("UPDATE `"._DB_PREFIX_."guest` SET `id_operating_system`='0' WHERE `id_operating_system` IS NULL");
	$res = dbquery("UPDATE `"._DB_PREFIX_."guest` SET `id_web_browser`='0' WHERE `id_web_browser` IS NULL");
	$res = dbquery("UPDATE `"._DB_PREFIX_."guest` SET `real_player`='0' WHERE `real_player` IS NULL");
	$res = dbquery("UPDATE `"._DB_PREFIX_."guest` SET `screen_color`='0' WHERE `screen_color` IS NULL");
	$res = dbquery("UPDATE `"._DB_PREFIX_."guest` SET `screen_resolution_x`='0' WHERE `screen_resolution_x` IS NULL");
	$res = dbquery("UPDATE `"._DB_PREFIX_."guest` SET `screen_resolution_y`='0' WHERE `screen_resolution_y` IS NULL");
	$res = dbquery("UPDATE `"._DB_PREFIX_."guest` SET `sun_java`='0' WHERE `sun_java` IS NULL");
	$res = dbquery("UPDATE `"._DB_PREFIX_."guest` SET `windows_media`='0' WHERE `windows_media` IS NULL");
	$res = dbquery("UPDATE `"._DB_PREFIX_."hook` SET `description`='' WHERE `description` IS NULL");
	$res = dbquery("UPDATE `"._DB_PREFIX_."image_lang` SET `legend`='' WHERE `legend` IS NULL");
	$res = dbquery("UPDATE `"._DB_PREFIX_."log` SET `error_code`='0' WHERE `error_code` IS NULL");
	$res = dbquery("UPDATE `"._DB_PREFIX_."log` SET `id_employee`='0' WHERE `id_employee` IS NULL");
	if(version_compare(_PS_VERSION_ , "1.7.8", ">="))
	  $res = dbquery("UPDATE `"._DB_PREFIX_."log` SET `id_lang`='0' WHERE `id_lang` IS NULL");
	$res = dbquery("UPDATE `"._DB_PREFIX_."log` SET `object_id`='0' WHERE `object_id` IS NULL");
	$res = dbquery("UPDATE `"._DB_PREFIX_."log` SET `object_type`='' WHERE `object_type` IS NULL");
	$res = dbquery("UPDATE `"._DB_PREFIX_."manufacturer_lang` SET `description`='' WHERE `description` IS NULL");
	$res = dbquery("UPDATE `"._DB_PREFIX_."manufacturer_lang` SET `meta_description`='' WHERE `meta_description` IS NULL");
	$res = dbquery("UPDATE `"._DB_PREFIX_."manufacturer_lang` SET `meta_keywords`='' WHERE `meta_keywords` IS NULL");
	$res = dbquery("UPDATE `"._DB_PREFIX_."manufacturer_lang` SET `meta_title`='' WHERE `meta_title` IS NULL");
	$res = dbquery("UPDATE `"._DB_PREFIX_."manufacturer_lang` SET `short_description`='' WHERE `short_description` IS NULL");
	$res = dbquery("UPDATE `"._DB_PREFIX_."message` SET `id_employee`='0' WHERE `id_employee` IS NULL");
	$res = dbquery("UPDATE `"._DB_PREFIX_."meta_lang` SET `description`='' WHERE `description` IS NULL");
	$res = dbquery("UPDATE `"._DB_PREFIX_."meta_lang` SET `keywords`='' WHERE `keywords` IS NULL");
	$res = dbquery("UPDATE `"._DB_PREFIX_."meta_lang` SET `title`='' WHERE `title` IS NULL");
	$res = dbquery("UPDATE `"._DB_PREFIX_."order_carrier` SET `id_order_invoice`='0' WHERE `id_order_invoice` IS NULL");
	$res = dbquery("UPDATE `"._DB_PREFIX_."order_carrier` SET `shipping_cost_tax_excl`='0' WHERE `shipping_cost_tax_excl` IS NULL");
	$res = dbquery("UPDATE `"._DB_PREFIX_."order_carrier` SET `shipping_cost_tax_incl`='0' WHERE `shipping_cost_tax_incl` IS NULL");
	$res = dbquery("UPDATE `"._DB_PREFIX_."order_carrier` SET `tracking_number`='' WHERE `tracking_number` IS NULL");
	$res = dbquery("UPDATE `"._DB_PREFIX_."order_carrier` SET `weight`='0' WHERE `weight` IS NULL");
	$res = dbquery("UPDATE `"._DB_PREFIX_."order_detail` SET `download_deadline`='0000-00-00 00:00:00' WHERE `download_deadline` IS NULL");
	$res = dbquery("UPDATE `"._DB_PREFIX_."order_detail` SET `download_hash`='' WHERE `download_hash` IS NULL");
	$res = dbquery("UPDATE `"._DB_PREFIX_."order_detail` SET `id_order_invoice`='0' WHERE `id_order_invoice` IS NULL");
	$res = dbquery("UPDATE `"._DB_PREFIX_."order_detail` SET `product_attribute_id`='0' WHERE `product_attribute_id` IS NULL");
	$res = dbquery("UPDATE `"._DB_PREFIX_."order_detail` SET `product_ean13`='' WHERE `product_ean13` IS NULL");
	if(version_compare(_PS_VERSION_ , "1.7.0", ">="))
	  $res = dbquery("UPDATE `"._DB_PREFIX_."order_detail` SET `product_isbn`='' WHERE `product_isbn` IS NULL");
	if(version_compare(_PS_VERSION_ , "1.7.7", ">="))
	  $res = dbquery("UPDATE `"._DB_PREFIX_."order_detail` SET `product_mpn`='' WHERE `product_mpn` IS NULL");
	$res = dbquery("UPDATE `"._DB_PREFIX_."order_detail` SET `product_reference`='' WHERE `product_reference` IS NULL");
	$res = dbquery("UPDATE `"._DB_PREFIX_."order_detail` SET `product_supplier_reference`='' WHERE `product_supplier_reference` IS NULL");
	$res = dbquery("UPDATE `"._DB_PREFIX_."order_detail` SET `product_upc`='' WHERE `product_upc` IS NULL");
	$res = dbquery("UPDATE `"._DB_PREFIX_."order_invoice` SET `delivery_date`='0000-00-00 00:00:00' WHERE `delivery_date` IS NULL");
	$res = dbquery("UPDATE `"._DB_PREFIX_."order_invoice` SET `note`='' WHERE `note` IS NULL");
	if(version_compare(_PS_VERSION_ , "1.6.1.0", ">="))
	  $res = dbquery("UPDATE `"._DB_PREFIX_."order_invoice` SET `shop_address`='' WHERE `shop_address` IS NULL");
	$res = dbquery("UPDATE `"._DB_PREFIX_."order_payment` SET `card_brand`='' WHERE `card_brand` IS NULL");
	$res = dbquery("UPDATE `"._DB_PREFIX_."order_payment` SET `card_expiration`='' WHERE `card_expiration` IS NULL");
	$res = dbquery("UPDATE `"._DB_PREFIX_."order_payment` SET `card_holder`='' WHERE `card_holder` IS NULL");
	$res = dbquery("UPDATE `"._DB_PREFIX_."order_payment` SET `card_number`='' WHERE `card_number` IS NULL");
	$res = dbquery("UPDATE `"._DB_PREFIX_."order_payment` SET `transaction_id`='' WHERE `transaction_id` IS NULL");
	$res = dbquery("UPDATE `"._DB_PREFIX_."order_return_state` SET `color`='' WHERE `color` IS NULL");
	$res = dbquery("UPDATE `"._DB_PREFIX_."order_slip_detail` SET `amount_tax_excl`='0' WHERE `amount_tax_excl` IS NULL");
	$res = dbquery("UPDATE `"._DB_PREFIX_."order_slip_detail` SET `amount_tax_incl`='0' WHERE `amount_tax_incl` IS NULL");
	if(version_compare(_PS_VERSION_ , "1.6.0.10", ">="))
	{ $res = dbquery("UPDATE `"._DB_PREFIX_."order_slip_detail` SET `total_price_tax_excl`='0' WHERE `total_price_tax_excl` IS NULL");
	  $res = dbquery("UPDATE `"._DB_PREFIX_."order_slip_detail` SET `total_price_tax_incl`='0' WHERE `total_price_tax_incl` IS NULL");
	  $res = dbquery("UPDATE `"._DB_PREFIX_."order_slip_detail` SET `unit_price_tax_excl`='0' WHERE `unit_price_tax_excl` IS NULL");
	  $res = dbquery("UPDATE `"._DB_PREFIX_."order_slip_detail` SET `unit_price_tax_incl`='0' WHERE `unit_price_tax_incl` IS NULL");
	}
	if(version_compare(_PS_VERSION_ , "1.6.0.10", ">="))
	{ $res = dbquery("UPDATE `"._DB_PREFIX_."order_slip` SET `total_products_tax_excl`='0' WHERE `total_products_tax_excl` IS NULL");
	  $res = dbquery("UPDATE `"._DB_PREFIX_."order_slip` SET `total_products_tax_incl`='0' WHERE `total_products_tax_incl` IS NULL");
	  $res = dbquery("UPDATE `"._DB_PREFIX_."order_slip` SET `total_shipping_tax_excl`='0' WHERE `total_shipping_tax_excl` IS NULL");
	  $res = dbquery("UPDATE `"._DB_PREFIX_."order_slip` SET `total_shipping_tax_incl`='0' WHERE `total_shipping_tax_incl` IS NULL");
	}
	$res = dbquery("UPDATE `"._DB_PREFIX_."order_state` SET `color`='' WHERE `color` IS NULL");
	$res = dbquery("UPDATE `"._DB_PREFIX_."order_state` SET `module_name`='' WHERE `module_name` IS NULL");
	$res = dbquery("UPDATE `"._DB_PREFIX_."orders` SET `gift_message`='' WHERE `gift_message` IS NULL");
	if(version_compare(_PS_VERSION_ , "1.7.8", ">="))
	  $res = dbquery("UPDATE `"._DB_PREFIX_."orders` SET `note`='' WHERE `note` IS NULL");
	if(version_compare(_PS_VERSION_ , "8.1", ">="))
	{ $res = dbquery("UPDATE `"._DB_PREFIX_."product_attribute_lang` SET `available_later`='' WHERE `available_later` IS NULL");
	  $res = dbquery("UPDATE `"._DB_PREFIX_."product_attribute_lang` SET `available_now`='' WHERE `available_now` IS NULL");
	}
	$res = dbquery("UPDATE `"._DB_PREFIX_."product_attribute_shop` SET `available_date`='0000-00-00' WHERE `available_date` IS NULL");
	if(version_compare(_PS_VERSION_ , "1.7.3", ">="))
	  $res = dbquery("UPDATE `"._DB_PREFIX_."product_attribute_shop` SET `low_stock_threshold`='0' WHERE `low_stock_threshold` IS NULL");
	$res = dbquery("UPDATE `"._DB_PREFIX_."product_attribute` SET `available_date`='0000-00-00' WHERE `available_date` IS NULL");
	$res = dbquery("UPDATE `"._DB_PREFIX_."product_attribute` SET `ean13`='' WHERE `ean13` IS NULL");
	if(version_compare(_PS_VERSION_ , "1.7.0", ">="))
	  $res = dbquery("UPDATE `"._DB_PREFIX_."product_attribute` SET `isbn`='' WHERE `isbn` IS NULL");
	if(version_compare(_PS_VERSION_ , "1.7.3", ">="))
	  $res = dbquery("UPDATE `"._DB_PREFIX_."product_attribute` SET `low_stock_threshold`='0' WHERE `low_stock_threshold` IS NULL");
	if(version_compare(_PS_VERSION_ , "1.7.7", ">="))
	$res = dbquery("UPDATE `"._DB_PREFIX_."product_attribute` SET `mpn`='' WHERE `mpn` IS NULL");
	$res = dbquery("UPDATE `"._DB_PREFIX_."product_attribute` SET `reference`='' WHERE `reference` IS NULL");
	$res = dbquery("UPDATE `"._DB_PREFIX_."product_attribute` SET `supplier_reference`='' WHERE `supplier_reference` IS NULL");
	$res = dbquery("UPDATE `"._DB_PREFIX_."product_attribute` SET `upc`='' WHERE `upc` IS NULL");
	$res = dbquery("UPDATE `"._DB_PREFIX_."product_download` SET `date_expiration`='0000-00-00 00:00:00' WHERE `date_expiration` IS NULL");
	$res = dbquery("UPDATE `"._DB_PREFIX_."product_download` SET `nb_days_accessible`='0' WHERE `nb_days_accessible` IS NULL");
	$res = dbquery("UPDATE `"._DB_PREFIX_."product_lang` SET `available_later`='' WHERE `available_later` IS NULL");
	$res = dbquery("UPDATE `"._DB_PREFIX_."product_lang` SET `available_now`='' WHERE `available_now` IS NULL");
	if(version_compare(_PS_VERSION_ , "1.7.3", ">="))
	{ $res = dbquery("UPDATE `"._DB_PREFIX_."product_lang` SET `delivery_in_stock`='' WHERE `delivery_in_stock` IS NULL");
	  $res = dbquery("UPDATE `"._DB_PREFIX_."product_lang` SET `delivery_out_stock`='' WHERE `delivery_out_stock` IS NULL");
	}
	$res = dbquery("UPDATE `"._DB_PREFIX_."product_lang` SET `description_short`='' WHERE `description_short` IS NULL");
	$res = dbquery("UPDATE `"._DB_PREFIX_."product_lang` SET `description`='' WHERE `description` IS NULL");
	$res = dbquery("UPDATE `"._DB_PREFIX_."product_lang` SET `meta_description`='' WHERE `meta_description` IS NULL");
	$res = dbquery("UPDATE `"._DB_PREFIX_."product_lang` SET `meta_keywords`='' WHERE `meta_keywords` IS NULL");
	$res = dbquery("UPDATE `"._DB_PREFIX_."product_lang` SET `meta_title`='' WHERE `meta_title` IS NULL");
	$res = dbquery("UPDATE `"._DB_PREFIX_."product_sale` SET `date_upd`=CURRENT_TIMESTAMP WHERE `date_upd` IS NULL");
	$res = dbquery("UPDATE `"._DB_PREFIX_."product_shop` SET `available_date`='0000-00-00' WHERE `available_date` IS NULL");
	$res = dbquery("UPDATE `"._DB_PREFIX_."product_shop` SET `cache_default_attribute`='0' WHERE `cache_default_attribute` IS NULL");
	$res = dbquery("UPDATE `"._DB_PREFIX_."product_shop` SET `id_category_default`='0' WHERE `id_category_default` IS NULL");
	if(version_compare(_PS_VERSION_ , "1.7.3", ">="))
	  $res = dbquery("UPDATE `"._DB_PREFIX_."product_shop` SET `low_stock_threshold`='0' WHERE `low_stock_threshold` IS NULL");
	$res = dbquery("UPDATE `"._DB_PREFIX_."product_shop` SET `unity`='' WHERE `unity` IS NULL");
	$res = dbquery("UPDATE `"._DB_PREFIX_."product_supplier` SET `product_supplier_reference`='' WHERE `product_supplier_reference` IS NULL");
	$res = dbquery("UPDATE `"._DB_PREFIX_."product` SET `available_date`='0000-00-00' WHERE `available_date` IS NULL");
	$res = dbquery("UPDATE `"._DB_PREFIX_."product` SET `cache_default_attribute`='0' WHERE `cache_default_attribute` IS NULL");
	$res = dbquery("UPDATE `"._DB_PREFIX_."product` SET `ean13`='' WHERE `ean13` IS NULL");
	$res = dbquery("UPDATE `"._DB_PREFIX_."product` SET `id_category_default`='0' WHERE `id_category_default` IS NULL");
	$res = dbquery("UPDATE `"._DB_PREFIX_."product` SET `id_manufacturer`='0' WHERE `id_manufacturer` IS NULL");
	$res = dbquery("UPDATE `"._DB_PREFIX_."product` SET `id_supplier`='0' WHERE `id_supplier` IS NULL");
	if(version_compare(_PS_VERSION_ , "1.7.0", ">="))
	  $res = dbquery("UPDATE `"._DB_PREFIX_."product` SET `isbn`='' WHERE `isbn` IS NULL");
	if(version_compare(_PS_VERSION_ , "1.7.3", ">="))
	  $res = dbquery("UPDATE `"._DB_PREFIX_."product` SET `low_stock_threshold`='0' WHERE `low_stock_threshold` IS NULL");
	if(version_compare(_PS_VERSION_ , "1.7.7", ">="))
	  $res = dbquery("UPDATE `"._DB_PREFIX_."product` SET `mpn`='' WHERE `mpn` IS NULL");
	$res = dbquery("UPDATE `"._DB_PREFIX_."product` SET `reference`='' WHERE `reference` IS NULL");
	$res = dbquery("UPDATE `"._DB_PREFIX_."product` SET `supplier_reference`='' WHERE `supplier_reference` IS NULL");
	$res = dbquery("UPDATE `"._DB_PREFIX_."product` SET `unity`='' WHERE `unity` IS NULL");
	$res = dbquery("UPDATE `"._DB_PREFIX_."product` SET `upc`='' WHERE `upc` IS NULL");
	$res = dbquery("UPDATE `"._DB_PREFIX_."risk` SET `color`='' WHERE `color` IS NULL");
	$res = dbquery("UPDATE `"._DB_PREFIX_."stock` SET `ean13`='' WHERE `ean13` IS NULL");
	if(version_compare(_PS_VERSION_ , "1.7.0", ">="))
	  $res = dbquery("UPDATE `"._DB_PREFIX_."stock` SET `isbn`='' WHERE `isbn` IS NULL");
	if(version_compare(_PS_VERSION_ , "1.7.7", ">="))
	  $res = dbquery("UPDATE `"._DB_PREFIX_."stock` SET `mpn`='' WHERE `mpn` IS NULL");
	$res = dbquery("UPDATE `"._DB_PREFIX_."stock` SET `upc`='' WHERE `upc` IS NULL");
	if(version_compare(_PS_VERSION_ , "1.7.3", ">="))
	{ $res = dbquery("UPDATE `"._DB_PREFIX_."store_lang` SET `address2`='' WHERE `address2` IS NULL");
	  $res = dbquery("UPDATE `"._DB_PREFIX_."store_lang` SET `note`='' WHERE `note` IS NULL");
	}
	$res = dbquery("UPDATE `"._DB_PREFIX_."store` SET `email`='' WHERE `email` IS NULL");
	$res = dbquery("UPDATE `"._DB_PREFIX_."store` SET `fax`='' WHERE `fax` IS NULL");
	$res = dbquery("UPDATE `"._DB_PREFIX_."store` SET `id_state`='0' WHERE `id_state` IS NULL");
	$res = dbquery("UPDATE `"._DB_PREFIX_."store` SET `phone`='' WHERE `phone` IS NULL");
	$res = dbquery("UPDATE `"._DB_PREFIX_."supplier_lang` SET `description`='' WHERE `description` IS NULL");
	$res = dbquery("UPDATE `"._DB_PREFIX_."supplier_lang` SET `meta_description`='' WHERE `meta_description` IS NULL");
	$res = dbquery("UPDATE `"._DB_PREFIX_."supplier_lang` SET `meta_keywords`='' WHERE `meta_keywords` IS NULL");
	$res = dbquery("UPDATE `"._DB_PREFIX_."supplier_lang` SET `meta_title`='' WHERE `meta_title` IS NULL");
	$res = dbquery("UPDATE `"._DB_PREFIX_."supply_order_detail` SET `ean13`='' WHERE `ean13` IS NULL");
	if(version_compare(_PS_VERSION_ , "1.7.0", ">="))
	  $res = dbquery("UPDATE `"._DB_PREFIX_."supply_order_detail` SET `isbn`='' WHERE `isbn` IS NULL");
	if(version_compare(_PS_VERSION_ , "1.7.7", ">="))
	  $res = dbquery("UPDATE `"._DB_PREFIX_."supply_order_detail` SET `mpn`='' WHERE `mpn` IS NULL");
	$res = dbquery("UPDATE `"._DB_PREFIX_."supply_order_detail` SET `upc`='' WHERE `upc` IS NULL");
	$res = dbquery("UPDATE `"._DB_PREFIX_."supply_order_state` SET `color`='' WHERE `color` IS NULL");
	$res = dbquery("UPDATE `"._DB_PREFIX_."supply_order` SET `date_delivery_expected`='0000-00-00 00:00:00' WHERE `date_delivery_expected` IS NULL");
	$res = dbquery("UPDATE `"._DB_PREFIX_."warehouse_product_location` SET `location`='' WHERE `location` IS NULL");
	$res = dbquery("UPDATE `"._DB_PREFIX_."warehouse` SET `reference`='' WHERE `reference` IS NULL");
	$res = dbquery("UPDATE `"._DB_PREFIX_."webservice_account` SET `description`='' WHERE `description` IS NULL");
}
  
  /* table ps_currency_lang was introduced in PS 1.7.6. Missing its values causes a "missing locale" error. */
  /* simultaneously a field "precision" was introduced into the ps_currency table. */
  /* here we handle such cases as good as possible */
  if (_PS_VERSION_ >= "1.7.6")
  { $qquery = 'SELECT value FROM '._DB_PREFIX_.'configuration WHERE name = "COPYSHOPDATA_CURRENCIES"'; 
    $qres = dbxquery($conn, $qquery);
	$qrow = mysqli_fetch_assoc($qres);
	$currencyblock = json_decode($qrow["value"], true);
  
    if(!in_array("currency_lang", $oldtables) && in_array("currency_lang", $newtables))
	{ echo "<br>Truncating currency_lang<br>";
	  $query = "TRUNCATE "._DB_PREFIX_."currency_lang"; 
      $res = dbxquery($conn, $query);
	}	
    $query = "SELECT * FROM "._DB_PREFIX_."currency"; 
    $res = dbxquery($conn, $query);
	$everythingok = true;
  	$symbols = array("ALL"=>"L","CAD"=>"CA$","CHF"=>"fr","CZK"=>"Kč","EUR"=>"€","GBP"=>"£",
	"HRK"=>"kn","HUF"=>"Ft","IDR"=>"Rp","ILS"=>"₪","INR"=>"₹","IRR"=>"﷼","MXN"=>"MX$","NOK"=>"kr",
	"PLN"=>"zł","RON"=>"L","RSD"=>"din","RUB"=>"₽","SEK"=>"kr","TRY"=>"₺","USD"=>"$");  /* ISO 4217-code */
    while($row = mysqli_fetch_assoc($res))
	{ 
	  if(version_compare($oldversion , "1.7.0", "<"))
	  { $squery = "SELECT name, sign, iso_code FROM "._OLD_PREFIX_."currency WHERE id_currency=".$row["id_currency"];
	    $sres = dbxquery($oldconn, $squery); 
		$srow = mysqli_fetch_assoc($sres);
	  }
	  else
	    $srow = array();
		
	  $lquery = "SELECT * FROM "._DB_PREFIX_."currency_lang";
	  $lquery .= " WHERE id_currency=".$row["id_currency"]." AND name!=''";
	  $lres = dbxquery($conn, $lquery); 
	  if(mysqli_num_rows($lres) == 0)
	  { if(isset($srow["name"]) && ($srow["name"] != ""))
		{ $name = $srow["name"];
		  $sign = $srow["sign"];  /* the field is named sign in 1.6 and symbol in 1.7 */
		}
		else if(isset($symbols[$row["iso_code"]]))
		{ $sign = $symbols[$row["iso_code"]];
		  $name = $row["iso_code"];
		}
		else
		  $sign = $name = $row["iso_code"];
	  }
	  else 
	  { $lrow = mysqli_fetch_assoc($lres);
	    $name = $lrow["name"];
		$sign = $lrow["symbol"];
	  }

	  foreach($newlang_list AS $lang)
	  { $lquery = "SELECT * FROM "._DB_PREFIX_."currency_lang";
	    $lquery .= " WHERE id_currency=".$row["id_currency"]." AND id_lang=".$lang;
	  	$lres = dbxquery($conn, $lquery); 
	    if(mysqli_num_rows($lres) == 0)
	    { if(isset($currencyblock[$row["iso_code"]][$lang]))
		  { if($currencyblock[$row["iso_code"]][$lang]["name"] != "")
		      $name = $currencyblock[$row["iso_code"]][$lang]["name"];
			if($currencyblock[$row["iso_code"]][$lang]["symbol"] != "") 
		      $sign = $currencyblock[$row["iso_code"]][$lang]["symbol"];
		  }
		  $everythingok = false;
		  $iquery = 'INSERT INTO '._DB_PREFIX_.'currency_lang SET id_currency="'.$row["id_currency"];
		  $iquery .= '",id_lang="'.$lang.'",name="'.$name.'", symbol="'.$sign.'"';
	  	  $ires = dbxquery($conn, $iquery); 
		}
		else
		{ $lrow = mysqli_fetch_assoc($lres);
		  if($lrow["name"] == "")
		  { $everythingok = false;
		    if(isset($currencyblock[$row["iso_code"]][$lang]))
		    { if($currencyblock[$row["iso_code"]][$lang]["name"] != "")
		        $name = $currencyblock[$row["iso_code"]][$lang]["name"];
			  if($currencyblock[$row["iso_code"]][$lang]["symbol"] != "") 
		        $sign = $currencyblock[$row["iso_code"]][$lang]["symbol"];
		    }
		    $uquery = "UPDATE "._DB_PREFIX_."currency_lang SET name='".$name."',symbol='".$sign."'";
			$uquery .= " WHERE id_currency=".$row["id_currency"]." AND id_lang=".$lang;
			$ures = dbxquery($conn, $uquery); 
		  }
		}
	  }
      if ($oldversion < "1.7.6")
	  { $nquery = 'SELECT value FROM '._OLD_PREFIX_.'configuration WHERE name = "PS_PRICE_DISPLAY_PRECISION"'; 
        $nres = dbxquery($oldconn, $nquery);
        if(mysqli_num_rows($nres) > 0)
		  list($precision) = mysqli_fetch_row($nres);
	    else
		  $precision = 2;
	    $uquery = 'UPDATE '._DB_PREFIX_.'currency SET `precision`='.intval($precision); /* precision is a reserved keyword and must be quoted */
        $ures = dbxquery($conn, $uquery);
	  }
	}
	if(!$everythingok)
	  echo "<h2>Currency translations were added. Check the symbols.</h2>";
  }
  
  /* an active field for category_shop exists only in newer Thirty Bees versions */
  $hasactiveshopfield = false;
  $res = dbxquery($conn, 'SHOW COLUMNS FROM '._DB_PREFIX_.'category_shop WHERE field="active"');
  if(mysqli_num_rows($res) > 0)
  { $ores = dbxquery($oldconn, 'SHOW COLUMNS FROM '._OLDDB_PREFIX_.'category_shop WHERE field="active"');
    if(mysqli_num_rows($ores) == 0)
	{ $query = "UPDATE "._DB_PREFIX_."category_shop cs";
	  $query .= " LEFT JOIN "._DB_PREFIX_."category c ON c.id_category=cs.id_category";
	  $query .= " SET cs.active=c.active";
	  $res = dbxquery($conn, $query);
	}
  }  
  
  emptyCache();
  echo '<p>You now need to switch modules on and off to match your old shop. 
  The <a href="copy_shopdata_modulemap.php" target="_blank">module map</a> can help with that by providing an overview.<br>';
  echo "<p>** finished **";
  mysqli_close($conn);
