<?php

namespace WPSpeedo_Team;

use  WP_Query, WP_Error ;
if ( !defined( 'ABSPATH' ) ) {
    exit;
}
class Utils
{
    static function get_posts_meta_cache_key( $meta_key, $post_type = null )
    {
        if ( empty($post_type) ) {
            $post_type = self::post_type_name();
        }
        return sprintf( 'wps--meta-vals--%s_%s', $post_type, $meta_key );
    }
    
    static function is_external_url( $url )
    {
        $self_data = wp_parse_url( home_url() );
        $url_data = wp_parse_url( $url );
        if ( $self_data['host'] == $url_data['host'] ) {
            return false;
        }
        return true;
    }
    
    static function get_ext_url_params()
    {
        return ' rel="nofollow noopener noreferrer" target="_blank"';
    }
    
    static function update_posts_meta_vals( $meta_key, $post_type = null )
    {
        if ( empty($post_type) ) {
            $post_type = self::post_type_name();
        }
        $cache_key = self::get_posts_meta_cache_key( $meta_key, $post_type );
        delete_transient( $cache_key );
        return self::get_posts_meta_vals( $meta_key, $post_type );
    }
    
    static function update_all_posts_meta_vals( $meta_fields = array(), $post_type = null )
    {
        $meta_fields = ( !empty($meta_fields) ? $meta_fields : [ '_ribbon' ] );
        if ( empty($post_type) ) {
            $post_type = self::post_type_name();
        }
        foreach ( $meta_fields as $meta_key ) {
            self::update_posts_meta_vals( $meta_key, $post_type );
        }
    }
    
    static function get_posts_meta_vals( $meta_key, $post_type = null )
    {
        global  $wpdb ;
        if ( empty($post_type) ) {
            $post_type = self::post_type_name();
        }
        $cache_key = self::get_posts_meta_cache_key( $meta_key, $post_type );
        $cache_data = get_transient( $cache_key );
        if ( $cache_data !== false ) {
            return $cache_data;
        }
        $results = $wpdb->get_results( $wpdb->prepare( "\n\t\t\tselect META.meta_value\n\t\t\tfrom {$wpdb->postmeta} AS META\n\t\t\tINNER JOIN {$wpdb->posts} AS POST\n\t\t\tON META.post_id = POST.ID\n\t\t\twhere POST.post_type = %s AND\n\t\t\tPOST.post_status = 'publish' AND\n\t\t\tMETA.meta_key = %s;\n\t\t", $post_type, $meta_key ) );
        
        if ( !empty($results) ) {
            $results = wp_list_pluck( $results, 'meta_value' );
            $results = array_values( array_unique( $results ) );
            set_transient( $cache_key, $results, MINUTE_IN_SECONDS * 10 );
            return $results;
        }
        
        return [];
    }
    
    static function get_posts( $query_args = array() )
    {
        $args = [
            'posts_per_page' => -1,
        ];
        $args = array_merge( $args, $query_args );
        $args = (array) apply_filters( 'wpspeedo_team/query_params', $args );
        $args['post_type'] = Utils::post_type_name();
        return new WP_Query( $args );
    }
    
    static function get_meta_field_keys()
    {
        $field_keys = [
            '_experience',
            '_company',
            '_skills',
            '_designation',
            '_telephone',
            '_email',
            '_website',
            '_social_links',
            '_ribbon',
            '_mobile',
            '_color'
        ];
        return $field_keys;
    }
    
    static function get_item_data( $data_key, $post_id = null )
    {
        if ( empty($post_id) ) {
            $post_id = get_the_ID();
        }
        $meta_fields = self::get_meta_field_keys();
        $taxonomies = self::get_taxonomies( true );
        
        if ( in_array( $data_key, $meta_fields ) ) {
            return get_post_meta( $post_id, $data_key, true );
        } else {
            if ( in_array( $data_key, $taxonomies ) ) {
                return get_the_terms( $post_id, str_replace( '_', '-', $data_key ) );
            }
        }
        
        return false;
    }
    
    static function load_template( $template_name )
    {
        $template_folder = (string) apply_filters( 'wpspeedo_team/template/folder', 'wpspeedo-team' );
        $template_folder = '/' . trailingslashit( ltrim( $template_folder, '/\\' ) );
        // Load from current theme
        $template_path = get_stylesheet_directory() . $template_folder . $template_name;
        if ( file_exists( $template_path ) ) {
            return $template_path;
        }
        // Load from parent theme if not found in child theme
        
        if ( is_child_theme() ) {
            $template_path = get_template_directory() . $template_folder . $template_name;
            if ( file_exists( $template_path ) ) {
                return $template_path;
            }
        }
        
        // Load from plugin site
        $template_path = WPS_TEAM_PATH . 'templates/' . $template_name;
        if ( file_exists( $template_path ) ) {
            return $template_path;
        }
        return new WP_Error( 'wpspeedo_team/template/not_found', __( 'Template file is not found', 'wpspeedo-team' ) );
    }
    
    static function get_temp_settings()
    {
        $temp_key = self::get_shortcode_preview_key();
        if ( $temp_key ) {
            return get_transient( $temp_key );
        }
    }
    
    static function is_shortcode_preview()
    {
        return (bool) (!empty($_REQUEST['wps_team_sh_preview']));
    }
    
    static function get_shortcode_preview_key()
    {
        return ( self::is_shortcode_preview() ? sanitize_text_field( $_REQUEST['wps_team_sh_preview'] ) : null );
    }
    
