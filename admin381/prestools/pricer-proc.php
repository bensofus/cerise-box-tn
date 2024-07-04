<?php 
if(!@include 'approve.php') die( "approve.php was not found!");
$percentage = (int)$_GET["percentage"];
if($percentage == 0)
{ echo '<script>alert("Percentage evaluated to zero");</script>';
  return; /* nothing to do */
}

$query = "UPDATE "._DB_PREFIX_."product_shop SET price = ROUND(price*(100+".$percentage.")/100)";
$query .= " WHERE id_tax_rules_group=0 OR id_tax_rules_group=1";
$res=dbquery($query);

$query = "UPDATE "._DB_PREFIX_."product_shop SET price = ROUND(price*1.09*(100+".$percentage.")/100)/1.09";
$query .= " WHERE id_tax_rules_group=2";
$res=dbquery($query);

$query = "UPDATE "._DB_PREFIX_."product p";
$query .= " INNER JOIN "._DB_PREFIX_."product_shop ps on p.id_product=ps.id_product AND ps.id_shop=1";
$query .= " SET p.price=ps.price";
$res=dbquery($query);

  echo '<script>alert("Finished"); location.href="pricer.php";</script>';
    include "footer1.php";
?>
</body>
</html>
