<?php
add_action('admin_menu', 'wml_create_menu');
add_action("admin_print_scripts", 'wml_adminjslibs');
add_action("admin_print_styles", 'wml_adminCsslibs');
add_action('wp_enqueue_scripts', 'wml_client_js_css');

function wml_create_menu() { // Create menu for the plugin
	add_menu_page( 'WP Masonry Posts', 'WP Masonry', 'manage_options', 'wml_shortcodes', 'wml_shortcodes', '');
	add_submenu_page( 'wml_shortcodes', 'Shortcodes', 'Shortcodes', 'manage_options', 'wml_shortcodes', 'wml_shortcodes');
	add_submenu_page( 'wml_shortcodes', 'Documentation', 'Documentation', 'manage_options', 'wml_documentation', 'wml_documentation');
	add_submenu_page( 'wml_shortcodes', 'Get Pro Version', 'Get Pro Version', 'manage_options', 'wml_get_pro_version', 'wml_get_pro_version');
}

function wml_adminjslibs(){ // Load needed js
	wp_register_script('wml_validate_js',plugins_url("wp-masonry-layout/js/jquery.validate.min.js"));		
	wp_enqueue_script('wml_validate_js');
}

function wml_adminCsslibs(){ // Load needed css
	wp_register_style('wml_admin_style', plugins_url('wp-masonry-layout/css/wmlc_admin.css'));
    wp_enqueue_style('wml_admin_style');
}

function wml_client_js_css(){
	wp_enqueue_script( 'jquery');
	wp_enqueue_script( 'masonry' );
	wp_register_script('wmljs',plugins_url("wp-masonry-layout/js/wmljs.js"));		
	wp_enqueue_script('wmljs');
	
	
	/* FOR PRO VERSION ONLY */
	wp_register_script('wml_infinity_scroll',plugins_url("wp-masonry-layout/js/jquery.infinitescroll.min.js"));
	wp_enqueue_script('wml_infinity_scroll');
	/* EOF FOR PRO VERSION ONLY */
	
	wp_register_style('wml_client_style', plugins_url('wp-masonry-layout/css/wmlc_client.css'));
    wp_enqueue_style('wml_client_style');
}

function wml_activate(){ // When plugin gets activated or updated.
	$primaryKey = get_option('wmlo_primary_key');
	if (empty($primaryKey)){
		update_option('wmlo_primary_key','1');
	}
}

function wml_fill_up_form($fillUpData, $fieldkey, $selectOptionValue = ''){
	if (!empty($fillUpData)){
		if (array_key_exists($fieldkey,$fillUpData)){
			if (empty($selectOptionValue)){
				return $fillUpData[$fieldkey];
			} else {
				if ($fillUpData[$fieldkey] == $selectOptionValue){
					return 'selected="selected"';
				} else {
					return '';
				}
			}
		}
	}
	return '';
}

function wml_shortcodes(){ // Main page
	include('includes/admin/common/wml_header.php');
	include('includes/admin/shortcode/wml_shortcodes.php');
	include('includes/admin/shortcode/wml_add_edit_shortcode.php');
	include('includes/admin/common/wml_footer.php');
}

function wml_get_pro_version(){ // Get full version
	include('includes/admin/common/wml_header.php');
	include('includes/admin/go_pro/wml_go_pro.php');
	include('includes/admin/common/wml_footer.php');
}

function wml_documentation(){
	include('includes/admin/common/wml_header.php');
	include('includes/admin/documentation/wml_documentation.php');
	include('includes/admin/common/wml_footer.php');
}