    public static function render_html_attributes( array $attributes )
    {
        $rendered_attributes = [];
        foreach ( $attributes as $attribute_key => $attribute_values ) {
            if ( is_array( $attribute_values ) ) {
                $attribute_values = implode( ' ', $attribute_values );
            }
            $rendered_attributes[] = sprintf( '%1$s="%2$s"', $attribute_key, esc_attr( $attribute_values ) );
        }
        return implode( ' ', $rendered_attributes );
    }
    
    public static function max_letters( $text, $limit, $broken_words_fix = false )
    {
        
        if ( strlen( $text ) > $limit ) {
            $pos = ( $broken_words_fix ? strpos( $text, ' ', $limit ) : $limit );
            $text = substr( $text, 0, $pos ) . '...';
        }
        
        return $text;
    }
    
    public static function get_brnad_name( $icon )
    {
        return str_replace( [ 'fab fa-', 'far fa-', 'fas fa-' ], '', $icon );
    }
    
    public static function sanitize_phone_number( $phone )
    {
        return preg_replace( '/[^0-9\\-\\_\\+]*/', '', $phone );
    }
    
    public static function default_settings()
    {
        return [
            'enable_archive'            => true,
            'post_type_slug'            => 'wps-members',
            'enable_group_taxonomy'     => true,
            'enable_group_archive'      => false,
            'group_slug'                => 'wps-members-group',
            'enable_location_taxonomy'  => false,
            'enable_location_archive'   => false,
            'location_slug'             => 'wps-members-location',
            'enable_language_taxonomy'  => false,
            'enable_language_archive'   => false,
            'language_slug'             => 'wps-members-language',
            'enable_specialty_taxonomy' => false,
            'enable_specialty_archive'  => false,
            'specialty_slug'            => 'wps-members-specialty',
            'enable_gender_taxonomy'    => false,
            'enable_gender_archive'     => false,
            'gender_slug'               => 'wps-members-gender',
            'member_plural_name'        => self::trans( 'members' ),
            'member_single_name'        => self::trans( 'member' ),
            'group_plural_name'         => self::trans( 'groups' ),
            'group_single_name'         => self::trans( 'group' ),
            'location_plural_name'      => self::trans( 'locations' ),
            'location_single_name'      => self::trans( 'location' ),
            'language_plural_name'      => self::trans( 'languages' ),
            'language_single_name'      => self::trans( 'language' ),
            'specialty_plural_name'     => self::trans( 'specialties' ),
            'specialty_single_name'     => self::trans( 'specialty' ),
            'gender_plural_name'        => self::trans( 'genders' ),
            'gender_single_name'        => self::trans( 'gender' ),
        ];
    }
    
    static function get_registered_image_sizes()
    {
        $sizes = get_intermediate_image_sizes();
        if ( empty($sizes) ) {
            return [];
        }
        $_sizes = [];
        foreach ( $sizes as $size ) {
            $_sizes[] = [
                'label' => ucwords( preg_replace( '/_|-/', ' ', $size ) ),
                'value' => $size,
            ];
        }
        $_sizes = array_merge( $_sizes, [ [
            'label' => __( 'Full', 'wpspeedo-team' ),
            'value' => 'full',
        ] ] );
        return $_sizes;
    }
    
    static function get_thumbnail_position()
    {
        return [ [
            'label' => 'Top',
            'value' => 'top center',
        ], [
            'label' => 'Middle',
            'value' => 'center center',
        ], [
            'label' => 'Bottom',
            'value' => 'bottom center',
        ] ];
    }
    
    public static function get_settings()
    {
        $defaults = self::default_settings();
        $settings = (array) get_option( self::get_option_name(), $defaults );
        $settings = array_merge( $defaults, $settings );
        $fields = [
            'post_type_slug',
            'group_slug',
            'member_plural_name',
            'member_single_name',
            'group_plural_name',
            'group_single_name'
        ];
        foreach ( $fields as $field ) {
            if ( empty($settings[$field]) ) {
                $settings[$field] = $defaults[$field];
            }
        }
        return $settings;
    }
    
    public static function get_setting( $key, $default = '' )
    {
        $settings = self::get_settings();
        
        if ( array_key_exists( $key, $settings ) ) {
            $val = $settings[$key];
            if ( $val === null && !empty($default) ) {
                return $default;
            }
            return $val;
        }
        
        if ( !empty($default) ) {
            return $default;
        }
        return null;
    }
    
    public static function has_archive()
    {
        return wp_validate_boolean( self::get_setting( 'enable_archive' ) );
    }
    
    public static function has_group_archive()
    {
        return wp_validate_boolean( self::get_setting( 'enable_group_archive' ) );
    }
    
    public static function has_location_archive()
    {
        return wp_validate_boolean( self::get_setting( 'enable_location_archive' ) );
    }
    
    public static function has_language_archive()
    {
        return wp_validate_boolean( self::get_setting( 'enable_language_archive' ) );
    }
    
    public static function has_specialty_archive()
    {
        return wp_validate_boolean( self::get_setting( 'enable_specialty_archive' ) );
    }
    
    public static function has_gender_archive()
    {
        return wp_validate_boolean( self::get_setting( 'enable_gender_archive' ) );
    }
    
    public static function group_taxonomy_name( $is_field = false )
    {
        $name = 'wps-team-group';
        if ( $is_field ) {
            return self::to_field_key( $name );
        }
        return $name;
    }
    
    public static function location_taxonomy_name( $is_field = false )
    {
        $name = 'wps-team-location';
        if ( $is_field ) {
            return self::to_field_key( $name );
        }
        return $name;
    }
    
    public static function language_taxonomy_name( $is_field = false )
    {
        $name = 'wps-team-language';
        if ( $is_field ) {
            return self::to_field_key( $name );
        }
        return $name;
    }
    
