<?php
/* Template Name: Expert Page */
get_header();
$id = get_the_ID();
$page = get_post($id);
?>

<?php

/**Hero */
hm_get_template_part('template-parts/hero', ['page' => $page]);

?>

<section class="bg-dark-blue">
    <div class="container text-white no-pad-gutters">
        <h3 class="text-uppercase mb-4"><?php echo $page->intro_title ?></h3>
        <div class="row">
            <div class="col-md-8 mb-4">
                <?php echo $page->post_content ?>
            </div>
        </div>
        <!--May implement the search and filter here-->
    </div>
</section>

<!--May implement the experts profile list here-->

<?php
get_footer();
?>