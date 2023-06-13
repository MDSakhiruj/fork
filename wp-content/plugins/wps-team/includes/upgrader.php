<?php

namespace WPSpeedo_Team;

if ( ! defined('ABSPATH') ) exit;

class Upgrader {

    public static $instance = null;
    public $old_version;
    public $new_version;

    public function __construct( $old_version, $new_version ) {
        $this->old_version = $old_version;
        $this->new_version = $new_version;
        $this->run();
    }
    
	public static function instance( $old_version, $new_version ) {
        if ( is_null( self::$instance ) ) {
            self::$instance = new self( $old_version, $new_version );
		}
        return self::$instance;
    }

    public function upgrade_paths() {
        return [ '2.4.0', '2.5.7', '2.5.8' ];
    }

    public function run() {
        if ( $this->old_version === $this->new_version ) return;
        foreach ( $this->upgrade_paths() as $version ) {
            if ( version_compare( $version, $this->old_version, '>' ) ) {
                $ungrade_fn = '_v_' . str_replace( '.', '_', $version );
                if ( method_exists( $this, $ungrade_fn ) ) {
                    $this->$ungrade_fn();
                }
            }
        }
    }

    public function _v_2_4_0() {

        $themes = [
            'theme-one'   => 'square-01',
            'theme-two'   => 'square-02',
            'theme-three' => 'square-03',
            'theme-four'  => 'square-04',
            'theme-five'  => 'square-05',
            'theme-six'   => 'circle-01',
            'horiz-one'   => 'horiz-01',
            'horiz-two'   => 'horiz-02',
            'horiz-three' => 'horiz-03',
            'horiz-four'  => 'horiz-04',
        ];

        global $wpdb;

        $shortcodes = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}wps_team ORDER BY created_at DESC", ARRAY_A );
        
