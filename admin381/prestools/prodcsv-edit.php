<?php
if(!@include 'approve.php') die( "approve.php was not found!");

if(!isset($_FILES["csvfile"]))colordie("No file(s) provided");
$FileType = pathinfo($_FILES["csvfile"]["name"],PATHINFO_EXTENSION);
if($FileType != "csv") 	colordie("Sorry, only CSV files are allowed.".$FileType);
if(!is_uploaded_file($_FILES["csvfile"]["tmp_name"])) colordie("There was an error uploading your file!");

$input = $_POST;
ini_set('auto_detect_line_endings',TRUE);
$separator = ",";
if($input["separator"] == "semicolon")
  $separator = ";";
$csvfield = $input["csvfield"];
/* keyfields are for future expansion where keyfields are provided as input */
$keyfields = array("ean13","id_product","id_product_attribute","isbn","mpn","reference","upc");
if($csvfield == "supplier_reference")
	$keyfields[] = "supplier_reference";
if(!in_array($csvfield, $keyfields))
	colordie("<p>".$csvfield." is not a valid key field");
$id_shop = intval($input["id_shop"]);
$id_lang = intval($input["id_lang"]);

/* get shop group and its shared_stock status */
$query="select s.id_shop_group, g.share_stock, g.name from ". _DB_PREFIX_."shop s, "._DB_PREFIX_."shop_group g";
$query .= " WHERE s.id_shop_group=g.id_shop_group and id_shop='".$id_shop."'";
$res=dbquery($query);
$row = mysqli_fetch_array($res);
$id_shop_group = $row['id_shop_group'];
$share_stock = $row["share_stock"];

/* Get default country for the VAT tables and calculations */
$query="select l.name, id_country from ". _DB_PREFIX_."configuration f, "._DB_PREFIX_."country_lang l";
$query .= " WHERE f.name='PS_COUNTRY_DEFAULT' AND f.value=l.id_country AND id_lang=".$id_lang; 
$res=dbquery($query);
$row = mysqli_fetch_array($res);
$countryname = $row['name'];
$id_country = $row["id_country"];

/* get tax table */
$ps_tax = get_configuration_value('PS_TAX'); /* flag that taxes are enabled */
$taxrates = $done = array();
$taxrates[0] = 0;
if($ps_tax)
{ $query = "SELECT rate,name,tr.id_tax_rule,g.id_tax_rules_group,g.active,";
  if(version_compare(_PS_VERSION_ , "1.6.0.10", "<"))
    $query .= "0 AS deleted";
  else
    $query .= "g.deleted";
  $query .= " FROM "._DB_PREFIX_."tax_rule tr";
  $query .= " LEFT JOIN "._DB_PREFIX_."tax t ON (t.id_tax = tr.id_tax)";
  $query .= " LEFT JOIN "._DB_PREFIX_."tax_rules_group g ON (tr.id_tax_rules_group = g.id_tax_rules_group)";
  $query .= " WHERE tr.id_country = '".$id_country."' AND tr.id_state='0' AND NOT g.id_tax_rules_group IS NULL";
  $res=dbquery($query);
  $taxblock = '<option value="0">Select VAT</option>';
  while($row = mysqli_fetch_array($res))
  { $taxrates[$row['id_tax_rules_group']] = $row['rate'];
	$done[] = $row['id_tax_rules_group'];
  }
  /* now the taxrule groups that have no rate defined for this country */
  $query = "SELECT g.id_tax_rules_group,name,g.active,";
  if(version_compare(_PS_VERSION_ , "1.6.0.10", "<"))
    $query .= "0 AS deleted";
  else
    $query .= "g.deleted";
  $query .= " FROM "._DB_PREFIX_."tax_rules_group g";
  if(sizeof($done) > 0)
    $query .= " WHERE NOT g.id_tax_rules_group IN (".implode(",",$done).")";
  $res=dbquery($query);
  while($row = mysqli_fetch_array($res))
  { $taxrates[$row['id_tax_rules_group']] = 0;
  }
}
else
{ $query = "SELECT DISTINCT id_tax_rules_group FROM "._DB_PREFIX_."product_shop";
  $res=dbquery($query);
  while($row = mysqli_fetch_array($res))
  { $taxrates[$row['id_tax_rules_group']] = 0;
  }
}   

