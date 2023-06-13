<?php

namespace WPSpeedo_Team;

if ( !defined( 'ABSPATH' ) ) {
    exit;
}
class Settings_Editor extends Editor_Controls
{
    public function __construct( array $data = array(), array $args = null )
    {
        parent::__construct( $data, $args );
        do_action( 'wpspeedo_team/settings_editor/init', $this );
    }
    
    public function get_name()
    {
        return 'meta_box_editor';
    }
    
    protected function _register_controls()
    {
        $this->general_settings();
        $this->archive_settings();
        $this->localization_settings();
    }
    
    protected function general_settings()
    {
        $this->start_controls_section( 'general_settings_section', [
            'label'      => __( 'General Settings', 'wpspeedo-team' ),
            'menu_label' => __( 'General', 'wpspeedo-team' ),
            'icon'       => 'fas fa-tools',
            'path'       => 'general',
        ] );
        $this->add_control( 'disable_google_fonts_loading', [
            'label'       => __( 'Disable Google Fonts Loading', 'wpspeedo-team' ),
            'label_block' => false,
            'type'        => Controls_Manager::SWITCHER,
            'default'     => false,
        ] );
        $this->add_control( 'thumbnail_size', [
            'label'       => __( 'Member Image Size', 'wpspeedo-team' ),
            'description' => __( 'This image size is used for general layout globally for all shortcodes, unless it is overridden from the specific shortcode.', 'wpspeedo-team' ),
            'label_block' => true,
            'type'        => Controls_Manager::SELECT,
            'render_type' => 'template',
            'separator'   => 'before',
            'options'     => Utils::get_registered_image_sizes(),
            'placeholder' => __( 'Select Size', 'wpspeedo-team' ),
        ] );
        $this->add_control( 'detail_thumbnail_size', [
            'label'       => __( 'Member Detail\'s Image Size', 'wpspeedo-team' ),
            'description' => __( 'This image size is used for modal, expand, panel & single layouts globally for all shortcodes, unless it is overridden from the specific shortcode', 'wpspeedo-team' ),
            'label_block' => true,
            'type'        => Controls_Manager::SELECT,
            'render_type' => 'template',
            'separator'   => 'before',
            'default'     => 'full',
            'options'     => Utils::get_registered_image_sizes(),
            'placeholder' => __( 'Select Size', 'wpspeedo-team' ),
        ] );
        $this->end_controls_section();
    }
    
    protected function archive_settings()
    {
        $this->start_controls_section( 'archive_settings_section', [
            'label'      => __( 'Archive & Taxonomy Settings', 'wpspeedo-team' ),
            'menu_label' => __( 'Archive', 'wpspeedo-team' ),
            'icon'       => 'fas fa-archive',
            'path'       => 'archive',
        ] );
        $this->add_control( 'enable_archive', [
            'label'       => __( 'Enable Single/Archive Page', 'wpspeedo-team' ),
            'label_block' => false,
            'separator'   => 'none',
            'type'        => Controls_Manager::SWITCHER,
            'default'     => true,
        ] );
        $this->add_control( 'post_type_slug', [
            'label'       => __( 'Archive Slug', 'wpspeedo-team' ),
            'label_block' => false,
            'separator'   => 'none',
            'type'        => Controls_Manager::TEXT,
            'default'     => Utils::get_archive_slug(),
            'condition'   => [
            'enable_archive' => true,
        ],
        ] );
        // Group Archive Slug Settings
        $this->add_control( 'enable_group_taxonomy', [
            'label'       => __( 'Enable Group Taxonomy', 'wpspeedo-team' ),
            'label_block' => false,
            'separator'   => 'before',
            'type'        => Controls_Manager::SWITCHER,
            'default'     => true,
        ] );
        $this->add_control( 'enable_group_archive', [
            'label'       => __( 'Enable Group Archive', 'wpspeedo-team' ),
            'label_block' => false,
            'separator'   => 'none',
            'type'        => Controls_Manager::SWITCHER,
            'default'     => false,
            'condition'   => [
            'enable_archive'        => true,
            'enable_group_taxonomy' => true,
        ],
        ] );
        $this->add_control( 'group_slug', [
            'label'       => __( 'Group Archive Slug', 'wpspeedo-team' ),
            'label_block' => false,
            'separator'   => 'none',
            'type'        => Controls_Manager::TEXT,
            'default'     => Utils::get_group_archive_slug(),
            'condition'   => [
            'enable_archive'        => true,
            'enable_group_taxonomy' => true,
            'enable_group_archive'  => true,
        ],
        ] );
        // Location Archive Slug Settings
        $this->add_control( 'enable_location_taxonomy', [
            'label'       => __( 'Enable Location Taxonomy', 'wpspeedo-team' ),
            'label_block' => true,
            'separator'   => 'before',
            'type'        => Controls_Manager::UPGRADE_NOTICE,
            'condition'   => [
            'enable_archive' => true,
        ],
        ] );
        // Language Archive Slug Settings
        $this->add_control( 'enable_language_taxonomy', [
            'label'       => __( 'Enable Language Taxonomy', 'wpspeedo-team' ),
            'label_block' => true,
            'separator'   => 'before',
            'type'        => Controls_Manager::UPGRADE_NOTICE,
            'condition'   => [
            'enable_archive' => true,
        ],
        ] );
        // Specialty Archive Slug Settings
        $this->add_control( 'enable_specialty_taxonomy', [
            'label'       => __( 'Enable Specialty Taxonomy', 'wpspeedo-team' ),
            'label_block' => true,
            'separator'   => 'before',
            'type'        => Controls_Manager::UPGRADE_NOTICE,
            'condition'   => [
            'enable_archive' => true,
        ],
        ] );
        // Gender Archive Slug Settings
        $this->add_control( 'enable_gender_taxonomy', [
            'label'       => __( 'Enable Gender Taxonomy', 'wpspeedo-team' ),
            'label_block' => true,
            'separator'   => 'before',
            'type'        => Controls_Manager::UPGRADE_NOTICE,
            'condition'   => [
            'enable_archive' => true,
        ],
        ] );
        $this->end_controls_section();
    }
    
