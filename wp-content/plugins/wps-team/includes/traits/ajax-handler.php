<?php

namespace WPSpeedo_Team;

if ( ! defined( 'ABSPATH' ) ) exit;

trait AJAX_Handler {

    public function set_ajax_scope_hooks() {

        add_action( 'wp_ajax_' . $this->ajax_key . $this->ajax_scope, array($this, 'handle_request_route') );
        
    }

    public function handle_request_route() {

        check_ajax_referer( '_' . $this->ajax_key . '_nonce' );

        if ( !empty( $route = sanitize_key($_REQUEST['route']) ) && method_exists($this, $route) ) {
            $this->$route();
        }

        wp_send_json_error( __('Something is wrong, request not found', 'wpspeedo-team'), 404 );
    }

}