    public static function specialty_taxonomy_name( $is_field = false )
    {
        $name = 'wps-team-specialty';
        if ( $is_field ) {
            return self::to_field_key( $name );
        }
        return $name;
    }
    
    public static function gender_taxonomy_name( $is_field = false )
    {
        $name = 'wps-team-gender';
        if ( $is_field ) {
            return self::to_field_key( $name );
        }
        return $name;
    }
    
    public static function get_group_archive_slug()
    {
        return self::get_setting( 'group_slug' );
    }
    
    public static function get_location_archive_slug()
    {
        return self::get_setting( 'location_slug' );
    }
    
    public static function get_language_archive_slug()
    {
        return self::get_setting( 'language_slug' );
    }
    
    public static function get_specialty_archive_slug()
    {
        return self::get_setting( 'specialty_slug' );
    }
    
    public static function get_gender_archive_slug()
    {
        return self::get_setting( 'gender_slug' );
    }
    
    public static function get_taxonomies( $is_field = false )
    {
        $taxonomies = [];
        if ( Utils::get_setting( 'enable_group_taxonomy' ) ) {
            $taxonomies[] = self::group_taxonomy_name();
        }
        if ( $is_field ) {
            return array_map( 'self::to_field_key', $taxonomies );
        }
        return $taxonomies;
    }
    
    public static function archive_enabled_taxonomies()
    {
        return [ 'wps-team-group' ];
    }
    
    public static function post_type_name()
    {
        return 'wps-team-members';
    }
    
    public static function to_field_key( $str )
    {
        return str_replace( '-', '_', $str );
    }
    
    public static function get_option_name()
    {
        return 'wps_team_members';
    }
    
    public static function get_archive_slug()
    {
        return self::get_setting( 'post_type_slug' );
    }
    
    public static function flush_rewrite_rules()
    {
        delete_option( self::rewrite_flush_key() );
    }
    
    public static function rewrite_flush_key()
    {
        return 'wps-rewrite--flushed';
    }
    
    public static function get_plugin_icon()
    {
        return WPS_TEAM_URL . '/images/icon.svg';
    }
    
    public static function get_pro_label()
    {
        return __( '(Pro) - ', 'wpspeedo-team' );
    }
    
    public static function get_options_display_type()
    {
        $options = [ [
            'label' => 'Grid',
            'value' => 'grid',
        ], [
            'label' => 'Carousel',
            'value' => 'carousel',
        ], [
            'disabled' => true,
            'label'    => __( 'Filter' ),
            'value'    => 'filter',
        ] ];
        return $options;
    }
    
    public static function get_options_layout_mode()
    {
        $options = [ [
            'label' => __( 'Masonry', 'wpspeedo-team' ),
            'value' => 'masonry',
        ], [
            'label' => __( 'Fit Rows', 'wpspeedo-team' ),
            'value' => 'fitRows',
        ] ];
        return $options;
    }
    
    public static function get_shape_types()
    {
        $options = [
            'circle' => [
            'title' => __( 'Circle', 'wpspeedo-team' ),
            'icon'  => 'fas fa-circle',
        ],
            'square' => [
            'title' => __( 'Square', 'wpspeedo-team' ),
            'icon'  => 'fas fa-square-full',
        ],
            'radius' => [
            'title' => __( 'Radius', 'wpspeedo-team' ),
            'icon'  => 'fas fa-square',
        ],
        ];
        return $options;
    }
    
    public static function get_options_theme()
    {
        $options = [
            [
            'label' => __( 'Square One' ),
            'value' => 'square-01',
        ],
            [
            'label' => __( 'Square Two' ),
            'value' => 'square-02',
        ],
            [
            'label' => __( 'Square Three' ),
            'value' => 'square-03',
        ],
            [
            'label' => __( 'Square Four' ),
            'value' => 'square-04',
        ],
            [
            'label' => __( 'Square Five' ),
            'value' => 'square-05',
        ],
            [
            'disabled' => true,
            'label'    => __( 'Square Six' ),
            'value'    => 'square-06',
        ],
            [
            'disabled' => true,
            'label'    => __( 'Square Seven' ),
            'value'    => 'square-07',
        ],
            [
            'disabled' => true,
            'label'    => __( 'Square Eight' ),
            'value'    => 'square-08',
        ],
            [
            'disabled' => true,
            'label'    => __( 'Square Nine' ),
            'value'    => 'square-09',
        ],
            [
            'disabled' => true,
            'label'    => __( 'Square Ten' ),
            'value'    => 'square-10',
        ],
            [
            'disabled' => true,
            'label'    => __( 'Square Eleven' ),
            'value'    => 'square-11',
        ],
            [
            'disabled' => true,
            'label'    => __( 'Square Twelve' ),
            'value'    => 'square-12',
        ],
            [
            'label' => __( 'Circle One' ),
            'value' => 'circle-01',
        ],
            [
            'disabled' => true,
            'label'    => __( 'Circle Two' ),
            'value'    => 'circle-02',
        ],
            [
            'disabled' => true,
            'label'    => __( 'Circle Three' ),
            'value'    => 'circle-03',
        ],
            [
            'disabled' => true,
            'label'    => __( 'Circle Four' ),
            'value'    => 'circle-04',
        ],
            [
            'disabled' => true,
            'label'    => __( 'Circle Five' ),
            'value'    => 'circle-05',
        ],
            [
            'disabled' => true,
            'label'    => __( 'Circle Six' ),
            'value'    => 'circle-06',
        ],
            [
            'disabled' => true,
            'label'    => __( 'Horiz One' ),
            'value'    => 'horiz-01',
        ],
            [
            'disabled' => true,
            'label'    => __( 'Horiz Two' ),
            'value'    => 'horiz-02',
        ],
            [
            'disabled' => true,
            'label'    => __( 'Horiz Three' ),
            'value'    => 'horiz-03',
        ],
            [
            'disabled' => true,
            'label'    => __( 'Horiz Four' ),
            'value'    => 'horiz-04',
        ],
            [
            'disabled' => true,
            'label'    => __( 'Table One' ),
            'value'    => 'table-01',
        ],
            [
            'disabled' => true,
            'label'    => __( 'Table Two' ),
            'value'    => 'table-02',
        ],
            [
            'disabled' => true,
            'label'    => __( 'Table Three' ),
            'value'    => 'table-03',
        ],
            [
            'disabled' => true,
            'label'    => __( 'Table Four' ),
            'value'    => 'table-04',
        ]
        ];
        return $options;
    }
    
