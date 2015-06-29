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


//Sidebars
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
    echo sprintf('<div id="product_total_price">%s %s</div>',__('', 'woocommerce'),'<input type="text" disabled=disabled class="price" value="' . number_format($product->get_price(), 2).'" />');
    ?>
        <script>
            jQuery(function($){
                var price = <?php echo $product->get_price(); ?>;
 
                $('[name=quantity]').change(function(){
                    if (!(this.value < 1)) {
                        var product_total = parseFloat(price * this.value);
                        $('#product_total_price .price').val( product_total.toFixed(2));
                    }
 
                });
            });
        </script>
    <?php
}

//Woocommerce add next step
	add_filter( 'template_include', 'so_30978278_single_product_alt' );
	function so_30978278_single_product_alt( $template ){
		if ( is_single() && get_post_type() == 'product' && isset( $_GET['next-step'] ) && intval( $_GET['next-step'] ) == 1 ) {
			$template = locate_template( 'single-product-step2.php' );
		}
		return $template; 
	}

if(!isset($_GET['next-step'])){
	add_action( 'woocommerce_before_add_to_cart_form', 'so_30978278_additional_template_button' );
	function so_30978278_additional_template_button(){
		printf( '<a id="add-to-cart-button" href="%s">%s <i class="fa fa-arrow-circle-right"></i></a>', esc_url( add_query_arg( 'next-step', 1 ) ), __( 'Volgende stap' ) );
	}
}

function custom_add_to_cart_redirect() { 
    return 'galerij'; 
}
add_filter( 'woocommerce_add_to_cart_redirect', 'custom_add_to_cart_redirect' );


//Woocommerce dimensions
add_filter( 'woocommerce_catalog_settings', 'add_woocommerce_dimension_units' );
 
function add_woocommerce_dimension_units( $settings ) {
  foreach ( $settings as &$setting ) {
 
    if ( $setting['id'] == 'woocommerce_dimension_unit' ) {
      $options = array();
 
      foreach ( $setting['options'] as $key => $value ) {
        if ( $key == 'in' ) {
          // safely add foot and mile to the dimensions units, in the correct order
          $options[ $key ] = $value;
 
          if ( ! isset( $setting['options']['ft'] ) ) $options['ft'] = __( 'ft' );  // foot
          if ( ! isset( $options['yd'] ) )            $options['yd'] = __( 'yd' );  // yard (correct order)
          if ( ! isset( $setting['options']['mi'] ) ) $options['mi'] = __( 'mi' );  // mile
 
        } else {
          // maintain all other existing dimensions
          if ( ! isset( $options[ $key ] ) ) $options[ $key ] = $value;
        }
      }
      $setting['options'] = $options;
    }
  }
 
  return $settings;
}
?>