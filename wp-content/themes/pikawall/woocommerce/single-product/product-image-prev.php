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
		<img src="<?php echo $image_link ?>" alt="<?php echo $image_title ?>" height="500" width="500" />
		<img src="<?php bloginfo('stylesheet_directory'); ?>/img/one.png" alt="<?php echo $image_title ?>" height="500" width="500" id="slide1" />
		<img src="<?php bloginfo('stylesheet_directory'); ?>/img/two.png" alt="<?php echo $image_title ?>" style="margin-left: -501px;" height="500" width="500" id="slide2" />
		<img src="<?php bloginfo('stylesheet_directory'); ?>/img/three.png" alt="<?php echo $image_title ?>" style="margin-left: -501px;" height="500" width="500" id="slide3" />
		<img src="<?php bloginfo('stylesheet_directory'); ?>/img/four.png" alt="<?php echo $image_title ?>" style="margin-left: -501px;" height="500" width="500" id="slide4" />
	</div>
</div>

<script>
function slide(){
	jQuery('#slide4').css('margin-left', -501)
	setTimeout(function(){
	jQuery('#slide1').animate({marginLeft: "500px"});
	jQuery('#slide2').animate({marginLeft: "0"}, 500, function(){
		jQuery('#slide1').css('margin-left', -501)
		setTimeout(function(){
			jQuery('#slide2').animate({marginLeft: "500px"});
			jQuery('#slide3').animate({marginLeft: "0"}, 500, function(){
			jQuery('#slide2').css('margin-left', -501)
				setTimeout(function(){
					jQuery('#slide3').animate({marginLeft: "500px"});
					jQuery('#slide4').animate({marginLeft: "0"}, 500, function(){
						jQuery('#slide3').css('margin-left', -501)
						setTimeout(function(){
							jQuery('#slide4').animate({marginLeft: "500px"});
							jQuery('#slide1').animate({marginLeft: "0"}, 500, function(){
								slide();
							});
						}, 5000);
					});
				}, 5000);
			});
		}, 5000);
	});
	}, 5000);
}

jQuery(function() {
	slide();
});
</script>
