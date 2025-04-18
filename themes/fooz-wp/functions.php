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
        'posts_per_page' => 5,
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
 * Shortcode for displaying books from specific genre
 * Usage: [genre_books genre_id="123"]
 */
function fooz_wp_genre_books_shortcode($atts) {
    // Parse attributes
    $atts = shortcode_atts(
        array(
            'genre_id' => 0,
        ),
        $atts,
        'genre_books'
    );

    // Validate genre_id
    $genre_id = intval($atts['genre_id']);
    if ($genre_id <= 0) {
        return esc_html__('Please provide a valid genre ID', 'fooz-wp');
    }

    // Check if term exists
    $term = get_term($genre_id, 'book-genre');
    if (is_wp_error($term) || !$term) {
        return esc_html__('Genre not found', 'fooz-wp');
    }

    // Query arguments
    $args = array(
        'post_type'      => 'book',
        'posts_per_page' => 5,
        'orderby'        => 'title',
        'order'          => 'ASC',
        'tax_query'      => array(
            array(
                'taxonomy' => 'book-genre',
                'field'    => 'term_id',
                'terms'    => $genre_id,
            ),
        ),
    );

    $books = get_posts($args);

    if (empty($books)) {
        return sprintf(
            '<p class="no-books-message">%s</p>',
            esc_html__('No books found in this genre', 'fooz-wp')
        );
    }

    $output = sprintf(
        '<div class="genre-books-list"><h3 class="genre-books-title">%s: %s</h3><ul>',
        esc_html__('Books in genre', 'fooz-wp'),
        esc_html($term->name)
    );

    foreach ($books as $book) {
        $output .= sprintf(
            '<li class="genre-book-item"><a href="%s" class="genre-book-link">%s</a></li>',
            esc_url(get_permalink($book->ID)),
            esc_html($book->post_title)
        );
    }

    $output .= '</ul></div>';

    return $output;
}
add_shortcode('genre_books', 'fooz_wp_genre_books_shortcode');

/**
 * Shortcode for loading books with AJAX
 */
function fooz_wp_load_books_shortcode() {
    // Ensure scripts are enqueued
    wp_enqueue_script('fooz-wp-scripts');

    $output = sprintf(
        '<div class="fooz-books-loader">
        <div class="fooz-books-container"></div>
        <button type="button" class="fooz-load-books-btn">%s</button>
        </div>',
        esc_html__('Load books', 'fooz-wp')
    );

    return $output;
}
add_shortcode('load_books', 'fooz_wp_load_books_shortcode');

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
 * AJAX callback for getting books
 */
function fooz_wp_get_books_callback() {
    // Verify nonce for security
    if (!isset($_REQUEST['nonce']) || !wp_verify_nonce($_REQUEST['nonce'], 'fooz_wp_get_books_nonce')) {
        wp_send_json_error('Invalid nonce');
    }

    // Get page number from request
    $page = isset($_REQUEST['page']) ? max(1, intval($_REQUEST['page'])) : 1;
    $posts_per_page = 20;

    // Get total number of books
    $total_books = wp_count_posts('book')->publish;

    $args = array(
        'post_type'      => 'book',
        'posts_per_page' => $posts_per_page,
        'orderby'        => 'date',
        'order'          => 'DESC',
        'offset'         => ($page - 1) * $posts_per_page,
    );

    $books = get_posts($args);
    $response = array();

    foreach ($books as $book) {
        // Get genres with links
        $genres = array();
        $terms = wp_get_post_terms($book->ID, 'book-genre');
        foreach ($terms as $term) {
            $genres[] = array(
                'name' => $term->name,
                'url'  => get_term_link($term)
            );
        }

        // Format date
        $date = get_the_date('Y-m-d', $book->ID);

        // Get excerpt and limit to 55 characters
        $excerpt = has_excerpt($book->ID)
            ? wp_strip_all_tags(get_the_excerpt($book->ID))
            : wp_strip_all_tags($book->post_content);
        $excerpt = substr($excerpt, 0, 55) . (strlen($excerpt) > 55 ? '...' : '');

        $response[] = array(
            'name'    => $book->post_title,
            'date'    => $date,
            'genre'   => $genres,
            'excerpt' => $excerpt,
            'url'     => get_permalink($book->ID)
        );
    }

    wp_send_json_success(array(
        'books' => $response,
        'page' => $page,
        'total' => $total_books,
        'hasMore' => ($page * $posts_per_page) < $total_books
    ));
}
add_action('wp_ajax_get_books', 'fooz_wp_get_books_callback');
add_action('wp_ajax_nopriv_get_books', 'fooz_wp_get_books_callback');

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

    // Add AJAX URL and nonce to script
    wp_localize_script(
        'fooz-wp-scripts',
        'foozWP',
        array(
            'ajaxurl' => admin_url('admin-ajax.php'),
            'nonce'   => wp_create_nonce('fooz_wp_get_books_nonce'),
            'i18n'    => array(
                'loadBooks' => esc_html__('Load books', 'fooz-wp'),
                'loading'   => esc_html__('Loading...', 'fooz-wp'),
                'error'     => esc_html__('Error loading books', 'fooz-wp')
            )
        )
    );
}
add_action( 'wp_enqueue_scripts', 'fooz_wp_enqueue_scripts' );