    public static function get_options_card_action()
    {
        $options = [
            [
            'label' => __( 'None', 'wpspeedo-team' ),
            'value' => 'none',
        ],
            [
            'label' => __( 'Single Page', 'wpspeedo-team' ),
            'value' => 'single-page',
        ],
            [
            'disabled' => true,
            'label'    => __( 'Modal', 'wpspeedo-team' ),
            'value'    => 'modal',
        ],
            [
            'disabled' => true,
            'label'    => __( 'Side Panel', 'wpspeedo-team' ),
            'value'    => 'side-panel',
        ],
            [
            'disabled' => true,
            'label'    => __( 'Expand', 'wpspeedo-team' ),
            'value'    => 'expand',
        ],
            [
            'disabled' => true,
            'label'    => self::get_setting( 'link_1_label', 'Resume Link' ),
            'value'    => 'link_1',
        ],
            [
            'disabled' => true,
            'label'    => self::get_setting( 'link_2_label', 'Hire Link' ),
            'value'    => 'link_2',
        ]
        ];
        return $options;
    }
    
    public static function get_options_orderby()
    {
        $options = [
            [
            'label' => __( 'ID', 'wpspeedo-team' ),
            'value' => 'ID',
        ],
            [
            'label' => __( 'Title', 'wpspeedo-team' ),
            'value' => 'title',
        ],
            [
            'label' => __( 'Date', 'wpspeedo-team' ),
            'value' => 'date',
        ],
            [
            'label' => __( 'Random', 'wpspeedo-team' ),
            'value' => 'rand',
        ],
            [
            'label' => __( 'Modified', 'wpspeedo-team' ),
            'value' => 'modified',
        ],
            [
            'disabled' => true,
            'label'    => __( 'Custom Order', 'wpspeedo-team' ),
            'value'    => 'menu_order',
        ]
        ];
        return $options;
    }
    
    public static function get_post_term_slugs( array $term_names, $separator = ' ' )
    {
        global  $post ;
        $terms = [];
        foreach ( $term_names as $term_name ) {
            $_terms = get_the_terms( $post->ID, $term_name );
            if ( !empty($_terms) && !is_wp_error( $_terms ) ) {
                $terms = array_merge( $terms, wp_list_pluck( $_terms, 'slug' ) );
            }
        }
        if ( !empty($terms) ) {
            return implode( $separator, $terms );
        }
        return '';
    }
    
    public static function get_terms( $taxonomy, $args = array() )
    {
        $args = array_merge( [
            'taxonomy'   => $taxonomy,
            'orderby'    => 'name',
            'order'      => 'ASC',
            'hide_empty' => false,
            'include'    => [],
            'exclude'    => [],
        ], $args );
        $terms = get_terms( $args );
        if ( empty($terms) || is_wp_error( $terms ) ) {
            return [];
        }
        return $terms;
    }
    
    public static function get_groups( $args = array() )
    {
        return self::get_terms( self::group_taxonomy_name(), $args );
    }
    
    public static function get_locations( $args = array() )
    {
        return self::get_terms( self::location_taxonomy_name(), $args );
    }
    
    public static function get_languages( $args = array() )
    {
        return self::get_terms( self::language_taxonomy_name(), $args );
    }
    
    public static function get_specialties( $args = array() )
    {
        return self::get_terms( self::specialty_taxonomy_name(), $args );
    }
    
    public static function get_genders( $args = array() )
    {
        return self::get_terms( self::gender_taxonomy_name(), $args );
    }
    
    public static function get_term_options( $terms )
    {
        $terms = wp_list_pluck( $terms, 'name', 'term_id' );
        return self::to_options( $terms );
    }
    
    public static function to_options( array $options )
    {
        $_options = [];
        foreach ( $options as $key => $val ) {
            $_options[] = [
                'label' => $val,
                'value' => $key,
            ];
        }
        return $_options;
    }
    
    public static function get_control_options( $control_id )
    {
        $method = "get_options_{$control_id}";
        $options = self::$method();
        foreach ( $options as &$option ) {
            if ( array_key_exists( 'disabled', $option ) ) {
                $option['label'] = self::get_pro_label() . $option['label'];
            }
        }
        return $options;
    }
    
    public static function get_active_themes()
    {
        $themes = [
            'square-01',
            'square-02',
            'square-03',
            'square-04',
            'square-05',
            'circle-01'
        ];
        return $themes;
    }
    
    public static function get_group_themes( $theme_category )
    {
        $themes = self::get_active_themes();
        return array_filter( $themes, function ( $theme ) use( $theme_category ) {
            return strpos( $theme, $theme_category ) !== false;
        } );
    }
    
    public static function get_wps_team( $shortcode_id )
    {
        return do_shortcode( sprintf( '[wpspeedo-team id=%d]', $shortcode_id ) );
    }
    
