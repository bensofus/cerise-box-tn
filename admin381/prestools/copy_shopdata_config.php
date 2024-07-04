<?php
/* This product is not easy to use (and impossible without reading the manual) */
/* Also it may take considerable time to get your shop once again from default in the desirable state */
/* However, it is very good at saving shops that no longer work or won't upgrade */

  define('_OLD_SERVER_', 'cresussfftbox20.mysql.db');
  define('_OLD_NAME_', 'cresussfftbox20');
  define('_OLD_USER_', 'cresussfftbox20');
  define('_OLD_PASSWD_', 'D9tE2Q6Y6zjW8jQpu3T62wUdr5');
  define('_OLD_PREFIX_', 'ps_');
  
  $verbose = "false";
  set_time_limit(3600); /* Set a long but not endless time limit: 3600 seconds = 1 hour */
  $do_initialization = 1; /* 1=initialize once; 0=skip initialisation; 2=initialize always */
  $startnum = 1; /* use this to skip tables. When you start it should be 1. */
  $numtables = 9999; /* number of tables to process. Leave at a high number for all remaining tables;  */
  
  /* in the following arrays, fill in the table names within quotes. Use 'all' for all. */
  $copy_extra_old_fielders = array();  /* when extra fields are found in old shop, create them in the new one */
  $field_length_fixers = array(); /* when a field was larger in the old shop copy its length to the new shop. This does not affect formats without an explicit length like TEXT. */
  $index_fixers = array(); /* copy indexation from old to new shop for these tables. Be careful: this can cause duplicate key errors in which case the table will end up with no indexes. */

  $copy_mode = "all";   /* options: "all", "none", "diff" */
	/* "none" is for when you only want to change field definitions and/or indexes.
	With "all" the tables will be truncated and filled from the old shop. With "none" only field corrections will be done. "diff" is for the situation where the new shop has extra fields. It adds new records and deletes the ones that were deleted in the old shop. The records that are in both shops are updated on the common fields. */
  $duplicate_key_removers = array(); /* remove the extra entries that cause duplicate key errors on unique indexes in these tables */

  $skip_log_and_stats_tables = true; /* skip statistics and log tables */
  $skip_search_tables = false; /* skip keyword tables: you can regenerate them in the backoffice at Settings->Search  */
  $copy_data_via_file = 0; /* try this when direct copying doesn't work */
  
