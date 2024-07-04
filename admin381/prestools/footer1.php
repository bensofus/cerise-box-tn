<?php

echo '
<hr style="border: 1px dotted #CCCCCC;" />';
echo '<center><a href="#top">Top</a></center>';
if(defined('_TB_VERSION_'))
  echo '<center>Thirty Bees version: '._TB_VERSION_;
else
  echo '<center>Prestashop version: '._PS_VERSION_;
echo '. Prestools version 1.31j. Release date: 14-feb-2023. ';
echo "PHP version ".phpversion()." and MySQL version ";
$result = dbquery("SELECT version() AS version");
$query_data = mysqli_fetch_array($result);
echo $query_data["version"]." ";
echo 'under '.php_uname();
echo " IP: ".$_SERVER["SERVER_ADDR"]." ";
echo "Time: ".(time() - $prestools_starttime)."s";
echo '</center>';

if(!isset($prestools_notbought)) check_notbought(array());
if(sizeof($prestools_notbought)==0)
{ echo '<center>Prestools full version</center>';
  echo '<center>For support you can go to the <a href="https://www.prestashop.com/forums/topic/1064774-free-script-prestools-mass-edit-and-other-maintenance-tools/">thread on the Prestashop forum</a> or use the contact form on the Prestools website.</center>';
}
else 
{ echo '<center><span style="background-color:yellow;">In this installation the following fields are shown in demo mode: '.implode(",",$prestools_notbought).
	'</span>. You can buy plugin(s) to modify them at <a href="http://www.prestools.com/prestools-suite-plugins">Prestools.com</a>.</center>';
  echo '<center>For support go to the <a href="https://www.prestashop.com/forums/topic/1064774-free-script-prestools-mass-edit-and-other-maintenance-tools/">thread on the Prestashop forum</a>'.
       ' or <a href="https://forum.thirtybees.com/topic/925-prestools-the-free-mass-edit-toolset/">the thread on the Thirty Bees forum</a></center>';
  echo '<center>For purchased plugins you can also get support at the Prestools website.</center>';
}
if(sizeof($prestools_missing)!=0)
{ echo '<script>var notpaid_fld = document.getElementById("notpaid");
	if(notpaid_fld)
	{ notpaid_fld.innerHTML = \'Some fields ('.implode(",",$prestools_missing).') are in demo mode. You can buy plugin(s) to use them at <a href="http://www.prestools.com/prestools-suite-plugins">Prestools.com</a>.\';
      notpaid_fld.style.backgroundColor = "#FFE0A8";
	}
	</script>';
}
if($initwarnings != "")
	echo "<script>alert('".$initwarnings."');</script>";
// dbquery("FLUSH TABLES");
 mysqli_close($conn);



