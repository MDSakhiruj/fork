<?php

namespace WPSpeedo_Team;

if ( ! defined('ABSPATH') ) exit;

$tag = $args['tag'];

printf( '<%s class="wps-team--member-s-links wps-team--member-element">', $tag );
    
    if ( $args['show_title'] ) printf( '<%1$s class="team-member--slinks-title">%2$s</%1$s>', $args['title_tag'], $args['title_text'] ); ?>

    <ul <?php self::shortcode_loader()->print_attribute_string( 'social' ); ?>>
        <?php foreach( $social_links as $slink ) :
            printf( '<li class="wps-si--%s">
                <a href="%s" aria-label="%s"%s>%s</a>
            </li>', Utils::get_brnad_name( $slink['social_icon']['icon'] ), esc_url_raw( $slink['social_link'] ), __('Social Link', 'wpspeedo-team' ), self::get_ext_url_params(), Icon_Manager::render_font_icon( $slink['social_icon'] ) );
        endforeach; ?>
    </ul>

<?php

printf( '</%s>', $tag );