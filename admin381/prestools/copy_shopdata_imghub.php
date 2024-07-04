<?php 
if(!@include 'approve.php') die( "approve.php was not found!");
if(!include 'copy_shopdata_functions.inc.php') die( "copy_shopdata_functions.inc.php was not found!");
if(!include 'copy_shopdata_config.php') die( "copy_shopdata_config.php was not found!");

$input = $_GET;

?><!DOCTYPE html> 
<html lang="en"><head><meta charset="utf-8">
<title>Prestashop copyshopdata product image copy</title>
<style>
table.copyform td {
	width: 33%;
	text-align: center;
}
table#imgdirlist td
{ vertical-align:top;
}
</style>
<script type="text/javascript" src="utils8.js"></script>
<script type="text/javascript" src="sorter.js"></script>
<script>
var timeoutid = 0;
var regen_active = 0;
var missingimages = [];
var badimages = [];
var imgsprocessed = 0;

function startcheck()
{ regen_active = true;
  missingimages = [];
  badimages = [];
  imgsprocessed = 0;
  checkimages();
}

function checkimages()
{ if(timeoutid != 0)
  {	clearTimeout(timeoutid);
    timeoutid = 0;
  }
  topform.action.value = "check";
  topform.submit();
  if(topform.autocorrect.checked) /* backup for when call gets timeout and crashes */
    timeoutid = setTimeout(restartcopy,30000+parseInt(topform.interval.value));
}

function restartcheck()
{ var genlist=document.getElementById("genlist"); 
  genlist.innerHTML += " Restart ";
  checkimages();
}

function startcopy()
{ regen_active = true;
  missingimages = [];
  badimages = [];
  imgsprocessed = 0;
  copyimages();
}

function copyimages()
{ if(timeoutid != 0)
  {	clearTimeout(timeoutid);
    timeoutid = 0;
  }
  topform.action.value = "copy";
  topform.submit();
  if(topform.autocorrect.checked) /* backup for when call gets timeout and crashes */
    timeoutid = setTimeout(restartcopy,30000+parseInt(topform.interval.value));
}

function restartcopy()
{ var genlist=document.getElementById("genlist"); 
  genlist.innerHTML += " Restart ";
  copyimages();
}


function starthttpcopy()
{ regen_active = true;
  missingimages = [];
  badimages = [];
  imgsprocessed = 0;
  httpcopyimages();
}

function httpcopyimages()
{ if(timeoutid != 0)
  {	clearTimeout(timeoutid);
    timeoutid = 0;
  }
  topform.action.value = "httpcopy";
  topform.submit();
  if(topform.autocorrect.checked) /* backup for when call gets timeout and crashes */
    timeoutid = setTimeout(restartcopy,30000+parseInt(topform.interval.value));
}

function restarthttpcopy()
{ var genlist=document.getElementById("genlist"); 
  genlist.innerHTML += " Restart ";
  httpcopyimages();
}

function selectimgdirs(elt)
{ var block = document.getElementById("imgdirlist");
  var flds = block.getElementsByTagName("INPUT");
  for (var i = 0; i < flds.length; i++) 
  { if(elt.checked)
	  flds[i].checked=true;
    else
	  flds[i].checked=false;	
  }
}

function submit_other()
{ otherform.verbose.value = topform.verbose.checked;
}

