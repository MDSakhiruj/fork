<?php

namespace WPSpeedo_Team;

if ( !defined( 'ABSPATH' ) ) {
    exit;
}
class Shortcode_Loader extends Attribute_Manager
{
    use  Setting_Methods ;
    public  $settings = array() ;
    public  $mode ;
    public  $id ;
    public  $posts = array() ;
    function __construct( $args )
    {
        $this->id = $args['id'];
        $this->mode = $args['mode'];
        $this->settings = $args['settings'];
        // Required for Assets Building
        $this->settings['id'] = $this->id;
        $this->set_attributes();
    }
    
    public function load_template()
    {
        
        if ( $this->mode == 'preview' ) {
            $this->load_editor_template();
            $assets_model = plugin()->assets->set_settings( $this->settings );
            $assets_model->load_fonts_on_preview( $this->settings );
            $css = $assets_model->get_custom_css( $this->id );
            if ( !empty($css) ) {
                printf( '<style class="%s">%s</style>', 'wpspeedo-team--customized', $css );
            }
        } else {
            if ( empty(plugin()->assets->get_data()) ) {
                plugin()->assets->build( $this->settings )->force_enqueue();
            }
            $this->load_public_template();
        }
    
    }
    
    public function set_attributes()
    {
        $theme = $this->get_setting( 'theme' );
        $display_type = $this->get_setting( 'display_type' );
        $card_action = $this->get_setting( 'card_action' );
        $layout_mode = $this->get_setting( 'layout_mode' );
        if ( $card_action == 'expand' && in_array( $display_type, [ 'carousel', 'filter' ] ) ) {
            $display_type = 'grid';
        }
        if ( !in_array( $theme, Utils::get_active_themes() ) ) {
            $theme = 'square-01';
        }
        
        if ( !in_array( $theme, Utils::get_group_themes( 'table' ) ) ) {
            $this->add_attribute( 'single_item_row', 'class', 'wps-row' );
            $this->add_attribute( 'single_item_col', 'class', 'wps-col' );
        }
        
        
        if ( in_array( $theme, Utils::get_group_themes( 'table' ) ) ) {
            $this->add_attribute( 'single_item_row', 'class', 'wps-row wps-table' );
            $this->add_attribute( 'single_item_col', 'class', 'wps-col' );
            if ( $display_type == 'carousel' ) {
                $display_type = 'grid';
            }
        }
        
        $this->add_attribute( 'wrapper', 'id', $this->get_shortcode_id( $this->id ) );
        $this->add_attribute( 'wrapper', 'class', [
            'wps-container wps-widget wps-widget--team',
            'wps-team-theme--' . $theme,
            'wps-team-type--' . $display_type,
            'wps-team-card-action--' . $card_action
        ] );
        $this->add_attribute( 'wrapper_inner', 'class', [ 'wps-container--inner' ] );
        
        if ( $display_type == 'carousel' ) {
            $this->add_attribute( 'wrapper_inner', 'class', 'swiper' );
            $this->add_attribute( 'single_item_row', 'class', 'swiper-wrapper' );
            $this->add_attribute( 'single_item_col', 'class', 'swiper-slide' );
            if ( wp_validate_boolean( $this->get_setting( 'dots' ) ) ) {
                $this->add_attribute( 'wrapper', 'class', 'wps-team--carousel-has-dots' );
            }
            if ( wp_validate_boolean( $this->get_setting( 'navs' ) ) ) {
                $this->add_attribute( 'wrapper', 'class', 'wps-team--carousel-has-navs' );
            }
        }
        
        
        if ( in_array( $display_type, [ 'carousel' ] ) ) {
            $carousel_settings = [
                'columns'                   => (int) $this->get_setting( 'columns' ),
                'columns_tablet'            => (int) $this->get_setting( 'columns_tablet' ),
                'columns_small_tablet'      => (int) $this->get_setting( 'columns_small_tablet' ),
                'columns_mobile'            => (int) $this->get_setting( 'columns_mobile' ),
                'gap'                       => (int) $this->get_setting( 'gap' ),
                'gap_tablet'                => (int) $this->get_setting( 'gap_tablet' ),
                'gap_small_tablet'          => (int) $this->get_setting( 'gap_small_tablet' ),
                'gap_mobile'                => (int) $this->get_setting( 'gap_mobile' ),
                'gap_vertical'              => (int) $this->get_setting( 'gap_vertical' ),
                'gap_vertical_tablet'       => (int) $this->get_setting( 'gap_vertical_tablet' ),
                'gap_vertical_small_tablet' => (int) $this->get_setting( 'gap_vertical_small_tablet' ),
                'gap_vertical_mobile'       => (int) $this->get_setting( 'gap_vertical_mobile' ),
                'speed'                     => (int) $this->get_setting( 'speed' ),
                'dots'                      => wp_validate_boolean( $this->get_setting( 'dots' ) ),
                'navs'                      => wp_validate_boolean( $this->get_setting( 'navs' ) ),
                'loop'                      => wp_validate_boolean( $this->get_setting( 'loop' ) ),
            ];
            $this->add_attribute( 'wrapper', 'data-carousel-settings', json_encode( $carousel_settings ) );
        }
        
        
        if ( in_array( $display_type, [ 'filter' ] ) ) {
            $filter_settings = [
                'layout_mode'      => $layout_mode,
                'transition_speed' => (int) $this->get_setting( 'filter_animation_speed' ),
                'multi_select'     => (bool) $this->get_setting( 'multi_select' ),
            ];
            $this->add_attribute( 'wrapper', 'data-filter-settings', json_encode( $filter_settings ) );
            if ( $filter_settings['multi_select'] ) {
                $this->add_attribute( 'wrapper', 'class', 'wps-team--multi-select' );
            }
        }
        
        $this->set_social_attributes();
    }
    