/* and now the most important part: the copied tables */
/* the copied_tables mentions all tables that should be copied. If you find problems you could decide to delete some of them.  */
/* The $init_tables are only copied the first time as you are expected to install carriers and payment providers on the new shop and those might change them. Note that the range tables are also used for other purposes. */
$init_tables = array("carrier","carrier_group","carrier_lang","carrier_shop","carrier_tax_rules_group_shop","carrier_zone","delivery","range_price","range_weight","order_state","order_state_lang"); 
$copied_tables = array("accessory","address","address_format","advice","advice_lang","alias","attachment"
,"attachment_lang","attribute","attribute_group","attribute_group_lang","attribute_group_shop"
,"attribute_impact","attribute_lang","attribute_shop","badge","badge_lang"
,"cart","cart_cart_rule","cart_product","cart_rule","cart_rule_carrier"
,"cart_rule_combination","cart_rule_country","cart_rule_group","cart_rule_lang"
,"cart_rule_product_rule","cart_rule_product_rule_group","cart_rule_product_rule_value"
,"cart_rule_shop","category","category_group","category_lang","category_product","category_shop"
,"cms","cms_block","cms_block_lang","cms_block_page","cms_block_shop","cms_category"
,"cms_category_lang","cms_category_shop","cms_lang","cms_role","cms_role_lang","cms_shop"
,"compare","compare_product","condition","condition_advice","condition_badge"
,"contact","contact_lang","contact_shop"
,"country","country_lang","country_shop","cronjobs","currency","currency_lang","currency_shop"
,"customer","customer_group","customer_message","customer_message_sync_imap","customer_thread"
,"customization","customization_field","customization_field_lang","customized_data"
,"date_range","employee","employee_notification","employee_shop","emailsubscription","feature"
,"feature_lang","feature_product","feature_shop","feature_value","feature_value_lang"
,"gender","gender_lang","group","group_lang","group_reduction","group_shop"
,"image","image_lang","image_shop","import_match","info","info_lang","info_shop"
,"layered_category","layered_filter","layered_filter_block","layered_filter_shop","layered_friendly_url"
,"layered_indexable_attribute_group","layered_indexable_attribute_group_lang_value"
,"layered_indexable_attribute_lang_value","layered_indexable_feature"
,"layered_indexable_feature_lang_value","layered_indexable_feature_value_lang_value"
,"layered_price_index","layered_product_attribute"
,"link_block","link_block_lang","link_block_shop"
,"mail","manufacturer","manufacturer_lang","manufacturer_shop"
,"message","message_readed","newsletter","operating_system","orders","order_carrier","order_cart_rule"
,"order_detail","order_detail_tax","order_history","order_invoice","order_invoice_payment"
,"order_invoice_tax","order_message","order_message_lang","order_payment","order_return"
,"order_return_detail","order_return_state","order_return_state_lang","order_slip","order_slip_detail"
,"order_slip_detail_tax","pack","page","page_type"
,"product","product_attachment","product_attribute","product_attribute_combination"
,"product_attribute_image","product_attribute_shop","product_carrier"
,"product_comment","product_comment_criterion","product_comment_criterion_category"
,"product_comment_criterion_lang","product_comment_criterion_product","product_comment_grade"
,"product_comment_report","product_comment_usefulness"
,"product_country_tax","product_download","product_group_reduction_cache","product_lang","product_sale"
,"product_shop","product_supplier","product_tag"
,"profile","profile_lang","quick_access","quick_access_lang","request_sql"
,"required_field","risk","risk_lang","scene","scene_category","scene_lang"
,"scene_products","scene_shop"
,"specific_price","specific_price_priority","specific_price_rule","specific_price_rule_condition"
,"specific_price_rule_condition_group","state","statssearch"
,"stock","stock_available","stock_mvt","stock_mvt_reason","stock_mvt_reason_lang"
,"store","store_lang","store_shop"
,"supplier","supplier_lang","supplier_shop","supply_order","supply_order_detail"
,"supply_order_history","supply_order_receipt_history","supply_order_state","supply_order_state_lang"
,"tag","tag_count","tax","tax_lang","tax_rule","tax_rules_group","tax_rules_group_shop","translation"
,"warehouse","warehouse_carrier","warehouse_product_location","warehouse_shop"
,"webservice_account","webservice_account_shop","webservice_permission","zone","zone_shop");

/* The next lines adds optional tables. */
/* They are not necessary for the running of the new shop */
$log_and_stats_tables = array("connections","connections_page",
"connections_source","guest","log","pagenotfound","page_viewed",
"referrer","referrer_cache","referrer_shop","sekeyword");
if(!$skip_log_and_stats_tables)
  $copied_tables = array_merge($copied_tables, $log_and_stats_tables);
$search_tables = array("search_index","search_word");
if(!$skip_search_tables)
  $copied_tables = array_merge($copied_tables, $search_tables);
if(($do_initialization == "2") || (($do_initialization == "1") && ((!isset($qrow)) || (!isset($qrow["value"])) || ($qrow["value"] != "1"))))
  $copied_tables = array_merge($copied_tables, $init_tables);
  
/* Here you can mention tables specific for your installation. By listing them you will not forget them.
   And by keeping them apart you make it easier to update this software. */
$module_tables = array("awp_attribute_wizard_pro","gsitemap_sitemap","mailalert_customer_oos","myparcel","paypal_customer"
,"paypal_login_user","paypal_order"
,"psgdpr","psgdpr_consent", "psgdpr_consent_lang", "psgdpr_log", "psreassurance","psreassurance_lang","product_comment"
,"product_comment_criterion","product_comment_criterion_category","product_comment_criterion_lang"
,"product_comment_criterion_product","product_comment_grade","product_comment_report"
,"product_comment_usefulness","sisow","yotposnippetcache","wishlist","wishlist_email"
,"wishlist_product","wishlist_product_cart");

