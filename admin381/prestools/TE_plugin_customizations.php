<?php 
	$custcount = intval($GLOBALS['custom_count'.$x]);

    for($i=0; $i < $custcount; $i++)
	{ $status = $GLOBALS['custom_status'.$i.'s'.$x];
	  $custom_id = $GLOBALS['custom_id'.$i.'s'.$x];
	  $shops = explode(",",$shoplist); /* shops selected in product-edit for which command is valid */
	  
	  /* we will only delete ps_customization_field when the command is for all shops in which the 
	     customization is active in the ps_customization_field_lang table */
	  if($status == "deleted")
	  { if($custom_id == "") continue;
	    $myshoparr = explode(",",$myshops); /* all shops active for this installation */
		$forgottenshops = array_diff($myshoparr,$shops);
		$delete_all = true;
		if(sizeof($forgottenshops) > 0)
		{ $query = 'SELECT * FROM '. _DB_PREFIX_.'customization_field_lang WHERE id_customization_field="'.$custom_id.'" AND id_shop IN ('.implode(",",$forgottenshops).')'; 
		  $res = dbquery($query);
		  if(mysqli_num_rows($res) > 0)
			$delete_all = false;
		}
		
		if($delete_all)
		{ if (version_compare(_PS_VERSION_ , "1.7.3", ">="))
	      { $query = 'UPDATE '. _DB_PREFIX_.'customization_field SET is_deleted=1';
		    $query .= ' WHERE id_product = "'.$id_product.'" AND id_customization_field="'.$custom_id.'"';
	        $res = dbquery($query);
		  }
	      else
		  { $query = 'DELETE FROM '. _DB_PREFIX_.'customization_field WHERE id_product = "'.$id_product.'" AND id_customization_field="'.$custom_id.'"';
	        $res = dbquery($query);
		    $query = 'DELETE FROM '. _DB_PREFIX_.'customization_field_lang WHERE id_customization_field="'.$custom_id.'"';
	        $res = dbquery($query);
		  }
		}
		else
		{ foreach($shops AS $shop)
		  { $query = 'DELETE FROM '. _DB_PREFIX_.'customization_field_lang WHERE id_customization_field="'.$custom_id.'" AND id_shop='.$shop;
		    $res = dbquery($query); 
		  }		
		}
		continue;
	  }
	  
	  if(isset($GLOBALS['custom_req'.$i.'s'.$x])) /* if set value="on" */
	      $custom_req = '1';
		else
	      $custom_req = '0';
		if($GLOBALS['custom_type'.$i.'s'.$x] == 'textfield')
	      $custom_type = '1';
		else
	      $custom_type = '0';
	  
	  if($status == "update") 
	  { 
	    $query = 'UPDATE '. _DB_PREFIX_.'customization_field SET required="'.$custom_req.'",type="'.$custom_type.'"';
		$query .= ' WHERE id_product = "'.$id_product.'" AND id_customization_field="'.$custom_id.'"';
	    $res = dbquery($query);
	    foreach($languages AS $language)
		{ $name = $GLOBALS['custom_name'.$language.'x'.$i.'s'.$x];
		  $shops = explode(",",$shoplist);
		  foreach($shops AS $shop)
		  { $query = 'SELECT * FROM '. _DB_PREFIX_.'customization_field_lang WHERE id_customization_field="'.$custom_id.'" AND id_lang='.$language.' AND id_shop='.$shop; 
		    $res = dbquery($query);
			if(mysqli_num_rows($res) > 0)
			{ $query = 'UPDATE '. _DB_PREFIX_.'customization_field_lang SET name="'.mysqli_real_escape_string($conn, $name).'"';
		      $query .= ' WHERE id_customization_field="'.$custom_id.'" AND id_lang='.$language.' AND id_shop='.$shop;
			}
			else
			{ $query = 'INSERT INTO '. _DB_PREFIX_.'customization_field_lang SET name="'.mysqli_real_escape_string($conn, $name).'", id_customization_field="'.$custom_id.'", id_lang='.$language.', id_shop='.$shop;
			}
	        $res = dbquery($query);
		  }
		}
	  }
	  else if($status == "new") 
	  { $query = 'INSERT INTO '. _DB_PREFIX_.'customization_field SET id_product = "'.$id_product.'"';
	    $query .= ',required="'.$custom_req.'",type="'.$custom_type.'",is_module=0';
		if (version_compare(_PS_VERSION_ , "1.7.3", ">="))
		  $query .= ',is_deleted=0';
	    $res = dbquery($query);
		$custom_id = mysqli_insert_id($conn);
	    foreach($languages AS $language)
		{ 
		  $name = $GLOBALS['custom_name'.$language.'x'.$i.'s'.$x];
		  foreach($shops AS $shop)
		  { $query = 'INSERT INTO '. _DB_PREFIX_.'customization_field_lang SET name="'.mysqli_real_escape_string($conn, $name).'", id_customization_field="'.$custom_id.'", id_lang='.$language.', id_shop='.$shop;
	        $res = dbquery($query);
		  }
		}	  
	  }
	}