    public static function get_top_label_menu()
    {
        return 'edit.php?post_type=' . Utils::post_type_name();
    }
    
    public static function string_to_array( $terms = '' )
    {
        if ( empty($terms) ) {
            return [];
        }
        return (array) array_filter( explode( ',', $terms ) );
    }
    
    public static function get_demo_data_status( $demo_type = '' )
    {
        $status = [
            'post_data'      => wp_validate_boolean( get_option( 'wpspeedo_team_dummy_post_data_created' ) ),
            'shortcode_data' => wp_validate_boolean( get_option( 'wpspeedo_team_dummy_shortcode_data_created' ) ),
        ];
        if ( !empty($demo_type) && array_key_exists( $demo_type, $status ) ) {
            return $status[$demo_type];
        }
        return $status;
    }
    
    public static function get_social_classes( array $initials, array $settings )
    {
        $initials = array_filter( $initials );
        $settings = array_filter( $settings );
        $config = array_merge( [
            'shape'               => 'circle',
            'bg_color_type'       => 'brand',
            'bg_color_type_hover' => 'brand',
            'color_type'          => 'custom',
            'color_type_hover'    => 'custom',
        ], $initials, $settings );
        $social_classes = [ 'wps--social-links' ];
        if ( $config['shape'] ) {
            $social_classes[] = 'wps-si--shape-' . $config['shape'];
        }
        if ( $config['bg_color_type'] === 'brand' ) {
            $social_classes[] = 'wps-si--b-bg-color';
        }
        if ( $config['bg_color_type_hover'] === 'brand' ) {
            $social_classes[] = 'wps-si--b-bg-color--hover';
        }
        if ( $config['bg_color_type'] !== 'brand' && $config['color_type'] === 'brand' ) {
            $social_classes[] = 'wps-si--b-color';
        }
        if ( $config['bg_color_type_hover'] !== 'brand' && $config['color_type_hover'] === 'brand' ) {
            $social_classes[] = 'wps-si--b-color--hover';
        }
        return $social_classes;
    }
    
    public static function get_installed_time()
    {
        $installed_time = get_option( '_wps_team_installed_time' );
        if ( !empty($installed_time) ) {
            return $installed_time;
        }
        $installed_time = time();
        update_option( '_wps_team_installed_time', $installed_time );
        return $installed_time;
    }
    
    public static function get_timestamp_diff( $old_time, $new_time = null )
    {
        if ( $new_time == null ) {
            $new_time = time();
        }
        return ceil( ($new_time - $old_time) / DAY_IN_SECONDS );
    }
    
    function minify_css( $css )
    {
        // https://datayze.com/howto/minify-css-with-php
        $css = preg_replace( '/\\/\\*((?!\\*\\/).)*\\*\\//', '', $css );
        // negative look ahead
        $css = preg_replace( '/\\s{2,}/', ' ', $css );
        $css = preg_replace( '/\\s*([:;{}])\\s*/', '$1', $css );
        $css = preg_replace( '/;}/', '}', $css );
        return $css;
    }
    
    public static function get_post_link_attrs( $post_id, $shortcode_id = null, $action = 'single-page' )
    {
        $attrs = [
            'href'   => '',
            'class'  => '',
            'target' => '',
            'rel'    => '',
        ];
        if ( !Utils::has_archive() && $action === 'single-page' ) {
            return $attrs;
        }
        if ( $action === 'single-page' ) {
            $attrs['href'] = get_the_permalink( $post_id );
        }
        return $attrs;
    }
    
    public static function get_the_title( $post_id, $args = array() )
    {
        $args = shortcode_atts( [
            'card_action' => 'single-page',
            'tag'         => 'h3',
            'class'       => '',
        ], $args );
        $action = $args['card_action'];
        $tag = $args['tag'];
        $title_classes = [ 'wps-team--member-title wps-team--member-element' ];
        if ( !empty($args['class']) ) {
            $title_classes[] = $args['class'];
        }
        if ( !Utils::has_archive() && $action === 'single-page' ) {
            $action = 'none';
        }
        if ( $action !== 'none' ) {
            $title_classes[] = 'team-member--link';
        }
        $html = sprintf( '<%s class="%s">', $tag, implode( ' ', $title_classes ) );
        
        if ( $action === 'none' ) {
            $html .= get_the_title();
        } else {
            $attrs = self::get_post_link_attrs( $post_id, self::shortcode_loader()->id, $action );
            $html .= sprintf(
                '<a href="%s" class="%s" %s %s>%s</a>',
                esc_attr( $attrs['href'] ),
                esc_attr( $attrs['class'] ),
                ( empty($attrs['target']) ? '' : sprintf( 'target="%s"', esc_attr( $attrs['target'] ) ) ),
                ( empty($attrs['rel']) ? '' : sprintf( 'rel="%s"', esc_attr( $attrs['rel'] ) ) ),
                get_the_title()
            );
        }
        
        $html .= sprintf( '</%s>', $tag );
        return $html;
    }
    
