<?php

namespace WPSpeedo_Team;

if ( ! defined('ABSPATH') ) exit;

get_header();

while ( have_posts() ) : the_post();

$experience         = Utils::get_item_data( '_experience' );
$company            = Utils::get_item_data( '_company' );
$color              = Utils::get_item_data( '_color' );

$s_button_link      = Utils::get_item_data( '_s_button_link' );
$ribbon             = Utils::get_item_data( '_ribbon' );

// $location           = Utils::get_item_data( '_location' );
// $language           = Utils::get_item_data( '_language' );
// $specialty         = Utils::get_item_data( '_specialty' );
// $gender             = Utils::get_item_data( '_gender' );

$thumbnail_size     = Utils::get_setting( 'detail_thumbnail_size' );

$shortcode_loader = new Single_Loader();

$shortcode_loader->add_attribute( 'wps-widget-single-page--wrapper', 'class', [
    'wps-container wps-widget--team wps-widget-container-single wps-team--social-hover-up',
    // 'wps-si--b-bg-color wps-si--b-bg-color--hover'
]);

// $shortcode_loader->add_attribute( 'social', 'class', [
//     'wps--social-links wps-si--shape-circle wps-si--b-bg-color wps-si--b-bg-color--hover'
// ]);

if ( !empty($color) ) {
    $shortcode_loader->add_attribute( 'wps-widget-single-page--wrapper', 'style', '--wps-divider-bg-color:' . sanitize_text_field($color) );
}

?>

<div <?php $shortcode_loader->print_attribute_string('wps-widget-single-page--wrapper'); ?>>

    <div class="wps-row">

        <div class="wps-col wps-col--left-info">

            <?php
            
            echo Utils::get_the_thumbnail( get_the_ID(), [ 'card_action' => 'none', 'thumbnail_size' => $thumbnail_size ] );

            echo Utils::get_the_title( get_the_ID(), [ 'card_action' => 'none', 'tag' => 'h1', 'class' => 'wps-show--tablet-small' ] );
            echo Utils::get_the_designation( get_the_ID(), [ 'class' => 'wps-show--tablet-small' ] );
            echo Utils::get_the_divider([ 'class' => 'wps-show--tablet-small' ]);
            
            echo Utils::get_the_extra_info( get_the_ID(), [
                'fields' => [ '_mobile', '_telephone', '_email', '_website' ],
                'info_style' => 'start-aligned',
            ]);
            
            echo Utils::get_the_extra_info( get_the_ID(), [
                'fields' => array_merge( [ '_experience', '_company' ], Utils::get_taxonomies( true ) ),
                'label_type' => 'text',
                'info_style' => 'start-aligned-alt',
                'info_top_border' => true
            ]);

            ?>

        </div>

        <div class="wps-col wps-col--right-info">
            <div class="wps-team--single-inner">

                <?php
                
                echo Utils::get_the_title( get_the_ID(), [ 'card_action' => 'none', 'tag' => 'h1', 'class' => 'wps-hide--tablet-small' ] );
                echo Utils::get_the_designation( get_the_ID(), [ 'class' => 'wps-hide--tablet-small' ] );
                echo Utils::get_the_divider([ 'class' => 'wps-hide--tablet-small' ]);

                echo do_shortcode( Utils::get_the_content( get_the_ID() ) );
                Utils::get_the_social_links( get_the_ID(), [ 'show_title' => true ] );
                echo Utils::get_the_skills( get_the_ID() );

                ?>

            </div>
        </div>

    </div>

</div>

<?php endwhile;

get_footer();