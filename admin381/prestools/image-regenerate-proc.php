<?php 
if(!@include 'approve.php') die( "approve.php was not found!");

/* Note: this script uses only the PHP GD library. Prestashop uses php_image_magician as a shell around that (admin/filemanager/include/php_image_magician.php) */

$indexfile = '<?php
/*
* Index file generated by Prestools for Prestashop and Thirty Bees
*/

header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT");

header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

header("Location: ../");
exit;
';

if(isset($_POST["task"]))
  $input = $_POST;
else if(isset($_GET["task"]))
  $input = $_GET;
else
  exit(0);
$task = $input["task"];
if($task == "") exit(0);
$starttime=time();
$totwidth = $totheight=$totsize=$lastimage=0;
if(isset($input["imageformat"])) 
{ $imageformats = $input["imageformat"]; 
}
else
{ $imageformats = array();
  if(!isset($input["recreateindex"]) && !isset($input["updatelegends"]) && !isset($input['delnotused'])) colordie("Nothing to do.");
} 
$library = intval($input["glibrary"]);
$duration = intval($input["duration"]);
if(isset($input["marginstrip"]))
  $marginstrip = intval($input["marginstrip"]);
else
  $marginstrip = 0;
if(isset($input["replacexist"]))
	$replacexist = true;
else
	$replacexist = false;
$sidemargin = intval($input["sidemargin"]);
$tbmargin = intval($input["tbmargin"]); /* t(op)b(ottom)margin */
$trimcolor = "notset"; /* trigger recalculation */
$trimtolerance=50;
$trimcount=5;

$pngorjpg = $input["pngorjpg"];
if(!in_array($pngorjpg, array("jpg","png","png_all")))
  colordie("You should provide a valid image type: jpg, png when src is png or all png");
$gdformats = $imagickformats = [];
if(in_array($input["use_webp"], array("no", "yes", "only")))
	$use_webp = $input["use_webp"];
else 
	$use_webp = "no";
if($use_webp != "only")
{ if ($library == 1)
	$imagickformats[] = $pngorjpg;
  else
	$gdformats[] = $pngorjpg;
}

if($use_webp != "no")
{ if ($library == 1) /* test that Imagick supports Webp */
  { $imformats = (new Imagick)->queryformats();
    if(!in_array("WEBP", $imformats))
    { $found = false;
      if(function_exists('imagewebp'))
	  { echo "<b>Webp is not supported by your Imagick installation; using GD for Webp.</b> ";
		$gdformats[] = "webp";
	  }
	  else
	  { echo '<b>Your server doesn\'t support Webp creation!</b>';
	  }
	}
	else
		$imagickformats[] = "webp";
  }
  else if(!function_exists('imagewebp'))
  { $imfound = false;
	if( class_exists("Imagick")) 
    { $imformats = (new Imagick)->queryformats(); 
	  if(in_array("WEBP", $imformats))
	  { echo "<b>Webp is not supported by your GD installation; using Imagick for Webp.</b> ";
		$imagickformats[] = "webp";
		$imfound = true;
	  }
	}
	if(!$imfound)
	{ echo '<b>Your server doesn\'t support Webp creation!</b>';
	}
  }
  else
	$gdformats[] = "webp";  
}

dbquery("UPDATE ". _DB_PREFIX_."configuration SET value=0,date_upd=NOW() WHERE id_shop IS NULL AND id_shop_group IS NULL AND name='PRESTOOLS_IMREGEN_STOPFLAG'");

if(isset($input["filter"]))
  $filter = intval($input["filter"]);
else
  $filter = 22; /* 22=LANCZOS */


echo '<!DOCTYPE html>
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
 echo " <span id=finspan></span></br>";
 if(($library==1) && ($task != "check_ids"))
 { echo " using Imagick";
   $filter_array = array(
imagick::FILTER_UNDEFINED => "UNDEFINED",
imagick::FILTER_POINT => "POINT",
imagick::FILTER_BOX => "BOX",
imagick::FILTER_TRIANGLE => "TRIANGLE",
imagick::FILTER_HERMITE => "HERMITE",
imagick::FILTER_HANNING => "HANNING",
imagick::FILTER_HAMMING => "HAMMING",
imagick::FILTER_BLACKMAN => "BLACKMAN",
imagick::FILTER_GAUSSIAN => "GAUSSIAN",
imagick::FILTER_QUADRATIC => "QUADRATIC",
imagick::FILTER_CUBIC => "CUBIC",
imagick::FILTER_CATROM => "CATROM",
imagick::FILTER_MITCHELL => "MITCHELL",
imagick::FILTER_LANCZOS => "LANCZOS",
imagick::FILTER_BESSEL => "BESSEL",
imagick::FILTER_SINC => "SINC");
   if($verbose=="true")
	 echo " with filter ".$filter_array[$filter]."<br>";
  }

$id_lang = get_configuration_value('PS_LANG_DEFAULT');
$generate_hight_dpi_images = get_configuration_value('PS_HIGHT_DPI');
$jpg_quality = intval($input['jpg_quality']); 
if(($jpg_quality <=0) || ($jpg_quality > 100))
  colordie("You should provide a valid JPG quality"); 
$png_quality =  intval($input['png_quality']); 
if(($png_quality <=0) || ($png_quality > 9))
  colordie("You should provide a valid PNG quality"); 
$webp_quality = get_configuration_value('TB_WEBP_QUALITY');
if(($webp_quality <=0) || ($webp_quality > 100))
    $webp_quality = 90;

$shownotfound = true;

if(isset($demo_mode) && $demo_mode)
{ echo '<script>alert("The script is in demo mode. Nothing is changed!");</script>';
  exit(0);
}

/* we create a structure $updatedformats
   each entry has an index $ratio and consists of an array of height-width pairs
   to account for rounding errors we allow for a variation of 3%
   Aim is to minimize calculations and increasing speed.
*/

