<?php
/**
 * Fooz WP Theme functions and definitions
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

/**
 * Include custom post types and taxonomies
 */
require_once get_stylesheet_directory() . '/inc/post-types/books.php';
require_once get_stylesheet_directory() . '/inc/taxonomies/book-genre.php';

/**
 * Enqueue parent theme and child theme styles
 */
function fooz_wp_enqueue_styles() {
    $parent_style = 'twentytwenty-style';

    // Enqueue parent theme's style.css
    wp_enqueue_style( $parent_style,
        get_template_directory_uri() . '/style.css',
        array(),
        wp_get_theme()->parent()->get('Version')
    );

    // Enqueue child theme's style.css
    wp_enqueue_style( 'fooz-wp-style',
        get_stylesheet_uri(),
        array( $parent_style ),
        wp_get_theme()->get('Version')
    );
}
add_action( 'wp_enqueue_scripts', 'fooz_wp_enqueue_styles' );

/**
 * Enqueue custom scripts
 */
function fooz_wp_enqueue_scripts() {
    wp_enqueue_script(
        'fooz-wp-scripts',
        get_stylesheet_directory_uri() . '/assets/js/scripts.js',
        array('jquery'),
        wp_get_theme()->get('Version'),
        true
    );
}
add_action( 'wp_enqueue_scripts', 'fooz_wp_enqueue_scripts' );