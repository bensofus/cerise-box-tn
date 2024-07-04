<?php 
if(!@include 'approve.php')
   die( "approve.php was not in the admin directory");
$input = $_GET;
if(!isset($input['id_lang'])) $input['id_lang']="";
?><!DOCTYPE html> 
<html lang="en"><head><meta charset="utf-8">
<title>Prestashop Product Inactivata - move inactive products to bottom of their categories</title>
</head>
<body>
<h1>Inactivata</h1>
<h2>move inactive products to bottom of their categories</h2>
<?php

if($input['id_lang'] == "") {
	$query="select value, l.name from ". _DB_PREFIX_."configuration f, ". _DB_PREFIX_."lang l";
	$query .= " WHERE f.name='PS_LANG_DEFAULT' AND f.value=l.id_lang";
	$res=dbquery($query);
	$row = mysqli_fetch_array($res);
	$id_lang = $row['value'];
	$languagename = $row['name'];
}
else
  $id_lang = $input['id_lang'];

$cquery = "select c.id_category,name from ". _DB_PREFIX_."category c, ". _DB_PREFIX_."category_lang l WHERE c.id_category=l.id_category AND id_lang='".$id_lang."' ORDER BY id_category";
$cres=dbquery($cquery);
$z=0;
$zerocount = 0;
while ($crow=mysqli_fetch_array($cres)) 
{	echo $crow['id_category']." - ".$crow['name'];
	$changed = 0;
	$pquery = "select cp.id_product,position,active,price from ". _DB_PREFIX_."category_product cp LEFT JOIN ". _DB_PREFIX_."product p ON cp.id_product=p.id_product";
	$pquery .= " WHERE id_category='".$crow['id_category']."' ORDER BY active DESC, position";
	$pres=dbquery($pquery);
	echo " - ".mysqli_num_rows($pres)." products<br/>";
	$pos = 0;
	$zeroes = array(); // array with articles with price zero
	$inactives = array(); // array with inactive articles
	while ($prow=mysqli_fetch_array($pres)) 
	{	if($prow["price"]==0)
			$zeroes[$prow['id_product']] = $prow['position'];
		else if ($prow["active"]==0)
			$inactives[$prow['id_product']] = $prow['position'];
	    else
		{	if($prow["position"] != $pos)
			{	$changed = 1;
				echo $prow['id_product']." - ".$prow['position']."<br/>";
		
				$uquery = "UPDATE ". _DB_PREFIX_."category_product SET position = '".$pos."'";
				$uquery .= " WHERE id_category='".$crow['id_category']."' AND id_product='".$prow['id_product']."'";
				$ures=dbquery($uquery);
			}
			$pos++;
		}
	}
	if(sizeof($zeroes)> 0)
	{ echo sizeof($zeroes)." products with zero prices: ";
	  foreach($zeroes AS $key => $oldpos)
	    echo $key." ";
	  echo "<br>";
	}
	foreach($zeroes AS $key => $oldpos)
	{	if($oldpos != $pos) 
		{	echo $key." - ".$oldpos." to ".$pos."<br/>";
			$uquery = "UPDATE ". _DB_PREFIX_."category_product SET position = '".$pos."'";
			$uquery .= " WHERE id_category='".$crow['id_category']."' AND id_product='".$key."'";
			$ures=dbquery($uquery);
		}
		$pos++;
	}
	if(sizeof($inactives)> 0)
	  echo sizeof($inactives)." inactives: <br/>";
	foreach($inactives AS $key => $oldpos)
	{	if($oldpos != $pos) 
		{	echo $key." - ".$oldpos." to ".$pos."<br/>";
			$uquery = "UPDATE ". _DB_PREFIX_."category_product SET position = '".$pos."'";
			$uquery .= " WHERE id_category='".$crow['id_category']."' AND id_product='".$key."'";
			$ures=dbquery($uquery);
		}
		$pos++;
	}
}


?>
