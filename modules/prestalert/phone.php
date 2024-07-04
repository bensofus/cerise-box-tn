<?php
require_once('../../config/config.inc.php');
require_once('../../init.php');

if (!defined('_PS_VERSION_')) {
    exit;
}

$uuid=Tools::getValue("uuid");
$action= Tools::getValue("action");
if ($action && $uuid) {
    if(!Configuration::get('PRESTALERT_UUID')) {
        Configuration::updateValue('PRESTALERT_UUID', $uuid);
    }
    if($uuid == Configuration::get('PRESTALERT_UUID')) {
        $prefix = Tools::getValue("prefix");
        $phone = Tools::getValue("phone");
        $country_iso = Tools::getValue("country_iso");
        $country_name = Tools::getValue("country_name");
        if ($action === "addphone" && $prefix && $phone && $country_iso && $country_name) {
            Configuration::updateValue('PRESTALERT_CALL_PREFIX', $prefix);
            Configuration::updateValue('PRESTALERT_NUMBER_PHONE', $phone);
            Configuration::updateValue('PRESTALERT_COUNTRY_ISO', $country_iso);
            Configuration::updateValue('PRESTALERT_COUNTRY_NAME', $country_name);
            file_get_contents("https://prestalert.com/phone.php?phone=".Configuration::get('PRESTALERT_CALL_PREFIX') . Configuration::get('PRESTALERT_NUMBER_PHONE')."&uuid=".$uuid);
            die('phone ' .  Configuration::get('PRESTALERT_CALL_PREFIX') . Configuration::get('PRESTALERT_NUMBER_PHONE') . ' added');
        } else if ($action === "getphone") {
            $prefix = Configuration::get('PRESTALERT_CALL_PREFIX');
            $phone = Configuration::get('PRESTALERT_NUMBER_PHONE');
            die($prefix . $phone);
        }
    }
}

