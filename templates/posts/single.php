<?php
/**
 * Template Name: Default Post Template
 */

get_header(); ?>

<main id="primary" class="site-main container py-12">
    <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
        <article <?php post_class(); ?>>
            <?php the_content(); ?>
        </article>
    <?php endwhile; endif; ?>
</main>

<?php get_footer(); ?>