$allformats = array();
$updatedformats = array();
$imgtypes = '';
if(($task == "sequential") && (substr($input['batchsize'],0,1) == '/'))
	$imgtypes = preg_replace('/[^,0-9]/','',$input["imgtypes"]);

  /* get the image formats and create images */
$query = 'SELECT name,width,height,id_image_type FROM `'._DB_PREFIX_.'image_type`';
$query .= ' WHERE products=1'; 
if($imgtypes != "")  /* when using subbatches the main page submitted a list of formats */
  $query .= ' AND id_image_type IN ('.$imgtypes.')'; 
$query .= ' ORDER BY `name` ASC';
$res = dbquery($query);
while($row = mysqli_fetch_array($res))
{ $allformats[] = $row["name"];  /* used for delnotused */
  if(!in_array($row["id_image_type"],$imageformats)) continue;
  if(sizeof($imageformats) == 1)
  { $row["height"] = intval($input["customheight"]);
	$row["width"] = intval($input["customwidth"]);
  }
  $ratio = 100*$row["width"]/$row["height"];
  $found = false;
//  echo $ratio."=".$row["width"]." / ".$row["height"]."<br>";
  $updatedformats[$row['name']] = array($row['width'],$row['height']);
}

if($task == "check_ids")
{ $squery="select id_image, id_product from ". _DB_PREFIX_."image i WHERE ";
  $squery .= rangetosql($input["id_image"],"id_image");
  if(!isset($input["activeprods"]) || !isset($input["inactiveprods"]))
  { if(isset($input["activeprods"]))
    { $squery .= " AND exists (SELECT NULL FROM ". _DB_PREFIX_."product_shop ps WHERE ps.id_product=i.id_product AND ps.active=1)";
    }
    else if(isset($input["inactiveprods"]))
    { $squery .= " AND exists (SELECT NULL FROM ". _DB_PREFIX_."product_shop pt WHERE pt.id_product=i.id_product AND pt.active=0)";
    }
    else
	  $squery .= " AND 0";
  }
  $squery .= " ORDER BY id_image";
  $sres=dbquery($squery);
  $num_images = mysqli_num_rows($sres);
  echo '<b>'.$num_images.' images</b>';

  adapt_timelimit($num_images);
  while($srow = mysqli_fetch_array($sres))
  { check_image($srow["id_image"]);
  }
}
else if($task == "image_ids")
{ $sections = explode(",",$input["id_image"]);
  $squery="select id_image, id_product from ". _DB_PREFIX_."image i WHERE ";
  $squery .= rangetosql($input["id_image"],"id_image");
  if(!isset($input["activeprods"]) || !isset($input["inactiveprods"]))
  { if(isset($input["activeprods"]))
    { $squery .= " AND exists (SELECT NULL FROM ". _DB_PREFIX_."product_shop ps WHERE ps.id_product=i.id_product AND ps.active=1)";
    }
    else if(isset($input["inactiveprods"]))
    { $squery .= " AND exists (SELECT NULL FROM ". _DB_PREFIX_."product_shop pt WHERE pt.id_product=i.id_product AND pt.active=0)";
    }
    else
	  $squery .= " AND 0";
  }
  $squery .= " ORDER BY id_image";
  $sres=dbquery($squery);
  $num_images = mysqli_num_rows($sres);
  echo '<b>'.$num_images.' images</b>';

  adapt_timelimit($num_images);
  while($srow = mysqli_fetch_array($sres))
  { regenerate_image($srow["id_image"], $srow["id_product"]);
  }
}
else if($task == "sequential")
{ $startimg = intval($input["startimg"]);
  $batchsize = $input["batchsize"];
  $logbook = "";
  if(substr($batchsize,0,1) == '/')
    $numimgz = 1;
  else
    $numimgz = intval($batchsize);
  $squery="select id_image, id_product from ". _DB_PREFIX_."image i WHERE ";
  $squery .= rangetosql($input["imgrange"],"id_image");
  $squery .= " AND (id_image >= ".$startimg.")";
  if(!isset($input["activeprods"]) || !isset($input["inactiveprods"]))
  { if(isset($input["activeprods"]))
    { $squery .= " AND exists (SELECT NULL FROM ". _DB_PREFIX_."product_shop ps WHERE ps.id_product=i.id_product AND ps.active=1)";
    }
    else if(isset($input["inactiveprods"]))
    { $squery .= " AND exists (SELECT NULL FROM ". _DB_PREFIX_."product_shop pt WHERE pt.id_product=i.id_product AND pt.active=0)";
    }
    else
	  $squery .= " AND 0";
  }
  $squery .= " ORDER BY id_image LIMIT ".$numimgz;
  $sres=dbquery($squery);
  $num_images = mysqli_num_rows($sres);
  echo '<b>'.$num_images.' images</b>';
//  adapt_timelimit($num_images);
  while($srow = mysqli_fetch_array($sres))
  { regenerate_image($srow["id_image"], $srow["id_product"]);
	$logbook .= " ".$srow["id_image"];
	if(substr($batchsize,0,1) == '/')
	  $logbook .= " (".$imgtypes.")";
  }
  if(mysqli_num_rows($sres) < $numimgz)
	 $logbook .= " end";
}
else if($task == "product_ids")
{ $squery="select id_image,id_product FROM ". _DB_PREFIX_."image i WHERE ";
  $squery .= rangetosql($input["id_product"],"id_product");
  if(!isset($input["activeprods"]) || !isset($input["inactiveprods"]))
  { if(isset($input["activeprods"]))
    { $squery .= " AND exists (SELECT NULL FROM ". _DB_PREFIX_."product_shop ps WHERE ps.id_product=i.id_product AND ps.active=1)";
    }
    else if(isset($input["inactiveprods"]))
    { $squery .= " AND exists (SELECT NULL FROM ". _DB_PREFIX_."product_shop pt WHERE pt.id_product=i.id_product AND pt.active=0)";
    }
    else
	  $squery .= " AND 0";
  }
  $squery .= " ORDER BY id_product, id_image";
  $sres=dbquery($squery);
  $num_images = mysqli_num_rows($sres);
  echo '<b>'.$num_images.' images</b>';

  adapt_timelimit($num_images);
  $lastproduct = 0;
  while($srow = mysqli_fetch_array($sres))
  { if($srow["id_product"] != $lastproduct)
	{ $lastproduct = $srow["id_product"];
	  pecho(" p".$lastproduct);
	}
	regenerate_image($srow["id_image"], $srow["id_product"]);
  }
}
else if($task == "category_ids")
{ $cquery = "select id_image,i.id_product FROM ". _DB_PREFIX_."image i";
  $cquery .= " LEFT JOIN ". _DB_PREFIX_."category_product cp ON i.id_product=cp.id_product";
  $cquery .= " WHERE ";
  $cquery .= rangetosql($input["id_category"],"id_category");
  if(!isset($input["activeprods"]) || !isset($input["inactiveprods"]))
  { if(isset($input["activeprods"]))
    { $cquery .= " AND exists (SELECT NULL FROM ". _DB_PREFIX_."product_shop ps WHERE ps.id_product=i.id_product AND ps.active=1)";
    }
    else if(isset($input["inactiveprods"]))
    { $cquery .= " AND exists (SELECT NULL FROM ". _DB_PREFIX_."product_shop pt WHERE pt.id_product=i.id_product AND pt.active=0)";
    }
    else
	  $cquery .= " AND 0";
  }
  $cquery .= " ORDER BY id_product, id_image";
  $cres=dbquery($cquery);
  $num_images = mysqli_num_rows($cres);
  echo '<b>'.$num_images.' images</b>';

  $lastproduct = 0;
  adapt_timelimit($num_images);
  while($crow = mysqli_fetch_array($cres))
  { if($crow["id_product"] != $lastproduct)
	{ $lastproduct = $crow["id_product"];
	  pecho(" p".$lastproduct);
	}
	regenerate_image($crow["id_image"], $crow["id_product"]);
  }
}
$endtime = time();
if($num_images == 0) { echo "No images to process!"; return; }
echo '<br>'.$num_images.' images/'.($endtime-$starttime).'s: '.round((($endtime-$starttime)/$num_images),2).'s per image';
echo '<br>Av.width='.round(($totwidth/$num_images),2).'; Av.height='.round(($totheight/$num_images),2).'; Av.size='.round(($totsize/$num_images),2).' pixels';; 
if(!$replacexist && ($task != "check_ids") && ($task != "sequential")) pecho(" Replace existing is disabled so some images may have been skipped. ");
echo '<h1>Finish</h1>';
if($task == "sequential")
  echo '<script>finspan=document.getElementById("finspan"); finspan.innerHTML="<b>Finished in '.($endtime-$starttime).'s</b>"; parent.dynamo2("end sequential '.$logbook.'");</script>';
