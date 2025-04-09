<?php
/**
 * Template for displaying single book
 */

get_header();
?>

<main id="primary" class="site-main">
    <?php while ( have_posts() ) : the_post(); ?>
        <article id="post-<?php the_ID(); ?>" <?php post_class('book-single'); ?>>
            <div class="book-header">
                <h1 class="book-title"><?php the_title(); ?></h1>
                <div class="book-meta">
                    <span class="book-date">
                        <?php echo esc_html__('Published on:', 'fooz-wp'); ?>
                        <?php echo get_the_date(); ?>
                    </span>
                    <?php
                    $genres = get_the_terms(get_the_ID(), 'book-genre');
                    if ($genres && !is_wp_error($genres)) : ?>
                        <div class="book-genres">
                            <span class="book-genres-label"><?php echo esc_html__('Genres:', 'fooz-wp'); ?></span>
                            <?php foreach ($genres as $genre) : ?>
                                <a href="<?php echo esc_url(get_term_link($genre)); ?>" class="book-genre-link">
                                    <?php echo esc_html($genre->name); ?>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <?php if (has_post_thumbnail()) : ?>
                <div class="book-cover">
                    <?php the_post_thumbnail('large', array('class' => 'book-cover-image')); ?>
                </div>
            <?php endif; ?>

            <div class="book-content">
                <?php the_content(); ?>
            </div>
        </article>
    <?php endwhile; ?>
</main>

<?php
get_footer();