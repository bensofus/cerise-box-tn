<?php 
if(!@include 'approve.php') die( "approve.php was not found!");
$input = $_GET;

/* get default language: we use this for the categories, manufacturers */
$query="select value from ". _DB_PREFIX_."configuration WHERE name='PS_LANG_DEFAULT'";
$res=dbquery($query);
$row = mysqli_fetch_array($res);
$id_lang = $row['value'];
?><!DOCTYPE html> 
<html lang="en"><head><meta charset="utf-8">
<title>Prestashop Cleanup</title>
<style>
.comment {background-color:#aabbcc}
#delbutton:disabled {background-color: #aabbcc}
</style>
<script type="text/javascript" src="utils8.js"></script>
<script type="text/javascript" src="sorter.js"></script>
<script>
function formprepare(formname)
{ var field = eval(formname+".verbose");
  field.value = configform.verbose.checked;  
}

function regenerateurls()
{ regenerateurlsform.submit();
}

function regenerateurls_start()
{ pcnt = ccnt = 0;  /* global vars!!! */
  regenerateurlsform.start_id.value = "p0";
  regenerateurls();
}

function regenerateurls_looper(next_id, upcnt, uccnt)
{ pcnt = pcnt + upcnt;
  ccnt = ccnt + uccnt;
  if(next_id==-1)
	alert("Url regeneration finished! "+pcnt+" product fields and "+ccnt+" category fields were updated.");
  else
  { regenerateurlsform.start_id.value = next_id;
    setTimeout('regenerateurls()',500);
  }
}
</script>
<link rel="stylesheet" href="style1.css" type="text/css" />
</head><body>
<?php print_menubar(); ?>
<table class="triplehome" cellpadding="0" cellspacing="0"><tr><td width="80%">
<a href="cleanup.php" style="text-decoration:none; font-size:160%"><b><center>Prestashop Cleanup</center></b></a>
<center>This page allows you to do some cleanup operations on your webshop. 
<br>The functions here don't require much knowledge of the technical side of the shop.
<br>In multishop configurations the cleaning will apply to all shops.
<br>Make a backup before any advanced operation.
<br>Some operations take long. You see a popup when finished. In some cases a timeout might happen - what shouldn't be problematic.
<br>You might also have a look at <a href="https://github.com/PrestaShop/pscleaner/">PSCleaner</a> (for Prestashop)
or <a href="https://github.com/thirtybees/tbcleaner">TBCleaner</a> (for Thirty Bees)</center>
</td><td><iframe name=tank width=230 height=93></iframe></td></tr></table>
<form name=configform><input type=checkbox name=verbose> verbose</form><p>
<?php

  echo '<table class="spacer" style="width:100%">';
  echo '<tr><td><b>Empty cache</b><br>';
  echo 'Empty Cache immediately empties the general Prestashop cache, the theme cache and the Prestools temp directory.';
  echo '</td><td>';
  echo '<form name=cacheform action="cleanup-proc.php" method=post target=tank onsubmit=formprepare("cacheform")>';
  echo '<input type=hidden name="subject" value="emptycache" ><input type=hidden name=verbose>';
  echo '<input type=submit value="empty cache"></form></td></tr>';
  
  echo '<tr><td><b>Delete abondonned carts</b><br>';
  echo '<form name="abcartform" target=tank action="cleanup-proc.php" method=post onsubmit=formprepare("abcartform")>';
  echo 'Delete abondonned carts older than <input name="days" value="14" size=2 style="text-align:right"> days.
  <br>Abadonned carts belonging to a customer will be kept 14 days longer.
  <input type=hidden name="subject" value="abcarts"><input type=hidden name=verbose>';
  echo '<br>This process can take a lot of time. Timeouts may occur but are not harmful.';
  echo '</td><td>';
  echo '<button>Delete abondonned carts</button></form></td></tr>';
  
  echo '<tr><td><b>Delete old connections</b><br>';
  echo '<form name="connectionform" target=tank action="cleanup-proc.php" method=post onsubmit=formprepare("connectionform")>';
  $nowtime = time();
  $ddate = date("Y-m-d H:i:s",($nowtime-(7*60*60*24)));
  $gquery = "SELECT id_guest FROM "._DB_PREFIX_."connections WHERE date_add<'".$ddate."' ORDER BY date_add DESC LIMIT 1";
  $gres=dbquery($gquery);
  if(mysqli_num_rows($gres) > 0)
  { $grow=mysqli_fetch_array($gres);
    $dgquery = "SELECT COUNT(*) AS guestcount FROM "._DB_PREFIX_."guest WHERE id_guest < ".$grow["id_guest"];
    $dgres=dbquery($dgquery);
    $dgrow=mysqli_fetch_array($dgres);
    $affected_guests=$dgrow["guestcount"];
  }
  else 
    $affected_guests = 0;
  $dquery = "SELECT COUNT(*) AS conncount FROM "._DB_PREFIX_."connections WHERE http_referer=''";
  $dquery .= " AND date_add<'".$ddate."' AND id_connections NOT IN (SELECT id_connections FROM "._DB_PREFIX_."connections_source)";
  $dres=dbquery($dquery);
  $drow=mysqli_fetch_array($dres);
  echo 'Delete connections older than <input name="days" value="7" size=2 style="text-align:right"> days.
  Connections with a referrer or a source will be kept for a year.
  <br>The connections table is infamous for how big it can get.
  <br>Related entries in the guest table will also be deleted.
  <br>You have '.$affected_guests.' entries in the guest table and '.$drow["conncount"].' entries in the connections table older than 7 days that could be deleted.
  <br>This may take a long time (come back after an hour) and you may even need to repeat the process a few times due to timeouts.
  <br>When there is no timeout you will see a popup telling you how many entries were deleted.
  <br>Pressing the button a second time will result in a "Lock wait timeout exceeded" warning and disable the popup.
  <input type=hidden name="subject" value="connections"><input type=hidden name=verbose>';
  echo '</td><td>';
  echo '<button>Delete old connections</button></form></td></tr>';

  echo '<tr><td><b>Delete page-not-found statistics</b><br>';
  echo '<form name="pagestatsform" target=tank action="cleanup-proc.php" method=post onsubmit=formprepare("pagestatsform")>';
  echo 'Delete entries from the '._DB_PREFIX_.'pagenotfound tables older than <input name="days" value="30" size=2 style="text-align:right"> days - when you have this table. 
  <input type=hidden name="subject" value="pagestats"><input type=hidden name=verbose>';
  echo '</td><td>';
  echo '<button>Delete pagenotfound stats</button></form></td></tr>';

  echo '<tr><td><b>Remove deleted languages</b><br>';
  echo 'When you delete languages Prestashop often leaves translations in the tables for
  the erased languages. Pressing this button will immediately delete those translations.';
  echo '<br>Order related information will not be deleted';
  echo '</td><td>';
  echo '<form name=remtransform action="cleanup-proc.php" method=post target=tank onsubmit=formprepare("remtransform")>';
  echo '<input type=hidden name="subject" value="removetranslations" ><input type=hidden name=verbose>';
  echo '<input type=submit value="remove unused translations"></form></td></tr>';

  echo '<tr><td><b>Remove deleted shops info</b><br>';
  echo 'This removes all information connected to deleted shops in a multishop configuration. This concerns only shops 
  that are no longer in the ps_shop database. Information related to shops marked as deleted in the database will not be touched.';
  echo '<br>Order related information will not be deleted';
  echo '</td><td>';
  echo '<form name=shopform action="cleanup-proc.php" method=post target=tank onsubmit=formprepare("shopform")>';
  echo '<input type=hidden name="subject" value="removeshops" ><input type=hidden name=verbose>';
  echo '<input type=submit value="remove unused shops"></form></td></tr>';

  echo '<tr><td><b>Cleanup deleted product info</b><br>';
  echo 'Prestashop sometimes leaves information about deleted products in some tables. This cleans that up in
  layered navigation, specific prices, tags and accessories.<br>';
  echo '</td><td>';
  echo '<form name=layeredform action="cleanup-proc.php" method=post target=tank onsubmit=formprepare("layeredform")>';
  echo '<input type=hidden name="subject" value="cleanupdeletedprod" ><input type=hidden name=verbose>';
  echo '<button>Cleanup deleted<br>product info</button></form></td></tr>';
  
  echo '<tr><td><b>Check and repair image covers</b><br>';
  echo 'Standard one image of a product is appointed as cover. However, sometimes this goes wrong during product import and none of the images get the cover flag.';
  echo ' This can lead to problems. This function assigns the image on the first position as cover for products without a cover.';
  echo '<br><form name=icoverform action="cleanup-proc.php" method=post target=tank onsubmit=formprepare("icoverform")>';
  echo '</td><td>';
  echo '<input type=hidden name="subject" value="imagecovercheck" ><input type=hidden name=verbose>';
  echo '<input type=submit value="repair image covers"></form></td></tr>';
  
  echo '<tr><td><b>Check for zero prices</b><br>';
  echo 'Many shops contain some products with a zero price. It is easy to detect them.<br>
  This function will open a new window with product-edit showing such products when present.';
  echo '</td><td>';
  echo '<a href="product-edit.php?search_txt1=0&search_cmp1=eq&search_fld1=ps.price" 
  target=_blank style="background-color:#2c82c9; color:white; text-decoration:none; padding:5px;">zero price check</a>';
  echo '</td></tr>';

  echo '<tr><td><b>Delete expired specific prices</b><br>';
  echo '<form name="discountform" target=tank action="cleanup-proc.php" method=post onsubmit=formprepare("discountform")>';
  echo 'Delete specific prices (discounts) that have expired more than <input name="days" value="14" size=2 style="text-align:right"> days ago.
  <br>This will also delete specific prices for deleted products.
  <input type=hidden name="subject" value="olddiscounts"><input type=hidden name=verbose>';
  echo '</td><td>';
  echo '<button>Delete expired<br>specific prices</button></form></td></tr>';
  
  echo '<tr><td><b>Delete expired vouchers</b><br>';
  echo '<form name="voucherform" target=tank action="cleanup-proc.php" method=post onsubmit=formprepare("voucherform")>';
  echo 'Delete vouchers (cart rules) that have expired more than <input name="days" value="28" size=2 style="text-align:right"> days ago.
  <input type=hidden name="subject" value="oldvouchers"><input type=hidden name=verbose>';
  echo '</td><td>';
  echo '<button>Delete expired<br>vouchers</button></form></td></tr>';
  
  echo '<tr><td><b>Delete expired catalog rules</b><br>';
  echo '<form name="catrulesform" target=tank action="cleanup-proc.php" method=post onsubmit=formprepare("catrulesform")>';
  echo 'Delete catalog rules (specific price rules) that have expired more than <input name="days" value="28" size=2 style="text-align:right"> days ago.
  <input type=hidden name="subject" value="oldcatalogrules"><input type=hidden name=verbose>';
  echo '</td><td>';
  echo '<button>Delete expired<br>catalog rules</button></form></td></tr>';
  
  echo '<tr><td><b>Cleanup search index</b><br>';
  echo '<form name="inactivesearchform" target=tank action="cleanup-proc.php" method=post onsubmit=formprepare("inactivesearchform")>';
  echo 'Delete search index entries for inactive and deleted products.
  <input type=hidden name="subject" value="searchinactive"><input type=hidden name=verbose>';
  echo '</td><td>';
  echo '<button>Cleanup search index</button></form></td></tr>';
  
  echo '<tr><td><b>Delete unused keywords</b><br>';
  echo '<form name="keywordform" target=tank action="cleanup-proc.php" method=post onsubmit=formprepare("keywordform")>';
  echo 'Delete keywords from the search index that are not used. Please run "Cleanup search index" first.
  <input type=hidden name="subject" value="searchkeywords"><input type=hidden name=verbose>';
  echo '</td><td>';
  echo '<button>Delete unused keywords</button></form></td></tr>';
    
  echo '<tr><td><b>Regenerate friendly urls for products and categories</b><br>';
  echo '<form name="regenerateurlsform" target=tank action="cleanup-proc.php" method=post onsubmit="regenerateurls_start(); return false;")>';
  echo 'Timeouts may occur in big shops. Check verbose to keep track of how far you came.<br>';
  echo 'This may not work for non-latin charsets. Use the mass-edit funtion of product-edit for them.<br>';
  echo '<input type=hidden name="subject" value="regenerateurls"><input type=hidden name=verbose><input type=hidden name="start_id">';
  echo '<input type=checkbox name="regenprods"> products - range <input name=prodrange> example: 1-100,200-250,500,c5 (use c prefix for all products in a category)<br>';
  echo '<input type=checkbox name="regencats"> categories - range <input name=catrange><br>';
  echo 'Regeneration happens sorted by id. Leave range empty for all. To prevent timeouts it happens in batches (batch size <input name=batchsize size=3 value="50000">). With small batchsize your browser may stop the process.';
  echo '</td><td>';
  echo '<button>Regenerate<br>friendly urls</button></form></td></tr>';
  
  echo '<tr><td><b>Delete unused entries in category_product table</b><br>';
  echo '<form name="catprodform" target=tank action="cleanup-proc.php" method=post onsubmit=formprepare("catprodform")>';
  echo 'The category_product table - that connects products with categories - can contain entries about products and categories that are no longer in the database. These can be deleted.<br>
  <input type=hidden name="subject" value="catprodcleanse"><input type=hidden name=verbose>';
  echo '</td><td>';
  echo '<button>Cleanse<br>category_product</button></form></td></tr>';
 
 /* This function is implemented in integrity_checks
  echo '<tr><td><b>Delete removed modules</b><br>';
  echo '<form name="remmoduleform" target=tank action="cleanup-proc.php" method=post onsubmit=formprepare("remmoduleform")>';
  echo 'Sometimes modules are removed by deleting the files. This function removes the entries of those
 in the modules* tables. Data elsewhere are not deleted. 
 <br>You are advised to only use this function when you have found such entries.
 <br>Don\'t use this function when you have temporarily renamed modules.
  <input type=hidden name="subject" value="removemodules"><input type=hidden name=verbose>';
  echo '</td><td>';
  echo '<button>Delete<br>removed modules</button></form></td></tr>';
  */
  
  echo '<tr><td><b>Find duplicate product names</b><br>';
  echo 'This search will look for different products with the same name.<br>';
  echo '</td><td>';
  echo '<a href="dupli-finder.php" 
  target=_blank style="background-color:#2c82c9; color:white; text-decoration:none; padding:5px;">Duplicates check</a>';
  echo '</td></tr>';
  
/* To do: set products’ cheapest combinations as default
	activate inactive categories with active products
*/

  echo '</table>';

echo '<p>';
  include "footer1.php";
echo '</body></html>';


