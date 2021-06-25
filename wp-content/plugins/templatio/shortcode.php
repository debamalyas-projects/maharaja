<?php
/*
 * Plugin Name: Templatio
 * Description: This plugin is used to store part templates in cleopatra.
 * Version: 1.0
 * Author: Knowlicle
 * Author URI: http://mytasker.com
 */
 

// Creates Templatio Custom Post Type
function templatio_init() {
    $args = array(
      'label' => 'Templatio',
        'public' => false,
		'exclude_from_search' => false,
		'show_in_nav_menus' => false,
		'publicly_queryable' => true,
        'show_ui' => true,
        'capability_type' => 'post',
        'hierarchical' => false,
        'rewrite' => array('slug' => 'templatio'),
        'query_var' => true,
        'menu_icon' => 'dashicons-admin-page',
        'supports' => array(
            'title',
            'editor'
            )
        );
    register_post_type( 'templatio', $args );
}
add_action( 'init', 'templatio_init' );