<?php

namespace WPSpeedo_Team;

if ( ! defined('ABSPATH') ) exit;

class Shortcode {

	public function __construct() {
		add_shortcode( 'wpspeedo-team', [ $this, 'shortcode'] );
	}

    public function load_settings( $sc_id ) {
        $settings = plugin()->api->fetch_shortcode( $sc_id );
		if ( empty($settings) ) return [];
		return $settings['settings'];
    }
	
	public function shortcode( $args ) {

		$settings = (array) $this->load_settings( $args['id'] );

		if ( empty($settings) ) return sprintf( '<h3>Team Shortcode <strong>%s</strong> not found</h3>', $args['id'] );
		
		ob_start();

		global $shortcode_loader;

        $shortcode_loader = new Shortcode_Loader([
			'id' => $args['id'],
			'settings' => $settings,
			'mode' => 'public'
		]);

		$shortcode_loader->load_template();

		return ob_get_clean();

	}

}