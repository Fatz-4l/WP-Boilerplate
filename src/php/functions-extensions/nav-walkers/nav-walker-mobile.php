<?php
/**
 * Mobile Navigation Walker
 * This builds the mobile navigation menu
 */

class Mobile_Nav_Walker extends Walker_Nav_Menu {

    public function start_lvl(&$output, $depth = 0, $args = null) {
        $padding = ($depth >= 2) ? 'pl-2' : 'pl-1';

        $output .= '<ul class="sub-menu flex flex-col depth-' . $depth . ' ' . $padding . ' transition-all duration-300">';
    }

    public function start_el(&$output, $item, $depth = 0, $args = null, $id = 0) {
        $has_children = in_array('menu-item-has-children', $item->classes);
        $is_top = ($depth == 0);
        $active_class = (in_array('current-menu-item', $item->classes) || in_array('current-menu-ancestor', $item->classes)) ? ' font-semibold' : ' font-light';

        // Parent with children (top level)
        if ($has_children && $is_top) {
            $li_class = 'menu-item-parent mt-4 first:mt-0';
            $a_class = 'sub-menu-toggle flex items-center  transition-colors duration-200 w-full' . $active_class;
            $arrow = '<img src="' . get_template_directory_uri() . '/src/media/base/arrow-down.svg" class="dropdown-arrow ml-auto w-4 h-4 invert transition-all duration-300 mr-2">';
        } elseif ($has_children) {
            $li_class = 'menu-item-sub-parent relative mt-4';
            $a_class = 'sub-menu-toggle flex items-center justify-between transition-colors duration-200' . $active_class;
            $arrow = '<img src="' . get_template_directory_uri() . '/src/media/base/arrow-down.svg" class="dropdown-arrow ml-auto w-4 h-4 invert transition-all duration-300 mr-2">';
        } elseif ($is_top) {
            $li_class = 'menu-item-regular mt-4 first:mt-0';
            $a_class = 'transition-colors duration-200' . $active_class;
            $arrow = '';
        } else {
            $li_class = 'menu-item-regular mt-4';
            $a_class = 'transition-colors duration-200' . $active_class;
            $arrow = '';
        }

        $output .= '<li class="' . $li_class . '">';
        $output .= '<a href="' . $item->url . '" class="' . $a_class . '">';
        $output .= $item->title;
        $output .= $arrow;
        $output .= '</a>';
    }

    public function end_el(&$output, $item, $depth = 0, $args = null) {
        $output .= '</li>';
    }

    public function end_lvl(&$output, $depth = 0, $args = null) {
        $output .= '</ul>';
    }
}