    public function set_social_attributes()
    {
        $theme = $this->get_setting( 'theme' );
        $theme_defaults = [];
        if ( in_array( $theme, [
            'square-02',
            'square-03',
            'square-05',
            'square-08'
        ] ) ) {
            $theme_defaults['shape'] = 'radius';
        }
        
        if ( in_array( $theme, [
            'square-02',
            'square-03',
            'square-05',
            'circle-01'
        ] ) ) {
            $theme_defaults['bg_color_type'] = 'custom';
            $theme_defaults['bg_color_type_hover'] = 'brand';
            $theme_defaults['color_type'] = 'brand';
            $theme_defaults['color_type_hover'] = 'custom';
        }
        
        
        if ( in_array( $theme, [ 'square-08', 'square-09' ] ) ) {
            $theme_defaults['bg_color_type'] = 'custom';
            $theme_defaults['bg_color_type_hover'] = 'custom';
            $theme_defaults['color_type'] = 'custom';
            $theme_defaults['color_type_hover'] = 'custom';
        }
        
        $setting_atts = [
            'shape'               => $this->get_setting( 'social_links_shape' ),
            'bg_color_type'       => $this->get_setting( 'social_links_bg_color_type' ),
            'bg_color_type_hover' => $this->get_setting( 'social_links_bg_color_type_hover' ),
            'color_type'          => $this->get_setting( 'social_links_color_type' ),
            'color_type_hover'    => $this->get_setting( 'social_links_color_type_hover' ),
        ];
        $social_classes = Utils::get_social_classes( $theme_defaults, $setting_atts );
        $this->add_attribute( 'social', 'class', $social_classes );
    }
    
    private function load_editor_template()
    {
        ?>
        <!DOCTYPE html>
        <html <?php 
        language_attributes();
        ?>>
            <head>
                <meta charset="<?php 
        bloginfo( 'charset' );
        ?>">
                <meta name="viewport" content="width=device-width, initial-scale=1">
                <?php 
        wp_head();
        ?>
            </head>
            <body <?php 
        body_class();
        ?>>
                <div class='wps-widget--preview_wrapper'>
                    <?php 
        $this->init_template();
        ?>
                </div>
                <?php 
        wp_footer();
        ?>
            </body>
        </html>
        <?php 
    }
    
    private function load_public_template()
    {
        $this->init_template();
    }
    
    public function get_tax_queries()
    {
        $tax_query = [];
        // Include By Group
        $include_by_group = (array) $this->get_setting( 'include_by_group' );
        if ( !empty($include_by_group) ) {
            $tax_query[] = [
                'taxonomy' => Utils::group_taxonomy_name(),
                'field'    => 'term_id',
                'terms'    => $include_by_group,
            ];
        }
        // Exclude By Group
        $exclude_by_group = (array) $this->get_setting( 'exclude_by_group' );
        $exclude_by_group = array_diff( $exclude_by_group, $include_by_group );
        if ( !empty($exclude_by_group) ) {
            $tax_query[] = [
                'taxonomy' => Utils::group_taxonomy_name(),
                'field'    => 'term_id',
                'terms'    => $exclude_by_group,
                'operator' => 'NOT IN',
            ];
        }
        return $tax_query;
    }
    
    public function set_posts()
    {
        $query_args = [
            'orderby' => $this->get_setting( 'orderby' ),
            'order'   => $this->get_setting( 'order' ),
        ];
        if ( !$this->get_setting( 'show_all' ) ) {
            $query_args['posts_per_page'] = (int) $this->get_setting( 'limit' );
        }
        if ( !empty($tax_query = $this->get_tax_queries()) ) {
            $query_args['tax_query'] = $tax_query;
        }
        if ( !empty($query_args['orderby']) && $query_args['orderby'] === 'menu_order' ) {
            $query_args['orderby'] = 'date';
        }
        $this->posts = Utils::get_posts( $query_args );
    }
    
    public function get_posts()
    {
        return $this->posts;
    }
    
    public function modify_excerpt_length()
    {
        return Utils::get_description_length();
    }
    
    public function init_template()
    {
        $theme = $this->get_setting( 'theme' );
        $card_action = $this->get_setting( 'card_action' );
        $display_type = $this->get_setting( 'display_type' );
        $thumbnail_size = $this->get_setting( 'thumbnail_size' );
        $widget_custom_class = $this->get_setting( 'container_custom_class' );
        $description_length = Utils::get_description_length();
        $fix_broken_words = $this->get_setting( 'fix_broken_words' );
        add_filter( 'excerpt_length', [ $this, 'modify_excerpt_length' ], 999 );
        if ( $card_action == 'expand' && in_array( $display_type, [ 'carousel', 'filter' ] ) ) {
            $display_type = 'grid';
        }
        $this->set_posts();
        if ( !in_array( $theme, Utils::get_active_themes() ) ) {
            $theme = 'square-01';
        }
        if ( !empty($widget_custom_class = trim( $widget_custom_class )) ) {
            $this->add_attribute( 'wrapper', 'class', $widget_custom_class );
        }
        $shortcode_loader = $this;
        include Utils::load_template( "template-{$theme}.php" );
        remove_filter( 'excerpt_length', [ $this, 'modify_excerpt_length' ], 999 );
        wp_reset_query();
    }

}