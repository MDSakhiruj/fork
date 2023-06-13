<?php

namespace WPSpeedo_Team;

if ( !defined( 'ABSPATH' ) ) {
    exit;
}
$this->add_attribute( 'wrapper', 'class', [ 'wps--thumb-round', 'wps-team--social-hover-up', 'wps-team--thumb-zoom-in' ] );
?>

<div <?php 
$this->print_attribute_string( 'wrapper' );
?>>
    
    <?php 
do_action( 'wpspeedo_team/before_wrapper_inner', $this );
?>

    <div <?php 
$this->print_attribute_string( 'wrapper_inner' );
?>>
        
        <?php 

if ( $this->get_posts()->have_posts() ) {
    ?>

            <div <?php 
    $this->print_attribute_string( 'single_item_row' );
    ?>>

                <?php 
    while ( $this->get_posts()->have_posts() ) {
        $this->get_posts()->the_post();
        ?>

                    <?php 
        $this->add_attribute(
            'single_item_col_' . get_the_ID(),
            'class',
            'wps-widget--item-' . get_the_ID(),
            true
        );
        do_action( 'wpspeedo_team/before_single_team', $this );
        ?>
            
                    <div <?php 
        $this->print_attribute_string( [ 'single_item_col', 'single_item_col_' . get_the_ID() ] );
        ?>>
                        <div class="wpspeedo-team--single">
                            <div class="wps-team--single-inner">
                                
                                <?php 
        echo  Utils::get_the_thumbnail( get_the_ID(), [
            'card_action'    => $card_action,
            'thumbnail_size' => $thumbnail_size,
        ] ) ;
        echo  Utils::get_the_designation( get_the_ID() ) ;
        echo  Utils::get_the_title( get_the_ID(), [
            'card_action' => $card_action,
        ] ) ;
        echo  Utils::get_the_divider() ;
        echo  Utils::get_the_excerpt( get_the_ID(), [
            'description_length' => $description_length,
            'fix_broken_words'   => $fix_broken_words,
        ] ) ;
        Utils::get_the_social_links( get_the_ID(), [] );
        ?>
                                
                            </div>
                        </div>
                    </div>

                    <?php 
        do_action( 'wpspeedo_team/after_single_team', $this );
        ?>

                <?php 
    }
    ?>
        
            </div>

        <?php 
}

?>

    </div>

    <?php 
do_action( 'wpspeedo_team/after_wrapper_inner', $this );
?>
    
</div><?php 