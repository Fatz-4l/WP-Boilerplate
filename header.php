<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
   <meta charset="<?php bloginfo('charset'); ?>">
   <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
   <title><?php wp_title('|', true, 'right'); ?></title>
   <?php wp_head(); ?>

   <!-- Font declarations -->
   <link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/src/css/fonts.css">

</head>

<body <?php body_class(); ?>>
   <?php get_template_part('templates/navigation/nav-header'); ?>