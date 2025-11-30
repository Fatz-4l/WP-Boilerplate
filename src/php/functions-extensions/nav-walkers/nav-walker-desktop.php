<?php
/**
 * Desktop Navigation Walker
 * This builds the desktop navigation menu
 */

class Desktop_Nav_Walker extends Walker_Nav_Menu {

    public function start_lvl(&$output, $depth = 0, $args = null) {

        $base_classes = 'dropdown-menu hidden md:flex flex-col gap-4 py-3 px-4 absolute bg-black rounded-md min-w-48 z-50 opacity-0 invisible transition-all duration-300 ease-out';

        switch ($depth) {
            case 0:
                $position_classes = 'top-[2rem] left-0';
                break;
            case 1:
                $position_classes = 'top-0 left-[12rem]';
                break;
        }

        $output .= '<ul class="' . $base_classes . ' depth-' . $depth . ' ' . $position_classes . '">';
    }

    public function start_el(&$output, $item, $depth = 0, $args = null, $id = 0) {
        $has_children = in_array('menu-item-has-children', $item->classes);
        $is_top = ($depth == 0);
        $active_class = (in_array('current-menu-item', $item->classes) || in_array('current-menu-ancestor', $item->classes)) ? ' font-semibold' : ' hover:text-gray-500 transition-all duration-300';

        if ($has_children && $is_top)
        {
            $li_class = 'menu-item-parent relative';
            $a_class = 'dropdown-trigger flex items-center uppercase' . $active_class;
            $arrow = '<img src="' . get_template_directory_uri() . '/src/media/base/arrow-down.svg" class="dropdown-arrow ml-2 w-4 h-4 transition-transform duration-300 invert">';
        }
        elseif ($has_children)
        {
            $li_class = 'menu-item-sub-parent relative';
            $a_class = 'dropdown-trigger-sub flex items-center justify-between w-full text-white' . $active_class;
            $arrow = '<img src="' . get_template_directory_uri() . '/src/media/base/arrow-right.svg" class="dropdown-arrow-right ml-auto w-4 h-4 transition-transform duration-200">';
        }
        elseif ($is_top)
        {
            $li_class = 'menu-item-regular';
            $a_class = 'uppercase' . $active_class;
            $arrow = '';
        }
        else
        {
            $li_class = 'menu-item-regular';
            $a_class = 'text-white' . $active_class;
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