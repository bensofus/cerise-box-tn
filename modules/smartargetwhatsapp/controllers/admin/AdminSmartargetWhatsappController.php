<?php
/**
 * This file provides the Smartarget Whatsapp module for PrestaShop.
 *
 * @author Smartarget
 * @copyright Smartarget 2023
 * @license MIT
 */
if (!defined('_PS_VERSION_')) {
    exit;
}
class AdminSmartargetWhatsappController extends ModuleAdminController
{
    public function __construct()
    {
        parent::__construct();
        Tools::redirectAdmin($this->context->link->getAdminLink('AdminModules', true, [], ['configure' => 'smartargetwhatsapp']));
    }
}
