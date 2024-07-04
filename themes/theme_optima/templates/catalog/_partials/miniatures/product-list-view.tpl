{**
 * Copyright since 2007 PrestaShop SA and Contributors
 * PrestaShop is an International Registered Trademark & Property of PrestaShop SA
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License 3.0 (AFL-3.0)
 * that is bundled with this package in the file LICENSE.md.
 * It is also available through the world-wide-web at this URL:
 * https://opensource.org/licenses/AFL-3.0
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please refer to https://devdocs.prestashop.com/ for more information.
 *
 * @author    PrestaShop SA and Contributors <contact@prestashop.com>
 * @copyright Since 2007 PrestaShop SA and Contributors
 * @license   https://opensource.org/licenses/AFL-3.0 Academic Free License 3.0 (AFL-3.0)
 *}
 {block name='product_miniature_item'}
	
	<article class="thumbnail-container product-miniature-list product-miniature js-product-miniature item_in" data-id-product="{$product.id_product}" data-id-product-attribute="{$product.id_product_attribute}">
		<div class="img-block">
		  {block name='product_thumbnail'}
			{if $product.cover}
	            <a href="{$product.url}" class="thumbnail product-thumbnail">
	              <img
	                src="{$product.cover.bySize.home_default.url}"
	                alt="{if !empty($product.cover.legend)}{$product.cover.legend}{else}{$product.name|truncate:30:'...'}{/if}"
	                loading="lazy"
	                data-full-size-image-url="{$product.cover.large.url}"
	                width="{$product.cover.bySize.home_default.width}"
	                height="{$product.cover.bySize.home_default.height}"
	              />
	            </a>
	        {else}
	            <a href="{$product.url}" class="thumbnail product-thumbnail">
	              <img
	                src="{$urls.no_picture_image.bySize.home_default.url}"
	                loading="lazy"
	                width="{$urls.no_picture_image.bySize.home_default.width}"
	                height="{$urls.no_picture_image.bySize.home_default.height}"
	              />
	            </a>
	        {/if}
		  {/block}
		</div>
		<div class="product-content"> 
			{if isset($product.id_manufacturer) && $show_brand}
			 <div class="manufacturer"><a href="{url entity='manufacturer' id=$product.id_manufacturer }">{Manufacturer::getnamebyid($product.id_manufacturer)}</a></div>
			{/if}
			{block name='product_name'}
			  <h3><a href="{$product.url}" class="product_name" title="{$product.name}">{$product.name}</a></h3> 
			{/block}
			{block name='product_reviews'}
				<div class="hook-reviews">
				{hook h='displayProductListReviews' product=$product}
				</div>
			{/block}
			
			{block name='product_description_short'}
				<div class="product-desc">{$product.description_short nofilter}</div>
			{/block}
		
			<div class="variant-links">
			{block name='product_variants'}
			{if $product.main_variants}
			{include file='catalog/_partials/variant-links.tpl' variants=$product.main_variants}
			{/if}
			{/block} 
			</div>
			<div class="col-buy">
			  {block name='product_price_and_shipping'}
				{if $product.show_price}
				  <div class="product-price-and-shipping">
					{if $product.has_discount}
					  {hook h='displayProductPriceBlock' product=$product type="old_price"}
  
					  <span class="sr-only">{l s='Regular price' d='Shop.Theme.Catalog'}</span>
					  <span class="regular-price">{$product.regular_price}</span>
					{/if}
  
					{hook h='displayProductPriceBlock' product=$product type="before_price"}
  
					<span class="sr-only">{l s='Price' d='Shop.Theme.Catalog'}</span>
					<span class="price {if $product.has_discount}price-sale{/if}">{$product.price}</span>
					{hook h='displayProductPriceBlock' product=$product type='unit_price'}
  
					{hook h='displayProductPriceBlock' product=$product type='weight'}
				  </div>
				{/if}
			  {/block} 
			  <div class="availability"> 
				{if $product.show_availability }
					{if $product.quantity > 0}
					<div class="availability-list in-stock">{l s='Availability' d='Shop.Theme.Actions'}: <span>{$product.quantity} {l s='In Stock' d='Shop.Theme.Actions'}</span></div>

					{else}

					<div class="availability-list out-of-stock">{l s='Availability' d='Shop.Theme.Actions'}: <span>{l s='Out of stock' d='Shop.Theme.Actions'}</span></div> 
					{/if}
				{/if}
			  </div>
			  <div class="product-cart">
				{include file='catalog/_partials/customize/button-cart.tpl' product=$product}
			  </div>
			  <div class="add-links">					
				{hook h='displayProductListFunctionalButtons' product=$product}
				{assign var='displayProductListCompare' value={hook h='displayProductListCompare'} }
				{if $displayProductListCompare} 
				<a href="#" class="poscompare-add compare-button js-poscompare-add"  data-id_product="{$product.id_product|intval}" onclick="posCompare.addCompare($(this),{$product.id_product|intval},'{$product.name}','{$product.cover.bySize.home_default.url}'); return false;" title="{l s='Add to compare' d='Shop.Theme.Actions'}"><i class="icon-rt-ios-shuffle-strong"></i><span>{l s='Compare' d='Shop.Theme.Actions'}</span></a>
				{/if}
			   </div>
			</div>
			
		</div>
	</article>
{/block}