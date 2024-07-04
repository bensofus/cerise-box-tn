<?php 
if(!@include 'approve.php') die( "approve.php was not found!");
set_time_limit(4);
//$verbose = true;

$rewrite_settings = get_rewrite_settings();

$query="select value from ". _DB_PREFIX_."configuration  WHERE name='PS_COUNTRY_DEFAULT'";
$res=dbquery($query);
$row = mysqli_fetch_assoc($res);
$id_country = $row["value"];

if(!isset($_GET['id_lang']) || $_GET['id_lang'] == "") {
	$query="select value, l.name from ". _DB_PREFIX_."configuration f, ". _DB_PREFIX_."lang l";
	$query .= " WHERE f.name='PS_LANG_DEFAULT' AND f.value=l.id_lang";
	$res=dbquery($query);
	$row = mysqli_fetch_assoc($res);
	$id_lang = $row['value'];
}
else
  $id_lang = intval($_GET['id_lang']);
  
if(!isset($_GET['id_shop']) || $_GET['id_shop'] == "")
  $id_shop = 1;
else 
  $id_shop = intval($_GET['id_shop']);

if(!isset($_GET['startrec']) || (trim($_GET['startrec']) == '')) $_GET['startrec']="0";
if(!isset($_GET['numrecs'])) {$_GET['numrecs']="100";}

$error = "";
if(isset($_GET['id_product']) && ($_GET['id_product'] != ""))
{ $id_product = intval($_GET['id_product']);
  $query="select * from ". _DB_PREFIX_."product";
  $query .= " WHERE id_product='".$id_product."'";
  $res=dbquery($query);
  if(mysqli_num_rows($res) == 0)
    $error = $id_product." is not a valid product id";
}
else 
{ $error = "Please provide a product id!";
  $id_product = "";
}

?><!DOCTYPE html> 
<html lang="en"><head><meta charset="utf-8">
<title>Prestashop Customizations</title>
<link rel="stylesheet" href="style1.css" type="text/css" />
<script type="text/javascript" src="utils8.js"></script>
<script type="text/javascript" src="sorter.js"></script>
<script type="text/javascript">

function flip_visibility()
{ var tab = document.getElementById("Maintable");
  for(var i=0; i<tab.rows.length; i++)
  { if(i>0) /* header is always visible - so next if will do nothing */
	   tab.rows[i].style.display = "none";
	if ((ccform.showsame.checked) && (tab.rows[i].cells[0].innerHTML != "") && (tab.rows[i].cells[1].innerHTML != "") && (tab.rows[i].cells[3].innerHTML == tab.rows[i].cells[4].innerHTML))
	   tab.rows[i].style.display = "table-row";
	if ((ccform.showdiff.checked) && (tab.rows[i].cells[0].innerHTML != "") && (tab.rows[i].cells[1].innerHTML != "") && (tab.rows[i].cells[3].innerHTML != tab.rows[i].cells[4].innerHTML))
	   tab.rows[i].style.display = "table-row";
	if (ccform.showaonly.checked && (tab.rows[i].cells[0].innerHTML!="") && (tab.rows[i].cells[1].innerHTML==""))
	   tab.rows[i].style.display = "table-row";
	if (ccform.showbonly.checked && (tab.rows[i].cells[0].innerHTML=="") && (tab.rows[i].cells[1].innerHTML!=""))
	   tab.rows[i].style.display = "table-row";	   
	if (ccform.showshopflags.checked)
	{ tab.rows[i].cells[5].style.display = tab.rows[i].cells[6].style.display = "table-cell";
	  tab.rows[i].cells[7].style.display = tab.rows[i].cells[8].style.display = "table-cell";
	}
    else
	{ tab.rows[i].cells[5].style.display = tab.rows[i].cells[6].style.display = "none";
	  tab.rows[i].cells[7].style.display = tab.rows[i].cells[8].style.display = "none";
	}
	if (ccform.showdates.checked)
	{ tab.rows[i].cells[9].style.display = tab.rows[i].cells[10].style.display = "table-cell";
	  tab.rows[i].cells[11].style.display = tab.rows[i].cells[12].style.display = "table-cell";
	}
    else
	{ tab.rows[i].cells[9].style.display = tab.rows[i].cells[10].style.display = "none";
	  tab.rows[i].cells[11].style.display = tab.rows[i].cells[12].style.display = "none";
	}
  }
  var val = ccform.fltr.value;
  if(val.length > 0)
  { var re = new RegExp(val, "gi");
    for(var i=0; i<tab.rows.length; i++)
	{ if(tab.rows[i].style.display == "none") continue;
	  if(!tab.rows[i].cells[2].innerHTML.match(re) && !tab.rows[i].cells[3].innerHTML.match(re) && !tab.rows[i].cells[4].innerHTML.match(re))
	  { tab.rows[i].style.display = "none"; 
	  }
	}
  }  
}

