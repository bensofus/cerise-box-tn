<?php 
if(!@include 'approve.php') die( "approve.php was not found!");
$input = $_GET;
if(!isset($integrity_repair_allowed)) $integrity_repair_allowed = true;
if(!isset($integrity_delete_allowed)) $integrity_delete_allowed = false;

/* get default language: we use this for the categories, manufacturers */
if(!($id_lang_default = get_configuration_value('PS_LANG_DEFAULT')))
  colordie("No default language found!");
if(!($id_shop_default = get_configuration_value('PS_SHOP_DEFAULT')))
  colordie("No default shop found!");

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
$shoplist = implode(",",$shops);

$shoplangs = array();
$resx = dbquery("SELECT concat(id_shop,'-',id_lang) AS ident FROM "._DB_PREFIX_."lang_shop ORDER BY id_shop,id_lang");
while ($rowx=mysqli_fetch_array($resx)) 
	$shoplangs[] = $rowx["ident"];

?><!DOCTYPE html> 
<html lang="en"><head><meta charset="utf-8">
<title>Prestashop Integrity Checks</title>

<style>
.comment {background-color:#aabbcc}
h2 { margin-bottom:5px; margin-top:22px;}
form { margin-top: 8px; }
</style>
<script type="text/javascript" src="utils8.js"></script>
<script type="text/javascript" src="sorter.js"></script>
<script>
function repairhomecats(flag)
{ var f = document.createElement("form");
  f.setAttribute('method',"post");
  f.setAttribute('action',"integrity-repair.php");
  f.setAttribute('target',"tank");
  var i = document.createElement("input"); //input element, text
  i.setAttribute('type',"hidden");
  i.setAttribute('name',"subject");
  if(flag==1)
    i.setAttribute('value',"repairhomes");
  else
    i.setAttribute('value',"repairallhomes");
  f.appendChild(i);
  document.getElementsByTagName('body')[0].appendChild(f);
  f.submit();	
}


function repair_categorys()
{ if(!catrepairform.pagree.checked) 
  { alert("You need to agree before you can repair!");
	return false;
  }
  catrepairform.submit();
}

function agreeformprepare(formname)
{ var myform = eval(formname);
  if(!myform.pagree.checked) 
  { alert("You need to agree before you can repair!");
	return false;
  }
  if(formname == "removeform")
  { var delprods = removeform.delproducts.value.split(",");
    for(var i=0; i< delprods.length; i++)
    { if(problems.indexOf(delprods[i]) === -1)
      { alert("You can only erase problematic products that were flagged. "+delprods[i]+" is not problematic.");
	    return false;
      }
    }
  }
  myform.verbose.value = configform.verbose.checked; 
  myform.submit();
}

function formprepare(formname)
{ var myform = eval(formname);
  if(!myform.pagree.checked) 
  { alert("You need to agree before you can repair!");
	return false;
  }
  myform.verbose.value = configform.verbose.checked;  
}
</script>
<link rel="stylesheet" href="style1.css" type="text/css" />
</head><body>
<?php print_menubar(); ?>
<div style="float:right; "><iframe name=tank width=230 height=93></iframe></div>
<h1>Integrity Checks</h1>
<form name=configform><input type=checkbox name=verbose checked> verbose</form>
On this page you will see the results of some data integrity checks for a.o. products and categories in your database. You will be offered repair and delete options and sometimes the option to view and edit the situation. 
<p>If there is an image for a product, the product id links to it.
If the image has a legend, you will see it when you hover the mouse over the link.
<p>Use the "functional integrity constraints" option of
<a href="https://github.com/PrestaShop/pscleaner/">PSCleaner</a> if you want to remove anything
that doesn't fit. This module is included with some Prestashop versions. Be sure to make a backup 
of the database before using ps_cleaner. This software is no longer supported and shouldn't be used for multishop installations.
(For Thirty Bees TBCleaner has the same role). 
<?php 
echo '
<h2 style="color:red; background-color:yellow;">Repair only when you know what you are doing!</h2>
 - Integrity Checks focuses on the core of the shop: products and categories. Cleanup focuses on everything else.
 - Running the routines under Cleanup first can significantly reduce the number of issues reported here.
 - In settings1.php you can set whether repair is allowed.<br>
 - With multishop you may see a product mentioned for each shop.<br>
 - Use the delete only to remove product remains that you can\'t get rid off in the Prestashop backoffice<br>
 - Repaired products and categories for which no name is available will be called "dummy" followed by a number after repair<br>
 - There is always some risk with this kind of operation - specially when you have modules installed that
 change the database. Sometimes it helps to reset or remove and re-install such modules. Make a backup<br>
 - When you choose repair categories without product or subcategory will be deleted. In all other cases the missing fields
will be restored. Categories with missing parent will be placed under Home.
 - The Repair and Delete options are recommended for small numbers of issues. When you have hundreds or thousands of issues you are recommended to use the functions at the bottom of the page first.
 - Work from the top to the bottom: when the first three entries of "products 2: more serious problems" are ok you know that all products are either in all three or in none of the three. At that point you can refresh and the rest is clearer.
 ';

if(defined('_TB_VERSION_'))
{ echo '<h2>Duplicate urls in Thirty Bees</h2>';
  echo 'When more than one of the products is active the line is bold.<br>';
  echo 'Product numbers of active products are bold<br>';
  $query = "SELECT cl.id_lang, cl.value, l.iso_code FROM "._DB_PREFIX_."configuration c";
  $query .= " LEFT JOIN "._DB_PREFIX_."configuration_lang cl ON c.id_configuration=cl.id_configuration";
  $query .= " INNER JOIN "._DB_PREFIX_."lang l ON cl.id_lang=l.id_lang";
  $query .= " WHERE c.name='PS_ROUTE_product_rule' AND l.active=1";
  $res=dbquery($query);
  /* the query usually delivers {categories:/}{rewrite} for a fresh TB and something like  {category:/}{id}{-:id_product_attribute}-{rewrite}.html for PS */
  /* Note that in a freshly installed PS 1.7 the ROUTE entries are not yet present in the onfiguration table of the database */
  while ($row=mysqli_fetch_array($res)) 
  { if(strpos($row["value"],"{id}") === false)
	{ foreach($shops AS $shop)
	  { echo "shop=".$shop.",lang=".$row["iso_code"].":<br>";
	    $squery = "SELECT pl.link_rewrite, cl.link_rewrite AS cat_rewrite, GROUP_CONCAT(CONCAT(pl.id_product,'-',ps.active)) AS ids, COUNT(*) AS cnt, SUM(ps.active) AS activecnt FROM "._DB_PREFIX_."product_lang pl";
		$squery .= " LEFT JOIN "._DB_PREFIX_."product_shop ps ON ps.id_product=pl.id_product AND ps.id_shop=pl.id_shop";
		$squery .= " LEFT JOIN "._DB_PREFIX_."category_lang cl ON ps.id_category_default=cl.id_category AND cl.id_shop=".$shop." AND cl.id_lang=pl.id_lang";
	    $squery .= " WHERE cl.id_shop=".$shop." AND cl.id_lang=".$row["id_lang"];
	    $squery .= " GROUP BY pl.link_rewrite,cl.link_rewrite HAVING cnt > 1";
		$sres=dbquery($squery);
		while ($srow=mysqli_fetch_array($sres)) 
		{ if($srow["activecnt"] > 1)
		    echo "<b> - ".$srow["cat_rewrite"]."/".$srow["link_rewrite"]." (";
		  else
		    echo " - ".$srow["cat_rewrite"]."/".$srow["link_rewrite"]." (";
		  $arr = explode(",",$srow["ids"]);
		  sort($arr);
		  $first = true;
		  foreach($arr AS $prod)
		  { if($first) $first=false; else echo ",";
		    $parts = explode("-",$prod);
		    if(($srow["activecnt"] > 1) && ($parts[1] == 0))
				echo "</b>".$parts[0]."<b>";
			else if(($srow["activecnt"] <= 1) && ($parts[1] == 1))
				echo "<b>".$parts[0]."</b>";
			else
				echo $parts[0];
		  }
		  if($srow["activecnt"] > 1)
			  echo "</b>";
		  echo ")<br>";
		}
		echo "<br>";
	  }
	}
  }
}

echo '<h2>Products 1: light problems </h2>';
echo "Products with a default category that no longer exists:";
$query = "SELECT ps.id_product, ps.id_shop, pl.name FROM "._DB_PREFIX_."product_shop ps";
$query .= " LEFT OUTER JOIN "._DB_PREFIX_."category c on ps.id_category_default=c.id_category";
$query .= " LEFT JOIN "._DB_PREFIX_."product_lang pl on ps.id_product=pl.id_product AND ps.id_shop=pl.id_shop AND pl.id_lang=".$id_lang_default;
$query .= " WHERE c.id_category is null ORDER BY ps.id_product";
$res=dbquery($query);
$numhomes = mysqli_num_rows($res);
$x=0;
while ($row=mysqli_fetch_array($res)) 
{ echo "<a href='product-solo.php?id_product=".$row['id_product']."&id_lang=".$id_lang_default."&id_shop=".$row['id_shop']."' title='".str_replace("'","",$row['name'])."' target='_blank'>".$row['id_product']."</a>,";
  if(!($x++%10)) echo " ";
}
if($numhomes > 0) echo ' &nbsp;<input type=button value="repair all" onclick="repairhomecats(1); return false;">';

if($numhomes == 0)
{ echo "<br>Products with a default category that is no longer connected to the product:";
  $query = "SELECT ps.id_product, ps.id_shop,pl.name FROM "._DB_PREFIX_."product_shop ps";
  $query .= " LEFT OUTER JOIN "._DB_PREFIX_."category_product cp ON ps.id_product=cp.id_product AND ps.id_category_default=cp.id_category";
  $query .= " LEFT JOIN "._DB_PREFIX_."product_lang pl on ps.id_product=pl.id_product AND ps.id_shop=pl.id_shop AND pl.id_lang=".$id_lang_default;
  $query .= " WHERE cp.id_category is null ORDER BY ps.id_product";
  $res=dbquery($query);
  $numhomes = mysqli_num_rows($res);
  $x=0;
  while ($row=mysqli_fetch_array($res)) 
  { echo "<a href='product-solo.php?id_product=".$row['id_product']."&id_lang=".$id_lang_default."&id_shop=".$row['id_shop']."' title='".str_replace("'","",$row['name'])."' target='_blank'>".$row['id_product']."</a>,";
    if(!($x++%10)) echo " ";
  }
  if(mysqli_num_rows($res) > 0) echo ' &nbsp;<input type=button value="repair all" onclick="repairhomecats(2); return false;">';
}

if($numhomes == 0)
{ echo "<br>Active products with an inactive default category:";
$query = "SELECT ps.id_product, ps.id_shop, pl.name FROM "._DB_PREFIX_."product_shop ps";
$query .= " LEFT JOIN "._DB_PREFIX_."category c on ps.id_category_default=c.id_category";
$query .= " LEFT JOIN "._DB_PREFIX_."product_lang pl on ps.id_product=pl.id_product AND ps.id_shop=pl.id_shop AND pl.id_lang=".$id_lang_default;
$query .= " WHERE c.active=0 AND ps.active=1 ORDER BY ps.id_product";
  $res=dbquery($query);
  $numhomes = mysqli_num_rows($res);
  $x=0;
  while ($row=mysqli_fetch_array($res)) 
  { echo "<a href='product-solo.php?id_product=".$row['id_product']."&id_lang=".$id_lang_default."&id_shop=".$row['id_shop']."' title='".str_replace("'","",$row['name'])."' target='_blank'>".$row['id_product']."</a>,";
    if(!($x++%10)) echo " ";
  }
//  if(mysqli_num_rows($res) > 0) echo ' &nbsp;<input type=button value="repair all" onclick="repairhomecats(2); return false;">';
}

echo "<br>Active products without an active category:";
$query = "SELECT ps.id_product, ps.id_shop, pl.name FROM "._DB_PREFIX_."product_shop ps";
$query .= " LEFT JOIN "._DB_PREFIX_."product_lang pl on ps.id_product=pl.id_product AND ps.id_shop=pl.id_shop AND pl.id_lang=".$id_lang_default;
$query .= " LEFT OUTER JOIN ";
$query .= "(SELECT cp.* FROM "._DB_PREFIX_."category_product cp";
$query .= " LEFT JOIN "._DB_PREFIX_."category c on cp.id_category=c.id_category WHERE c.active=1) cx";
$query .= " ON ps.id_product=cx.id_product";
$query .= " WHERE cx.id_category is null AND ps.active=1 ORDER BY ps.id_product";
$res=dbquery($query);
$numhomes = mysqli_num_rows($res);
$x=0;
while ($row=mysqli_fetch_array($res)) 
{ echo "<a href='product-solo.php?id_product=".$row['id_product']."&id_lang=".$id_lang_default."&id_shop=".$row['id_shop']."' title='".str_replace("'","",$row['name'])."' target='_blank'>".$row['id_product']."</a>,";
  if(!($x++%10)) echo " ";
}

echo '<h2>Products 2: more serious problems </h2>';

$problem_products = array();

echo "Products in "._DB_PREFIX_."product that are not in "._DB_PREFIX_."product_shop: ";
$query = "SELECT p.id_product FROM "._DB_PREFIX_."product p";
$query .= " LEFT OUTER JOIN "._DB_PREFIX_."product_shop ps on p.id_product=ps.id_product";
$query .= " WHERE ps.id_shop is null ORDER BY p.id_product";
$res=dbquery($query);
$x=0;
while ($row=mysqli_fetch_array($res)) 
{ printprod($row["id_product"]).",";
  $problem_products[] = $row["id_product"];
  if(!($x++%10)) echo " ";
}

echo "<br>Products in "._DB_PREFIX_."product that are not in "._DB_PREFIX_."product_lang: ";
$x=0;
foreach($languages AS $id_lang)
{ foreach($shops AS $id_shop)
  { if(!in_array($id_shop."-".$id_lang, $shoplangs)) continue;
    $query = "SELECT p.id_product FROM "._DB_PREFIX_."product p";
    $query .= " INNER JOIN "._DB_PREFIX_."product_shop ps on p.id_product=ps.id_product AND ps.id_shop=".$id_shop;
    $query .= " LEFT OUTER JOIN "._DB_PREFIX_."product_lang pl on p.id_product=pl.id_product AND pl.id_shop=ps.id_shop AND pl.id_lang=".$id_lang;
    $query .= " WHERE pl.id_shop is null ORDER BY p.id_product";
    $res=dbquery($query);
	if(mysqli_num_rows($res) > 0) echo "<br>Lang".$id_lang."(".$langnames[$id_lang].")-Shop".$id_shop.": ";
    while ($row=mysqli_fetch_array($res)) 
    { printprod($row["id_product"]).",";
	  $problem_products[] = $row["id_product"];
	  if(!($x++%10)) echo " ";
    }
  }
}
  
echo "<br>Products in "._DB_PREFIX_."product_shop that are not in "._DB_PREFIX_."product: ";
$query = "SELECT DISTINCT ps.id_product FROM "._DB_PREFIX_."product_shop ps";
$query .= " LEFT OUTER JOIN "._DB_PREFIX_."product p on p.id_product=ps.id_product";
$query .= " WHERE p.id_shop_default is null ORDER BY ps.id_product";
$res=dbquery($query);
$x=0;
while ($row=mysqli_fetch_array($res)) 
{ printprod($row["id_product"]).",";
  $problem_products[] = $row["id_product"];
  if(!($x++%10)) echo " ";
}

echo "<br>Products in "._DB_PREFIX_."product_lang that are not in "._DB_PREFIX_."product: ";
$query = "SELECT DISTINCT pl.id_product FROM "._DB_PREFIX_."product_lang pl";
$query .= " LEFT OUTER JOIN "._DB_PREFIX_."product p on p.id_product=pl.id_product";
$query .= " WHERE p.id_shop_default is null ORDER BY pl.id_product";
$res=dbquery($query);
$x=0;
while ($row=mysqli_fetch_array($res)) 
{ printprod($row["id_product"]).",";
  $problem_products[] = $row["id_product"];
  if(!($x++%10)) echo " ";
}

echo "<br>Products in "._DB_PREFIX_."product_lang that are not in the connected "._DB_PREFIX_."product_shop: ";
$query = "SELECT DISTINCT pl.id_product FROM "._DB_PREFIX_."product_lang pl";
$query .= " LEFT OUTER JOIN "._DB_PREFIX_."product_shop ps on ps.id_shop=pl.id_shop AND ps.id_product=pl.id_product";
$query .= " WHERE ps.id_shop is null ORDER BY pl.id_product";
$res=dbquery($query);
$x=0;
while ($row=mysqli_fetch_array($res)) 
{ printprod($row["id_product"]).",";
  $problem_products[] = $row["id_product"];
  if(!($x++%10)) echo " ";
}

echo "<br>Products with an empty name in at least one shop-language combination: ";
$query = "SELECT pl.id_product,pl.id_lang,pl.id_shop, COUNT(*) AS emptycount FROM "._DB_PREFIX_."product_lang pl";
$query .= " INNER JOIN "._DB_PREFIX_."product_shop ps ON ps.id_product=pl.id_product AND ps.id_shop=pl.id_shop";
$query .= " WHERE TRIM(name)='' GROUP BY id_product HAVING emptycount>0 ORDER BY id_product";
$res=dbquery($query);
$x=0;
while ($row=mysqli_fetch_array($res)) 
{ printprod($row["id_product"]).",";
  $problem_products[] = $row["id_product"];
  if(!($x++%10)) echo " ";
}

echo "<br>Products with an empty link_rewrite in at least one shop-language combination: ";
$query = "SELECT pl.id_product,pl.id_lang,pl.id_shop, COUNT(*) AS emptycount FROM "._DB_PREFIX_."product_lang pl";
$query .= " INNER JOIN "._DB_PREFIX_."product_shop ps ON ps.id_product=pl.id_product AND ps.id_shop=pl.id_shop";
$query .= " WHERE TRIM(link_rewrite)='' GROUP BY id_product HAVING emptycount>0 ORDER BY id_product";
$res=dbquery($query);
$x=0;
while ($row=mysqli_fetch_array($res)) 
{ printprod($row["id_product"]).",";
  $problem_products[] = $row["id_product"];
  if(!($x++%10)) echo " ";
}

echo "<br>Products where the friendly url generation failed in at least one language/shop combination: ";
$query = "SELECT DISTINCT(id_product) FROM "._DB_PREFIX_."product_lang pl";
$query .= " WHERE link_rewrite='friendly-url-autogeneration-failed' ORDER BY id_product";
$res=dbquery($query);
$x=0;
while ($row=mysqli_fetch_array($res)) 
{ printprod($row["id_product"]).",";
  $problem_products[] = $row["id_product"];
  if(!($x++%10)) echo " ";
}



echo "<br>Products in "._DB_PREFIX_."category_product that are not in "._DB_PREFIX_."product: ";
$query = "SELECT DISTINCT cp.id_product FROM "._DB_PREFIX_."category_product cp";
$query .= " LEFT OUTER JOIN "._DB_PREFIX_."product p on p.id_product=cp.id_product";
$query .= " WHERE p.id_product is null ORDER BY cp.id_product";
$res=dbquery($query);
$x=0;
while ($row=mysqli_fetch_array($res)) 
{ printprod($row["id_product"]).",";
  $problem_products[] = $row["id_product"];
  if(!($x++%10)) echo " ";
}

echo "<br>Products in "._DB_PREFIX_."product that are not in "._DB_PREFIX_."category_product: ";
$query = "SELECT DISTINCT p.id_product,name FROM "._DB_PREFIX_."product p";
$query .= " LEFT JOIN "._DB_PREFIX_."product_lang pl ON p.id_product=pl.id_product AND pl.id_lang=".$id_lang_default;
$query .= " LEFT OUTER JOIN "._DB_PREFIX_."category_product cp on p.id_product=cp.id_product";
$query .= " WHERE cp.id_product is null ORDER BY p.id_product";
$res=dbquery($query);
$x=0;
while ($row=mysqli_fetch_array($res)) 
{ echo "<a href='product-solo.php?id_product=".$row['id_product']."&id_lang=".$id_lang_default."&id_shop=".$id_shop_default."' title='".str_replace("'","",$row['name'])."' target='_blank'>".$row['id_product']."</a>,";
  $problem_products[] = $row["id_product"];
  if(!($x++%10)) echo " ";
}

echo "<br>Images from which the product id is not in "._DB_PREFIX_."product (id_product shown): ";
$query = "SELECT DISTINCT i.id_product FROM "._DB_PREFIX_."image i";
$query .= " LEFT OUTER JOIN "._DB_PREFIX_."product p on p.id_product=i.id_product";
$query .= " WHERE p.id_shop_default is null ORDER BY i.id_product";
$res=dbquery($query);
$x=0;
while ($row=mysqli_fetch_array($res)) 
{ printprod($row["id_product"]);
  $problem_products[] = $row["id_product"];
  if(!($x++%10)) echo " ";
}

echo "<script>problems=['".implode("','",$problem_products)."'];</script>";

if($integrity_repair_allowed &&(sizeof($problem_products)>0))
{ $prods = array_unique($problem_products);
  sort($prods);
  echo '<p>Product repair will match the ps_product, ps_product_lang and ps_product_shop tables. If a 
product id is present in any of these tables it will create entries for the other(s). If a product has no
category it will place it in Home. Some of these will be products that got damaged. Others will be rests
of product deletions that didn\'t go well. After you have run this you should go to your backoffice to 
delete those products that you don\'t want. You also may need to fill in names and descriptions. After repair
each product will have a translation for all installed languages. If a product was not assigned to a shop
it will be assigned to the shop with the lowest shop id or its default shop. 
Images without product are not handled.<br>
<b>This will not fix "light" problems</b>
<form name="repairform" target="tank" action="integrity-repair.php" method=post onsubmit=\'return agreeformprepare("repairform")\'>
<input type="hidden" name="subject" value="productrepair"><input type="hidden" name="verbose">
<input type=checkbox name="pagree"> I did make a backup of the database and want to repair the following product entries now.<br>
<input name="products" value = "'.implode(",",$prods).'" style="width:500px">
<button>Repair products</button></form>';
}

if($integrity_delete_allowed &&(sizeof($problem_products)>0))
{ $prods = array_unique($problem_products);
  echo '<form name="removeform" target="tank" action="integrity-repair.php" method=post onsubmit=\'return agreeformprepare("removeform")\'>
<input type="hidden" name="subject" value="productremove"><input type="hidden" name="verbose">
<input type=checkbox name="pagree"> I did make a backup of the database and want to delete the following product entries now.<br>
<input name="delproducts" value = "" style="width:500px">
<button>Delete products</button></form>';
}

echo '<h2>Categories</h2>';
$problem_categorys = array();
echo "Categories in "._DB_PREFIX_."category that are not in "._DB_PREFIX_."category_shop: ";
$query = "SELECT c.id_category FROM "._DB_PREFIX_."category c";
$query .= " LEFT OUTER JOIN "._DB_PREFIX_."category_shop cs on c.id_category=cs.id_category";
$query .= " WHERE cs.id_shop is null ORDER BY c.id_category";
$res=dbquery($query);
$x=0;
while ($row=mysqli_fetch_array($res)) 
{ printcat($row["id_category"]).",";
  $problem_categorys[] = $row["id_category"];
  if(!($x++%10)) echo " ";
}

echo "<br>Categories in "._DB_PREFIX_."category that are not in "._DB_PREFIX_."category_lang: ";
$x=0;
foreach($languages AS $id_lang)
{ foreach($shops AS $id_shop)
  { if(!in_array($id_shop."-".$id_lang, $shoplangs)) continue;
    $query = "SELECT c.id_category FROM "._DB_PREFIX_."category c";
    $query .= " INNER JOIN "._DB_PREFIX_."category_shop cs on c.id_category=cs.id_category AND cs.id_shop=".$id_shop;
    $query .= " LEFT OUTER JOIN "._DB_PREFIX_."category_lang cl on c.id_category=cl.id_category AND cl.id_shop =cs.id_shop AND cl.id_lang=".$id_lang;
    $query .= " WHERE cl.id_shop is null ORDER BY c.id_category";
    $res=dbquery($query);
	if(mysqli_num_rows($res) > 0) echo "<br>Lang".$id_lang."(".$langnames[$id_lang].")-Shop".$id_shop.": ";
    while ($row=mysqli_fetch_array($res)) 
    { printcat($row["id_category"]).",";
	  $problem_categorys[] = $row["id_category"];
      if(!($x++%10)) echo " ";
    }
  }
}

echo "<br>Categories in "._DB_PREFIX_."category that are not in "._DB_PREFIX_."category_group: ";
$query = "SELECT c.id_category FROM "._DB_PREFIX_."category c";
$query .= " LEFT OUTER JOIN "._DB_PREFIX_."category_group cg on c.id_category=cg.id_category";
$query .= " WHERE cg.id_category is null AND c.id_parent != 0 ORDER BY c.id_category";
$res=dbquery($query); /* the root category had no groups */
$x=0;
while ($row=mysqli_fetch_array($res)) 
{ printcat($row["id_category"]).",";
  $problem_categorys[] = $row["id_category"];
  if(!($x++%10)) echo " ";
}
  
echo "<br>Categories in "._DB_PREFIX_."category_shop that are not in "._DB_PREFIX_."category: ";
$query = "SELECT DISTINCT cs.id_category FROM "._DB_PREFIX_."category_shop cs";
$query .= " LEFT OUTER JOIN "._DB_PREFIX_."category c on c.id_category=cs.id_category";
$query .= " WHERE c.id_shop_default is null ORDER BY cs.id_category";
$res=dbquery($query);
$x=0;
while ($row=mysqli_fetch_array($res)) 
{ printcat($row["id_category"]).",";
  $problem_categorys[] = $row["id_category"];
  if(!($x++%10)) echo " ";
}

echo "<br>Categories in "._DB_PREFIX_."category_lang that are not in "._DB_PREFIX_."category: ";
$query = "SELECT DISTINCT cl.id_category FROM "._DB_PREFIX_."category_lang cl";
$query .= " LEFT OUTER JOIN "._DB_PREFIX_."category c on c.id_category=cl.id_category";
$query .= " WHERE c.id_shop_default is null ORDER BY cl.id_category";
$res=dbquery($query);
$x=0;
while ($row=mysqli_fetch_array($res)) 
{ printcat($row["id_category"]).",";
  $problem_categorys[] = $row["id_category"];
  if(!($x++%10)) echo " ";
}

echo "<br>Categories in "._DB_PREFIX_."category_group that are not in "._DB_PREFIX_."category: ";
$query = "SELECT DISTINCT cg.id_category FROM "._DB_PREFIX_."category_group cg";
$query .= " LEFT OUTER JOIN "._DB_PREFIX_."category c on c.id_category=cg.id_category";
$query .= " WHERE c.id_shop_default is null ORDER BY cg.id_category";
$res=dbquery($query);
$x=0;
while ($row=mysqli_fetch_array($res)) 
{ printcat($row["id_category"]).",";
  $problem_categorys[] = $row["id_category"];
  if(!($x++%10)) echo " ";
}

echo "<br>Categories in "._DB_PREFIX_."category_lang that are not in the connected "._DB_PREFIX_."category_shop: ";
$query = "SELECT DISTINCT cl.id_category FROM "._DB_PREFIX_."category_lang cl";
$query .= " LEFT OUTER JOIN "._DB_PREFIX_."category_shop cs on cs.id_shop=cl.id_shop AND cs.id_category=cl.id_category";
$query .= " WHERE cs.id_shop is null ORDER BY cl.id_category";
$res=dbquery($query);
$x=0;
while ($row=mysqli_fetch_array($res)) 
{ printcat($row["id_category"]).",";
  $problem_categorys[] = $row["id_category"];
  if(!($x++%10)) echo " ";
}

echo "<br>Categories in "._DB_PREFIX_."category_product that are not in "._DB_PREFIX_."category: ";
$query = "SELECT DISTINCT cp.id_category FROM "._DB_PREFIX_."category_product cp";
$query .= " LEFT OUTER JOIN "._DB_PREFIX_."category c on c.id_category=cp.id_category";
$query .= " WHERE c.id_category is null ORDER BY cp.id_product";
$res=dbquery($query);
$x=0;
while ($row=mysqli_fetch_array($res)) 
{ printprod($row["id_category"]).",";
  $problem_categorys[] = $row["id_category"];
  if(!($x++%10)) echo " ";
}

echo "<br>Categories with an empty name: ";
$query = "SELECT id_category,id_lang,id_shop FROM "._DB_PREFIX_."category_lang";
$query .= " WHERE TRIM(name)='' ORDER BY id_category";
$res=dbquery($query);
$x=0;
while ($row=mysqli_fetch_array($res)) 
{ printcat($row["id_category"])."(".$row["id_lang"].",".$row["id_shop"]."),";
  $problem_categorys[] = $row["id_category"];
  if(!($x++%10)) echo " ";
}

$squery = "SELECT s.id_category, s.name FROM "._DB_PREFIX_."shop s";
$squery .= " LEFT OUTER JOIN "._DB_PREFIX_."category c on s.id_category=c.id_category";
$squery .= " WHERE c.id_category is null ORDER BY s.id_shop";
$sres=dbquery($squery);
if(mysqli_num_rows($sres) > 0)
{ echo "<br>Shops without valid home category found: ";
  $x=0;
  while($srow=mysqli_fetch_assoc($sres))
  { if($x++ > 0) echo ", ";
    echo '<a href="#" title="'.$srow["name"].'" onclick="return false;">'.$srow["id_category"].'</a>';
  }
  die("<br>This cannot be repaired with this script.");
}

if($integrity_repair_allowed &&(sizeof($problem_categorys)>0))
{ $cats = array_unique($problem_categorys);
  sort($cats);
  echo '
<form name="catrepairform" target="tank" action="integrity-repair.php" method=post onsubmit=\'return agreeformprepare("catrepairform")\'>
<input type="hidden" name="subject" value="categoryrepair"><input type="hidden" name="verbose">
<input type=checkbox name="pagree"> I did make a backup of the database and want to repair the category entries now.<br>
<input name="categorys" value = "'.implode(",",$cats).'" style="width:500px">
<button>Repair categories</button></form>';
}

echo '<h2>Category Tree</h2>';
$problem_categorys = array();
echo "Categories without valid parent: ";
$query = "SELECT DISTINCT c.id_category FROM "._DB_PREFIX_."category c";
$query .= " LEFT OUTER JOIN "._DB_PREFIX_."category c2 on c.id_parent=c2.id_category";
$query .= " WHERE c2.id_category is null AND c.id_parent!='0' ORDER BY c.id_category";
$res=dbquery($query);
$x=0;
while ($row=mysqli_fetch_array($res)) 
{ printcat($row["id_category"]).",";
  $problem_categorys[] = $row["id_category"];
  if(!($x++%10)) echo " ";
}

echo "<br>Category tree foundation problems: ";
$res=dbquery("SELECT id_category FROM "._DB_PREFIX_."category WHERE id_parent=0");
if(mysqli_num_rows($res)== 0) echo "No root category found!";
if(mysqli_num_rows($res)>1)
{ echo "More than one root category found: ";
  while($row = mysqli_fetch_assoc($res))
  { printcat($row["id_category"]).",";
    $problem_categorys[] = $row["id_category"];
    if(!($x++%10)) echo " ";
  }
}
$row = mysqli_fetch_assoc($res);
$rootcat = intval($row["id_category"]);
if($rootcat == 0) colordie("Problem finding root");

echo "<br>Unconnected Categories: ";
$query = 'SELECT c.`id_category`, c.`id_parent`, c.`level_depth` FROM '._DB_PREFIX_.'category c';
$query .= ' LEFT JOIN '._DB_PREFIX_.'category_shop cs ON c.`id_category` = cs.`id_category` AND cs.`id_shop` = '.$id_shop_default;
$query .= ' ORDER BY c.`id_parent`, cs.`position` ASC';
$res = dbquery($query);

$uccategories = array();
$levels = array();
$categoriesArray = array();
while($category = mysqli_fetch_array($res))
{	$categoriesArray[$category['id_parent']]['subcategories'][] = $category['id_category'];
	$uccategories[] = $category['id_category'];
	$levels[$category['id_category']] = $category['level_depth'];
}
$ucparents = array_keys($categoriesArray);
$pos = array_search('0', $ucparents);
array_splice($ucparents, $pos,1);

$n = 0;
if (isset($categoriesArray[0]) && $categoriesArray[0]['subcategories']) {
	_subTreeSub($categoriesArray, 0, $n);
}

$x=0;
if(sizeof($ucparents) != 0)
{ foreach($ucparents AS $ucparent)
  { printcat($ucparent).",";
    $problem_categorys[] = $ucparent;
    if(!($x++%10)) echo " ";
  }
}
echo "|";
$uccategories = array_diff($uccategories,$ucparents);
if(sizeof($uccategories) != 0) 
{ foreach($uccategories AS $uccategory)
  { printcat($uccategory).",";
    $problem_categorys[] = $uccategory;
    if(!($x++%10)) echo " ";
  }
}

if($integrity_repair_allowed &&(sizeof($problem_categorys)>0))
{ $cats = array_unique($problem_categorys);
  sort($cats);
  echo '<p>Repairing a damaged category tree structure is risky. So make a backup. And look afterwards at your sitemap
  to find out what the new structure of your category tree is. In most cases unconnected categories will be placed under
  the home category of the default shop.
<form name="cattreerepairform" target="tank" action="integrity-repair.php" method=post onsubmit=\'return agreeformprepare("categorytreerepairform")\'>
<input type="hidden" name="subject" value="categorytreerepair"><input type="hidden" name="verbose">
<input type=checkbox name="pagree"> I did make a backup of the database and want to repair the category entries now.<br>
<input name="categorys" value = "'.implode(",",$cats).'" style="width:500px">
<button>Repair category tree</button></form>';
}

echo '<h2>Attributes</h2>';
echo "Products with non-existing attributes.";
$res = dbquery("SELECT * FROM "._DB_PREFIX_."module WHERE name='attributewizardpro'");
if(mysqli_num_rows($res) > 0)
	echo "<br><b>Attribute WizardPro detected. Attributes are not checked!</b><p>";
else
{ echo "The missing attribute id\'s are between brackets; if there is more than one occurrence a frequency is added between square brackets. Note that some modules change the behavior of attributes and cause innocent reports here: ";
  $query = "SELECT pa.id_product,GROUP_CONCAT(DISTINCT pac.id_attribute) AS attributes, COUNT(*) AS acount, pl.name, pa.id_product_attribute FROM "._DB_PREFIX_."product_attribute pa";
  $query .= " LEFT JOIN "._DB_PREFIX_."product_lang pl on pa.id_product=pl.id_product AND pl.id_shop=".$id_shop_default." AND pl.id_lang=".$id_lang_default;
  $query .= " INNER JOIN "._DB_PREFIX_."product_attribute_combination pac ON pa.id_product_attribute=pac.id_product_attribute";
  $query .= " LEFT OUTER JOIN "._DB_PREFIX_."attribute a on pac.id_attribute=a.id_attribute";
  $query .= " WHERE a.id_attribute is null GROUP BY pa.id_product ORDER BY pa.id_product ";
  $res=dbquery($query);
  $x=0;
  $missing = array();
  while ($row=mysqli_fetch_array($res)) 
  { echo "<a href='combi-edit.php?id_product=".$row['id_product']."&id_shop=".$id_shop_default."' title='".str_replace("'","",$row['name'])."' target='_blank'>".$row['id_product']."</a>";
    echo "(".$row["attributes"];
    if($row["acount"] > 1)
      echo "[".$row["acount"]."]";
    echo "), ";
  }
}

  $squery = "SELECT DISTINCT pac.id_attribute FROM "._DB_PREFIX_."product_attribute_combination pac";
  $squery .= " LEFT OUTER JOIN "._DB_PREFIX_."attribute a on pac.id_attribute=a.id_attribute";
  $squery .= " WHERE a.id_attribute is null ORDER BY pac.id_attribute";
  $sres=dbquery($squery);
  echo "<br>Product attribute combination attributes that are not in "._DB_PREFIX_."attribute: ";
  while ($srow=mysqli_fetch_array($sres)) 
    echo $srow["id_attribute"].", ";

  $squery = "SELECT DISTINCT a.id_attribute, a.id_attribute_group FROM "._DB_PREFIX_."attribute a";
  $squery .= " LEFT OUTER JOIN "._DB_PREFIX_."attribute_group ag on a.id_attribute_group=ag.id_attribute_group";
  $squery .= " WHERE ag.id_attribute_group is null ORDER BY a.id_attribute_group";
  $sres=dbquery($squery);
  echo "<br>Attributes with a not-existing attribute group (group between brackets): ";
  while ($srow=mysqli_fetch_array($sres)) 
    echo $srow["id_attribute"]."[".$srow["id_attribute_group"]."], ";

echo '<h2>Images</h2>'; 
echo "Note: image id's that have a physical image are between brackets. When they are deleted the base image is moved to the /img/archive directory.";
  $problemimages = array();
  $squery = "SELECT DISTINCT i.id_image FROM "._DB_PREFIX_."image i";
  $squery .= " LEFT OUTER JOIN "._DB_PREFIX_."product p ON p.id_product=i.id_product";
  $squery .= " WHERE p.id_product is null ORDER BY i.id_image";
  $sres=dbquery($squery);
  echo "<br>Images whose product doesn't exist in "._DB_PREFIX_."product: ";
  while ($srow=mysqli_fetch_array($sres)) 
  {	if(image_exists($srow["id_image"]))
      echo "[".$srow["id_image"]."], ";
    else
      echo $srow["id_image"].", ";
    $problemimages[] = $srow["id_image"];
  }

  $squery = "SELECT DISTINCT il.id_image FROM "._DB_PREFIX_."image_lang il";
  $squery .= " LEFT OUTER JOIN "._DB_PREFIX_."image i ON i.id_image=il.id_image";
  $squery .= " WHERE i.id_image is null ORDER BY il.id_image";
  $sres=dbquery($squery);
  echo "<br>Images that are in the "._DB_PREFIX_."image_lang table but not in the "._DB_PREFIX_."image table:";
  while ($srow=mysqli_fetch_array($sres)) 
  {	if(image_exists($srow["id_image"]))
      echo "[".$srow["id_image"]."], ";
    else
      echo $srow["id_image"].", ";
    $problemimages[] = $srow["id_image"];
  }

  $squery = "SELECT DISTINCT ims.id_image FROM "._DB_PREFIX_."image_shop ims";
  $squery .= " LEFT OUTER JOIN "._DB_PREFIX_."image i ON i.id_image=ims.id_image";
  $squery .= " WHERE i.id_image is null ORDER BY ims.id_image";
  $sres=dbquery($squery);
  echo "<br>Images that are in the "._DB_PREFIX_."image_shop table but not in the "._DB_PREFIX_."image table:";
  while ($srow=mysqli_fetch_array($sres)) 
  {	if(image_exists($srow["id_image"]))
      echo "[".$srow["id_image"]."], ";
    else
      echo $srow["id_image"].", ";
    $problemimages[] = $srow["id_image"];
  }
  
$problemimages = array_unique($problemimages);
sort($problemimages);
  
if(sizeof($problemimages) > 0)
{ echo '<form name="imagecleanseform" target="tank" action="integrity-repair.php" method=post onsubmit=\'return agreeformpcleanserepare("imagecleanseform")\'>
<input type="hidden" name="subject" value="imagecleanse"><input type="hidden" name="verbose">
<input type=checkbox name="pagree"> I did make a backup of the database and want to delete the following image records now.<br>
<input name="problemimages" value = "'.implode(",",$problemimages).'" style="width:500px">
<button>Delete image records</button></form>';
}

  function image_exists($id_image)
  { global $triplepath;
    $fff = $triplepath.'img/p'.getpath($id_image).'/'.$id_image.".jpg";
	if(file_exists($fff))
	  return true;
    else
	  return false;
  }


  /* first get all valid tax groups */
  $id_country = get_configuration_value('PS_COUNTRY_DEFAULT');
  $query = "SELECT rate,name,tr.id_tax_rule,g.id_tax_rules_group,g.active,";
  if(version_compare(_PS_VERSION_ , "1.6.0.10", "<"))
    $query .= "0 AS gdeleted, t.deleted AS tdeleted";
  else
    $query .= "g.deleted AS gdeleted, t.deleted AS tdeleted";
  $query .= " FROM "._DB_PREFIX_."tax_rule tr";
  $query .= " LEFT JOIN "._DB_PREFIX_."tax t ON (t.id_tax = tr.id_tax)";
  $query .= " LEFT JOIN "._DB_PREFIX_."tax_rules_group g ON (tr.id_tax_rules_group = g.id_tax_rules_group)";
  $query .= " WHERE tr.id_country = '".$id_country."' AND tr.id_state='0' AND g.active=1 AND t.active=1";
  $res=dbquery($query);
  $validtaxgroups = array();
  while($row = mysqli_fetch_array($res))
  { if(($row["tdeleted"] == 0) && ($row["gdeleted"] == 0))
      $validtaxgroups[] = $row["id_tax_rules_group"];
  }
  
$ps_tax = get_configuration_value('PS_TAX'); /* flag that taxes are enabled */
if($ps_tax && (sizeof($validtaxgroups) > 0))
{ echo '<h2>Taxes</h2>';

  /* now count for each the shop the number of products without a valid tax group */
  $invalidtaxfound = false;
  foreach($shops AS $shop)
  { $query = "SELECT COUNT(*) AS prodcount";  // , GROUP_CONCAT(id_product) AS products
    $query .= " FROM "._DB_PREFIX_."product_shop";
    $query .= " WHERE NOT id_tax_rules_group IN (0,".implode(",",$validtaxgroups).") AND id_shop=".$shop;
    $res=dbquery($query);
	$row = mysqli_fetch_array($res);
	if($row["prodcount"] == 0)
	  echo "No products with invalid tax groups found for shop ".$shop."<br>";
	else
	{ $invalidtaxfound = true;
//	  echo $row["products"]."<br>";
	  echo $row["prodcount"]." products with invalid tax groups found for shop ".$shop;
	  echo ': <a href="product-edit.php?search_txt1=0,'.implode(",",$validtaxgroups).'&search_cmp1=not_eq&search_fld1=ps.id_tax_rules_group&id_shop='.$shop.'" target=_blank>view</a><br>';
	}	
  }
  if($invalidtaxfound)
    echo "Tax groups are considered not valid when either the tax_rules_group or the tax is not active or is flagged as deleted or the tax_rule is not defined for the default country<br>";
  echo "<br>";
	
  /* now look for products without a tax rules group */
  foreach($shops AS $shop)
  { $query = "SELECT COUNT(*) AS prodcount FROM "._DB_PREFIX_."product_shop";
    $query .= " WHERE id_tax_rules_group=0 AND id_shop=".$shop;
    $res=dbquery($query);
	$row = mysqli_fetch_array($res);
	if($row["prodcount"] == 0)
	  echo "No products with a zero tax group found for shop ".$shop."<br>";
	else
	{ echo $row["prodcount"]." products without an assigned tax rules group found for shop ".$shop;
	  echo ': <a href="product-edit.php?search_txt1=0&search_cmp1=eq&search_fld1=ps.id_tax_rules_group&id_shop='.$shop.'" target=_blank>view</a><br>';
	}	
  }
}


echo '<h2>Missing Translations:</h2>';
echo "This section shows id's for which a translation is missing. No fixes for these problems yet. Usually they will harmless.";
$langtables = array(
"attachment_lang" => array("id_attachment"),
"attribute_group_lang" => array("id_attribute_group"),
"attribute_lang" => array("id_attribute"),
"carrier_lang" => array("id_carrier", "id_shop"),
"cart_rule_lang" => array("id_cart_rule"),
"category_lang" => array("id_category"),
"cms_category_lang" => array("id_cms_category"),
"cms_lang" => array("id_cms"),
"feature_lang" => array("id_feature"),
"feature_value_lang" => array("id_feature_value"),
"gender_lang" => array("id_gender"),
"group_lang" => array("id_group"),
"image_lang" => array("id_image"),
"manufacturer_lang" => array("id_manufacturer"),
"meta_lang" => array("id_meta"),
"order_message_lang" => array("id_order_message"),
"order_return_state_lang" => array("id_order_return_state"),
"order_state_lang" => array("id_order_state"),
// "product_lang" => array("id_product"),
"profile_lang" => array("id_profile"),
"quick_access_lang" => array("id_quick_access"),
"risk_lang" => array("id_risk"),
"supplier_lang" => array("id_supplier"),
"supply_order_state_lang" => array("id_supply_order_state"),
"tab_lang" => array("id_tab"),
"tax_lang" => array("id_tax"));
foreach($langtables AS $langtable => $langkey)
{ foreach($languages AS $lang)
  { $maintable = substr($langtable,0,-5);
    $query = "SELECT m.id_".$maintable." AS mykey FROM "._DB_PREFIX_.$maintable." m";
    $query .= " LEFT JOIN "._DB_PREFIX_.$langtable." l ON m.id_".$maintable."=l.id_".$maintable." AND l.id_lang=".$lang;
	$query .= " WHERE l.id_".$maintable." IS NULL";
	$res = dbquery($query);
	if(mysqli_num_rows($res) > 0)
	{ echo "<br>".$langtable." with language ".$lang.": ";
	  while($row = mysqli_fetch_assoc($res))
	    echo $row["mykey"].", ";
	}
  }
}

echo '<h2>Tables with missing indexes:</h2>';
echo '<i>Your indexes are compared with a standard set. Some differences can be expected as not all PS/TB versions are the same.';
echo 'See the manual how you can create a reference for your webshop software version.</i>';
/* excluders are tables that are checked when present but not reported when missing */
$excluders = array("advice","advice_lang","badge","badge_lang"
,"cms_block","cms_block_lang","cms_block_page","cms_block_shop","cms_role","cms_role_lang"
,"compare","compare_product","condition","condition_advice","condition_badge","homeslider"
,"homeslider_slides","homeslider_slides_lang","layered_friendly_url","modules_perfs"
,"newsletter","scene","scene_category","scene_lang","scene_products","scene_shop"
,"smarty_last_flush","smarty_lazy_cache","tab_advice","tag_count"
,"theme","theme_meta","theme_specific","themeconfigurator"
);

if(!@include 'indexreference.php') die( "indexreference.php was not found!");
$tables = array();
$uniquers = array(); /* tables with unique index */
$prefixlen = strlen(_DB_PREFIX_);
$res = dbquery("SHOW TABLES");
/* first step: build tree for this shop */
while($row = mysqli_fetch_row($res))
{ if(substr($row[0],0,$prefixlen) != _DB_PREFIX_) 
	  continue;
  $tablename = substr($row[0],$prefixlen);
  if(!isset($tables[$tablename]))
	$tables[$tablename] = array();
  $ires = dbquery("SHOW INDEXES FROM ".$row["0"]);
  $tabkeys = array();
  while($irow = mysqli_fetch_assoc($ires))
  { if($irow["Non_unique"] == 0)
	  $uniquers[] = $tablename;
    if(!isset($tabkeys[$irow["Key_name"]]))
	  $tabkeys[$irow["Key_name"]] = array();
    $tabkeys[$irow["Key_name"]][] = $irow["Column_name"];
  }
  foreach($tabkeys AS $tabkey => $tabfields)
  { if(!isset($tables[$tablename][$tabkey]))
	  $tables[$tablename][$tabkey] = array();
	foreach($tabfields AS $tabfield)
	{ $tables[$tablename][$tabkey][] = $tabfield;
	}
  }
}
/* second step: compare the tree with that in indexreference.php */
$missingtables = array();
$notuniquers = array();
foreach($indexlist AS $reftable => $refindexes)
{ if(!isset($tables[$reftable])) 
  { if(!in_array($reftable, $excluders))
	{ echo "<br>table ".$reftable." missing"; 
	  $missingtables[] = $reftable;
	}
    continue; 
  }
  if((sizeof($refindexes) != sizeof($tables[$reftable])) && (sizeof($tables[$reftable])==0))
  { echo "<br>table ".$reftable." has ".sizeof($tables[$reftable])." indexes in this shop and ".sizeof($refindexes)." in the reference.";
  }
  if(!in_array($reftable, $uniquers))
    $notuniquers[] = $reftable;
  continue; /* code below can be used if you want more details about the differences */
  foreach($refindexes AS $refindex => $reffields)
  { if(!isset($tables[$reftable][$refindex])) 
    { echo "<br>index ".$refindex." for ".$reftable." missing"; 
      continue;
	}
    foreach($reffields AS $reffield)
    { if(!in_array($reffield, $tables[$reftable][$refindex])) { echo "<br>index ".$refindex." for ".$reftable." misses field ".$reffield; continue; }
    }
  }
}
$notuniquers = array_unique($notuniquers);
$notuniquers = array_diff($notuniquers, array("accessory","customer_message_sync_imap","linksmenutop_lang","order_detail_tax","order_invoice_tax","order_slip_detail_tax","wishlist_email","wishlist_product_cart"));
if(sizeof($notuniquers) > 0)
{ echo "<br>Tables without a unique index: <b>".implode("</b>, <b>",$notuniquers)."</b>";
  echo "<br>Note that cart_product and image_shop before PS 1.6.1 often didn't have a unique index.";
}

echo '<h2>Tables without correct auto-increment</h2>';
echo 'This function checks only a few tables. Problems here are usually related to incorrectly imported tables. Usually there was a timeout and the auto-increment setting - that happens at the end - was missed.<br>';
echo 'Note that when all relevant tables miss auto_increment (the first is address) the assigning of indexes like wasn\'t completed either and you should re-import the part of the sql file that wasn\'t processed.  ';
echo 'By clicking the table name you can repair them. Zero values for the key are partially deleted before auto-increase is applied. If you have more than a few missing auto-increments you should import the sql database fragment.<br>';
echo 'For checking third-party modules the script uses an algoritm that may make the wrong assumptions. Fix them only when needed.';
 $coretables = array("address","attribute","carrier","cart","category","cms","configuration","connections","contact","country","currency","customer","customization","employee","feature","gender","group","guest","hook","image","lang","mail","manufacturer","message","meta","module","orders","page","pagenotfound","product","shop","stock","store","supplier","tab","tax","zone");
/*
$mytables = array("address", "alias", "attachment", "attribute_group", "attribute_impact", "attribute", "block","carrier", "cart_rule", "cart", "category", "cms_block_page","cms_block", "cms_block", "cms_category", "cms_role", "cms", "compare", "configuration_kpi", 
"configuration", "connections_source", "connections", "contact", "country", "currency", "customer_message", "customer_thread", "customer", "customization_field", "customization", "date_range", "delivery", "employee", "feature_value", "feature", "gender", 
"group_reduction", "group", "guest", "homeslider_slides", "hook_alias", "hook_module_exceptions", "hook", "image_type", "image", "import_match","info", "item", "lang", "layered_category", "layered_filter", "layered_friendly_url",
"linksmenutop", "log", "mail", "manufacturer", "memcached_server", "message", "meta", "module_preference", "module", "modules_perfs", "operating_system", "order_carrier","order_cart_rule", "order_detail", "order_history", "order_invoice", 
"order_message", "order_payment", "order_return_state", "order_return", "order_slip", "order_state", "order", "page_type", "page", "pagenotfound", "product_attribute", "product_download", "product_rule_group", "product_rule", "product_supplier", "product",
 "profile_permission", "profile", "quick_access", "range_price", "range_weight", "redis_server", "referrer", "request_sql", "required_field", "risk", "scene", "scheduled_task_execution", "scheduled_task", "search_engine", "sekeyword", "shop_group",
 "shop_url", "shop", "specific_price_priority", "specific_price_rule_condition_group", "specific_price_rule_condition", "specific_price_rule", "specific_price", "state", "statssearch", "stock_available", "stock_mvt_reason", "stock_mvt",
"stock", "store", "supplier", "supply_order_detail", "supply_order_history", "supply_order_receipt_history", "supply_order_state", "supply_order", "tab_module_preference", "tab", "tag", "tax_rule", "tax_rules_group", "tax", "theme_meta",
 "theme", "timezone", "tracking_consent", "warehouse_product_location", "warehouse", "web_browser", "webservice_account", "webservice_permission", "word", "workqueue_task", "zone");
if(version_compare(_PS_VERSION_ , "1.7", "<"))
	$mytables[] = "theme";
$cnt = 0;
foreach ($mytables AS $mytable)
{ if(in_array($mytable,$missingtables)) continue;
*/
$query = "SHOW TABLES";
$res = dbquery($query); 
$len = strlen(_DB_PREFIX_);
$cnt=0;
while($row = mysqli_fetch_row($res))
{ $tablename = $row[0]; // echo $tablename."=";
  $mytable = substr($tablename,$len);
  if(in_array($mytable, array("page_cache","smarty_cache"))) continue;
  if(substr($tablename,0,$len) != _DB_PREFIX_) continue;
  $colname = "id_".$mytable;
  $iquery = "SELECT * FROM information_schema.`COLUMNS` WHERE table_schema = '"._DB_NAME_."' AND table_name = '".$tablename."' AND column_name='".$colname."'";  
  $ires = dbquery($iquery); 
  if(mysqli_num_rows($ires) > 0)
  { $squery = "SELECT `AUTO_INCREMENT` FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA='"._DB_NAME_."' AND TABLE_NAME='".$tablename."'";
    $sres = dbquery($squery); 
    if(mysqli_num_rows($sres) == 0) 
    { if(in_array($mytable,$coretables))
        echo "Table "._DB_PREFIX_.$mytable." is missing. ";
	}
    $srow = mysqli_fetch_array($sres);
    if(intval($srow['AUTO_INCREMENT']) == 0) { $cnt++; echo "<a href='integrity-repair.php?subject=repairautoincrease&table=".$tablename."' target=_blank><b>".$tablename."</b></a>, "; }
    if($cnt >= 20)
    { echo "<h2 style='margin-top:0; color:red;'> >>> Too many tables without auto_increment: this shop cannot be used! &lt;&lt;&lt;</h2>";
	  break;
	}
  }
}
if($cnt == 0) echo "<b>No problematic tables found</b>";

echo '<h2>Out-of-bound values</h2>';
echo 'This section checks for a few keys (shop and currency) whether there are values 
that are not defined in their base table (like ps_shop). The list below will show the tables with 
id_shops that are either zero or have a non-existing id number. The format is "tablename-(zerocount;non-existcount)". 
Such values don\'t need to be problematic (in some tables a zero stands for "all"), but when you have problems it is a good idea to check them out:<br>';

$shoptables = array("attribute_group_shop", "attribute_shop","carrier_shop",
"cart_tax_rules_group_shop","cart","cart_product","cart_rule_shop",
"cms_block_shop","cms_category_shop","cms_shop","configuration","configuration_kpi","contact_shop",
"country_shop","cronjobs","currency_shop","customer","customer_thread","delivery","editorial",
"employee_shop","favorite_product","feature_shop","group_shop","hook_module","hook_module_exceptions",
"image_shop","info_shop","lang_shop","layered_category","layered_filter_shop","layered_price_index",
"layered_product_attribute","mailalert_customer_oos","mailing_history","mailing_import","mailing_track",
"manufacturer_shop","meta_shop","module_country","module_currency","module_group",
"module_shop","newsletter","opc_field_shop","opc_payment_shop","opc_ship_to_pay",
"orders","order_detail","pagenotfound","page_cache","page_viewed", 
"product_attribute_shop","product_carrier","product_shop","referrer_shop","scene_shop","search_word",
"sekeyword","specific_price","specific_price_rule","statssearch","stock_available","store_shop",
"supplier_shop","tag_count","tax_rules_group_shop","theme_specific","warehouse_shop",
"webservice_account_shop","wishlist","zone_shop");
if(_PS_VERSION_ >= "1.7.0.0")
	$shoptables[] = "module_carrier";
echo "<b>Shops:</b> ";
foreach($shoptables AS $shoptable)
{ $res = dbquery('SELECT TABLE_NAME FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA="'._DB_NAME_.'" AND TABLE_NAME="'._DB_PREFIX_.$shoptable.'"');
  if(mysqli_num_rows($res) == 0) continue; /* table doesn't exist in this shop */
  if($shoptable != "specific_price")
  { $res = dbquery('SELECT count(*) AS rowcount FROM '._DB_PREFIX_.$shoptable.' WHERE id_shop=0');
    $row = mysqli_fetch_assoc($res);
  }
  else
    $row["rowcount"] = 0;
  $eres = dbquery('SELECT count(*) AS rowcount FROM '._DB_PREFIX_.$shoptable.' WHERE NOT id_shop IN (0,'.$shoplist.')');
  $erow = mysqli_fetch_assoc($eres);
  if(($row["rowcount"] != 0) || ($erow["rowcount"] != 0))
    echo $shoptable."-(".$row["rowcount"].";".$erow["rowcount"].") ";
}
echo "<br><b>Currencies:</b> ";
$currencytables = array("cart","country","currency_module","currency_shop","layered_price_index",
"orders","order_payment","product_supplier","specific_price","specific_price_rule","supply_order",
"supply_order_detail","warehouse");
$res = dbquery('SELECT GROUP_CONCAT(id_currency) AS currencies FROM '._DB_PREFIX_.'currency');
$row = mysqli_fetch_assoc($res);
$currencylist = $row["currencies"];
foreach($currencytables AS $currencytable)
{ $res = dbquery('SELECT TABLE_NAME FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA="'._DB_NAME_.'" AND TABLE_NAME="'._DB_PREFIX_.$currencytable.'"');
  if(mysqli_num_rows($res) == 0) continue; /* table doesn't exist in this shop */
  if(!in_array($currencytable, array("country","product_supplier","specific_price")))
  { $res = dbquery('SELECT count(*) AS rowcount FROM '._DB_PREFIX_.$currencytable.' WHERE id_currency=0');
    $row = mysqli_fetch_assoc($res);
  }
  else
    $row["rowcount"] = 0;
  $eres = dbquery('SELECT count(*) AS rowcount FROM '._DB_PREFIX_.$currencytable.' WHERE NOT id_currency IN (0,'.$currencylist.')');
  $erow = mysqli_fetch_assoc($eres);
  if(($row["rowcount"] != 0) || ($erow["rowcount"] != 0))
    echo $currencytable."-(".$row["rowcount"].";".$erow["rowcount"].") ";
}
echo "<br><b>Groups:</b> ";
$grouptables = array("cart_rule_group","category_group","customer_group",
"group_reduction","group_shop","module_group","specific_price","specific_price_rule","tag_count");
$res = dbquery('SELECT GROUP_CONCAT(id_group) AS groups FROM '._DB_PREFIX_.'group');
$row = mysqli_fetch_assoc($res);
$grouplist = $row["groups"];
foreach($grouptables AS $grouptable)
{ $res = dbquery('SELECT TABLE_NAME FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA="'._DB_NAME_.'" AND TABLE_NAME="'._DB_PREFIX_.$grouptable.'"');
  if(mysqli_num_rows($res) == 0) continue; /* table doesn't exist in this shop */
  if(!in_array($grouptable, array("specific_price","tag_count")))
  { $res = dbquery('SELECT count(*) AS rowcount FROM '._DB_PREFIX_.$grouptable.' WHERE id_group=0');
    $row = mysqli_fetch_assoc($res);
  }
  else
    $row["rowcount"] = 0;
  $eres = dbquery('SELECT count(*) AS rowcount FROM '._DB_PREFIX_.$grouptable.' WHERE NOT id_group IN (0,'.$grouplist.')');
  $erow = mysqli_fetch_assoc($eres);
  if(($row["rowcount"] != 0) || ($erow["rowcount"] != 0))
    echo $grouptable."-(".$row["rowcount"].";".$erow["rowcount"].") ";
}

  echo '<h2>Modules cleanup</h2>';
  echo 'Modules in database but not in disk: ';
  /* we will not delete module entries that are also in the following three tables */
  if(_PS_VERSION_ >= "1.7.0.0")
  { $rs1 = dbquery("SELECT GROUP_CONCAT(DISTINCT id_module) AS modules FROM "._DB_PREFIX_."module_carrier"); 
    $rw1 = mysqli_fetch_array($rs1);
    if($rw1["modules"] == "") $r1 = array(); else $r1 = explode(",",$rw1["modules"]);
  }
  else
	$r1 = array();
  $rs2 = dbquery("SELECT GROUP_CONCAT(DISTINCT id_module) AS modules FROM "._DB_PREFIX_."module_country"); 
  $rw2 = mysqli_fetch_array($rs2);
  $rs3 = dbquery("SELECT GROUP_CONCAT(DISTINCT id_module) AS modules FROM "._DB_PREFIX_."module_currency"); 
  $rw3 = mysqli_fetch_array($rs3);
  if($rw2["modules"] == "") $r2 = array(); else $r2 = explode(",",$rw2["modules"]);
  if($rw3["modules"] == "") $r3 = array(); else $r3 = explode(",",$rw3["modules"]);
  $r = array_merge($r1,$r2,$r3);
  $r = array_unique($r);

  /* debug */
  if(sizeof($r) > 0)
  { $res=dbquery("SELECT GROUP_CONCAT(name) AS names FROM "._DB_PREFIX_."module WHERE id_module IN (".implode(",",$r).")");
	$row = mysqli_fetch_array($res);
//	echo "excluded: ".$row["names"]."<br>";
  }

  $query="SELECT id_module,name,version FROM "._DB_PREFIX_."module";
  if(sizeof($r) > 0)
    $query.=" WHERE NOT id_module IN (".implode(",",$r).")";
  $query .= " GROUP BY id_module";
  $query .= " ORDER BY name";
  $res=dbquery($query);
  $missingmodules = array();
  while($row = mysqli_fetch_array($res))
  { if(!is_dir($triplepath.'modules/'.$row['name']))
	{ $missingmodules[] = $row['name'];
	}
  }
  
  if(sizeof($missingmodules) == 0)
    echo "Nothing found";
  else 
  { echo "Remainders of the following ".sizeof($missingmodules)." modules were found: ".implode(", ",$missingmodules);
  }
  
  echo '<br>Modules in module_shop but not module table: ';
  $query = "SELECT ms.* FROM "._DB_PREFIX_."module_shop ms";
  $query .= " LEFT OUTER JOIN "._DB_PREFIX_."module m on m.id_module=ms.id_module";
  $query .= " WHERE m.id_module is null ORDER BY ms.id_module";
  $res=dbquery($query);
  $modshopcount = mysqli_num_rows($res);
  if($modshopcount == 0)
    echo "Nothing found";
  else 
    echo $modshopcount." entries found where the module was missing.";
  
  echo '<br>Modules in module_group but not module table: ';
  $query = "SELECT mg.* FROM "._DB_PREFIX_."module_group mg";
  $query .= " LEFT OUTER JOIN "._DB_PREFIX_."module m on m.id_module=mg.id_module";
  $query .= " WHERE m.id_module is null ORDER BY mg.id_module";
  $res=dbquery($query);
  $modgroupcount = mysqli_num_rows($res);
  if($modgroupcount == 0)
    echo "Nothing found";
  else 
    echo $modgroupcount." entries found where the module was missing.";
  
  echo '<br>Modules in hooks but not modules table: ';
  $query = "SELECT hm.* FROM "._DB_PREFIX_."hook_module hm";
  $query .= " LEFT OUTER JOIN "._DB_PREFIX_."module m on m.id_module=hm.id_module";
  $query .= " WHERE m.id_module is null ORDER BY hm.id_module";
  $res=dbquery($query);
  $modhookcount = mysqli_num_rows($res);
  if($modhookcount == 0)
    echo "Nothing found";
  else 
  { echo $modhookcount." hooks were found where the module was missing.";
  }
  
  echo '<br>Module hook exceptions without valid hook-module: ';
  $query = "SELECT * FROM "._DB_PREFIX_."hook_module_exceptions hme";
  $query .= " LEFT OUTER JOIN "._DB_PREFIX_."hook_module hm on hme.id_module=hm.id_module AND hme.id_hook=hm.id_hook";
  $query .= " WHERE hm.id_module is null ORDER BY hme.id_module,hme.id_hook";
  $res=dbquery($query);
  $mhecount = mysqli_num_rows($res);
  if($mhecount == 0)
    echo "Nothing found";
  else 
  { echo $mhecount." module hook exceptions were found where the module hook was missing.";
  }
  
  if($integrity_repair_allowed && ((sizeof($missingmodules)>0) || ($modhookcount != 0) || ($mhecount != 0) || ($modshopcount != 0) || ($modgroupcount != 0)))
    echo '
<form name="modulerepairform" target="tank" action="integrity-repair.php" method=post onsubmit=\'return agreeformprepare("modulerepairform")\'>
<input type="hidden" name="subject" value="modulerepair"><input type="hidden" name="verbose">
<input type=checkbox name="pagree"> I did make a backup of the database and want to cleanup the module/hook entries now.<br>
<button>Cleanup<br>module tables</button></form>';

echo '<h2>Possibly problematic values</h2>';
echo 'Prestashop contains some table values that will cause your products to be not completely available for commerce. It is easy to forget when you applied them to a product. When the id_shop value is empty the occurrence is in ps_product, otherwise it is in ps_product_shop. Note that these function checks include inactive products.<br>
<table class="triplemain">
<tr><td>variable</td><td>value</td><td>nr of occurrences</td><td>id_shop</td><td>comment</td></tr>';
/* state = 0 -> means Temporary
 * State = 1 -> means Saved
 * https://github.com/prestaShop/PrestaShop/blob/develop/classes/Product.php#L313
 * When we hit "Add new product" PS creates a new record on DB (table product) with State = 0 (Temporary) 
 * and when we finally hit "Save" it change State to 1 (Saved). 
 */
if(version_compare(_PS_VERSION_ , "1.7", ">="))
{ $query = "SELECT id_product FROM "._DB_PREFIX_."product WHERE state != 1";
  $res=dbquery($query);
  echo '<tr><td>state</td><td>0</td><td>'.mysqli_num_rows($res).'</td><td></td><td>0=temporary=invisible: delete when not part of faulty import or upgrade</td><td>';
  echo '<a href="product-edit.php?search_txt1=0&search_cmp1=eq&search_fld1=p.state&fields[]=name&fields[]=VAT&fields[]=priceVAT&fields[]=price&fields[]=category&fields[]=image&fields[]=active&fields[]=state" target=_blank>view</a>';
  echo '</td></tr>';
}

foreach(array("catalog","search","none") AS $visoption)
{ $query = "SELECT count(id_product) AS products FROM "._DB_PREFIX_."product";
  $query .= " WHERE visibility='".$visoption."'";
  $res=dbquery($query);
  $row = mysqli_fetch_array($res);
  echo '<tr><td>visibility</td><td>'.$visoption.'</td><td>'.$row["products"].'</td><td></td>';
  echo '<td>Where links to product page be found: both is default</td>';
  if($visoption == "catalog")
    echo '<td rowspan=3><a href="product-edit.php?search_txt1=both&search_cmp1=not_eq&search_fld1=p.visibility&fields[]=name&fields[]=VAT&fields[]=priceVAT&fields[]=price&fields[]=category&fields[]=image&fields[]=active&fields[]=visibility" target=_blank>view</a></td>';
  echo '</tr>';
}
foreach($shops AS $shop)
{ foreach(array("catalog","search","none") AS $visoption)
  { $query = "SELECT count(id_product) AS products FROM "._DB_PREFIX_."product_shop";
	$query .= " WHERE visibility='".$visoption."' AND id_shop=".$shop;
	$res=dbquery($query);
	$row = mysqli_fetch_array($res);
    echo '<tr><td>visibility</td><td>'.$visoption.'</td><td>'.$row["products"].'</td><td>'.$shop.'</td>';
    echo '<td>Where links to product page be found: both is default</td>';
    if($visoption == "catalog")
	  echo '<td rowspan=3><a href="product-edit.php?search_txt1=both&search_cmp1=not_eq&search_fld1=ps.visibility&fields[]=name&fields[]=VAT&fields[]=priceVAT&fields[]=price&fields[]=category&fields[]=image&fields[]=active&fields[]=visibility&id_shop='.$shop.'" target=_blank>view</a></td>';
    echo '</tr>';
  }
}

$query = "SELECT count(id_product) AS products FROM "._DB_PREFIX_."product";
$query .= " WHERE available_for_order=0";
$res=dbquery($query);
$row = mysqli_fetch_array($res);
echo '<tr><td>available_for_order</td><td>0</td><td>'.$row["products"].'</td><td></td>';
echo '<td></td>';
echo '<td><a href="product-edit.php?search_txt1=0&search_cmp1=eq&search_fld1=p.available_for_order&fields[]=name&fields[]=VAT&fields[]=priceVAT&fields[]=price&fields[]=category&fields[]=image&fields[]=active&fields[]=availorder" target=_blank>view</a></td>';
echo '</tr>';
foreach($shops AS $shop)
{ $query = "SELECT count(id_product) AS products FROM "._DB_PREFIX_."product_shop";
  $query .= " WHERE available_for_order=0 AND id_shop=".$shop;
  $res=dbquery($query);
  $row = mysqli_fetch_array($res);
  echo '<tr><td>available_for_order</td><td>0</td><td>'.$row["products"].'</td><td>'.$shop.'</td>';
  echo '<td></td>';
  echo '<td><a href="product-edit.php?search_txt1=0&search_cmp1=eq&search_fld1=ps.available_for_order&fields[]=name&fields[]=VAT&fields[]=priceVAT&fields[]=price&fields[]=category&fields[]=image&fields[]=active&fields[]=availorder&id_shop='.$shop.'" target=_blank>view</a></td>';
  echo '</tr>';
}

$query = "SELECT count(id_product) AS products FROM "._DB_PREFIX_."product";
$query .= " WHERE show_price=0";
$res=dbquery($query);
$row = mysqli_fetch_array($res);
echo '<tr><td>show_price</td><td>0</td><td>'.$row["products"].'</td><td></td>';
echo '<td></td>';
echo '<td><a href="product-edit.php?search_txt1=0&search_cmp1=eq&search_fld1=p.show_price&fields[]=name&fields[]=VAT&fields[]=priceVAT&fields[]=price&fields[]=category&fields[]=image&fields[]=active&fields[]=availorder" target=_blank>view</a></td>';
echo '</tr>';
foreach($shops AS $shop)
{ $query = "SELECT count(id_product) AS products FROM "._DB_PREFIX_."product_shop";
  $query .= " WHERE show_price=0 AND id_shop=".$shop;
  $res=dbquery($query);
  $row = mysqli_fetch_array($res);
  echo '<tr><td>show_price</td><td>0</td><td>'.$row["products"].'</td><td>'.$shop.'</td>';
  echo '<td></td>';
  echo '<td><a href="product-edit.php?search_txt1=0&search_cmp1=eq&search_fld1=ps.show_price&fields[]=name&fields[]=VAT&fields[]=priceVAT&fields[]=price&fields[]=category&fields[]=image&fields[]=active&fields[]=availorder" target=_blank>view</a></td>';
  echo '</tr>';
}
echo '</table>';

echo '<h2>Character set issues</h2>';
echo 'Every Prestashop and Thirty Bees version has its preferred character set and collation for the database tables. As long as you have a variety of utf8 things should work ok in almost any setting. Only special cases like emojis might not work. Anyway, here is an option to get your shop on the preferred character set and collation if it isn\'t.<br>';
echo 'In some cases with non-latin sets changing the collation from "general" to "unicode" can fail with a duplicate key error. See the manual.<br>'; 
echo 'This option will convert the system tables. Tables belonging to modules will not be converted.<br>';
  $query = "SELECT CCSA.CHARACTER_SET_NAME ,T.TABLE_COLLATION
FROM information_schema.`TABLES` T,information_schema.`COLLATION_CHARACTER_SET_APPLICABILITY` CCSA
WHERE CCSA.COLLATION_NAME = T.TABLE_COLLATION
AND T.table_schema = '"._DB_NAME_."'
AND T.table_name = '"._DB_PREFIX_."product_lang'";

  $res3 = dbquery($query);
//  echo " ".mysqli_num_rows($res3)." rows";
  if((!$res3) || (mysqli_num_rows($res3)==0)) die("Error reviewing database charset.");
  $row = mysqli_fetch_array($res3);

  $diff = false;
    if(($row["CHARACTER_SET_NAME"]!="utf8") && ((!defined('_TB_VERSION_')&&(version_compare(_PS_VERSION_ , "1.7.7.0", "<")))))
  { echo "Char set is '".$row["CHARACTER_SET_NAME"]."' instead of 'utf8'.";
	$diff = true;
  }
    else if(($row["CHARACTER_SET_NAME"]!="utf8mb4") && ((defined('_TB_VERSION_')||(version_compare(_PS_VERSION_ , "1.7.7.0", ">=")))))
  {	echo "Char set is '".$row["CHARACTER_SET_NAME"]."' instead of 'utf8mb4'.";
    $diff = true;
  }
  else 
	  echo "Char set ok";
  echo "<br>";

    if(($row["TABLE_COLLATION"]!="utf8mb4_unicode_ci") && (defined('_TB_VERSION_')))
	{ $diff = true;
	  echo "Collation is '".$row["TABLE_COLLATION"]."' instead of 'utf8mb4_unicode_ci'.";
	}
    else if(($row["TABLE_COLLATION"]!="utf8_general_ci") && (version_compare(_PS_VERSION_ , "1.7.7.0", "<")))
	{ $diff = true;
	  echo "Collation is '".$row["TABLE_COLLATION"]."' instead of 'utf8_general_ci'.";
	}
	else if(($row["TABLE_COLLATION"]!="utf8mb4_general_ci") && (version_compare(_PS_VERSION_ , "1.7.7.0", ">=")))
    { $diff = true;
	  echo "Collation is '".$row["TABLE_COLLATION"]."' instead of 'utf8mb4_general_ci'.";
	}
  else 
	  echo "Collation ok";

  if($diff)
  { echo '<form name="charcollform" target="tank" action="integrity-repair.php" method=post onsubmit=\'return agreeformprepare("charcollform")\'>
<input type="hidden" name="subject" value="charsetcollation"><input type="hidden" name="verbose">
<input type=checkbox name="pagree"> I did make a backup of the database and want to change the character encoding now.<br>
<button>Fix database table encoding</button></form>';
  }
	  
  echo '<h2>Quick fixes</h2>';
  echo 'The routines below provide quick fixes for some of the problems detected above. They apply to all affected products';
  
  echo '<table class="spacer" style="width:100%">';
  echo '<form name=deactivateform action="integrity-repair.php" method=post target=tank onsubmit=\'return formprepare("deactivateform")\'>';
  echo '<tr><td><b>Deactivate active products without an active category</b><br>';
  echo '<input type=checkbox name="pagree"> I made a backup and want to deactivate now.<br>';
  echo 'This will set all your active products without an active category to inactive.';
  echo '</td><td style="width:15%">';
  echo '<input type=hidden name="subject" value="inactivecategory" ><input type=hidden name=verbose>';
  echo '<button>deactivate products<br>without active category</button></form></td></tr>';
  
  echo '<tr><td><b>Fix products with inactive default category</b><br>';
  echo '<form name=fixdefaultform action="integrity-repair.php" method=post target=tank onsubmit=\'return formprepare("fixdefaultform")\'>';
  echo '<input type=checkbox name="pagree"> I made a backup and want to fix now.<br>';
  echo 'When products have an inactive default category but they have another category is that is active this will
  set that active category as default. If there is more than one active category the deepest will be chosen. When there are several 
  on the same level the lowest id will be chosen. If no active category is found the product status is set to inactive.';
  echo '</td><td>';
  echo '<input type=hidden name="subject" value="fixdefault" ><input type=hidden name=verbose>';
  echo '<button>fix products with<br>inactive default category</button></form></td></tr>';
  
  echo '<tr><td><b>Delete incomplete products</b><br>';
  echo '<form name=delfaultyprodsform action="integrity-repair.php" method=post target=tank onsubmit=\'return formprepare("delfaultyprodsform")\'>';
  echo '<input type=checkbox name="pagree"> I made a backup and want to delete now.<br>';
  echo 'Products should be present in at least three tables: ps_product, ps_product_shop and ps_product_lang. If they aren\'t this command will delete them.';
  echo '</td><td>';
  echo '<input type=hidden name="subject" value="delfaultyprods" ><input type=hidden name=verbose>';
  echo '<button>Delete incomplete<br>products</button></form></td></tr>';
  
  echo '<tr><td><b>Delete incomplete categories without products</b><br>';
  echo '<form name=delfaultycatsform action="integrity-repair.php" method=post target=tank onsubmit=\'return formprepare("delfaultycatsform")\'>';
  echo '<input type=checkbox name="pagree"> I made a backup and want to delete now.<br>';
  echo 'Categories should be present in at least three tables: ps_category, ps_category_shop and ps_category_lang. If they are not present in the ps_category table this command will delete them. An exception is made for categories that contain products that are not in another category.';
  echo '</td><td>';
  echo '<input type=hidden name="subject" value="delfaultycats" ><input type=hidden name=verbose>';
  echo '<button>delete incomplete<br>categories</button></form></td></tr>';
  
  echo '<tr><td><b>Delete comments for non-existing products</b><br>';
  echo '<form name=delcommentsform action="integrity-repair.php" method=post target=tank onsubmit=\'return formprepare("delcommentsform")\'>';
  echo '<input type=checkbox name="pagree"> I made a backup and want to delete now.<br>';
  echo 'Prestools will not delete product comments with other product data as some people might want to keep them. SO here is a separate function to delete them.';
  echo '</td><td>';
  echo '<input type=hidden name="subject" value="delcomments" ><input type=hidden name=verbose>';
  echo '<button>delete comments<br>for deleted products</button></form></td></tr>';
  
  
  
  echo '</table>';

/* printprod() print a product id in such a way that you access to its name and picture */
function printprod($id_product)
{ global $triplepath;
  $iquery = "SELECT id_image FROM "._DB_PREFIX_."image WHERE id_product=".$id_product." ORDER BY cover DESC LIMIT 1";
  $ires=dbquery($iquery);
  if($irow=mysqli_fetch_assoc($ires))
  { $id_image = $irow["id_image"];
    $lquery = "SELECT legend FROM "._DB_PREFIX_."image_lang WHERE id_image=".$id_image." LIMIT 1";
    $lres=dbquery($lquery);
	if($lrow=mysqli_fetch_assoc($lres))
		$legend = $lrow["legend"];
	else 
	{ $lquery = "SELECT name FROM "._DB_PREFIX_."product_lang WHERE id_product=".$id_product." LIMIT 1";
      $lres=dbquery($lquery);
	  if($lrow=mysqli_fetch_assoc($lres))
		$legend = str_replace("'","",$lrow["legend"]);
      else
		$legend = "";
	}
	echo "<a href='".$triplepath.'img/p'.getpath($id_image).'/'.$id_image.".jpg' target=_blank title='".$legend."'>".$id_product."</a>,";
  }
  else
	 echo $id_product.","; 
}
echo '<p>';
  include "footer1.php";
echo '</body></html>';


function printcat($id_category)
{ global $triplepath;
  $query = "SELECT name FROM "._DB_PREFIX_."category_lang WHERE id_category=".$id_category;
  $res=dbquery($query);
  $name = "";
  if(mysqli_num_rows($res)>0)
  { $row=mysqli_fetch_assoc($res);
	$name = $row["name"];
  }
  echo "<a href='".$triplepath.'img/c/'.$id_category.".jpg' target=_blank title='".str_replace("'","",$name)."'>".$id_category."</a>,";
}

function _subTreeSub(&$categories, $idCategory, &$n)
{   global $ucparents, $uccategories;
	if (isset($categories[(int) $idCategory]['subcategories'])) {
		foreach ($categories[(int) $idCategory]['subcategories'] as $idSubcategory) 
		{	_subTreeSub($categories, (int) $idSubcategory, $n);
			$pos = array_search($idSubcategory, $ucparents);
			if($pos!== false) 
				array_splice($ucparents, $pos,1);
		}
	}
	$pos = array_search($idCategory, $uccategories);
	if($pos!== false) 
		array_splice($uccategories, $pos,1);
}