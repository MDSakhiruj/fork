<?php

namespace WPSpeedo_Team;

if ( !defined( 'ABSPATH' ) ) {
    exit;
}
class Data
{
    public function __construct()
    {
        /*
         * Register Custom Post Types
         */
        add_action( 'init', array( $this, 'register_cpts' ), 0 );
        /*
         * Register Custom Taxonomies
         */
        add_action( 'init', array( $this, 'register_taxonomies' ), 0 );
        /*
         * Register Custom Metaboxes
         */
        add_action( 'add_meta_boxes', array( $this, 'register_metaboxes' ) );
        /*
         * Handle Meta Fields Saving
         */
        add_action( 'save_post_' . Utils::post_type_name(), array( $this, 'save_meta_fields' ) );
        /*
         * Display Columns in Members admin page
         */
        add_action( 'admin_head', [ $this, 'add_columns_style' ] );
        add_filter( 'manage_' . Utils::post_type_name() . '_posts_columns', [ $this, 'post_type_columns' ] );
        add_action(
            'manage_' . Utils::post_type_name() . '_posts_custom_column',
            [ $this, 'post_type_columns_data' ],
            10,
            2
        );
    }
    
    /*
     * Post type columns style
     */
    public function add_columns_style()
    {
        echo  '<style>.post-type-wps-team-members .thumbnail.column-thumbnail img{border-radius:2px}.post-type-wps-team-members th.manage-column.column-thumbnail{width:100px}.wps-post--info{margin-bottom:4px}.wps-post--info:first-child{margin-top:6px}.wps-post--info:last-child{margin-bottom:6px}</style>' ;
    }
    
    /*
     * Add post type columns
     */
    public function post_type_columns( $columns )
    {
        $_columns = [];
        $date = $columns['date'];
        $cb = $columns['cb'];
        $_columns['cb'] = $cb;
        $_columns['thumbnail'] = __( 'Thumbnail', 'wpspeedo-team' );
        $_columns = array_merge( $_columns, $columns );
        $_columns['title'] = __( 'Name', 'wpspeedo-team' );
        unset( $_columns['date'] );
        $_columns['contact_info'] = __( 'Contact Info', 'wpspeedo-team' );
        $_columns['other_info'] = __( 'Other Info', 'wpspeedo-team' );
        $_columns['date'] = $date;
        return $_columns;
    }
    
    /*
     * Handle post type columns data
     */
    public function post_type_columns_data( $column, $post_id )
    {
        if ( $column == 'thumbnail' ) {
            echo  get_the_post_thumbnail( $post_id, array( 64, 64 ) ) ;
        }
        
        if ( $column == 'contact_info' ) {
            $email = get_post_meta( $post_id, '_email', true );
            $mobile = get_post_meta( $post_id, '_mobile', true );
            $telephone = get_post_meta( $post_id, '_telephone', true );
            printf( '<div class="wps-post--info"><strong class="wps-post--info-title">%s</strong>&nbsp;&nbsp;<span class="wps-post--info-data">%s</span></div>', __( 'Email:', 'wpspeedo-team' ), $email );
            printf( '<div class="wps-post--info"><strong class="wps-post--info-title">%s</strong>&nbsp;&nbsp;<span class="wps-post--info-data">%s</span></div>', __( 'Mobile:', 'wpspeedo-team' ), $mobile );
            printf( '<div class="wps-post--info"><strong class="wps-post--info-title">%s</strong>&nbsp;&nbsp;<span class="wps-post--info-data">%s</span></div>', __( 'Telephone:', 'wpspeedo-team' ), $telephone );
        }
        
        
        if ( $column == 'other_info' ) {
            $company = get_post_meta( $post_id, '_company', true );
            $designation = get_post_meta( $post_id, '_designation', true );
            $website = get_post_meta( $post_id, '_website', true );
            printf( '<div class="wps-post--info"><strong class="wps-post--info-title">%s</strong>&nbsp;&nbsp;<span class="wps-post--info-data">%s</span></div>', __( 'Company:', 'wpspeedo-team' ), $company );
            printf( '<div class="wps-post--info"><strong class="wps-post--info-title">%s</strong>&nbsp;&nbsp;<span class="wps-post--info-data">%s</span></div>', __( 'Designation:', 'wpspeedo-team' ), $designation );
            printf( '<div class="wps-post--info"><strong class="wps-post--info-title">%s</strong>&nbsp;&nbsp;<span class="wps-post--info-data">%s</span></div>', __( 'Website:', 'wpspeedo-team' ), $website );
        }
    
    }
    