/* the tables below are not copied */
$rights_tables = array("access","admin_filter","authorization_role","module_access");
$system_tables = array("configuration","configuration_kpi","configuration_kpi_lang",
"configuration_lang","customer_session", "employee_session",
"homeslider","homeslider_slides","homeslider_slides_lang","hook","hook_alias",
"hook_module","hook_module_exceptions","image_type", 
"lang","lang_shop","linksmenutop","linksmenutop_lang","memcached_servers","meta","meta_lang","module",
"module_access", "module_carrier",
"module_country","module_currency","module_group","module_history", "module_preference","module_shop",
"modules_perfs","search_engine","smarty_cache","smarty_last_flush","smarty_lazy_cache",
"supplier_lang","tab","tab_advice","tab_lang",
"tab_module_preference","theme","theme_meta","theme_specific",
"themeconfigurator","timezone","web_browser"); /* these are the tables that have been left out and are not copied */

/* the following modules are modules that are standard enabled in a 1.6 and 1.7 shop */
/* they will during initialization been checked and disabled if not present in old shop */

  $conf_modules16 = array("blockbanner","bankwire","blockbestsellers","blockcart","blocksocial",
"blockcategories","blockcurrencies","blockfacebook","blocklanguages","blocklayered","blockcms",
"blockcmsinfo","blockcontact","blockcontactinfos","blockmanufacturer","blockmyaccount",
"blockmyaccountfooter","blocknewproducts","blocknewsletter","blockpaymentlogo","blocksearch",
"blockspecials","blockstore","blocksupplier","blocktags","blocktopmenu","blockuserinfo","blockviewed",
"cheque","cronjobs","dashactivity","dashtrends","dashgoals","dashproducts","gamification",
"graphnvd3","gridhtml","homeslider","homefeatured","pagesnotfound","productpaymentlogos",
"sekeywords","socialsharing","statsbestcategories","statsbestcustomers","statsbestproducts",
"statsbestsuppliers","statsbestvouchers","statscarrier",
"statscatalog","statscheckup","statsdata","statsequipment","statsforecast","statslive",
"statsnewsletter","statsorigin","statspersonalinfos","statsproduct","statsregistrations",
"statssales","statssearch","statsstock","statsvisits","themeconfigurator");

  $conf_modules17 = array("blockreassurance","contactform","dashactivity","dashgoals","dashproducts",
"dashtrends","emarketing","gamification","graphnvd3","gridhtml","gsitemap","pagesnotfound",
"productcomments","banner","buybuttonlite","categorytree","checkpayment","contactinfo",
"crossselling","currencyselector","customeraccountlinks","customersignin","customtext",
"dataprivacy","emailsubscription","facetedsearch","faviconnotificationbo",
"featuredproducts","imageslider","languageselector","linklist","mainmenu","mbo",
"searchbar","sharebuttons","shoppingcart","socialfollow","themecusto","wirepayment",
"psaddonsconnect","psgdpr","sekeywords","statsbestcategories","statsbestcustomers","statsbestproducts",
"statsbestsuppliers","statsbestvouchers","statscarrier","statscatalog","statscheckup","statsdata",
"statsequipment","statsforecast","statslive","statsnewsletter","statsorigin","statspersonalinfos",
"statsproduct","statsregistrations","statssales","statssearch","statsstock","statsvisits","welcome");

/* INITIALISATION SETTINGS */

