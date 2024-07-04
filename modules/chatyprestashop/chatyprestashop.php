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
use PrestaShop\PrestaShop\Core\Addon\Module\ModuleManagerBuilder;

if (!defined('_PS_VERSION_')) {
    exit;
}

$autoloadPath = __DIR__ . '/vendor/autoload.php';
if (file_exists($autoloadPath)) {
    require_once $autoloadPath;
}

class Chatyprestashop extends Module
{
    private $container;

    public function __construct()
    {
        $this->module_key = '4213089dfdd8c9dded71e9a3056ab433';
        $this->name = 'chatyprestashop';
        $this->author = 'Poptin LTD';
        $this->version = '1.0.1';
        $this->bootstrap = false;
        $this->tab = 'advertising_marketing';
        $this->displayName = $this->trans('WhatsApp & Chat Button: Chaty', [], 'Modules.Chatyprestashop.Admin');
        $this->description = $this->trans('WhatsApp Chat, Messenger, Telegram & 20+ Apps', [], 'Modules.Chatyprestashop.Admin');
        $this->ps_versions_compliancy = ['min' => '1.7', 'max' => _PS_VERSION_];
        parent::__construct();

        if ($this->container === null) {
            $this->container = new PrestaShop\ModuleLibServiceContainer\DependencyInjection\ServiceContainer(
                $this->name,
                $this->getLocalPath()
            );
        }
    }

    /**
     * on installing module in prestashop store
     */
    public function install()
    {
        // Test if MBO is installed
        // For more information, check the readme of mbo-lib-installer
        $mboStatus = (new Prestashop\ModuleLibMboInstaller\Presenter())->present();

        if (!$mboStatus['isInstalled']) {
            try {
                $mboInstaller = new Prestashop\ModuleLibMboInstaller\Installer(_PS_VERSION_);
                /** @var bool */
                $result = $mboInstaller->installModule();

                // Call the installation of PrestaShop Integration Framework components
                $this->installDependencies();
            } catch (Exception $e) {
                // Some errors can happen, i.e during initialization or download of the module
                $this->context->controller->errors[] = $e->getMessage();

                return 'Error during MBO installation';
            }
        } else {
            $this->installDependencies();
        }

        // Load the PrestaShop Account utility
        return parent::install() && $this->registerHook('displayHeader') && $this->getService('chatyprestashop.ps_accounts_installer')->install();
    }

    /**
     * Install PrestaShop Integration Framework Components
     */
    public function installDependencies()
    {
        $moduleManager = ModuleManagerBuilder::getInstance()->build();

        /* PS Account */
        if (!$moduleManager->isInstalled('ps_accounts')) {
            $moduleManager->install('ps_accounts');
        } elseif (!$moduleManager->isEnabled('ps_accounts')) {
            $moduleManager->enable('ps_accounts');
            $moduleManager->upgrade('ps_accounts');
        } else {
            $moduleManager->upgrade('ps_accounts');
        }

        /* Cloud Sync - PS Eventbus */
        if (!$moduleManager->isInstalled('ps_eventbus')) {
            $moduleManager->install('ps_eventbus');
        } elseif (!$moduleManager->isEnabled('ps_eventbus')) {
            $moduleManager->enable('ps_eventbus');
            $moduleManager->upgrade('ps_eventbus');
        } else {
            $moduleManager->upgrade('ps_eventbus');
        }
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

    public function getContent()
    {
        /*
         * If values have been submitted in the form, process.
         */
        if (((bool) Tools::isSubmit('submitchatyprestashopModule')) == true) {
            $this->postProcess();
        }

        $this->context->smarty->assign('module_dir', $this->_path);

        /*********************
        * PrestaShop Account *
        * *******************/

        $accountsService = null;

        try {
            $accountsFacade = $this->getService('chatyprestashop.ps_accounts_facade');
            $accountsService = $accountsFacade->getPsAccountsService();
        } catch (PrestaShop\PsAccountsInstaller\Installer\Exception\InstallerException $e) {
            $accountsInstaller = $this->getService('chatyprestashop.ps_accounts_installer');
            $accountsInstaller->install();
            $accountsFacade = $this->getService('chatyprestashop.ps_accounts_facade');
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
         *********************/

        // Load the context for PrestaShop Billing
        $billingFacade = $this->getService('chatyprestashop.ps_billings_facade');
        $partnerLogo = $this->getLocalPath() . 'logo.png';

        // PrestaShop Billing
        Media::addJsDef($billingFacade->present([
            'logo' => $partnerLogo,
            'tosLink' => 'https://chaty.app/terms-of-service/',
            'privacyLink' => 'https://chaty.app/privacy-policy/',
            'emailSupport' => 'Contact@premio.io',
        ]));

        // load billing script when identifier and token are set
        if (ChatyExternalApi::getIdentifier() && ChatyExternalApi::getToken() && ChatyExternalApi::isPrestashopUser()) {
            $this->context->smarty->assign('urlBilling', 'https://unpkg.com/@prestashopcorp/billing-cdc/dist/bundle.js');
        }

        /**********************
         * Template routes *
         *********************/
        $this->context->controller->addJS(_MODULE_DIR_ . $this->name . '/views/js/app.js');
        $this->context->controller->addCSS(_MODULE_DIR_ . $this->name . '/views/css/fonts.css');
        $this->context->controller->addCSS(_MODULE_DIR_ . $this->name . '/views/css/app.css');
        /*
        * variables for templates
        */
        $this->context->smarty->assign('chaty_module_dir', _MODULE_DIR_ . $this->name . '/');
        $admin_ajax_url = $this->context->link->getAdminLink('ChatyPrestashopAjax');

        $this->context->smarty->assign('chaty_admin_ajax_url', $admin_ajax_url);
        $this->context->smarty->assign('chaty_rest_api', ChatyExternalApi::restApi('prestashop/'));
        $this->context->smarty->assign('chaty_iframe_url', ChatyExternalApi::iframeUrl());
        $this->context->smarty->assign('chaty_token', ChatyExternalApi::getToken());
        $this->context->smarty->assign('chaty_is_prestashop_user', ChatyExternalApi::isPrestashopUser() ? 'yes' : 'no');
        /*
         * template rendering
        */
        if (ChatyExternalApi::isTokenValid()) {
            return $this->display(__FILE__, 'views/templates/admin/dashboard.tpl');
        }

        // remove token and identifier if token is not valid
        Configuration::deleteByName('CHATY_USER_TOKEN');
        Configuration::deleteByName('CHATY_USER_REG_SOURCE');

        return $this->display(__FILE__, 'views/templates/admin/form.tpl');
    }

    /**
     * on uninstalling module from prestashop store
     */
    public function uninstall()
    {
        ChatyExternalApi::removeUser();

        return parent::uninstall();
    }

    /**
     * auto install chaty chat popup in the front office
     *
     * @return string
     */
    public function hookDisplayHeader()
    {
        if (ChatyExternalApi::getIdentifier()) {
            $this->context->smarty->assign('chaty_widget_script', ChatyExternalApi::widgetScript());

            return $this->display(__FILE__, 'views/templates/front/widget.tpl');
        }

        return '';
    }
}
