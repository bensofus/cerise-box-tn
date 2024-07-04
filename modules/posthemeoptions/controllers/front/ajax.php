<?php

class PosThemeoptionsAjaxModuleFrontController extends ModuleFrontController
{
	
    public function init()
    {
        parent::init();
    }

	public function postProcess()
    {
        parent::initContent();

        $tab_data = Tools::getValue('tabData');
        $listing = $tab_data['listing'];
        $order_by = $tab_data['order_by'];
        $order_dir = $tab_data['order_dir'];
        $limit = $tab_data['limit'];
        $id_category = $tab_data['category_id'];
        $products = $tab_data['products'];

        $products = $this->module->getProducts($listing, $order_by, $order_dir, $limit, $id_category, $products);
        $this->context->smarty->assign(array(
            'specific_layout' => $tab_data['specific_layout'],
            'carousel_active' => $tab_data['carousel_active'],
            'products' => $products,
            'tab_class' => $tab_data['tab_class'],
            'theme_template_path' => $tab_data['theme_template_path'],
            'slick_options' => $tab_data['slick_options'],
            'slick_responsive' => $tab_data['slick_responsive'],
            'columns_desktop' => $tab_data['columns_desktop'],
            'columns_tablet' => $tab_data['columns_tablet'],
            'columns_mobile' => $tab_data['columns_mobile'],
        ));
        $template = _PS_MODULE_DIR_ . 'posthemeoptions/views/templates/front/producttabs-ajax.tpl';

        if (!$template){
            $template = $this->module->l('No template found', 'ajax');
        }

        $this->ajaxDie(array(
            'html' => $this->context->smarty->fetch($template)
        ));

    }

    protected function ajaxDie($value = null, $controller = null, $method = null)
    {
        if (null === $controller) {
            $controller = get_class($this);
        }
        if (null === $method) {
            $bt = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 2);
            $method = $bt[1]['function'];
        }
        
        Hook::exec('actionAjaxDie' . $controller . $method . 'Before', ['value' => $value]);
        
        header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');

        die(json_encode($value));
    }

    
}
