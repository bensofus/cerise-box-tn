<?php
/**
 * The PrestaShop Chaty module is under the GPL2 license,
 * the Chaty.app SaaS product isn't under the GPL2 license and not related
 * to the Prestashop Chaty module source code
 *
 * @author Poptin LTD and Contributors <contact@poptin.com>
 * @copyright Poptin LTD
 * @license GPL2
 */
use ChatyPrestashop\App\ChatyExternalApi;

if (!defined('_PS_VERSION_')) {
    exit;
}

class ChatyPrestashopAjaxController extends ModuleAdminController
{
    public function init()
    {
        parent::init();
    }

    /**
     * Save user identifier and token
     *
     * @return void
     */
    public function ajaxProcessSaveUser()
    {
        $user_identifier = Tools::getValue('user_identifier');
        $user_token = Tools::getValue('user_token');
        $reg_source = Tools::getValue('reg_source');

        if (empty($user_identifier) || empty($user_token)) {
            $response = [
                'success' => false,
                'message' => 'user_identifier and user_token are required',
            ];

            exit(json_encode($response));
        } else {
            // save user data
            ChatyExternalApi::saveUser($user_identifier, $user_token, $reg_source);

            // Handle AJAX request here
            $response = [
                'success' => true,
                'message' => 'saved user successfully',
            ];

            exit(json_encode($response));
        }
    }

    /**
     * Log out user
     *
     * @return void
     */
    public function ajaxProcessLogOutUser()
    {
        // remove user data
        ChatyExternalApi::removeUser();
        // Handle AJAX request here
        $response = [
            'success' => true,
            'message' => 'log out successful',
        ];

        exit(json_encode($response));
    }
}
