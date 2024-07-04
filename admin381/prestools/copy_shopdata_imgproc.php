<?php
/* This file copies the image directory from the old shop to the new shop. 
 * It is to be used when both shops are on the same server.
 * It goes through both trees directory by directory. In each directory it compares the files.
 * If files have the same name and length it skips them. Otherwise they are copied from the old to the new shop
 * This function copies also the upload and download directories
 */
  if(!@include 'approve.php') die( "approve.php was not found! Please use this script together with Prestools that can be downloaded for free in the Free Modules & Themes section of the Prestashop forum");
  if(!include 'copy_shopdata_functions.inc.php') die( "copy_shopdata_functions.inc.php was not found!");
  if(!include 'copy_shopdata_config.php') die( "copy_shopdata_config.php was not found!");
  
  if(isset($_POST["task"]))
    $input = $_POST;
  else if(isset($_GET["task"]))
    $input = $_GET;
  else
    exit(0);

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
    colordie("Both shops must use the same domain");

echo '<!DOCTYPE html> 
<html lang="en"><head><meta charset="utf-8">
<script>
function newwin()
{ nwin = window.open("","_blank", "scrollbars,menubar,toolbar, status,resizable,location");
  content = document.body.innerHTML;
  if(nwin != null)
  { nwin.document.write("<html><head><meta http-equiv=\'Content-Type\' content=\'text/html; charset=utf-8\' /></head><body>"+content+"</body></html>");
    nwin.document.close();
  }
}
</script></head><body>';
echo '<a href="#" title="Show the content of this frame in a New Window" onclick="newwin(); return false;">NW</a> ';

  $oldpath = $oldshops[$set[0]];
  $newpath = $newshops[$set[0]];

/* now we translate the paths from ps_shop_url into paths on the harddisk */
  $olddir = $newdir = $triplepath;
  $cnt = substr_count($newpath, '/');
  for($i=1; $i<$cnt; $i++)
    $olddir = "../".$olddir;
  $olddir = $olddir.ltrim($oldpath, '/');
  if($verbose)
    echo "Olddir: ".$olddir." newdir: ".$newdir."<br>";
  if($olddir == $newdir) colordie("<p>Old and new image directory are the same!");

