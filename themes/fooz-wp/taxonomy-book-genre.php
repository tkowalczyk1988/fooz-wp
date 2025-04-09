<?php
/**
 * The template for displaying book genre archives
 */

get_header();
?>

<main id="site-content">
    <header class="archive-header has-text-align-center header-footer-group">
        <div class="archive-header-inner section-inner medium">
            <h1 class="archive-title">
                <?php echo esc_html__('Genre:', 'fooz-wp'); ?> <?php echo esc_html(single_term_title('', false)); ?>
            </h1>

            <?php if (term_description()) : ?>
                <div class="archive-subtitle section-inner thin max-percentage intro-text">
                    <?php echo wp_kses_post(wpautop(term_description())); ?>
                </div>
            <?php endif; ?>
        </div>
    </header>

    <?php if (have_posts()) : ?>
        <div class="posts section-inner">
            <?php
            while (have_posts()) :
                the_post();
            ?>
                <article <?php post_class('post-grid'); ?> id="post-<?php the_ID(); ?>">
                    <div class="post-inner">
                        <?php if (has_post_thumbnail()) : ?>
                            <figure class="featured-media">
                                <div class="featured-media-inner section-inner medium">
                                    <a class="thumbnail-link" href="<?php the_permalink(); ?>">
                                        <?php the_post_thumbnail('medium'); ?>
                                    </a>
                                </div>
                            </figure>
                        <?php endif; ?>

                        <header class="entry-header">
                            <h2 class="entry-title heading-size-1">
                                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                            </h2>
                            <div class="post-meta-wrapper">
                                <div class="post-meta">
                                    <span class="post-date"><?php echo get_the_date(); ?></span>
                                </div>
                            </div>
                        </header>
                    </div>
                </article>
            <?php
            endwhile;
            ?>
        </div>

        <?php if (get_the_posts_pagination()) : ?>
            <div class="pagination-wrapper section-inner">
                <hr class="styled-separator pagination-separator is-style-wide" aria-hidden="true">
                <nav class="navigation pagination" role="navigation" aria-label="<?php esc_attr_e('Books', 'fooz-wp'); ?>">
                    <h2 class="screen-reader-text"><?php esc_html_e('Books navigation', 'fooz-wp'); ?></h2>
                    <div class="nav-links">
                        <?php
                        echo get_the_posts_pagination(array(
                            'prev_text'     => '<span class="nav-prev-text">' . __('Previous', 'fooz-wp') . '</span>',
                            'next_text'     => '<span class="nav-next-text">' . __('Next', 'fooz-wp') . '</span>',
                            'end_size'      => 2,
                            'mid_size'      => 1,
                        ));
                        ?>
                    </div>
                </nav>
            </div>
        <?php endif; ?>

    <?php
    else :
    ?>
        <div class="no-results section-inner thin">
            <div class="post-inner">
                <div class="entry-content">
                    <p><?php esc_html_e('No books found in this genre.', 'fooz-wp'); ?></p>
                </div>
            </div>
        </div>
    <?php
    endif;
    ?>

</main>

<?php get_footer(); ?>