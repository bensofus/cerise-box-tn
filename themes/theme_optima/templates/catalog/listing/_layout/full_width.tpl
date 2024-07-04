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
{block name="content_wrapper"}
  <div id="content-wrapper" class="js-content-wrapper no-column col-xs-12">
  {hook h="displayContentWrapperTop"}
  {block name='content'}
    <section id="main">

      {hook h="displayHeaderCategory"}

      <section id="products">
        {if $listing.products|count}
          {if isset($postheme.category_filters) && $postheme.category_filters == 'top'}
            <div class="filters-wrapper filters-top">
              {hook h="displayFilterCanvas"}
            </div>
          {/if}
          {block name='product_list_active_filters'}
            <div class="filters-active">
              {$listing.rendered_active_filters nofilter}
            </div>
          {/block}
          {block name='product_list_top'}
            {include file='catalog/_partials/products-top.tpl' listing=$listing}
          {/block}
          

          {block name='product_list'}
            {include file='catalog/_partials/products.tpl' listing=$listing productClass="col-xs-6 col-xl-4"}
          {/block}

          {block name='product_list_bottom'}
            {include file='catalog/_partials/products-bottom.tpl' listing=$listing}
          {/block}

        {else}
          <div id="js-product-list-top"></div>

          <div id="js-product-list">
            {capture assign="errorContent"}
              <h4>{l s='No products available yet' d='Shop.Theme.Catalog'}</h4>
              <p>{l s='Stay tuned! More products will be shown here as they are added.' d='Shop.Theme.Catalog'}</p>
            {/capture}

            {include file='errors/not-found.tpl' errorContent=$errorContent}
          </div>

          <div id="js-product-list-bottom"></div>
        {/if}
      </section>
      {block name='product_list_footer'}{/block}  
      {hook h="displayFooterCategory"}

    </section>
  {/block}
  {hook h="displayContentWrapperBottom"}
  </div>
{/block}
{block name="left_column"}{/block}
{block name="right_column"}{/block}
{block name='hook_filter_canvas'}
  <div class="filters-canvas">
    <button class="filter-close-btn"><i class="icon-rt-close-outline float-xs-right"></i></button>
    {if isset($postheme.category_filters) && $postheme.category_filters == 'canvas'}
      {hook h="displayFilterCanvas"}
    {else}
      <div class="filters-mobile">
      <div id="_mobile_search_filters"></div>
      </div>
    {/if}
  </div>
{/block}

