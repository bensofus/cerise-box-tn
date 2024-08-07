<?php  

namespace CE;

defined('_PS_VERSION_') or die;

use Context;

use Posthemes\Module\Poselements\WidgetHelper;

class PosTestimonialsWidget extends WidgetHelper { 
	public function getName() {
		return 'pos_testimonials';
	}

	public function getTitle() {
		return $this->l( 'Pos Testimonials', [], 'Admin.Global' );
	}
	
	public function getIcon() {
		return 'fa fa-address-card-o';
	}

	public function getCategories() {
		return [ 'posthemes' ];
	}

	protected function _registerControls() {
		
		//Tab Content
		$this->startControlsSection(
			'content_section',
			[
				'label' => $this->l( 'Testimonials list', [], 'Admin.Global' ),
				'tab' => ControlsManager::TAB_CONTENT,
			]
		);
			$sample = [
	            'content' => $this->l('Click edit button to change this text. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.'),
	            'image' => [
	                'url' => Utils::getPlaceholderImageSrc(),
	            ],
	            'name' => 'John Doe',
	            'title' => 'Designer',
	        ];
			$this->addControl(
            'slides',
	            [
	                'type' => ControlsManager::REPEATER,
	                'default' => [$sample, $sample, $sample],
	                'fields' => [
	                    [
	                        'name' => 'content',
	                        'label' => $this->l('Content'),
	                        'type' => ControlsManager::TEXTAREA,
	                        'rows' => '8',
	                        'default' => $this->l('List Item'),
	                    ],
	                    [
	                        'name' => 'image',
	                        'label' => $this->l('Add Image'),
	                        'type' => ControlsManager::MEDIA,
	                        'seo' => 'true',
	                        'default' => [
	                            'url' => Utils::getPlaceholderImageSrc(),
	                        ],
	                    ],
	                    [
	                        'name' => 'name',
	                        'label' => $this->l('Name'),
	                        'type' => ControlsManager::TEXT,
	                        'default' => 'John Doe',
	                    ],
	                    [
	                        'name' => 'title',
	                        'label' => $this->l('Job'),
	                        'type' => ControlsManager::TEXT,
	                        'default' => 'Designer',
	                    ],
	               
	                ],
	                'title_field' => '<# if (image.url) { #>' .
	                    '<img src="{{ elementor.imagesManager.getImageUrl(image) }}" class="ce-repeater-thumb"><# } #>' .
	                    '{{{ name || title || image.title || image.alt || image.url.split("/").pop() }}}',
	            ]
	        );
			 

		$this->endControlsSection();

		$this->startControlsSection(
			'layout_section',
			[
				'label' => $this->l( 'Layout', [], 'Admin.Global' ),
				'tab' => ControlsManager::TAB_CONTENT,
			]
		);
			
			$designs = array('1' => 'Design 1','2' => 'Design 2','3' => 'Design 3','4' => 'Design 4','5' => 'Design 5');
			$this->addControl(
				'design',
				[
					'label' => $this->l( 'Select design' ),
					'type' => ControlsManager::SELECT,
					'options' => $designs,
					'frontend_available' => true,
					'default' => '1'
				]
			);
			$this->addControl(
                'center_mode',
                [
                    'label'         => $this->l('Center mode'),
                    'type'          => \CE\ControlsManager::SWITCHER,
                    'default'       => 'no',
                    'label_on'      => $this->l('Yes'),
                    'label_off'     => $this->l('No'),
					'prefix_class' => 'slick-center-',
					'render_type' => 'template',
					'frontend_available' => true
                ]
            );
			$this->addControl(
				'center_padding',
				[
					'label' => $this->l( 'Center Padding'),
					'type' => ControlsManager::SLIDER,
					'size_units' => [ 'px' ],
					'range' => [
						'px' => [
							'min' => 20,
							'max' => 1000,
						],
					],
					'default' => [
							'size' => 300,
							'unit' => 'px',
						],
					'condition' => [
						'center_mode' => 'yes'
					],
				]
			);

			$this->addControl(
				'enable_slider',
				[
					'type' => ControlsManager::HIDDEN,
					'default' => 'yes'
				]
			);
		$this->endControlsSection();
		
		$this->addCarouselControls($this->getName() , 3);
		$this->startControlsSection(
			'section_style_testimonial_item',
			[
				'label' => $this->l( 'Item', [], 'Admin.Global' ),
				'tab' => ControlsManager::TAB_STYLE,
			]
		);

		$this->addControl(
			'item_bg',
			[
				'label' => $this->l( 'Backgound', [], 'Admin.Global' ),
				'type' => ControlsManager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .pos-testimonial .testimonial-item .testimonial-item-inner' => 'background: {{VALUE}};',
				],
			]
		);
		$this->addResponsiveControl(
			'item_align',
			[
				'label' => $this->l( 'Alignment' ),
				'type' => ControlsManager::CHOOSE,
				'options' => [
					'left' => [
						'title' => $this->l( 'Left' ),
						'icon' => 'fa fa-align-left',
					],
					'center' => [
						'title' => $this->l( 'Center' ),
						'icon' => 'fa fa-align-center',
					],
					'right' => [
						'title' => $this->l( 'Right' ),
						'icon' => 'fa fa-align-right',
					],
					'justify' => [
						'title' => $this->l( 'Justified' ),
						'icon' => 'fa fa-align-justify',
					],
				],
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .pos-testimonial .testimonial-item .testimonial-item-inner' => 'justify-content: {{VALUE}};',
				],
			]
		);
		$this->addControl(
			'item_padding',
			array(
				'label' => __('Item padding', 'elementor'),
				'type' => ControlsManager::DIMENSIONS,
				'size_units' => array('px', 'em', '%'),
				'selectors' => array(
					'{{WRAPPER}} .pos-testimonial .testimonial-item .testimonial-item-inner' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);
		$this->addControl(
			'item_margin',
			array(
				'label' => __('Item margin', 'elementor'),
				'type' => ControlsManager::DIMENSIONS,
				'size_units' => array('px', 'em', '%'),
				'selectors' => array(
					'{{WRAPPER}} .pos-testimonial .testimonial-item .testimonial-item-inner' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);
		$this->addGroupControl(
			GroupControlBorder::getType(),
			[
				'name' => 'item_border',
				'label' => $this->l('Border'),
				'selector' => '{{WRAPPER}} .pos-testimonial .testimonial-item .testimonial-item-inner',
			]
		);
		$this->addControl(
			'item_border_radius',
			[
				'label' => $this->l( 'Item Border Radius', [], 'Admin.Global' ),
				'type' => ControlsManager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .pos-testimonial .testimonial-item .testimonial-item-inner' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->addGroupControl(
            GroupControlBoxShadow::getType(),
            array(
                'name' => 'item_box_shadow',
                'selector' => '{{WRAPPER}} .pos-testimonial .testimonial-item .testimonial-item-inner',
            )
        );

		$this->endControlsSection();
		$this->startControlsSection(
			'section_style_testimonial_content',
			[
				'label' => $this->l( 'Content', [], 'Admin.Global' ),
				'tab' => ControlsManager::TAB_STYLE,
			]
		);
		$this->addResponsiveControl(
			'align',
			[
				'label' => $this->l( 'Alignment' ),
				'type' => ControlsManager::CHOOSE,
				'options' => [
					'left' => [
						'title' => $this->l( 'Left' ),
						'icon' => 'fa fa-align-left',
					],
					'center' => [
						'title' => $this->l( 'Center' ),
						'icon' => 'fa fa-align-center',
					],
					'right' => [
						'title' => $this->l( 'Right' ),
						'icon' => 'fa fa-align-right',
					],
					'justify' => [
						'title' => $this->l( 'Justified' ),
						'icon' => 'fa fa-align-justify',
					],
				],
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .tes-content' => 'text-align: {{VALUE}};',
				],
			]
		);
		$this->addControl(
			'content_color',
			[
				'label' => $this->l( 'Text Color', [], 'Admin.Global' ),
				'type' => ControlsManager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .pos-testimonial .testimonial-item .tes-content-wrapper .tes-content' => 'color: {{VALUE}};',
				],
			]
		);
		$this->addControl(
			'content_bg',
			[
				'label' => $this->l( 'Backgound', [], 'Admin.Global' ),
				'type' => ControlsManager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .pos-testimonial .testimonial-item .tes-content-wrapper' => 'background: {{VALUE}};',
				],
			]
		);
		$this->addResponsiveControl(
			'content_padding',
			array(
				'label' => __('Content padding', 'elementor'),
				'type' => ControlsManager::DIMENSIONS,
				'size_units' => array('px', 'em', '%'),
				'selectors' => array(
					'{{WRAPPER}} .pos-testimonial .testimonial-item .tes-content-wrapper' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);
		$this->addResponsiveControl(
			'content_margin',
			array(
				'label' => __('Content margin', 'elementor'),
				'type' => ControlsManager::DIMENSIONS,
				'size_units' => array('px', 'em', '%'),
				'selectors' => array(
					'{{WRAPPER}} .pos-testimonial .testimonial-item .tes-content-wrapper' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);
		
		$this->addControl(
			'content_border_radius',
			[
				'label' => $this->l( 'Content Border Radius', [], 'Admin.Global' ),
				'type' => ControlsManager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .pos-testimonial .testimonial-item .tes-content-wrapper' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->addGroupControl(
			GroupControlTypography::getType(),
			[
				'name' => 'content_typography',
				'selector' => '{{WRAPPER}} .pos-testimonial .tes-content',
			]
		);

		$this->endControlsSection();

		// Image.
		$this->startControlsSection(
			'section_style_testimonial_image',
			[
				'label' => $this->l( 'Image', [], 'Admin.Global' ),
				'tab' => ControlsManager::TAB_STYLE,
			]
		);

		$this->addResponsiveControl(
			'image_size',
			[
				'label' => $this->l( 'Image Size', [], 'Admin.Global' ),
				'type' => ControlsManager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 20,
						'max' => 400,
					],
				],
				'default' => [
                        'size' => 120,
                        'unit' => 'px',
                    ],
				'selectors' => [
					'{{WRAPPER}} .pos-testimonial .tes-img img' => 'width: {{SIZE}}{{UNIT}};height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->addGroupControl(
			GroupControlBorder::getType(),
			[
				'name' => 'image_border',
				'selector' => '{{WRAPPER}} .pos-testimonial .tes-img img',
				'separator' => 'before',
			]
		);

		$this->addControl(
			'image_border_radius',
			[
				'label' => $this->l( 'Border Radius', [], 'Admin.Global' ),
				'type' => ControlsManager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .pos-testimonial .tes-img img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->endControlsSection();

		// Name.
		$this->startControlsSection(
			'section_style_testimonial_name',
			[
				'label' => $this->l( 'Name', [], 'Admin.Global' ),
				'tab' => ControlsManager::TAB_STYLE,
			]
		);

		$this->addControl(
			'name_text_color',
			[
				'label' => $this->l( 'Text Color', [], 'Admin.Global' ),
				'type' => ControlsManager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .pos-testimonial .testimonial-item .tes-name' => 'color: {{VALUE}};',
				],
			]
		);

		$this->addGroupControl(
			GroupControlTypography::getType(),
			[
				'name' => 'name_typography',
				'selector' => '{{WRAPPER}} .pos-testimonial .testimonial-item .tes-name',
			]
		);

		$this->endControlsSection();

		// Job.
		$this->startControlsSection(
			'section_style_testimonial_job',
			[
				'label' => $this->l( 'Job', [], 'Admin.Global' ),
				'tab' => ControlsManager::TAB_STYLE,
			]
		);

		$this->addControl(
			'job_text_color',
			[
				'label' => $this->l( 'Text Color', [], 'Admin.Global' ),
				'type' => ControlsManager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .pos-testimonial .testimonial-item .tes-title' => 'color: {{VALUE}};',
				],
			]
		);

		$this->addGroupControl(
			GroupControlTypography::getType(),
			[
				'name' => 'job_typography',
				'selector' => '{{WRAPPER}} .pos-testimonial .testimonial-item .tes-title',
			]
		);
		$this->endControlsSection();
		$this->addNavigationStyle($this->getName());	

	}

	/**
	 * Render widget output on the frontend. 
  
	 */
	 
	protected function render() {

		$settings = $this->getSettings(); 
		$custom_navigation = '';
		if(($settings['navigation'] == 'both' && $settings['navigation_position'] == 'top') || ($settings['navigation'] == 'arrows' && $settings['arrows_position'] == 'top')){
			$custom_navigation = 'top';
		}
		if($settings['navigation'] == 'both' && $settings['navigation_position'] == 'bottom'){
			$custom_navigation = 'bottom';
		}
		//print_r($settings);die;
		// Data settings
        $slick_options = [
			'slidesToShow'   => ($settings['items']) ? (int)$settings['items'] : 4,
			'slidesToScroll' => ($settings['slides_to_scroll']) ? (int)$settings['slides_to_scroll'] : 1,
			'autoplay'       => ($settings['autoplay'] == 'yes') ? true : false,
			'autoplaySpeed'  => ($settings['autoplay_speed']) ? (int)$settings['autoplay_speed'] : 5000,
			'infinite'       => ($settings['infinite'] == 'yes') ? true : false,
			'pauseOnHover'   => ($settings['pause_on_hover'] == 'yes') ? true : false,
			'speed'          => ($settings['transition_speed']) ? (int)$settings['transition_speed'] : 500,
			'arrows'         => (($settings['navigation'] == 'arrows')|| ($settings['navigation'] == 'both')) ? true : false,
			'dots'           => (($settings['navigation'] == 'dots')|| ($settings['navigation'] == 'both')) ? true : false, 
			'rows'           => (int) $settings['rows'] ? $settings['rows'] : 1,
			'custom_navigation' => $custom_navigation,
			'centerMode' => ($settings['center_mode'] == 'yes') ? true : false,
			'centerPadding' => $settings['center_padding']['size'] . 'px',
		]; 

		$responsive = array();
		if($settings['responsive'] == 'default') {
			$responsive = $this->posDefaultResponsive((int)$settings['items']);
		}else{
			$default_responsive = $this->posDefaultResponsive((int)$settings['items']);
			$responsive = array(
				'xl' => $settings['items_laptop'] ? (int)$settings['items_laptop'] : $default_responsive['xl'],
				'lg' => $settings['items_landscape_tablet'] ? (int)$settings['items_landscape_tablet'] : $default_responsive['lg'],
				'md' => $settings['items_portrait_tablet'] ? (int)$settings['items_portrait_tablet'] : $default_responsive['md'],
				'sm' => $settings['items_landscape_mobile'] ? (int)$settings['items_landscape_mobile'] : $default_responsive['sm'],
				'xs' => $settings['items_portrait_mobile'] ? (int)$settings['items_portrait_mobile'] : $default_responsive['xs'],
				'xxs' => $settings['items_small_mobile'] ? (int)$settings['items_small_mobile'] : $default_responsive['xxs'],
			);
		}
		if($settings['slides_to_scroll'] == '1'){
			$scroll = true;
		}else{
			$scroll = false;
		}
		$slick_responsive = [
			'items_laptop'            => $responsive['xl'],
            'items_landscape_tablet'  => $responsive['lg'],
            'items_portrait_tablet'   => $responsive['md'],
            'items_landscape_mobile'  => $responsive['sm'],
            'items_portrait_mobile'   => $responsive['xs'],
            'items_small_mobile'      => $responsive['xxs'],
            'scroll' 				  => $scroll,
		];
		 
		$this->addRenderAttribute(
			'testimonial', 
			[
				'class' => ['pos-testimonial', 'slick-slider-block', 'layout-'.$settings['design'],'column-desktop-'. $responsive['xl'],'column-tablet-'. $responsive['md'],'column-mobile-'. $responsive['xs']],
				'data-slider_responsive' => json_encode($slick_responsive),
				'data-slider_options' => json_encode($slick_options),
			]
			
		);
		$html = '';
		if ( $settings['slides'] ) {
		
			$html .= '<div '. $this->getRenderAttributeString('testimonial') .'>';
				foreach ( $settings['slides'] as $item ) { 
					$has_content = $item['content'];
					$has_image = $item['image']['url'];
					$has_name = $item['name'];
					$has_title = $item['title'];
					
					if($settings['design'] == '1') {
						$html .= '<div class="testimonial-item">';
							$html .= '<div class="testimonial-item-inner style1">';
								$html .= '<div class="tes-img">';
									$html .= GroupControlImageSize::getAttachmentImageHtml($item, 'image', 'auto');
									
								$html .= '</div>';
								$html .= '<div class="tes-content-wrapper">';
									if($has_content){
										$html .= '<p class="tes-content">'. $item['content'] .'</p>';
									}
									$html .= '<div class="author">';
										if($has_name){
											$html .= '<p class="tes-name">'. $item['name'] .'</p>';
										}
										if($has_title){
											$html .= '<p class="tes-title">'. $item['title'] .'</p>';
										}
									$html .= '</div>';
								$html .= '</div>';
							$html .= '</div>	';			
						$html .= '</div>';
					}
					if($settings['design'] == '2') {
						$html .= '<div class="testimonial-item">';
							$html .= '<div class="testimonial-item-inner style2">';
								$html .= '<div class="tes-img">';
									$html .= GroupControlImageSize::getAttachmentImageHtml($item, 'image', 'auto');
									
								$html .= '</div>';
								$html .= '<div class="tes-content-wrapper">';
									if($has_content){
										$html .= '<p class="tes-content">'. $item['content'] .'</p>';
									}
									$html .= '<div class="author">';
										if($has_name){
											$html .= '<p class="tes-name">'. $item['name'] .'</p>';
										}
										if($has_title){
											$html .= '<p class="tes-title">'. $item['title'] .'</p>';
										}
									$html .= '</div>';
								$html .= '</div>';
							$html .= '</div>	';			
						$html .= '</div>';
					}
					if($settings['design'] == '3') {
						$html .= '<div class="testimonial-item">';
							$html .= '<div class="testimonial-item-inner style3">';
								$html .= '<div class="tes-content-wrapper">';
									if($has_content){
										$html .= '<p class="tes-content">'. $item['content'] .'</p>';
									}
								$html .= '</div>';
								$html .= '<div class="tes-img">';
									$html .= GroupControlImageSize::getAttachmentImageHtml($item, 'image', 'auto');
									
								$html .= '</div>';
								$html .= '<div class="author">';
									if($has_name){
										$html .= '<p class="tes-name">'. $item['name'] .'</p>';
									}
									if($has_title){
										$html .= '<p class="tes-title">'. $item['title'] .'</p>';
									}
								$html .= '</div>';
							$html .= '</div>	';			
						$html .= '</div>';
					}
					if($settings['design'] == '4') {
						$html .= '<div class="testimonial-item">';
							$html .= '<div class="testimonial-item-inner style4">';
								$html .= '<div class="tes-content-wrapper">';
									if($has_content){
										$html .= '<p class="tes-content">'. $item['content'] .'</p>';
									}
								$html .= '</div>';
								$html .= '<div class="tes-img">';
									$html .= GroupControlImageSize::getAttachmentImageHtml($item, 'image', 'auto');
									
								$html .= '</div>';
								$html .= '<div class="author">';
									if($has_name){
										$html .= '<p class="tes-name">'. $item['name'] .'</p>';
									}
									if($has_title){
										$html .= '<p class="tes-title">'. $item['title'] .'</p>';
									}
								$html .= '</div>';
							$html .= '</div>	';			
						$html .= '</div>';
					}
					if($settings['design'] == '5') {
						$html .= '<div class="testimonial-item">';
							$html .= '<div class="testimonial-item-inner style5">';
								$html .= '<div class="image-box">';
									$html .= '<div class="tes-img">';
										$html .= GroupControlImageSize::getAttachmentImageHtml($item, 'image', 'auto');
										
									$html .= '</div>';
									$html .= '<div class="author">';
										if($has_name){
											$html .= '<p class="tes-name">'. $item['name'] .'</p>';
										}
										if($has_title){
											$html .= '<p class="tes-title">'. $item['title'] .'</p>';
										}
									$html .= '</div>';
								$html .= '</div>';
								$html .= '<div class="tes-content-wrapper">';
									if($has_content){
										$html .= '<p class="tes-content">'. $item['content'] .'</p>';
									}
								$html .= '</div>';
							$html .= '</div>';			
						$html .= '</div>';
					}
				}
			$html .= '</div>';
			$html .= '<div class="slick-custom-navigation"><div class="slick-custom-arrows"></div></div>';
	    echo $html;

		}
	} 
	/**
     * Get translation for a given widget text
     *
     * @access protected
     *
     * @param string $string    String to translate
     *
     * @return string Translation
     */
    protected function l($string)
    {
        return translate($string, 'posthemeoptions', basename(__FILE__, '.php'));
    }
}