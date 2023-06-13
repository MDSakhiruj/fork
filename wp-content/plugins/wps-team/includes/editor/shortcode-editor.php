<?php

namespace WPSpeedo_Team;

if ( !defined( 'ABSPATH' ) ) {
    exit;
}
class Shortcode_Editor extends Editor_Controls
{
    public function __construct( array $data = array(), array $args = null )
    {
        parent::__construct( $data, $args );
        do_action( 'wpspeedo_team/shortcode_editor/init', $this );
    }
    
    public function get_name()
    {
        return 'shortcode_editor';
    }
    
    protected function _register_controls()
    {
        $this->layout_section();
        $this->elements_section();
        $this->carousel_section();
        $this->filter_section();
        $this->query_section();
        $this->style_section();
        $this->typo_section();
        $this->advance_section();
        $this->advance_container_section();
    }
    
    protected function layout_section()
    {
        $this->start_controls_section( 'layout_section', [
            'label' => __( 'Layout', 'wpspeedo-team' ),
        ] );
        $this->add_control( 'display_type', [
            'label'       => __( 'Display Type', 'wpspeedo-team' ),
            'label_block' => true,
            'type'        => Controls_Manager::SELECT,
            'placeholder' => __( 'Display Type', 'wpspeedo-team' ),
            'render_type' => 'template',
            'options'     => Utils::get_control_options( 'display_type' ),
            'default'     => 'grid',
        ] );
        $this->add_control( 'theme', [
            'label'       => __( 'Theme', 'wpspeedo-team' ),
            'label_block' => true,
            'type'        => Controls_Manager::SELECT,
            'placeholder' => __( 'Theme', 'wpspeedo-team' ),
            'render_type' => 'template',
            'options'     => Utils::get_control_options( 'theme' ),
            'default'     => 'square-01',
        ] );
        $this->add_control( 'card_action', [
            'label'       => __( 'Card Action', 'wpspeedo-team' ),
            'label_block' => true,
            'type'        => Controls_Manager::SELECT,
            'placeholder' => __( 'Card Action', 'wpspeedo-team' ),
            'render_type' => 'template',
            'options'     => Utils::get_control_options( 'card_action' ),
            'default'     => 'single-page',
        ] );
        $this->add_responsive_control( 'container_width', [
            'label'                => __( 'Container Width', 'wpspeedo-team' ),
            'label_block'          => true,
            'type'                 => Controls_Manager::SLIDER,
            'size_units'           => [ '%', 'px', 'vw' ],
            'range'                => [
            '%'  => [
            'min'     => 1,
            'max'     => 100,
            'default' => 100,
        ],
            'px' => [
            'min'     => 1,
            'max'     => 2000,
            'default' => 1200,
        ],
            'vw' => [
            'min'     => 1,
            'max'     => 100,
            'default' => 80,
        ],
        ],
            'unit'                 => 'px',
            'tablet_unit'          => '%',
            'small_tablet_unit'    => '%',
            'mobile_unit'          => '%',
            'default'              => 1200,
            'tablet_default'       => 90,
            'small_tablet_default' => 90,
            'mobile_default'       => 85,
        ] );
        $this->add_responsive_control( 'columns', [
            'label'                => __( 'Columns', 'wpspeedo-team' ),
            'label_block'          => true,
            'type'                 => Controls_Manager::SLIDER,
            'min'                  => 1,
            'max'                  => 10,
            'default'              => 3,
            'tablet_default'       => 3,
            'small_tablet_default' => 2,
            'mobile_default'       => 1,
        ] );
        $this->add_responsive_control( 'gap', [
            'label'                => __( 'Gap', 'wpspeedo-team' ),
            'label_block'          => true,
            'type'                 => Controls_Manager::SLIDER,
            'min'                  => 0,
            'max'                  => 100,
            'step'                 => 1,
            'default'              => 30,
            'tablet_default'       => 30,
            'small_tablet_default' => 30,
            'mobile_default'       => 30,
        ] );
        $this->add_responsive_control( 'gap_vertical', [
            'label'                => __( 'Gap Vertical', 'wpspeedo-team' ),
            'label_block'          => true,
            'type'                 => Controls_Manager::SLIDER,
            'min'                  => 0,
            'max'                  => 100,
            'step'                 => 1,
            'default'              => 30,
            'tablet_default'       => 30,
            'small_tablet_default' => 30,
            'mobile_default'       => 30,
            'condition'            => [
            'display_type' => [ 'grid', 'filter' ],
        ],
        ] );
        $this->add_control( 'description_length', [
            'label'       => __( 'Max Characters for Description', 'wpspeedo-team' ),
            'description' => __( 'Set 0 to get full content.', 'wpspeedo-team' ),
            'label_block' => true,
            'render_type' => 'template',
            'type'        => Controls_Manager::SLIDER,
            'min'         => 0,
            'max'         => 1000,
            'step'        => 10,
            'default'     => 110,
        ] );
        $this->add_control( 'fix_broken_words', [
            'label'       => __( 'Fix Broken Words', 'wpspeedo-team' ),
            'label_block' => false,
            'type'        => Controls_Manager::SWITCHER,
            'default'     => false,
            'render_type' => 'template',
        ] );
        $this->end_controls_section();
    }
    
