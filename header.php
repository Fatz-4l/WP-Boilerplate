<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
   <meta charset="<?php bloginfo('charset'); ?>">
   <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
   <title><?php wp_title(); ?></title>
   <?php wp_head(); ?>

   <!-- Font Import -->
   <link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/src/css/base/fonts.css">

</head>

<body>
   <?php get_template_part('templates/navigation/nav-header'); ?>