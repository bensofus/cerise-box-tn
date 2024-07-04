<?php 
if(!@include 'approve.php') die( "approve.php was not found!");
$input = $_GET;

/* get default language: we use this for the categories, manufacturers */
$query="select value from "._DB_PREFIX_."configuration WHERE name='PS_LANG_DEFAULT'";
$res=dbquery($query);
$row = mysqli_fetch_array($res);
$id_lang = $row['value'];

$languages = array();
$langnames = array();
$query = "SELECT id_lang,iso_code FROM "._DB_PREFIX_."lang ORDER BY id_lang";
$res=dbquery($query);
while ($row=mysqli_fetch_array($res)) 
{ $languages[] = $row["id_lang"];
  $langnames[$row["id_lang"]] = $row["iso_code"]; 
}

$shops = array();
$query = "SELECT id_shop FROM "._DB_PREFIX_."shop WHERE active=1";
$res=dbquery($query);
while ($row=mysqli_fetch_array($res)) 
{ $shops[] = $row["id_shop"];
}

$shoplangs = array();
$resx = dbquery("SELECT concat(id_shop,'-',id_lang) AS ident FROM "._DB_PREFIX_."lang_shop ORDER BY id_shop,id_lang");
while ($rowx=mysqli_fetch_array($resx)) 
	$shoplangs[] = $rowx["ident"];