define("HIDE", 0); define("DISPLAY", 1); define("EDIT", 2);  // display

  define("NOT_EDITABLE", 0); define("INPUT", 1);
$fieldarray = array(
"id_product" => array("id", DISPLAY, NOT_EDITABLE),
"id_product_attribute" => array("combi", DISPLAY, NOT_EDITABLE),
"name" => array("name", DISPLAY, NOT_EDITABLE),
"price" => array("price", DISPLAY, INPUT),
"VAT" => array("VAT", DISPLAY, NOT_EDITABLE),
"priceVAT" => array("priceVAT", DISPLAY, INPUT),
"quantity" => array("qnt", DISPLAY, INPUT),
"ean13" => array("ean13", HIDE, NOT_EDITABLE),
"isbn" => array("isbn", HIDE, NOT_EDITABLE),
"mpn" => array("mpn", HIDE, NOT_EDITABLE),
"reference" => array("reference", HIDE, NOT_EDITABLE),
"upc" => array("upc", HIDE, NOT_EDITABLE),
);

if($csvfield == "supplier_reference")
	$fieldarray["supplier_reference"] = array("sup_ref", HIDE, NOT_EDITABLE);

$fieldarray[$csvfield][1] = DISPLAY;
$fieldarray[$csvfield][2] = NOT_EDITABLE;

echo '<!DOCTYPE html> 
<html lang="en"><head><meta charset="utf-8">';
if (isset($_SERVER['HTTP_USER_AGENT']) && (strpos($_SERVER['HTTP_USER_AGENT'], 'Chrome') !== false  || strpos($_SERVER['HTTP_USER_AGENT'], 'CriOS') !== false))
  echo '<meta name="referrer" content="no-referrer">';
else
  echo '<meta name="referrer" content="none">';	
echo '
<title>Prestashop Product CSV</title>
<link rel="stylesheet" href="style1.css" type="text/css" />
<style>
table.tripleswitch td
{ vertical-align: top;
}
</style>
<script type="text/javascript" src="utils8.js"></script>
<script type="text/javascript" src="sorter.js"></script>
<script>
';
?>
trioflag = false; /* check that only one of price, priceVAT and VAT is editable at a time */
var prestashop_version = '<?php echo _PS_VERSION_ ?>';

