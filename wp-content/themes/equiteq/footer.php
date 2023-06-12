</div> <!-- closes <div class=container"> -->

<!-- Footer -->
<footer class="bg-black text-white text-md-left text-left">
    <div class="container">

        <div class="row position-relative">
            <div class="col-md-8">
                <div class="pb-3">
                    <img src="<?php echo get_template_directory_uri() ?>/images/svg/Equitec_Logo_ReverseColour_RGB-2.svg" alt="logo">
                </div>
                <?php if (is_active_sidebar('custom-footer-text-widget')): ?>
                    <div id="header-widget-area" class="chw-widget-area widget-area pt-3" role="complementary">
                        <?php dynamic_sidebar('custom-footer-text-widget');?>
                    </div>
                <?php endif;?>
            </div>
            <!-- <div class="col-md-4 pt-4 mb-fixed">
                <a id="cta-consult-expert" class="btn-cta pull-right text-uppercase bold" href="javascript:void(0);" data-toggle="modal" data-target="#expertModal" data-backdrop="static" data-keyboard="false">Consult with our experts</a>
            </div> -->
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="footer-menu row pt-3 pb-3">
                    <?php hm_get_template_part('template-parts/navigation/navigation-footer');?>
                </div>
                <div class="socials">
                    <ul class="pl-0">

                        <li><a class="youtube" href="https://www.youtube.com/user/equiteq" target="_blank" alt="Youtube"></a></li>
                        <li><a class="twitter" href="https://twitter.com/consultingmanda" target="_blank" alt="Twitter"></a></li>
                        <li><a class="slideshare" href="https://www.slideshare.net/EquiteqEdge" target="_blank" alt="Slideshare"></a></li>
                        <li><a class="linkedin" href="https://www.linkedin.com/company/equiteq-llp" target="_blank" alt="LinkedIn"></a></li>
                    </ul>
                </div>
                <div class="copyright pt3">
                    <?php hm_get_template_part('template-parts/navigation/navigation-below-footer');?><span class="d-none d-md-inline">&nbsp;&nbsp;&nbsp;</span>Copyright &copy; Equiteq <?php echo date('Y'); ?>
                </div>
            </div>
        </div>

    </div>
</footer>

<?php
// $page2 = get_post($id);
// hm_get_template_part('template-parts/cta/modal-cta-expert', ['page' => $page2]);
?>
<!-- <script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script> -->

<?php wp_footer()?>

<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"
    integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous">
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.9/dist/js/bootstrap-select.min.js"></script>
<script src="https://unpkg.com/isotope-layout@3/dist/isotope.pkgd.min.js"></script>
<script src="https://unpkg.com/imagesloaded@4/imagesloaded.pkgd.min.js"></script>
<script defer src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>

</body>

</html>