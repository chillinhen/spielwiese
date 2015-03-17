<?php

/*
  Plugin Name: my Customs Posts
  Description: seitenspezifische Custom Posts
 */

//create custom posts
//carousels
function my_dsh_registration() {
    $labels = array(
	'name' => _x('DSH-Anmeldungen', 'post type general name'),
	'singular_name' => _x('DSH-Anmeldung', 'post type singular name'),
	#'add_new' => _x('Add New', 'book'),
	#'add_new_item' => __('Add New Carousel'),
	#'edit_item' => __('Edit Carousel'),
	#'new_item' => __('New Carousel'),
	'all_items' => __('Alle Anmeldungen'),
	'view_item' => __('Anmeldungen ansehen'),
	'search_items' => __('Search Anmeldungen durchsuchen'),
	'not_found' => __('keine Anmeldungen gefunden'),
	'not_found_in_trash' => __('keine gelöschten Anmeldungen gefunden'),
	'parent_item_colon' => '',
	'menu_name' => 'DSH-Anmeldungen'
    );
    $args = array(
	'labels' => $labels,
	'description' => 'sammelt alle DSH Anmeldungen und speichert diese',
	'public' => true,
	'menu_position' => 5,
	'supports' => array('title', 'thumbnail'),
	'has_archive' => true,
    );
    register_post_type('carousel', $args);
}

add_action('init', 'my_dsh_registration');

?>