else
  echo '<script>finspan=document.getElementById("finspan"); finspan.innerHTML="<b>Finished in '.($endtime-$starttime).'s</b>"; parent.dynamo2("end '.$task.' '.$lastimage.'");</script>';

function regenerate_image($id_image, $id_product)
{ global $library, $imageformats, $triplepath, $allformats, $shownotfound, $verbose,
  $indexfile, $lastimage, $task, $replacexist, $gdformats, $imagickformats;
  global $input, $conn;
  if(!($id_image%10)) flush(); /* flush about once every 10 images */
  $srcfile = "";
  /* check for interuption */
  $gquery = "SELECT value FROM ". _DB_PREFIX_."configuration";
  $gquery .= ' WHERE name="PRESTOOLS_IMREGEN_STOPFLAG" AND id_shop IS NULL and id_shop_group IS NULL';
  $gres = dbquery($gquery);
  if(mysqli_num_rows($gres)>0)
  { $grow = mysqli_fetch_array($gres);
    if($grow["value"]=="1")
    { $cquery="UPDATE ". _DB_PREFIX_."configuration";
      $cquery .= ' SET value=0,date_upd=NOW() WHERE id_shop IS NULL AND id_shop_group IS NULL AND name="PRESTOOLS_IMREGEN_STOPFLAG"';
      dbquery($cquery);
	  pecho("Regeneration interruped by User");
	  die("!!!");
	}
  }
  $dir = $triplepath.'img/p'.getpath($id_image).'/';
  if((!file_exists($dir)) || (!is_dir($dir)))
  { if($shownotfound)
      pecho('Image '.$id_image.' dir not Found. ');
	return;
  }
  
  $srcfile = $id_image.".jpg";
  if(!is_file($dir.$srcfile))
  { if($shownotfound)
       pecho('Image '.$id_image.'(p'.$id_product.') not Found. ');
	return;
  }
  
  $files = scandir($dir);
  $foundjformats = $foundwformats = array();
  foreach ($files AS $file)
  { if(is_dir($dir.$file)) continue;
	if(($file==".") || ($file=="..")) continue;
    if($file == $srcfile) continue;
    if($file == "index.php") continue;
    $pos = strpos($file,"-");
    if(!$pos)
	{ if(isset($input["delnotused"]))
	  { if(!unlink($dir.$file))
	      pecho (" error deleting ".$file);
	  }
	  continue;
	}
	$suffix = substr($file,$pos+1);
	$suffix = str_ireplace(".jpg","",$suffix);
	$suffix = str_ireplace(".webp","",$suffix);
	if(!in_array($suffix,$allformats))
	{ if(isset($input["delnotused"]))
	  { if(!unlink($dir.$file))
	      pecho (" error deleting ".$file);
	  }
	  continue;
	}
  }

  /* recreateindex can only after all the files have been handled; it replaces index.php */
  if(isset($input["recreateindex"]))
  { if($verbose)
	  echo "<br>Recreating ".$dir."index.php";
    $fx = fopen($dir."index.php", "w");
    fwrite($fx, $indexfile);
	fclose($fx);
  }

  /* the following section has the problem that product_lang contains id_shop but image_lang not */
  if(isset($input["updatelegends"]))
  { $qry = "SELECT i.id_image, i.id_product, i.cover,il.legend,il.id_lang,pl.id_shop, pl.name FROM "._DB_PREFIX_."image i";
    $qry .= " LEFT JOIN "._DB_PREFIX_."product_lang pl ON i.id_product=pl.id_product";
	$qry .= " LEFT JOIN "._DB_PREFIX_."image_lang il ON i.id_image=il.id_image AND pl.id_lang=il.id_lang";
	$qry .= " INNER JOIN "._DB_PREFIX_."lang l ON pl.id_lang=l.id_lang";
	$qry .= " INNER JOIN "._DB_PREFIX_."shop s ON s.id_shop=pl.id_shop";
	$qry .= " WHERE i.id_image=".$id_image." AND l.active=1 AND s.active=1 AND s.deleted=0";
	$qry  .= " ORDER BY id_lang, id_shop";
	$res = dbquery($qry);
	$lastlang =0;
	while($row = mysqli_fetch_array($res))
	{ if($row["id_lang"] != $lastlang) /* take only the first valid name */
	  { $setimg = false;
	    $newlegend = "";
		if($row["cover"] == 1)
		{ if($input["legenddefaultaction"] == "update")
		  { $setimg = true;
			$newlegend = $row["name"];
		  }
		}
		else
		{ if($input["legendotheraction"] == "name")
		  { $setimg = true;
			$newlegend = $row["name"];
		  }
		  else if(($input["legendotheraction"] == "namewhenempty") && ($row["legend"]==""))
		  { $setimg = true;
			$newlegend = $row["name"];
		  }
		  else if($input["legendotheraction"] == "empty")
		  { $setimg = true;
		  }
		}
		if($setimg)
		{ if($row["legend"] != $row["name"])
		  { $q = "UPDATE "._DB_PREFIX_."image_lang SET legend='".mysqli_real_escape_string($conn, $row["name"])."' WHERE id_image=".$id_image." AND id_lang=".$row["id_lang"];
			$r = dbquery($q);
		  }
		}		
	  }
	  $lastlang = $row["id_lang"];
	}
  }
  
  if ($verbose=="true") echo "<br>";
  if($task != "sequential")
    pecho(" ".$id_image);
  if ($verbose=="true") echo " Creating";

  if(!empty($imageformats))
  { if(sizeof($imagickformats) > 0)
      regenerate_image_imagick($id_image, $srcfile, $dir);
    if(sizeof($gdformats) > 0)
	  regenerate_image_gd($id_image, $srcfile, $dir);
  }
  
  if($replacexist)  /* delete backoffice thumbnail */
  { $ipath =  $triplepath.'img/tmp/';
	if(is_dir($ipath))
	{ $files = scandir($ipath);
	  $filetrunk = "product_mini_".$id_product."_";
	  $len = strlen($filetrunk);
      foreach ($files as $file)
		if(substr($file,0,$len) == $filetrunk)
			unlink($ipath.$file); 
	}
  }
  
  $lastimage = $id_image;
}