// SHORTCODE
add_shortcode( 'wmls', 'wml_shortcode');
function wml_shortcode($atts){
	if (array_key_exists('id',$atts)){ // Check if shortcode ID is passed
		$shortcodeId = $atts['id'];
		if (!empty($shortcodeId)){ // Check if Id is not empty
			$shortcodesRawData 	= get_option('wmlo_shortcodes_data');
			$shortcodesData		= json_decode($shortcodesRawData, true);
			if (array_key_exists($shortcodeId, $shortcodesData)){ // Check if requested shortcode is in our record.
				$shortcodeData	= $shortcodesData[$shortcodeId];
				
				ob_start();
				include('themes/'.$shortcodeData['wmlo_layout_theme'].'/style.php');
				include('includes/client/wml_container.php');
				$masonryContainerOutput = ob_get_clean();
				return $masonryContainerOutput;
				
			} else {
				echo "WP Masonry Posts : Coudln't find shortcode in our record.";
			}
		} else {
			return 'WP Masonry Posts : Shortcode ID Empty.';	
		}
	} else {
		return 'WP Masonry Posts : Shortcode ID Undefined.';
	}
}

// AJAX HANDELR
add_action("wp_ajax_nopriv_wml_load_posts", "wml_ajax_load_posts");
add_action("wp_ajax_wml_load_posts", "wml_ajax_load_posts");
function wml_ajax_load_posts(){
	global $randSeed;
	$returnData			= array();
	$shortcodeId 		= $_GET['shortcodeId'];
	$pageNumber 		= $_GET['pageNumber'];
	$randSeed			= $_GET['randSeed'];
	$shortcodesRawData 	= get_option('wmlo_shortcodes_data');
	$shortcodesData		= json_decode($shortcodesRawData, true);
	if (array_key_exists($shortcodeId, $shortcodesData)){ // Check if requested shortcode is in our record.
		$shortcodeData	= $shortcodesData[$shortcodeId];
		$themeLayout	= $shortcodeData['wmlo_layout_theme'];
		
		//CREATE QUERY ARGUMENTS
		$query_arg = array(
				   'post_type' 		=> $shortcodeData['wmlo_post_type'],
				   'posts_per_page'	=> $shortcodeData['wmlo_post_count'],
				   'post_status'	=> 'publish'
				   );

		if (($shortcodeData['wmlo_post_type'] == 'post') && ($shortcodeData['wmlo_post_category'] > 0)){ // If post type is post and category is selected
			$query_arg['cat']	= wml_wpml_icl_object_id($shortcodeData['wmlo_post_category'], 'category');
		}
		
		if (($shortcodeData['wmlo_post_type'] == 'product') && ($shortcodeData['wmlo_product_category'] > 0)){ // If post type is post and category is selected
			$product_cat_term = get_term( $shortcodeData['wmlo_product_category'], 'product_cat' );
			$product_cat_slug = $product_cat_term->slug;
			
			$query_arg['product_cat']		= $product_cat_slug;
		}
		
		if (($shortcodeData['wmlo_post_type'] == 'page') && ($shortcodeData['wmlo_page_parent'] > 0)){ // If post type is page and page parent is selected
			$query_arg['post_parent']	= $shortcodeData['wmlo_page_parent'];
		}
		
		if ($shortcodeData['wmlo_order_by'] != '0'){
			if ($shortcodeData['wmlo_order_by'] == 'rand'){
				add_filter('posts_orderby', 'wml_post_orderby');
			} else {
				$query_arg['orderby']	= $shortcodeData['wmlo_order_by'];
			}
		}
		
		if ($shortcodeData['wmlo_order'] != '0'){
			$query_arg['order']		= $shortcodeData['wmlo_order'];
		}
		
		if ($pageNumber > 0){
			$query_arg['paged']     = $pageNumber;
		}
		include('themes/loader.php');
	} else {
		$returnData['status']			= 'no_shortcode';
		$returnData['message'] 			= "WP Masonry Posts : Coudln't find shortcode in our record.";
	}
	echo json_encode($returnData);
	die();
}

// RANDOM UNIQUE POST FILTER
function wml_post_orderby($orderby_statement) {
    global $randSeed;
	$orderby_statement = 'RAND('.$randSeed.')';
    return $orderby_statement;
}


function wml_wpml_icl_object_id($cat_id, $type){
	if (function_exists('icl_object_id')){ // Check ig WPML plugin is enabled.
		return icl_object_id($cat_id, $type, true); // Reference  : http://wpml.org/documentation/support/creating-multilingual-wordpress-themes/language-dependent-ids/
	} else {
		return $cat_id;
	}
}