<?php 
if(!@include 'approve.php') die( "approve.php was not found!");
$input = $_GET;

$rewrite_settings = get_rewrite_settings();
$base_uri = get_base_uri();
set_time_limit(310);  /* number of seconds that this function is allowed to run; increase when it doesn't finish all tables */

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
$eximpath = "import";
if(!is_dir($eximpath))
{ if(mkdir($eximpath))
    $basepath .= $eximpath."/";
}
else
  $basepath .= $eximpath."/";

$thetables = array();
$query = "SHOW TABLES";
$res = mysqli_query($conn, $query); 
while($row = mysqli_fetch_row($res))
  $thetables[] = $row[0];

//$verbose = true;
if(!($dp = opendir($eximpath."/")))
      colordie("Cannot open import directory");
while( $dirfile = readdir($dp))
{ if(preg_match("/\.dtx$/i", $dirfile))
  { $tablename = substr($dirfile, 14);
    $tablename = str_replace(".dtx","",$tablename);
	if(in_array($tablename,$thetables))
	{ echo $tablename." ".date("H:i:s", time())."<br>";
	  $tablefile = $basepath.$dirfile;
	  $query = "TRUNCATE TABLE ".$tablename;
	  $res = dbquery($query); 
	  $tablefile = "http://www.prestools.com/_ps1611/triple/import/".$dirfile;
	  $query = "LOAD DATA INFILE '".$tablefile."' INTO TABLE ".$tablename." CHARACTER SET 'utf8'";
	  $res = dbquery($query); 
	}
  }  
}


?>