function regenerate_image_imagick($id_image, $srcfile, $dir)
{ global $verbose, $filter, $updatedformats, $marginstrip,$sidemargin,$tbmargin;
  global $totwidth, $totheight, $totsize, $jpg_quality, $png_quality, $webp_quality;
  global $pngorjpg, $use_webp, $replacexist, $imagickformats;
  try {
    $src = new Imagick(realpath($dir.$srcfile));
} catch (ImagickException $e) {
	pecho ('<b>Image Creation Failing For '.$srcfile.'</b><br>');
	return;
}

  if(!$src)
  { pecho ('<b>Image creation failed for file '.$srcfile.'</b><br>');
    return;
  }
  
  $pos = strrpos($srcfile,".");
  $imgroot = substr($srcfile,0,$pos);
  $imgbase2 = realpath($dir.$srcfile);
  $pos = strrpos($imgbase2,".");
  $imgbase = substr($imgbase2,0,$pos);

  if(method_exists($src, 'getImageCompression')) /* For PHP Imagick versions >=3.3 */
  { $compressiontype = $src->getImageCompression();
    if($compressiontype == Imagick::COMPRESSION_ZIP) echo ":png";
    else if($compressiontype == Imagick::COMPRESSION_JPEG) {}
    else if($compressiontype == Imagick::COMPRESSION_GIF) echo ":gif";
//  echo " setting:".$jpg_quality;
  }
  else
	 $compressiontype = Imagick::COMPRESSION_JPEG;
  
  $srcwidth  = $src->getImageWidth();
  $srcheight = $src->getImageHeight();
  $totwidth+=$srcwidth; $totheight+=$srcheight; $totsize+=($srcheight*$srcwidth);
  
  $targettypes = [];
  foreach($imagickformats AS $f)
  { if($f == "png")
	{ if($compressiontype == Imagick::COMPRESSION_ZIP)
		$targettypes[] = "png";
	  else
		$targettypes[] = "jpg";  
	}
	else if($pngorjpg == "png_all")
	  $targettypes[] = "png";
    else
	  $targettypes[] = $f;	
  }

  if($marginstrip)
  { $margins = trimImage_imagick($src);
    $mtop = $margins[0];
	$mright = $margins[1];
    $mbottom = $margins[2];
    $mleft = $margins[3];
	$mwidth = $mright-$mleft;
	$mheight = $mbottom-$mtop;

    $temp = clone $src;
    $temp->cropImage($mwidth, $mheight, $mleft, $mtop);
	$temp->setImagePage(0,0,0,0);
    $src = $temp;
	$srcwidth = $mwidth; $srcheight = $mheight; 
  }
  
  foreach($updatedformats AS $key => $format)
  { $makepng = $makewebp = $makejpg = false;
    if(in_array("png",$targettypes))
	  if($replacexist || (!file_exists($imgbase."-".$key.'.jpg')))
		$makepng = true;
	if(in_array("webp",$targettypes))
	  if($replacexist || (!file_exists($imgbase."-".$key.'.webp')))
		$makewebp = true;
	if(in_array("jpg",$targettypes))
	  if($replacexist || (!file_exists($imgbase."-".$key.'.jpg')))
		$makejpg = true;
	
	$targetwidth = $format[0];
	$targetheight = $format[1];
	/* newheight/newwidth are the new dimensions of the image (excluding whitespace) */
    if($sidemargin || $tbmargin) /* add margin */
    { $usedwidth = $targetwidth*(100-$sidemargin)/100;
      $usedheight = $targetheight*(100-$tbmargin)/100;
    }
    else
    { $usedwidth = $targetwidth;
	  $usedheight = $targetheight;
    }
	if(($usedwidth < $srcwidth) || ($usedheight < $srcheight))
	{ if(($usedwidth / $srcwidth) > ($usedheight / $srcheight))
	  { $mul = $usedheight / $srcheight;
		$newheight = round($usedheight,0,PHP_ROUND_HALF_UP);
	    $newwidth = round($srcwidth*$mul,0,PHP_ROUND_HALF_UP);
	  }
	  else
	  { $mul = $usedwidth / $srcwidth;
		$newwidth = round($usedwidth,0,PHP_ROUND_HALF_UP);
		$newheight = round($srcheight*$mul,0,PHP_ROUND_HALF_UP);
	  }
	  /* rounding may cause the left margin to have one pixel more than the right */
	  /* here a correction */
	  if(($targetwidth-$newwidth)%2)
	  { if($newwidth > ($srcwidth*$mul))
		  $newwidth--;
	    else
		  $newwidth++;
	  }
	  if(($targetheight-$newheight)%2)
	  { if($newheight > ($srcheight*$mul))
		  $newheight--;
	    else
		  $newheight++;
	  }
	}
	else
	{ $newwidth = $srcwidth;
	  $newheight = $srcheight;
	}

    if($makepng || $makewebp)
	{ $img = clone $src;
	  if($srcheight != $newheight)
		$img->resizeImage($newwidth,$newheight,$filter, 1);
	  if(($newwidth != $targetwidth) || ($newheight != $targetheight))
	  { $img2 = new Imagick();
        $img2->newImage($targetwidth, $targetheight, new ImagickPixel('none'));
	    $img2->compositeImage($img, imagick::COMPOSITE_DEFAULT, ($targetwidth-$newwidth)/2, ($targetheight-$newheight)/2);
	    $img=$img2;
	  }
	  
	  if($makepng)
      { $formatter = "png:"; /* imagick uses extension as indicator of filetype. We need to override that */
		if(!$img->writeImage($formatter.$imgbase."-".$key.'.jpg'))
		  pecho("Error creating ".$imgbase."-".$key.'.jpg'); /* image, filename, quality */
	  }
	  
      if($makewebp)
	  { $img->setImageCompressionQuality($webp_quality);
	    $img->setImageFormat( "webp" );
	    $img->setOption('webp:method', '5'); /* compression method: 0-6; higher is slower compression and smaller images; 4 is default */
	    if(!$img->writeImage($imgbase."-".$key.'.webp'))
		  echo "<br>Problem writing webp image!"; 
	  }
	  
	  $img->destroy();
	}
	  
	if($makejpg)
	{ $img = clone $src;
	  if($srcheight != $newheight)
		$img->resizeImage($newwidth,$newheight,$filter, 1);
	  if(($newwidth != $targetwidth) || ($newheight != $targetheight))
	  { $img2 = new Imagick();
        $img2->newImage($targetwidth, $targetheight, new ImagickPixel('white'));
	    $img2->compositeImage($img, imagick::COMPOSITE_DEFAULT, ($targetwidth-$newwidth)/2, ($targetheight-$newheight)/2);
	    $img=$img2;
	  }
	  $img->setImageCompression(Imagick::COMPRESSION_JPEG);
	  $img->setInterlaceScheme(Imagick::INTERLACE_PLANE); /* alternatives: INTERLACE_NONE and INTERLACE_LINE */
	  $img->setImageCompressionQuality($jpg_quality);
	  if(!$img->writeImage($imgbase."-".$key.'.jpg'))
		  pecho("Error creating ".$imgbase."-".$key.'.jpg'); 
	  $img->destroy();
	}
  }
  if ($verbose=="true") echo "<br>";
}

