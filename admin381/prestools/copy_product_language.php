<?php 
if(!@include 'approve.php') die( "approve.php was not found!");
$mode = "background";
echo $_POST['products'];
echo $_POST['fields'];
if(!isset($_POST['products']))
{ echo "No products";
  return;
}
$products = preg_replace('/[a-zA-Z]/', "", $_POST['products']);
if(!isset($_POST['fields']))
{ echo "No fields";
  return;
}
$pattern = '/,\.\"\' /';
$fields = preg_replace($pattern, "", $_POST['fields']);
if(!isset($_POST['id_shop']))
{ echo "No shop";
  return;
}
$id_shop = strval(intval($_POST['id_shop']));
if(!isset($_POST['id_lang']))
{ echo "No language";
  return;
}
$id_lang = strval(intval($_POST['id_lang']));

echo '<!DOCTYPE html> 
<html lang="en"><head><meta charset="utf-8">
</head><body onload=update_parent()>';

$queryfields = array();
$field_array = explode(",", $fields);
foreach($field_array AS $field)
{ if(substr($field,-3,1) == "_")
    $basefield = substr($field,0, strlen($field)-3);
  else
    $basefield = $field;
  if(!in_array($basefield, array("name","link_rewrite","description","description_short","meta_title","meta_keywords","meta_description","available_now","available_later")))
    colordie("Unrecognized fieldname ".$basefield);
  $queryfields[] = $basefield;	
}

$myfields = implode(",", $queryfields);

if(count($queryfields) == 0)
  die("<b>No Fields</b>");

$query = "SELECT id_product,".$myfields." FROM ". _DB_PREFIX_."product_lang WHERE id_product IN (".$products.") AND id_lang='".$id_lang."' AND id_shop='".$id_shop."'";
$res = dbquery($query);
echo '<script type="text/javascript">function update_parent() { top.prepare_update(); ';
while ($row=mysqli_fetch_array($res)) 
{ foreach($field_array AS $field)
  { if(substr($field,-3,1) == "_")
      $qfield = substr($field,0, strlen($field)-3);
    else
      $qfield = $field;
    echo '
  top.update_field("'.$row["id_product"].'", "'.$field.'", '.json_encode($row[$qfield]).');';
//  top.update_field("'.$row["id_product"].'", "'.$qfield.'", "'.str_replace("\n","\\n",str_replace('"','\\"',$row[$qfield])).'");';
  }

}
echo "} </script>Finished successfully!</body></html>";

?>
