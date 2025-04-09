<?php
/**
 * Register Book Genre Taxonomy
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

/**
 * Register Genre taxonomy for Books
 */
function fooz_wp_register_book_genre_taxonomy() {
    $labels = array(
        'name'                       => _x( 'Genres', 'taxonomy general name', 'fooz-wp' ),
        'singular_name'              => _x( 'Genre', 'taxonomy singular name', 'fooz-wp' ),
        'search_items'               => __( 'Search Genres', 'fooz-wp' ),
        'popular_items'              => __( 'Popular Genres', 'fooz-wp' ),
        'all_items'                  => __( 'All Genres', 'fooz-wp' ),
        'parent_item'                => __( 'Parent Genre', 'fooz-wp' ),
        'parent_item_colon'          => __( 'Parent Genre:', 'fooz-wp' ),
        'edit_item'                  => __( 'Edit Genre', 'fooz-wp' ),
        'view_item'                  => __( 'View Genre', 'fooz-wp' ),
        'update_item'                => __( 'Update Genre', 'fooz-wp' ),
        'add_new_item'               => __( 'Add New Genre', 'fooz-wp' ),
        'new_item_name'              => __( 'New Genre Name', 'fooz-wp' ),
        'separate_items_with_commas' => __( 'Separate genres with commas', 'fooz-wp' ),
        'add_or_remove_items'        => __( 'Add or remove genres', 'fooz-wp' ),
        'choose_from_most_used'      => __( 'Choose from the most used genres', 'fooz-wp' ),
        'not_found'                  => __( 'No genres found.', 'fooz-wp' ),
        'no_terms'                   => __( 'No genres', 'fooz-wp' ),
        'menu_name'                  => __( 'Genres', 'fooz-wp' ),
        'back_to_items'              => __( '← Back to Genres', 'fooz-wp' ),
    );

    $args = array(
        'labels'            => $labels,
        'hierarchical'      => true,
        'public'            => true,
        'show_ui'           => true,
        'show_admin_column' => true,
        'show_in_nav_menus' => true,
        'show_tagcloud'     => true,
        'show_in_rest'      => true,
        'rewrite'           => array(
            'slug'          => 'book-genre',
            'with_front'    => true,
            'hierarchical'  => true,
        ),
    );

    register_taxonomy( 'book-genre', array( 'book' ), $args );
}
add_action( 'init', 'fooz_wp_register_book_genre_taxonomy' );