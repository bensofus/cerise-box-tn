<?php 
if(!@include 'approve.php') die( "approve.php was not found!");
$input = $_GET;

$rewrite_settings = get_rewrite_settings();
$base_uri = get_base_uri();

echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script>
function newwin()
{ nwin = window.open("","_blank", "scrollbars,menubar,toolbar, status,resizable,location");
  content = document.body.innerHTML;
  if(nwin != null)
  { nwin.document.write("<html><head><meta http-equiv=\'Content-Type\' content=\'text/html; charset=utf-8\' /></head><body>"+content+"</body></html>");
    nwin.document.close();
  }
}
</script></head><body>
<a href="#" title="Show the content of this frame in a New Window" onclick="newwin(); return false;">NW</a>
<p>';
/* MySQL needs absolute paths for exporting to and importing from files. We create that here. We also create the Exim subdirectory. */
$basepath = str_replace("copy_shopdata_config.php","", realpath('copy_shopdata_config.php')); // the mysql functions won't work without an absolute path 
$basepath = str_replace("\\", "/", $basepath); 
$eximpath = "export";
if(!is_dir($eximpath))
{ if(mkdir($eximpath))
    $basepath .= $eximpath."/";
}
else
  $basepath .= $eximpath."/";

//$verbose = true;
$query = "SHOW TABLES";
$res = dbquery($query);
while($row = mysqli_fetch_row($res))
{ $tablefile = $basepath."copy_eximdata_".$row[0].".dtx";
  echo "<br>Export ".$row[0];
  $subquery = "SELECT * INTO OUTFILE '".$tablefile."' FROM ".$row[0]."";
  $subres = dbquery($subquery); 
}
?>