    /*
     * Register Custom Post Types
     */
    public function register_cpts()
    {
        $single_name = ucfirst( Utils::get_setting( 'member_single_name' ) );
        $plural_name = ucfirst( Utils::get_setting( 'member_plural_name' ) );
        $single_name_lc = lcfirst( $single_name );
        $plural_name_lc = lcfirst( $plural_name );
        $labels = array(
            'name'                  => $plural_name,
            'singular_name'         => $single_name,
            'menu_name'             => 'Team',
            'name_admin_bar'        => $single_name,
            'archives'              => sprintf( __( '%s Archives', 'wpspeedo-team' ), $single_name ),
            'attributes'            => sprintf( __( '%s Attributes', 'wpspeedo-team' ), $single_name ),
            'all_items'             => sprintf( __( 'All %s', 'wpspeedo-team' ), $plural_name ),
            'add_new_item'          => sprintf( __( 'Add %s', 'wpspeedo-team' ), $single_name ),
            'add_new'               => sprintf( __( 'Add %s', 'wpspeedo-team' ), $single_name ),
            'new_item'              => sprintf( __( 'New %s', 'wpspeedo-team' ), $single_name ),
            'edit_item'             => sprintf( __( 'Edit %s', 'wpspeedo-team' ), $single_name ),
            'update_item'           => sprintf( __( 'Update %s', 'wpspeedo-team' ), $single_name ),
            'view_item'             => sprintf( __( 'View %s', 'wpspeedo-team' ), $single_name ),
            'search_items'          => sprintf( __( 'Search %s', 'wpspeedo-team' ), $single_name ),
            'featured_image'        => sprintf( __( '%s Image', 'wpspeedo-team' ), $single_name ),
            'view_items'            => sprintf( __( 'View %s', 'wpspeedo-team' ), $plural_name ),
            'items_list'            => sprintf( __( '%s list', 'wpspeedo-team' ), $plural_name ),
            'items_list_navigation' => sprintf( __( '%s list navigation', 'wpspeedo-team' ), $plural_name ),
            'set_featured_image'    => sprintf( __( 'Set %s image', 'wpspeedo-team' ), $single_name_lc ),
            'remove_featured_image' => sprintf( __( 'Remove %s image', 'wpspeedo-team' ), $single_name_lc ),
            'use_featured_image'    => sprintf( __( 'Use as %s image', 'wpspeedo-team' ), $single_name_lc ),
            'insert_into_item'      => sprintf( __( 'Insert into %s', 'wpspeedo-team' ), $single_name_lc ),
            'uploaded_to_this_item' => sprintf( __( 'Uploaded to this %s', 'wpspeedo-team' ), $single_name_lc ),
            'filter_items_list'     => sprintf( __( 'Filter %s list', 'wpspeedo-team' ), $plural_name_lc ),
            'not_found'             => __( 'Not found', 'wpspeedo-team' ),
            'not_found_in_trash'    => __( 'Not found in Trash', 'wpspeedo-team' ),
        );
        $args = array(
            'label'             => $single_name,
            'labels'            => $labels,
            'supports'          => array( 'title', 'editor', 'thumbnail' ),
            'taxonomies'        => array( 'group' ),
            'hierarchical'      => false,
            'public'            => false,
            'show_in_menu'      => true,
            'menu_position'     => 5,
            'menu_icon'         => Utils::get_plugin_icon(),
            'show_in_admin_bar' => true,
            'can_export'        => true,
            'has_archive'       => false,
            'show_ui'           => true,
            'rewrite'           => false,
            'capability_type'   => 'post',
        );
        
        if ( Utils::has_archive() ) {
            $args['public'] = true;
            $args['has_archive'] = Utils::get_archive_slug();
            $args['rewrite'] = [
                'slug' => Utils::get_archive_slug(),
            ];
        }
        
        register_post_type( Utils::post_type_name(), $args );
    }
    
