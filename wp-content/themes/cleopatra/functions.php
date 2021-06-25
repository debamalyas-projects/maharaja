<?php
session_start();
// add featured image in post
add_theme_support ( 'post-thumbnails' );

add_theme_support ( 'woocommerce' );

add_theme_support( 'automatic-feed-links' );

// Add RSS Links to <head><meta http-equiv="Content-Type" content="text/html; charset=utf-8"> section

// Nav Menu registration for 3.0 and above
if (function_exists ( 'register_nav_menus' ))
 register_nav_menus ( array (
   'main_nav' => 'main_menu', 
   'footer_nav' => 'footer_menu', 
 ) );  function change_submenu_class($menu) {    $menu = preg_replace('/ class="sub-menu"/','/ class="sub" /',$menu);    return $menu;  }  add_filter('wp_nav_menu','change_submenu_class');  
 ?>