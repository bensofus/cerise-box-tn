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

namespace ChatyPrestashop\App;

if (!defined('_PS_VERSION_')) {
    exit;
}

class ChatyExternalApi
{
    /**
     * Get the API endpoint
     *
     * @param string $endpoint
     *
     * @return string
     */
    public static function restApi($endpoint = '')
    {
        return 'https://api.chaty.app/api/' . $endpoint;
        // return 'https://dev-api.chaty.app/api/' . $endpoint;
    }

    /**
     * Get the widget script url
     *
     * @return string
     */
    public static function widgetScript()
    {
        return 'https://cdn.chaty.app/pixel-dev.js?id=' . self::getIdentifier();
        // return 'https://dev-cdn.chaty.app/pixel-dev.js?id=' . self::getIdentifier();
    }

    public static function iframeUrl()
    {
        $user_identifier = self::getIdentifier();
        $user_token = self::getToken();

        if ($user_identifier !== false && $user_token !== false) {
            return 'https://go.chaty.app/prestashop?token=' . $user_token;
            // return 'https://dev-go.chaty.app/prestashop?token=' . $user_token;
        }

        return '';
    }

    /**
     * Get the user token
     *
     * @return string|bool
     */
    public static function getToken()
    {
        $user_token = \Configuration::get('CHATY_USER_TOKEN');

        if ($user_token !== false && $user_token !== '') {
            return $user_token;
        }

        return false;
    }

    /**
     * Get the user identifier
     *
     * @return string|bool
     */
    public static function getIdentifier()
    {
        $user_identifier = \Configuration::get('CHATY_USER_IDENTIFIER');

        if ($user_identifier !== false && $user_identifier !== '') {
            return $user_identifier;
        }

        return false;
    }

    /**
     * Check if the user is logged in
     *
     * @return bool
     */
    public static function isTokenValid()
    {
        if (self::getToken() && self::getIdentifier()) {
            $url = self::restApi('profile');

            // Initialize cURL session
            $ch = curl_init();

            // Set cURL options
            curl_setopt($ch, CURLOPT_URL, $url); // Replace with your API endpoint URL
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . self::getToken(),
            ]);

            // Execute the cURL request
            $response = curl_exec($ch);
            // Check for cURL errors
            if (curl_errno($ch)) {
                return false;
            } else {
                $response = json_decode($response);
                if (isset($response->data, $response->data->attributes)) {
                    return true;
                }

                return false;
            }

            // Close cURL session
            curl_close($ch);
        }

        return false;
    }

    /**
     * check if the user is a prestashop user
     *
     * @return bool
     */
    public static function isPrestashopUser()
    {
        $reg_source = \Configuration::get('CHATY_USER_REG_SOURCE');

        return strtolower($reg_source) === 'prestashop';
    }

    /**
     * remove user
     *
     * @return void
     */
    public static function removeUser()
    {
        \Configuration::deleteByName('CHATY_USER_IDENTIFIER');
        \Configuration::deleteByName('CHATY_USER_TOKEN');
        \Configuration::deleteByName('CHATY_USER_REG_SOURCE');
    }

    /**
     * save user
     *
     * @param string $user_identifier
     * @param string $user_token
     *
     * @return void
     */
    public static function saveUser($user_identifier, $user_token, $reg_source)
    {
        \Configuration::updateValue('CHATY_USER_IDENTIFIER', $user_identifier);
        \Configuration::updateValue('CHATY_USER_TOKEN', $user_token);
        \Configuration::updateValue('CHATY_USER_REG_SOURCE', $reg_source);
    }
}
