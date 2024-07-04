{**
* 2007-2015 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author    PrestaShop SA <contact@prestashop.com>
*  @copyright 2007-2015 PrestaShop SA
*  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*}

<div id="mobile_vegamenu" class="mobile-menu"> 
<div class="pos-menu-vertical">
	<ul class="menu-content"> 
		{foreach from=$menus item=menu name=menus}	 			
				<li class="menu-item menu-item{$menu.id_posvegamenu_item} {$menu.item_class|escape:'html':'UTF-8'} {if $menu.submenu_type == 0 && count($menu.sub_menu) > 0}hasChild{/if}">
					
					<a href="{if $menu.type_link==0}{$menu.link|escape:'html':'UTF-8'}{elseif $menu.type_link ==1}{$menu.custom_link|escape:'html':'UTF-8'}{else}javascript:void(0){/if}" {if $menu.new_window == 1} target="_blank" {/if}>
						
						{if $menu.type_icon == 2 && $menu.icon != ''}
						<img class="img-icon" src="{$menu.icon|escape:'html':'UTF-8'}" alt=""/>
						{elseif $menu.type_icon == 1}
							{$menu.icon_class|escape:'html':'UTF-8' nofilter}
						{/if}
						<span>{$menu.title|escape:'html':'UTF-8'}</span>
						{if $menu.subtitle != ''}<span class="menu-subtitle">{$menu.subtitle|escape:'html':'UTF-8'}</span>{/if}
					</a>
					{if $menu.submenu_type == 0}
						{if isset($menu.sub_menu) && count($menu.sub_menu.info_rows) > 0}
						
						{if count($menu.sub_menu) > 0}<span class="icon-drop-mobile"><i class="material-icons add">add </i><i class="material-icons remove">remove </i></span>{/if}
						<div class="pos-sub-menu menu-dropdown col-xs-12 col-lg-{$menu.sub_menu.submenu_config.submenu_width|escape:'html':'UTF-8'} {$menu.sub_menu.submenu_config.submenu_class|escape:'html':'UTF-8'}">
							<div class="popup_vertical">
							{foreach from=$menu.sub_menu.info_rows item= menu_row name=menu_row}
								<div class="pos-menu-row row {$menu.item_class|escape:'html':'UTF-8'}">
									{if isset($menu_row.list_col) && count($menu_row.list_col) > 0}
										{foreach from=$menu_row.list_col item= menu_col name=menu_col}
											<div class="pos-menu-col {$menu_col.class|escape:'html':'UTF-8'} {if !$menu_col.active_mobile}hidden-mobile{/if}">
												{if $menu_col.title}
													{if $menu_col.type_link == 0}
														<a href="{$menu_col.link}" class="column_title">{$menu_col.title}</a>
													{else if $menu_col.type_link == 1}
														{if $menu_col.custom_link}
															<a href="{$menu_col.custom_link}">{$menu_col.title}</a>
														{else}
															<h4 class="column_title">{$menu_col.title}</h4>
														{/if}
													{else}
														<h4 class="column_title">{$menu_col.title}</h4>
													{/if}
													<span class="icon-drop-mobile"><i class="material-icons add">add </i><i class="material-icons remove">remove </i></span>
												{/if}
												{if count($menu_col.list_menu_item) > 0}
													<ul class="ul-column {if $menu_col.title}column_dropdown_vega {/if}">
													{foreach from=$menu_col.list_menu_item item= sub_menu_item name=sub_menu_item}
														<li class="submenu-item {if !$sub_menu_item.active_mobile}hidden-mobile{/if}">
															{if $sub_menu_item.type_link == 1}
																<a href="{$sub_menu_item.categories.link}">{$sub_menu_item.categories.name}</a>
																{if $sub_menu_item.categories.children}<span class="icon-drop-mobile"><i class="material-icons add">add </i><i class="material-icons remove">remove </i></span>{/if}
																{if $sub_menu_item.categories.children}
																<ul class="category-sub-menu">
																	{foreach from=$sub_menu_item.categories.children item=node}
																	  <li>
																		  <a href="{$node.link}">{$node.name}</a>
																	  </li>
																	{/foreach}
																</ul>
																{/if}
															{else if $sub_menu_item.type_link == 2}
																<a href="{$sub_menu_item.link}">{$sub_menu_item.title}</a>
															{else if $sub_menu_item.type_link == 3}
																{if $sub_menu_item.customlink_title }
																	{if $sub_menu_item.customlink_link}
																		<a href="{$sub_menu_item.customlink_link|escape:'html':'UTF-8'}">{$sub_menu_item.customlink_title|escape:'html':'UTF-8'}</a>
																	{else}
																		<span>{$sub_menu_item.customlink_title|escape:'html':'UTF-8'}</span>
																	{/if}	
																{/if}
															
															{else if $sub_menu_item.type_link == 5}
																{if $sub_menu_item.image}
																	{if $sub_menu_item.image_link}
																		<a href="{$sub_menu_item.image_link}"><img src="{$sub_menu_item.image}" alt="" /></a>
																	{else}
																		<img src="{$sub_menu_item.image}" />
																	{/if}
																{/if}
															{else if $sub_menu_item.type_link == 6}
																{if $sub_menu_item.htmlcontent}
																<div class="html-block">
																	{$sub_menu_item.htmlcontent nofilter}
																</div>
																{/if}
															{else if $sub_menu_item.type_link == 7}
																<a href="{$sub_menu_item.link}"><img src="{$sub_menu_item.manufacturer_logo}" /></a>
															{/if}
														</li>
													{/foreach}
													</ul>
												{/if}
											</div>
										{/foreach}
									{/if}
								</div>

							{/foreach}
							</div>
						</div>
						{/if}
					{else if $menu.submenu_type == 1}
						{$menu.sub_menu|regex_replace:"/[\']/":"&apos;"|escape:'quotes':'UTF-8' nofilter}
					{/if}
				</li>
		{/foreach}
	</ul>
	
</div>
</div>