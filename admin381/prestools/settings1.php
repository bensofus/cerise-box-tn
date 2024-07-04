<?php

$username = 'hela.chadi@gmail.com'; /* change this from default "demo@demo.com" */
$password = '123456789'; /* change this from the default "opensecret" */
$ipadresses = array();
// Example: $ipadresses = array("111.22.33.44","11.33.55.*","::1"); 
//Note that "::1" is the IPv6 variation on "127.0.0.1" and used for localhost.
// array("*.*.*.*","*:*:*:*:*:*:*:*:*","::1") will give access to all ip addresses
$noboipcheck = true; /* no IP check when you login via the backoffice using the module */

$md5hashed = false; /* if you don't want your password in the source code you can encrypt it with the md5.php tool. In that case you should change $md5hashed from "false" to "true" */
$demo_mode = false;
$profiling = false; /* gives time that queries take when verbose is on */
define('_PRESTOOLS_PREFIX_', 'prestools_'); /* will be added to database tables created by Prestools - in addition to the Prestashop prefix */
/* when ipaddresses is empty everyone can access your script. You are advised to enter here your ip address(es) so that access becomes restricted to them. */
$usecookies = true;  /* Deprecated; sessions are no longer supported */

$updateallshops = 0; /* Default for multishop option to apply changes to all shops. 0=no; 1=group; 2=all shops */
/* $avoid_iframes opens rowsubmit commands in a new window and puts all arguments in the url. With it you can submit less rows at once */
/* DO NOT ENABLE $avoid_iframes UNLESS YOU FIND IT IMPOSSIBLE TO USE PRESTOOLS OTHERWISE AS IT WILL SERIOUSLY LIMIT THE NUMBER OF RECORDS YOU CAN SUBMIT AT ONCE. ALSO YOU WILL NO LONGER SEE THE COLOR CHANGE THAT CONFIRMS A CORRECT LINE SUBMIT. */
$avoid_iframes = false; /* Try setting true when anti-malware software obstructs your use of Prestools. */
$autosort = true; 	/* in product sort: if you type a position number and leave that field the row will immediately be moved when true */
					/* This is a visual thing only: when you submit the position numbers count. */

/* you can set here the default fields you will see for product_edit.php */
$default_product_fields = array("name","VAT","price", "priceVAT","quantity", "active","category", "ean", "description", "description_short", "image");
/* for other fields see the table below */
 
/* the following array determines which fields are shown in the search block of product-edit */
/* pack_stock_type was new in version 1.6.0.12. New fields in 1.7 are isbn, state, show_condition */
/* sosh_title and sosh_description (social_sharing) were present in the first 1.7 version but not later */
/* new in 1.7.3 were low_stock_threshold, additional_delivery_times, delivery_in_stock,delivery_out_stock, low_stock_alert */
/* new in 1.7.7 was mpn */
/* it is recommended that when you delete some of the fields you keep an outcommented copy of the original */
$productedit_fieldblock = Array(
	Array("name","VAT","priceVAT","reference","link_rewrite","description","description_short","meta_title","meta_keywords","meta_description"),
	Array("quantity","price","category","wholesaleprice","position","manufacturer","virtualp","availorder","on_sale","online_only"),
	Array("ean","image","date_upd","minimal_quantity","shipweight","shipheight","shipwidth","shipdepth","aShipCost","attachmnts"),
	Array("upc","active","date_add","visibility","condition","pack_stock_type","reserved","customizations","indexed","indexes"),
	Array("unit","ecotax","unitPrice","available_now","available_later","available_date","stockflags","warehousing","redirect","out_of_stock"),
    Array("isbn","state","show_condition","aDeliveryT", "deliInStock","deliOutStock","ls_threshold", "ls_alert","location","mpn"),
	Array("tags","shopz","carrier","discount","accessories","combinations","supplier","featureEdit","features","statistics")
	);
 
/* You can specify which fields should come first and in what order.
 * The remaining fields will be ordered alphabetically */
$fieldsorder = array("name","active","category", "price","VAT", "priceVAT","quantity", "description", "description_short", "image");

$productedit_numrecs = 100; /* number of records shown in product-edit by default */

$prestoolslanguage = "en"; /* use iso code here. Translations can be stored in a php file with this name like "fr.php" */

/* $catselectortype determines how the category selection in the search block looks like */
$catselectortype = 1; /* 1=alphabetic; 2=alphabetic with parent; 3=tree */

/* The following settings determines where the scripts looks for images. You should only change this when you have no pictures */
$img_extensions = array('-small_default.jpg','-small.jpg','-small_dm.jpg');
$prod_img_width = "45px";
$prod_img_height = "auto"; /* change this is you want other picture sizes. Set both to zero if you want the original size */

/* $lean_tabindex is interesting when you navigate your page with the tab-key. When true it skips the links */
$lean_tabindex = true; 

/* Allow repair or deletion on the "integrity checks" page? */
/* be careful with enabling delete because you can also delete sound products */
$integrity_repair_allowed = true;
$integrity_delete_allowed = true;

$allow_server_shoplist = false; /* gives an overview of all shops on a server */
$allow_filelist_export = false; /* allow export of list of all files and directories in your shop */

/* the following settings are for the uploading of product images */
$maxprodimgsize = 4000000;  /* bigger images will be refused */
$skip_initial_jpg_process = 1; /* With skipping the uploaded jpg images are just copied and don't undergo initial compression. Setting to 0 often makes the base images smaller. But the final images will get the same size and quality will be a tiny bit less */

$settings_version = 2;