function regenerate_image_gd($id_image, $srcfile, $dir)
{ global $verbose, $updatedformats, $marginstrip,$sidemargin,$tbmargin;
  global $totwidth,$totheight,$totsize, $jpg_quality, $png_quality, $webp_quality;
  global $pngorjpg, $use_webp, $replacexist, $gdformats;
  $pos = strrpos($srcfile,".");
  $imgroot = substr($srcfile,0,$pos);
  $imgbase2 = realpath($dir.$srcfile);
  $pos = strrpos($imgbase2,".");
  $imgbase = substr($imgbase2,0,$pos);
  list($srcwidth, $srcheight, $imgtype, $attr) = @getimagesize( $dir.$srcfile );
  $totwidth+=$srcwidth; $totheight+=$srcheight; $totsize+=($srcheight*$srcwidth);
//  echo "width=".$srcwidth.", height=".$srcheight." imgtype=".$imgtype." and attr=".$attr."<br>";
  if($srcheight == 0)
  { pecho ("Image ".$id_image."(".$dir.$srcfile.") reports height 0 and cannot be processed<br>");
    return;
  }

  /* image type constants are defined here: http://php.net/manual/en/function.exif-imagetype.php */
  if($imgtype == IMAGETYPE_GIF)
  { $src = imagecreatefromgif($dir.$srcfile);
	$msg = " ".$id_image.":gif;";
  }
  else if($imgtype == IMAGETYPE_PNG)
  { $src = imagecreatefrompng($dir.$srcfile);
	$msg = " ".$id_image.":png;";
  }
  else if($imgtype == IMAGETYPE_JPEG)
  { $src = imagecreatefromjpeg($dir.$srcfile);
	$msg = " ".$id_image.":jpg;";
  }
  else if($imgtype == IMAGETYPE_WBMP)
  { $src = imagecreatefromwbmp($dir.$srcfile);
	$msg = " ".$id_image.":wbmp;";
  }
  else if($imgtype == IMAGETYPE_WEBP)
  { $src = imagecreatefromwebp($dir.$srcfile);
	$msg = " ".$id_image.":webp;";
  }
  else if($imgtype == IMAGETYPE_BMP)
  { $src = imagecreatefrombmp($dir.$srcfile);	/* not a GD library function, but defined below */
	$msg = " ".$id_image.":bmp;";
  }
  else /* if we get an unknown format we try it as a jpg */
  { $src = imagecreatefromjpeg($dir.$srcfile);
	pecho ('<b>Unknown image type '.$imgtype.'</b><br>');
	$msg = '<b>Unknown image type '.$imgtype.'</b><br>';
  }
  echo $msg;
  
  if(!$src)
  { pecho ('<b>Image creation failed for file '.$srcfile.'</b><br>');
    return;
  }
  
  foreach($gdformats AS $f)
  { if($f == "png")
	{ if($imgtype == IMAGETYPE_PNG)
		$targettypes[] = "png";
	  else
		$targettypes[] = "jpg";  
	}
	else if($pngorjpg == "png_all")
	  $targettypes[] = "png";
    else
	  $targettypes[] = $f;	
  }

  if($marginstrip)     /* when trim was selected */
  { $margins = trimImage_gd($src);
    $mtop = $margins[0];
	$mright = $margins[1];
    $mbottom = $margins[2];
    $mleft = $margins[3];
	$mwidth = $mright-$mleft;
	$mheight = $mbottom-$mtop;
	$img2 = imagecrop($src,['x' => $mleft, 'y' => $mtop, 'width' => $mwidth, 'height' => $mheight]);
//	$img2 = imagecropauto($src,IMG_CROP_THRESHOLD,?,?);
    $src = $img2;
	$srcwidth = $mwidth; $srcheight = $mheight;;
  }
  
  foreach($updatedformats AS $key => $format)
  { $makepng = $makewebp = $makejpg = false;
    if(in_array("png",$targettypes))
	  if($replacexist || (!file_exists($imgbase."-".$key.'.jpg')))
		$makepng = true;
	if(in_array("webp",$targettypes))
	  if($replacexist || (!file_exists($imgbase."-".$key.'.webp')))
		$makewebp = true;
	if(in_array("jpg",$targettypes))
	  if($replacexist || (!file_exists($imgbase."-".$key.'.jpg')))
		$makejpg = true;
	
	$targetwidth = $format[0];
	$targetheight = $format[1];
	/* newheight/newwidth are the new dimensions of the image (excluding whitespace) */
    if($sidemargin || $tbmargin) /* add margin */
    { $usedwidth = $targetwidth*(100-$sidemargin)/100;
      $usedheight = $targetheight*(100-$tbmargin)/100;
    }
    else
    { $usedwidth = $targetwidth;
	  $usedheight = $targetheight;
    }
	if(($usedwidth < $srcwidth) || ($usedheight < $srcheight))
	{ if(($usedwidth / $srcwidth) > ($usedheight / $srcheight))
	  { $mul = $usedheight / $srcheight;
		$newheight = round($usedheight,0,PHP_ROUND_HALF_UP);
	    $newwidth = round($srcwidth*$mul,0,PHP_ROUND_HALF_UP);
	  }
	  else
	  { $mul = $usedwidth / $srcwidth;
		$newwidth = round($usedwidth,0,PHP_ROUND_HALF_UP);
		$newheight = round($srcheight*$mul,0,PHP_ROUND_HALF_UP);
	  }
	  /* rounding may cause the left margin to have one pixel more than the right */
	  /* here a correction */
	  if(($targetwidth-$newwidth)%2)
	  { if($newwidth > ($srcwidth*$mul))
		  $newwidth--;
	    else
		  $newwidth++;
	  }
	  if(($targetheight-$newheight)%2)
	  { if($newheight > ($srcheight*$mul))
		  $newheight--;
	    else
		  $newheight++;
	  }
	}
	else
	{ $newwidth = $srcwidth;
	  $newheight = $srcheight;
	}

    if($makepng || $makewebp)
	{
	 /* If an image is too small we put a transparant border around it */
	  $img2 = imagecreatetruecolor ( $targetwidth , $targetheight );  /* create black image */
	  if(($newwidth != $targetwidth) || ($newheight != $targetheight))
	  { imagealphablending($img2, false);
		imagesavealpha($img2, true);
		$transparent = imagecolorallocatealpha($img2, 255, 255, 255, 127);
		imagefilledrectangle($img2, 0, 0, $targetwidth , $targetheight, $transparent);
		
	  }
	  imagecopyresampled($img2, $src, ($targetwidth-$newwidth)/2, ($targetheight-$newheight)/2, 0, 0, $newwidth, $newheight, $srcwidth, $srcheight);
	  
      if($makepng)
	  { if(!imagepng($img2, $dir.$imgroot."-".$key.'.jpg', $png_quality))
		  pecho("Error creating ".$dir.$imgroot."-".$key.'.jpg'); /* image, filename, quality */
	  }
	  if($makewebp)
	  { if(!imagewebp($img2, $dir.$imgroot."-".$key.'.webp', $webp_quality))
		  pecho("Error creating ".$dir.$imgroot."-".$key.'.webp'); 
	  }
	}

	if($makejpg)
	{ /* If an image is too small we put a transparant border around it */
	  $img2 = imagecreatetruecolor ( $targetwidth , $targetheight );  /* create black image */
	  if(($newwidth != $targetwidth) || ($newheight != $targetheight))
	  { $white = imagecolorallocate($img2, 255, 255, 255);
	    imagefill($img2, 0, 0, $white);
	  }
	  imageinterlace($img2, 1); /* make the image progressive */
	  imagecopyresampled($img2, $src, ($targetwidth-$newwidth)/2, ($targetheight-$newheight)/2, 0, 0, $newwidth, $newheight, $srcwidth, $srcheight);
	  
      if(!imagejpeg($img2, $dir.$imgroot."-".$key.'.jpg', $jpg_quality))
		  pecho("Error creating ".$dir.$imgroot."-".$key.'.jpg'); /* image, filename, quality */
	}
  }
  if ($verbose=="true") echo "<br>";
}