function dynamo2(data)  /* add to copy list */
{ if(timeoutid != 0)
  {	clearTimeout(timeoutid);
    timeoutid = 0;
  }
  var genlist=document.getElementById("genlist"); /* = bottom space where results are shown */
  if(((data.substring(0,9) == "end check") || (data.substring(0,9) == "end copy ")) && regen_active)
  { var parts = data.substring(9).split(' ');
	var lastimage = parts[parts.length-1];
	if(lastimage != "end") 
	{ lastimage = parseInt(lastimage);
	  imgsprocessed += parts.length;
	  last = lastimage;
	}
	else
	{ imgsprocessed += parts.length-1;
	  if(parts.length > 1)
		last = parts[parts.length-2];
	}
	if(parts.length > 1)
	{// if(topform.showall.checked)
	//	  genlist.innerHTML += " - "+data.substring(9);
	//  else
	    genlist.innerHTML += " "+topform.startimg.value+"-"+last;
	}
    if(lastimage != "end")
	{ var interval = parseInt(topform.interval.value);
	  startimg = 1+lastimage;
	  if(isNaN(startimg))
	  { alert(lastimage);
		return;
	  }
	  topform.startimg.value = startimg;
	  if(data.substring(0,9) == "end copy ")
	    setTimeout(copyimages,interval);
	  else
	    setTimeout(checkimages,interval);  
	  return;
	}

	genlist.innerHTML += " Finish: "+imgsprocessed+" imgs processed";
	if(missingimages.length > 0)
	{ for(i=10; i< missingimages.length; i=i+5)
		missingimages[i] = ' '+missingimages[i];
	  genlist.innerHTML += "<br>Missing images: "+missingimages.join(",");
	}
	if(badimages.length > 0)
	{ for(i=10; i< badimages.length; i=i+5)
		badimages[i] = ' '+badimages[i];
	  genlist.innerHTML += "<br>Bad images: "+badimages.join(",");
	}
	genlist.innerHTML += "<br>";
  }
  else if(data.substring(0,6) == "Image ")
  { var results = data.match(/[0-9]+/);
    if(data.indexOf("not Found") > 0)
	  missingimages.push(results[0]);  
	else
	  badimages.push(results[0]); 
    genlist.innerHTML += " "+data;
  }
  else 
    genlist.innerHTML += data;
  return regen_active;
}

function stop_copycheck()
{ stopflag = true;
}

function empty_log()
{ var genlist=document.getElementById("genlist");
  genlist.innerHTML = "";
}
</script>
<link rel="stylesheet" href="style1.css" type="text/css" />
</head><body>
<?php print_menubar(); ?>
<div style="float:right; "><iframe name=tank width=230 height=93></iframe></div>
<h1>Prestashop copyshopdata image copy</h1>
<h2>product images</h2>
This is the interactive version of copy_shopdata_image. You need to set the source shop's data there. Other data set there can be changed here.<br>
You can change settings while the script is running and the changes will take immediate effect.<br>
Only image id's that are present in the database will be copied.<br>
Batchsize 100 is recommended for copying. For checking 1000 can be used.The start id will increase during the process.<br>
For "other images and files" the directory names will be shown in red when the old directory contains file names that are not present in the new one. When it is not present in the new shop a blue background is used.<br>
Derived images are images like 123-small_default.jpg.
Range can be like 1-100,777.
<p>
<?php

  $oldserver = strtolower(_OLD_SERVER_);
  if(($oldserver == "127.0.0.1") OR ($oldserver == "::1")) $oldserver = "localhost";
  $newserver = strtolower(_DB_SERVER_);
  if(($newserver == "127.0.0.1") OR ($newserver == "::1")) $newserver = "localhost"; 
	 
  if($oldserver != $newserver) 
    colordie("For the copying of images the old and the new shop should be on the same server!");
		
  if((_OLD_USER_ != _DB_USER_) || (_OLD_PASSWD_ != _DB_PASSWD_) || (_OLD_NAME_ != _DB_NAME_))
  { $oldconn = @mysqli_connect($oldserver, _OLD_USER_, _OLD_PASSWD_) or colordie ("Could not connect to old database server!!! Did you fill in the credentials of the old shop correctly in the configuration file?");
    mysqli_select_db($oldconn, _OLD_NAME_) or colordie("Error selecting database");
    $query = "SET NAMES utf8";
    $result = dbxquery($oldconn, $query);
  }
  else 
    $oldconn = $conn;

