<!-- The WordPress Primary Menu -->
<?php wp_nav_menu(
    [
        'theme_location' => 'below_footer',
        'menu_class' => 'list-unstyled',
        'container_class' => 'nav-pipe',
        'container_id' => 'navBelowFooter',
    ]
);?>

<script>
var $=jQuery.noConflict();
$('.copyright li').after('|');
</script>
