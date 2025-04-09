<?php
/**
 * Register Books Custom Post Type
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

/**
 * Register Books post type
 */
function fooz_wp_register_books_post_type() {
    $labels = array(
        'name'                  => _x( 'Books', 'Post type general name', 'fooz-wp' ),
        'singular_name'         => _x( 'Book', 'Post type singular name', 'fooz-wp' ),
        'menu_name'             => _x( 'Books', 'Admin Menu text', 'fooz-wp' ),
        'name_admin_bar'        => _x( 'Book', 'Add New on Toolbar', 'fooz-wp' ),
        'add_new'               => __( 'Add New', 'fooz-wp' ),
        'add_new_item'          => __( 'Add New Book', 'fooz-wp' ),
        'new_item'              => __( 'New Book', 'fooz-wp' ),
        'edit_item'             => __( 'Edit Book', 'fooz-wp' ),
        'view_item'             => __( 'View Book', 'fooz-wp' ),
        'all_items'             => __( 'All Books', 'fooz-wp' ),
        'search_items'          => __( 'Search Books', 'fooz-wp' ),
        'parent_item_colon'     => __( 'Parent Books:', 'fooz-wp' ),
        'not_found'             => __( 'No books found.', 'fooz-wp' ),
        'not_found_in_trash'    => __( 'No books found in Trash.', 'fooz-wp' ),
        'archives'              => __( 'Book Archives', 'fooz-wp' ),
        'attributes'            => __( 'Book Attributes', 'fooz-wp' ),
        'insert_into_item'      => __( 'Insert into book', 'fooz-wp' ),
        'uploaded_to_this_item' => __( 'Uploaded to this book', 'fooz-wp' ),
        'featured_image'        => __( 'Book Cover', 'fooz-wp' ),
        'set_featured_image'    => __( 'Set book cover', 'fooz-wp' ),
        'remove_featured_image' => __( 'Remove book cover', 'fooz-wp' ),
        'use_featured_image'    => __( 'Use as book cover', 'fooz-wp' ),
    );

    $args = array(
        'labels'                => $labels,
        'public'                => true,
        'publicly_queryable'    => true,
        'show_ui'               => true,
        'show_in_menu'          => true,
        'query_var'             => true,
        'rewrite'               => array( 'slug' => 'library' ),
        'capability_type'       => 'post',
        'has_archive'           => true,
        'hierarchical'          => false,
        'menu_position'         => 5,
        'menu_icon'             => 'dashicons-book-alt',
        'supports'              => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments' ),
        'show_in_rest'          => true,
    );

    register_post_type( 'book', $args );
}
add_action( 'init', 'fooz_wp_register_books_post_type' );