/* updating important values in the configuration table */
/* conf_values will be updated when present; Otherwise they will be inserted */
/* Feel free to remove some of the values if you suspect that they might cause trouble */
/* if you add values you should also add a definition in the $conf_validation table below */
  $conf_values = array("BANK_WIRE_DETAILS","BANK_WIRE_OWNER","BANK_WIRE_ADDRESS",
  "BLOCKCONTACT_EMAIL", "BLOCKCONTACTINFOS_ADDRESS","BLOCKCONTACTINFOS_COMPANY",
  "BLOCKCONTACTINFOS_EMAIL", "BLOCKCONTACTINFOS_PHONE", "blockfacebook_url", "FOOTER_BEST-SALES",
  "FOOTER_BLOCK_ACTIVATION", "FOOTER_CMS", "FOOTER_NEW-PRODUCTS" ,"FOOTER_POWEREDBY","FOOTER_PRICE-DROP",
  "HOME_FEATURED_CAT", "PS_ADVANCED_STOCK_MANAGEMENT", "PS_ALLOW_ACCENTED_CHARS_URL", "PS_ALLOW_HTML_IFRAME",
  "PS_API_KEY", "PS_ATTRIBUTE_ANCHOR_SEPARATOR",  "PS_ATTRIBUTE_CATEGORY_DISPLAY","PS_B2B_ENABLE",
  "PS_CARRIER_DEFAULT", "PS_CART_REDIRECT","PS_COMPARATOR_MAX_ITEM", 
  "PS_CONDITIONS", "PS_CONDITIONS_CMS_ID", "PS_COUNTRY_DEFAULT", "PS_CURRENCY_DEFAULT",
  "PS_CUSTOMER_CREATION_EMAIL","PS_CUSTOMER_NWSL","PS_CUSTOMER_OPTIN","PS_CUSTOMER_SERVICE_SIGNATURE",
  "PS_DASHBOARD_SIMULATION", "PS_DEFAULT_WAREHOUSE_NEW_PRODUCT", 
  "PS_DETECT_COUNTRY", "PS_CUSTOMER_GROUP", "PS_DETECT_LANG", "PS_DISALLOW_HISTORY_REORDERING",  
  "PS_DISP_UNAVAILABLE_ATTR", "PS_DISPLAY_BEST_SELLERS", "PS_DISPLAY_JQZOOM", "PS_DISPLAY_QTIES","PS_DISPLAY_SUPPLIERS",
  "PS_FORCE_ASM_NEW_PRODUCT",
  "PS_GIFT_WRAPPING","PS_GIFT_WRAPPING_PRICE", "PS_GIFT_WRAPPING_TAX_RULES_GROUP", 
  "PS_GUEST_CHECKOUT_ENABLED", "PS_GUEST_GROUP", "PS_HOME_CATEGORY",
  "PS_LAST_QTIES", "PS_LOCALE_COUNTRY", "PS_LOCALE_LANGUAGE","PS_MAINTENANCE_TEXT",
  "PS_NB_DAYS_NEW_PRODUCT", "PS_ONE_PHONE_AT_LEAST","PS_LABEL_OOS_PRODUCTS_BOD",
  "PS_ORDER_OUT_OF_STOCK",
  "PS_ORDER_PROCESS_TYPE","PS_PACK_STOCK_TYPE", "PS_PRICE_ROUND_MODE", "PS_PRODUCTS_ORDER_BY", "PS_PRODUCTS_ORDER_WAY",
  "PS_PRODUCTS_PER_PAGE", "PS_PURCHASE_MINIMUM", "PS_RECYCLABLE_PACK","PS_REGISTRATION_PROCESS_TYPE",
  "PS_ROOT_CATEGORY","PS_ROUND_TYPE","PS_SEARCH_BLACKLIST","PS_SHIP_WHEN_AVAILABLE","PS_SHOP_ACTIVITY",
  "PS_SHOP_NAME","PS_SHOP_STATE","PS_SHOP_STATE_ID","PS_SMARTY_FORCE_COMPILE","PS_STOCK_MANAGEMENT",
  "PS_STOCK_CUSTOMER_ORDER_REASON","PS_STOCK_MANAGEMENT","PS_STOCK_MVT_DEC_REASON_DEFAULT",
  "PS_STOCK_MVT_INC_REASON_DEFAULT","PS_STOCK_MVT_REASON_DEFAULT","PS_STOCK_MVT_SUPPLY_ORDER",
  "PS_STOCK_MVT_TRANSFER_FROM","PS_STOCK_MVT_TRANSFER_TO","PS_STORES_DISPLAY_FOOTER",
  "PS_UNIDENTIFIED_GROUP"  );
  
  /* in 1.7.6 a precision field was added to the currency table. In 1.7.7 "PS_PRICE_DISPLAY_PRECISION" was deleted */
  if(version_compare(_PS_VERSION_ , "1.7.7", "<")) $conf_values[] = "PS_PRICE_DISPLAY_PRECISION";
  
/* In study: PS_DETECT_COUNTRY
*/
   
/* conf_update_values will be updated when present; Otherwise they will be ignored */  
  $conf_update_values = array("BLOCKSOCIAL_FACEBOOK","BLOCKSOCIAL_TWITTER","BLOCKSOCIAL_RSS");