function switchDisplay(id, elt, fieldno, val)  // collapse(field)
{ var tmp, tmp2, val, checked;
  var advanced_stock = false;
  if(val == '0') /* hide */
  { var tbl= document.getElementById(id).parentNode;
    for (var i = 0; i < tbl.rows.length; i++)
	  if(tbl.rows[i].cells[fieldno])
	    tbl.rows[i].cells[fieldno].style.display='none';
  }
  if((val == '1') || (val=='2')) /* 1 = show */
  { var tbl= document.getElementById(id).parentNode;
    for (var i = 0; i < tbl.rows.length; i++) 
	  if(tbl.rows[i].cells[fieldno])
	    tbl.rows[i].cells[fieldno].style.display='table-cell';
  }
  if(val=='2') /* 2 = edit */
  { tab = document.getElementById('Maintable');
    var tblEl = document.getElementById(id);
    field = tab.tHead.rows[1].cells[fieldno].children[0].innerHTML;
    if((trioflag == true) && ((field == "price") || (field == "priceVAT")))
    { alert("You may edit only one of the two fields at a time: price and priceVAT");
      return;
    }
    if((field == "price") || (field == "priceVAT"))
      trioflag = true;
	if(field == "default_on")
	{ var fieldnr = tbl.rows[1].cells.length - 1;
	  for (var i = 0; i < tbl.rows.length; i++)
		if(tbl.rows[i].cells[fieldnr])
			tbl.rows[i].cells[fieldnr].style.display='none';
	  alert("Please use the Submit All button to submit changes to the default field!");
	}
    for(var i=0; i<tblEl.rows.length; i++)
    { if(!tblEl.rows[i].cells[fieldno]) continue; 
	  tmp = tblEl.rows[i].cells[fieldno].innerHTML;
      tmp2 = tmp.replace("'","\'");
      row = tblEl.rows[i].cells[1].dataset.row; /* fieldname id_product_attribute7 => 7 */
      if(field=="priceVAT") 
      { tblEl.rows[i].cells[fieldno].innerHTML = '<input name="'+field+row+'" value="'+tmp2+'" class="show" onchange="priceVAT_change(this)" />';
		priceVAT_editable = true;
	  }
      else if(field=="price") 
      { tblEl.rows[i].cells[fieldno].innerHTML = '<input name="showprice'+row+'" value="'+tmp2+'" class="show" onchange="price_change(this)" />';
		price_editable = true;
	  }
      else if(field=="image") 	  
	  { if(emptylegendfound)
		{ alert('Please make sure that all images of this product have legends!'); 
		  return;
		}
		var res = tmp.match(/(\d+)\.jpg/);
		if(res)
			var id_image = res[1];
		else 
			var id_image = "XEARQ"; /* matches with nothing */
		var tagger =  new RegExp("="+id_image+">", "g");
		var legendblock2 = legendblock.replace(tagger,'='+id_image+' selected>');
		tblEl.rows[i].cells[fieldno].innerHTML = '<select name="image'+row+'" onchange="showimage('+row+');"><option value=0>Select an image</option>'+legendblock2+'</select><span id="imgspan'+row+'"></span>';
	  }
	  else if((field=="quantity") && (tblEl.rows[i].cells[fieldno].style.backgroundColor == "yellow"))
	  { advanced_stock = true;
		continue;
	  }
      else
	  { tblEl.rows[i].cells[fieldno].innerHTML = '<input name="'+field+row+'" value="'+tmp2+'" onchange="reg_change(this);" />';
	  }
	}
    tmp = elt.parentElement.innerHTML;
    tmp = tmp.replace(/<br.*$/,'');
    elt.parentElement.innerHTML = tmp+"<br><br>Edit";
  }
  var warning = "";
  if(advanced_stock)
    warning += "Quantity fields of combinations with warehousing - marked in yellow - cannot be changed.";
  var tmp = document.getElementById("warning");
  tmp.innerHTML = warning;
  return;
}

function init()
{
}
<?php echo '
</script>
</head>
<body onload="init()">';

print_menubar();

echo '<table class="triplehome" cellpadding=0 cellspacing=0><tr>';
echo '<td width="80%" class="headline"><a href="prodcsv-prep.php">Prodcsv edit</a><br>';
echo 'Edit products and combinations from a list of identifiers';
echo '<br>The imported data are at the end of each row. Their fieldnames have been preceded with an underscore. You should process those data in the database data and then save that.';
echo "</td>";
echo '<td style="text-align:right; width:30%" rowspan=3><iframe name=tank width="230" height="95"></iframe></td></tr></table>';

/* read the csv file */
$csvdata = array();
$fp = fopen($_FILES["csvfile"]["tmp_name"], 'r');
$header = fgetcsv($fp,0,$separator);
if(!in_array($csvfield, $header))
	colordie("Key field ".$csvfield." was not found in the imported file!");
$keys = array();
while (($row = fgetcsv($fp,0,$separator)) !== FALSE ) 
{ $tmp = array_combine($header, $row);
  if(in_array($tmp[$csvfield], $keys))
	 colordie("Key value ".$row[$csvfield]." was found more than once!");
  $keys[] = $tmp[$csvfield];
  $csvdata[$tmp[$csvfield]] = $tmp;
}

/* add csv fields to field list */
foreach($header AS $fld)
{ if($fld != $csvfield)
    $fieldarray["_".$fld] = array("_".$fld, DISPLAY, NOT_EDITABLE);
}

