<?php

use \CE\Plugin;

class AdminPosThemeoptionsController extends ModuleAdminController {

	private $images;
    private $templates;
    private $destination = _PS_IMG_DIR_.'cms/';
    private $parent_module = 'creativeelements';

    public function __construct()
    {
        parent::__construct();
        
        $this->templates = 'https://optima.posthemes.com/optima_data/';
		if ((bool)Tools::getValue('ajax')){
			$this->ajaxImportData(Tools::getValue('layout'));
		}else{
			Tools::redirectAdmin($this->context->link->getAdminLink('AdminModules').'&configure=posthemeoptions');
		}
        
    }

    function ajaxImportData($layout){
		$results = '';
    	require_once _PS_MODULE_DIR_.$this->parent_module.'/'.$this->parent_module.'.php';
    	$files = array(
    	'header-'.$layout.'.json', 'home-'.$layout.'.json', 'footer-'.$layout.'.json'
    	);

        $themeoption = 'posthemeoptions';
        $vegamenu = 'posvegamenu';
        
		foreach ($files as $file){
			$_FILES['file']['tmp_name'] = $this->templates. $layout. '/'. $file;
			$response = \CE\Plugin::instance()->templates_manager->importTemplate();

			if (is_object($response)){
				$this->ajaxDie(json_encode(array(
					'success' => false,
					'data' => [
						'message' => $this->l('Error!!! Reload and try again.'),
					]
				)));
			}
		}
        
        $prefixname  = 'posthemeoptions';
    	if($layout == 'fashion1' || $layout == 'fashion2' || $layout == 'fashion3' || $layout == 'fashion4' || $layout == 'fashion5' || $layout == 'fashion6' || $layout == 'fashion7' || $layout == 'fashion8'){
    		//Theme settings 
			$results .= $this->updateValue($themeoption . 'g_body_font_size', '16');
			$results .= $this->updateValue($themeoption . 'p_padding', '0');
			$results .= $this->updateValue($themeoption . 'p_border', '0');
			$results .= $this->updateValue($themeoption . 'g_body_bg_image', '');
			$results .= $this->updateValue($themeoption . 'g_body_bg_color', '');
			$results .= $this->updateValue($themeoption . 'layout', 'wide');
			$results .= $this->updateValue($themeoption . 'g_title_font_weight', '1');
			$results .= $this->updateValue($themeoption . 'g_dark', '0');
			$results .= $this->updateValue($themeoption . 'container_width', '');
			$results .= $this->updateValue($themeoption . 'sidebar', 'normal');
			$results .= $this->updateValue($themeoption . 'p_display', 5);
			$results .= $this->updateValue($themeoption . 'sticky_background', '#ffffff');
			$results .= $this->updateValue($themeoption . 'g_body_gfont_url', 'https://fonts.googleapis.com/css?family=Jost:300,300i,400,400i,500,600,700&display=swap&subset=cyrillic,cyrillic-ext,greek,greek-ext,latin-ext,vietnamese');
			$results .= $this->updateValue($themeoption . 'g_body_gfont_name', "Jost", sans-serif);
			$results .= $this->updateValue($themeoption . 'g_title_gfont_url', 'https://fonts.googleapis.com/css?family=Jost:300,300i,400,400i,500,600,700&display=swap&subset=cyrillic,cyrillic-ext,greek,greek-ext,latin-ext,vietnamese');
			$results .= $this->updateValue($themeoption . 'g_title_gfont_name', "Jost", sans-serif);
			if($layout == 'fashion1' || $layout == 'fashion2' || $layout == 'fashion3' || $layout == 'fashion4'){
				$results .= $this->updateValue($themeoption . 'g_main_color', '#313030');
				$results .= $this->updateValue($themeoption . 'p_name_colorh', '#313030');
			}else{
				$results .= $this->updateValue($themeoption . 'g_main_color', '#c42e19');
				$results .= $this->updateValue($themeoption . 'p_name_colorh', '#c42e19');
			}
			
			$results .= $this->updateValue($vegamenu . '_behaviour', 2);
			$results .= $this->updateValue('POSSEARCH_CATE', 0);
            $images = array();
    	}

    	if($layout == 'cosmetic1' || $layout == 'cosmetic2' || $layout == 'cosmetic3' || $layout == 'cosmetic4'){
    		//Theme settings
			$results .= $this->updateValue($themeoption . 'g_body_font_size', '16');
			$results .= $this->updateValue($themeoption . 'p_padding', '0');
			$results .= $this->updateValue($themeoption . 'p_border', '0');
			$results .= $this->updateValue($themeoption . 'g_body_bg_image', '');
			$results .= $this->updateValue($themeoption . 'g_body_bg_color', '');
			$results .= $this->updateValue($themeoption . 'layout', 'wide');
			$results .= $this->updateValue($themeoption . 'g_title_font_weight', '1');
			$results .= $this->updateValue($themeoption . 'g_dark', '0');
			$results .= $this->updateValue($themeoption . 'container_width', '');
			$results .= $this->updateValue($themeoption . 'sidebar', 'normal');
			$results .= $this->updateValue($themeoption . 'p_display', 5);
			$results .= $this->updateValue($themeoption . 'sticky_background', '#ffffff');
			$results .= $this->updateValue($themeoption . 'g_body_gfont_url', 'https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i,800,800i&display=swap&subset=cyrillic,cyrillic-ext,greek,greek-ext,latin-ext,vietnamese');
			$results .= $this->updateValue($themeoption . 'g_body_gfont_name', '"Open Sans", sans-serif');
			$results .= $this->updateValue($themeoption . 'g_title_gfont_url', 'https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i,800,800i&display=swap&subset=cyrillic,cyrillic-ext,greek,greek-ext,latin-ext,vietnamese');
			$results .= $this->updateValue($themeoption . 'g_title_gfont_name', '"Open Sans", sans-serif');
			$results .= $this->updateValue($themeoption . 'g_main_color', '#CC3B46');
			$results .= $this->updateValue($themeoption . 'p_name_colorh', '#CC3B46');
			$results .= $this->updateValue($vegamenu . '_behaviour', 2); 
            $images = array();
    	}
		if($layout == 'decoration1' || $layout == 'decoration2' || $layout == 'decoration3' || $layout == 'decoration4'){
    		//Theme settings
			$results .= $this->updateValue($themeoption . 'g_body_font_size', '16');
			$results .= $this->updateValue($themeoption . 'p_padding', '0');
			$results .= $this->updateValue($themeoption . 'p_border', '0');
			$results .= $this->updateValue($themeoption . 'g_body_bg_image', '');
			$results .= $this->updateValue($themeoption . 'g_body_bg_color', '');
			$results .= $this->updateValue($themeoption . 'layout', 'wide');
			$results .= $this->updateValue($themeoption . 'g_title_font_weight', '1');
			$results .= $this->updateValue($themeoption . 'g_dark', '0');
			$results .= $this->updateValue($themeoption . 'container_width', '');
			$results .= $this->updateValue($themeoption . 'boxed_width', '');
			$results .= $this->updateValue($themeoption . 'sidebar', 'normal');
			$results .= $this->updateValue($themeoption . 'p_display', 1);
			$results .= $this->updateValue($themeoption . 'sticky_background', '#0090f0');
			$results .= $this->updateValue($themeoption . 'g_body_gfont_url', 'https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i,800,800i&display=swap&subset=cyrillic,cyrillic-ext,greek,greek-ext,latin-ext,vietnamese');
			$results .= $this->updateValue($themeoption . 'g_body_gfont_name', '"Open Sans", sans-serif');
			$results .= $this->updateValue($themeoption . 'g_title_gfont_url', 'https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i,800,800i&display=swap&subset=cyrillic,cyrillic-ext,greek,greek-ext,latin-ext,vietnamese');
			$results .= $this->updateValue($themeoption . 'g_title_gfont_name', '"Open Sans", sans-serif');
			$results .= $this->updateValue($themeoption . 'g_main_color', '#DA2E1F');
			$results .= $this->updateValue($themeoption . 'p_name_colorh', '#DA2E1F');
			$results .= $this->updateValue($vegamenu . '_behaviour', 2); 
            $images = array();
    	}
		if($layout == 'pet1' || $layout == 'pet2' || $layout == 'pet3' || $layout == 'pet4'){
    		//Theme settings
			$results .= $this->updateValue($themeoption . 'layout', 'wide');
			$results .= $this->updateValue($themeoption . 'container_width', '1200px');
			$results .= $this->updateValue($themeoption . 'boxed_width', '');
			$results .= $this->updateValue($themeoption . 'sidebar', 'normal');
			$results .= $this->updateValue($themeoption . 'g_body_font_size', '13');
			$results .= $this->updateValue($themeoption . 'p_padding', '0');
			$results .= $this->updateValue($themeoption . 'p_border', '1');
			$results .= $this->updateValue($themeoption . 'p_display', 1);
			$results .= $this->updateValue($themeoption . 'sticky_background', '#FFFFFF');
			$results .= $this->updateValue($themeoption . 'g_body_gfont_url', 'https://fonts.googleapis.com/css?family=Libre+Franklin:300,300i,400,400i,500,500i,600,600i,700,700i,800,800i&display=swap');
			$results .= $this->updateValue($themeoption . 'g_body_gfont_name', '"Libre Franklin", sans-serif');
			$results .= $this->updateValue($themeoption . 'g_title_gfont_url', 'https://fonts.googleapis.com/css?family=Libre+Franklin:300,300i,400,400i,500,500i,600,600i,700,700i,800,800i&display=swap');
			$results .= $this->updateValue($themeoption . 'g_title_gfont_name', '"Libre Franklin", sans-serif');
			$results .= $this->updateValue($themeoption . 'g_main_color', '#39BFEF');
			$results .= $this->updateValue($themeoption . 'p_name_colorh', '#39BFEF');
			$results .= $this->updateValue($vegamenu . '_behaviour', 2); 
            $images = array();
    	}
		if($layout == 'toy1' || $layout == 'toy2' || $layout == 'toy3' || $layout == 'toy4'){
    		//Theme settings
			$results .= $this->updateValue($themeoption . 'layout', 'wide');
			$results .= $this->updateValue($themeoption . 'container_width', '1200px');
			$results .= $this->updateValue($themeoption . 'boxed_width', '');
			$results .= $this->updateValue($themeoption . 'sidebar', 'normal');
			$results .= $this->updateValue($themeoption . 'g_body_font_size', '13');
			$results .= $this->updateValue($themeoption . 'p_padding', '0');
			$results .= $this->updateValue($themeoption . 'p_border', '1');
			$results .= $this->updateValue($themeoption . 'p_display', 1);
			$results .= $this->updateValue($themeoption . 'sticky_background', '#FFFFFF');
			$results .= $this->updateValue($themeoption . 'g_body_gfont_url', 'https://fonts.googleapis.com/css?family=Libre+Franklin:300,300i,400,400i,500,500i,600,600i,700,700i,800,800i&display=swap');
			$results .= $this->updateValue($themeoption . 'g_body_gfont_name', '"Libre Franklin", sans-serif');
			$results .= $this->updateValue($themeoption . 'g_title_gfont_url', 'https://fonts.googleapis.com/css?family=Libre+Franklin:300,300i,400,400i,500,500i,600,600i,700,700i,800,800i&display=swap');
			$results .= $this->updateValue($themeoption . 'g_title_gfont_name', '"Libre Franklin", sans-serif');
			$results .= $this->updateValue($themeoption . 'g_main_color', '#116AEA');
			$results .= $this->updateValue($themeoption . 'p_name_colorh', '#116AEA');
			$results .= $this->updateValue($vegamenu . '_behaviour', 2); 
            $images = array();
    	}
		if($layout == 'sport1' || $layout == 'sport2' || $layout == 'sport3' || $layout == 'sport4'){
    		//Theme settings
			$results .= $this->updateValue($themeoption . 'layout', 'wide');
			$results .= $this->updateValue($themeoption . 'container_width', '1200px');
			$results .= $this->updateValue($themeoption . 'boxed_width', '');
			$results .= $this->updateValue($themeoption . 'sidebar', 'normal');
			$results .= $this->updateValue($themeoption . 'g_body_font_size', '13');
			$results .= $this->updateValue($themeoption . 'p_padding', '0');
			$results .= $this->updateValue($themeoption . 'p_border', '1');
			$results .= $this->updateValue($themeoption . 'p_display', 1);
			$results .= $this->updateValue($themeoption . 'sticky_background', '#FFFFFF');
			$results .= $this->updateValue($themeoption . 'g_body_gfont_url', 'https://fonts.googleapis.com/css?family=Libre+Franklin:300,300i,400,400i,500,500i,600,600i,700,700i,800,800i&display=swap');
			$results .= $this->updateValue($themeoption . 'g_body_gfont_name', '"Libre Franklin", sans-serif');
			$results .= $this->updateValue($themeoption . 'g_title_gfont_url', 'https://fonts.googleapis.com/css?family=Libre+Franklin:300,300i,400,400i,500,500i,600,600i,700,700i,800,800i&display=swap');
			$results .= $this->updateValue($themeoption . 'g_title_gfont_name', '"Libre Franklin", sans-serif');
			$results .= $this->updateValue($themeoption . 'g_main_color', '#7FB82B');
			$results .= $this->updateValue($themeoption . 'p_name_colorh', '#7FB82B');
			$results .= $this->updateValue($vegamenu . '_behaviour', 2); 
            $images = array();
    	}
		if($layout == 'sport1' || $layout == 'sport2' || $layout == 'sport3' || $layout == 'sport4'){
    		//Theme settings
			$results .= $this->updateValue($themeoption . 'layout', 'wide');
			$results .= $this->updateValue($themeoption . 'container_width', '1200px');
			$results .= $this->updateValue($themeoption . 'boxed_width', '');
			$results .= $this->updateValue($themeoption . 'sidebar', 'normal');
			$results .= $this->updateValue($themeoption . 'g_body_font_size', '13');
			$results .= $this->updateValue($themeoption . 'p_padding', '0');
			$results .= $this->updateValue($themeoption . 'p_border', '1');
			$results .= $this->updateValue($themeoption . 'p_display', 1);
			$results .= $this->updateValue($themeoption . 'sticky_background', '#FFFFFF');
			$results .= $this->updateValue($themeoption . 'g_body_gfont_url', 'https://fonts.googleapis.com/css?family=Libre+Franklin:300,300i,400,400i,500,500i,600,600i,700,700i,800,800i&display=swap');
			$results .= $this->updateValue($themeoption . 'g_body_gfont_name', '"Libre Franklin", sans-serif');
			$results .= $this->updateValue($themeoption . 'g_title_gfont_url', 'https://fonts.googleapis.com/css?family=Libre+Franklin:300,300i,400,400i,500,500i,600,600i,700,700i,800,800i&display=swap');
			$results .= $this->updateValue($themeoption . 'g_title_gfont_name', '"Libre Franklin", sans-serif');
			$results .= $this->updateValue($themeoption . 'g_main_color', '#7FB82B');
			$results .= $this->updateValue($themeoption . 'p_name_colorh', '#7FB82B');
			$results .= $this->updateValue($vegamenu . '_behaviour', 2); 
            $images = array();
    	}
    	if($layout == 'flower1' || $layout == 'flower2' || $layout == 'flower3' || $layout == 'flower4'){
    		//Theme settings
			$results .= $this->updateValue($themeoption . 'layout', 'wide');
			$results .= $this->updateValue($themeoption . 'container_width', '1200px');
			$results .= $this->updateValue($themeoption . 'boxed_width', '');
			$results .= $this->updateValue($themeoption . 'sidebar', 'normal');
			$results .= $this->updateValue($themeoption . 'g_body_font_size', '15');
			$results .= $this->updateValue($themeoption . 'p_padding', '0');
			$results .= $this->updateValue($themeoption . 'p_border', '1');
			$results .= $this->updateValue($themeoption . 'p_display', 1);
			$results .= $this->updateValue($themeoption . 'sticky_background', '#FFFFFF');
			$results .= $this->updateValue($themeoption . 'g_body_gfont_url', 'https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,500;0,600;0,700;1,400&display=swap');
			$results .= $this->updateValue($themeoption . 'g_body_gfont_name', '"Playfair Display", sans-serif');
			$results .= $this->updateValue($themeoption . 'g_title_gfont_url', 'https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,500;0,600;0,700;1,400&display=swap');
			$results .= $this->updateValue($themeoption . 'g_title_gfont_name', '"Playfair Display", sans-serif');
			$results .= $this->updateValue($themeoption . 'g_main_color', '#DB8678');
			$results .= $this->updateValue($themeoption . 'p_name_colorh', '#DB8678');
			$results .= $this->updateValue($vegamenu . '_behaviour', 2); 
            $images = array();
    	}
		if($layout == 'barber1' || $layout == 'barber2' || $layout == 'barber3'){
    		//Theme settings
			$results .= $this->updateValue($themeoption . 'layout', 'wide');
			$results .= $this->updateValue($themeoption . 'container_width', '1200px');
			$results .= $this->updateValue($themeoption . 'boxed_width', '');
			$results .= $this->updateValue($themeoption . 'sidebar', 'normal');
			$results .= $this->updateValue($themeoption . 'g_body_font_size', '13');
			$results .= $this->updateValue($themeoption . 'p_padding', '0');
			$results .= $this->updateValue($themeoption . 'p_border', '1');
			$results .= $this->updateValue($themeoption . 'p_display', 1);
			$results .= $this->updateValue($themeoption . 'sticky_background', '#151515');
			$results .= $this->updateValue($themeoption . 'g_body_gfont_url', 'https://fonts.googleapis.com/css?family=Libre+Franklin:300,300i,400,400i,500,500i,600,600i,700,700i,800,800i&display=swap');
			$results .= $this->updateValue($themeoption . 'g_body_gfont_name', '"Libre Franklin", sans-serif');
			$results .= $this->updateValue($themeoption . 'g_title_gfont_url', 'https://fonts.googleapis.com/css?family=Libre+Franklin:300,300i,400,400i,500,500i,600,600i,700,700i,800,800i&display=swap');
			$results .= $this->updateValue($themeoption . 'g_title_gfont_name', '"Libre Franklin", sans-serif');
			$results .= $this->updateValue($themeoption . 'g_main_color', '#E0A34C');
			$results .= $this->updateValue($themeoption . 'p_name_colorh', '#E0A34C');
			$results .= $this->updateValue($vegamenu . '_behaviour', 2); 
            $images = array();
    	}
		if($layout == 'barber4'){
    		//Theme settings
			$results .= $this->updateValue($themeoption . 'layout', 'wide');
			$results .= $this->updateValue($themeoption . 'container_width', '1200px');
			$results .= $this->updateValue($themeoption . 'boxed_width', '');
			$results .= $this->updateValue($themeoption . 'sidebar', 'normal');
			$results .= $this->updateValue($themeoption . 'g_body_font_size', '13');
			$results .= $this->updateValue($themeoption . 'p_padding', '0');
			$results .= $this->updateValue($themeoption . 'p_border', '1');
			$results .= $this->updateValue($themeoption . 'p_display', 1);
			$results .= $this->updateValue($themeoption . 'sticky_background', '#FFFFFF');
			$results .= $this->updateValue($themeoption . 'g_body_gfont_url', 'https://fonts.googleapis.com/css?family=Libre+Franklin:300,300i,400,400i,500,500i,600,600i,700,700i,800,800i&display=swap');
			$results .= $this->updateValue($themeoption . 'g_body_gfont_name', '"Libre Franklin", sans-serif');
			$results .= $this->updateValue($themeoption . 'g_title_gfont_url', 'https://fonts.googleapis.com/css?family=Libre+Franklin:300,300i,400,400i,500,500i,600,600i,700,700i,800,800i&display=swap');
			$results .= $this->updateValue($themeoption . 'g_title_gfont_name', '"Libre Franklin", sans-serif');
			$results .= $this->updateValue($themeoption . 'g_main_color', '#E0A34C');
			$results .= $this->updateValue($themeoption . 'p_name_colorh', '#E0A34C');
			$results .= $this->updateValue($vegamenu . '_behaviour', 2); 
            $images = array();
    	}
		if($layout == 'handmade1' || $layout == 'handmade2' || $layout == 'handmade3' || $layout == 'handmade4'){
    		//Theme settings
			$results .= $this->updateValue($themeoption . 'layout', 'wide');
			$results .= $this->updateValue($themeoption . 'container_width', '1200px');
			$results .= $this->updateValue($themeoption . 'boxed_width', '');
			$results .= $this->updateValue($themeoption . 'sidebar', 'normal');
			$results .= $this->updateValue($themeoption . 'g_body_font_size', '13');
			$results .= $this->updateValue($themeoption . 'p_padding', '0');
			$results .= $this->updateValue($themeoption . 'p_border', '1');
			$results .= $this->updateValue($themeoption . 'p_display', 1);
			$results .= $this->updateValue($themeoption . 'sticky_background', '#FFFFFF');
			$results .= $this->updateValue($themeoption . 'g_body_gfont_url', 'https://fonts.googleapis.com/css?family=Libre+Franklin:300,300i,400,400i,500,500i,600,600i,700,700i,800,800i&display=swap');
			$results .= $this->updateValue($themeoption . 'g_body_gfont_name', '"Libre Franklin", sans-serif');
			$results .= $this->updateValue($themeoption . 'g_title_gfont_url', 'https://fonts.googleapis.com/css?family=Libre+Franklin:300,300i,400,400i,500,500i,600,600i,700,700i,800,800i&display=swap');
			$results .= $this->updateValue($themeoption . 'g_title_gfont_name', '"Libre Franklin", sans-serif');
			$results .= $this->updateValue($themeoption . 'g_main_color', '#25BBDC');
			$results .= $this->updateValue($themeoption . 'p_name_colorh', '#25BBDC');
			$results .= $this->updateValue($vegamenu . '_behaviour', 2); 
            $images = array();
    	}
		if($layout == 'tools1' || $layout == 'tools3'){
    		//Theme settings
			$results .= $this->updateValue($themeoption . 'p_padding', '0');
			$results .= $this->updateValue($themeoption . 'p_border', '0');
			$results .= $this->updateValue($themeoption . 'layout', 'wide');
			$results .= $this->updateValue($themeoption . 'container_width', '');
			$results .= $this->updateValue($themeoption . 'boxed_width', '');
			$results .= $this->updateValue($themeoption . 'sidebar', 'normal');
			$results .= $this->updateValue($themeoption . 'g_body_font_size', '13');
			$results .= $this->updateValue($themeoption . 'p_display', 1);
			$results .= $this->updateValue($themeoption . 'sticky_background', '#ffffff');
			$results .= $this->updateValue($themeoption . 'g_body_gfont_url', 'https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i,800,800i&display=swap&subset=cyrillic,cyrillic-ext,greek,greek-ext,latin-ext,vietnamese');
			$results .= $this->updateValue($themeoption . 'g_body_gfont_name', '"Open Sans", sans-serif');
			$results .= $this->updateValue($themeoption . 'g_title_gfont_url', 'https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i,800,800i&display=swap&subset=cyrillic,cyrillic-ext,greek,greek-ext,latin-ext,vietnamese');
			$results .= $this->updateValue($themeoption . 'g_title_gfont_name', '"Open Sans", sans-serif');
			$results .= $this->updateValue($themeoption . 'g_main_color', '#FDCE23');
			$results .= $this->updateValue($themeoption . 'p_name_colorh', '#FDCE23');
			$results .= $this->updateValue($vegamenu . '_behaviour', 2);
			$results .= $this->updateValue('POSSEARCH_CATE', 0); 
            $images = array();
    	}
		if($layout == 'tools2'){
    		//Theme settings
			$results .= $this->updateValue($themeoption . 'p_padding', '0');
			$results .= $this->updateValue($themeoption . 'p_border', '0');
			$results .= $this->updateValue($themeoption . 'layout', 'wide');
			$results .= $this->updateValue($themeoption . 'container_width', '');
			$results .= $this->updateValue($themeoption . 'boxed_width', '');
			$results .= $this->updateValue($themeoption . 'sidebar', 'normal');
			$results .= $this->updateValue($themeoption . 'g_body_font_size', '13');
			$results .= $this->updateValue($themeoption . 'p_display', 1);
			$results .= $this->updateValue($themeoption . 'sticky_background', '#0D1316');
			$results .= $this->updateValue($themeoption . 'g_body_gfont_url', 'https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i,800,800i&display=swap&subset=cyrillic,cyrillic-ext,greek,greek-ext,latin-ext,vietnamese');
			$results .= $this->updateValue($themeoption . 'g_body_gfont_name', '"Open Sans", sans-serif');
			$results .= $this->updateValue($themeoption . 'g_title_gfont_url', 'https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i,800,800i&display=swap&subset=cyrillic,cyrillic-ext,greek,greek-ext,latin-ext,vietnamese');
			$results .= $this->updateValue($themeoption . 'g_title_gfont_name', '"Open Sans", sans-serif');
			$results .= $this->updateValue($themeoption . 'g_main_color', '#FDCE23');
			$results .= $this->updateValue($themeoption . 'p_name_colorh', '#FDCE23');
			$results .= $this->updateValue($vegamenu . '_behaviour', 2);
			$results .= $this->updateValue('POSSEARCH_CATE', 0); 
            $images = array();
    	}
		if($layout == 'tools4'){
    		//Theme settings
			$results .= $this->updateValue($themeoption . 'p_padding', '0');
			$results .= $this->updateValue($themeoption . 'p_border', '0');
			$results .= $this->updateValue($themeoption . 'layout', 'wide');
			$results .= $this->updateValue($themeoption . 'container_width', '');
			$results .= $this->updateValue($themeoption . 'boxed_width', '');
			$results .= $this->updateValue($themeoption . 'sidebar', 'normal');
			$results .= $this->updateValue($themeoption . 'g_body_font_size', '13');
			$results .= $this->updateValue($themeoption . 'p_display', 1);
			$results .= $this->updateValue($themeoption . 'sticky_background', '#253237');
			$results .= $this->updateValue($themeoption . 'g_body_gfont_url', 'https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i,800,800i&display=swap&subset=cyrillic,cyrillic-ext,greek,greek-ext,latin-ext,vietnamese');
			$results .= $this->updateValue($themeoption . 'g_body_gfont_name', '"Open Sans", sans-serif');
			$results .= $this->updateValue($themeoption . 'g_title_gfont_url', 'https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i,800,800i&display=swap&subset=cyrillic,cyrillic-ext,greek,greek-ext,latin-ext,vietnamese');
			$results .= $this->updateValue($themeoption . 'g_title_gfont_name', '"Open Sans", sans-serif');
			$results .= $this->updateValue($themeoption . 'g_main_color', '#FDCE23');
			$results .= $this->updateValue($themeoption . 'p_name_colorh', '#FDCE23');
			$results .= $this->updateValue($vegamenu . '_behaviour', 2);
			$results .= $this->updateValue('POSSEARCH_CATE', 0); 
            $images = array();
    	}
		
		if($layout == 'wine1'){
    		//Theme settings
			$results .= $this->updateValue($themeoption . 'layout', 'wide');
			$results .= $this->updateValue($themeoption . 'container_width', '1200px');
			$results .= $this->updateValue($themeoption . 'boxed_width', '');
			$results .= $this->updateValue($themeoption . 'sidebar', 'normal');
			$results .= $this->updateValue($themeoption . 'g_body_font_size', '13');
			$results .= $this->updateValue($themeoption . 'p_padding', '0');
			$results .= $this->updateValue($themeoption . 'p_border', '1');
			$results .= $this->updateValue($themeoption . 'p_display', 6);
			$results .= $this->updateValue($themeoption . 'sticky_background', '#FFFFFF');
			$results .= $this->updateValue($themeoption . 'g_body_gfont_url', 'https://fonts.googleapis.com/css?family=Libre+Franklin:300,300i,400,400i,500,500i,600,600i,700,700i,800,800i&display=swap');
			$results .= $this->updateValue($themeoption . 'g_body_gfont_name', '"Libre Franklin", sans-serif');
			$results .= $this->updateValue($themeoption . 'g_title_gfont_url', 'https://fonts.googleapis.com/css?family=Libre+Franklin:300,300i,400,400i,500,500i,600,600i,700,700i,800,800i&display=swap');
			$results .= $this->updateValue($themeoption . 'g_title_gfont_name', '"Libre Franklin", sans-serif');
			$results .= $this->updateValue($themeoption . 'g_main_color', '#A82049');
			$results .= $this->updateValue($themeoption . 'p_name_colorh', '#A82049');
			$results .= $this->updateValue($vegamenu . '_behaviour', 2); 
            $images = array();
    	}
		if($layout == 'wine2' || $layout == 'wine4'){
    		//Theme settings
			$results .= $this->updateValue($themeoption . 'layout', 'wide');
			$results .= $this->updateValue($themeoption . 'container_width', '1200px');
			$results .= $this->updateValue($themeoption . 'boxed_width', '');
			$results .= $this->updateValue($themeoption . 'sidebar', 'normal');
			$results .= $this->updateValue($themeoption . 'g_body_font_size', '13');
			$results .= $this->updateValue($themeoption . 'p_padding', '0');
			$results .= $this->updateValue($themeoption . 'p_border', '1');
			$results .= $this->updateValue($themeoption . 'p_display', 6);
			$results .= $this->updateValue($themeoption . 'sticky_background', '#151515');
			$results .= $this->updateValue($themeoption . 'g_body_gfont_url', 'https://fonts.googleapis.com/css?family=Libre+Franklin:300,300i,400,400i,500,500i,600,600i,700,700i,800,800i&display=swap');
			$results .= $this->updateValue($themeoption . 'g_body_gfont_name', '"Libre Franklin", sans-serif');
			$results .= $this->updateValue($themeoption . 'g_title_gfont_url', 'https://fonts.googleapis.com/css?family=Libre+Franklin:300,300i,400,400i,500,500i,600,600i,700,700i,800,800i&display=swap');
			$results .= $this->updateValue($themeoption . 'g_title_gfont_name', '"Libre Franklin", sans-serif');
			$results .= $this->updateValue($themeoption . 'g_main_color', '#A82049');
			$results .= $this->updateValue($themeoption . 'p_name_colorh', '#A82049');
			$results .= $this->updateValue($vegamenu . '_behaviour', 2); 
            $images = array();
    	}
		if($layout == 'wine3'){
    		//Theme settings
			$results .= $this->updateValue($themeoption . 'layout', 'wide');
			$results .= $this->updateValue($themeoption . 'container_width', '1200px');
			$results .= $this->updateValue($themeoption . 'boxed_width', '');
			$results .= $this->updateValue($themeoption . 'sidebar', 'normal');
			$results .= $this->updateValue($themeoption . 'g_body_font_size', '13');
			$results .= $this->updateValue($themeoption . 'p_padding', '0');
			$results .= $this->updateValue($themeoption . 'p_border', '1');
			$results .= $this->updateValue($themeoption . 'p_display', 6);
			$results .= $this->updateValue($themeoption . 'sticky_background', '#A82049');
			$results .= $this->updateValue($themeoption . 'g_body_gfont_url', 'https://fonts.googleapis.com/css?family=Libre+Franklin:300,300i,400,400i,500,500i,600,600i,700,700i,800,800i&display=swap');
			$results .= $this->updateValue($themeoption . 'g_body_gfont_name', '"Libre Franklin", sans-serif');
			$results .= $this->updateValue($themeoption . 'g_title_gfont_url', 'https://fonts.googleapis.com/css?family=Libre+Franklin:300,300i,400,400i,500,500i,600,600i,700,700i,800,800i&display=swap');
			$results .= $this->updateValue($themeoption . 'g_title_gfont_name', '"Libre Franklin", sans-serif');
			$results .= $this->updateValue($themeoption . 'g_main_color', '#A82049');
			$results .= $this->updateValue($themeoption . 'p_name_colorh', '#A82049');
			$results .= $this->updateValue($vegamenu . '_behaviour', 2); 
            $images = array();
    	}
		if($layout == 'watches1' || $layout == 'watches2' || $layout == 'watches3' || $layout == 'watches4'){
    		//Theme settings
			$results .= $this->updateValue($themeoption . 'layout', 'wide');
			$results .= $this->updateValue($themeoption . 'container_width', '1200px');
			$results .= $this->updateValue($themeoption . 'boxed_width', '');
			$results .= $this->updateValue($themeoption . 'sidebar', 'normal');
			$results .= $this->updateValue($themeoption . 'g_body_font_size', '14');
			$results .= $this->updateValue($themeoption . 'p_padding', '0');
			$results .= $this->updateValue($themeoption . 'p_border', '1');
			$results .= $this->updateValue($themeoption . 'p_display', 1);
			$results .= $this->updateValue($themeoption . 'sticky_background', '#FFFFFF');
			$results .= $this->updateValue($themeoption . 'g_body_gfont_url', 'https://fonts.googleapis.com/css?family=Libre+Franklin:300,300i,400,400i,500,500i,600,600i,700,700i,800,800i&display=swap');
			$results .= $this->updateValue($themeoption . 'g_body_gfont_name', '"Libre Franklin", sans-serif');
			$results .= $this->updateValue($themeoption . 'g_title_gfont_url', 'https://fonts.googleapis.com/css?family=Libre+Franklin:300,300i,400,400i,500,500i,600,600i,700,700i,800,800i&display=swap');
			$results .= $this->updateValue($themeoption . 'g_title_gfont_name', '"Libre Franklin", sans-serif');
			$results .= $this->updateValue($themeoption . 'g_main_color', '#E55022');
			$results .= $this->updateValue($themeoption . 'p_name_colorh', '#E55022');
			$results .= $this->updateValue($vegamenu . '_behaviour', 2); 
            $images = array();
    	}
    	if($layout == 'watches1' || $layout == 'watches2' || $layout == 'watches3' || $layout == 'watches4'){
    		//Theme settings
			$results .= $this->updateValue($themeoption . 'layout', 'wide');
			$results .= $this->updateValue($themeoption . 'container_width', '1200px');
			$results .= $this->updateValue($themeoption . 'boxed_width', '');
			$results .= $this->updateValue($themeoption . 'sidebar', 'normal');
			$results .= $this->updateValue($themeoption . 'g_body_font_size', '14');
			$results .= $this->updateValue($themeoption . 'p_padding', '0');
			$results .= $this->updateValue($themeoption . 'p_border', '1');
			$results .= $this->updateValue($themeoption . 'p_display', 1);
			$results .= $this->updateValue($themeoption . 'sticky_background', '#FFFFFF');
			$results .= $this->updateValue($themeoption . 'g_body_gfont_url', 'https://fonts.googleapis.com/css?family=Libre+Franklin:300,300i,400,400i,500,500i,600,600i,700,700i,800,800i&display=swap');
			$results .= $this->updateValue($themeoption . 'g_body_gfont_name', '"Libre Franklin", sans-serif');
			$results .= $this->updateValue($themeoption . 'g_title_gfont_url', 'https://fonts.googleapis.com/css?family=Libre+Franklin:300,300i,400,400i,500,500i,600,600i,700,700i,800,800i&display=swap');
			$results .= $this->updateValue($themeoption . 'g_title_gfont_name', '"Libre Franklin", sans-serif');
			$results .= $this->updateValue($themeoption . 'g_main_color', '#E55022');
			$results .= $this->updateValue($themeoption . 'p_name_colorh', '#E55022');
			$results .= $this->updateValue($vegamenu . '_behaviour', 2); 
            $images = array();
    	}
		if($layout == 'bag1' || $layout == 'bag2' || $layout == 'bag3'){
    		//Theme settings
			$results .= $this->updateValue($themeoption . 'layout', 'wide');
			$results .= $this->updateValue($themeoption . 'container_width', '1200px');
			$results .= $this->updateValue($themeoption . 'boxed_width', '');
			$results .= $this->updateValue($themeoption . 'sidebar', 'normal');
			$results .= $this->updateValue($themeoption . 'g_body_font_size', '14');
			$results .= $this->updateValue($themeoption . 'p_padding', '0');
			$results .= $this->updateValue($themeoption . 'p_border', '1');
			$results .= $this->updateValue($themeoption . 'p_display', 1);
			$results .= $this->updateValue($themeoption . 'sticky_background', '#FFFFFF');
			$results .= $this->updateValue($themeoption . 'g_body_gfont_url', 'https://fonts.googleapis.com/css?family=Libre+Franklin:300,300i,400,400i,500,500i,600,600i,700,700i,800,800i&display=swap');
			$results .= $this->updateValue($themeoption . 'g_body_gfont_name', '"Libre Franklin", sans-serif');
			$results .= $this->updateValue($themeoption . 'g_title_gfont_url', 'https://fonts.googleapis.com/css?family=Libre+Franklin:300,300i,400,400i,500,500i,600,600i,700,700i,800,800i&display=swap');
			$results .= $this->updateValue($themeoption . 'g_title_gfont_name', '"Libre Franklin", sans-serif');
			$results .= $this->updateValue($themeoption . 'g_main_color', '#33BCF5');
			$results .= $this->updateValue($themeoption . 'p_name_colorh', '#33BCF5');
			$results .= $this->updateValue($vegamenu . '_behaviour', 2); 
            $images = array();
    	}
		if($layout == 'bag4'){
    		//Theme settings
			$results .= $this->updateValue($themeoption . 'layout', 'wide');
			$results .= $this->updateValue($themeoption . 'container_width', '1200px');
			$results .= $this->updateValue($themeoption . 'boxed_width', '');
			$results .= $this->updateValue($themeoption . 'sidebar', 'normal');
			$results .= $this->updateValue($themeoption . 'g_body_font_size', '14');
			$results .= $this->updateValue($themeoption . 'p_padding', '0');
			$results .= $this->updateValue($themeoption . 'p_border', '1');
			$results .= $this->updateValue($themeoption . 'p_display', 1);
			$results .= $this->updateValue($themeoption . 'sticky_background', '#323232');
			$results .= $this->updateValue($themeoption . 'g_body_gfont_url', 'https://fonts.googleapis.com/css?family=Libre+Franklin:300,300i,400,400i,500,500i,600,600i,700,700i,800,800i&display=swap');
			$results .= $this->updateValue($themeoption . 'g_body_gfont_name', '"Libre Franklin", sans-serif');
			$results .= $this->updateValue($themeoption . 'g_title_gfont_url', 'https://fonts.googleapis.com/css?family=Libre+Franklin:300,300i,400,400i,500,500i,600,600i,700,700i,800,800i&display=swap');
			$results .= $this->updateValue($themeoption . 'g_title_gfont_name', '"Libre Franklin", sans-serif');
			$results .= $this->updateValue($themeoption . 'g_main_color', '#33BCF5');
			$results .= $this->updateValue($themeoption . 'p_name_colorh', '#33BCF5');
			$results .= $this->updateValue($vegamenu . '_behaviour', 2); 
            $images = array();
    	}
		if($layout == 'jewelry1' || $layout == 'jewelry2' || $layout == 'jewelry3' || $layout == 'jewelry4'){
    		//Theme settings
			$results .= $this->updateValue($themeoption . 'layout', 'wide');
			$results .= $this->updateValue($themeoption . 'container_width', '1200px');
			$results .= $this->updateValue($themeoption . 'boxed_width', '');
			$results .= $this->updateValue($themeoption . 'sidebar', 'normal');
			$results .= $this->updateValue($themeoption . 'g_body_font_size', '14');
			$results .= $this->updateValue($themeoption . 'p_padding', '0');
			$results .= $this->updateValue($themeoption . 'p_border', '1');
			$results .= $this->updateValue($themeoption . 'p_display', 1);
			$results .= $this->updateValue($themeoption . 'sticky_background', '#FFFFFF');
			$results .= $this->updateValue($themeoption . 'g_body_gfont_url', 'https://fonts.googleapis.com/css2?family=Rubik:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap');
			$results .= $this->updateValue($themeoption . 'g_body_gfont_name', '"Rubik", sans-serif');
			$results .= $this->updateValue($themeoption . 'g_title_gfont_url', 'https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,500;0,600;0,700;0,800;0,900;1,400;1,500;1,600;1,700;1,800;1,900&display=swap');
			$results .= $this->updateValue($themeoption . 'g_title_gfont_name', '"Playfair Display", sans-serif');
			$results .= $this->updateValue($themeoption . 'g_main_color', '#C09578');
			$results .= $this->updateValue($themeoption . 'p_name_colorh', '#C09578');
			$results .= $this->updateValue($vegamenu . '_behaviour', 2); 
            $images = array();
    	}
		if($layout == 'plant1' || $layout == 'plant2' || $layout == 'plant3' || $layout == 'plant4'){
    		//Theme settings
			$results .= $this->updateValue($themeoption . 'layout', 'wide');
			$results .= $this->updateValue($themeoption . 'container_width', '1200px');
			$results .= $this->updateValue($themeoption . 'boxed_width', '');
			$results .= $this->updateValue($themeoption . 'sidebar', 'normal');
			$results .= $this->updateValue($themeoption . 'g_body_font_size', '14');
			$results .= $this->updateValue($themeoption . 'p_padding', '0');
			$results .= $this->updateValue($themeoption . 'p_border', '1');
			$results .= $this->updateValue($themeoption . 'p_display', 1);
			$results .= $this->updateValue($themeoption . 'sticky_background', '#FFFFFF');
			$results .= $this->updateValue($themeoption . 'g_body_gfont_url', 'https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap');
			$results .= $this->updateValue($themeoption . 'g_body_gfont_name', '"Poppins", sans-serif');
			$results .= $this->updateValue($themeoption . 'g_title_gfont_url', 'https://fonts.googleapis.com/css2?family=Yesteryear&display=swap');
			$results .= $this->updateValue($themeoption . 'g_title_gfont_name', '"Yesteryear", sans-serif');
			$results .= $this->updateValue($themeoption . 'g_main_color', '#ABD373');
			$results .= $this->updateValue($themeoption . 'p_name_colorh', '#ABD373');
			$results .= $this->updateValue($vegamenu . '_behaviour', 2); 
            $images = array();
    	}
		if($layout == 'digital1' || $layout == 'digital2' || $layout == 'digital3' || $layout == 'digital4'){
    		//Theme settings
			$results .= $this->updateValue($themeoption . 'p_padding', '0');
			$results .= $this->updateValue($themeoption . 'p_border', '1');
			$results .= $this->updateValue($themeoption . 'layout', 'wide');
			$results .= $this->updateValue($themeoption . 'container_width', '1140px');
			$results .= $this->updateValue($themeoption . 'boxed_width', '');
			$results .= $this->updateValue($themeoption . 'sidebar', 'normal');
			$results .= $this->updateValue($themeoption . 'g_body_font_size', '14');
			$results .= $this->updateValue($themeoption . 'p_display', 6);
			$results .= $this->updateValue($themeoption . 'sticky_background', '#0090F0');
			$results .= $this->updateValue($themeoption . 'g_body_gfont_url', 'https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i,800,800i&display=swap&subset=cyrillic,cyrillic-ext,greek,greek-ext,latin-ext,vietnamese');
			$results .= $this->updateValue($themeoption . 'g_body_gfont_name', '"Open Sans", sans-serif');
			$results .= $this->updateValue($themeoption . 'g_title_gfont_url', 'https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i,800,800i&display=swap&subset=cyrillic,cyrillic-ext,greek,greek-ext,latin-ext,vietnamese');
			$results .= $this->updateValue($themeoption . 'g_title_gfont_name', '"Open Sans", sans-serif');
			$results .= $this->updateValue($themeoption . 'g_main_color', '#0090F0');
			$results .= $this->updateValue($themeoption . 'p_name_colorh', '#0090F0');
			$results .= $this->updateValue($vegamenu . '_behaviour', 2);
			$results .= $this->updateValue('POSSEARCH_CATE', 0); 
            $images = array();
    	}
		if($layout == 'digital1' || $layout == 'digital2' || $layout == 'digital4'){
    		//Theme settings
			$results .= $this->updateValue($themeoption . 'p_padding', '0');
			$results .= $this->updateValue($themeoption . 'p_border', '1');
			$results .= $this->updateValue($themeoption . 'layout', 'wide');
			$results .= $this->updateValue($themeoption . 'container_width', '1140px');
			$results .= $this->updateValue($themeoption . 'boxed_width', '');
			$results .= $this->updateValue($themeoption . 'sidebar', 'normal');
			$results .= $this->updateValue($themeoption . 'g_body_font_size', '14');
			$results .= $this->updateValue($themeoption . 'p_display', 6);
			$results .= $this->updateValue($themeoption . 'sticky_background', '#0090F0');
			$results .= $this->updateValue($themeoption . 'g_body_gfont_url', 'https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i,800,800i&display=swap&subset=cyrillic,cyrillic-ext,greek,greek-ext,latin-ext,vietnamese');
			$results .= $this->updateValue($themeoption . 'g_body_gfont_name', '"Open Sans", sans-serif');
			$results .= $this->updateValue($themeoption . 'g_title_gfont_url', 'https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i,800,800i&display=swap&subset=cyrillic,cyrillic-ext,greek,greek-ext,latin-ext,vietnamese');
			$results .= $this->updateValue($themeoption . 'g_title_gfont_name', '"Open Sans", sans-serif');
			$results .= $this->updateValue($themeoption . 'g_main_color', '#0090F0');
			$results .= $this->updateValue($themeoption . 'p_name_colorh', '#0090F0');
			$results .= $this->updateValue($vegamenu . '_behaviour', 2);
			$results .= $this->updateValue('POSSEARCH_CATE', 0); 
            $images = array();
    	}
		if($layout == 'digital3'){
    		//Theme settings
			$results .= $this->updateValue($themeoption . 'p_padding', '0');
			$results .= $this->updateValue($themeoption . 'p_border', '1');
			$results .= $this->updateValue($themeoption . 'layout', 'wide');
			$results .= $this->updateValue($themeoption . 'container_width', '1140px');
			$results .= $this->updateValue($themeoption . 'boxed_width', '');
			$results .= $this->updateValue($themeoption . 'sidebar', 'normal');
			$results .= $this->updateValue($themeoption . 'g_body_font_size', '14');
			$results .= $this->updateValue($themeoption . 'p_display', 6);
			$results .= $this->updateValue($themeoption . 'sticky_background', '#ffffff');
			$results .= $this->updateValue($themeoption . 'g_body_gfont_url', 'https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i,800,800i&display=swap&subset=cyrillic,cyrillic-ext,greek,greek-ext,latin-ext,vietnamese');
			$results .= $this->updateValue($themeoption . 'g_body_gfont_name', '"Open Sans", sans-serif');
			$results .= $this->updateValue($themeoption . 'g_title_gfont_url', 'https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i,800,800i&display=swap&subset=cyrillic,cyrillic-ext,greek,greek-ext,latin-ext,vietnamese');
			$results .= $this->updateValue($themeoption . 'g_title_gfont_name', '"Open Sans", sans-serif');
			$results .= $this->updateValue($themeoption . 'g_main_color', '#0090F0');
			$results .= $this->updateValue($themeoption . 'p_name_colorh', '#0090F0');
			$results .= $this->updateValue($vegamenu . '_behaviour', 2);
			$results .= $this->updateValue('POSSEARCH_CATE', 0); 
            $images = array();
    	}
		if($layout == 'furniture1' || $layout == 'furniture2' || $layout == 'furniture3'){
    		//Theme settings
			$results .= $this->updateValue($themeoption . 'p_padding', '0');
			$results .= $this->updateValue($themeoption . 'p_border', '1');
			$results .= $this->updateValue($themeoption . 'layout', 'wide');
			$results .= $this->updateValue($themeoption . 'container_width', '1430px');
			$results .= $this->updateValue($themeoption . 'boxed_width', '');
			$results .= $this->updateValue($themeoption . 'sidebar', 'normal');
			$results .= $this->updateValue($themeoption . 'g_body_font_size', '14');
			$results .= $this->updateValue($themeoption . 'p_display', 6);
			$results .= $this->updateValue($themeoption . 'sticky_background', '#ffffff');
			$results .= $this->updateValue($themeoption . 'g_body_gfont_url', 'https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap');
			$results .= $this->updateValue($themeoption . 'g_body_gfont_name', '"Poppins", sans-serif');
			$results .= $this->updateValue($themeoption . 'g_title_gfont_url', 'https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap');
			$results .= $this->updateValue($themeoption . 'g_title_gfont_name', '"Poppins", sans-serif');
			$results .= $this->updateValue($themeoption . 'g_main_color', '#EB2D2D');
			$results .= $this->updateValue($themeoption . 'p_name_colorh', '#EB2D2D');
			$results .= $this->updateValue($vegamenu . '_behaviour', 2);
			$results .= $this->updateValue('POSSEARCH_CATE', 0); 
            $images = array();
    	}
		if($layout == 'furniture4'){
    		//Theme settings
			$results .= $this->updateValue($themeoption . 'p_padding', '0');
			$results .= $this->updateValue($themeoption . 'p_border', '1');
			$results .= $this->updateValue($themeoption . 'layout', 'wide');
			$results .= $this->updateValue($themeoption . 'container_width', '1430px');
			$results .= $this->updateValue($themeoption . 'boxed_width', '');
			$results .= $this->updateValue($themeoption . 'sidebar', 'normal');
			$results .= $this->updateValue($themeoption . 'g_body_font_size', '14');
			$results .= $this->updateValue($themeoption . 'p_display', 6);
			$results .= $this->updateValue($themeoption . 'sticky_background', '#323232');
			$results .= $this->updateValue($themeoption . 'g_body_gfont_url', 'https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap');
			$results .= $this->updateValue($themeoption . 'g_body_gfont_name', '"Poppins", sans-serif');
			$results .= $this->updateValue($themeoption . 'g_title_gfont_url', 'https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap');
			$results .= $this->updateValue($themeoption . 'g_title_gfont_name', '"Poppins", sans-serif');
			$results .= $this->updateValue($themeoption . 'g_main_color', '#EB2D2D');
			$results .= $this->updateValue($themeoption . 'p_name_colorh', '#EB2D2D');
			$results .= $this->updateValue($vegamenu . '_behaviour', 2);
			$results .= $this->updateValue('POSSEARCH_CATE', 0); 
            $images = array();
    	}
		if($layout == 'organic1' || $layout == 'organic3' || $layout == 'organic4'){
    		//Theme settings
			$results .= $this->updateValue($themeoption . 'p_padding', '0');
			$results .= $this->updateValue($themeoption . 'p_border', '0');
			$results .= $this->updateValue($themeoption . 'layout', 'wide');
			$results .= $this->updateValue($themeoption . 'container_width', '1430px');
			$results .= $this->updateValue($themeoption . 'boxed_width', '');
			$results .= $this->updateValue($themeoption . 'sidebar', 'normal');
			$results .= $this->updateValue($themeoption . 'g_body_font_size', '14');
			$results .= $this->updateValue($themeoption . 'p_display', 6);
			$results .= $this->updateValue($themeoption . 'sticky_background', '#ffffff');
			$results .= $this->updateValue($themeoption . 'g_body_gfont_url', 'https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap');
			$results .= $this->updateValue($themeoption . 'g_body_gfont_name', '"Poppins", sans-serif');
			$results .= $this->updateValue($themeoption . 'g_title_gfont_url', 'https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap');
			$results .= $this->updateValue($themeoption . 'g_title_gfont_name', '"Poppins", sans-serif');
			$results .= $this->updateValue($themeoption . 'g_main_color', '#83BC2E');
			$results .= $this->updateValue($themeoption . 'p_name_colorh', '#83BC2E');
			$results .= $this->updateValue($vegamenu . '_behaviour', 2);
			$results .= $this->updateValue('POSSEARCH_CATE', 0); 
            $images = array();
    	}
		if($layout == 'organic2'){
    		//Theme settings
			$results .= $this->updateValue($themeoption . 'p_padding', '0');
			$results .= $this->updateValue($themeoption . 'p_border', '0');
			$results .= $this->updateValue($themeoption . 'layout', 'wide');
			$results .= $this->updateValue($themeoption . 'container_width', '1430px');
			$results .= $this->updateValue($themeoption . 'boxed_width', '');
			$results .= $this->updateValue($themeoption . 'sidebar', 'normal');
			$results .= $this->updateValue($themeoption . 'g_body_font_size', '14');
			$results .= $this->updateValue($themeoption . 'p_display', 6);
			$results .= $this->updateValue($themeoption . 'sticky_background', '#1D1D1D');
			$results .= $this->updateValue($themeoption . 'g_body_gfont_url', 'https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap');
			$results .= $this->updateValue($themeoption . 'g_body_gfont_name', '"Poppins", sans-serif');
			$results .= $this->updateValue($themeoption . 'g_title_gfont_url', 'https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap');
			$results .= $this->updateValue($themeoption . 'g_title_gfont_name', '"Poppins", sans-serif');
			$results .= $this->updateValue($themeoption . 'g_main_color', '#83BC2E');
			$results .= $this->updateValue($themeoption . 'p_name_colorh', '#83BC2E');
			$results .= $this->updateValue($vegamenu . '_behaviour', 2);
			$results .= $this->updateValue('POSSEARCH_CATE', 0); 
            $images = array();
    	}
		if($layout == 'marketplace1' || $layout == 'marketplace2'){
    		//Theme settings
			$results .= $this->updateValue($themeoption . 'p_padding', '0');
			$results .= $this->updateValue($themeoption . 'p_border', '0');
			$results .= $this->updateValue($themeoption . 'layout', 'wide');
			$results .= $this->updateValue($themeoption . 'container_width', '1740px');
			$results .= $this->updateValue($themeoption . 'boxed_width', '');
			$results .= $this->updateValue($themeoption . 'sidebar', 'narrow');
			$results .= $this->updateValue($themeoption . 'g_body_font_size', '14');
			$results .= $this->updateValue($themeoption . 'p_display', 6);
			$results .= $this->updateValue($themeoption . 'sticky_background', '#0662CA');
			$results .= $this->updateValue($themeoption . 'g_body_gfont_url', 'https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,300;1,400;1,500;1,600;1,700;1,800&display=swap');
			$results .= $this->updateValue($themeoption . 'g_body_gfont_name', '"Open Sans", sans-serif');
			$results .= $this->updateValue($themeoption . 'g_title_gfont_url', 'https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,300;1,400;1,500;1,600;1,700;1,800&display=swap');
			$results .= $this->updateValue($themeoption . 'g_title_gfont_name', '"Open Sans", sans-serif');
			$results .= $this->updateValue($themeoption . 'g_main_color', '#0662CA');
			$results .= $this->updateValue($themeoption . 'p_name_colorh', '#0662CA');
			$results .= $this->updateValue($vegamenu . '_behaviour', 2);
			$results .= $this->updateValue('POSSEARCH_CATE', 0); 
            $images = array();
    	}
		if($layout == 'marketplace3'){
    		//Theme settings
			$results .= $this->updateValue($themeoption . 'p_padding', '0');
			$results .= $this->updateValue($themeoption . 'p_border', '0');
			$results .= $this->updateValue($themeoption . 'layout', 'wide');
			$results .= $this->updateValue($themeoption . 'container_width', '1740px');
			$results .= $this->updateValue($themeoption . 'boxed_width', '');
			$results .= $this->updateValue($themeoption . 'sidebar', 'narrow');
			$results .= $this->updateValue($themeoption . 'g_body_font_size', '14');
			$results .= $this->updateValue($themeoption . 'p_display', 6);
			$results .= $this->updateValue($themeoption . 'sticky_background', '#232C38');
			$results .= $this->updateValue($themeoption . 'g_body_gfont_url', 'https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,300;1,400;1,500;1,600;1,700;1,800&display=swap');
			$results .= $this->updateValue($themeoption . 'g_body_gfont_name', '"Open Sans", sans-serif');
			$results .= $this->updateValue($themeoption . 'g_title_gfont_url', 'https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,300;1,400;1,500;1,600;1,700;1,800&display=swap');
			$results .= $this->updateValue($themeoption . 'g_title_gfont_name', '"Open Sans", sans-serif');
			$results .= $this->updateValue($themeoption . 'g_main_color', '#0662CA');
			$results .= $this->updateValue($themeoption . 'p_name_colorh', '#0662CA');
			$results .= $this->updateValue($vegamenu . '_behaviour', 2);
			$results .= $this->updateValue('POSSEARCH_CATE', 0); 
            $images = array();
    	}
		if($layout == 'marketplace4'){
    		//Theme settings
			$results .= $this->updateValue($themeoption . 'p_padding', '0');
			$results .= $this->updateValue($themeoption . 'p_border', '0');
			$results .= $this->updateValue($themeoption . 'layout', 'wide');
			$results .= $this->updateValue($themeoption . 'container_width', '1740px');
			$results .= $this->updateValue($themeoption . 'boxed_width', '');
			$results .= $this->updateValue($themeoption . 'sidebar', 'narrow');
			$results .= $this->updateValue($themeoption . 'g_body_font_size', '14');
			$results .= $this->updateValue($themeoption . 'p_display', 6);
			$results .= $this->updateValue($themeoption . 'sticky_background', '#F8D203');
			$results .= $this->updateValue($themeoption . 'g_body_gfont_url', 'https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,300;1,400;1,500;1,600;1,700;1,800&display=swap');
			$results .= $this->updateValue($themeoption . 'g_body_gfont_name', '"Open Sans", sans-serif');
			$results .= $this->updateValue($themeoption . 'g_title_gfont_url', 'https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,300;1,400;1,500;1,600;1,700;1,800&display=swap');
			$results .= $this->updateValue($themeoption . 'g_title_gfont_name', '"Open Sans", sans-serif');
			$results .= $this->updateValue($themeoption . 'g_main_color', '#0662CA');
			$results .= $this->updateValue($themeoption . 'p_name_colorh', '#0662CA');
			$results .= $this->updateValue($vegamenu . '_behaviour', 2);
			$results .= $this->updateValue('POSSEARCH_CATE', 0); 
            $images = array();
    	}
		if($layout == 'kitchen1' || $layout == 'kitchen2' || $layout == 'kitchen3' || $layout == 'kitchen4'){
    		//Theme settings
			$results .= $this->updateValue($themeoption . 'p_padding', '0');
			$results .= $this->updateValue($themeoption . 'p_border', '0');
			$results .= $this->updateValue($themeoption . 'layout', 'wide');
			$results .= $this->updateValue($themeoption . 'container_width', '1190px');
			$results .= $this->updateValue($themeoption . 'boxed_width', '');
			$results .= $this->updateValue($themeoption . 'sidebar', 'normal');
			$results .= $this->updateValue($themeoption . 'g_body_font_size', '14');
			$results .= $this->updateValue($themeoption . 'p_display', 6);
			$results .= $this->updateValue($themeoption . 'sticky_background', '#ffffff');
			$results .= $this->updateValue($themeoption . 'g_body_gfont_url', 'https://fonts.googleapis.com/css2?family=Rubik:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap');
			$results .= $this->updateValue($themeoption . 'g_body_gfont_name', '"Rubik", sans-serif');
			$results .= $this->updateValue($themeoption . 'g_title_gfont_url', 'https://fonts.googleapis.com/css2?family=Rubik:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap');
			$results .= $this->updateValue($themeoption . 'g_title_gfont_name', '"Rubik", sans-serif');
			$results .= $this->updateValue($themeoption . 'g_main_color', '#CC2121');
			$results .= $this->updateValue($themeoption . 'p_name_colorh', '#CC2121');
			$results .= $this->updateValue($vegamenu . '_behaviour', 2);
			$results .= $this->updateValue('POSSEARCH_CATE', 0); 
            $images = array();
    	}
		if($layout == 'shoes1' || $layout == 'shoes2'){
    		//Theme settings
			$results .= $this->updateValue($themeoption . 'p_padding', '0');
			$results .= $this->updateValue($themeoption . 'p_border', '0');
			$results .= $this->updateValue($themeoption . 'layout', 'wide');
			$results .= $this->updateValue($themeoption . 'container_width', '1190px');
			$results .= $this->updateValue($themeoption . 'boxed_width', '');
			$results .= $this->updateValue($themeoption . 'sidebar', 'normal');
			$results .= $this->updateValue($themeoption . 'g_body_font_size', '14');
			$results .= $this->updateValue($themeoption . 'p_display', 6);
			$results .= $this->updateValue($themeoption . 'sticky_background', '#ffffff');
			$results .= $this->updateValue($themeoption . 'g_body_gfont_url', 'https://fonts.googleapis.com/css2?family=Rubik:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap');
			$results .= $this->updateValue($themeoption . 'g_body_gfont_name', '"Rubik", sans-serif');
			$results .= $this->updateValue($themeoption . 'g_title_gfont_url', 'https://fonts.googleapis.com/css2?family=Rubik:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap');
			$results .= $this->updateValue($themeoption . 'g_title_gfont_name', '"Rubik", sans-serif');
			$results .= $this->updateValue($themeoption . 'g_main_color', '#CC2121');
			$results .= $this->updateValue($themeoption . 'p_name_colorh', '#CC2121');
			$results .= $this->updateValue($vegamenu . '_behaviour', 2);
			$results .= $this->updateValue('POSSEARCH_CATE', 0); 
            $images = array();
    	}
		if($layout == 'shoes3' || $layout == 'shoes4'){
    		//Theme settings
			$results .= $this->updateValue($themeoption . 'p_padding', '0');
			$results .= $this->updateValue($themeoption . 'p_border', '0');
			$results .= $this->updateValue($themeoption . 'layout', 'wide');
			$results .= $this->updateValue($themeoption . 'container_width', '1190px');
			$results .= $this->updateValue($themeoption . 'boxed_width', '');
			$results .= $this->updateValue($themeoption . 'sidebar', 'normal');
			$results .= $this->updateValue($themeoption . 'g_body_font_size', '14');
			$results .= $this->updateValue($themeoption . 'p_display', 6);
			$results .= $this->updateValue($themeoption . 'sticky_background', '#242424');
			$results .= $this->updateValue($themeoption . 'g_body_gfont_url', 'https://fonts.googleapis.com/css2?family=Rubik:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap');
			$results .= $this->updateValue($themeoption . 'g_body_gfont_name', '"Rubik", sans-serif');
			$results .= $this->updateValue($themeoption . 'g_title_gfont_url', 'https://fonts.googleapis.com/css2?family=Rubik:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap');
			$results .= $this->updateValue($themeoption . 'g_title_gfont_name', '"Rubik", sans-serif');
			$results .= $this->updateValue($themeoption . 'g_main_color', '#CC2121');
			$results .= $this->updateValue($themeoption . 'p_name_colorh', '#CC2121');
			$results .= $this->updateValue($vegamenu . '_behaviour', 2);
			$results .= $this->updateValue('POSSEARCH_CATE', 0); 
            $images = array();
    	}
		if($layout == 'book1' || $layout == 'book2' || $layout == 'book4'){
    		//Theme settings
			$results .= $this->updateValue($themeoption . 'p_padding', '0');
			$results .= $this->updateValue($themeoption . 'p_border', '0');
			$results .= $this->updateValue($themeoption . 'layout', 'wide');
			$results .= $this->updateValue($themeoption . 'container_width', '1190px');
			$results .= $this->updateValue($themeoption . 'boxed_width', '');
			$results .= $this->updateValue($themeoption . 'sidebar', 'normal');
			$results .= $this->updateValue($themeoption . 'g_body_font_size', '15');
			$results .= $this->updateValue($themeoption . 'p_display', 1);
			$results .= $this->updateValue($themeoption . 'sticky_background', '#ffffff');
			$results .= $this->updateValue($themeoption . 'g_body_gfont_url', 'https://fonts.googleapis.com/css?family=Libre+Franklin:300,300i,400,400i,500,500i,600,600i,700,700i,800,800i&display=swap');
			$results .= $this->updateValue($themeoption . 'g_body_gfont_name', '"Libre Franklin", sans-serif');
			$results .= $this->updateValue($themeoption . 'g_title_gfont_url', 'https://fonts.googleapis.com/css?family=Libre+Franklin:300,300i,400,400i,500,500i,600,600i,700,700i,800,800i&display=swap');
			$results .= $this->updateValue($themeoption . 'g_title_gfont_name', '"Libre Franklin", sans-serif');
			$results .= $this->updateValue($themeoption . 'g_main_color', '#DF2121');
			$results .= $this->updateValue($themeoption . 'p_name_colorh', '#DF2121');
			$results .= $this->updateValue($vegamenu . '_behaviour', 2);
			$results .= $this->updateValue('POSSEARCH_CATE', 0); 
            $images = array();
    	}
		if($layout == 'book3'){
    		//Theme settings
			$results .= $this->updateValue($themeoption . 'p_padding', '0');
			$results .= $this->updateValue($themeoption . 'p_border', '0');
			$results .= $this->updateValue($themeoption . 'layout', 'wide');
			$results .= $this->updateValue($themeoption . 'container_width', '1190px');
			$results .= $this->updateValue($themeoption . 'boxed_width', '');
			$results .= $this->updateValue($themeoption . 'sidebar', 'normal');
			$results .= $this->updateValue($themeoption . 'g_body_font_size', '15');
			$results .= $this->updateValue($themeoption . 'p_display', 1);
			$results .= $this->updateValue($themeoption . 'sticky_background', '#DF2121');
			$results .= $this->updateValue($themeoption . 'g_body_gfont_url', 'https://fonts.googleapis.com/css?family=Libre+Franklin:300,300i,400,400i,500,500i,600,600i,700,700i,800,800i&display=swap');
			$results .= $this->updateValue($themeoption . 'g_body_gfont_name', '"Libre Franklin", sans-serif');
			$results .= $this->updateValue($themeoption . 'g_title_gfont_url', 'https://fonts.googleapis.com/css?family=Libre+Franklin:300,300i,400,400i,500,500i,600,600i,700,700i,800,800i&display=swap');
			$results .= $this->updateValue($themeoption . 'g_title_gfont_name', '"Libre Franklin", sans-serif');
			$results .= $this->updateValue($themeoption . 'g_main_color', '#DF2121');
			$results .= $this->updateValue($themeoption . 'p_name_colorh', '#DF2121');
			$results .= $this->updateValue($vegamenu . '_behaviour', 2);
			$results .= $this->updateValue('POSSEARCH_CATE', 0); 
            $images = array();
    	}
        $error = false;
		if(!empty($images))
        foreach($images as $image){
            if(! $this->importImageFromURL($image, false)){
                $error = true;
            }
        }
	
    	$this->ajaxDie(json_encode(array(
            'success' => true,
			'content' => $results,
            'data' => [
                'message' => $error ? $this->l('Error with import images.') : $this->l('Import successfully !!!'),
            ]
        )));
    }