    public static function get_the_thumbnail( $post_id, $args = array() )
    {
        $args = shortcode_atts( [
            'card_action'    => 'single-page',
            'thumbnail_size' => 'large',
            'force_show'     => false,
            'tag'            => 'div',
            'class'          => '',
        ], $args );
        if ( !$args['force_show'] && self::shortcode_loader()->get_setting( 'show_thumbnail' ) == 'false' ) {
            return '';
        }
        $tag = $args['tag'];
        $thumb_classes = [ 'team-member--thumbnail-wrapper wps-team--member-element' ];
        if ( !empty($args['class']) ) {
            $thumb_classes[] = $args['class'];
        }
        $action = $args['card_action'];
        if ( !Utils::has_archive() && $action === 'single-page' ) {
            $action = 'none';
        }
        $html = sprintf( '<%s class="%s">', $tag, implode( ' ', $thumb_classes ) );
        $html .= '<div class="team-member--thumbnail">';
        
        if ( $action === 'none' ) {
            $html .= get_the_post_thumbnail( null, $args['thumbnail_size'] );
        } else {
            $attrs = self::get_post_link_attrs( $post_id, self::shortcode_loader()->id, $action );
            $html .= sprintf(
                '<a href="%s" class="%s" %s %s>',
                esc_attr( $attrs['href'] ),
                esc_attr( $attrs['class'] ),
                ( empty($attrs['target']) ? '' : sprintf( 'target="%s"', esc_attr( $attrs['target'] ) ) ),
                ( empty($attrs['rel']) ? '' : sprintf( 'rel="%s"', esc_attr( $attrs['rel'] ) ) )
            ) . get_the_post_thumbnail( null, $args['thumbnail_size'] ) . '</a>';
        }
        
        $html .= sprintf( '</div></%s>', $tag );
        return $html;
    }
    
    public static function shortcode_loader()
    {
        return $GLOBALS['shortcode_loader'];
    }
    
    public static function get_the_designation( $post_id, $args = array() )
    {
        if ( self::shortcode_loader()->get_setting( 'show_designation' ) == 'false' ) {
            return '';
        }
        $args = shortcode_atts( [
            'card_action' => 'single-page',
            'tag'         => 'h4',
            'class'       => '',
        ], $args );
        $desig_classes = [ 'wps-team--member-desig wps-team--member-element' ];
        if ( !empty($args['class']) ) {
            $desig_classes[] = $args['class'];
        }
        $designation = Utils::get_item_data( '_designation', $post_id );
        if ( empty($designation) ) {
            return '';
        }
        return sprintf(
            '<%1$s class="%2$s">%3$s</%1$s>',
            $args['tag'],
            implode( ' ', $desig_classes ),
            esc_html( $designation )
        );
    }
    
    public static function elements_display_order()
    {
        $elements = [
            'thumbnail'                           => __( 'Thumbnail', 'wpspeedo-team' ),
            'divider'                             => __( 'Divider', 'wpspeedo-team' ),
            'designation'                         => __( 'Designation', 'wpspeedo-team' ),
            'description'                         => __( 'Description', 'wpspeedo-team' ),
            'social'                              => __( 'Social', 'wpspeedo-team' ),
            'ribbon'                              => __( 'Ribbon/Tag', 'wpspeedo-team' ),
            'email'                               => __( 'Email', 'wpspeedo-team' ),
            'mobile'                              => __( 'Mobile', 'wpspeedo-team' ),
            'telephone'                           => __( 'Telephone', 'wpspeedo-team' ),
            'experience'                          => __( 'Experience', 'wpspeedo-team' ),
            'website'                             => __( 'Website', 'wpspeedo-team' ),
            'company'                             => __( 'Company', 'wpspeedo-team' ),
            'skills'                              => __( 'Skills', 'wpspeedo-team' ),
            'link_1'                              => Utils::get_setting( 'link_1_label', 'Resume Link' ),
            'link_2'                              => Utils::get_setting( 'link_2_label', 'Hire Link' ),
            self::group_taxonomy_name( true )     => __( 'Group', 'wpspeedo-team' ),
            self::location_taxonomy_name( true )  => __( 'Location', 'wpspeedo-team' ),
            self::language_taxonomy_name( true )  => __( 'Language', 'wpspeedo-team' ),
            self::specialty_taxonomy_name( true ) => __( 'Specialty', 'wpspeedo-team' ),
            self::gender_taxonomy_name( true )    => __( 'Gender', 'wpspeedo-team' ),
        ];
        return $elements;
    }
    
    public static function allowed_elements_display_order()
    {
        return [
            'thumbnail',
            'divider',
            'designation',
            'description',
            'social',
            'ribbon'
        ];
    }
    
    public static function get_sorted_elements()
    {
        $elements = array_keys( Utils::elements_display_order() );
        $_elements = [];
        foreach ( $elements as $element ) {
            $_elements[$element] = self::shortcode_loader()->get_setting( 'order_' . $element );
        }
        asort( $_elements );
        $element_keys = array_keys( $_elements );
        $element_keys = array_map( function ( $element_key ) {
            if ( in_array( $element_key, self::get_taxonomies( true ) ) ) {
                return $element_key;
            }
            return '_' . $element_key;
        }, $element_keys );
        return $element_keys;
    }
    
    public static function get_the_divider( $args = array() )
    {
        if ( self::shortcode_loader()->get_setting( 'show_divider' ) == 'false' ) {
            return '';
        }
        $args = shortcode_atts( [
            'class' => '',
        ], $args );
        $divider_classes = [ 'wps-team--divider-wrapper wps-team--member-element' ];
        if ( !empty($args['class']) ) {
            $divider_classes[] = $args['class'];
        }
        $html = sprintf( '<div class="%s">', implode( ' ', $divider_classes ) );
        $html .= '<div class="wps-team--divider"></div>';
        $html .= '</div>';
        return $html;
    }
    
    public static function get_description_length()
    {
        $description_length = self::shortcode_loader()->get_setting( 'description_length' );
        if ( $description_length == 0 ) {
            $description_length = PHP_INT_MAX - 500;
        }
        return $description_length;
    }
    
