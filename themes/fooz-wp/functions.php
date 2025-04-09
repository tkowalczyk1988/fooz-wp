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
 * Shortcode for displaying the most recent book title with link
 */
function fooz_wp_recent_book_title_shortcode() {
    $args = array(
        'post_type'      => 'book',
        'posts_per_page' => 1,
        'orderby'        => 'date',
        'order'          => 'DESC',
    );

    $recent_book = get_posts($args);

    if (!empty($recent_book)) {
        return sprintf(
            '<a href="%s" class="recent-book-link"><span class="recent-book-title">%s</span></a>',
            esc_url(get_permalink($recent_book[0]->ID)),
            esc_html($recent_book[0]->post_title)
        );
    }

    return esc_html__('No books found', 'fooz-wp');
}
add_shortcode('recent_book', 'fooz_wp_recent_book_title_shortcode');
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

    // Enqueue book styles
    if (is_singular('book')) {
        wp_enqueue_style( 'fooz-wp-book-style',
            get_stylesheet_directory_uri() . '/assets/css/book.css',
            array( 'fooz-wp-style' ),
            wp_get_theme()->get('Version')
        );
    }

    // Enqueue book genre archive styles
    if (is_tax('book-genre')) {
        wp_enqueue_style( 'fooz-wp-book-genre-style',
            get_stylesheet_directory_uri() . '/assets/css/book-genre.css',
            array( 'fooz-wp-style' ),
            wp_get_theme()->get('Version')
        );
    }
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