function file2_change(elt)
{ if(elt.files)
    ccform.useactive.checked = false;
  else
    ccform.useactive.checked = true;
}

function confcompare_change(elt)
{ if(elt.checked)
    ccform.file2.files.length = 0;
}

function init()
{ 
}

</script>
</head><body onload="init()">
<?php print_menubar(); ?>

<center><h1>Compare configuration tables</h1></center>
<br>Config Compare allows you to compare the exported ps_configuration tables (sql files) of two shops.
<br>Alternative you can also compare one export file with the configuration of the present shop.
<p>
<form name=ccform method=post enctype="multipart/form-data">
<input type=hidden name=verbose><input type=hidden name=task value="compareconfig">
Table 1: <input type=file name=file1> &nbsp; &nbsp; 
Table 2: <input type=file name=file2 id="confcompareswitch" onchange="file2_change(this)"> &nbsp; &nbsp; 
<input type=checkbox name=useactive onchange="confcompare_change(this);" checked> Use configuration table of this shop
<br>Show <input type=checkbox name=showsame onchange="flip_visibility();"> same &nbsp <input type=checkbox name=showdiff checked onchange="flip_visibility();"> different
 &nbsp; <input type=checkbox name=showaonly checked onchange="flip_visibility();"> a only &nbsp <input type=checkbox name=showbonly checked onchange="flip_visibility();"> b only
<br>Show <input type=checkbox name=showshopflags onchange="flip_visibility();"> shopflags &nbsp <input type=checkbox name=showdates onchange="flip_visibility();"> dates
 &nbsp; [] &nbsp; search for <input name="fltr" size="3" onkeyup="flip_visibility()">
<br><center><input type=submit value="Compare"></center></form>