    public function get_taxonomy_args( $single_name, $plural_name )
    {
        $single_name = ucfirst( $single_name );
        $plural_name = ucfirst( $plural_name );
        $plural_name_lc = lcfirst( $plural_name );
        $labels = array(
            'name'                       => $plural_name,
            'singular_name'              => $single_name,
            'menu_name'                  => sprintf( __( '%s', 'wpspeedo-team' ), $plural_name ),
            'all_items'                  => sprintf( __( 'All %s', 'wpspeedo-team' ), $plural_name ),
            'popular_items'              => sprintf( __( 'Popular %s', 'wpspeedo-team' ), $plural_name ),
            'search_items'               => sprintf( __( 'Search %s', 'wpspeedo-team' ), $plural_name ),
            'items_list'                 => sprintf( __( '%s list', 'wpspeedo-team' ), $plural_name ),
            'items_list_navigation'      => sprintf( __( '%s list navigation', 'wpspeedo-team' ), $plural_name ),
            'separate_items_with_commas' => sprintf( __( 'Separate %s with commas', 'wpspeedo-team' ), $plural_name_lc ),
            'no_terms'                   => sprintf( __( 'No %s', 'wpspeedo-team' ), $plural_name_lc ),
            'add_or_remove_items'        => sprintf( __( 'Add or remove %s', 'wpspeedo-team' ), $plural_name_lc ),
            'parent_item'                => sprintf( __( 'Parent %s', 'wpspeedo-team' ), $single_name ),
            'parent_item_colon'          => sprintf( __( 'Parent %s:', 'wpspeedo-team' ), $single_name ),
            'new_item_name'              => sprintf( __( 'New %s Name', 'wpspeedo-team' ), $single_name ),
            'add_new_item'               => sprintf( __( 'Add New %s', 'wpspeedo-team' ), $single_name ),
            'edit_item'                  => sprintf( __( 'Edit %s', 'wpspeedo-team' ), $single_name ),
            'update_item'                => sprintf( __( 'Update %s', 'wpspeedo-team' ), $single_name ),
            'view_item'                  => sprintf( __( 'View %s', 'wpspeedo-team' ), $single_name ),
            'choose_from_most_used'      => __( 'Choose from the most used', 'wpspeedo-team' ),
            'not_found'                  => __( 'Not Found', 'wpspeedo-team' ),
        );
        $args = array(
            'labels'            => $labels,
            'hierarchical'      => true,
            'public'            => false,
            'show_ui'           => true,
            'show_admin_column' => true,
            'show_in_nav_menus' => true,
            'show_tagcloud'     => false,
        );
        return $args;
    }
    
    /*
     * Register Custom Taxonomies
     */
    public function register_taxonomies()
    {
        // Group Taxonomy
        
        if ( Utils::get_setting( 'enable_group_taxonomy' ) ) {
            $args = $this->get_taxonomy_args( Utils::get_setting( 'group_single_name' ), Utils::get_setting( 'group_plural_name' ) );
            
            if ( Utils::has_archive() && Utils::has_group_archive() ) {
                $args['public'] = true;
                $args['rewrite'] = [
                    'slug'         => Utils::get_group_archive_slug(),
                    'with_front'   => true,
                    'hierarchical' => false,
                ];
            }
            
            register_taxonomy( Utils::group_taxonomy_name(), array( Utils::post_type_name() ), $args );
        }
    
    }
    
    /*
     * Register Custom Metaboxes
     */
    public function register_metaboxes()
    {
        add_meta_box(
            'member-details',
            __( 'Member\'s Details', 'wpspeedo-team' ),
            array( $this, 'metabox_content' ),
            Utils::post_type_name()
        );
    }
    
