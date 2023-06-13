<?php

namespace WPSpeedo_Team;

if ( ! defined( 'ABSPATH' ) ) exit;

class Notice_Manager {

    public function __construct() {
        add_action( 'admin_enqueue_scripts', array( $this, 'load_script' ) );
        add_action( 'wp_ajax_wpspeedo_dismiss_admin_notice', array( $this, 'dismiss_admin_notice' ) );
    }

    public function load_script() {
        if ( is_customize_preview() ) return;
        wp_enqueue_script( 'wpspeedo-dismissible-notices', plugins_url( 'notice-manager.js', __FILE__ ), ['jquery', 'common'], false, true );
        wp_localize_script( 'wpspeedo-dismissible-notices', 'wpspeedo_dismissible_notice', [ 'nonce' => wp_create_nonce('wpspeedo-dismissible-notice') ] );
    }

    public function dismiss_admin_notice() {
        $option_name        = isset( $_POST['option_name'] ) ? sanitize_text_field( wp_unslash( $_POST['option_name'] ) ) : '';
        $dismissible_length = isset( $_POST['dismissible_length'] ) ? sanitize_text_field( wp_unslash( $_POST['dismissible_length'] ) ) : 0;

        check_ajax_referer( 'wpspeedo-dismissible-notice', 'nonce' );
        self::set_admin_notice_cache( $option_name, $dismissible_length );
        wp_die();
    }

    public function force_dismiss_admin_notice( $arg ) {
        $array       = explode( '-', $arg );
        $length      = array_pop( $array );
        $option_name = implode( '-', $array );
        self::set_admin_notice_cache( $option_name, $length );
    }

    public static function is_admin_notice_active( $arg ) {
        $array       = explode( '-', $arg );
        $length      = array_pop( $array );
        $option_name = implode( '-', $array );
        $db_record   = self::get_admin_notice_cache( $option_name );

        if ( 'forever' === $db_record ) {
            return false;
        } elseif ( absint( $db_record ) >= time() ) {
            return false;
        } else {
            return true;
        }
    }

    public static function get_admin_notice_cache( $id = false ) {
        
        if ( ! $id ) return false;

        $cache_key = 'wpspeedo-' . md5( $id );
        $timeout   = get_site_option( $cache_key );
        $timeout   = 'forever' === $timeout ? time() + 60 : $timeout;

        if ( empty( $timeout ) || time() > $timeout ) {
            return false;
        }

        return $timeout;
    }

    public static function set_admin_notice_cache( $id, $timeout ) {
        $cache_key = 'wpspeedo-' . md5( $id );

        if ( 'forever' !== $timeout ) {
            $timeout = ( 0 === absint( $timeout ) ) ? 1 : $timeout;
            $timeout = strtotime( absint( $timeout ) . ' days' );
        }

        update_site_option( $cache_key, $timeout );

        return true;
    }

}