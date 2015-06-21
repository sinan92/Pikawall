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

add_filter( 'woocommerce_product_tabs', 'woo_remove_product_tabs', 98 );

function woo_remove_product_tabs( $tabs ) {

    unset( $tabs['description'] );      	// Remove the description tab
    unset( $tabs['reviews'] ); 			// Remove the reviews tab
    unset( $tabs['additional_information'] );  	// Remove the additional information tab

    return $tabs;

}

// we are going to hook this on priority 31, so that it would display below add to cart button.
add_action( 'woocommerce_single_product_summary', 'woocommerce_total_product_price', 30 );
function woocommerce_total_product_price() {
    global $woocommerce, $product;
    // let's setup our divs
    echo sprintf('<div id="product_total_price" style="margin-bottom:20px;">%s %s</div>',__('', 'woocommerce'),'<span class="price">'. get_woocommerce_currency_symbol() . number_format($product->get_price(), 2).'</span>');
    ?>
        <script>
            jQuery(function($){
                var price = <?php echo $product->get_price(); ?>,
                    currency = '<?php echo get_woocommerce_currency_symbol(); ?>';
 
                $('[name=quantity]').change(function(){
                    if (!(this.value < 1)) {
                        var product_total = parseFloat(price * this.value);
                        $('#product_total_price .price').html( currency + product_total.toFixed(2));
                    }
 
                });
            });
        </script>
    <?php
}
?>