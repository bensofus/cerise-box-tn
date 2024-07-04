<?php 
  error_reporting(E_ALL); 
if(!include 'approve.php') die( "approve.php was not found!");
if(!include 'ps_sourced_code.php') die( "ps_sourced_code.php was not found!");

$id_product=$id_lang=$id_shop =0;
if (isset($_GET['id_product']))
{ $id_product = intval($_GET['id_product']);
  if (isset($_GET['id_lang'])) $id_lang = intval($_GET['id_lang']);
  if (isset($_GET['id_shop'])) $id_shop = intval($_GET['id_shop']);
}
else if (isset($_GET['id_product']))
if (isset($_POST['id_product']))
{ $id_product = intval($_GET['id_product']);
  if (isset($_POST['id_lang'])) $id_lang = intval($_GET['id_lang']);
  if (isset($_POST['id_shop'])) $id_shop = intval($_GET['id_shop']);
}
if($id_lang==0) 
  $id_lang = get_configuration_value('PS_LANG_DEFAULT'); 
if($id_shop==0) 
  $id_shop = get_configuration_value('PS_SHOP_DEFAULT'); 

/* section 2: page header */
?><!DOCTYPE html> 
<html lang="en"><head><meta charset="utf-8">
<title>Prestashop Product Search Words</title>
<link rel="stylesheet" href="style1.css" type="text/css" />
<style type="text/css">
</style>
<script type="text/javascript">
function reindexate()
{ var prod = selectform.id_product.value;
  tank.location = "utilities-proc.php?subject=indexate&id_product="+prod;
}
</script>
<script type="text/javascript" src="utils8.js"></script>
<script type="text/javascript" src="sorter.js"></script>
</head>
<body>
<?php print_menubar(); 
  echo '<table width="100%"><tr><td  class="headline" style="width:90%">
  <a href="prodwords.php">Product Search Words</a><br>';
  echo 'This page shows the search words that the database contains for a product:<br>
  - On the left side you see the words (and their weights) as stored in the ps_search_word and ps_search_index tables. These tables are consulted when you do a search in your shop.<br>
 - On the right side you see the same values as calculated on the spot from the name, description and other relevant fields of the product.<br>
  You can click on the column headers of the tables to sort them.<br>
  You can (re)index products in the Utilities page.<br>
  Note that the search words are shop and language specific.</td>
