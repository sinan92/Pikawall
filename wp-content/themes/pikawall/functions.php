<?php
add_theme_support( 'menus' );
add_theme_support( 'widgets' );
remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10);
remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10);
remove_action( 'woocommerce_before_main_content','woocommerce_breadcrumb', 20, 0); //Breadcrumbs eruit halen

add_action('woocommerce_before_main_content', 'my_theme_wrapper_start', 10);
add_action('woocommerce_after_main_content', 'my_theme_wrapper_end', 10);

function my_theme_wrapper_start() {
  echo '<div id="main"><div class="wrap"><div id="content">';
}

function my_theme_wrapper_end() {
  echo '</div>';
}

add_action( 'after_setup_theme', 'woocommerce_support' );
function woocommerce_support() {
    add_theme_support( 'woocommerce' );
}

//Resize Images
if ( function_exists( 'add_theme_support' ) ) {

	add_theme_support( 'post-thumbnails' );

        set_post_thumbnail_size( 300, 300); // default Post Thumbnail dimensions   

}


//Sidebar
function your_widget(){

register_sidebar(array( 
    'name' => 'Cat Sidebar',
    'id' => 'cat-sidebar', // I also added the ID but doesn't work 
    'before_widget' => '<div id="%1$s" class="omc-footer-widget %2$s">',    
    'after_widget' => '</div>', 
    'before_title' => '<h4>',   
    'after_title' => '</h4>'   
));

}

add_action( 'widgets_init', 'your_widget' );

/*
 * wc_remove_related_products
 * 
 * Clear the query arguments for related products so none show.
 * Add this code to your theme functions.php file.  
 */
function wc_remove_related_products( $args ) {
	return array();
}
add_filter('woocommerce_related_products_args','wc_remove_related_products', 10); 

?>