        foreach ( $shortcodes as &$shortcode ) {

            $shortcode['settings'] = maybe_unserialize( $shortcode['settings'] );

            if ( !empty( $theme = $shortcode['settings']['theme']['value'] ) && array_key_exists( $theme, $themes ) ) {
                $shortcode['settings']['theme']['value'] = $themes[ $theme ];

                $shortcode['settings'] = maybe_serialize( $shortcode['settings'] );
                $shortcode["updated_at"] = current_time('mysql');
                $wpdb->update( "{$wpdb->prefix}wps_team" , $shortcode, array( 'id' => $shortcode['id'] ),  plugin()->api->db_columns_format() );
            }

        }

    }

    public function _v_2_5_7() {

        if ( ! wps_team_fs()->can_use_premium_code() ) return;
        
        global $wpdb;
        
        $shortcodes = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}wps_team ORDER BY created_at DESC", ARRAY_A );
        
        foreach ( $shortcodes as &$shortcode ) {

            $shortcode['settings'] = maybe_unserialize( $shortcode['settings'] );

            if ( $shortcode['settings']['typo_name_font_size']['value'] == 0 ) $shortcode['settings']['typo_name_font_size']['value'] = '';
            if ( $shortcode['settings']['typo_name_font_size_mobile']['value'] == 0 ) $shortcode['settings']['typo_name_font_size_mobile']['value'] = '';
            if ( $shortcode['settings']['typo_name_font_size_small_tablet']['value'] == 0 ) $shortcode['settings']['typo_name_font_size_small_tablet']['value'] = '';
            if ( $shortcode['settings']['typo_name_font_size_tablet']['value'] == 0 ) $shortcode['settings']['typo_name_font_size_tablet']['value'] = '';

            if ( $shortcode['settings']['typo_desig_font_size']['value'] == 0 ) $shortcode['settings']['typo_desig_font_size']['value'] = '';
            if ( $shortcode['settings']['typo_desig_font_size_mobile']['value'] == 0 ) $shortcode['settings']['typo_desig_font_size_mobile']['value'] = '';
            if ( $shortcode['settings']['typo_desig_font_size_small_tablet']['value'] == 0 ) $shortcode['settings']['typo_desig_font_size_small_tablet']['value'] = '';
            if ( $shortcode['settings']['typo_desig_font_size_tablet']['value'] == 0 ) $shortcode['settings']['typo_desig_font_size_tablet']['value'] = '';

            if ( $shortcode['settings']['typo_content_font_size']['value'] == 0 ) $shortcode['settings']['typo_content_font_size']['value'] = '';
            if ( $shortcode['settings']['typo_content_font_size_mobile']['value'] == 0 ) $shortcode['settings']['typo_content_font_size_mobile']['value'] = '';
            if ( $shortcode['settings']['typo_content_font_size_small_tablet']['value'] == 0 ) $shortcode['settings']['typo_content_font_size_small_tablet']['value'] = '';
            if ( $shortcode['settings']['typo_content_font_size_tablet']['value'] == 0 ) $shortcode['settings']['typo_content_font_size_tablet']['value'] = '';

            if ( $shortcode['settings']['typo_meta_font_size']['value'] == 0 ) $shortcode['settings']['typo_meta_font_size']['value'] = '';
            if ( $shortcode['settings']['typo_meta_font_size_mobile']['value'] == 0 ) $shortcode['settings']['typo_meta_font_size_mobile']['value'] = '';
            if ( $shortcode['settings']['typo_meta_font_size_small_tablet']['value'] == 0 ) $shortcode['settings']['typo_meta_font_size_small_tablet']['value'] = '';
            if ( $shortcode['settings']['typo_meta_font_size_tablet']['value'] == 0 ) $shortcode['settings']['typo_meta_font_size_tablet']['value'] = '';

            $shortcode['settings'] = maybe_serialize( $shortcode['settings'] );
            $shortcode["updated_at"] = current_time('mysql');
            $wpdb->update( "{$wpdb->prefix}wps_team" , $shortcode, array( 'id' => $shortcode['id'] ),  plugin()->api->db_columns_format() );

        }

    }

    public function _v_2_5_8() {

        if ( ! wps_team_fs()->can_use_premium_code() ) return;
        
        global $wpdb;
        
        $shortcodes = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}wps_team ORDER BY created_at DESC", ARRAY_A );
        
        foreach ( $shortcodes as &$shortcode ) {

            $shortcode['settings'] = maybe_unserialize( $shortcode['settings'] );

            // Type Name

            if ( $shortcode['settings']['typo_name_font_size']['value'] == 0 ) $shortcode['settings']['typo_name_font_size']['value'] = '';
            if ( $shortcode['settings']['typo_name_font_size_mobile']['value'] == 0 ) $shortcode['settings']['typo_name_font_size_mobile']['value'] = '';
            if ( $shortcode['settings']['typo_name_font_size_small_tablet']['value'] == 0 ) $shortcode['settings']['typo_name_font_size_small_tablet']['value'] = '';
            if ( $shortcode['settings']['typo_name_font_size_tablet']['value'] == 0 ) $shortcode['settings']['typo_name_font_size_tablet']['value'] = '';

            if ( $shortcode['settings']['typo_name_line_height']['value'] == 0 ) $shortcode['settings']['typo_name_line_height']['value'] = '';
            if ( $shortcode['settings']['typo_name_line_height_mobile']['value'] == 0 ) $shortcode['settings']['typo_name_line_height_mobile']['value'] = '';
            if ( $shortcode['settings']['typo_name_line_height_small_tablet']['value'] == 0 ) $shortcode['settings']['typo_name_line_height_small_tablet']['value'] = '';
            if ( $shortcode['settings']['typo_name_line_height_tablet']['value'] == 0 ) $shortcode['settings']['typo_name_line_height_tablet']['value'] = '';

            if ( $shortcode['settings']['typo_name_letter_spacing']['value'] == 0 ) $shortcode['settings']['typo_name_letter_spacing']['value'] = '';
            if ( $shortcode['settings']['typo_name_letter_spacing_mobile']['value'] == 0 ) $shortcode['settings']['typo_name_letter_spacing_mobile']['value'] = '';
            if ( $shortcode['settings']['typo_name_letter_spacing_small_tablet']['value'] == 0 ) $shortcode['settings']['typo_name_letter_spacing_small_tablet']['value'] = '';
            if ( $shortcode['settings']['typo_name_letter_spacing_tablet']['value'] == 0 ) $shortcode['settings']['typo_name_letter_spacing_tablet']['value'] = '';

            // Type Desig

            if ( $shortcode['settings']['typo_desig_font_size']['value'] == 0 ) $shortcode['settings']['typo_desig_font_size']['value'] = '';
            if ( $shortcode['settings']['typo_desig_font_size_mobile']['value'] == 0 ) $shortcode['settings']['typo_desig_font_size_mobile']['value'] = '';
            if ( $shortcode['settings']['typo_desig_font_size_small_tablet']['value'] == 0 ) $shortcode['settings']['typo_desig_font_size_small_tablet']['value'] = '';
            if ( $shortcode['settings']['typo_desig_font_size_tablet']['value'] == 0 ) $shortcode['settings']['typo_desig_font_size_tablet']['value'] = '';

            if ( $shortcode['settings']['typo_desig_line_height']['value'] == 0 ) $shortcode['settings']['typo_desig_line_height']['value'] = '';
            if ( $shortcode['settings']['typo_desig_line_height_mobile']['value'] == 0 ) $shortcode['settings']['typo_desig_line_height_mobile']['value'] = '';
            if ( $shortcode['settings']['typo_desig_line_height_small_tablet']['value'] == 0 ) $shortcode['settings']['typo_desig_line_height_small_tablet']['value'] = '';
            if ( $shortcode['settings']['typo_desig_line_height_tablet']['value'] == 0 ) $shortcode['settings']['typo_desig_line_height_tablet']['value'] = '';

            if ( $shortcode['settings']['typo_desig_letter_spacing']['value'] == 0 ) $shortcode['settings']['typo_desig_letter_spacing']['value'] = '';
            if ( $shortcode['settings']['typo_desig_letter_spacing_mobile']['value'] == 0 ) $shortcode['settings']['typo_desig_letter_spacing_mobile']['value'] = '';
            if ( $shortcode['settings']['typo_desig_letter_spacing_small_tablet']['value'] == 0 ) $shortcode['settings']['typo_desig_letter_spacing_small_tablet']['value'] = '';
            if ( $shortcode['settings']['typo_desig_letter_spacing_tablet']['value'] == 0 ) $shortcode['settings']['typo_desig_letter_spacing_tablet']['value'] = '';

            // Type Content

            if ( $shortcode['settings']['typo_content_font_size']['value'] == 0 ) $shortcode['settings']['typo_content_font_size']['value'] = '';
            if ( $shortcode['settings']['typo_content_font_size_mobile']['value'] == 0 ) $shortcode['settings']['typo_content_font_size_mobile']['value'] = '';
            if ( $shortcode['settings']['typo_content_font_size_small_tablet']['value'] == 0 ) $shortcode['settings']['typo_content_font_size_small_tablet']['value'] = '';
            if ( $shortcode['settings']['typo_content_font_size_tablet']['value'] == 0 ) $shortcode['settings']['typo_content_font_size_tablet']['value'] = '';

            if ( $shortcode['settings']['typo_content_line_height']['value'] == 0 ) $shortcode['settings']['typo_content_line_height']['value'] = '';
            if ( $shortcode['settings']['typo_content_line_height_mobile']['value'] == 0 ) $shortcode['settings']['typo_content_line_height_mobile']['value'] = '';
            if ( $shortcode['settings']['typo_content_line_height_small_tablet']['value'] == 0 ) $shortcode['settings']['typo_content_line_height_small_tablet']['value'] = '';
            if ( $shortcode['settings']['typo_content_line_height_tablet']['value'] == 0 ) $shortcode['settings']['typo_content_line_height_tablet']['value'] = '';

            if ( $shortcode['settings']['typo_content_letter_spacing']['value'] == 0 ) $shortcode['settings']['typo_content_letter_spacing']['value'] = '';
            if ( $shortcode['settings']['typo_content_letter_spacing_mobile']['value'] == 0 ) $shortcode['settings']['typo_content_letter_spacing_mobile']['value'] = '';
            if ( $shortcode['settings']['typo_content_letter_spacing_small_tablet']['value'] == 0 ) $shortcode['settings']['typo_content_letter_spacing_small_tablet']['value'] = '';
            if ( $shortcode['settings']['typo_content_letter_spacing_tablet']['value'] == 0 ) $shortcode['settings']['typo_content_letter_spacing_tablet']['value'] = '';

            // Typo Meta

            if ( $shortcode['settings']['typo_meta_font_size']['value'] == 0 ) $shortcode['settings']['typo_meta_font_size']['value'] = '';
            if ( $shortcode['settings']['typo_meta_font_size_mobile']['value'] == 0 ) $shortcode['settings']['typo_meta_font_size_mobile']['value'] = '';
            if ( $shortcode['settings']['typo_meta_font_size_small_tablet']['value'] == 0 ) $shortcode['settings']['typo_meta_font_size_small_tablet']['value'] = '';
            if ( $shortcode['settings']['typo_meta_font_size_tablet']['value'] == 0 ) $shortcode['settings']['typo_meta_font_size_tablet']['value'] = '';

            if ( $shortcode['settings']['typo_meta_line_height']['value'] == 0 ) $shortcode['settings']['typo_meta_line_height']['value'] = '';
            if ( $shortcode['settings']['typo_meta_line_height_mobile']['value'] == 0 ) $shortcode['settings']['typo_meta_line_height_mobile']['value'] = '';
            if ( $shortcode['settings']['typo_meta_line_height_small_tablet']['value'] == 0 ) $shortcode['settings']['typo_meta_line_height_small_tablet']['value'] = '';
            if ( $shortcode['settings']['typo_meta_line_height_tablet']['value'] == 0 ) $shortcode['settings']['typo_meta_line_height_tablet']['value'] = '';

            if ( $shortcode['settings']['typo_meta_letter_spacing']['value'] == 0 ) $shortcode['settings']['typo_meta_letter_spacing']['value'] = '';
            if ( $shortcode['settings']['typo_meta_letter_spacing_mobile']['value'] == 0 ) $shortcode['settings']['typo_meta_letter_spacing_mobile']['value'] = '';
            if ( $shortcode['settings']['typo_meta_letter_spacing_small_tablet']['value'] == 0 ) $shortcode['settings']['typo_meta_letter_spacing_small_tablet']['value'] = '';
            if ( $shortcode['settings']['typo_meta_letter_spacing_tablet']['value'] == 0 ) $shortcode['settings']['typo_meta_letter_spacing_tablet']['value'] = '';

            $shortcode['settings'] = maybe_serialize( $shortcode['settings'] );
            $shortcode["updated_at"] = current_time('mysql');
            $wpdb->update( "{$wpdb->prefix}wps_team" , $shortcode, array( 'id' => $shortcode['id'] ),  plugin()->api->db_columns_format() );

        }

    }

}