    public static function get_the_excerpt( $post_id, $args = array() )
    {
        $args = shortcode_atts( [
            'tag'                => 'div',
            'fix_broken_words'   => false,
            'description_length' => 110,
        ], $args );
        if ( self::shortcode_loader()->get_setting( 'show_description' ) == 'false' ) {
            return '';
        }
        $tag = $args['tag'];
        $excerpt = Utils::max_letters( get_the_excerpt( $post_id ), $args['description_length'], $args['fix_broken_words'] );
        return sprintf( '<%1$s class="wps-team--member-details wps-team--member-details-excerpt wps-team--member-element">%2$s</%1$s>', $tag, sanitize_text_field( $excerpt ) );
    }
    
    public static function get_the_content( $post_id )
    {
        return '<div class="wps-team--member-details wps-team--member-element">' . wpautop( get_the_content( null, false, $post_id ) ) . '</div>';
    }
    
    public static function get_the_social_links( $post_id, $args = array() )
    {
        if ( self::shortcode_loader()->get_setting( 'show_social' ) == 'false' ) {
            return '';
        }
        $args = shortcode_atts( [
            'show_title' => false,
            'title_tag'  => 'h4',
            'tag'        => 'div',
            'title_text' => __( 'Connect with me:', 'wpspeedo-team' ),
        ], $args );
        $social_links = array_filter( (array) Utils::get_item_data( '_social_links', $post_id ) );
        if ( empty($social_links) ) {
            return;
        }
        include Utils::load_template( 'partials/template-social-links.php' );
    }
    
    public static function get_the_action_links( $post_id, $args = array() )
    {
        $args = shortcode_atts( [
            'link_1' => false,
            'link_2' => false,
        ], $args );
        $show_link_1 = self::shortcode_loader()->get_setting( 'show_link_1' );
        $show_link_2 = self::shortcode_loader()->get_setting( 'show_link_2' );
        $show_link_1 = ( $show_link_1 == '' ? $args['link_1'] : wp_validate_boolean( $show_link_1 ) );
        $show_link_2 = ( $show_link_2 == '' ? $args['link_2'] : wp_validate_boolean( $show_link_2 ) );
        if ( !$show_link_1 && !$show_link_2 ) {
            return '';
        }
        $link_1 = self::get_item_data( '_link_1' );
        $link_2 = self::get_item_data( '_link_2' );
        if ( empty($link_1) && empty($link_2) ) {
            return '';
        }
        $html = sprintf( '<div class="wps-team--action-links wps-team--member-element">' );
        if ( $show_link_1 && !empty($link_1) ) {
            $html .= sprintf(
                '<a href="%s" class="wps-team--btn wps-team--link-1"%s>%s</a>',
                esc_url_raw( $link_1 ),
                ( self::is_external_url( $link_1 ) ? self::get_ext_url_params() : '' ),
                esc_html( Utils::get_setting( 'link_1_btn_text', 'My Resume' ) )
            );
        }
        if ( $show_link_2 && !empty($link_2) ) {
            $html .= sprintf(
                '<a href="%s" class="wps-team--btn wps-team--link-2"%s>%s</a>',
                esc_url_raw( $link_2 ),
                ( self::is_external_url( $link_2 ) ? self::get_ext_url_params() : '' ),
                esc_html( Utils::get_setting( 'link_2_btn_text', 'Hire Me' ) )
            );
        }
        $html .= '</div>';
        return $html;
    }
    
    public static function get_the_skills( $post_id, $args = array() )
    {
        if ( self::shortcode_loader()->get_setting( 'show_skills' ) == 'false' ) {
            return '';
        }
        $args = shortcode_atts( [], $args );
        $skills = array_filter( (array) Utils::get_item_data( '_skills', $post_id ) );
        if ( empty($skills) ) {
            return;
        }
        include Utils::load_template( 'partials/template-skills.php' );
    }
    
    public static function get_the_field_label( $field_key, $label_type = '' )
    {
        $field_label = '';
        
        if ( $label_type === 'icon' ) {
            switch ( $field_key ) {
                case '_mobile':
                    $field_label = '<i class="fas fa-mobile-alt"></i>';
                    break;
                case '_telephone':
                    $field_label = '<i class="fas fa-phone"></i>';
                    break;
                case '_email':
                    $field_label = '<i class="fas fa-envelope"></i>';
                    break;
                case '_website':
                    $field_label = '<i class="fas fa-globe"></i>';
                    break;
                case '_experience':
                    $field_label = '<i class="fas fa-briefcase"></i>';
                    break;
                case '_company':
                    $field_label = '<i class="fas fa-building"></i>';
                    break;
                case Utils::group_taxonomy_name( true ):
                    $field_label = '<i class="fas fa-tags"></i>';
                    break;
            }
            if ( !empty($field_label) ) {
                $field_label = '<span class="wps--info-label info-label--icon">' . $field_label . '</span>';
            }
        } else {
            switch ( $field_key ) {
                case '_mobile':
                    $field_label = Utils::get_setting( 'mobile_meta_label', 'Mobile:' );
                    break;
                case '_telephone':
                    $field_label = Utils::get_setting( 'phone_meta_label', 'Phone:' );
                    break;
                case '_email':
                    $field_label = Utils::get_setting( 'email_meta_label', 'Email:' );
                    break;
                case '_website':
                    $field_label = Utils::get_setting( 'website_meta_label', 'Website:' );
                    break;
                case '_experience':
                    $field_label = Utils::get_setting( 'experience_meta_label', 'Experience:' );
                    break;
                case '_company':
                    $field_label = Utils::get_setting( 'company_meta_label', 'Company:' );
                    break;
                case Utils::group_taxonomy_name( true ):
                    $field_label = Utils::get_setting( 'group_meta_label', 'Group:' );
                    break;
            }
            if ( !empty($field_label) ) {
                $field_label = '<strong class="wps--info-label info-label--text">' . $field_label . '</strong>';
            }
        }
        
        return $field_label;
    }
    