function adapt_timelimit($numrecs)
{ global $duration,$marginstrip, $task;
  if($marginstrip) $perimg = 27;
  else $perimg = 4;
  $msg = $numrecs.' images to regenerate.';
  $timeout = $numrecs*$perimg;
  if(($timeout > 30) || ($duration != 0))
  { if($duration > $timeout)
	   $timeout = $duration;
    set_time_limit ($timeout);
	$msg .= ' The timeout has been extended to '.$timeout.' seconds.';
  }
  if($task != "check_ids")
    echo '<script>parent.dynamo2("'.$msg.'");</script>';
}

function pecho($txt)
{ echo $txt;
  echo '<script>parent.dynamo2("'.$txt.'");</script>';
}

/* copied and adapted from http://php.net/manual/en/function.imagecolorat.php */
/* arguments: resource $image , int $colour , int $tolerance */
function trimImage_gd($im)
{ global $trimcolor, $trimtolerance, $trimcount;
  // if trim colour ($c) isn't a number between 0 - 255
  if ((strlen($trimcolor) != 6) || (!ctype_xdigit($trimcolor))) 
  { // grab the colour from the top left corner and use that as default
    $rgb = imagecolorat($im, 2, 2); // 2 pixels in to avoid messy edges
    $r = ($rgb >> 16) & 0xFF;
    $g = ($rgb >> 8) & 0xFF;
    $b = $rgb & 0xFF;
    $rgb = imagecolorat($im, 8, 2); // 2 pixels in to avoid messy edges
    $r += ($rgb >> 16) & 0xFF;
    $g += ($rgb >> 8) & 0xFF;
    $b += $rgb & 0xFF;
    $rgb = imagecolorat($im, 2, 8); // 2 pixels in to avoid messy edges
    $r += ($rgb >> 16) & 0xFF;
    $g += ($rgb >> 8) & 0xFF;
    $b += $rgb & 0xFF;
	$rt = $r/3;
	$gt = $g/3;
	$bt = $b/3;
  }
  else
  { $rt = ($trimcolor >> 16) & 0xFF;
    $gt = ($trimcolor >> 8) & 0xFF;
    $bt = $trimcolor & 0xFF;
  }

  // if tolerance ($t) isn't a number between 0 - 255 use 10 as default
  if (!is_numeric($trimtolerance) || $trimtolerance < 0 || $trimtolerance > 255) $trimtolerance = 50;
//  echo "BT=".$bt." GT=".$gt." RT=".$rt." TT=".$trimtolerance;
  // Calculate these once rather than for every iteration

  $w = imagesx($im); // image width
  $h = imagesy($im); // image height
  $x_axis=$y_axis=array();
  for($i=0;$i<$w; $i++) $x_axis[$i]=0;
  for($i=0;$i<$h; $i++) $y_axis[$i]=0;
  for($x = 0; $x < $w; $x++) {
    for($y = 0; $y < $h; $y++) {
      $rgb = imagecolorat($im, $x, $y);
      $r = ($rgb >> 16) & 0xFF;
      $g = ($rgb >> 8) & 0xFF;
      $b = $rgb & 0xFF;
	  $diff = abs($rt-$r)+abs($gt-$g)+abs($bt-$b);
      if ($diff > $trimtolerance) 
	  { //echo "<br>".$r."-".$g."-".$b."=".$diff." (".$x.",",$y.")";
        $y_axis[$y]++;
        $x_axis[$x]++;
      }
    }
  }
  // sort them so first and last occurances are at start and end

  for($y=0; $y<$h-1; $y++)
  { if(($y_axis[$y] >= $trimcount) && ($y_axis[$y+1] >= $trimcount))
	  {$top = $y; break; }
  }
  for($y=$h-1; $y>0; $y--)
  { if(($y_axis[$y] >= $trimcount) && ($y_axis[$y-1] >= $trimcount))
	  {$bottom = $y; break; }
  }
  for($x=0; $x<$w-1; $x++)
  { if(($x_axis[$x] >= $trimcount) && ($x_axis[$x+1] >= $trimcount))
	  {$left = $x; break; }
  }
  for($x=$w-1; $x>0; $x--)
  { if(($x_axis[$x] >= $trimcount) && ($x_axis[$x-1] >= $trimcount))
	  {$right = $x; break; }
  }

 // echo "<br>XXS-top=".$top."-right=".$right."-bottom=".$bottom."-left=".$left."<br>"; 
    /* add a 2% margin */
    $wmargin = intval(2*($right-$left)/100);
    $hmargin = intval(2*($bottom-$top)/100);
    $top = $top-$hmargin;
    if($top <0) $top=0;
    $right = $right+$wmargin;
    if($right >= $w) $right = $w-1;
    $bottom = $bottom+$hmargin;
    if($bottom >= $h) $bottom = $h-1;
    $left = $left-$wmargin;
    if($left <0) $left=0;

  return array($top,$right,$bottom,$left);
}

