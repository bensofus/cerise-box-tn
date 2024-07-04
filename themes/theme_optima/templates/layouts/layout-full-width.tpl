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
{extends file='layouts/layout-both-columns.tpl'}

{block name='content_wrapper'}
	{if $page.page_name != 'index'}
		<div id="content-wrapper" class="js-content-wrapper 
  {if $page.page_name == 'product' && $postheme.product_layout == 1}left-column col-xs-12 col-lg-9
  {elseif $page.page_name == 'product' && $postheme.product_layout == 3}right-column col-xs-12 col-lg-9
  {else}col-xs-12{/if}">
			{hook h="displayContentWrapperTop"}
			{block name='content'}
				<p>Hello world! This is HTML5 Boilerplate.</p>
			{/block}
			{hook h="displayContentWrapperBottom"}
		</div>
	{/if}
{/block}
{if $page.page_name == 'product' && $postheme.product_layout == 1}
	{block name="left_column"}
		<div id="left-column" class="col-xs-12 col-lg-3">
			<div id="left-content">
				{block name="left_content"}
					{hook h="displayLeftColumnProduct"}
				{/block}
			</div>
		</div>
	{/block}
{else}
	{block name='left_column'}{/block}
{/if}
{if $page.page_name == 'product' && $postheme.product_layout == 3}
	{block name="right_column"}
		<div id="right-column" class="col-xs-12 col-lg-3">
			<div id="right-content">
				{block name="right_content"}
					{hook h="displayRightColumnProduct"}
				{/block}
			</div>
		</div>
	{/block}
{else}
	{block name='right_column'}{/block}
{/if}