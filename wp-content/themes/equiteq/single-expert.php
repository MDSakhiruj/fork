<?php

get_header();
$id = get_the_ID();
$expert = get_expert($id);
// $industry_expertises = maybe_unserialize($expert->industry_expertise);
?>


<section>
    <div class="container no-pad-gutters">
        <div class="back mb-4 mb-md-5">
            <i class="fa fa-caret-left align-bottom" style="font-size: 22px;" aria-hidden="true"></i> <a
                href="#" class="btn-outline-success text-uppercase px-0 ml-2">Back to team</a>
        </div>
        <!--May implement the expert's profile here -->
    </div>
</section>


<!--May implement the expert's industry expertise here -->

<?php
get_footer();