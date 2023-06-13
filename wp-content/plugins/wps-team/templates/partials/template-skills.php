<?php

namespace WPSpeedo_Team;

if ( ! defined('ABSPATH') ) exit;

?>

<div class="wps-team--member-skills wps-team--member-element">
    <ul class="wps--skills">
        <?php foreach( $skills as $skill ) :
            printf( '<li>
                <span class="skill-name">%1$s</span>
                <span class="skill-value">%2$d%3$s</span>
                <span class="skill-bar" data-width="%2$d" style="width: %2$d%3$s"></span>
            </li>', sanitize_text_field( $skill['skill_name'] ), sanitize_text_field( $skill['skill_val'] ), '%' );
        endforeach; ?>
    </ul>
</div>