    public static function get_the_extra_info( $post_id, $args = array() )
    {
        $args = shortcode_atts( [
            'fields'             => [],
            'info_style'         => '',
            'info_style_default' => 'center-aligned',
            'label_type'         => '',
            'label_type_default' => 'icon',
            'items_border'       => false,
            'info_top_border'    => false,
        ], $args );
        $info_classes = [ 'team-member--info-wrapper' ];
        $info_style = ( empty($args['info_style']) ? $args['info_style_default'] : $args['info_style'] );
        $label_type = ( empty($args['label_type']) ? $args['label_type_default'] : $args['label_type'] );
        // $info_style = 'start-aligned';
        // $info_style = 'start-aligned-alt';
        // $info_style = 'center-aligned';
        // $info_style = 'center-aligned-alt';
        // $info_style = 'center-aligned-combined';
        // $info_style = 'justify-aligned';
        if ( in_array( $info_style, [ 'start-aligned-alt', 'center-aligned-alt', 'center-aligned-combined' ] ) ) {
            $info_classes[] = 'wps-team--info-tabled';
        }
        if ( $args['items_border'] ) {
            $info_classes[] = 'wps-team--info-bordered';
        }
        $supported_fields = array_merge( [
            '_telephone',
            '_email',
            '_website',
            '_experience',
            '_company',
            '_mobile'
        ], Utils::get_taxonomies( true ) );
        $fields = (array) $args['fields'];
        $sorted_fields = self::get_sorted_elements();
        $display_fields = [];
        foreach ( $sorted_fields as $s_field ) {
            $s_field_alt = ltrim( $s_field, '_' );
            $s_field_status = self::shortcode_loader()->get_setting( 'show_' . $s_field_alt );
            if ( $s_field_status == 'true' || $s_field_status != 'false' && in_array( $s_field, $fields ) ) {
                $display_fields[] = $s_field;
            }
        }
        $fields = array_intersect( $display_fields, $supported_fields );
        if ( empty($fields) ) {
            return;
        }
        $fields_html = '';
        foreach ( $fields as $field ) {
            $val = Utils::get_item_data( $field, $post_id );
            if ( empty($val) ) {
                continue;
            }
            $field_label = Utils::get_the_field_label( $field, $label_type );
            
            if ( $field === '_mobile' ) {
                $fields_html .= '<li>' . $field_label . sprintf( '<a class="wps--info-text" href="tel:%s">%s</a>', Utils::sanitize_phone_number( $val ), sanitize_text_field( $val ) ) . '</li>';
                continue;
            }
            
            
            if ( $field === '_telephone' ) {
                $fields_html .= '<li>' . $field_label . sprintf( '<a class="wps--info-text" href="tel:%s">%s</a>', Utils::sanitize_phone_number( $val ), sanitize_text_field( $val ) ) . '</li>';
                continue;
            }
            
            
            if ( $field === '_email' ) {
                $fields_html .= '<li>' . $field_label . sprintf( '<a class="wps--info-text" href="mailto:%1$s">%1$s</a>', sanitize_text_field( $val ) ) . '</li>';
                continue;
            }
            
            
            if ( $field === '_website' ) {
                $link_params = ( self::is_external_url( $val ) ? self::get_ext_url_params() : '' );
                $fields_html .= '<li>' . $field_label . sprintf( '<a class="wps--info-text" href="%1$s" %2$s>%1$s</a>', esc_url_raw( $val ), $link_params ) . '</li>';
                continue;
            }
            
            
            if ( $field === '_experience' ) {
                $fields_html .= '<li>' . $field_label . sprintf( '<span class="wps--info-text">%s</a>', sanitize_text_field( $val ) ) . '</li>';
                continue;
            }
            
            
            if ( $field === '_company' ) {
                $fields_html .= '<li>' . $field_label . sprintf( '<span class="wps--info-text">%s</a>', sanitize_text_field( $val ) ) . '</li>';
                continue;
            }
            
            
            if ( $field === Utils::group_taxonomy_name( true ) ) {
                $val = wp_list_pluck( $val, 'name' );
                $fields_html .= '<li>' . $field_label . sprintf( '<span class="wps--info-text">%s</span>', implode( ', ', $val ) ) . '</li>';
                continue;
            }
        
        }
        if ( empty($fields_html) ) {
            return '';
        }
        $info_classes[] = 'info--' . $info_style;
        if ( $args['info_top_border'] ) {
            $info_classes[] = 'wps-team--info-top-border';
        }
        return sprintf( '<div class="%s"><ul class="wps--member-info">', esc_attr( implode( ' ', $info_classes ) ) ) . $fields_html . '</ul></div>';
    }
    
    public static function trans( $key = '' )
    {
        return plugin()->translations->get( $key );
    }
    
    public static function do_not_cache()
    {
        if ( !defined( 'DONOTCACHEPAGE' ) ) {
            define( 'DONOTCACHEPAGE', true );
        }
        if ( !defined( 'DONOTCACHEDB' ) ) {
            define( 'DONOTCACHEDB', true );
        }
        if ( !defined( 'DONOTMINIFY' ) ) {
            define( 'DONOTMINIFY', true );
        }
        if ( !defined( 'DONOTCDN' ) ) {
            define( 'DONOTCDN', true );
        }
        if ( !defined( 'DONOTCACHCEOBJECT' ) ) {
            define( 'DONOTCACHCEOBJECT', true );
        }
        // Set the headers to prevent caching for the different browsers.
        nocache_headers();
    }

}