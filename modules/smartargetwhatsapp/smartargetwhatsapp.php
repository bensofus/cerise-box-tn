<?php
/**
 * This file provides the Smartarget Whatsapp module for PrestaShop.
 *
 * @author Smartarget
 * @copyright Smartarget 2023
 * @license MIT
 */
use PrestaShop\ModuleLibServiceContainer\DependencyInjection\ServiceContainer;

if (!defined('_PS_VERSION_')) {
    exit;
}

$autoloadPath = __DIR__ . '/vendor/autoload.php';
if (file_exists($autoloadPath)) {
    require_once $autoloadPath;
}

class SmartargetWhatsapp extends Module
{
    /**
     * @var ServiceContainer
     */
    private $container;

    public function __construct()
    {
        $this->name = 'smartargetwhatsapp';
        $this->tab = 'front_office_features';
        $this->version = '1.0.2';
        $this->module_key = '5eb6a7309c6a047f19c53a9e4915f197';
        $this->author = 'Smartarget';
        $this->need_instance = 0;
        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->l('Smartarget Whatsapp - Contact Us');
        $this->description = $this->l('Allow customers to contact you using WhatsApp');

        $this->confirmUninstall = $this->l('Are you sure you want to uninstall?');

        if ($this->container === null) {
            $this->container = new \PrestaShop\ModuleLibServiceContainer\DependencyInjection\ServiceContainer(
                $this->name,
                $this->getLocalPath()
            );
        }
    }

    public function install()
    {
        parent::install();

        if (!$this->registerHook('displayHeader')) {
            return false;
        }

        $tab = new Tab();
        $tab->active = 1;
        $tab->class_name = 'AdminSmartargetWhatsapp';
        $tab->name = [];
        foreach (Language::getLanguages(true) as $lang) {
            $tab->name[$lang['id_lang']] = 'Smartarget Whatsapp';
        }

        // Set parent as the "Modules" section.
        $tab->id_parent = (int) Tab::getIdFromClassName('AdminParentModulesSf');
        $tab->module = $this->name;
        $tab->add();

        $this->getService('smartargetwhatsapp.ps_accounts_installer')->install();

        return true;
    }

    public function hookDisplayHeader()
    {
        $storefrontHash = sha1(sha1('prestashop_whatsapp_' . preg_replace("/www\.|https?:\/\/|\/$|\/?\?.+|\/.+|^\./", '', Tools::getShopDomain())) . '_script');
        $this->context->smarty->assign('storefrontHash', $storefrontHash);

        return $this->display(__FILE__, 'views/templates/hook/header.tpl');
    }

    public function getContent()
    {
        $adminHash = sha1('prestashop_whatsapp_' . preg_replace("/www\.|https?:\/\/|\/$|\/?\?.+|\/.+|^\./", '', Tools::getShopDomain()));
        $this->context->smarty->assign('adminHash', $adminHash);
        $this->context->smarty->assign('storefrontDomain', Tools::getShopDomain());
        $this->context->smarty->assign('prestashopAccount', json_encode([]));

        $this->context->smarty->assign('module_dir', $this->_path);

        /*********************
         * PrestaShop Account *
         * *******************/

        $accountsService = null;

        try {
            $accountsFacade = $this->getService('smartargetwhatsapp.ps_accounts_facade');
            $accountsService = $accountsFacade->getPsAccountsService();
        } catch (\PrestaShop\PsAccountsInstaller\Installer\Exception\InstallerException $e) {
            $accountsInstaller = $this->getService('smartargetwhatsapp.ps_accounts_installer');
            $accountsInstaller->install();
            $accountsFacade = $this->getService('smartargetwhatsapp.ps_accounts_facade');
            $accountsService = $accountsFacade->getPsAccountsService();
        }

        try {
            Media::addJsDef([
                'contextPsAccounts' => $accountsFacade->getPsAccountsPresenter()
                                                      ->present($this->name),
            ]);

            // Retrieve the PrestaShop Account CDN
            $this->context->smarty->assign('urlAccountsCdn', $accountsService->getAccountsCdn());
        } catch (Exception $e) {
            $this->context->controller->errors[] = $e->getMessage();

            return '';
        }

        /**********************
         * PrestaShop Billing *
         * *******************/

        // Load the context for PrestaShop Billing
        $billingFacade = $this->getService('smartargetwhatsapp.ps_billings_facade');
        $partnerLogo = $this->getLocalPath() . 'logo.png';

        // PrestaShop Billing
        Media::addJsDef($billingFacade->present([
            'logo' => $partnerLogo,
            'tosLink' => 'https://smartarget.online/page_terms.html',
            'privacyLink' => 'https://smartarget.online/page_privacy.html',
            'emailSupport' => 'support@smartarget.online',
        ]));

        $this->context->smarty->assign('urlBilling', 'https://unpkg.com/@prestashopcorp/billing-cdc/dist/bundle.js');

        $output = $this->context->smarty->fetch($this->local_path . 'views/templates/admin/configure.tpl');

        return $output;
    }

    /**
     * Retrieve the service
     *
     * @param string $serviceName
     *
     * @return mixed
     */
    public function getService($serviceName)
    {
        return $this->container->getService($serviceName);
    }

    public function uninstall()
    {
        try {
            $tabId = (int) Tab::getIdFromClassName('AdminSmartargetWhatsapp');
            if ($tabId) {
                $tab = new Tab($tabId);
                $tab->delete();
            }
        } catch (Exception $e) {
        }

        return parent::uninstall();
    }
}