?><!DOCTYPE html> 
<html lang="en"><head><meta charset="utf-8">
<title>Prestashop Utilities</title>
<style>
.comment {background-color:#aabbcc}
h2 { margin-bottom:5px; margin-top:22px;}
form { margin-top: 8px; }
</style>
<script type="text/javascript" src="utils8.js"></script>
<script type="text/javascript" src="sorter.js"></script>
<script>
function indexprepare()
{ indexform.id_product.value = indexform.id_product.value.replace(' ','');
  if(indexform.id_product.value == "")
  { alert("You must fill in one or more product id's");
    return false;
  }
  var cleansed = indexform.id_product.value.replace(/^0-9,/, '');
  if(indexform.id_product.value != cleansed)
  { alert("only numbers and commas are allowed values");
    return false;
  }
  indexform.verbose.value = configform.verbose.checked;  
  return true;
}
function analyzewordsprepare()
{ analyzewordsform.id_word.value = analyzewordsform.id_word.value.replace(' ','');
  if(analyzewordsform.id_word.value == "")
  { alert("You must fill in one or more word id's");
    return false;
  }
  var cleansed = analyzewordsform.id_word.value.replace(/^0-9,/, '');
  if(analyzewordsform.id_word.value != cleansed)
  { alert("only numbers and commas are allowed values");
    return false;
  }
  analyzewordsform.verbose.value = configform.verbose.checked;  
  return true;
}

function unzipfileprepare()
{ var fname = unzipfileform.theunzipfile.value;
  if(fname.length < 14)
  { alert("Invalid filename");
    return false;
  }
  if(fname.substring(fname.length-14) != "prestafile.zip")
  { alert("Only files with the name prestafile.zip can be unzipped."+fname.substring(fname.length-14));
    return false;
  }
  unzipfileform.verbose.value = configform.verbose.checked;  
  return true;
}

function wordprepare()
{ wordform.id_product.value = wordform.id_product.value.replace(' ','');
  if(wordform.id_product.value == "")
  { alert("You must fill in a product id");
    return false;
  }
  var cleansed = wordform.id_product.value.replace(/^0-9/, '');
  if(wordform.id_product.value != cleansed)
  { alert("only numbers are allowed values");
    return false;
  }
//  wordform.verbose.value = configform.verbose.checked;  
  return true;
}

function sqlcutterprepare()
{ if(sqlcutterform.extables.value == "")
  { alert("You must provide at least one tablename to exclude.");
    return false;
  }
  if(sqlcutterform.sqlfile.value == "")
  { alert("You must select a sql file.");
    return false;
  }
//  sqlcutterform.verbose.value = configform.verbose.checked;  
  return true;
}

function formprepare(formname)
{ var myform = eval(formname);
  if(!myform.pagree.checked) 
  { alert("In order to prevent accidental clicks you need to 'I want to do this' checkbox!");
	return false;
  }
  myform.verbose.value = configform.verbose.checked;  
}
</script>
<link rel="stylesheet" href="style1.css" type="text/css" />
</head><body>
<?php print_menubar(); ?>
<table width="100%"><tr><td  class="headline" style="width:90%">
<a href="utilities.php">Utilities</a><br>
Here are some handy utilities. Unless mentioned differently changes apply to all shops in a multishop configuration.</td>
<td align=right rowspan=3><iframe name="tank" height="95" width="230"></iframe></td></tr></table>

<form name=configform><input type=checkbox name=verbose checked> verbose</form>
<?php 

  echo '<table class="spacer" style="width:100%">';
  echo '<form name=stockdeactivateform action="utilities-proc.php" method=get target=tank onsubmit=\'return formprepare("stockdeactivateform")\'>';
  echo '<tr><td><b>Deactivate products with stock of 0 or lower</b><br>';
  echo '<input type=checkbox name="pagree"> I want to do this.<br>';
  echo 'This will set all your active products that are not in stock to inactive. Products with combinations will only be set inactive when all combinations are out of stock.';
  echo '</td><td style="width:15%">';
  echo '<input type=hidden name="subject" value="stockdeactivate" ><input type=hidden name=verbose>';
  echo '<button>Deactivate products<br>that are out of stock</button></form></td></tr>';
  
  echo '<form name=stockactivateform action="utilities-proc.php" method=get target=tank onsubmit=\'return formprepare("stockactivateform")\'>';
  echo '<tr><td><b>Activate products that are in stock</b><br>';
  echo '<input type=checkbox name="pagree"> I want to do this.<br>';
  echo 'This will set all your inactive products that are in stock to active. Products with combinations will be set active when at least one combination is available.';
  echo '</td><td style="width:15%">';
  echo '<input type=hidden name="subject" value="stockactivate" ><input type=hidden name=verbose>';
  echo '<button>Activate products<br>that are in stock</button></form></td></tr>';
  
  echo '<tr><td><b>Deactivate manufacturers without active products</b><br>';
  echo '<form name="damanufacturerform" target=tank action="utilities-proc.php" method=get onsubmit=\'return formprepare("damanufacturerform")\'>';
  echo '<input type=checkbox name="pagree"> I want to do this.<br>';
  echo 'Deactivate active manufacturers when they don\'t have any active products left.
  <input type=hidden name="subject" value="manufacturerdeactivate"><input type=hidden name=verbose>';
  echo '</td><td>';
  echo '<button>Deactivate manufacturers<br>without active products</button></form></td></tr>';
  
  echo '<tr><td><b>Activate manufacturers with active products</b><br>';
  echo '<form name="amanufacturerform" target=tank action="utilities-proc.php" method=get onsubmit=\'return formprepare("amanufacturerform")\'>';
  echo '<input type=checkbox name="pagree"> I want to do this.<br>';
  echo 'Activate deactivated manufacturers with active products.
  <input type=hidden name="subject" value="manufactureractivate"><input type=hidden name=verbose>';
  echo '</td><td>';
  echo '<button>Activate manufacturers<br>with active products</button></form></td></tr>';
  
  echo '<tr><td><b>Search for faulty indexation</b><br>';
  echo 'This function compares the outcome of Prestools indexation with your present indexation. It will flag products where there are differences.</td>';
  echo '<td><a href="prodwordcheck.php" target=_blank><button>Search for<br>faulty indexation</button></a></td></tr>';
  
  echo '<tr><td><b>Index product(s)</b><br>';
  echo '<form name="indexform" target=tank action="utilities-proc.php" method=get onsubmit=\'return indexprepare()\'>';
  echo 'Give one or more comma separated product id\'s or ranges (like 14-18) that should be (re)indexed:<br>';
  echo '<input name=id_product size=50><input type=hidden name="subject" value="indexate">';
  echo '<br>Indexation will be applied for all shops and languages.';
  echo '<input type=hidden name=verbose></td>';
  echo '<td><button>Index product(s)</button></form></td></tr>';
  
  echo '<tr><td><b>Show search words for product</b><br>';
  echo '<form name="wordform" target=_blank method=get action="prodwords.php" onsubmit=\'return wordprepare()\'>';
  echo 'Give one product id for which you want to see the search terms in the database:<br>';
  echo '<input name=id_product size=3>';
  echo '<input type=hidden name=verbose></td>';
  echo '<td><button>Show search words</button></form></td></tr>';
  
  echo '<tr><td><b>Analyze search keyword id\'s</b><br>';
  echo '<form name="analyzewordsform" target=tank action="utilities-proc.php" method=get onsubmit=\'return analyzewordsprepare()\'>';
  echo 'Give one or more comma separated word id\'s for which you want to find the word:<br>';
  echo '<input name=id_word size=50><input type=hidden name="subject" value="analyzewords">';
  echo '<br> This function is for analyzing verbose output.
  The answer will be in the format "123=word-1-3" The latter two figures are resp. the id_shop and id_lang.';
  echo '<input type=hidden name=verbose></td>';
  echo '<td><button>Analyze keyword id\'s</button></form></td></tr>';
  
  echo '<tr><td><b>Edit SEO strings</b><br>';
  echo 'This is similar to the Prestashop function</td>';
  echo '<td><a href="urlseo-edit.php" target=_blank><button>SEO & URLs edit</button></a></td></tr>';
  
  echo '<tr><td><b>Config Info</b><br>';
  echo 'Config Info offers a versatile look at the settings in your configuration table. It includes the option to compare the configuration of two shops.</td>';
  echo '<td><a href="config-info.php" target=_blank><button>Config Info</button></a></td></tr>';

  echo '<tr><td><b>Tax Info</b><br>';
  echo 'Tax Info offers an overview of your shop\'s tax settings.</td>';
  echo '<td><a href="tax-info.php" target=_blank><button>Tax Info</button></a></td></tr>';

  echo '<tr><td><b>Cut out table data from sql file</b><br>';
  echo '<form name="sqlcutterform" target=tank action="utilities-proc.php" method=post enctype="multipart/form-data" onsubmit=\'return sqlcutterprepare()\'>';
  echo 'Provide the comma separated name(s) of one of more tables of which the data should be purged while the structure should be preserved.<br>';
  echo '<input name=extables size=40><input type=hidden name="subject" value="sqlcutter">';
  echo '<br>Select the sql file. <input type=file name="sqlfile"> ';
  echo 'This can be useful when you exported a shop and don\'t want tables like connection.';
  echo ' The output will be in another file. The file size is limited by the "post_max_size" setting in your php.ini file.<br>';
  echo 'For this function verbose is ignored.';
  echo '<input type=hidden name=verbose value="false"></td>';
  echo '<td><button>Cut data from sql file</button></form></td></tr>';

  echo '</table>';
  
    include "footer1.php";
?>
</body>
</html>