<?php
if((isset($_FILES["file1"]) AND ($_FILES['file1']['name'] != "")) AND ((isset($_FILES["file2"]) AND ($_FILES['file2']['name']!="")) OR isset($_POST["useactive"])))
{ echo "Comparing ".$_FILES['file1']['name'];
  if(!isset($_POST['useactive'])) echo " and ".$_FILES['file2']['name']; else echo " and the configuration of the active shop.";
  
  $c1arr = procfile("file1");
  if(!isset($_POST['useactive']))
  { $c2arr = procfile("file2");
  }
  else /* read active configuration table into array */
  { $c2arr = array();
    $query="select * FROM ". _DB_PREFIX_."configuration ORDER BY name,id_shop_group,id_shop,date_add";
    $res=dbquery($query);
    while($row = mysqli_fetch_assoc($res))
	  $c2arr[] = $row;  
  }
  $len1 = sizeof($c1arr);
  $len2 = sizeof($c2arr);
  $i = $j=0;
  $cdata = array();
  while(($i<$len1) && ($j < $len2))
  { if(!isset($c1arr[$i]["name"]))
	{ copydata(2);
	}
	else if(!isset($c2arr[$i]["name"]))
	{ copydata(1);
	}
    else if(strtoupper($c1arr[$i]["name"]) == strtoupper($c2arr[$j]["name"]))
	{ copydata(3);
	}
	else if(strtoupper($c1arr[$i]["name"]) < strtoupper($c2arr[$j]["name"]))
	{ copydata(1);
	}
	else if(strtoupper($c1arr[$i]["name"]) > strtoupper($c2arr[$j]["name"]))
	{ copydata(2);
	}
  }

  echo '<div id="testdiv"><table id="Maintable" class="triplemain"><colgroup id="mycolgroup">';
  $fields = array("id_configurationa","id_configurationb","namea","valuea","valueb",
"id_shop_groupa","id_shopa","id_shop_groupb","id_shopb","date_adda","date_upda","date_addb","date_updb");
  $fieldnames = array("ida","idb","name","valuea","valueb",
"groupa","shopa","groupb","shopb","date_adda","date_upda","date_addb","date_updb");
  $hiddenfields = array("id_shop_groupa","id_shopa","id_shop_groupb","id_shopb","date_adda","date_upda","date_addb","date_updb");
  $i = 0;
  for($i=0; $i<sizeof($fields); $i++)
  { $namecol = "";
    if(in_array($fields[$i], array("namea","valuea","valueb")))
        $namecol = ' class="namecol"';
    echo "<col id='col".$i."'".$namecol."></col>";
  }
  echo "</colgroup><thead><tr>";
  $i=0;
  foreach($fieldnames AS $fieldname)
  { $width="";
    if(($fieldname=="valuea") or ($fieldname=="valueb")) $width = 'style="width:100px"';
	if(in_array($fields[$i],$hiddenfields)) $flag = 'style="display:none"'; else $flag = '';
    echo '<th '.$flag.'><a href="" onclick="this.blur(); return sortTable(\'offTblBdy\', '.$i++.', 0);" fieldname="'.$fieldname.'" title="'.$fieldname.'">'.$fieldname.'</a></th>';
  }
  echo '</tr></thead><tbody id="offTblBdy">
  ';

  foreach($cdata AS $cline)
  { /* sql file can contain quotes. Database not. So we strip them here. */
    if(!isset($_POST['showsame']) && isset($_POST['useactive']) && isset($cline["valuea"]))
    { if((strlen($cline["valuea"])!=0) && ($cline["valuea"][0] == "'")) 
		  $cline["valuea"] = substr($cline["valuea"],1,strlen($cline["valuea"])-2); 
	}
	$lineflag = "";
    if(isset($cline["namea"]) && isset($cline["nameb"]) && (!strcasecmp($cline["valuea"],$cline["valueb"])))
	   $lineflag = 'style="display:none"';
    echo '<tr '.$lineflag.'>';
    foreach($fields AS $field)
	{ if($field == "namea")
	  { if(isset($cline["namea"]))
	      echo '<td>'.$cline[$field].'</td>';
		else
		  echo '<td>'.$cline["nameb"].'</td>';
		continue;
	  }
	  if(in_array($field,$hiddenfields)) $flag = 'display:none; '; else $flag = '';
	  if(array_key_exists($field,$cline))
	  { if((strlen($cline[$field])!=0) && ($cline[$field][0] == "'")) 
		  $cline[$field] = substr($cline[$field],1,strlen($cline[$field])-2); /* remove quotes */
	    if(($field == "valuea") OR ($field == "valueb"))
		  echo '<td '.$flag.'><div style="max-width:300px; overflow-x: auto; overflow-y: auto;">'.htmlspecialchars($cline[$field]).'</div></td>';
	    else
		  echo '<td style="'.$flag.'">'.$cline[$field].'</td>';
	  }
	  else
	  { echo '<td style="'.$flag.' background-color:#DDDDDD;">';
	    echo '</td>';
	  }
	}
	echo "</tr>
";
  }
  echo "</tbody></table>";
  echo '<script>var len = '.sizeof($cdata).';</script>';
}

