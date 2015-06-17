<?php
/*
Plugin Name: Simple Masonry Gallery
Plugin URI: http://wordpress.org/plugins/simple-masonry-gallery/
Version: 3.4
Description: Add the effect of Masonry to image.
Author: Katsushi Kawamori
Author URI: http://riverforest-wp.info/
Text Domain: simplemasonry
Domain Path: /languages
*/

/*  Copyright (c) 2014- Katsushi Kawamori (email : dodesyoswift312@gmail.com)
    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; version 2 of the License.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/

	load_plugin_textdomain('simplemasonry', false, basename( dirname( __FILE__ ) ) . '/languages' );

	define("SIMPLEMASONRY_PLUGIN_BASE_FILE", plugin_basename(__FILE__));
	define("SIMPLEMASONRY_PLUGIN_BASE_DIR", dirname(__FILE__));
	define("SIMPLEMASONRY_PLUGIN_URL", plugins_url($path='',$scheme=null).'/simple-masonry-gallery');

	require_once( dirname( __FILE__ ) . '/req/SimpleMasonryRegist.php' );
	$simplemasonryregistandheader = new SimpleMasonryRegist();
	add_action('admin_init', array($simplemasonryregistandheader, 'register_settings'));
	unset($simplemasonryregistandheader);

	require_once( dirname( __FILE__ ) . '/req/SimpleMasonryAdmin.php' );
	$simplemasonryadmin = new SimpleMasonryAdmin();
	add_action( 'admin_menu', array($simplemasonryadmin, 'plugin_menu'));
	add_action( 'admin_enqueue_scripts', array($simplemasonryadmin, 'load_custom_wp_admin_style') );
	add_action( 'admin_menu', array($simplemasonryadmin, 'add_apply_simplemasonry_custom_box'));
	add_action( 'save_post', array($simplemasonryadmin, 'save_apply_simplemasonry_postdata'));
	add_filter( 'plugin_action_links', array($simplemasonryadmin, 'settings_link'), 10, 2 );
	add_filter('manage_posts_columns', array($simplemasonryadmin, 'posts_columns_simplemasonry'));
	add_action('manage_posts_custom_column', array($simplemasonryadmin, 'posts_custom_columns_simplemasonry'), 10, 2);
	add_filter('manage_pages_columns', array($simplemasonryadmin, 'pages_columns_simplemasonry'));
	add_action('manage_pages_custom_column', array($simplemasonryadmin, 'pages_custom_columns_simplemasonry'), 10, 2);
	add_action( 'admin_footer', array($simplemasonryadmin, 'load_custom_wp_admin_style2') );
	unset($simplemasonryadmin);

	include_once( SIMPLEMASONRY_PLUGIN_BASE_DIR.'/inc/SimpleMasonry.php' );
	$simplemasonry = new SimpleMasonry();
	$footer_jscss_s = array();
	$simplemasonry->footer_jscss_s = $footer_jscss_s;

	add_action( 'the_post', array($simplemasonry, 'filter_select') );

	add_action( 'wp_footer', array($simplemasonry, 'add_footer') );

	unset($simplemasonry);

?>