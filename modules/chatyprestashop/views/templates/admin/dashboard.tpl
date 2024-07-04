{**
* The PrestaShop Chaty module is under the GPL2 license, 
* the Chaty.app SaaS product isn't under the GPL2 license and not related 
* to the Prestashop Chaty module source code
* 
* @author Poptin LTD and Contributors <contact@poptin.com>
* @copyright Poptin LTD
* @license GPL2
*}
{if $chaty_token != false && $chaty_is_prestashop_user === 'yes'}
<prestashop-accounts></prestashop-accounts>
<div id="ps-billing"></div>
<div id="ps-modal"></div>
{/if}

{include file='module:/chatyprestashop/views/templates/admin/common.tpl'}
{if $chaty_token != false && $chaty_is_prestashop_user === 'yes'}
<script src="{$urlAccountsCdn|escape:'htmlall':'UTF-8'}" rel=preload></script>
<script src="{$urlBilling|escape:'htmlall':'UTF-8'}" rel=preload></script>
<script>
    /*********************
     * PrestaShop Account *
     * *******************/
    window?.psaccountsVue?.init();

    // Check if Account is associated before displaying Billing component
    if(window.psaccountsVue.isOnboardingCompleted() == true)
    {
        const { domain, uuid } = window.psBillingContext.context.shop
        window.dispatchEvent(new CustomEvent('chaty.saveUserDetails', {
            detail: {
                shop_id: uuid,
                shop_url: domain
            }
        }))  
        /*********************
         * PrestaShop Billing *
         * *******************/
        window.psBilling.initialize(window.psBillingContext.context, '#ps-billing', '#ps-modal');
    }
</script>
{/if}
<script>
jQuery(function($) {
    const $li = $('.toolbarBox .btn-toolbar ul.nav li').last();
    if( $li.length > 0 ) {
        const $cloned_li = $li.clone();
        const a = $cloned_li.find('a');
        a.attr('href', '#');
        a.attr('title', 'Logout from chaty dashboard');
        a.html('Logout from Chaty');

        a.on('click', function(ev) {
            ev.preventDefault();
            window.postMessage('chaty/logout_from_iframe');
            return;
        })
        $cloned_li.attr('id', 'toolbar-chaty-logout')
        $('.toolbarBox .btn-toolbar ul.nav').append($cloned_li);
    }
})
</script>
<div class="chatyprestashop-admin">
    <iframe id="chaty-iframe" src="{$chaty_iframe_url|escape:'htmlall':'UTF-8'}" frameborder="0" width="100%" height="100%"></iframe>
</div>