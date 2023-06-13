<?php

namespace WPSpeedo_Team;

if ( !defined( 'ABSPATH' ) ) {
    exit;
}
if ( !class_exists( '\\WPSpeedo_Team\\Assets_Manager' ) ) {
    require_once WPS_TEAM_INC_PATH . 'managers/assets-manager.php';
}
class Assets extends Assets_Manager
{
    use  Setting_Methods ;
    public  $settings ;
    public function __construct()
    {
        $this->set_ajax_scope_hooks( '_assets_handler' );
        parent::__construct();
    }
    
    public function is_preview()
    {
        return Utils::is_shortcode_preview();
    }
    
    public function get_assets_key()
    {
        return 'wps-team';
    }
    
    public function asset_handler()
    {
        return 'wpspeedo-team';
    }
    
    public function build_assets_data( array $settings )
    {
        $this->settings = $settings;
        $display_type = $this->get_setting( 'display_type' );
        $card_action = $this->get_setting( 'card_action' );
        $this->add_item_in_asset_list( 'styles', $this->asset_handler() );
        $this->add_item_in_asset_list( 'scripts', $this->asset_handler(), [ 'jquery' ] );
        
        if ( $display_type == 'carousel' ) {
            $this->add_item_in_asset_list( 'styles', $this->asset_handler(), [ 'wpspeedo-swiper' ] );
            $this->add_item_in_asset_list( 'scripts', $this->asset_handler(), [ 'wpspeedo-swiper' ] );
        }
        
        if ( $display_type == 'filter' ) {
            $this->add_item_in_asset_list( 'scripts', $this->asset_handler(), [ 'wpspeedo-isotope' ] );
        }
        $css = $this->get_custom_css( $settings['id'] );
        if ( !empty($css) ) {
            $this->add_item_in_asset_list( 'styles', 'inline', $css );
        }
    }
    
    public function build_assets_data_preview()
    {
        $this->add_item_in_asset_list( 'styles', $this->asset_handler(), [ 'wpspeedo-swiper' ] );
        $this->add_item_in_asset_list( 'scripts', $this->asset_handler(), [ 'jquery', 'wpspeedo-swiper', 'wpspeedo-isotope' ] );
    }
    
    public function get_widget_fonts( $settings )
    {
        $fonts = [];
        if ( !empty($settings['typo_name_font_family']) ) {
            $fonts[] = $settings['typo_name_font_family']['value'];
        }
        if ( !empty($settings['typo_desig_font_family']) ) {
            $fonts[] = $settings['typo_desig_font_family']['value'];
        }
        if ( !empty($settings['typo_content_font_family']) ) {
            $fonts[] = $settings['typo_content_font_family']['value'];
        }
        if ( !empty($settings['typo_meta_font_family']) ) {
            $fonts[] = $settings['typo_meta_font_family']['value'];
        }
        if ( empty($fonts) ) {
            $fonts = [ 'Cambo', 'Roboto', 'Fira Sans' ];
        }
        return $fonts;
    }
    
    public function public_scripts()
    {
        
        if ( $this->is_preview() ) {
            $this->force_enqueue_assets();
            return;
        }
        
        $this->register_assets();
        $this->enqueue();
        
        if ( is_singular( 'wps-team-members' ) || is_post_type_archive( 'wps-team-members' ) || is_tax( [ 'wps-team-group' ] ) ) {
            wp_enqueue_style( $this->asset_handler() );
            wp_enqueue_script( $this->asset_handler() );
        }
    
    }
    
    public function register_assets()
    {
        wp_register_style(
            'wpspeedo-fontawesome--all',
            WPS_TEAM_ASSET_URL . 'libs/fontawesome/css/all.min.css',
            '',
            WPS_TEAM_VERSION
        );
        wp_register_style(
            'wpspeedo-swiper',
            WPS_TEAM_ASSET_URL . 'libs/swiper/swiper-bundle.min.css',
            [],
            WPS_TEAM_VERSION
        );
        wp_register_script(
            'wpspeedo-swiper',
            WPS_TEAM_ASSET_URL . 'libs/swiper/swiper-bundle.min.js',
            [],
            WPS_TEAM_VERSION,
            true
        );
        wp_register_script(
            'wpspeedo-isotope',
            WPS_TEAM_ASSET_URL . 'libs/isotope/isotope.min.js',
            [],
            WPS_TEAM_VERSION,
            true
        );
        wp_register_style(
            $this->asset_handler(),
            WPS_TEAM_ASSET_URL . 'css/style.min.css',
            [ 'wpspeedo-fontawesome--all' ],
            WPS_TEAM_VERSION
        );
        wp_register_script(
            $this->asset_handler(),
            WPS_TEAM_ASSET_URL . 'js/script.min.js',
            [ 'jquery' ],
            WPS_TEAM_VERSION,
            true
        );
        wp_register_style(
            $this->asset_handler() . '-preview',
            WPS_TEAM_ASSET_URL . 'admin/css/preview.min.css',
            [ $this->asset_handler() ],
            WPS_TEAM_VERSION
        );
        wp_register_script(
            $this->asset_handler() . '-preview',
            WPS_TEAM_ASSET_URL . 'admin/js/preview.min.js',
            [ $this->asset_handler(), 'underscore' ],
            WPS_TEAM_VERSION,
            true
        );
        $data = [
            'is_pro' => wp_validate_boolean( wps_team_fs()->can_use_premium_code__premium_only() ),
        ];
        wp_localize_script( $this->asset_handler() . '-preview', '_wps_team_data', $data );
    }
    
    public function generate_css( $shortcode_id )
    {
        $selector = $this->shortcode_selector( $shortcode_id );
        $this->add_responsive_style(
            $selector,
            '--wps-container-width: {{value}}{{unit}}',
            'container_width',
            [ 'value', 'unit' ]
        );
        $this->add_responsive_style( $selector, '--wps-item-col-gap-alt: calc(-{{value}}px/2)', 'gap' );
        $this->add_responsive_style( $selector, '--wps-item-col-gap: calc({{value}}px/2)', 'gap' );
        $this->add_responsive_style(
            $selector,
            '--wps-item-col-gap-vert: calc({{value}}px/2)',
            'gap_vertical',
            'value',
            'gap'
        );
        $this->add_responsive_style( $selector, '--wps-item-col-width: calc((100%/{{value}}) - 0.1px)', 'columns' );
        $this->add_background_style( $selector, 'item_background_', '--wps-item-bg-color' );
        $this->add_responsive_style( $selector, '--wps-title-color: {{value}}', 'title_color' );
        $this->add_responsive_style( $selector, '--wps-title-color-hover: {{value}}', 'title_color_hover' );
        $this->add_responsive_style( $selector, '--wps-desig-color: {{value}}', 'designation_color' );
        $this->add_responsive_style( $selector, '--wps-text-color: {{value}}', 'desc_color' );
        $this->add_responsive_style( $selector . ' .wps-team--divider', '--wps-divider-bg-color: {{value}}', 'divider_color' );
        $this->add_style( $selector, '--wps-info-icon-color: {{value}}', 'info_icon_color' );
        $this->add_style( $selector, '--wps-info-text-color: {{value}}', 'info_text_color' );
        $this->add_style( $selector, '--wps-info-link-color: {{value}}', 'info_link_color' );
        $this->add_style( $selector, '--wps-info-link-hover-color: {{value}}', 'info_link_hover_color' );
        $this->add_style( $selector, '--wps-thumb-object-pos: {{value}}', 'thumbnail_position' );
    }

}