/* first search for products */
$query = "SELECT p.id_product,ps.price,s.depends_on_stock,name,s.quantity";
$query .= ",ean13,isbn,mpn,reference,upc,ps.id_tax_rules_group";
if($csvfield == "supplier_reference")
	$query .= ",product_supplier_reference AS supplier_reference";
$query .= " FROM ". _DB_PREFIX_."product p";
$query .= " LEFT JOIN ". _DB_PREFIX_."product_shop ps ON p.id_product=ps.id_product";
$query .= " LEFT JOIN ". _DB_PREFIX_."product_lang pl ON p.id_product=pl.id_product AND pl.id_shop=ps.id_shop";
if($share_stock == 0)
  $query .=" LEFT JOIN ". _DB_PREFIX_."stock_available s on s.id_product=p.id_product AND s.id_shop = '".$id_shop."'";
else
  $query .= " LEFT JOIN ". _DB_PREFIX_."stock_available s on s.id_product=p.id_product AND s.id_shop_group = '".$id_shop_group."'";
if($csvfield == "supplier_reference")
{ $query .= " INNER JOIN ". _DB_PREFIX_."product_supplier psu WHERE psu.id_product = p.id_product AND psu.product_supplier_reference IN ('".implode('","',$keys)."'))";
}
$query .= " WHERE ps.id_shop=".$id_shop." AND id_lang=".$id_lang;
if($csvfield == "supplier_reference")
{ 
}
else if(in_array($csvfield, array("id_product","upc","ean13")))
{ $query .= ' AND p.'.$csvfield.' IN ('.implode(',',$keys).')';
}
else
{ $query .= ' AND p.'.$csvfield.' IN ("'.implode('","',$keys).'")';
}
$res=dbquery($query);
$prodkeys = $doubleprodkeys = $prodids = array();
$prodrows = array();
while ($row=mysqli_fetch_assoc($res)) 
{ if(in_array($row[$csvfield], $prodkeys))
	 $doubleprodkeys[] = $row[$csvfield];
  else
  { $prodids[] = $row["id_product"];
	$prodkeys[] = $row[$csvfield];
	$row["id_product_attribute"] = 0;
	$prodrows[] = $row;
  }
}

/* now search for combinations */
$query = "SELECT pa.id_product,ps.price AS baseprice, pas.price,s.depends_on_stock,name,s.quantity";
$query .= ",pa.id_product_attribute,pa.ean13,pa.isbn,pa.mpn,pa.reference,pa.upc,ps.id_tax_rules_group";
if($csvfield == "supplier_reference")
	$query .= ",product_supplier_reference AS supplier_reference";
$query .= " FROM ". _DB_PREFIX_."product_attribute pa";
$query .= " LEFT JOIN ". _DB_PREFIX_."product_attribute_shop pas ON pa.id_product_attribute=pas.id_product_attribute AND pas.id_shop=".$id_shop;
$query .= " LEFT JOIN ". _DB_PREFIX_."product p ON pa.id_product=p.id_product";
$query .= " LEFT JOIN ". _DB_PREFIX_."product_shop ps ON pa.id_product=ps.id_product";
$query .= " LEFT JOIN ". _DB_PREFIX_."product_lang pl ON pa.id_product=pl.id_product AND pl.id_shop=ps.id_shop";
if($share_stock == 0)
  $query .=" LEFT JOIN ". _DB_PREFIX_."stock_available s on s.id_product_attribute=pa.id_product_attribute AND s.id_shop = '".$id_shop."'";
else
  $query .= " LEFT JOIN ". _DB_PREFIX_."stock_available s on s.id_product_attribute=pa.id_product_attribute AND s.id_shop_group = '".$id_shop_group."'";
