<?php
/**
* 2007-2023 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author    PrestaShop SA <contact@prestashop.com>
*  @copyright 2007-2023 PrestaShop SA
*  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/

use PrestaShop\PrestaShop\Core\Addon\Module\ModuleManagerBuilder;

if (!defined('_PS_VERSION_')) {
    exit;
}

$autoloadPath = __DIR__ . '/vendor/autoload.php';
if (file_exists($autoloadPath)) {
    require_once $autoloadPath;
}

class prestalert extends Module
{
    protected $config_form = false;

    private $container;

    public function __construct()
    {
        $this->name = 'prestalert';
        $this->tab = 'administration';
        $this->version = '1.0.1';
        $this->author = 'Artdinamica';
        $this->module_key = '15bf0558687ea79646131fad889c2403';
        $this->need_instance = 0;

        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->l('PrestAlert');
        $this->description = $this->l('Artdinamica Web Monitoring Alerts Module.');

        $this->ps_versions_compliancy = array('min' => '1.7.0', 'max' => _PS_VERSION_);

        if ($this->container === null) {
            $this->container = new \PrestaShop\ModuleLibServiceContainer\DependencyInjection\ServiceContainer(
                $this->name,
                $this->getLocalPath()
            );
        }
    }

    public function install()
    {
        // Test if MBO is installed
        // For more information, check the readme of mbo-lib-installer
        $mboStatus = (new Prestashop\ModuleLibMboInstaller\Presenter)->present();

        if(!$mboStatus["isInstalled"])
        {
            try {
                $mboInstaller = new Prestashop\ModuleLibMboInstaller\Installer(_PS_VERSION_);
                /** @var boolean */
               $result = $mboInstaller->installModule();

               // Call the installation of PrestaShop Integration Framework components
               $this->installDependencies();
            } catch (\Exception $e) {
                // Some errors can happen, i.e during initialization or download of the module
                $this->context->controller->errors[] = $e->getMessage();
                return 'Error during MBO installation';
            }
        }
        else {
            $this->installDependencies();
        }

        if ($this->installSql()) {
            return (parent::install() && $this->registerHook('dashboardZoneTwo'));
        } else {
            return 'Error during SQL installation';
        }
    }

    /**
     * Install tables in database
     */
    public function installSql()
    {
        $success = true;

        $sql[] = 'CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'prestalert_urls` (
                    `id_url` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
                    `url` VARCHAR(255) NOT NULL,
                    `date_add` DATETIME NOT NULL,
                    `date_upd` DATETIME NOT NULL,
                    PRIMARY KEY (`id_url`)
                ) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8;';

        foreach ($sql as $query) {
            if (Db::getInstance()->execute($query) == false) {
                $success = false;
            }
        }

        if($success) {
            $home_url = Tools::getHttpHost(true).__PS_BASE_URI__;
            $date = date('Y-m-d H:i:s');
            $data = array(
                'url' => $home_url,
                'date_add' => $date,
                'date_upd' => $date
            );

            //Insert home url by default
            Db::getInstance()->insert('prestalert_urls', $data);
        }

        return $success;
    }

    /**
     * Install PrestaShop Integration Framework Components
     */
    public function installDependencies()
    {
        $moduleManager = ModuleManagerBuilder::getInstance()->build();

        /* PS Account */
        if (!$moduleManager->isInstalled("ps_accounts")) {
            $moduleManager->install("ps_accounts");
        } else if (!$moduleManager->isEnabled("ps_accounts")) {
            $moduleManager->enable("ps_accounts");
            $moduleManager->upgrade('ps_accounts');
        } else {
            $moduleManager->upgrade('ps_accounts');
        }

        /* Cloud Sync - PS Eventbus */
        if (!$moduleManager->isInstalled("ps_eventbus")) {
            $moduleManager->install("ps_eventbus");
        } else if (!$moduleManager->isEnabled("ps_eventbus")) {
            $moduleManager->enable("ps_eventbus");
            $moduleManager->upgrade('ps_eventbus');
        } else {
            $moduleManager->upgrade('ps_eventbus');
        }
    }

    public function uninstall()
    {
        return parent::uninstall();
    }

    /**
     * Load the configuration content
     */
    public function getContent()
    {
        $this->context->smarty->assign('module_dir', $this->_path);
        $moduleManager = ModuleManagerBuilder::getInstance()->build();
        $language = $this->context->language;
        $countries = Country::getCountries($language->id);
        $link = $this->context->link;
        $this->context->smarty->assign('link', $link);
        $this->context->smarty->assign('countries', $countries);

        if(!Configuration::get('PRESTALERT_COUNTRY_ISO')) {
            $default_country_iso = Country::getIsoById(Configuration::get('PS_COUNTRY_DEFAULT'));
            Configuration::updateValue('PRESTALERT_COUNTRY_ISO', $default_country_iso);
        }
        $this->context->smarty->assign('PRESTALERT_COUNTRY_ISO', Configuration::get('PRESTALERT_COUNTRY_ISO'));

        if(!Configuration::get('PRESTALERT_COUNTRY_NAME')) {
            $default_country_name = Country::getNameById($language->id, Configuration::get('PS_COUNTRY_DEFAULT'));
            Configuration::updateValue('PRESTALERT_COUNTRY_NAME', $default_country_name);
        }
        $this->context->smarty->assign('PRESTALERT_COUNTRY_NAME', Configuration::get('PRESTALERT_COUNTRY_NAME'));

        if(!Configuration::get('PRESTALERT_CALL_PREFIX')) {
            $default_country = new Country(Configuration::get('PS_COUNTRY_DEFAULT'));
            $default_call_prefix = $default_country->call_prefix;
            Configuration::updateValue('PRESTALERT_CALL_PREFIX', $default_call_prefix);
        }
        $this->context->smarty->assign('PRESTALERT_CALL_PREFIX', Configuration::get('PRESTALERT_CALL_PREFIX'));

        if(Configuration::get('PRESTALERT_NUMBER_PHONE')) {
            $this->context->smarty->assign('PRESTALERT_NUMBER_PHONE', Configuration::get('PRESTALERT_NUMBER_PHONE'));
        }

        $accountsService = null;

        try {
            $accountsFacade = $this->getService('prestalert.ps_accounts_facade');
            $accountsService = $accountsFacade->getPsAccountsService();
        } catch (\PrestaShop\PsAccountsInstaller\Installer\Exception\InstallerException $e) {
            $accountsInstaller = $this->getService('prestalert.ps_accounts_installer');
            $accountsInstaller->install();
            $accountsFacade = $this->getService('prestalert.ps_accounts_facade');
            $accountsService = $accountsFacade->getPsAccountsService();
        }

        try {
            Media::addJsDef([
                'contextPsAccounts' => $accountsFacade->getPsAccountsPresenter()
                    ->present($this->name),
            ]);

            // Retrieve Account CDN
            $this->context->smarty->assign('urlAccountsCdn', $accountsService->getAccountsCdn());

        } catch (Exception $e) {
            $this->context->controller->errors[] = $e->getMessage();
            return '';
        }

        if ($moduleManager->isInstalled("ps_eventbus")) {
            $eventbusModule =  \Module::getInstanceByName("ps_eventbus");
            if (version_compare($eventbusModule->version, '1.9.0', '>=')) {

                $eventbusPresenterService = $eventbusModule->getService('PrestaShop\Module\PsEventbus\Service\PresenterService');

                $this->context->smarty->assign('urlCloudsync', "https://assets.prestashop3.com/ext/cloudsync-merchant-sync-consent/latest/cloudsync-cdc.js");

                Media::addJsDef([
                    'contextPsEventbus' => $eventbusPresenterService->expose($this, ['info', 'modules', 'themes', 'carts','carriers','categories','currencies','customers','orders','products','stocks','stores','taxonomies', 'wishlists'])
                ]);
            }
        }

        /**********************
         * PrestaShop Billing *
         * *******************/

        // Load context for PsBilling
        $billingFacade = $this->getService('prestalert.ps_billings_facade');
        $partnerLogo = $this->getLocalPath() . 'logo.png';

        // Billing
        Media::addJsDef($billingFacade->present([
            'logo' => $partnerLogo,
            'tosLink' => 'https://artdinamica.com/aviso-legal.html',
            'privacyLink' => 'https://artdinamica.com/aviso-legal.html',
            'emailSupport' => 'info@artdinamica.com',
        ]));

        $this->context->smarty->assign('urlBilling', "https://unpkg.com/@prestashopcorp/billing-cdc/dist/bundle.js");
        $output = $this->context->smarty->fetch($this->local_path.'views/templates/admin/configure.tpl');
        return $output;
    }

    /**
     * Retrieve service
     *
     * @param string $serviceName
     *
     * @return mixed
     */
    public function getService($serviceName)
    {
        return $this->container->getService($serviceName);
    }

    public function hookDashboardZoneTwo() {
        try {
            $accountsFacade = $this->getService('prestalert.ps_accounts_facade');
            $accountsService = $accountsFacade->getPsAccountsService();
        } catch (\PrestaShop\PsAccountsInstaller\Installer\Exception\InstallerException $e) {
            $accountsInstaller = $this->getService('prestalert.ps_accounts_installer');
            $accountsInstaller->install();
            $accountsFacade = $this->getService('prestalert.ps_accounts_facade');
            $accountsService = $accountsFacade->getPsAccountsService();
        }

        try {
            Media::addJsDef([
                'contextPsAccounts' => $accountsFacade->getPsAccountsPresenter()
                    ->present($this->name),
            ]);

            // Retrieve Account CDN
            $this->context->smarty->assign('urlAccountsCdn', $accountsService->getAccountsCdn());

        } catch (Exception $e) {
            $this->context->controller->errors[] = $e->getMessage();
            return '';
        }

        return $this->context->smarty->fetch($this->local_path.'views/templates/admin/dashboard1.tpl');
    }
}