    protected function elements_section()
    {
        $this->start_controls_section( 'elements_section', [
            'label' => __( 'Elements Visibility', 'wpspeedo-team' ),
        ] );
        $elements = Utils::allowed_elements_display_order();
        foreach ( Utils::elements_display_order() as $element_key => $element_title ) {
            
            if ( in_array( $element_key, $elements ) ) {
                $element_key = 'show_' . $element_key;
                $this->add_control( $element_key, [
                    'label'       => $element_title,
                    'label_block' => false,
                    'type'        => Controls_Manager::CHOOSE,
                    'options'     => [
                    'true'  => [
                    'title' => __( 'Show', 'wpspeedo-team' ),
                    'icon'  => 'fas fa-eye',
                ],
                    'false' => [
                    'title' => __( 'Hide', 'wpspeedo-team' ),
                    'icon'  => 'fas fa-eye-slash',
                ],
                ],
                    'render_type' => 'template',
                ] );
            } else {
                $element_key = 'show_' . $element_key;
                $this->add_control( $element_key, [
                    'label'       => $element_title,
                    'label_block' => false,
                    'type'        => Controls_Manager::UPGRADE_NOTICE,
                ] );
            }
        
        }
        $this->end_controls_section();
    }
    
    protected function carousel_section()
    {
        $this->start_controls_section( 'carousel_section', [
            'label'     => __( 'Carousel Settings', 'wpspeedo-team' ),
            'condition' => [
            'display_type' => [ 'carousel', 'carousel-ajax' ],
        ],
        ] );
        $this->add_control( 'speed', [
            'label'       => __( 'Carousel Speed', 'wpspeedo-team' ),
            'label_block' => true,
            'type'        => Controls_Manager::SLIDER,
            'min'         => 100,
            'max'         => 5000,
            'step'        => 100,
            'default'     => 800,
        ] );
        $this->add_control( 'dots', [
            'label'       => __( 'Dots Pagination', 'wpspeedo-team' ),
            'label_block' => false,
            'type'        => Controls_Manager::SWITCHER,
            'default'     => true,
            'render_type' => 'template',
        ] );
        $this->add_control( 'navs', [
            'label'       => __( 'Arrow Navigation', 'wpspeedo-team' ),
            'label_block' => false,
            'type'        => Controls_Manager::SWITCHER,
            'default'     => true,
            'render_type' => 'template',
        ] );
        $this->add_control( 'loop', [
            'label'       => __( 'Carousel Loop', 'wpspeedo-team' ),
            'label_block' => false,
            'type'        => Controls_Manager::SWITCHER,
            'default'     => true,
            'render_type' => 'template',
        ] );
        $this->add_control( 'autoplay', [
            'label'       => Utils::trans( 'autoplay' ),
            'label_block' => true,
            'type'        => Controls_Manager::UPGRADE_NOTICE,
        ] );
        $this->add_control( 'autoplay_delay', [
            'label'       => Utils::trans( 'autoplay-delay' ),
            'label_block' => true,
            'type'        => Controls_Manager::UPGRADE_NOTICE,
        ] );
        $this->add_control( 'dynamic_dots', [
            'label'       => __( 'Dynamic Dots', 'wpspeedo-team' ),
            'label_block' => true,
            'type'        => Controls_Manager::UPGRADE_NOTICE,
        ] );
        $this->add_control( 'wheel', [
            'label'       => __( 'Scroll Navigation', 'wpspeedo-team' ),
            'label_block' => true,
            'type'        => Controls_Manager::UPGRADE_NOTICE,
        ] );
        $this->add_control( 'keyboard', [
            'label'       => __( 'Keyboard Navigation', 'wpspeedo-team' ),
            'label_block' => true,
            'type'        => Controls_Manager::UPGRADE_NOTICE,
        ] );
        $this->add_control( 'pause_on_hover', [
            'label'       => __( 'Pause On Hover', 'wpspeedo-team' ),
            'label_block' => true,
            'type'        => Controls_Manager::UPGRADE_NOTICE,
        ] );
        $this->end_controls_section();
    }
    