if($csvfield == "supplier_reference")
{ $query .= " INNER JOIN ". _DB_PREFIX_."product_supplier psu WHERE psu.id_product_attribute = pa.id_product_attribute  AND psu.product_supplier_reference IN ('".implode('","',$keys)."'))";
}
$query .= " WHERE ps.id_shop=".$id_shop." AND id_lang=".$id_lang;
if($csvfield == "supplier_reference")
{ 
}
else if(in_array($csvfield, array("id_product","upc","ean13")))
{ $query .= ' AND pa.'.$csvfield.' IN ('.implode(',',$keys).')';
}
else
{ $query .= ' AND pa.'.$csvfield.' IN ("'.implode('","',$keys).'")';
}
$res=dbquery($query);
$pakeys = $doublepakeys = $doublecrosskeys = array();
$parows = array();

while ($row=mysqli_fetch_assoc($res)) 
{ if(in_array($row[$csvfield], $prodkeys))
	 $doublecrosskeys[] = $row[$csvfield]; /* should result in error message */
  else if(in_array($row[$csvfield], $pakeys))
	 $doublepakeys[] = $row[$csvfield]; /* should result in error message */
  else if(in_array($row['id_product'], $prodids))
	 $crossids[] = $row['id_product'];
  else
  { $pakeys[] = $row[$csvfield];
	$prodrows[] = $row;
  }
}

if((sizeof($doublepakeys) > 0) || (sizeof($doubleprodkeys) > 0) ||(sizeof($doublecrosskeys) > 0))
{ echo "<b>Errors found:</b><br>";
  if(sizeof($doubleprodkeys) > 0)
  { echo "The following identifiers apply to more than one product: ";
    echo implode(", ",array_unique($doubleprodkeys));
	echo "<br>";
  }
  if(sizeof($doublepakeys) > 0)
  { echo "The following identifiers apply to more than one product combination: ";
    echo implode(", ",array_unique($doublepakeys));
	echo "<br>";
  }
  if(sizeof($doublecrosskeys) > 0)
  { echo "The following identifiers apply to both product(s) and combination(s): ";
    echo implode(", ",array_unique($doublecrosskeys));
	echo "<br>";
  }
  echo "<br>";
}

echo "<span id='warning' style='background-color: #FFAAAA'></span>";

/* the switchform: hide/show fields and submit button */
  echo '<form name=SwitchForm><table class="tripleswitch" style="empty-cells: show;"><tr><td><br>Hide<br>Show<br>Edit</td>';
  
  $i=3;
  foreach($fieldarray AS $field => $flags)
  { /* standard the start mode of fields is "DISPLAY"(=1). But you could specify in $flags[3] that the field is initially hidden or in edit mode */
    if(($field == "id_product") || ($field == "id_product_attribute")) continue;
	$checked0 = $checked1 = $checked2 = "";
    if($flags[1] == 0) $checked0 = "checked"; 
    if($flags[1] == 1) $checked1 = "checked"; 
    if($flags[1] == 2) $checked2 = "checked"; 
	
    echo '<td >'.$field.'<br>';
    echo '<input type="radio" name="disp'.$i.'" id="disp'.$i.'_off" value="0" '.$checked0.' onClick="switchDisplay(\'offTblBdy\', this,'.$i.',0)" /><br>';
    echo '<input type="radio" name="disp'.$i.'" id="disp'.$i.'_on" value="1" '.$checked1.' onClick="switchDisplay(\'offTblBdy\', this,'.$i.',1)" /><br>';
	if($flags[2] != NOT_EDITABLE)
      echo '<input type="radio" name="disp'.$i.'" id="disp'.$i.'_on" value="2" '.$checked2.' onClick="switchDisplay(\'offTblBdy\', this,'.$i.',2)" />';
    echo '</td>';
	$i++;
  }
  echo "</form></tr></table>";


$prows = array_merge($prodrows, $parows);

ksort($prows);


