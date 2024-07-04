<?php 
if(!@include 'approve.php') die( "approve.php was not found!");
$input = $_GET;

$rewrite_settings = get_rewrite_settings();
$base_uri = get_base_uri();

/* get language iso_code */
$query = "select iso_code from ". _DB_PREFIX_."configuration c";
$query .= " LEFT JOIN ". _DB_PREFIX_."lang l ON c.value=l.id_lang";
$query .= " WHERE c.name='PS_LANG_DEFAULT'";
$res=dbquery($query);
$row = mysqli_fetch_array($res);
$iso_code = $row['iso_code'];

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Export / Import</title>
<style>
option.defcat {background-color: #ff2222;}
</style>
<link rel="stylesheet" href="style1.css" type="text/css" />
<script type="text/javascript" src="utils8.js"></script>
<script type="text/javascript" src="sorter.js"></script>
<script>
function export_data()
{ var myframe = document.getElementById("tank");
  myframe.src = "exim-export.php";
}

function import_data()
{ var myframe = document.getElementById("tank");
  myframe.src = "exim-import.php";
}
</script>
</head>

<body>
<?php
print_menubar();
?>
  <table style="width:100%"><tr><td>
  <center><b><font size="+1">Export / Import</font></b></center>
  <p>Like <a href="https://www.prestashop.com/forums/topic/445453-copy-shopdata-script-for-copying-shop-content-for-upgrade/" target="_blank">Copy_shopdata</a> Export / Import is meant for transporting databases from one server to another.
  <br>However, unlike copy_shopdata it is meant for copying data between two database with exactly the same structure.
  <br>Note that an upgraded and a new Prestashop database have different structures! So you may need to copy the structure of the database first.
  <br>If you hosting company imposes limits on the size of your imported sql files you can import this allows a way around that.
  <br>Note that some servers will give mysql 1045 error rights problems. If you have root rights you can solve that with "grant file".
  </td>
  <td style="text-align:right; width:30%" rowspan=2><iframe name="tank" id="tank" width="330" height="125"></iframe>
  </td></tr></table>
  
  <p><p>&nbsp;<p><p>
  
  <b>Export</b>
  <br>Export will export all database tables to the "export" subdirectory of your Triple Edit directory. 
  <br>From there you can copy them (with FTP) to the "exim" subdirectory of Triple Edit on the target server.
  <br><button onclick="export_data(); return false;">Export</button>

    <p><p>&nbsp;<p><p>&nbsp;<p><p>

  <b>Import</b>
  <br>Import will import the data from the "import" subdirectory.
  <br>Be careful that the tables should have the same structure. Otherwise the result will be a useless.
  <br>The script will import all present table dumps. Tables for which there is no dump will be left untouched.
  <br>This function can take several minutes. If it doesn't finish you should prolong the "set_time_limit()" value at the top of the exim-import.php file.
  <br><button onclick="import_data(); return false;">Import</button>
  
      <p><p>&nbsp;<p><p>&nbsp;<p><p>
 <?php
  include "footer.php";
  echo '</body></html>';
  

?>
