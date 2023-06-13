<?php

namespace WPSpeedo_Team;

if ( ! defined('ABSPATH') ) exit;

class Notice {

    public $manager;

    public function __construct() {
        add_action( 'admin_init', array( $this, 'load_notice_manager' ), 0 );
        add_action( 'admin_enqueue_scripts', array( $this, 'load_notice_css' ), 99999999999 );
    }

    public function load_notice_manager() {

        if ( ! class_exists( '\WPSpeedo_Team\Notice_Manager' ) ) {
            require_once WPS_TEAM_INC_PATH . 'managers/notice-manager/notice-manager.php';
        }

        $this->manager = new Notice_Manager();
    }

    public function load_notice_css() {

        ob_start();

        ?>

        <style>
            .wpspeedo--notice {
                padding-left: 0;
            }
            .wpspeedo--notice-inner {
                display: flex;
                width: 100%;
            }
            .wpspeedo--notice-col {
                padding: 40px;
            }
            .wpspeedo--notice .logo-area {
                display: inline-flex;
                justify-content: center;
                align-items: center;
                flex-direction: column;
                margin-top: -1px;
                margin-bottom: -1px;
                background: url('<?php echo WPS_TEAM_URL . 'images/notice-bg.svg'; ?>');
                background-size: cover;
                background-position: center;
            }
            .wpspeedo--notice .logo-area img {
                width: 140px;
            }
            .wpspeedo--notice .logo-area p {
                padding: 4px 8px;
                border: 2px dotted rgba(0, 0, 0, .15);
                margin-top: 20px;
                font-weight: bold;
            }
            .wpspeedo--notice .notice-title {
                font-size: 2.2em;
                margin-bottom: 20px;
                color: #3d424f;
            }
            .wpspeedo--notice .content-area p {
                font-size: 1.2em;
            }
            .wpspeedo--notice .content-area .button {
                font-size: 1em;
            }
            .wpspeedo--notice .wpspeedo--notice-actions {
                margin-top: 24px;
            }
        </style>

        <?php

        $css = str_replace( ['<style>', '</style>'], ['',''], ob_get_clean() );

        wp_add_inline_style( 'common', $css );

    }

}