    protected function filter_section()
    {
        $this->start_controls_section( 'filter_section', [
            'label'     => __( 'Filter Settings', 'wpspeedo-team' ),
            'condition' => [
            'display_type' => [ 'filter' ],
        ],
        ] );
        $this->add_control( 'heading_filter_types', [
            'label' => __( 'Filters', 'wpspeedo-team' ),
            'type'  => Controls_Manager::HEADING,
        ] );
        $this->add_control( 'show_group_filter', [
            'label'       => Utils::trans( 'show-group-filter' ),
            'label_block' => true,
            'type'        => Controls_Manager::UPGRADE_NOTICE,
        ] );
        $this->add_control( 'show_location_filter', [
            'label'       => Utils::trans( 'show-location-filter' ),
            'label_block' => true,
            'type'        => Controls_Manager::UPGRADE_NOTICE,
        ] );
        $this->add_control( 'show_language_filter', [
            'label'       => Utils::trans( 'show-language-filter' ),
            'label_block' => true,
            'type'        => Controls_Manager::UPGRADE_NOTICE,
        ] );
        $this->add_control( 'show_specialty_filter', [
            'label'       => Utils::trans( 'show-specialty-filter' ),
            'label_block' => true,
            'type'        => Controls_Manager::UPGRADE_NOTICE,
        ] );
        $this->add_control( 'show_gender_filter', [
            'label'       => Utils::trans( 'show-gender-filter' ),
            'label_block' => true,
            'type'        => Controls_Manager::UPGRADE_NOTICE,
        ] );
        $this->add_control( 'heading_filter_config', [
            'label' => __( 'Filter Config', 'wpspeedo-team' ),
            'type'  => Controls_Manager::HEADING,
        ] );
        $this->add_control( 'layout_mode', [
            'label'       => __( 'Layout Mode', 'wpspeedo-team' ),
            'label_block' => true,
            'type'        => Controls_Manager::SELECT,
            'options'     => Utils::get_control_options( 'layout_mode' ),
            'default'     => 'masonry',
            'render_type' => 'template',
        ] );
        $this->add_control( 'multi_select', [
            'label'       => Utils::trans( 'enable-multi-select' ),
            'label_block' => true,
            'type'        => Controls_Manager::UPGRADE_NOTICE,
        ] );
        $this->add_control( 'filter_animation_speed', [
            'label'       => Utils::trans( 'filter-animation-speed' ),
            'label_block' => true,
            'type'        => Controls_Manager::UPGRADE_NOTICE,
        ] );
        $this->add_control( 'heading_filter_group_config', [
            'label' => __( 'Group Filter Config', 'wpspeedo-team' ),
            'type'  => Controls_Manager::HEADING,
        ] );
        $this->add_control( 'filter_inner_space', [
            'label'       => Utils::trans( 'filter-inner-space' ),
            'label_block' => true,
            'type'        => Controls_Manager::UPGRADE_NOTICE,
        ] );
        $this->add_control( 'filters_bottom_space', [
            'label'       => Utils::trans( 'filters-bottom-space' ),
            'label_block' => true,
            'type'        => Controls_Manager::UPGRADE_NOTICE,
        ] );
        $this->add_control( 'show_filter_all', [
            'label'       => Utils::trans( 'show-hide-filter-all' ),
            'label_block' => true,
            'type'        => Controls_Manager::UPGRADE_NOTICE,
        ] );
        $this->add_control( 'initial_filter', [
            'label'       => Utils::trans( 'initial-group-filter' ),
            'label_block' => true,
            'type'        => Controls_Manager::UPGRADE_NOTICE,
        ] );
        $this->add_control( 'heading_filters_text_config', [
            'label' => __( 'Filter Texts Config', 'wpspeedo-team' ),
            'type'  => Controls_Manager::HEADING,
        ] );
        $this->add_control( 'filter_all_text', [
            'label'       => Utils::trans( 'group-filter-all-text' ),
            'label_block' => true,
            'type'        => Controls_Manager::UPGRADE_NOTICE,
        ] );
        $this->add_control( 'filter_all_location_text', [
            'label'       => Utils::trans( 'location-filter-all-text' ),
            'label_block' => true,
            'type'        => Controls_Manager::UPGRADE_NOTICE,
        ] );
        $this->add_control( 'filter_all_language_text', [
            'label'       => Utils::trans( 'language-filter-all-text' ),
            'label_block' => true,
            'type'        => Controls_Manager::UPGRADE_NOTICE,
        ] );
        $this->add_control( 'filter_all_specialty_text', [
            'label'       => Utils::trans( 'specialty-filter-all-text' ),
            'label_block' => true,
            'type'        => Controls_Manager::UPGRADE_NOTICE,
        ] );
        $this->add_control( 'filter_all_gender_text', [
            'label'       => Utils::trans( 'gender-filter-all-text' ),
            'label_block' => true,
            'type'        => Controls_Manager::UPGRADE_NOTICE,
        ] );
        $this->end_controls_section();
    }
    
