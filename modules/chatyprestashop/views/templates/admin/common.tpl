{**
 * The PrestaShop Chaty module is under the GPL2 license, 
 * the Chaty.app SaaS product isn't under the GPL2 license and not related 
 * to the Prestashop Chaty module source code
 * 
 * @author Poptin LTD and Contributors <contact@poptin.com>
 * @copyright Poptin LTD
 * @license GPL2
 *}

 <script>
    const chaty_module_dir = "{$chaty_module_dir|escape:'javascript':'UTF-8'}";
    const chaty_rest_api = "{$chaty_rest_api|escape:'javascript':'UTF-8'}";
    const chaty_admin_ajax_url = "{$chaty_admin_ajax_url|escape:'javascript':'UTF-8'}";
    const chaty_token = "{$chaty_token|escape:'javascript':'UTF-8'}";

    window.chatyprestashop_vars = {
        chaty_module_dir, 
        chaty_rest_api,
        chaty_admin_ajax_url,
        chaty_token
    }
 </script>