function copydata($flag)
{ global $c1arr,$c2arr,$cdata,$i,$j;
  $tmp = array();
  if(($flag==1) || ($flag == 3))
  { $tmp["id_configurationa"] = $c1arr[$i]["id_configuration"];
	$tmp["id_shop_groupa"] = $c1arr[$i]["id_shop_group"]; 
	$tmp["id_shopa"] = $c1arr[$i]["id_shop"];	
	$tmp["namea"] = $c1arr[$i]["name"];
	$tmp["valuea"] = $c1arr[$i]["value"];
	$tmp["date_adda"] = $c1arr[$i]["date_add"];
	$tmp["date_upda"] = $c1arr[$i++]["date_upd"];
  }
  if(($flag==2) || ($flag == 3))
  { $tmp["id_configurationb"] = $c2arr[$j]["id_configuration"];
	$tmp["id_shop_groupb"] = $c2arr[$j]["id_shop_group"];
	$tmp["id_shopb"] = $c2arr[$j]["id_shop"];	
	$tmp["nameb"] = $c2arr[$j]["name"];
	$tmp["valueb"] = $c2arr[$j]["value"];
	$tmp["date_addb"] = $c2arr[$j]["date_add"];
	$tmp["date_updb"] = $c2arr[$j++]["date_upd"];
  }
  $cdata[]=$tmp;
}

function procfile($fname)
{ $lines = file($_FILES[$fname]["tmp_name"], FILE_IGNORE_NEW_LINES);
  $len = sizeof($lines);
  for($i=0; $i<$len; $i++)
  { $line = trim($lines[$i]);
    if(!strcasecmp("INSERT",substr($line,0,6))) break;
  }
  if($i==$len) colordie("Error analyzing configuration file ".$fname);
  $pos = strpos($lines[$i],"(");
  $pos2 = strpos($lines[$i],")");
  $fieldslist = substr($lines[$i],$pos+2,$pos2-$pos-3);
  $fields = explode("`, `",$fieldslist);
  $numfields = sizeof($fields);
  $blockrows = array();
  for($j=$i+1; $j<$len; $j++)
  { if(substr($lines[$j],0,1) !="(") break;
    $line = substr($lines[$j],1,-2);
    $datafields = explode(", ",$line);
//  \(\s*(?:(?'values''(?:[^']|'')*'|[^[,'\s)]+)(?:\s*,\s*|))+\s*\)
//    $pattern = "/\(\s*(?:(?'values''(?:[^']|'')*'|[^[,'\s)]+)(?:\s*,\s*|))+\s*\)/";
//	preg_match($pattern,$lines[$j],$datafields);
//print_r($datafields); die("XX");
	$dats = array();
	for($k=0;$k<$numfields;$k++)
	{ if(($fields[$k] == "name") && ($datafields[$k][0] == "'"))
	  { 
		$dats[$fields[$k]] = substr($datafields[$k],1,strlen($datafields[$k])-2);
	  }
	  else
        $dats[$fields[$k]] = $datafields[$k];
	}
	$blockrows[] = $dats;
  }
  usort($blockrows, "mysort");
  return $blockrows;  
}

function mysort($a,$b)
{   $an = strtoupper($a["name"]);
	$bn = strtoupper($b["name"]);
    if ($an == $bn) 
	{ if ($a["id_shop_group"] == $b["id_shop_group"])
	  { if ($a["id_shop"] == $b["id_shop"])
		  return 0;
	    return ($a["id_shop"] < $b["id_shop"]) ? -1 : 1;
	  }
	  return ($a["id_shop_group"] < $b["id_shop_group"]) ? -1 : 1;
    }
    return ($an < $bn) ? -1 : 1;
}

  
  include "footer1.php";
?>
</body>
</html>