if($input["task"] == "products")
{ $batchsize = intval($input["batchsize"]);
  $startimg = intval($input["startimg"]);  
  $action = preg_replace('/[^a-z]+/','', $input["action"]);

  $ps_hight_dpi = get_configuration_value('PS_HIGHT_DPI'); 
  $query = "select name FROM ". _DB_PREFIX_."image_type WHERE products = 1";
  $res=dbquery($query);
  $imagetypes = [];
  while($row = mysqli_fetch_array($res))
  {	$suffix = substr($row["name"],-2); /* suffix can be _2x and 2x */
    if(($suffix=="2x") && !$ps_hight_dpi) continue;
	$imagetypes[] = $row["name"];
  }

  $imgbase = $triplepath.'img/p';

  if($action == "check")
  { $squery="select id_image, id_product from ". _DB_PREFIX_."image WHERE ";
    $squery .= " id_image >= ".$startimg;
    if(trim($input["range"]) != "");
      $squery .= " AND (".rangetosql($input["range"],"id_image").")";
    $squery .= " ORDER BY id_image LIMIT ".$batchsize;
    $sres=dbquery($squery);
    $images = "";
    while($srow = mysqli_fetch_array($sres))
    { $id_image = $srow["id_image"];
      $base = $imgbase.getpath($id_image).'/'.$id_image;
      if(!file_exists($base.".jpg"))
         pecho('Image '.$id_image.'(p'.$srow["id_product"].') not Found. ');
      else 
	  { list($width, $height, $imgtype, $attr) = @getimagesize( $base.".jpg" );
	    if($height == 0) 
		  pecho('Image '.$id_image.'(p'.$srow["id_product"].') is not good.');
	    if(isset($input['doderived']))
        { foreach($imagetypes AS $type)
		  { if(!file_exists($base."-".$type.".jpg"))
			  pecho('Image '.$id_image.'-'.$type.' not Found. ');
		  }
	    }
	  }
	  $images .= $id_image." ";
    }
    if(mysqli_num_rows($sres) < $batchsize)
	  $images.="end";
    pecho("end check".trim($images));
  }
  else if($action == "copy")
  { $oldimgbase = $olddir.'img/p';
    $squery="select id_image, id_product from ". _DB_PREFIX_."image WHERE ";
    $squery .= " id_image >= ".$startimg;
    if(trim($input["range"]) != "");
      $squery .= " AND (".rangetosql($input["range"],"id_image").")";
    $squery .= " ORDER BY id_image LIMIT ".$batchsize;
    $sres=dbquery($squery);
    $images = "";
    while($srow = mysqli_fetch_array($sres))
    { $id_image = $srow["id_image"];
      $base = $imgbase.getpath($id_image).'/'.$id_image;
	  $oldbase = $oldimgbase.getpath($id_image).'/'.$id_image;
	  if(isset($input['replacexist']) || (!file_exists($base.".jpg")))
      { if(!file_exists($oldbase.".jpg"))
	    { pecho("Image ".$id_image."(p".$srow["id_product"].") not found");
	      continue;
	    }
	    check_imgdirs($id_image);
	    if(!copy($oldbase.".jpg", $base.".jpg"))
	      pecho("Image ".$id_image." copy failed");
	  }

	  if(isset($input['doderived'])) /* do derived: like 123-large.jpg */
      { foreach($imagetypes AS $type)
	    { if(isset($input['replacexist']) || (!file_exists($base."-".$type.".jpg")))
	      { if(!file_exists($oldbase."-".$type.".jpg"))
		    { pecho('Image '.$id_image.'-'.$type.' not Found. ');
			  pecho('<br>-----'.$oldbase."-".$type.".jpg====<br>");
		      continue;
		    }
	        if(!copy($oldbase."-".$type.".jpg", $base."-".$type.".jpg"))
			  pecho("Image ".$id_image."-".$type." copy failed");
		  }
	    }
	  }
	  $images .= $id_image." ";
    }
    if(mysqli_num_rows($sres) < $batchsize)
	  $images.="end";
    pecho("end copy ".trim($images));
  }
  else if($action == "httpcopy")
  { $webshopurl = preg_replace('/[^a-zA_Z0-9\/\.:]+/','',$input['webshopurl']);
    $webshopurl = preg_replace('/\/$/','',$input['webshopurl']);
    if($webshopurl == "") colordie("You did not provide a valid url!");
    if(substr($webshopurl,0,4) != "http") colordie("You did not Provide a Valid Url!");
    $headers = get_headers($webshopurl);
    echo $webshopurl."<br>";
    if(!($headers && strpos( $headers[0], '200')))
	  colordie("URL Doesn't Exist");
    $oldimgbase = $olddir.'img/p';
    $squery="select id_image, id_product from ". _DB_PREFIX_."image WHERE ";
    $squery .= " id_image >= ".$startimg;
    if(trim($input["range"]) != "");
       $squery .= " AND (".rangetosql($input["range"],"id_image").")";
    $squery .= " ORDER BY id_image LIMIT ".$batchsize;
    $sres=dbquery($squery);
    $images = "";
    while($srow = mysqli_fetch_array($sres))
    { $id_image = $srow["id_image"];
	  check_imgdirs($id_image);
      $img = $imgbase.getpath($id_image).'/'.$id_image.".jpg";
	  $oldimg = $webshopurl.'/img/p'.getpath($id_image).'/'.$id_image.".jpg";
	  if(isset($input['replacexist']) || (!file_exists($img)))
      { $headers = get_headers($oldimg);
	    if($headers && strpos( $headers[0], '200'))
	    { echo "<br>Copying ".$oldimg." to ".$img;
		  $res = file_put_contents($img, file_get_contents($oldimg));
		  if(!$res) 
			echo " failed";
		  else 
			$images[] = $id_image;
	    } 
	  }
    }
    if(mysqli_num_rows($sres) < $batchsize)
	  $images.="end";
    pecho("end copy ".trim($images));
  }
}
else if($input["task"] == "other")
{ 
  if(!isset($input['imgdirs']) && !isset($input['downloaddir']) && !isset($input['uploaddir']))
	colordie("Nothing to copy");
  if(isset($input['imgdirs']))
    $imgdirs = $input['imgdirs'];
  else
	$imgdirs = [];
  $oldbase = $olddir."img/";
  $newbase = $newdir."img/";
  foreach($imgdirs AS $dir)
  { if($dir == "")
      copy_directory($oldbase, $newbase, 0);
    else 
      copy_directory($oldbase.$dir, $newbase.$dir, 1);
  }
  if(isset($input['downloaddir']))
    copy_directory($olddir."download", $newdir."download", 0);
  if(isset($input['uploaddir']))
    copy_directory($olddir."upload", $newdir."upload", 0);
  pecho('Copying other images was completed!');
}

/* copy_directory is a recursive function */
function copy_directory($oldpath, $newpath, $recursive)
{ global $oldbase, $input;
 //$input["replacexist"];
  if(!file_exists($newpath))
	mkdir($newpath);
  $oldfiles = array();
  $files = scandir($oldpath);
  echo "DIR ".$oldpath." ".sizeof($files)."<br>";
  foreach($files as $t) 
  { if (($t==".") || ($t=="..")) continue;
    $currentFile = $oldpath.'/'.$t;
	if (is_dir($currentFile)) 
    { if(!$recursive) continue; /* when copying imgroot don't copy subdirs */
	  copy_directory($oldpath.'/'.$t, $newpath.'/'.$t, 1);
	}
	else
	{ if(isset($input["replacefiles"]) || (!file_exists($newpath.'/'.$t)))
		if(!copy($oldpath.'/'.$t, $newpath.'/'.$t))
		  colordie("Failing to copy ".$oldpath.'/'.$t);
	  echo " copy-".$t." ";
	}
  }
}

function pecho($txt)
{ echo $txt;
  echo '<script>parent.dynamo2("'.$txt.'"); </script>';
}

/* check that the image directory exists; if not, create it */
function check_imgdirs($id_image)
{ global $triplepath;
  $test = $triplepath.'img/p';
  for ($i=0; $i<strlen($id_image); $i++)
  { $test .= "/".substr($id_image,$i,1);
    if(!file_exists($test))
	{ if(!mkdir($test))
	  { pecho("Directory creation failed for ".$id_image.". Directory ".$test." could not be created");
		return;
	  }
    }
  }
}
