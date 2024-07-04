<?php

namespace CE;

defined('_PS_VERSION_') or die;

use Posthemes\Module\Poselements\WidgetHelper;

class PosIconBoxWidget extends WidgetBase
{
    public function getName()
    {
        return 'pos_icon_box';
    }

    public function getTitle()
    {
        return __('Pos Icon Box');
    }

    public function getIcon()
    {
        return 'eicon-icon-box';
    }

    public function getCategories()
    {
        return ['posthemes'];
    }

    protected function _registerControls()
    {
        $this->startControlsSection(
            'section_icon',
            array(
                'label' => __('Icon Box'),
            )
        );

        $this->addControl(
            'view',
            array(
                'label' => __('View'),
                'type' => ControlsManager::SELECT,
                'options' => array(
                    'default' => __('Default'),
                    'stacked' => __('Stacked'),
                    'framed' => __('Framed'),
                ),
                'default' => 'default',
                'prefix_class' => 'elementor-view-',
            )
        );

        $this->addControl(
            'icon',
            array(
                'label' => __('Icon'),
                'type' => ControlsManager::TEXT,
                'default' => __(''),
                'label_block' => true,
                'description' => 'See our list icons here:<a href="#" target="_blank">Link to icon</a>',
            )
        );
        

        $this->addControl(
            'shape',
            array(
                'label' => __('Shape'),
                'type' => ControlsManager::SELECT,
                'options' => array(
                    'circle' => __('Circle'),
                    'square' => __('Square'),
                ),
                'default' => 'circle',
                'condition' => array(
                    'view!' => 'default',
                ),
                'prefix_class' => 'elementor-shape-',
            )
        );

        $this->addControl(
            'title_text',
            array(
                'label' => __('Title & Description'),
                'type' => ControlsManager::TEXT,
                'default' => __('This is the heading'),
                'placeholder' => __('Your Title'),
                'label_block' => true,
            )
        );

        $this->addControl(
            'description_text',
            array(
                'label' => '',
                'type' => ControlsManager::TEXTAREA,
                'default' => __('Click edit button to change this text. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.'),
                'placeholder' => __('Your Description'),
                'title' => __('Input icon text here'),
                'separator' => 'none',
                'rows' => 10,
                'show_label' => false,
            )
        );

        $this->addControl(
            'link',
            array(
                'label' => __('Link to'),
                'type' => ControlsManager::URL,
                'placeholder' => __('http://your-link.com'),
                'separator' => 'before',
            )
        );

        $this->addControl(
            'position',
            array(
                'label' => __('Icon Position'),
                'type' => ControlsManager::CHOOSE,
                'default' => 'top',
                'options' => array(
                    'left' => array(
                        'title' => __('Left'),
                        'icon' => 'eicon-h-align-left',
                    ),
                    'top' => array(
                        'title' => __('Top'),
                        'icon' => 'eicon-v-align-top',
                    ),
                    'right' => array(
                        'title' => __('Right'),
                        'icon' => 'eicon-h-align-right',
                    ),
                ),
                'prefix_class' => 'elementor-position-',
                'toggle' => false,
            )
        );

        $this->addControl(
            'title_size',
            array(
                'label' => __('Title HTML Tag'),
                'type' => ControlsManager::SELECT,
                'options' => array(
                    'h1' => __('H1'),
                    'h2' => __('H2'),
                    'h3' => __('H3'),
                    'h4' => __('H4'),
                    'h5' => __('H5'),
                    'h6' => __('H6'),
                    'div' => __('div'),
                    'span' => __('span'),
                    'p' => __('p'),
                ),
                'default' => 'h3',
            )
        );

        $this->endControlsSection();

        $this->startControlsSection(
            'section_style_general',
            array(
                'label' => __('General'),
                'tab' => ControlsManager::TAB_STYLE,
            )
        );
            $this->addControl(
            'icon_box_width',
                [
                    'label' => __('Width'),
                    'type' => ControlsManager::SELECT,
                    'default' => 'inline',
                    'options' => [ 
                        'fullwidth' => __('Full width 100%'),
                        'inline' => __('Inline (auto)')
                    ],
                    'prefix_class' => 'pewidth-',
                    'render_type' => 'template',
                    'frontend_available' => true
                ]
            );
        $this->endControlsSection();
        $this->startControlsSection(
            'section_style_icon',
            array(
                'label' => __('Icon'),
                'tab' => ControlsManager::TAB_STYLE,
            )
        );

        $this->addControl(
            'primary_color',
            array(
                'label' => __('Primary Color'),
                'type' => ControlsManager::COLOR,
                'scheme' => array(
                    'type' => SchemeColor::getType(),
                    'value' => SchemeColor::COLOR_1,
                ),
                'default' => '',
                'selectors' => array(
                    '{{WRAPPER}}.elementor-view-stacked .elementor-icon' => 'background-color: {{VALUE}};',
                    '{{WRAPPER}}.elementor-view-framed .elementor-icon, {{WRAPPER}}.elementor-view-default .elementor-icon' => 'color: {{VALUE}}; border-color: {{VALUE}};',
                ),
            )
        );

        $this->addControl(
            'secondary_color',
            array(
                'label' => __('Secondary Color'),
                'type' => ControlsManager::COLOR,
                'default' => '',
                'condition' => array(
                    'view!' => 'default',
                ),
                'selectors' => array(
                    '{{WRAPPER}}.elementor-view-framed .elementor-icon' => 'background-color: {{VALUE}};',
                    '{{WRAPPER}}.elementor-view-stacked .elementor-icon' => 'color: {{VALUE}};',
                ),
            )
        );

        $this->addControl(
            'icon_space',
            array(
                'label' => __('Spacing'),
                'type' => ControlsManager::SLIDER,
                'default' => array(
                    'size' => 15,
                ),
                'range' => array(
                    'px' => array(
                        'min' => 0,
                        'max' => 100,
                    ),
                ),
                'selectors' => array(
                    '{{WRAPPER}}.elementor-position-right .elementor-icon-box-icon' => 'margin-left: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}}.elementor-position-left .elementor-icon-box-icon' => 'margin-right: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}}.elementor-position-top .elementor-icon-box-icon' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ),
            )
        );

        $this->addControl(
            'icon_size',
            array(
                'label' => __('Size'),
                'type' => ControlsManager::SLIDER,
                'range' => array(
                    'px' => array(
                        'min' => 6,
                        'max' => 300,
                    ),
                ),
                'selectors' => array(
                    '{{WRAPPER}} .elementor-icon' => 'font-size: {{SIZE}}{{UNIT}};',
                ),
            )
        );

        $this->addControl(
            'icon_padding',
            array(
                'label' => __('Padding'),
                'type' => ControlsManager::SLIDER,
                'selectors' => array(
                    '{{WRAPPER}} .elementor-icon' => 'padding: {{SIZE}}{{UNIT}};',
                ),
                'default' => array(
                    'size' => 1.5,
                    'unit' => 'em',
                ),
                'range' => array(
                    'em' => array(
                        'min' => 0,
                    ),
                ),
                'condition' => array(
                    'view!' => 'default',
                ),
            )
        );

        $this->addControl(
            'rotate',
            array(
                'label' => __('Rotate'),
                'type' => ControlsManager::SLIDER,
                'default' => array(
                    'size' => 0,
                    'unit' => 'deg',
                ),
                'selectors' => array(
                    '{{WRAPPER}} .elementor-icon i' => 'transform: rotate({{SIZE}}{{UNIT}});',
                ),
            )
        );

        $this->addControl(
            'border_width',
            array(
                'label' => __('Border Width'),
                'type' => ControlsManager::DIMENSIONS,
                'selectors' => array(
                    '{{WRAPPER}} .elementor-icon' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
                'condition' => array(
                    'view' => 'framed',
                ),
            )
        );

        $this->addControl(
            'border_radius',
            array(
                'label' => __('Border Radius'),
                'type' => ControlsManager::DIMENSIONS,
                'size_units' => array('px', '%'),
                'selectors' => array(
                    '{{WRAPPER}} .elementor-icon' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
                'condition' => array(
                    'view!' => 'default',
                ),
            )
        );

        $this->endControlsSection();

        $this->startControlsSection(
            'section_hover',
            array(
                'label' => __('Icon Hover'),
                'tab' => ControlsManager::TAB_STYLE,
            )
        );

        $this->addControl(
            'hover_primary_color',
            array(
                'label' => __('Primary Color'),
                'type' => ControlsManager::COLOR,
                'default' => '',
                'selectors' => array(
                    '{{WRAPPER}}.elementor-view-stacked .elementor-icon:hover' => 'background-color: {{VALUE}};',
                    '{{WRAPPER}}.elementor-view-framed .elementor-icon:hover, {{WRAPPER}}.elementor-view-default .elementor-icon:hover' => 'color: {{VALUE}}; border-color: {{VALUE}};',
                ),
            )
        );

        $this->addControl(
            'hover_secondary_color',
            array(
                'label' => __('Secondary Color'),
                'type' => ControlsManager::COLOR,
                'default' => '',
                'condition' => array(
                    'view!' => 'default',
                ),
                'selectors' => array(
                    '{{WRAPPER}}.elementor-view-framed .elementor-icon:hover' => 'background-color: {{VALUE}};',
                    '{{WRAPPER}}.elementor-view-stacked .elementor-icon:hover' => 'color: {{VALUE}};',
                ),
            )
        );

        $this->addControl(
            'hover_animation',
            array(
                'label' => __('Animation'),
                'type' => ControlsManager::HOVER_ANIMATION,
            )
        );

        $this->endControlsSection();

        $this->startControlsSection(
            'section_style_content',
            array(
                'label' => __('Content'),
                'tab' => ControlsManager::TAB_STYLE,
            )
        );

        $this->addResponsiveControl(
            'text_align',
            array(
                'label' => __('Alignment'),
                'type' => ControlsManager::CHOOSE,
                'options' => array(
                    'left' => array(
                        'title' => __('Left'),
                        'icon' => 'fa fa-align-left',
                    ),
                    'center' => array(
                        'title' => __('Center'),
                        'icon' => 'fa fa-align-center',
                    ),
                    'right' => array(
                        'title' => __('Right'),
                        'icon' => 'fa fa-align-right',
                    ),
                    'justify' => array(
                        'title' => __('Justified'),
                        'icon' => 'fa fa-align-justify',
                    ),
                ),
                'selectors' => array(
                    '{{WRAPPER}} .elementor-icon-box-wrapper' => 'text-align: {{VALUE}};',
                ),
            )
        );

        $this->addControl(
            'content_vertical_alignment',
            array(
                'label' => __('Vertical Alignment'),
                'type' => ControlsManager::SELECT,
                'options' => array(
                    'top' => __('Top'),
                    'middle' => __('Middle'),
                    'bottom' => __('Bottom'),
                ),
                'default' => 'top',
                'prefix_class' => 'elementor-vertical-align-',
            )
        );

        $this->addControl(
            'heading_title',
            array(
                'label' => __('Title'),
                'type' => ControlsManager::HEADING,
                'separator' => 'before',
            )
        );

        $this->addResponsiveControl(
            'title_bottom_space',
            array(
                'label' => __('Spacing'),
                'type' => ControlsManager::SLIDER,
                'range' => array(
                    'px' => array(
                        'min' => 0,
                        'max' => 100,
                    ),
                ),
                'selectors' => array(
                    '{{WRAPPER}} .elementor-icon-box-title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ),
            )
        );

        $this->addControl(
            'title_color',
            array(
                'label' => __('Color'),
                'type' => ControlsManager::COLOR,
                'default' => '',
                'selectors' => array(
                    '{{WRAPPER}} .elementor-icon-box-content .elementor-icon-box-title' => 'color: {{VALUE}};',
                ),
                'scheme' => array(
                    'type' => SchemeColor::getType(),
                    'value' => SchemeColor::COLOR_1,
                ),
            )
        );

        $this->addGroupControl(
            GroupControlTypography::getType(),
            array(
                'name' => 'title_typography',
                'selector' => '{{WRAPPER}} .elementor-icon-box-content .elementor-icon-box-title',
                'scheme' => SchemeTypography::TYPOGRAPHY_1,
            )
        );

        $this->addControl(
            'heading_description',
            array(
                'label' => __('Description'),
                'type' => ControlsManager::HEADING,
                'separator' => 'before',
            )
        );

        $this->addControl(
            'description_color',
            array(
                'label' => __('Color'),
                'type' => ControlsManager::COLOR,
                'default' => '',
                'selectors' => array(
                    '{{WRAPPER}} .elementor-icon-box-content .elementor-icon-box-description' => 'color: {{VALUE}};',
                ),
                'scheme' => array(
                    'type' => SchemeColor::getType(),
                    'value' => SchemeColor::COLOR_3,
                ),
            )
        );

        $this->addGroupControl(
            GroupControlTypography::getType(),
            array(
                'name' => 'description_typography',
                'selector' => '{{WRAPPER}} .elementor-icon-box-content .elementor-icon-box-description',
                'scheme' => SchemeTypography::TYPOGRAPHY_3,
            )
        );

        $this->endControlsSection();
    }

    protected function render()
    {
        $settings = $this->getSettings();

        $this->addRenderAttribute('icon', 'class', array('elementor-icon', 'elementor-animation-' . $settings['hover_animation']));

        $icon_tag = 'span';

        if (!empty($settings['link']['url'])) {
            $this->addRenderAttribute('link', 'href', $settings['link']['url']);
            $icon_tag = 'a';

            if (!empty($settings['link']['is_external'])) {
                $this->addRenderAttribute('link', 'target', '_blank');
            }
        }

        $this->addRenderAttribute('i', 'class', $settings['icon']);

        $icon_attributes = $this->getRenderAttributeString('icon');
        $link_attributes = $this->getRenderAttributeString('link');
        ?>
        <div class="elementor-icon-box-wrapper">
            <div class="elementor-icon-box-icon">
                <<?php echo implode(' ', array($icon_tag, $icon_attributes, $link_attributes)); ?>>
                    <i <?php echo $this->getRenderAttributeString('i'); ?>></i>
                </<?php echo $icon_tag; ?>>
            </div>
            <div class="elementor-icon-box-content">
                <<?php echo $settings['title_size']; ?> class="elementor-icon-box-title">
                    <<?php echo implode(' ', array($icon_tag, $link_attributes)); ?>><?php echo $settings['title_text']; ?></<?php echo $icon_tag; ?>>
                </<?php echo $settings['title_size']; ?>>
                <div class="elementor-icon-box-description"><?php echo $settings['description_text']; ?></div>
            </div>
        </div>
        <?php
    }

    protected function _contentTemplate()
    {
        ?>
        <# var link = settings.link.url ? 'href="' + settings.link.url + '"' : '',
                iconTag = link ? 'a' : 'span'; #>
        <div class="elementor-icon-box-wrapper">
            <div class="elementor-icon-box-icon">
                <{{{ iconTag + ' ' + link }}} class="elementor-icon elementor-animation-{{ settings.hover_animation }}">
                    <i class="{{ settings.icon }}"></i>
                </{{{ iconTag }}}>
            </div>
            <div class="elementor-icon-box-content">
                <{{{ settings.title_size }}} class="elementor-icon-box-title">
                    <{{{ iconTag + ' ' + link }}}>{{{ settings.title_text }}}</{{{ iconTag }}}>
                </{{{ settings.title_size }}}>
                <div class="elementor-icon-box-description">{{{ settings.description_text }}}</div>
            </div>
        </div>
        <?php
    }
}