    protected function query_section()
    {
        $this->start_controls_section( 'query_section', [
            'label' => __( 'Query', 'wpspeedo-team' ),
            'tab'   => 'query',
        ] );
        $this->add_control( 'show_all', [
            'label'       => __( 'Display All Members', 'wpspeedo-team' ),
            'label_block' => false,
            'render_type' => 'template',
            'type'        => Controls_Manager::SWITCHER,
            'separator'   => 'none',
            'default'     => true,
        ] );
        $this->add_control( 'limit', [
            'label'       => __( 'Members to Display', 'wpspeedo-team' ),
            'label_block' => true,
            'type'        => Controls_Manager::NUMBER,
            'default'     => 12,
            'min'         => 1,
            'max'         => 999,
            'render_type' => 'template',
            'separator'   => 'none',
            'condition'   => [
            'show_all' => false,
        ],
        ] );
        $this->add_control( 'orderby', [
            'label'       => __( 'Order By', 'wpspeedo-team' ),
            'label_block' => true,
            'type'        => Controls_Manager::SELECT,
            'render_type' => 'template',
            'options'     => Utils::get_control_options( 'orderby' ),
            'default'     => 'date',
            'separator'   => 'before',
        ] );
        $this->add_control( 'order', [
            'label'       => __( 'Order', 'wpspeedo-team' ),
            'label_block' => true,
            'type'        => Controls_Manager::SELECT,
            'render_type' => 'template',
            'options'     => [ [
            'label' => 'Ascending',
            'value' => 'ASC',
        ], [
            'label' => 'Descending',
            'value' => 'DESC',
        ] ],
            'default'     => 'DESC',
            'separator'   => 'after',
        ] );
        $this->add_control( 'heading_query_include', [
            'label' => __( 'Include', 'wpspeedo-team' ),
            'type'  => Controls_Manager::HEADING,
        ] );
        $this->add_control( 'include_by_group', [
            'label'       => __( 'Include by Group', 'wpspeedo-team' ),
            'label_block' => true,
            'type'        => Controls_Manager::SELECT,
            'render_type' => 'template',
            'options'     => Utils::get_term_options( Utils::get_groups() ),
            'placeholder' => __( 'Select Groups', 'wpspeedo-team' ),
            'multiple'    => true,
            'separator'   => 'none',
        ] );
        $this->add_control( 'include_by_location', [
            'label'       => Utils::trans( 'include-by-location' ),
            'label_block' => true,
            'type'        => Controls_Manager::UPGRADE_NOTICE,
            'separator'   => 'none',
        ] );
        $this->add_control( 'include_by_language', [
            'label'       => Utils::trans( 'include-by-language' ),
            'label_block' => true,
            'type'        => Controls_Manager::UPGRADE_NOTICE,
            'separator'   => 'none',
        ] );
        $this->add_control( 'include_by_specialty', [
            'label'       => Utils::trans( 'include-by-specialty' ),
            'label_block' => true,
            'type'        => Controls_Manager::UPGRADE_NOTICE,
            'separator'   => 'none',
        ] );
        $this->add_control( 'include_by_gender', [
            'label'       => Utils::trans( 'include-by-gender' ),
            'label_block' => true,
            'type'        => Controls_Manager::UPGRADE_NOTICE,
            'separator'   => 'after',
        ] );
        $this->add_control( 'heading_query_exclude', [
            'label' => __( 'Exclude', 'wpspeedo-team' ),
            'type'  => Controls_Manager::HEADING,
        ] );
        $this->add_control( 'exclude_by_group', [
            'label'       => __( 'Exclude by Group', 'wpspeedo-team' ),
            'label_block' => true,
            'type'        => Controls_Manager::SELECT,
            'render_type' => 'template',
            'options'     => Utils::get_term_options( Utils::get_groups() ),
            'placeholder' => __( 'Select Groups', 'wpspeedo-team' ),
            'multiple'    => true,
            'separator'   => 'none',
        ] );
        $this->add_control( 'exclude_by_location', [
            'label'       => Utils::trans( 'exclude-by-location' ),
            'label_block' => true,
            'type'        => Controls_Manager::UPGRADE_NOTICE,
            'separator'   => 'none',
        ] );
        $this->add_control( 'exclude_by_language', [
            'label'       => Utils::trans( 'exclude-by-language' ),
            'label_block' => true,
            'type'        => Controls_Manager::UPGRADE_NOTICE,
            'separator'   => 'none',
        ] );
        $this->add_control( 'exclude_by_specialty', [
            'label'       => Utils::trans( 'exclude-by-specialty' ),
            'label_block' => true,
            'type'        => Controls_Manager::UPGRADE_NOTICE,
            'separator'   => 'none',
        ] );
        $this->add_control( 'exclude_by_gender', [
            'label'       => Utils::trans( 'exclude-by-gender' ),
            'label_block' => true,
            'type'        => Controls_Manager::UPGRADE_NOTICE,
            'separator'   => 'none',
        ] );
        $this->end_controls_section();
    }
    