    protected function importImageFromURL($url, $regenerate = true)
    {
        $origin_image = pathinfo($url);
        $origin_name = $origin_image['filename'];
        $tmpfile = tempnam(_PS_TMP_IMG_DIR_, 'ps_import');
  
        $path = _PS_IMG_DIR_ . 'cms/';

        $url = urldecode(trim($url));
        $parced_url = parse_url($url);

        if (isset($parced_url['path'])) {
            $uri = ltrim($parced_url['path'], '/');
            $parts = explode('/', $uri);
            foreach ($parts as &$part) {
                $part = rawurlencode($part);
            }
            unset($part);
            $parced_url['path'] = '/' . implode('/', $parts);
        }

        if (isset($parced_url['query'])) {
            $query_parts = [];
            parse_str($parced_url['query'], $query_parts);
            $parced_url['query'] = http_build_query($query_parts);
        }

        if (!function_exists('http_build_url')) {
            require_once _PS_TOOL_DIR_ . 'http_build_url/http_build_url.php';
        }

        $url = http_build_url('', $parced_url);

        $orig_tmpfile = $tmpfile;

        if (Tools::copy($url, $tmpfile)) {
            // Evaluate the memory required to resize the image: if it's too much, you can't resize it.
            if (!ImageManager::checkImageMemoryLimit($tmpfile)) {
                @unlink($tmpfile);

                return false;
            }

            $tgt_width = $tgt_height = 0;
            $src_width = $src_height = 0;
            $error = 0;
            ImageManager::resize($tmpfile, $path . $origin_name .'.jpg', null, null, 'jpg', false, $error, $tgt_width, $tgt_height, 5, $src_width, $src_height);
   
        } else {
            echo 'cant copy image';
            @unlink($orig_tmpfile);

            return false;
        }
        unlink($orig_tmpfile);

        return true;
    }
	protected function updateValue($key, $value){
		$result = true;
		//echo $key . '----' .$idShopGroup . '----' .$idShop . '----' . $value . '<br>';
		$sql = 'UPDATE `' . _DB_PREFIX_ . 'configuration` 
				SET `value` = \''. $value .'\' , `date_upd` = NOW() 
				WHERE `name` = \''. $key .'\'';
		$result &= Db::getInstance()->execute($sql);

		return 'updated key='.$key.'value='.$value.'<br>';
	}
}
