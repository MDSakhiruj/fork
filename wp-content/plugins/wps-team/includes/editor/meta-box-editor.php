<?php

namespace WPSpeedo_Team;

if ( !defined( 'ABSPATH' ) ) {
    exit;
}
class Meta_Box_Editor extends Editor_Controls
{
    public function __construct( array $data = array(), array $args = null )
    {
        parent::__construct( $data, $args );
        do_action( 'wpspeedo_team/metabox_editor/init', $this );
    }
    
    public function get_name()
    {
        return 'meta_box_editor';
    }
    
    protected function _register_controls()
    {
        $this->personal_info();
        $this->social_links();
        $this->skills();
    }
    
    protected function personal_info()
    {
        $this->start_controls_section( 'personal_info_section', [
            'label' => __( 'Personal Information', 'wpspeedo-team' ),
        ] );
        $this->add_control( '_designation', [
            'label'       => __( 'Designation', 'wpspeedo-team' ),
            'label_block' => false,
            'separator'   => 'none',
            'type'        => Controls_Manager::TEXT,
        ] );
        $this->add_control( '_email', [
            'label'       => __( 'Email Address', 'wpspeedo-team' ),
            'label_block' => false,
            'separator'   => 'none',
            'type'        => Controls_Manager::TEXT,
        ] );
        $this->add_control( '_mobile', [
            'label'       => __( 'Mobile (Personal)', 'wpspeedo-team' ),
            'label_block' => false,
            'separator'   => 'none',
            'type'        => Controls_Manager::TEXT,
        ] );
        $this->add_control( '_telephone', [
            'label'       => __( 'Telephone (Office)', 'wpspeedo-team' ),
            'label_block' => false,
            'separator'   => 'none',
            'type'        => Controls_Manager::TEXT,
        ] );
        $this->add_control( '_experience', [
            'label'       => __( 'Years of Experience', 'wpspeedo-team' ),
            'label_block' => false,
            'separator'   => 'none',
            'type'        => Controls_Manager::TEXT,
        ] );
        $this->add_control( '_website', [
            'label'       => __( 'Website', 'wpspeedo-team' ),
            'label_block' => false,
            'separator'   => 'none',
            'type'        => Controls_Manager::TEXT,
        ] );
        $this->add_control( '_company', [
            'label'       => __( 'Company', 'wpspeedo-team' ),
            'label_block' => false,
            'separator'   => 'none',
            'type'        => Controls_Manager::TEXT,
        ] );
        $this->add_control( '_ribbon', [
            'label'       => __( 'Ribbon / Tag', 'wpspeedo-team' ),
            'label_block' => false,
            'separator'   => 'none',
            'type'        => Controls_Manager::TEXT,
        ] );
        $this->add_control( '_link_1', [
            'label'       => Utils::get_setting( 'link_1_label', 'Resume Link' ),
            'label_block' => false,
            'separator'   => 'none',
            'type'        => Controls_Manager::UPGRADE_NOTICE,
        ] );
        $this->add_control( '_link_2', [
            'label'       => Utils::get_setting( 'link_2_label', 'Hire Link' ),
            'label_block' => false,
            'separator'   => 'none',
            'type'        => Controls_Manager::UPGRADE_NOTICE,
        ] );
        $this->add_control( '_color', [
            'label'       => __( 'Color', 'wpspeedo-team' ),
            'label_block' => false,
            'separator'   => 'none',
            'type'        => Controls_Manager::COLOR,
        ] );
        $this->end_controls_section();
    }
    
    protected function social_links()
    {
        $this->start_controls_section( 'social_links', [
            'label' => __( 'Social Links', 'wpspeedo-team' ),
        ] );
        $repeater = new Repeater();
        $repeater->add_control( 'social_icon', [
            'type'        => Controls_Manager::ICON,
            'label_block' => true,
            'separator'   => 'none',
            'placeholder' => __( 'Icon', 'wpspeedo-team' ),
        ] );
        $repeater->add_control( 'social_link', [
            'type'        => Controls_Manager::TEXT,
            'label_block' => true,
            'separator'   => 'none',
            'placeholder' => __( 'Link', 'wpspeedo-team' ),
        ] );
        $this->add_control( '_social_links', [
            'type'    => Controls_Manager::REPEATER,
            'fields'  => $repeater->get_fields(),
            'class'   => 'wps-field-group--repeater',
            'default' => [],
        ] );
        $this->end_controls_section();
    }
    
    protected function skills()
    {
        $this->start_controls_section( 'skills', [
            'label' => __( 'Skills', 'wpspeedo-team' ),
        ] );
        $repeater = new Repeater();
        $repeater->add_control( 'skill_name', [
            'type'        => Controls_Manager::TEXT,
            'label_block' => true,
            'separator'   => 'none',
            'placeholder' => __( 'Skill Name', 'wpspeedo-team' ),
        ] );
        $repeater->add_control( 'skill_val', [
            'type'        => Controls_Manager::NUMBER,
            'label_block' => true,
            'separator'   => 'none',
            'min'         => 0,
            'max'         => 100,
            'step'        => 5,
        ] );
        $this->add_control( '_skills', [
            'type'    => Controls_Manager::REPEATER,
            'fields'  => $repeater->get_fields(),
            'class'   => 'wps-field-group--repeater',
            'default' => [],
        ] );
        $this->end_controls_section();
    }

}