<?php

namespace WPSpeedo_Team;

if ( ! defined('ABSPATH') ) exit;

global $wp_query;

$paged = get_query_var('paged') ? (int) get_query_var('paged') : 1;

$total_pages = apply_filters( 'wpspeedo_team/pagination/total', $wp_query->max_num_pages );

if ( $total_pages < 2 ) return; ?>

<div class="wps-pagination--wrap">
    
    <?php

    $pages = paginate_links([
        'base'      => str_replace( 999999999, '%#%', esc_url( get_pagenum_link( 999999999 ) ) ),
        'format'    => '?paged=%#%',
        'current'   => max( 1, $paged ),
        'total'     => $total_pages,
        'type'      => 'array',
        'show_all'  => true,
        'prev_text' => '<i class="fas fa-angle-double-left"></i><span class="sr-only">Previous</span>',
        'next_text' => '<i class="fas fa-angle-double-right"></i><span class="sr-only">Next</span>',
    ]);

    if ( is_array( $pages ) ) {
        echo '<nav class="wps-team--navigation"><ul class="wps-team--pagination">';
        foreach ( $pages as $page ) {
            $page = str_replace('page-numbers', 'wps--page-numbers', $page);
            $page = str_replace('current', 'wps--current', $page);
            echo "<li>$page</li>";
        }
        echo '</ul></nav>';
    }

    ?>

</div>