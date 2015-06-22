<?php
/**
 * Single Product Image
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.0.14
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $post, $woocommerce, $product;

$image_title = esc_attr( get_the_title( get_post_thumbnail_id() ) );
$image_link  = wp_get_attachment_url( get_post_thumbnail_id() );
?>

<div id="images-prev">
	<div class="image-prev">
		<img src="<?php echo $image_link ?>" alt="<?php echo $image_title ?>" height="250" width="250" />
		<img src="<?php bloginfo('stylesheet_directory'); ?>/img/one.png" alt="<?php echo $image_title ?>" height="250" width="250" />
	</div>
	
	<div class="image-prev">
		<img src="<?php echo $image_link ?>" alt="<?php echo $image_title ?>" height="250" width="250" />
		<img src="<?php bloginfo('stylesheet_directory'); ?>/img/two.png" alt="<?php echo $image_title ?>" height="250" width="250" />
	</div>
	
	<div class="image-prev">
		<img src="<?php echo $image_link ?>" alt="<?php echo $image_title ?>" height="250" width="250" />
		<img src="<?php bloginfo('stylesheet_directory'); ?>/img/three.png" alt="<?php echo $image_title ?>" height="250" width="250" />
	</div>
	
	<div class="image-prev">
		<img src="<?php echo $image_link ?>" alt="<?php echo $image_title ?>" height="250" width="250" />
		<img src="<?php bloginfo('stylesheet_directory'); ?>/img/four.png" alt="<?php echo $image_title ?>" height="250" width="250" />
	</div>
</div>