    protected function localization_settings()
    {
        $this->start_controls_section( 'localization_settings_section', [
            'label'      => __( 'Localization Settings', 'wpspeedo-team' ),
            'menu_label' => __( 'Localization', 'wpspeedo-team' ),
            'icon'       => 'fas fa-file-word',
            'path'       => 'localization',
        ] );
        $this->add_control( 'member_single_name', [
            'label'       => __( 'Member Single Name', 'wpspeedo-team' ),
            'label_block' => false,
            'separator'   => 'none',
            'type'        => Controls_Manager::TEXT,
            'placeholder' => 'Member',
            'default'     => 'Member',
        ] );
        $this->add_control( 'member_plural_name', [
            'label'       => __( 'Member Plural Name', 'wpspeedo-team' ),
            'label_block' => false,
            'separator'   => 'none',
            'type'        => Controls_Manager::TEXT,
            'placeholder' => 'Members',
            'default'     => 'Members',
        ] );
        $this->add_control( 'group_single_name', [
            'label'       => __( 'Group Single Name', 'wpspeedo-team' ),
            'label_block' => false,
            'separator'   => 'before',
            'type'        => Controls_Manager::TEXT,
            'placeholder' => 'Group',
            'default'     => 'Group',
            'condition'   => [
            'enable_group_taxonomy' => true,
        ],
        ] );
        $this->add_control( 'group_plural_name', [
            'label'       => __( 'Group Plural Name', 'wpspeedo-team' ),
            'label_block' => false,
            'separator'   => 'none',
            'type'        => Controls_Manager::TEXT,
            'placeholder' => 'Groups',
            'default'     => 'Groups',
            'condition'   => [
            'enable_group_taxonomy' => true,
        ],
        ] );
        $this->add_control( 'location_single_name', [
            'label'       => Utils::trans( 'location-single-name' ),
            'label_block' => true,
            'separator'   => 'before',
            'type'        => Controls_Manager::UPGRADE_NOTICE,
        ] );
        $this->add_control( 'location_plural_name', [
            'label'       => Utils::trans( 'location-plural-name' ),
            'label_block' => true,
            'separator'   => 'none',
            'type'        => Controls_Manager::UPGRADE_NOTICE,
        ] );
        $this->add_control( 'language_single_name', [
            'label'       => Utils::trans( 'language-single-name' ),
            'label_block' => true,
            'separator'   => 'before',
            'type'        => Controls_Manager::UPGRADE_NOTICE,
        ] );
        $this->add_control( 'language_plural_name', [
            'label'       => Utils::trans( 'language-plural-name' ),
            'label_block' => true,
            'separator'   => 'none',
            'type'        => Controls_Manager::UPGRADE_NOTICE,
        ] );
        $this->add_control( 'specialty_single_name', [
            'label'       => Utils::trans( 'specialty-single-name' ),
            'label_block' => true,
            'separator'   => 'before',
            'type'        => Controls_Manager::UPGRADE_NOTICE,
        ] );
        $this->add_control( 'specialty_plural_name', [
            'label'       => Utils::trans( 'specialty-plural-name' ),
            'label_block' => true,
            'separator'   => 'none',
            'type'        => Controls_Manager::UPGRADE_NOTICE,
        ] );
        $this->add_control( 'gender_single_name', [
            'label'       => Utils::trans( 'gender-single-name' ),
            'label_block' => true,
            'separator'   => 'before',
            'type'        => Controls_Manager::UPGRADE_NOTICE,
        ] );
        $this->add_control( 'gender_plural_name', [
            'label'       => Utils::trans( 'gender-plural-name' ),
            'label_block' => true,
            'separator'   => 'none',
            'type'        => Controls_Manager::UPGRADE_NOTICE,
        ] );
        $this->add_control( 'link_1_label', [
            'label'       => Utils::trans( 'link-1-label' ),
            'label_block' => true,
            'separator'   => 'before',
            'type'        => Controls_Manager::UPGRADE_NOTICE,
        ] );
        $this->add_control( 'link_1_btn_text', [
            'label'       => Utils::trans( 'link-1-btn-text' ),
            'label_block' => true,
            'type'        => Controls_Manager::UPGRADE_NOTICE,
        ] );
        $this->add_control( 'link_2_label', [
            'label'       => Utils::trans( 'link-2-label' ),
            'label_block' => true,
            'separator'   => 'before',
            'type'        => Controls_Manager::UPGRADE_NOTICE,
        ] );
        $this->add_control( 'link_2_btn_text', [
            'label'       => Utils::trans( 'link-2-btn-text' ),
            'label_block' => true,
            'type'        => Controls_Manager::UPGRADE_NOTICE,
        ] );
        $this->add_control( 'mobile_meta_label', [
            'label'       => Utils::trans( 'mobile-meta-label' ),
            'label_block' => false,
            'separator'   => 'before',
            'type'        => Controls_Manager::TEXT,
            'placeholder' => 'Mobile:',
            'default'     => 'Mobile:',
        ] );
        $this->add_control( 'phone_meta_label', [
            'label'       => Utils::trans( 'phone-meta-label' ),
            'label_block' => false,
            'separator'   => 'before',
            'type'        => Controls_Manager::TEXT,
            'placeholder' => 'Phone:',
            'default'     => 'Phone:',
        ] );
        $this->add_control( 'email_meta_label', [
            'label'       => Utils::trans( 'email-meta-label' ),
            'label_block' => false,
            'separator'   => 'before',
            'type'        => Controls_Manager::TEXT,
            'placeholder' => 'Email:',
            'default'     => 'Email:',
        ] );
        $this->add_control( 'website_meta_label', [
            'label'       => Utils::trans( 'website-meta-label' ),
            'label_block' => false,
            'separator'   => 'before',
            'type'        => Controls_Manager::TEXT,
            'placeholder' => 'Website:',
            'default'     => 'Website:',
        ] );
        $this->add_control( 'experience_meta_label', [
            'label'       => Utils::trans( 'experience-meta-label' ),
            'label_block' => false,
            'separator'   => 'before',
            'type'        => Controls_Manager::TEXT,
            'placeholder' => 'Experience:',
            'default'     => 'Experience:',
        ] );
        $this->add_control( 'company_meta_label', [
            'label'       => Utils::trans( 'company-meta-label' ),
            'label_block' => false,
            'separator'   => 'before',
            'type'        => Controls_Manager::TEXT,
            'placeholder' => 'Company:',
            'default'     => 'Company:',
        ] );
        $this->add_control( 'group_meta_label', [
            'label'       => Utils::trans( 'group-meta-label' ),
            'label_block' => false,
            'separator'   => 'before',
            'type'        => Controls_Manager::TEXT,
            'placeholder' => 'Group:',
            'default'     => 'Group:',
        ] );
        $this->add_control( 'location_meta_label', [
            'label'       => Utils::trans( 'location-meta-label' ),
            'label_block' => true,
            'separator'   => 'before',
            'type'        => Controls_Manager::UPGRADE_NOTICE,
        ] );
        $this->add_control( 'language_meta_label', [
            'label'       => Utils::trans( 'language-meta-label' ),
            'label_block' => true,
            'separator'   => 'before',
            'type'        => Controls_Manager::UPGRADE_NOTICE,
        ] );
        $this->add_control( 'specialty_meta_label', [
            'label'       => Utils::trans( 'specialty-meta-label' ),
            'label_block' => true,
            'separator'   => 'before',
            'type'        => Controls_Manager::UPGRADE_NOTICE,
        ] );
        $this->add_control( 'gender_meta_label', [
            'label'       => Utils::trans( 'gender-meta-label' ),
            'label_block' => true,
            'separator'   => 'before',
            'type'        => Controls_Manager::UPGRADE_NOTICE,
        ] );
        $this->end_controls_section();
    }

}