<?php 
if(!@include 'approve.php') die( "approve.php was not found!");
if(!include 'ps_sourced_code.php') die( "ps_sourced_code.php was not found!");
$mode = "background";
if(isset($_POST["subject"]))
  $input = $_POST;
else
  $input = $_GET;
if(isset($input['id_lang']))
  $id_lang = strval(intval($_GET['id_lang']));
$errstring = "";
if(isset($demo_mode) && $demo_mode)
{ echo '<script>alert("The script is in demo mode. Nothing is changed!");</script>';
  die();
}

/* making shop block */
$shops = array();
$query=" select id_shop,name from ". _DB_PREFIX_."shop ORDER BY id_shop";
$res=dbquery($query);
while ($row=mysqli_fetch_array($res)) 
{ $shop_ids[] = $row['id_shop'];
}

if($input['subject'] != "sqlcutter")
{ echo '<!DOCTYPE html> 
<html lang="en"><head><meta charset="utf-8">
<script>
function newwin()
{ nwin = window.open("","NewWindow", "scrollbars,menubar,toolbar, status,resizable,location");
  content = document.body.innerHTML;
  if(nwin != null)
  { nwin.document.write("<html><head><meta http-equiv=\'Content-Type\' content=\'text/html; charset=utf-8\' /></head><body>"+content+"</body></html>");
    nwin.document.close();
  }
}
</script></head><body>';
  echo '<a href="#" title="Show the content of this frame in a New Window" onclick="newwin(); return false;">NW</a> ';
  if(!isset($input['subject'])) die("No subject specified!");
} 
  /* Note on the use of '(SELECT * from "._DB_PREFIX_."product_shop)' on the second lines of the queries
   * This is necessary because otherwise some (not all) mysql installation produce the error:
   *   1093: Table 'ps_product_shop' is specified twice
   * See: https://stackoverflow.com/questions/44970574/table-is-specified-twice-both-as-a-target-for-update-and-as-a-separate-source
   */
  if($input['subject'] == "stockdeactivate")
  { /* get shop group and its shared_stock status */
    $cnt1 = 0;
	echo "time=".time()."<br>";
    $query="select s.id_shop,s.id_shop_group, g.share_stock from "._DB_PREFIX_."shop s, "._DB_PREFIX_."shop_group g";
    $query .= " WHERE s.id_shop_group=g.id_shop_group ORDER BY id_shop";
    $res=dbquery($query);
    while($row = mysqli_fetch_array($res))
    { /* first products without combinations */
	  if($row["share_stock"] == 0)
	  { 
		$squery = " UPDATE "._DB_PREFIX_."product_shop ps";
		$squery .= " JOIN "._DB_PREFIX_."stock_available sa ON ps.id_product=sa.id_product AND ps.id_shop=sa.id_shop";
		$squery .= " LEFT JOIN "._DB_PREFIX_."product_attribute pa ON sa.id_product=pa.id_product";
		$squery .= " SET ps.active=0, ps.indexed=0";
		$squery .= " WHERE ps.id_shop=".$row["id_shop"]." AND ps.active=1 AND pa.id_product_attribute is null"; 
		$squery .= " AND sa.id_shop=".$row["id_shop"]." AND sa.quantity <= 0";
	  }
	  else
      {
		$squery = " UPDATE "._DB_PREFIX_."product_shop ps";
		$squery .= " JOIN "._DB_PREFIX_."stock_available sa ON ps.id_product=sa.id_product AND ps.id_shop=sa.id_shop";
		$squery .= " LEFT JOIN "._DB_PREFIX_."product_attribute pa ON sa.id_product=pa.id_product";
		$squery .= " SET ps.active=0, ps.indexed=0";
		$squery .= " WHERE ps.id_shop=".$row["id_shop"]." AND ps.active=1 AND pa.id_product_attribute is null"; 
		$squery .= " AND sa.id_shop_group=".$row["id_shop_group"]." AND sa.quantity <= 0";
	  }
      $sres=dbquery($squery);
	  $cnt1 += mysqli_affected_rows($conn);

	  /* now the products with combinations */
	  /* note that on older PS versions the ps_product_attribute_shop table doesn't contain an id_product field */
	  if($row["share_stock"] == 0)
	  { $squery = "UPDATE "._DB_PREFIX_."product_shop SET active=0,indexed=0 WHERE id_shop=".$row["id_shop"];
	    $squery .= " AND id_product IN (select ps.id_product from 	
		(SELECT * from "._DB_PREFIX_."product_shop) AS ps";
		$squery .= " WHERE ps.active=1 AND ps.id_shop=".$row["id_shop"]." AND EXISTS";
		$squery .= " (select null FROM "._DB_PREFIX_."product_attribute_shop pas";
		$squery .= " LEFT JOIN "._DB_PREFIX_."product_attribute pa ON pa.id_product_attribute=pas.id_product_attribute";
		$squery .= " WHERE pa.id_product=ps.id_product AND pas.id_shop=".$row["id_shop"].")";
		$squery .= " AND NOT EXISTS (select null FROM "._DB_PREFIX_."stock_available sa WHERE ps.id_product=sa.id_product AND sa.id_shop=".$row["id_shop"]." AND sa.quantity > 0))";
	  }
	  else
	  { $squery = "UPDATE "._DB_PREFIX_."product_shop SET active=0,indexed=0 WHERE id_shop=".$row["id_shop"];
	    $squery .= " AND id_product IN (select ps.id_product from (SELECT * from "._DB_PREFIX_."product_shop) AS ps";
		$squery .= " WHERE ps.active=1 AND ps.id_shop=".$row["id_shop"]." AND EXISTS";
		$squery .= " (select null FROM "._DB_PREFIX_."product_attribute_shop pas";
		$squery .= " LEFT JOIN "._DB_PREFIX_."product_attribute pa ON pa.id_product_attribute=pas.id_product_attribute";
		$squery .= " WHERE pa.id_product=ps.id_product AND pas.id_shop=".$row["id_shop"].")";
		$squery .= " AND NOT EXISTS (select null FROM "._DB_PREFIX_."stock_available sa WHERE ps.id_product=sa.id_product AND sa.id_shop_group=".$row["id_shop_group"]." AND sa.quantity > 0))";
	  }
	echo "time=".time()."<br>";
      $sres=dbquery($squery);
	  $cnt1 += mysqli_affected_rows($conn);
    }
	/* now disable ps_product for product where none of the ps_product_shop entries is active */
/*	$query="UPDATE "._DB_PREFIX_."product SET active=0 WHERE id_product IN (select p.id_product from (SELECT * from "._DB_PREFIX_."product) AS p";
	$query .= " LEFT OUTER JOIN "._DB_PREFIX_."product_shop ps ON p.id_product=ps.id_product AND  ps.active=1";
    $query .= " WHERE p.active=1 AND ps.id_shop is null)";
*/	
	$query="UPDATE "._DB_PREFIX_."product p";
	$query .= " LEFT JOIN "._DB_PREFIX_."product_shop ps ON p.id_product=ps.id_product AND ps.active=1";
	$query .= " SET p.active=0, p.indexed=0";
    $query .= " WHERE p.active=1 AND ps.id_shop is null";
    $res=dbquery($query);
	$cnt2 = mysqli_affected_rows($conn);

    echo '<script>alert("Finished: '.$cnt2.' products AND '.$cnt1.' product_shops were deactivated!");</script>';
		echo "time=".time()."<br>";
 }
 else if($input['subject'] == "stockactivate")
  { /* get shop group and its shared_stock status */
  	echo "time=".time()."<br>";
    $query="select s.id_shop,s.id_shop_group, g.share_stock from "._DB_PREFIX_."shop s, "._DB_PREFIX_."shop_group g";
    $query .= " WHERE s.id_shop_group=g.id_shop_group ORDER BY id_shop";
    $res=dbquery($query);
	$cnt1 = 0;
    while($row = mysqli_fetch_array($res))
    { /* first products without combinations */
	  if($row["share_stock"] == 0)
	  { 
		$squery = " UPDATE "._DB_PREFIX_."product_shop ps";
		$squery .= " JOIN "._DB_PREFIX_."stock_available sa ON ps.id_product=sa.id_product AND ps.id_shop=sa.id_shop";
		$squery .= " LEFT JOIN "._DB_PREFIX_."product_attribute pa ON sa.id_product=pa.id_product";
		$squery .= " SET ps.active=1, ps.indexed=0";
		$squery .= " WHERE ps.id_shop=".$row["id_shop"]." AND ps.active=0 AND pa.id_product_attribute is null"; 
		$squery .= " AND sa.id_shop=".$row["id_shop"]." AND sa.quantity > 0";
		
	  }
	  else
      { 
		$squery = " UPDATE "._DB_PREFIX_."product_shop ps";
		$squery .= " JOIN "._DB_PREFIX_."stock_available sa ON ps.id_product=sa.id_product AND ps.id_shop=sa.id_shop";
		$squery .= " LEFT JOIN "._DB_PREFIX_."product_attribute pa ON sa.id_product=pa.id_product";
		$squery .= " SET ps.active=1, ps.indexed=0";
		$squery .= " WHERE ps.id_shop=".$row["id_shop"]." AND ps.active=1 AND pa.id_product_attribute is null"; 
		$squery .= " AND sa.id_shop_group=".$row["id_shop_group"]." AND sa.quantity > 0";
	  }
      $sres=dbquery($squery);
	  $cnt1 += mysqli_affected_rows($conn);
	  echo "time=".time()."<br>";

	  /* now the products with combinations */
	  /* note that on older PS versions the ps_product_attribute_shop table doesn't contain an id_product field */
	  if($row["share_stock"] == 0)
	  { $squery = "UPDATE "._DB_PREFIX_."product_shop SET active=1,indexed=0 WHERE id_shop=".$row["id_shop"];
	    $squery .= " AND id_product IN (select ps.id_product from (SELECT * from "._DB_PREFIX_."product_shop) AS ps";
		$squery .= " WHERE ps.active=0 AND ps.id_shop=".$row["id_shop"];
		$squery .= " AND EXISTS (select null FROM "._DB_PREFIX_."stock_available sa WHERE ps.id_product=sa.id_product AND sa.id_shop=".$row["id_shop"]." AND sa.quantity > 0))";
	  }
	  else
	  { $squery = "UPDATE "._DB_PREFIX_."product_shop SET active=1,indexed=0 WHERE id_shop=".$row["id_shop"];
	    $squery .= " AND id_product IN (select ps.id_product from (SELECT * from "._DB_PREFIX_."product_shop) AS ps";
		$squery .= " WHERE ps.active=1 AND ps.id_shop=".$row["id_shop"]." AND EXISTS";
		$squery .= " (select null FROM "._DB_PREFIX_."product_attribute_shop pas";
		$squery .= " LEFT JOIN "._DB_PREFIX_."product_attribute pa ON pa.id_product_attribute=pas.id_product_attribute";
		$squery .= " WHERE pa.id_product=ps.id_product AND pas.id_shop=".$row["id_shop"].")";
		$squery .= " AND NOT EXISTS (select null FROM "._DB_PREFIX_."stock_available sa WHERE ps.id_product=sa.id_product AND sa.id_shop_group=".$row["id_shop_group"]." AND sa.quantity > 0))";
	  }
      $sres=dbquery($squery);
	  $cnt1 += mysqli_affected_rows($conn);
	  echo "time=".time()."<br>";
	}
	
	/* now enable ps_product for product where at least one ps_product_shop entry is active */
/*	$query = "UPDATE "._DB_PREFIX_."product SET active=1 WHERE id_product IN (";
	$query .= "select p.id_product from (SELECT * from "._DB_PREFIX_."product) AS p";
	$query .= " INNER JOIN "._DB_PREFIX_."product_shop ps ON p.id_product=ps.id_product AND ps.active=1";
    $query .= " WHERE p.active=0 GROUP BY p.id_product ORDER BY p.id_product)";
*/	
	$query = "UPDATE "._DB_PREFIX_."product p";
	$query .= " INNER JOIN "._DB_PREFIX_."product_shop ps ON p.id_product=ps.id_product AND ps.active=1";
	$query .= " SET p.active=1,p.indexed=0 ";
    $query .= " WHERE p.active=0";
	
    $res=dbquery($query);
	$cnt2 = mysqli_affected_rows($conn);
    echo '<script>alert("Finished: '.$cnt2.' products AND '.$cnt1.' product_shops were activated!");</script>';
	echo "time=".time()."<br>";
 }
 
 else if($input['subject'] == "manufactureractivate")
 { $query = "SELECT m.id_manufacturer FROM "._DB_PREFIX_."manufacturer m";
   $query .= " WHERE m.active=0 AND EXISTS ";
   $query .= " (SELECT NULL FROM "._DB_PREFIX_."product p WHERE active=1 AND p.id_manufacturer=m.id_manufacturer)";
   $res=dbquery($query);
   $cnt = mysqli_num_rows($res);
   while($row = mysqli_fetch_array($res))
   { $uquery = "UPDATE "._DB_PREFIX_."manufacturer SET active=1 WHERE id_manufacturer=".$row["id_manufacturer"];
	 $ures=dbquery($uquery);
   }
   echo '<script>alert("Finished: '.$cnt.' manufacturers were activated!");</script>';
 }
 else if($input['subject'] == "manufacturerdeactivate")
 { $query = "SELECT m.id_manufacturer FROM "._DB_PREFIX_."manufacturer m";
   $query .= " WHERE m.active=1 AND NOT EXISTS ";
   $query .= " (SELECT NULL FROM "._DB_PREFIX_."product p WHERE active=1 AND p.id_manufacturer=m.id_manufacturer)";
   $res=dbquery($query);
   $cnt = mysqli_num_rows($res);
   while($row = mysqli_fetch_array($res))
   { $uquery = "UPDATE "._DB_PREFIX_."manufacturer SET active=0 WHERE id_manufacturer=".$row["id_manufacturer"];
	 $ures=dbquery($uquery);
   }
   echo '<script>alert("Finished: '.$cnt.' manufacturers were deactivated!");</script>';
 }
 else if($input['subject'] == "indexate")
 { $products = $input["id_product"];
   $productset=preg_replace('/[^0-9,\-]+/','',$products);
   echo "===".$productset."---";
   if($productset=="")
     colordie('No products!<script>alert("No products!");</script>');
   $invalids = $validprods = $ranges = array();
   $prods = explode(",",$productset);
   foreach($prods AS $prod)
   { if(strpos($prod, '-') !== false)
	 { $parts = explode("-", $prod);
	   if(!is_numeric($parts[0]) || !is_numeric($parts[1])) 
	   { $invalids[] = $prod;
	     continue;
	   }
	   $ranges[] = array($parts[0],$parts[1]);
	 }
     else if(is_numeric($prod))
	 { $res=dbquery("SELECT id_product FROM "._DB_PREFIX_."product WHERE id_product=".$prod);
       if(mysqli_num_rows($res) == 0)
	     $invalids[] = $prod;
	   else
	     $validprods[] = $prod;
	 }
	 else
	   $invalids[] = $prod;
   }

   if(sizeof($invalids) > 0)
   { echo '<script>alert("The following are not valid product id\'s: '.implode(",",$invalids).'!");</script>';
   }
   if((sizeof($validprods) == 0) && (sizeof($ranges) == 0))
     return;
   $query = "SELECT GROUP_CONCAT(id_product) AS filterset FROM "._DB_PREFIX_."product_shop WHERE visibility IN ('both','search') AND active=1 AND (";
   $first = true;
   if(sizeof($validprods) > 0)
   { $query .= "id_product IN (".implode(",",$validprods).")";
     $first = false;
   }
   foreach($ranges AS $range)
   { if($first) $first=false; else $query .= " OR ";
     $query .= "(id_product >= ".$range[0]." AND id_product <= ".$range[1].")";
   }
   $query .= ")";
   $res=dbquery($query);
   $row = mysqli_fetch_array($res);
   echo "=*=".$row["filterset"]."-*-";
   $filtered = explode(",", $row["filterset"]);
   $invalids = array_diff($validprods,$filtered);
   if(sizeof($invalids) > 0)
   { echo '<script>alert("The following products are either inactive or have a wrong value for the visibility field: '.implode(",",$invalids).'!");</script>';
   }
   if((sizeof($filtered) == 0) || ($row["filterset"] == ""))
   { echo '<script>alert("Nothing to update!");</script>';
     return;
   }
   update_shop_index(10, $filtered);
   echo '<script>alert("Indexation finished!");</script>';
 }
 else if($input['subject'] == "analyzewords")
 { $words = $input["id_word"];
   $wordset=preg_replace('/^0-9,/','',$words);
   if($wordset=="")
     colordie('No words!<script>alert("No words!");</script>');
   $answers = $invalids = array();
   $wrds = explode(",",$wordset);
   foreach($wrds AS $wrd)
   { if(!is_numeric($wrd))
	 { $invalids[] = $wrd;
	   continue;
	 }
     $query = 'SELECT * FROM '._DB_PREFIX_.'search_word WHERE id_word="'.mysqli_real_escape_string($conn,$wrd).'"';
     $res=dbquery($query);
	 if(mysqli_num_rows($res) == 0)
	   $invalids[] = $wrd;
	 else
	 { $row = mysqli_fetch_array($res);
	   $answers[] = $wrd."=".$row["word"]."-".$row["id_shop"]."-".$row["id_lang"];
	 }
   }
   if(sizeof($invalids) > 0)
   { echo '<script>alert("The following are no valid word id\'s: '.implode(",",$invalids).'!");</script>';
   }
   echo "<script>alert('The following words were found: ".implode(",",$answers)."');</script>!";
 } 
 else if($input['subject'] == "sqlcutter")
 { $fp = fopen($_FILES["sqlfile"]["tmp_name"], "r");
   header('Content-Description: File Transfer');
   header('Content-Type: application/octet-stream');
   header('Content-Disposition: attachment; filename=clean'.time().'.sql');
   header('Expires: 0');
   header('Cache-Control: must-revalidate');
   header('Pragma: public');
   $out = fopen('php://output', 'w');
   $extables = explode(",",str_replace(' ','',$input['extables']));
   $writing = true;
   while (($line = fgets($fp, 40960)) !== false)
   { $seg = substr($line, 0, 12);
	 if ($seg == "CREATE TABLE")
	 { if(!$writing)
		 $writing = true;
	 }
	 else if ($seg == "INSERT INTO ")
	 { $pos = strpos($line, '(');
	   $tabname = trim(str_replace('`','',substr($line, 12, ($pos-12))));
	   if(in_array($tabname, $extables))
	   { $writing = false;
	   }
	 }
	 if($writing)
		 fwrite($out, $line);
   }
   fclose($out);
 }
 else
 { echo "<script>alert('Unknown subject ".$input['subject']."');</script>!";
   die("Unknown subject ".$input['subject']);
 }
 echo "Finished!";
 mysqli_close($conn);
 