/* copied and adapted from http://php.net/manual/en/function.imagecolorat.php */
/* arguments: resource $image , int $colour , int $tolerance */
function trimImage_imagick($im) 
{ global $trimcolor, $trimtolerance, $trimcount;
  if ((strlen($trimcolor) != 6) || (!ctype_xdigit($trimcolor))) 
  { // grab the colour from the top left corner and use that as default
	$pixel = $im->getImagePixelColor(2, 2);
    $rgb = $pixel->getColor();
    $r = $rgb['r'];
    $g = $rgb['g'];
    $b = $rgb['b'];
	$pixel = $im->getImagePixelColor(8, 2);
    $rgb = $pixel->getColor();
    $r += $rgb['r'];
    $g += $rgb['g'];
    $b += $rgb['b'];
	$pixel = $im->getImagePixelColor(2, 8);
    $rgb = $pixel->getColor();
    $r += $rgb['r'];
    $g += $rgb['g'];
    $b += $rgb['b'];
	$rt = $r/3;
	$gt = $g/3;
	$bt = $b/3;
  }
  else
  { $rt = ($trimcolor >> 16) & 0xFF;
    $gt = ($trimcolor >> 8) & 0xFF;
    $bt = $trimcolor & 0xFF;
  }
  
  // if tolerance ($t) isn't a number between 0 - 255 use 10 as default
  if (!is_numeric($trimtolerance) || $trimtolerance < 0 || $trimtolerance > 255) $trimtolerance = 50;

  $w = $im->getImageWidth(); // image width
  $h = $im->getImageHeight(); // image height

  // Calculate these once rather than for every iteration
  $x_axis=$y_axis=array();
  for($i=0;$i<$w; $i++) $x_axis[$i]=0;
  for($i=0;$i<$h; $i++) $y_axis[$i]=0;

  $iterator = $im->getPixelIterator();
  foreach( $iterator as $y => $row ) {
    foreach( $row as $x => $pixel ) {
      $rgb = $pixel->getColor();
      $r = $rgb['r'];
      $g = $rgb['g'];
      $b = $rgb['b'];
	  $diff = abs($rt-$r)+abs($gt-$g)+abs($bt-$b);
      if ($diff > $trimtolerance) 
	  { $y_axis[$y]++;
        $x_axis[$x]++;
      }
    }
    $iterator->syncIterator();
  }

  for($y=0; $y<$h-1; $y++)
  { if(($y_axis[$y] >= $trimcount) && ($y_axis[$y+1] >= $trimcount))
	  {$top = $y; break; }
  }
  for($y=$h-1; $y>0; $y--)
  { if(($y_axis[$y] >= $trimcount) && ($y_axis[$y-1] >= $trimcount))
	  {$bottom = $y; break; }
  }
  for($x=0; $x<$w-1; $x++)
  { if(($x_axis[$x] >= $trimcount) && ($x_axis[$x+1] >= $trimcount))
	  {$left = $x; break; }
  }
  for($x=$w-1; $x>0; $x--)
  { if(($x_axis[$x] >= $trimcount) && ($x_axis[$x-1] >= $trimcount))
	  {$right = $x; break; }
  }
  
    /* add a 2% margin */
    $wmargin = intval(2*($right-$left)/100);
    $hmargin = intval(2*($bottom-$top)/100);
    $top = $top-$hmargin;
    if($top <0) $top=0;
    $right = $right+$wmargin;
    if($right >= $w) $right = $w-1;
    $bottom = $bottom+$hmargin;
    if($bottom >= $h) $bottom = $h-1;
    $left = $left-$wmargin;
    if($left <0) $left=0;

  return array($top,$right,$bottom,$left);
}

