<?php
/*
Template Name: Default Page Template
*/
?>

<?php get_header();?>

<main id="primary-page-template" class="container ">
   <?php while (have_posts()) : the_post(); ?>
   <?php the_content(); ?>
   <?php endwhile; ?>

</main>

<?php get_footer();?>
