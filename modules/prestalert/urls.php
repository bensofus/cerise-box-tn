<?php
require_once('../../config/config.inc.php');
require_once('../../init.php');

if (!defined('_PS_VERSION_')) {
    exit;
}

$uuid=Tools::getValue("uuid");

if ($action = Tools::getValue("action", false)) {
    if ($action === "add" && Tools::getValue("url")) {
        $max_urls = (int) Tools::getValue("max_urls");
        if($max_urls > 0) {
            $count_urls = Db::getInstance()->getValue('SELECT COUNT(*) FROM ' . _DB_PREFIX_ . 'prestalert_urls');
            if((int)$count_urls >= $max_urls) {
                die(json_encode(array("success" => false, "max_urls" => true)));
            }
        }
        $url = pSQL(Tools::getValue("url"));
        $domain = Tools::getValue("domain");

        if(strpos($url, $domain) === false) {
            die(json_encode(array("success" => false, "domain" => true)));
        }

        $date = date('Y-m-d H:i:s');

        $sql_exist = "SELECT url FROM " . _DB_PREFIX_ . "prestalert_urls WHERE url ='".pSQL($url)."' ";
        $exists = Db::getInstance()->getValue($sql_exist);
        $data = array(
            'url' => $url,
            'date_add' => $date,
            'date_upd' => $date
        );

        if (!$exists && Db::getInstance()->insert('prestalert_urls', $data) && $uuid) {
            $id = Db::getInstance()->Insert_ID();
            $response = array("success" => true, "id" => $id);
            // Después de insertar la URL, obtenemos todas las URLs nuevamente
            $urls_result = Db::getInstance()->executeS('SELECT * FROM ' . _DB_PREFIX_ . 'prestalert_urls ORDER BY id_url DESC');
            // Enviamos el JSON con todas las URLs a la URL de destino
            sendUrlsJson($urls_result, $uuid);
            echo json_encode($response);
        } else {
            echo json_encode(array("success" => false));
        }
    } elseif ($action === "delete" && Tools::getValue("id")) {
        $id = (int)Tools::getValue("id");
        if (Db::getInstance()->delete('prestalert_urls', 'id_url = ' . (int) $id) && $uuid) {
            // Después de eliminar la URL, obtenemos todas las URLs nuevamente
            $urls_result = Db::getInstance()->executeS('SELECT * FROM ' . _DB_PREFIX_ . 'prestalert_urls ORDER BY id_url DESC');
            // Enviamos el JSON con todas las URLs a la URL de destino
            sendUrlsJson($urls_result, $uuid);
            echo json_encode(array("success" => true));
        } else {
            echo json_encode(array("success" => false));
        }
    } elseif ($action === "get_urls") {
        // Consulta para obtener todas las URLs
        $result = Db::getInstance()->executeS('SELECT * FROM ' . _DB_PREFIX_ . 'prestalert_urls ORDER BY id_url DESC');
        if ($result) {
            echo json_encode(array("success" => true, "urls" => $result));
        } else {
            echo json_encode(array("success" => false));
        }
    }
}

function sendUrlsJson($urls_result, $uuid) {
    if ($urls_result) {
        // Construimos el JSON con las URLs
        $json_data = json_encode($urls_result);

        // URL de destino
        $url = 'https://prestalert.com/urls.php?uuid=' . $uuid;

        // Configuración de la solicitud
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $json_data);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        // Realizamos la solicitud
        $response = curl_exec($curl);

        // Verificamos si hay errores
        /*
        if ($response === false) {
          echo json_encode(array("success" => false, "error" => curl_error($curl)));
      } else {
          echo $response;
      }*/

        // Cerramos la solicitud
        curl_close($curl);
    } /* else {
        echo json_encode(array("success" => false));
    }*/
}