<td align=right rowspan=3><iframe name="tank" id="tank" height="95" width="230"></iframe></td></tr></table>';
  echo '<form name="selectform">';
  if($id_product==0) $id_product = "";
  echo 'id_product <input name=id_product value="'.$id_product.'" size=3> &nbsp; &nbsp; ';
  
  echo 'shop <select name=id_shop>';
	$query=" select id_shop,name from ". _DB_PREFIX_."shop ORDER BY id_shop";
	$res=dbquery($query);
	while ($row=mysqli_fetch_array($res)) 
	{ $selected='';
      if ($row['id_shop']==$id_shop) 
		$selected=' selected="selected" ';
      echo '<option  value="'.$row['id_shop'].'" '.$selected.'>'.$row['id_shop']."-".$row['name'].'</option>';
	}
  echo '</select> &nbsp; &nbsp; ';

  echo 'language <select name=id_lang>';
  $query = "SELECT id_lang, name, language_code, iso_code FROM ". _DB_PREFIX_."lang";
  $res=dbquery($query);
  while ($row=mysqli_fetch_array($res)) 
  { $selected='';
    if ($row['id_lang']==$id_lang) 
      $selected=' selected="selected" ';
    echo '<option  value="'.$row['id_lang'].'" '.$selected.'>'.$row['name'].'</option>';
  }
  echo '</select> &nbsp; &nbsp; ';
  echo '<input type=submit> &nbsp; &nbsp; <button onclick="reindexate(); return false;">Re-index</button><p>';
  
  if(($id_product == 0) || ($id_shop == 0) || ($id_lang == 0))
  { include "footer1.php";
    echo '</body></html>';
	return;
  }
  
  $query = 'SELECT name from '._DB_PREFIX_.'product_lang WHERE id_product='.$id_product.' AND id_shop='.$id_shop.' AND id_lang='.$id_lang;
  $res=dbquery($query);
  if(mysqli_num_rows($res) == 0)
  { $query = 'SELECT visibility,active from '._DB_PREFIX_.'product WHERE id_product='.$id_product;
    $res=dbquery($query);
    if(mysqli_num_rows($res) == 0)
      echo "<p>There is no product with this id";
    else
      echo "<p>This product doesn't exist for this shop";
    include "footer1.php";
    echo '</body></html>';
	return;
  }
  $row=mysqli_fetch_array($res);
  echo "<a href='product-solo.php?id_product=".$id_product."&id_lang=".$id_lang."&id_shop=".$id_shop."' target=_blank>";
  echo $row["name"].'</a>';
  
  $noindex=false;
  $query = 'SELECT visibility,active from '._DB_PREFIX_.'product_shop WHERE id_product='.$id_product.' AND id_shop='.$id_shop;
  $res=dbquery($query);
  $row=mysqli_fetch_array($res);
  if(!in_array($row["visibility"], array("both", "search")))
  { echo "<p>This product is not indexed because it has visibility=".$row["visibility"];
    $noindex=true;
  }
  if($row["active"]==0)
  { echo "<p>This product is not indexed because it is not active";
    $noindex=true;
  }
  if($noindex)
  { include "footer1.php";
    echo '</body></html>';
	return;
  }
  
  echo '<table class="triplemain"><tr><td>Search words</td><td style="width:10px;">&nbsp;</td><td>Name, description, etc</td></tr><tr><td>';
  echo '<div id="testdiv"><table id="Maintable" class="triplemain"><colgroup id="mycolgroup">';
  echo "<col id='col1'></col><col id='col2'></col><col id='col3'></col>";
  echo "</colgroup><thead><tr>";
  echo '<th><a href="" onclick="this.blur(); return sortTable(\'offTblBdy\', \'0\', false);" fieldname="id_word" >id_word</a></th>';
  echo '<th><a href="" onclick="this.blur(); return sortTable(\'offTblBdy\', \'1\', false);" fieldname="id" >word</a></th>';
  echo '<th><a href="" onclick="this.blur(); return sortTable(\'offTblBdy\', \'2\', false);" fieldname="id" >weight</a></th>';
  echo "</tr></thead ><tbody id='offTblBdy'>"; 
  
  $pquery = 'SELECT si.id_word, weight, word FROM '._DB_PREFIX_.'search_index si';
  $pquery .= ' LEFT JOIN '._DB_PREFIX_.'search_word sw ON (sw.id_word=si.id_word)';
  $pquery .= ' WHERE si.id_product = '.$id_product.' AND sw.id_shop = '.$id_shop.' AND sw.id_lang = '.$id_lang;
  $pquery .= ' ORDER BY word';
  $pres = dbquery($pquery);
  while($prow = mysqli_fetch_assoc($pres))
  { echo '<tr><td>'.$prow["id_word"].'</td><td>'.$prow["word"].'</td><td>'.$prow["weight"].'</td></tr>';
  }
  echo '</table>';
  echo '</td><td></td><td>';

  $weights = array(
    'pname' => get_configuration_value('PS_SEARCH_WEIGHT_PNAME'),
    'reference' => get_configuration_value('PS_SEARCH_WEIGHT_REF'),
	'pa_reference' => get_configuration_value('PS_SEARCH_WEIGHT_REF'),
	'supplier_references' => get_configuration_value('PS_SEARCH_WEIGHT_REF'),
//	'pa_supplier_reference' => get_configuration_value('PS_SEARCH_WEIGHT_REF'), // included in supplier_references
	'ean13' => get_configuration_value('PS_SEARCH_WEIGHT_REF'),
	'pa_ean13' => get_configuration_value('PS_SEARCH_WEIGHT_REF'),
	'upc' => get_configuration_value('PS_SEARCH_WEIGHT_REF'),
	'pa_upc' => get_configuration_value('PS_SEARCH_WEIGHT_REF'),
    'description_short' => get_configuration_value('PS_SEARCH_WEIGHT_SHORTDESC'),
    'description' => get_configuration_value('PS_SEARCH_WEIGHT_DESC'),
    'cname' => "1", /* default category: extra above cnames  */
    'cnames' => get_configuration_value('PS_SEARCH_WEIGHT_CNAME'),	/* all categories */
    'mname' => get_configuration_value('PS_SEARCH_WEIGHT_MNAME'),
    'tags' => get_configuration_value('PS_SEARCH_WEIGHT_TAG'),
    'attributes' => get_configuration_value('PS_SEARCH_WEIGHT_ATTRIBUTE'),
    'features' => get_configuration_value('PS_SEARCH_WEIGHT_FEATURE'));
	
  if ((int)$weights['cname'])	 
	  $weights["cnames"] = ((int)$weights["cnames"])-1;
	
  $p_fields = "p.id_product, pl.id_lang, pl.id_shop, l.iso_code";
  if ((int)$weights['pname'])     			 $p_fields .= ', pl.name AS pname';  
  if ((int)$weights['reference'])    		 $p_fields .= ', p.reference';
  if ((int)$weights['ean13'])     			 $p_fields .= ', p.ean13';
  if ((int)$weights['upc'])     			 $p_fields .= ', p.upc';
  if ((int)$weights['description_short'])    $p_fields .= ', pl.description_short';
  if ((int)$weights['description'])		     $p_fields .= ', pl.description';
  if ((int)$weights['cname'])			     $p_fields .= ', cl.name AS cname';
  if ((int)$weights['cnames'])			     $p_fields .= ', GROUP_CONCAT(" ",cl2.name) AS cnames';  
  if ((int)$weights['mname'])			     $p_fields .= ', m.name AS mname';  
	
  $pa_fields = "";
  if ((int)$weights['pa_reference'])	     $pa_fields .= ', pa.reference AS pa_reference';
  if ((int)$weights['pa_ean13'])		     $pa_fields .= ', pa.ean13 AS pa_ean13';
  if ((int)$weights['pa_upc'])			     $pa_fields .= ', pa.upc AS pa_upc';

  /* now we calculate how many products we should call at once */
  $res = dbquery('SELECT COUNT(*) AS langcount FROM '._DB_PREFIX_.'lang');
  $row = mysqli_fetch_assoc($res);
  $langcount = $row["langcount"];
  
  $isFeaturesActive = get_configuration_value('PS_FEATURE_FEATURE_ACTIVE');
  $isCombinationsActive = get_configuration_value('PS_COMBINATION_FEATURE_ACTIVE');  
  $count_words = 0;
  $query_array3 = array();
  $starttime = time();
  $batchsize = 30; /* number of records read at once */
  $last_product = $last_shop = 0; /* as there can be more than one language we can't update every loop */
  $productshops_array = array();
  
    $query = 'SELECT '.$p_fields.'
			FROM '._DB_PREFIX_.'product p
			LEFT JOIN '._DB_PREFIX_.'product_lang pl ON p.id_product = pl.id_product
			LEFT JOIN '._DB_PREFIX_.'product_shop ps ON p.id_product = ps.id_product
			LEFT JOIN '._DB_PREFIX_.'category_lang cl
				ON (cl.id_category = ps.id_category_default AND pl.id_lang = cl.id_lang AND cl.id_shop = ps.id_shop)
			LEFT JOIN '._DB_PREFIX_.'category_product cp ON cp.id_product = ps.id_product	
			LEFT JOIN '._DB_PREFIX_.'category_lang cl2
				ON (cp.id_category = cl2.id_category AND pl.id_lang = cl2.id_lang AND cl2.id_shop = ps.id_shop)
			LEFT JOIN '._DB_PREFIX_.'manufacturer m ON m.id_manufacturer = p.id_manufacturer
			LEFT JOIN '._DB_PREFIX_.'lang l ON l.id_lang = pl.id_lang
			WHERE pl.`id_product` = '.$id_product.' AND pl.`id_shop` = '.$id_shop.' 
			AND pl.`id_lang` = '.$id_lang.'
			GROUP BY id_product,id_shop,id_lang
			ORDER BY l.active DESC, ps.id_product, ps.id_shop, pl.id_lang';

    $res=dbquery($query);
	$product = mysqli_fetch_assoc($res);

	  if ((int)$weights['tags'])
	  { $tquery = 'SELECT GROUP_CONCAT(" ",t.name) AS ptags FROM '._DB_PREFIX_.'product_tag pt
		LEFT JOIN '._DB_PREFIX_.'tag t ON (pt.id_tag = t.id_tag AND t.id_lang = '.(int)$product['id_lang'].')
		WHERE pt.id_product = '.(int)$product['id_product'].'
		GROUP BY pt.id_product';
		$tres = dbquery($tquery);
		if(mysqli_num_rows($tres) > 0)
		{ $trow = mysqli_fetch_assoc($tres);
		  $product['tags'] = $trow['ptags'];
		}
	  } 
      if (((int)$weights['attributes']) && $isCombinationsActive)
	  { $aquery = 'SELECT GROUP_CONCAT(" ",al.name) AS atnames FROM '._DB_PREFIX_.'product_attribute pa
		INNER JOIN '._DB_PREFIX_.'product_attribute_combination pac ON pa.id_product_attribute = pac.id_product_attribute
		INNER JOIN '._DB_PREFIX_.'attribute_lang al ON (pac.id_attribute = al.id_attribute AND al.id_lang = '.(int)$product['id_lang'].')
		INNER JOIN '._DB_PREFIX_.'product_attribute_shop pas ON (pa.id_product_attribute = pas.id_product_attribute AND id_shop='.(int)$product['id_shop'].')
		WHERE pa.id_product = '.(int)$product['id_product'].'
		GROUP BY pa.id_product';
		$ares = dbquery($aquery);
		if(mysqli_num_rows($ares) > 0)
		{ $arow = mysqli_fetch_assoc($ares);
		  $product['attributes'] = $arow['atnames'];
		}
      }
      if (((int)$weights['features']) && $isFeaturesActive)
	  { $fquery = 'SELECT GROUP_CONCAT(" ",fvl.value) AS fvlvalues FROM '._DB_PREFIX_.'feature_product fp
		LEFT JOIN '._DB_PREFIX_.'feature_value_lang fvl ON (fp.id_feature_value = fvl.id_feature_value AND fvl.id_lang = '.(int)$product['id_lang'].')
		WHERE fp.id_product = '.(int)$product['id_product'].'
		GROUP BY fp.id_product';
		$fres = dbquery($fquery);
		if(mysqli_num_rows($fres) > 0)
		{ $frow = mysqli_fetch_assoc($fres);
		  $product['features'] = $frow['fvlvalues'];
		}
      }
      if ((int)$weights['supplier_references']) 
	  { $srquery = 'SELECT GROUP_CONCAT(" ",product_supplier_reference) AS srvalues FROM '._DB_PREFIX_.'product_supplier
		WHERE id_product = '.(int)$product['id_product'].'
		GROUP BY id_product';
 		$srres = dbquery($srquery);
		if(mysqli_num_rows($srres) > 0)
		{ $srrow = mysqli_fetch_assoc($srres);
		  $product['supplier_references'] = $srrow['srvalues'];
		}		  
	  }
	  if ($pa_fields != "") 
	  { $afquery = 'SELECT id_product '.$pa_fields.'
		FROM '._DB_PREFIX_.'product_attribute pa WHERE pa.id_product = '.(int)$product['id_product'];
		$afres = dbquery($afquery);
		if(mysqli_num_rows($afres)>0)
		{ while($afrow = mysqli_fetch_assoc($afres))
		  {	$product['attributes_fields'][] = $afrow;
		  }
		}
	  }

	  // Data must be cleaned of html, bad characters, spaces and anything, then if the resulting words are long enough, they're added to the array
	  $product_array = array();
	  foreach ($product as $key => $value) 
	  {	if ($key == 'attributes_fields')
	    { foreach ($value as $pa_array)
		  {	foreach ($pa_array as $pa_key => $pa_value) 
		    { fillProductArray($product_array, $weights, $pa_key, $pa_value, $product['id_lang'], $product['iso_code']);
			}
		  }
		} 
		else 
		{ fillProductArray($product_array, $weights, $key, $value, $product['id_lang'], $product['iso_code']);
		}
	  }
	  
  echo '<div id="testdav"><table id="Maintable" class="triplemain"><colgroup id="mycolgroup">';
  echo "<col id='col1'></col><col id='col2'></col>";
  echo "</colgroup><thead><tr>";
  echo '<th><a href="" onclick="this.blur(); return sortTable(\'offTableBdy\', \'0\', false);" fieldname="word" >word</a></th>';
  echo '<th><a href="" onclick="this.blur(); return sortTable(\'offTableBdy\', \'1\', false);" fieldname="weight" >weight</a></th>';
  echo "</tr></thead ><tbody id='offTableBdy'>"; 
	  
	  ksort($product_array, SORT_STRING);
	  foreach($product_array AS $word => $weight)
	  { echo '<tr><td>'.$word.'</td><td>'.$weight.'</td></tr>';
	  }
  echo '</table>';
  
  echo '</td></tr></table>';