    protected function style_section()
    {
        $this->start_controls_section( 'style_section', [
            'label' => __( 'Style', 'wpspeedo-team' ),
            'tab'   => 'style',
        ] );
        $this->add_control( 'title_color', [
            'label'       => __( 'Title Color', 'wpspeedo-team' ),
            'label_block' => false,
            'type'        => Controls_Manager::COLOR,
            'separator'   => 'after',
        ] );
        $this->add_control( 'title_color_hover', [
            'label'       => __( 'Title Color Hover', 'wpspeedo-team' ),
            'label_block' => false,
            'type'        => Controls_Manager::COLOR,
        ] );
        $this->add_control( 'designation_color', [
            'label'       => __( 'Designation Color', 'wpspeedo-team' ),
            'label_block' => false,
            'type'        => Controls_Manager::COLOR,
        ] );
        $this->add_control( 'desc_color', [
            'label'       => __( 'Description Color', 'wpspeedo-team' ),
            'label_block' => false,
            'type'        => Controls_Manager::COLOR,
        ] );
        $this->add_control( 'divider_color', [
            'label'       => __( 'Divider Color', 'wpspeedo-team' ),
            'label_block' => false,
            'type'        => Controls_Manager::COLOR,
        ] );
        $this->add_control( 'info_icon_color', [
            'label'       => __( 'Info Icon Color', 'wpspeedo-team' ),
            'label_block' => false,
            'type'        => Controls_Manager::COLOR,
        ] );
        $this->add_control( 'info_text_color', [
            'label'       => __( 'Info Text Color', 'wpspeedo-team' ),
            'label_block' => false,
            'type'        => Controls_Manager::COLOR,
        ] );
        $this->add_control( 'info_link_color', [
            'label'       => __( 'Info Link Color', 'wpspeedo-team' ),
            'label_block' => false,
            'type'        => Controls_Manager::COLOR,
        ] );
        $this->add_control( 'info_link_hover_color', [
            'label'       => __( 'Info Link Hover Color', 'wpspeedo-team' ),
            'label_block' => false,
            'type'        => Controls_Manager::COLOR,
        ] );
        $this->style_item_styling_controls();
        $this->style_buttons_controls();
        $this->style_filter_color_controls();
        $this->style_social_links_controls();
        // $this->add_control( 'skill_colors', [
        // 	'label' => __('Skill color settings', 'wpspeedo-team'),
        // 	'label_block' => true,
        // 	'type' => Controls_Manager::UPGRADE_NOTICE
        // ]);
        $this->end_controls_section();
    }
    