$i=1;
echo '<form name="Mainform" method=post>';
echo '<div id="testdiv"><table id="Maintable" class="triplemain"><colgroup id="mycolgroup"><col></col>';
foreach($fieldarray AS $field => $flags)
{ $align = $namecol = "";
  if($field == "name")
	$namecol = ' class="namecol"';
  echo "<col id='col".$i++."'".$namecol."></col>";
}
echo "</colgroup><thead><tr><th colspan='".(sizeof($fieldarray)+1)."'>";
echo "<span style='float:left'><input type=checkbox id='base_included' onclick='switch_pricebase(this)'> include baseprice</span>";
echo "<span style='float:right'><input type=checkbox name=verbose>verbose &nbsp; &nbsp; ";
echo "<input type=button value='Submit all' onClick='return SubmitForm();'></span>";
echo '</th></tr><tr><th></th>';

foreach($fieldarray AS $field => $flags)
{ $reverse = "false";
  if($flags[1]==HIDE) $vis='style="display:none"'; else $vis="";
  echo '<th '.$vis.'><a href="" onclick="this.blur(); return sortTable(\'offTblBdy\', '.$i.', '.$reverse.');"  fieldname="'.$field.'" title="'.$field.'">'.$flags[0].'</a></th
>';
}
echo '<th><a href="" onclick="this.blur(); return upsideDown(\'offTblBdy\');" title="Upside down: reverse table order"><img src="upsidedown.jpg"></a></th>';
echo "</tr></thead
><tbody id='offTblBdy'>"; /* end of header */

$x=0;
foreach($prows AS $prow)
{ $stocky = "";
  echo '<tr><td id="trid'.$x.'" changed="0" '.$stocky.' data-row='.$x.'><input type="button" value="X" style="width:4px" onclick="RemoveRow('.$x.')" title="Hide row '.$x.' from display" /><input type=hidden name="id_product'.$x.'" value="'.$prow['id_product'].'"><input type=hidden name="id_product_attribute'.$x.'" value="'.$prow['id_product_attribute'].'"></td>';  
  foreach($fieldarray AS $field => $flags)
  { if($flags[1]==HIDE) $vis='style="display:none"'; else $vis="";
	if($field == "id_product")
	{ echo '<td '.$vis.'>'.$prow["id_product"].'</td>';
	}
	else if($field == "id_product_attribute")
	{ echo '<td '.$vis.'>'.$prow["id_product_attribute"].'</td>';
	}
	else if($field == "VAT")
	{ $sorttxt = "idx='".$prow['id_tax_rules_group']."'";
	  echo "<td ".$sorttxt.">".floatval($taxrates[$prow['id_tax_rules_group']])."</td>";
	}
	else if($field == "price")
	{ echo '<td '.$vis.'>'.number_format($prow["price"],2, '.', '').'</td>';
	}
	else if($field == "priceVAT")
	{ $priceVAT = number_format(((($taxrates[$prow["id_tax_rules_group"]]/100) +1) * $prow['price']),2, '.', '');
      echo '<td '.$vis.'>'.$priceVAT.'</td>';
	}
	else if($field == "quantity")
	{ if($prow["depends_on_stock"] == "1")
        echo '<td style="background-color:yellow" ".$vis.">'.$prow['quantity'].'</td>';	
	  else
	    echo "<td ".$vis.">".$prow['quantity']."</td>";
	}
	else if(substr($field,0,1) == "_")
	{ $csvfld = substr($field,1);
	  echo '<td '.$vis.'>'.$csvdata[$prow[$csvfield]][$csvfld].'</td>';
	}
	else	
	{ echo '<td '.$vis.'>'.$prow[$field].'</td>';
	}	
  }
  echo '<td><img src="enter.png" title="submit row '.$x.'" onclick="RowSubmit(this)"></td>';
  echo '</tr>';
  $x++;
}
  echo '</table></div></form>';
  
  echo '<div style="display:block;">';
  echo '<form name=rowform action="product-proc.php" method=post target=tank>';
  echo '<table id=subtable></table>';
  echo '<input type=hidden name=submittedrow><input type=hidden name=id_lang value="'.$id_lang.'">';
  echo '<input type=hidden name=allshops><input type=hidden name=reccount value="1">';
  echo '<input type=hidden name=id_shop value="'.$id_shop.'"><input type=hidden name=verbose>';
  echo '</form></div>';  
  
  include "footer1.php";
  echo '</body></html>';
  