    /*
     * Custom Metabox Content
     */
    public function metabox_content()
    {
        global  $post ;
        $meta_data = $this->get_validated_meta_data( $post->ID );
        // Sanitization & Validation Done
        printf( "<div id='wps-meta-boxes'><meta-box meta_data='%s'></meta-box></div>", esc_attr( json_encode( $meta_data ) ) );
        printf( '<input type="hidden" name="_wps_meta_nonce" value="%s" />', wp_create_nonce( 'wps_save_meta_' . get_the_ID() ) );
    }
    
    /*
     * Handle Meta Fields Saving
     */
    public function save_meta_fields( $post_id )
    {
        if ( empty($_POST['_wps_meta_nonce']) ) {
            return $post_id;
        }
        if ( !wp_verify_nonce( $_POST['_wps_meta_nonce'], 'wps_save_meta_' . $post_id ) ) {
            return $post_id;
        }
        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
            return $post_id;
        }
        if ( get_post_status( $post_id ) === 'auto-draft' ) {
            return $post_id;
        }
        if ( !current_user_can( 'edit_page', $post_id ) || !current_user_can( 'edit_post', $post_id ) ) {
            return $post_id;
        }
        if ( !array_key_exists( '_wps_member_meta_data', $_POST ) || empty($_POST['_wps_member_meta_data']) ) {
            return;
        }
        $meta_data = json_decode( stripslashes( sanitize_text_field( $_POST['_wps_member_meta_data'] ) ), true );
        $meta_data = $this->get_validated_meta_data( $post_id, $meta_data );
        // Sanitization & Validation Done
        foreach ( $meta_data as $meta_key => $meta_value ) {
            update_post_meta( $post_id, $meta_key, $meta_value );
            Utils::update_all_posts_meta_vals();
        }
        $meta_keys = array_keys( $meta_data );
        update_post_meta( $post_id, '_wps_member_meta_keys', $meta_keys );
    }
    
    /*
     * Get sanitized meta data
     */
    public function get_sanitize_meta_data( $data = array() )
    {
        foreach ( $data as $meta_key => $meta_val ) {
            if ( empty($meta_val) ) {
                continue;
            }
            
            if ( in_array( $meta_key, [
                '_designation',
                '_company',
                '_ribbon',
                '_color',
                '_experience',
                '_mobile',
                '_telephone'
            ] ) ) {
                $data[$meta_key] = sanitize_text_field( $meta_val );
                continue;
            }
            
            
            if ( $meta_key == '_email' ) {
                $data[$meta_key] = sanitize_email( $meta_val );
                continue;
            }
            
            
            if ( $meta_key == '_website' ) {
                $data[$meta_key] = sanitize_url( $meta_val );
                continue;
            }
            
            
            if ( $meta_key == '_social_links' ) {
                foreach ( $meta_val as &$s_link ) {
                    if ( !empty($s_link['social_icon']) ) {
                        $s_link['social_icon'] = array_map( 'sanitize_text_field', $s_link['social_icon'] );
                    }
                    if ( !empty($s_link['social_link']) ) {
                        $s_link['social_link'] = sanitize_url( $s_link['social_link'] );
                    }
                }
                $data[$meta_key] = $meta_val;
                continue;
            }
            
            
            if ( $meta_key == '_skills' ) {
                foreach ( $meta_val as &$skill ) {
                    if ( !empty($skill['skill_name']) ) {
                        $skill['skill_name'] = sanitize_text_field( $skill['skill_name'] );
                    }
                    if ( !empty($skill['skill_val']) ) {
                        $skill['skill_val'] = (int) $skill['skill_val'];
                    }
                }
                $data[$meta_key] = $meta_val;
                continue;
            }
        
        }
        return $data;
    }
    
    /*
     * Get validated meta data
     */
    public function get_validated_meta_data( $post_id, $data = array() )
    {
        // Reading the Meta Fields
        
        if ( empty($data) ) {
            $meta_keys = get_post_meta( $post_id, '_wps_member_meta_keys', true );
            if ( !empty($meta_keys) ) {
                foreach ( $meta_keys as $wps_meta_key ) {
                    $data[$wps_meta_key] = get_post_meta( $post_id, $wps_meta_key, true );
                }
            }
        }
        
        return $this->get_sanitize_meta_data( $data );
    }

}