$counter = 0; /* used to print id_image after every hundred without error */
function check_image($id_image)
{ global $triplepath, $allformats, $verbose, $counter;
  $dirflag = $baseflag = $missflag = $extraflag = false;
  $indexflag = true;
  $srcfile = "";
  /* check for interuption */
  $gquery = "SELECT value FROM ". _DB_PREFIX_."configuration";
  $gquery .= ' WHERE name="PRESTOOLS_IMREGEN_STOPFLAG" AND id_shop IS NULL and id_shop_group IS NULL';
  $gres = dbquery($gquery);
  if(mysqli_num_rows($gres)>0)
  { $grow = mysqli_fetch_array($gres);
    if($grow["value"]=="1")
    { $cquery="UPDATE ". _DB_PREFIX_."configuration";
      $cquery .= ' SET value=0,date_upd=NOW() WHERE id_shop IS NULL AND id_shop_group IS NULL AND name="PRESTOOLS_IMREGEN_STOPFLAG"';
      dbquery($cquery);
	  pecho("Regeneration interruped by user");
	  die("!!!");
	}
  }
  $srcfile = $id_image.".jpg";
  $dir = $triplepath.'img/p'.getpath($id_image).'/';
  if((!file_exists($dir)) || (!is_dir($dir)))
  { $dirflag = true;
  }
  else
  { if(!is_file($dir.$srcfile))
      $baseflag = true;
    
	$foundextensions = array();
	$files = scandir($dir);
    foreach ($files AS $file)
    { if(is_dir($dir.$file)) continue;
	  if(($file==".") || ($file=="..")) continue;
      if($file == $srcfile) continue;
      if($file == "index.php") 
	  { $indexflag = false;
 		continue;
	  }
	  $file = strtolower($file);
	  $pos = strpos($file,".");
	  if(!$pos) continue;
	  if(substr($file,$pos) != ".jpg") continue;
	  $trunk = substr($file, 0, $pos);
      $pos = strpos($trunk,"-");
      if(!$pos) continue; /* we don't bother about them here */
	  $suffix = substr($trunk,$pos+1);
	  $foundextensions[] = $suffix;
    }
	$tmp = array_diff($foundextensions, $allformats);
	if(sizeof($tmp) > 0)
	{ // print_r($tmp);
 	  $extraflag = true;
	}
	$tmp = array_diff($allformats, $foundextensions);
	if(sizeof($tmp) > 0)
	{ // print_r($tmp);
 	  $missflag = true;
	}
  }
  
  $msg = "";
  if($dirflag || $baseflag || $missflag || $extraflag || $indexflag)
  { $counter = 0;
    $msg = $id_image;
	if($dirflag) $msg .= "DIR";
	if($baseflag) $msg .= "b";
	if($missflag) $msg .= "m";
	if($extraflag) $msg .= "x";
	if($indexflag) $msg .= "i";
	$msg .= " ";
  }
  else if($counter > 100)
  { $counter = 0;
    $msg = $id_image." ";
  }
  pecho($msg);
}