/* both shops may be multi-domain. We first look for a domain that both share */
  $oldshopdomains = array();
  $oldshops = array();
  $query = "SELECT * FROM "._OLD_PREFIX_."shop_url ORDER BY id_shop"; 
  $res = dbxquery($oldconn, $query); 
  while($row = mysqli_fetch_assoc($res))
  { $oldshopdomains[] = $row["domain"];
    $oldshops[$row["domain"]] = $row["physical_uri"];
  }
 
  $newshopdomains = array();
  $newshops = array();
  $query = "SELECT * FROM "._DB_PREFIX_."shop_url ORDER BY id_shop"; 
  $res = dbxquery($conn, $query); 
  while($row = mysqli_fetch_assoc($res))
  { $newshopdomains[] = $row["domain"];
    $newshops[$row["domain"]] = $row["physical_uri"];
  }
  
  $set = array_intersect($oldshopdomains, $newshopdomains);
  if(sizeof($set) == 0)
  { echo "<h1>The old and new shops don't share a common domain! Only HTTP copying allowed.</h1>";

  }
  else
  { $oldpath = $oldshops[$set[0]];
    $newpath = $newshops[$set[0]];
    echo "Copying from ".$oldpath." to ".$newpath."<br>";

  /* now we translate the paths from ps_shop_url into paths on the harddisk */
    $olddir = $newdir = $triplepath;
    $cnt = substr_count($newpath, '/');
    for($i=1; $i<$cnt; $i++)
      $olddir = "../".$olddir;
    $olddir = $olddir.ltrim($oldpath, '/');
    echo "Relative from ".$olddir." to ".$newdir."<br>";
    if($olddir == $newdir) colordie("<p>Old and new image directory are the same!");
  }
  
  /* get the max image id's */
  $res = dbxquery($oldconn, "SELECT MAX(id_image) FROM "._OLD_PREFIX_."image");
  list($oldmax) = mysqli_fetch_row($res);  
  $res = dbxquery($conn, "SELECT MAX(id_image) FROM "._DB_PREFIX_."image");
  list($newmax) = mysqli_fetch_row($res); 
  echo 'The higest id_image for the old shop is '.$oldmax.'. For the new shop it is '.$newmax;

  echo '<form name=topform target=tank action="copy_shopdata_imgproc.php"><input type=checkbox name=verbose> verbose';
  echo '<input type=hidden name=task value="products">';
  echo '<table class="triplemain copyform" style="width:100%"><tr><td colspan=3>';
  echo 'Batchsize <input name=batchsize size=4 value=100> &nbsp; &nbsp; Start id <input name=startimg size=4 value=0> &nbsp; &nbsp; Interval <input name=interval value=50 size=3>ms';
  echo ' &nbsp; &nbsp; <input type=checkbox name="autocorrect"> autocorrect (retry on fail/timeout)';
  echo ' &nbsp; &nbsp; <input type=checkbox name="doderived"> check/copy derived images too (not for http copy)<br>';
  echo ' &nbsp; &nbsp; <input type=checkbox name=replacexist> replace existing images (shown between brackets)<br>';
  echo 'Id_image range <input name=range size="100">';
  echo '<input type=hidden name="action">';

  echo '</td></tr><tr><td>';
  echo '<input type="submit" onclick="startcheck();" value="Check image presence">';
  echo '</td><td>';
  if(sizeof($set) > 0)
    echo '<input type="submit" onclick="startcopy();" value="Copy">';
  echo '</td><td>';
  echo 'Webshop url (like http://www.webshop.com/):<br>';
  echo '<input size=30 name=webshopurl><br>';
  echo '<input type="submit" onclick="starthttpcopy();" value="Http Copy">';
  echo '</td></tr></table>';
  echo '</form><hr>';
  
  echo '<h2>other images and files</h2>';
  echo 'Non-product images - select the directories you want to copy:<br>';
  echo '<form name=otherform target=tank action="copy_shopdata_imgproc.php" onsubmit="submit_other();">';
  echo '<input type=hidden name=task value="other"><input type=hidden name="verbose">';
  echo '<table><tr><td>';
  echo '<table id="imgdirlist" class="triplemain"><tr>';
  $files = scandir($olddir.'/img');
  $dirs = array();
  foreach($files AS $file)
  { if(in_array($file, array('.','..','p'))) continue;
    if(is_dir($olddir.'/img/'.$file))
	  $dirs[] = $file;
  }
  /* now distribute the dir names over the columns. */
  $numcols = 10;
  $len = 1+intval(sizeof($dirs)/$numcols); /* 1 extra column for rounding up and adding img root */
  $ready = false;
  for($i=0; $i<$numcols; $i++)
  { if($ready) break;
	if(is_dir($newdir.'/img/'.$dirs[$i]))
	{ $newdirfiles = scandir($newdir.'/img/'.$dirs[$i]);
	  $olddirfiles = scandir($olddir.'/img/'.$dirs[$i]);
	  $diff = array_diff($newdirfiles, $olddirfiles);
	  $dirmissing = false;
	}
	else
	  $dirmissing = true;
    echo '<td>';
    for($j=0; $j<$len; $j++)
	{ if($ready) continue;
	  $num = ($i*$len)+$j;
	  if($num < sizeof($dirs)) /* last column may not be fully filled */
	  { if($dirmissing)
	      echo "<input type=checkbox name='imgdirs[]' value='".$dirs[$num]."'><span style= 'color:#FF5555;'> ".$dirs[$num]."</span><br>";
	    else if(sizeof($diff) > 0)
	      echo "<input type=checkbox name='imgdirs[]' value='".$dirs[$num]."'><span style= 'color:#5555FF;'> ".$dirs[$num]."</span><br>";
	    else
	      echo "<input type=checkbox name='imgdirs[]' value='".$dirs[$num]."'> ".$dirs[$num]."<br>";
	  }
	  else
	  { $ready = true;
		$newdirfiles = scandir($newdir.'/img/');
		$olddirfiles = scandir($olddir.'/img/');
		$diff = array_diff($newdirfiles, $olddirfiles);
		if(sizeof($diff) > 0)
	      echo "<input type=checkbox name='imgdirs[]' value='/'><span style= 'background-color=#FF5555;'> img root</span>";
	    else
	  	  echo "<input type=checkbox name='imgdirs[]' value='/'> img root";
	  }
	}
    echo '</td>';
  }

  echo '</tr></table>';
  echo '</td><td>&emsp;&emsp;</td><td>';
  echo '<table class=triplemain><tr><td>';
  echo '<input type=checkbox name=downloaddir> Download dir<br>';
  echo '<input type=checkbox name=uploaddir> Upload dir<br>';
  echo '</td></tr></table>';
  echo '</td></tr></table>';
  echo '<input type=checkbox onclick="selectimgdirs(this)"> (un)select all image dirs<br>';
  echo '<input type=checkbox name="replacefiles"> replace existing files<br>';
  echo '<input type=submit value="Copy"></form>';
  
  echo '<hr>';
  echo '<input type=button value="stop copy/check" style="float:left;" onclick="stop_copycheck(); return false;">';  
  echo '<input type=button value="clear log" style="float:right;" onclick="empty_log(); return false;">';
  echo '<span id=genlist style="color:red;"></span>';
  echo "<p>";
  include "footer1.php";	  
  echo '</body></html>';
  
/* analyze folder: this is a recursive function */
function count_files($path)
{ global $triplepath;
  $cnt = 0;

  $files = scandir($path);
  foreach($files as $t) 
  { if (($t==".") || ($t=="..")) continue;
    if (is_dir($path.'/'.$t))
		$cnt += count_files($path.'/'.$t);
	else
		$cnt++;
  }
  return $cnt;
}


