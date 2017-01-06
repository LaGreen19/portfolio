<?php
function my_theme_enqueue_styles() {

    $parent_style = 'parent-style'; // This is 'bizroot-style' for the Bizroot theme.

    wp_enqueue_style( $parent_style, get_template_directory_uri() . '/style.css' );
    wp_enqueue_style( 'child-style',
        get_stylesheet_directory_uri() . '/style.css',
        array( $parent_style ),
        wp_get_theme()->get('Version')
    );
}
add_action( 'wp_enqueue_scripts', 'my_theme_enqueue_styles' );

add_filter( 'edit_post_link', '__return_false' );

function create_custom_post_types() {
    register_post_type( 'portfolio_projects',
        array(
            'labels' => array(
                'name' => __( 'Portfolio Projects' ),
                'singular_name' => __( 'Portfolio Project' )
            ),
            'public' => true,
            'has_archive' => true,
            'rewrite' => array( 'slug' => 'portfolio-projects' ),
        )
    );
}
add_action( 'init', 'create_custom_post_types' );

?>