/* Note: "PS_UNIDENTIFIED_GROUP","PS_GUEST_GROUP","PS_CUSTOMER_GROUP" are group id's for the customer groups. Some shops (probably very old) have different values for them. */
  
/* enter here othere entries that you want to copy - for example for modules */
  $conf_notvalidated = array();
  
  /* The following are validation strings. It is used in the function update_config_value() like:
   *  if(preg_match("/^".$conf_validation[$key]."$/", $myString)) {copy value to new table}
   *  Explanation unicode Regexp expressions:
   *     \p{Xan}: all that is a number or a letter in any alphabet of the unicode table.
   *     \p{L}: matches a single code point in the category "letter".
   */
  $conf_validation = array(
     "BANK_WIRE_DETAILS" => "[\p{Xan}\s:\@\.\-]+",  /* consider also \p{L} with /i */
	 "BANK_WIRE_OWNER" => "[\p{Xan}\s\-_\'\"\.\(\)]+",
	 "BANK_WIRE_ADDRESS" => "[\p{Xan}\s\-_\'\"\.\(\)\:;,\/]+",
	 "BLOCKCONTACT_EMAIL" => "[a-zA-Z0-9\-_@\.]+",
	 "BLOCKCONTACTINFOS_ADDRESS" => "[\p{Xan}:\s_\-\(\)\.]+",
	 "BLOCKCONTACTINFOS_COMPANY" => "[\p{Xan}:\s_\-\(\)\.\']+",
	 "BLOCKCONTACTINFOS_EMAIL" => "[a-zA-Z0-9\-_@\.]+",
	 "BLOCKCONTACTINFOS_PHONE" => "[\p{Xan}\-\s\+\(\)\:\/\.]+",
	 "blockfacebook_url" => "[a-zA-Z0-9\-_:\.\/\\\]+",
	 "BLOCKSOCIAL_FACEBOOK" => "[a-zA-Z0-9\-_:\#\.\/\\\]+",
	 "BLOCKSOCIAL_TWITTER" => "[a-zA-Z0-9\-_:\.\#\/\\\]+",
	 "BLOCKSOCIAL_RSS" => "[a-zA-Z0-9\-_:\.\/\\\]+",
	 "FOOTER_BEST-SALES" => "[01]",
	 "FOOTER_BLOCK_ACTIVATION" => "[0-9_\|]+",
	 "FOOTER_CMS" => "[0-9_\|]+",
	 "FOOTER_NEW-PRODUCTS" => "[01]",
     "FOOTER_POWEREDBY" => "[01]",
	 "FOOTER_PRICE-DROP" => "[01]",
	 "HOME_FEATURED_CAT" => "[0-9]+",
	 "PS_ADVANCED_STOCK_MANAGEMENT" => "[01]",
	 "PS_ALLOW_ACCENTED_CHARS_URL" => "[01]",
	 "PS_ALLOW_HTML_IFRAME" => "[01]",
	 "PS_API_KEY" => "[a-zA-Z0-9]+", /* this is the Google maps key */
	 "PS_ATTRIBUTE_ANCHOR_SEPARATOR" => "[-]",
	 "PS_ATTRIBUTE_CATEGORY_DISPLAY" => "[01]",
	 "PS_B2B_ENABLE" => "[01]",
	 "PS_CARRIER_DEFAULT" => "[0-9]+",
	 "PS_CART_REDIRECT" => "[01]",
	 "PS_COMPARATOR_MAX_ITEM" => "[0-9]+",
	 "PS_CONDITIONS" => "[01]",
	 "PS_CONDITIONS_CMS_ID" => "[0-9]+",
	 "PS_COUNTRY_DEFAULT" => "[0-9]+",
	 "PS_CURRENCY_DEFAULT" => "[0-9]+",
	 "PS_CUSTOMER_CREATION_EMAIL" => "[01]",
	 "PS_CUSTOMER_GROUP" => "[0-9]+",
	 "PS_CUSTOMER_NWSL" => "[01]",
	 "PS_CUSTOMER_OPTIN" => "[01]",
	 "PS_CUSTOMER_SERVICE_SIGNATURE" => "[\p{Xan}:\s_\-\(\)\.\']+",
	 "PS_DASHBOARD_SIMULATION" => "[01]",
	 "PS_DEFAULT_WAREHOUSE_NEW_PRODUCT" => "[01]",
	 "PS_DETECT_COUNTRY" => "[01]",
	 "PS_DETECT_LANG" => "[01]",
	 "PS_DISALLOW_HISTORY_REORDERING" => "[01]",
	 "PS_DISP_UNAVAILABLE_ATTR" => "[01]",
	 "PS_DISPLAY_BEST_SELLERS" => "[01]",
	 "PS_DISPLAY_JQZOOM" => "[01]",
	 "PS_DISPLAY_QTIES" => "[01]",
	 "PS_DISPLAY_SUPPLIERS" => "[01]",
	 "PS_FORCE_ASM_NEW_PRODUCT" => "[01]",
	 "PS_GIFT_WRAPPING" => "[01]",
	 "PS_GIFT_WRAPPING_PRICE" => "[01]",
	 "PS_GIFT_WRAPPING_TAX_RULES_GROUP" => "[01]",
	 "PS_GUEST_CHECKOUT_ENABLED" => "[01]",
	 "PS_GUEST_GROUP" => "[0-9]+",
	 "PS_HOME_CATEGORY" => "[0-9]+",
	 "PS_LABEL_OOS_PRODUCTS_BOD" => "[\p{Xan}:\s_\-\(\)\.\']+",
	 "PS_LAST_QTIES" => "[0-9]+",
	 "PS_LOCALE_COUNTRY" => "[a-z][a-z]",
	 "PS_LOCALE_LANGUAGE" => "[a-z][a-z]",
	 "PS_MAINTENANCE_TEXT" => "[\p{Xan}:\s_\-\(\)\.\']+",	
	 "PS_NB_DAYS_NEW_PRODUCT" => "[0-9]+",
	 "PS_ONE_PHONE_AT_LEAST" => "[01]",
	 "PS_ORDER_OUT_OF_STOCK" => "[01]",
	 "PS_ORDER_PROCESS_TYPE" => "[01]",
	 "PS_PACK_STOCK_TYPE" => "[0123]+",
	 "PS_PRICE_ROUND_MODE" => "[0-9]",
	 "PS_PRODUCTS_ORDER_BY" => "[0-7]+",
	 "PS_PRODUCTS_ORDER_WAY" => "[01]",
	 "PS_PRODUCTS_PER_PAGE" => "[0-9]+",
	 "PS_PURCHASE_MINIMUM" => "[0-9\.]+",
	 "PS_RECYCLABLE_PACK" => "[01]",
	 "PS_REGISTRATION_PROCESS_TYPE" => "[01]",
	 "PS_ROOT_CATEGORY" => "[0-9]+",
	 "PS_ROUND_TYPE" => "[0-9]",
	 "PS_SEARCH_BLACKLIST" => "[\p{Xan}:\s_\-\(\)\.\']+",
	 "PS_SHIP_WHEN_AVAILABLE" => "[01]",
	 "PS_SHOP_ACTIVITY" => "[0-9]+",
	 "PS_SHOP_NAME" => "[\p{Xan}\s\-\.\']+",
	 "PS_SMARTY_FORCE_COMPILE" => "[01]",
	 "PS_STOCK_MANAGEMENT" => "[01]",
	 "PS_STOCK_CUSTOMER_ORDER_REASON" => "[0-9]+",
	 "PS_STOCK_MANAGEMENT" => "[0-9]+",
	 "PS_STOCK_MVT_DEC_REASON_DEFAULT" => "[0-9]+",
	 "PS_STOCK_MVT_INC_REASON_DEFAULT" => "[0-9]+",
	 "PS_STOCK_MVT_REASON_DEFAULT" => "[0-9]+",
	 "PS_STOCK_MVT_SUPPLY_ORDER" => "[0-9]+",
	 "PS_STOCK_MVT_TRANSFER_FROM" => "[0-9]+",
	 "PS_STOCK_MVT_TRANSFER_TO" => "[0-9]+",
	 "PS_STORES_DISPLAY_FOOTER" => "[01]",
	 "PS_UNIDENTIFIED_GROUP" => "[0-9]+");
	 