    protected function style_item_styling_controls()
    {
        $this->add_control( 'heading_item_styling', [
            'label' => __( 'Single Area Styling', 'wpspeedo-team' ),
            'type'  => Controls_Manager::HEADING,
        ] );
        $this->add_group_control( Group_Control_Background::get_type(), [
            'name'  => 'item_background',
            'label' => __( 'Background', 'wpspeedo-team' ),
            'types' => [ 'classic', 'gradient' ],
        ] );
        $this->add_control( 'item_padding', [
            'label'       => Utils::trans( 'padding' ),
            'label_block' => true,
            'type'        => Controls_Manager::UPGRADE_NOTICE,
        ] );
        $this->add_control( 'item_border_radius', [
            'label'       => Utils::trans( 'border-radius' ),
            'label_block' => true,
            'type'        => Controls_Manager::UPGRADE_NOTICE,
        ] );
        $this->add_control( 'title_spacing', [
            'label'       => Utils::trans( 'title-spacing' ),
            'label_block' => true,
            'type'        => Controls_Manager::UPGRADE_NOTICE,
        ] );
        $this->add_control( 'desig_spacing', [
            'label'       => Utils::trans( 'designation-spacing' ),
            'label_block' => true,
            'type'        => Controls_Manager::UPGRADE_NOTICE,
        ] );
        $this->add_control( 'desc_spacing', [
            'label'       => Utils::trans( 'desc-spacing' ),
            'label_block' => true,
            'type'        => Controls_Manager::UPGRADE_NOTICE,
        ] );
        $this->add_control( 'devider_spacing', [
            'label'       => Utils::trans( 'devider-spacing' ),
            'label_block' => true,
            'type'        => Controls_Manager::UPGRADE_NOTICE,
        ] );
        $this->add_control( 'social_spacing', [
            'label'       => Utils::trans( 'social-icons-spacing' ),
            'label_block' => true,
            'type'        => Controls_Manager::UPGRADE_NOTICE,
        ] );
        $this->add_control( 'info_spacing', [
            'label'       => Utils::trans( 'meta-info-spacing' ),
            'label_block' => true,
            'type'        => Controls_Manager::UPGRADE_NOTICE,
        ] );
    }
    
    protected function style_buttons_controls()
    {
        $this->add_control( 'heading_resume_button_style', [
            'label'       => Utils::trans( 'resume-button-style' ),
            'label_block' => true,
            'type'        => Controls_Manager::UPGRADE_NOTICE,
        ] );
        $this->add_control( 'heading_hire_button_style', [
            'label'       => Utils::trans( 'resume-button-style' ),
            'label_block' => true,
            'type'        => Controls_Manager::UPGRADE_NOTICE,
        ] );
    }
    
    protected function style_filter_color_controls()
    {
        $this->add_control( 'heading_filter_colors', [
            'label'       => Utils::trans( 'filter-styling' ),
            'label_block' => true,
            'type'        => Controls_Manager::UPGRADE_NOTICE,
        ] );
    }
    
    protected function style_social_links_controls()
    {
        $this->add_control( 'heading_social_styling', [
            'label'       => Utils::trans( 'social-links-styling' ),
            'label_block' => true,
            'type'        => Controls_Manager::UPGRADE_NOTICE,
        ] );
    }
    
    protected function typo_section()
    {
        $this->start_controls_section( 'typo_section', [
            'label' => __( 'Typography', 'wpspeedo-team' ),
            'tab'   => 'typo',
        ] );
        $this->add_control( 'typo_name', [
            'label'       => Utils::trans( 'typo-name' ),
            'label_block' => true,
            'type'        => Controls_Manager::UPGRADE_NOTICE,
        ] );
        $this->add_control( 'typo_desig', [
            'label'       => Utils::trans( 'typo-designation' ),
            'label_block' => true,
            'type'        => Controls_Manager::UPGRADE_NOTICE,
        ] );
        $this->add_control( 'typo_content', [
            'label'       => Utils::trans( 'typo-content' ),
            'label_block' => true,
            'type'        => Controls_Manager::UPGRADE_NOTICE,
        ] );
        $this->add_control( 'typo_meta', [
            'label'       => Utils::trans( 'typo-meta' ),
            'label_block' => true,
            'separator'   => 'none',
            'type'        => Controls_Manager::UPGRADE_NOTICE,
        ] );
        $this->end_controls_section();
    }
    
