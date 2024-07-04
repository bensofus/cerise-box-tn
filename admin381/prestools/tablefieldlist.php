<?php
$tabletree=array(
  "access" => array('add','delete','edit','id_profile','id_tab','view'),
"accessory" => array('id_product_1','id_product_2'),
"address" => array('active','address1','address2','alias','city','company','date_add','date_upd','deleted','dni','firstname','id_address','id_country','id_customer','id_manufacturer','id_state','id_supplier','id_warehouse','lastname','other','phone','phone_mobile','postcode','vat_number'),
"address_format" => array('format','id_country'),
"advice" => array('hide','id_advice','id_ps_advice','id_tab','ids_tab','location','selector','start_day','stop_day','validated','weight'),
"advice_lang" => array('html','id_advice','id_lang'),
"alias" => array('active','alias','id_alias','search'),
"attachment" => array('file','file_name','file_size','id_attachment','mime'),
"attachment_lang" => array('description','id_attachment','id_lang','name'),
"attribute" => array('color','id_attribute','id_attribute_group','position'),
"attribute_group" => array('group_type','id_attribute_group','is_color_group','position'),
"attribute_group_lang" => array('id_attribute_group','id_lang','name','public_name'),
"attribute_group_shop" => array('id_attribute_group','id_shop'),
"attribute_impact" => array('id_attribute','id_attribute_impact','id_product','price','weight'),
"attribute_lang" => array('id_attribute','id_lang','name'),
"attribute_shop" => array('id_attribute','id_shop'),
"badge" => array('awb','group_position','id_badge','id_group','id_ps_badge','scoring','type','validated'),
"badge_lang" => array('description','group_name','id_badge','id_lang','name'),
"carrier" => array('active','deleted','external_module_name','grade','id_carrier','id_reference','id_tax_rules_group','is_free','is_module','max_depth','max_height','max_weight','max_width','name','need_range','position','range_behavior','shipping_external','shipping_handling','shipping_method','url'),
"carrier_group" => array('id_carrier','id_group'),
"carrier_lang" => array('delay','id_carrier','id_lang','id_shop'),
"carrier_shop" => array('id_carrier','id_shop'),
"carrier_tax_rules_group_shop" => array('id_carrier','id_shop','id_tax_rules_group'),
"carrier_zone" => array('id_carrier','id_zone'),
"cart" => array('allow_seperated_package','date_add','date_upd','delivery_option','gift','gift_message','id_address_delivery','id_address_invoice','id_carrier','id_cart','id_currency','id_customer','id_guest','id_lang','id_shop','id_shop_group','mobile_theme','recyclable','secure_key'),
"cart_cart_rule" => array('id_cart','id_cart_rule'),
"cart_product" => array('date_add','id_address_delivery','id_cart','id_product','id_product_attribute','id_shop','quantity'),
"cart_rule" => array('active','carrier_restriction','cart_rule_restriction','code','country_restriction','date_add','date_from','date_to','date_upd','description','free_shipping','gift_product','gift_product_attribute','group_restriction','highlight','id_cart_rule','id_customer','minimum_amount','minimum_amount_currency','minimum_amount_shipping','minimum_amount_tax','partial_use','priority','product_restriction','quantity','quantity_per_user','reduction_amount','reduction_currency','reduction_percent','reduction_product','reduction_tax','shop_restriction'),
"cart_rule_carrier" => array('id_carrier','id_cart_rule'),
"cart_rule_combination" => array('id_cart_rule_1','id_cart_rule_2'),
"cart_rule_country" => array('id_cart_rule','id_country'),
"cart_rule_group" => array('id_cart_rule','id_group'),
"cart_rule_lang" => array('id_cart_rule','id_lang','name'),
"cart_rule_product_rule" => array('id_product_rule','id_product_rule_group','type'),
"cart_rule_product_rule_group" => array('id_cart_rule','id_product_rule_group','quantity'),
"cart_rule_product_rule_value" => array('id_item','id_product_rule'),
"cart_rule_shop" => array('id_cart_rule','id_shop'),
"category" => array('active','date_add','date_upd','id_category','id_parent','id_shop_default','is_root_category','level_depth','nleft','nright','position'),
"category_group" => array('id_category','id_group'),
"category_lang" => array('description','id_category','id_lang','id_shop','link_rewrite','meta_description','meta_keywords','meta_title','name'),
"category_product" => array('id_category','id_product','position'),
"category_shop" => array('id_category','id_shop','position'),
"cms" => array('active','id_cms','id_cms_category','indexation','position'),
"cms_block" => array('display_store','id_cms_block','id_cms_category','location','position'),
"cms_block_lang" => array('id_cms_block','id_lang','name'),
"cms_block_page" => array('id_cms','id_cms_block','id_cms_block_page','is_category'),
"cms_block_shop" => array('id_cms_block','id_shop'),
"cms_category" => array('active','date_add','date_upd','id_cms_category','id_parent','level_depth','position'),
"cms_category_lang" => array('description','id_cms_category','id_lang','link_rewrite','meta_description','meta_keywords','meta_title','name'),
"cms_lang" => array('content','id_cms','id_lang','link_rewrite','meta_description','meta_keywords','meta_title'),
"cms_shop" => array('id_cms','id_shop'),
"compare" => array('id_compare','id_customer'),
"compare_product" => array('date_add','date_upd','id_compare','id_product'),
"condition" => array('calculation_detail','calculation_type','date_add','date_upd','id_condition','id_ps_condition','operator','request','result','type','validated','value'),
"condition_advice" => array('display','id_advice','id_condition'),
"condition_badge" => array('id_badge','id_condition'),
"configuration" => array('date_add','date_upd','id_configuration','id_shop','id_shop_group','name','value'),
"configuration_kpi" => array('date_add','date_upd','id_configuration_kpi','id_shop','id_shop_group','name','value'),
"configuration_kpi_lang" => array('date_upd','id_configuration_kpi','id_lang','value'),
"configuration_lang" => array('date_upd','id_configuration','id_lang','value'),
"connections" => array('date_add','http_referer','id_connections','id_guest','id_page','id_shop','id_shop_group','ip_address'),
"connections_page" => array('id_connections','id_page','time_end','time_start'),
"connections_source" => array('date_add','http_referer','id_connections','id_connections_source','keywords','request_uri'),
"contact" => array('customer_service','email','id_contact','position'),
"contact_lang" => array('description','id_contact','id_lang','name'),
"contact_shop" => array('id_contact','id_shop'),
"country" => array('active','call_prefix','contains_states','display_tax_label','id_country','id_currency','id_zone','iso_code','need_identification_number','need_zip_code','zip_code_format'),
"country_lang" => array('id_country','id_lang','name'),
"country_shop" => array('id_country','id_shop'),
"currency" => array('active','blank','conversion_rate','decimals','deleted','format','id_currency','iso_code','iso_code_num','name','sign'),
"currency_shop" => array('conversion_rate','id_currency','id_shop'),
"customer" => array('active','ape','birthday','company','date_add','date_upd','deleted','email','firstname','id_customer','id_default_group','id_gender','id_lang','id_risk','id_shop','id_shop_group','ip_registration_newsletter','is_guest','last_passwd_gen','lastname','max_payment_days','newsletter','newsletter_date_add','note','optin','outstanding_allow_amount','passwd','secure_key','show_public_prices','siret','website'),
"customer_group" => array('id_customer','id_group'),
"customer_message" => array('date_add','date_upd','file_name','id_customer_message','id_customer_thread','id_employee','ip_address','message','private','read','user_agent'),
"customer_message_sync_imap" => array('md5_header'),
"customer_thread" => array('date_add','date_upd','email','id_contact','id_customer','id_customer_thread','id_lang','id_order','id_product','id_shop','status','token'),
"customization" => array('id_address_delivery','id_cart','id_customization','id_product','id_product_attribute','in_cart','quantity','quantity_refunded','quantity_returned'),
"customization_field" => array('id_customization_field','id_product','required','type'),
"customization_field_lang" => array('id_customization_field','id_lang','name'),
"customized_data" => array('id_customization','index','type','value'),
"date_range" => array('id_date_range','time_end','time_start'),
"delivery" => array('id_carrier','id_delivery','id_range_price','id_range_weight','id_shop','id_shop_group','id_zone','price'),
"employee" => array('active','bo_color','bo_css','bo_menu','bo_theme','bo_width','default_tab','email','firstname','id_employee','id_lang','id_last_customer','id_last_customer_message','id_last_order','id_profile','last_passwd_gen','lastname','optin','passwd','preselect_date_range','stats_compare_from','stats_compare_option','stats_compare_to','stats_date_from','stats_date_to'),
"employee_shop" => array('id_employee','id_shop'),
"feature" => array('id_feature','position'),
"feature_lang" => array('id_feature','id_lang','name'),
"feature_product" => array('id_feature','id_feature_value','id_product'),
"feature_shop" => array('id_feature','id_shop'),
"feature_value" => array('custom','id_feature','id_feature_value'),
"feature_value_lang" => array('id_feature_value','id_lang','value'),
"gender" => array('id_gender','type'),
"gender_lang" => array('id_gender','id_lang','name'),
"group" => array('date_add','date_upd','id_group','price_display_method','reduction','show_prices'),
"group_lang" => array('id_group','id_lang','name'),
"group_reduction" => array('id_category','id_group','id_group_reduction','reduction'),
"group_shop" => array('id_group','id_shop'),
"guest" => array('accept_language','adobe_director','adobe_flash','apple_quicktime','id_customer','id_guest','id_operating_system','id_web_browser','javascript','mobile_theme','real_player','screen_color','screen_resolution_x','screen_resolution_y','sun_java','windows_media'),
"homeslider" => array('id_homeslider_slides','id_shop'),
"homeslider_slides" => array('active','id_homeslider_slides','position'),
"homeslider_slides_lang" => array('description','id_homeslider_slides','id_lang','image','legend','title','url'),
"hook" => array('description','id_hook','live_edit','name','position','title'),
"hook_alias" => array('alias','id_hook_alias','name'),
"hook_module" => array('id_hook','id_module','id_shop','position'),
"hook_module_exceptions" => array('file_name','id_hook','id_hook_module_exceptions','id_module','id_shop'),
"image" => array('cover','id_image','id_product','position'),
"image_lang" => array('id_image','id_lang','legend'),
"image_shop" => array('cover','id_image','id_shop'),
"image_type" => array('categories','height','id_image_type','manufacturers','name','products','scenes','stores','suppliers','width'),
"import_match" => array('id_import_match','match','name','skip'),
"info" => array('id_info','id_shop'),
"info_lang" => array('id_info','id_lang','text'),
"lang" => array('active','date_format_full','date_format_lite','id_lang','is_rtl','iso_code','language_code','name'),
"lang_shop" => array('id_lang','id_shop'),
"layered_category" => array('filter_show_limit','filter_type','id_category','id_layered_category','id_shop','id_value','position','type'),
"layered_filter" => array('date_add','filters','id_layered_filter','n_categories','name'),
"layered_filter_shop" => array('id_layered_filter','id_shop'),
"layered_friendly_url" => array('data','id_lang','id_layered_friendly_url','url_key'),
"layered_indexable_attribute_group" => array('id_attribute_group','indexable'),
"layered_indexable_attribute_group_lang_value" => array('id_attribute_group','id_lang','meta_title','url_name'),
"layered_indexable_attribute_lang_value" => array('id_attribute','id_lang','meta_title','url_name'),
"layered_indexable_feature" => array('id_feature','indexable'),
"layered_indexable_feature_lang_value" => array('id_feature','id_lang','meta_title','url_name'),
"layered_indexable_feature_value_lang_value" => array('id_feature_value','id_lang','meta_title','url_name'),
"layered_price_index" => array('id_currency','id_product','id_shop','price_max','price_min'),
"layered_product_attribute" => array('id_attribute','id_attribute_group','id_product','id_shop'),
"linksmenutop" => array('id_linksmenutop','id_shop','new_window'),
"linksmenutop_lang" => array('id_lang','id_linksmenutop','id_shop','label','link'),
"log" => array('date_add','date_upd','error_code','id_employee','id_log','message','object_id','object_type','severity'),
"manufacturer" => array('active','date_add','date_upd','id_manufacturer','name'),
"manufacturer_lang" => array('description','id_lang','id_manufacturer','meta_description','meta_keywords','meta_title','short_description'),
"manufacturer_shop" => array('id_manufacturer','id_shop'),
"memcached_servers" => array('id_memcached_server','ip','port','weight'),
"message" => array('date_add','id_cart','id_customer','id_employee','id_message','id_order','message','private'),
"message_readed" => array('date_add','id_employee','id_message'),
"meta" => array('configurable','id_meta','page'),
"meta_lang" => array('description','id_lang','id_meta','id_shop','keywords','title','url_rewrite'),
"module" => array('active','id_module','name','version'),
"module_access" => array('configure','id_module','id_profile','view'),
"module_country" => array('id_country','id_module','id_shop'),
"module_currency" => array('id_currency','id_module','id_shop'),
"module_group" => array('id_group','id_module','id_shop'),
"module_preference" => array('favorite','id_employee','id_module_preference','interest','module'),
"module_shop" => array('enable_device','id_module','id_shop'),
"newsletter" => array('active','email','http_referer','id','id_shop','id_shop_group','ip_registration_newsletter','newsletter_date_add'),
"operating_system" => array('id_operating_system','name'),
"order_carrier" => array('date_add','id_carrier','id_order','id_order_carrier','id_order_invoice','shipping_cost_tax_excl','shipping_cost_tax_incl','tracking_number','weight'),
"order_cart_rule" => array('free_shipping','id_cart_rule','id_order','id_order_cart_rule','id_order_invoice','name','value','value_tax_excl'),
"order_detail" => array('discount_quantity_applied','download_deadline','download_hash','download_nb','ecotax','ecotax_tax_rate','group_reduction','id_order','id_order_detail','id_order_invoice','id_shop','id_warehouse','original_product_price','product_attribute_id','product_ean13','product_id','product_name','product_price','product_quantity','product_quantity_discount','product_quantity_in_stock','product_quantity_refunded','product_quantity_reinjected','product_quantity_return','product_reference','product_supplier_reference','product_upc','product_weight','purchase_supplier_price','reduction_amount','reduction_amount_tax_excl','reduction_amount_tax_incl','reduction_percent','tax_computation_method','tax_name','tax_rate','total_price_tax_excl','total_price_tax_incl','total_shipping_price_tax_excl','total_shipping_price_tax_incl','unit_price_tax_excl','unit_price_tax_incl'),
"order_detail_tax" => array('id_order_detail','id_tax','total_amount','unit_amount'),
"order_history" => array('date_add','id_employee','id_order','id_order_history','id_order_state'),
"order_invoice" => array('date_add','delivery_date','delivery_number','id_order','id_order_invoice','note','number','shipping_tax_computation_method','total_discount_tax_excl','total_discount_tax_incl','total_paid_tax_excl','total_paid_tax_incl','total_products','total_products_wt','total_shipping_tax_excl','total_shipping_tax_incl','total_wrapping_tax_excl','total_wrapping_tax_incl'),
"order_invoice_payment" => array('id_order','id_order_invoice','id_order_payment'),
"order_invoice_tax" => array('amount','id_order_invoice','id_tax','type'),
"order_message" => array('date_add','id_order_message'),
"order_message_lang" => array('id_lang','id_order_message','message','name'),
"order_payment" => array('amount','card_brand','card_expiration','card_holder','card_number','conversion_rate','date_add','id_currency','id_order_payment','order_reference','payment_method','transaction_id'),
"order_return" => array('date_add','date_upd','id_customer','id_order','id_order_return','question','state'),
"order_return_detail" => array('id_customization','id_order_detail','id_order_return','product_quantity'),
"order_return_state" => array('color','id_order_return_state'),
"order_return_state_lang" => array('id_lang','id_order_return_state','name'),
"order_slip" => array('amount','conversion_rate','date_add','date_upd','id_customer','id_order','id_order_slip','partial','shipping_cost','shipping_cost_amount'),
"order_slip_detail" => array('amount_tax_excl','amount_tax_incl','id_order_detail','id_order_slip','product_quantity'),
"order_state" => array('color','deleted','delivery','hidden','id_order_state','invoice','logable','module_name','paid','send_email','shipped','unremovable'),
"order_state_lang" => array('id_lang','id_order_state','name','template'),
"orders" => array('carrier_tax_rate','conversion_rate','current_state','date_add','date_upd','delivery_date','delivery_number','gift','gift_message','id_address_delivery','id_address_invoice','id_carrier','id_cart','id_currency','id_customer','id_lang','id_order','id_shop','id_shop_group','invoice_date','invoice_number','mobile_theme','module','payment','recyclable','reference','secure_key','shipping_number','total_discounts','total_discounts_tax_excl','total_discounts_tax_incl','total_paid','total_paid_real','total_paid_tax_excl','total_paid_tax_incl','total_products','total_products_wt','total_shipping','total_shipping_tax_excl','total_shipping_tax_incl','total_wrapping','total_wrapping_tax_excl','total_wrapping_tax_incl','valid'),
"pack" => array('id_product_item','id_product_pack','quantity'),
"page" => array('id_object','id_page','id_page_type'),
"page_type" => array('id_page_type','name'),
"page_viewed" => array('counter','id_date_range','id_page','id_shop','id_shop_group'),
"pagenotfound" => array('date_add','http_referer','id_pagenotfound','id_shop','id_shop_group','request_uri'),
"product" => array('active','additional_shipping_cost','advanced_stock_management','available_date','available_for_order','cache_default_attribute','cache_has_attachments','cache_is_pack','condition','customizable','date_add','date_upd','depth','ean13','ecotax','height','id_category_default','id_manufacturer','id_product','id_product_redirected','id_shop_default','id_supplier','id_tax_rules_group','indexed','is_virtual','location','minimal_quantity','on_sale','online_only','out_of_stock','price','quantity','quantity_discount','redirect_type','reference','show_price','supplier_reference','text_fields','unit_price_ratio','unity','upc','uploadable_files','visibility','weight','wholesale_price','width'),
"product_attachment" => array('id_attachment','id_product'),
"product_attribute" => array('available_date','default_on','ean13','ecotax','id_product','id_product_attribute','location','minimal_quantity','price','quantity','reference','supplier_reference','unit_price_impact','upc','weight','wholesale_price'),
"product_attribute_combination" => array('id_attribute','id_product_attribute'),
"product_attribute_image" => array('id_image','id_product_attribute'),
"product_attribute_shop" => array('available_date','default_on','ecotax','id_product_attribute','id_shop','minimal_quantity','price','unit_price_impact','weight','wholesale_price'),
"product_carrier" => array('id_carrier_reference','id_product','id_shop'),
"product_country_tax" => array('id_country','id_product','id_tax'),
"product_download" => array('active','date_add','date_expiration','display_filename','filename','id_product','id_product_download','is_shareable','nb_days_accessible','nb_downloadable'),
"product_group_reduction_cache" => array('id_group','id_product','reduction'),
"product_lang" => array('available_later','available_now','description','description_short','id_lang','id_product','id_shop','link_rewrite','meta_description','meta_keywords','meta_title','name'),
"product_sale" => array('date_upd','id_product','quantity','sale_nbr'),
"product_shop" => array('active','additional_shipping_cost','advanced_stock_management','available_date','available_for_order','cache_default_attribute','condition','customizable','date_add','date_upd','ecotax','id_category_default','id_product','id_product_redirected','id_shop','id_tax_rules_group','indexed','minimal_quantity','on_sale','online_only','price','redirect_type','show_price','text_fields','unit_price_ratio','unity','uploadable_files','visibility','wholesale_price'),
"product_supplier" => array('id_currency','id_product','id_product_attribute','id_product_supplier','id_supplier','product_supplier_price_te','product_supplier_reference'),
"product_tag" => array('id_product','id_tag'),
"profile" => array('id_profile'),
"profile_lang" => array('id_lang','id_profile','name'),
"quick_access" => array('id_quick_access','link','new_window'),
"quick_access_lang" => array('id_lang','id_quick_access','name'),
"range_price" => array('delimiter1','delimiter2','id_carrier','id_range_price'),
"range_weight" => array('delimiter1','delimiter2','id_carrier','id_range_weight'),
"referrer" => array('base_fee','click_fee','date_add','http_referer_like','http_referer_like_not','http_referer_regexp','http_referer_regexp_not','id_referrer','name','passwd','percent_fee','request_uri_like','request_uri_like_not','request_uri_regexp','request_uri_regexp_not'),
"referrer_cache" => array('id_connections_source','id_referrer'),
"referrer_shop" => array('cache_order_rate','cache_orders','cache_pages','cache_reg_rate','cache_registrations','cache_sales','cache_visitors','cache_visits','id_referrer','id_shop'),
"request_sql" => array('id_request_sql','name','sql'),
"required_field" => array('field_name','id_required_field','object_name'),
"risk" => array('color','id_risk','percent'),
"risk_lang" => array('id_lang','id_risk','name'),
"scene" => array('active','id_scene'),
"scene_category" => array('id_category','id_scene'),
"scene_lang" => array('id_lang','id_scene','name'),
"scene_products" => array('id_product','id_scene','x_axis','y_axis','zone_height','zone_width'),
"scene_shop" => array('id_scene','id_shop'),
"search_engine" => array('getvar','id_search_engine','server'),
"search_index" => array('id_product','id_word','weight'),
"search_word" => array('id_lang','id_shop','id_word','word'),
"sekeyword" => array('date_add','id_sekeyword','id_shop','id_shop_group','keyword'),
"shop" => array('active','deleted','id_category','id_shop','id_shop_group','id_theme','name'),
"shop_group" => array('active','deleted','id_shop_group','name','share_customer','share_order','share_stock'),
"shop_url" => array('active','domain','domain_ssl','id_shop','id_shop_url','main','physical_uri','virtual_uri'),
"specific_price" => array('from','from_quantity','id_cart','id_country','id_currency','id_customer','id_group','id_product','id_product_attribute','id_shop','id_shop_group','id_specific_price','id_specific_price_rule','price','reduction','reduction_type','to'),
"specific_price_priority" => array('id_product','id_specific_price_priority','priority'),
"specific_price_rule" => array('from','from_quantity','id_country','id_currency','id_group','id_shop','id_specific_price_rule','name','price','reduction','reduction_type','to'),
"specific_price_rule_condition" => array('id_specific_price_rule_condition','id_specific_price_rule_condition_group','type','value'),
"specific_price_rule_condition_group" => array('id_specific_price_rule','id_specific_price_rule_condition_group'),
"state" => array('active','id_country','id_state','id_zone','iso_code','name','tax_behavior'),
"statssearch" => array('date_add','id_shop','id_shop_group','id_statssearch','keywords','results'),
"stock" => array('ean13','id_product','id_product_attribute','id_stock','id_warehouse','physical_quantity','price_te','reference','upc','usable_quantity'),
"stock_available" => array('depends_on_stock','id_product','id_product_attribute','id_shop','id_shop_group','id_stock_available','out_of_stock','quantity'),
"stock_mvt" => array('current_wa','date_add','employee_firstname','employee_lastname','id_employee','id_order','id_stock','id_stock_mvt','id_stock_mvt_reason','id_supply_order','last_wa','physical_quantity','price_te','referer','sign'),
"stock_mvt_reason" => array('date_add','date_upd','deleted','id_stock_mvt_reason','sign'),
"stock_mvt_reason_lang" => array('id_lang','id_stock_mvt_reason','name'),
"store" => array('active','address1','address2','city','date_add','date_upd','email','fax','hours','id_country','id_state','id_store','latitude','longitude','name','note','phone','postcode'),
"store_shop" => array('id_shop','id_store'),
"supplier" => array('active','date_add','date_upd','id_supplier','name'),
"supplier_lang" => array('description','id_lang','id_supplier','meta_description','meta_keywords','meta_title'),
"supplier_shop" => array('id_shop','id_supplier'),
"supply_order" => array('date_add','date_delivery_expected','date_upd','discount_rate','discount_value_te','id_currency','id_lang','id_ref_currency','id_supplier','id_supply_order','id_supply_order_state','id_warehouse','is_template','reference','supplier_name','total_tax','total_te','total_ti','total_with_discount_te'),
"supply_order_detail" => array('discount_rate','discount_value_te','ean13','exchange_rate','id_currency','id_product','id_product_attribute','id_supply_order','id_supply_order_detail','name','price_te','price_ti','price_with_discount_te','price_with_order_discount_te','quantity_expected','quantity_received','reference','supplier_reference','tax_rate','tax_value','tax_value_with_order_discount','unit_price_te','upc'),
"supply_order_history" => array('date_add','employee_firstname','employee_lastname','id_employee','id_state','id_supply_order','id_supply_order_history'),
"supply_order_receipt_history" => array('date_add','employee_firstname','employee_lastname','id_employee','id_supply_order_detail','id_supply_order_receipt_history','id_supply_order_state','quantity'),
"supply_order_state" => array('color','delivery_note','editable','enclosed','id_supply_order_state','pending_receipt','receipt_state'),
"supply_order_state_lang" => array('id_lang','id_supply_order_state','name'),
"tab" => array('active','class_name','hide_host_mode','id_parent','id_tab','module','position'),
"tab_advice" => array('id_advice','id_tab'),
"tab_lang" => array('id_lang','id_tab','name'),
"tab_module_preference" => array('id_employee','id_tab','id_tab_module_preference','module'),
"tag" => array('id_lang','id_tag','name'),
"tax" => array('active','deleted','id_tax','rate'),
"tax_lang" => array('id_lang','id_tax','name'),
"tax_rule" => array('behavior','description','id_country','id_state','id_tax','id_tax_rule','id_tax_rules_group','zipcode_from','zipcode_to'),
"tax_rules_group" => array('active','id_tax_rules_group','name'),
"tax_rules_group_shop" => array('id_shop','id_tax_rules_group'),
"theme" => array('default_left_column','default_right_column','directory','id_theme','name','product_per_page','responsive'),
"theme_meta" => array('id_meta','id_theme','id_theme_meta','left_column','right_column'),
"theme_specific" => array('entity','id_object','id_shop','id_theme'),
"themeconfigurator" => array('active','hook','html','id_item','id_lang','id_shop','image','image_h','image_w','item_order','target','title','title_use','url'),
"timezone" => array('id_timezone','name'),
"warehouse" => array('deleted','id_address','id_currency','id_employee','id_warehouse','management_type','name','reference'),
"warehouse_carrier" => array('id_carrier','id_warehouse'),
"warehouse_product_location" => array('id_product','id_product_attribute','id_warehouse','id_warehouse_product_location','location'),
"warehouse_shop" => array('id_shop','id_warehouse'),
"web_browser" => array('id_web_browser','name'),
"webservice_account" => array('active','class_name','description','id_webservice_account','is_module','key','module_name'),
"webservice_account_shop" => array('id_shop','id_webservice_account'),
"webservice_permission" => array('id_webservice_account','id_webservice_permission','method','resource'),
"zone" => array('active','id_zone','name'),
"zone_shop" => array('id_shop','id_zone'));