<!-- The WordPress Primary Menu -->
<?php
wp_nav_menu(
    [
        'theme_location'    => 'footer',
        'menu_class'        => 'list-unstyled',
        'container_class'  => 'col-12 col-md-3',
        'container_id'    => 'navbarNav',
    ]
);

wp_nav_menu(
    [
        'theme_location'    => 'footer_2',
        'menu_class'        => 'list-unstyled',
        'container_class'  => 'col-12 col-md-5',
        'container_id'    => 'navbarNav2',
    ]
);

wp_nav_menu(
    [
        'theme_location'    => 'footer_3',
        'menu_class'        => 'list-unstyled',
        'container_class'  => 'col-12 col-md-4',
        'container_id'    => 'navbarNav3',
    ]
);
?>