    protected function advance_section()
    {
        $this->start_controls_section( 'advance_section', [
            'label' => __( 'Advance', 'wpspeedo-team' ),
            'tab'   => 'advance',
        ] );
        $this->add_control( 'thumbnail_size', [
            'label'       => __( 'Member Image Size', 'wpspeedo-team' ),
            'description' => __( 'This image size is used for general layout.', 'wpspeedo-team' ),
            'label_block' => true,
            'type'        => Controls_Manager::SELECT,
            'render_type' => 'template',
            'options'     => Utils::get_registered_image_sizes(),
            'placeholder' => __( 'Select Size', 'wpspeedo-team' ),
        ] );
        $this->add_control( 'detail_thumbnail_size', [
            'label'       => __( 'Member Detail\'s Image Size', 'wpspeedo-team' ),
            'description' => __( 'This image size is used for modal, expand & panel layouts.', 'wpspeedo-team' ),
            'label_block' => true,
            'type'        => Controls_Manager::SELECT,
            'render_type' => 'template',
            'options'     => Utils::get_registered_image_sizes(),
            'placeholder' => __( 'Select Size', 'wpspeedo-team' ),
        ] );
        $this->add_control( 'thumbnail_position', [
            'label'       => __( 'Thumbnail Position', 'wpspeedo-team' ),
            'description' => __( 'This position is used for alignment of the thumbnail.', 'wpspeedo-team' ),
            'label_block' => true,
            'type'        => Controls_Manager::SELECT,
            'options'     => Utils::get_thumbnail_position(),
            'placeholder' => __( 'Thumbnail Position', 'wpspeedo-team' ),
            'default'     => 'center center',
        ] );
        $this->end_controls_section();
    }
    
    protected function advance_container_section()
    {
        $this->start_controls_section( 'container_settings_section', [
            'label' => __( 'Container Settings', 'wpspeedo-team' ),
            'tab'   => 'advance',
        ] );
        $this->add_control( 'container_background', [
            'label'       => Utils::trans( 'container-background-color' ),
            'label_block' => true,
            'type'        => Controls_Manager::UPGRADE_NOTICE,
        ] );
        $this->add_control( 'container_custom_class', [
            'label'       => Utils::trans( 'container-custom-class' ),
            'label_block' => true,
            'type'        => Controls_Manager::UPGRADE_NOTICE,
        ] );
        $this->add_control( 'container_padding', [
            'label'       => Utils::trans( 'container-padding' ),
            'label_block' => true,
            'type'        => Controls_Manager::UPGRADE_NOTICE,
        ] );
        $this->add_control( 'container_z_index', [
            'label'       => Utils::trans( 'container-z-index' ),
            'label_block' => true,
            'type'        => Controls_Manager::UPGRADE_NOTICE,
        ] );
        $this->add_control( 'container_border_radius', [
            'label'       => Utils::trans( 'container-border-radius' ),
            'label_block' => true,
            'type'        => Controls_Manager::UPGRADE_NOTICE,
        ] );
        // $this->add_control( 'container_box_shadow', [
        // 	'label' => __( 'Box Shadow', 'wpspeedo-team' ),
        // 	'label_block' => true,
        // 	'type' => Controls_Manager::UPGRADE_NOTICE,
        // ]);
        // $this->add_control( 'container_border', [
        // 	'label' => __( 'Border', 'wpspeedo-team' ),
        // 	'label_block' => true,
        // 	'type' => Controls_Manager::UPGRADE_NOTICE,
        // ]);
        // $this->add_control( 'entrance_animation', [
        // 	'label' => __( 'Entrance Animation', 'wpspeedo-team' ),
        // 	'label_block' => true,
        // 	'type' => Controls_Manager::UPGRADE_NOTICE,
        // ]);
        // $this->add_control( 'hover_animation', [
        // 	'label' => __( 'Hover Animation', 'wpspeedo-team' ),
        // 	'label_block' => true,
        // 	'separator' => 'none',
        // 	'type' => Controls_Manager::UPGRADE_NOTICE,
        // ]);
        $this->end_controls_section();
    }

}