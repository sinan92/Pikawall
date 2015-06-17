<?php
	// GET LIST OF POST TYPES
	$args = array(
		'_builtin' => false
	);
	$post_types['post']	= 'post';
	$post_types['page']	= 'page';
	$post_types	 		= array_merge($post_types, get_post_types($args));
	
	$args = array(
		'hide_empty'               => 1,
		'hierarchical'             => 0
	); 
	
	// ORDER BY ARRAY LIST
	$order_by = array(
						  'none' 			=> 'None',
						  'ID'				=> 'ID',
						  'author'			=> 'Author',
						  'title'			=> 'Title',
						  'date'			=> 'Date',
						  'modified'		=> 'Modified',
						  'parent'			=> 'Parent',
						  'comment_count'	=> 'Comment Count',
						  'menu_order'		=> 'Menu Order',
						  'rand'			=> 'Random'
					  );
	
	// EDIT SHORTCODE
	$shortcodeDetails = '';
	if(isset($_GET['edit_shortcode_key'])){
		$shortcodeEditId 		= $_GET['edit_shortcode_key'];
		$shortcodesRawData 		= get_option('wmlo_shortcodes_data');
		$shortcodesData			= json_decode($shortcodesRawData, true);
		$shortcodeDetails		= $shortcodesData[$shortcodeEditId];
	}

?>
<table class="wp-list-table widefat fixed bookmarks">
    <thead>
        <tr>
            <th><strong><?php echo !empty($shortcodeDetails)?'Edit':'Create'; ?> Shortcode</strong>
            	<?php if(!empty($shortcodeDetails)): ?>
                <div style="float:right;"><a class="add-new-h2 notop" href="admin.php?page=wml_shortcodes">Add Shortcode</a> <a class="add-new-h2 notop" href="admin.php?page=wml_shortcodes">Close</a></div>
                <?php endif; ?>
            </th>
            
        </tr>
    </thead>
    <tbody>
    <tr>
        <td>
            <form action="admin.php?page=wml_shortcodes" id="add_edit_shortcode_form" method="post" enctype="multipart/form-data">
            	<input type="hidden" value="<?php echo !empty($shortcodeDetails)?$shortcodeEditId:''; ?>" id="wmlo_shortcode_id" name="wmlo_shortcode_id" />
                <table class="wml_form">
                    <tr>
                        <td width="170">Reference Name</td>
                        <td><input type="text" name="wmlo_reference_name" value="<?php echo wml_fill_up_form($shortcodeDetails, 'wmlo_reference_name'); ?>" maxlength="50" minlength="5" class="required medium"/></td>
					</tr>
                    
                    <tr>
                    	<td>Layout Theme</td>
                        <td>
                            <select name="wmlo_layout_theme" class="required medium">
                            	<option value=""> -- Select -- </option>
                                <option value="default" <?php echo wml_fill_up_form($shortcodeDetails, 'wmlo_layout_theme', 'default'); ?>>Default</option>
                                <option value="" disabled="disabled">Get Pro Version for more themes.</option>
                            </select>
                        </td>
                    </tr>
                    
                    <tr>
                    	<td>Columns</td>
                        <td>
                            <select name="wmlo_columns" class="required medium">
                            	<option value=""> -- Select -- </option>
								<option value="col1" <?php echo wml_fill_up_form($shortcodeDetails, 'wmlo_columns', 'col1'); ?>>1 Column</option>
                                <option value="col2" <?php echo wml_fill_up_form($shortcodeDetails, 'wmlo_columns', 'col2'); ?>>2 Columns</option>
                                <option value="col3" <?php echo wml_fill_up_form($shortcodeDetails, 'wmlo_columns', 'col3'); ?>>3 Columns</option>
                                <option value="col4" <?php echo wml_fill_up_form($shortcodeDetails, 'wmlo_columns', 'col4'); ?>>4 Columns</option>
                                <option value="col5" <?php echo wml_fill_up_form($shortcodeDetails, 'wmlo_columns', 'col5'); ?>>5 Columns</option>                            </select>
                        </td>
                    </tr>
                    
                    <tr>
                    	<td>Post Type</td>
                        <td>
                        	<select name="wmlo_post_type" id="wmlo_post_type" class="required medium" onchange="openHidePostCategory();">
                        		<option value=""> -- Select -- </option>
								<?php 
                                    foreach ( $post_types as $post_type ) { 
									$postTypeObj = get_post_type_object( $post_type );
									?>
                                    <option value="<?php echo $post_type; ?>" <?php echo wml_fill_up_form($shortcodeDetails, 'wmlo_post_type', $post_type); ?>><?php echo $postTypeObj->labels->name; ?></option>
                                <?php } ?>
                            </select>
                        </td>
                    </tr>
                    
                    <tr id="post_category_holder" class="hidden">
                    	<td>Post Category</td>
                        <td>
                        	<?php wp_dropdown_categories(array('hide_empty' => 0, 'class' => 'medium', 'name' => 'wmlo_post_category', 'hierarchical' => true, 'show_option_none' => 'All', 'selected'=> @$shortcodeDetails['wmlo_post_category'])); ?>
                        </td>
                    </tr>
                    
                    <tr id="product_category_holder" class="hidden">
                    	<td>Product Category</td>
                        <td>
                        	<?php wp_dropdown_categories(array('hide_empty' => 0, 'class' => 'medium', 'name' => 'wmlo_product_category', 'hierarchical' => true, 'show_option_none' => 'All', 'taxonomy' => 'product_cat', 'selected'=> @$shortcodeDetails['wmlo_product_category'])); ?>
                        </td>
                    </tr>
                    
                    <tr id="page_parent_holder" class="hidden">
                    	<td>Child Pages of</td>
                        <td>
                        	<?php wp_dropdown_pages(array('class' => 'medium', 'name' => 'wmlo_page_parent', 'hierarchical' => true, 'show_option_none' => 'None',  'option_none_value' => '-1', 'selected'=> @$shortcodeDetails['wmlo_page_parent'])); ?>
                        </td>
                    </tr>
                    
                    <tr>
                    	<td>Thumbnail Image Size</td>
                        <td>
							<?php $image_sizes = get_intermediate_image_sizes(); ?>
                            <select name="wmlo_image_size" class="required medium">
                              <?php foreach ($image_sizes as $size_name => $size_attrs): var_dump($size_attrs);?>
                                <option value="<?php echo $size_attrs ?>" <?php echo wml_fill_up_form($shortcodeDetails, 'wmlo_image_size', $size_attrs); ?>><?php echo ucwords(str_replace(array('-','_'),' ',$size_attrs)); ?></option>                    
                              <?php endforeach; ?>
                            </select>
                        </td>
                    </tr>
                    
                    <tr>
                    	<td>Post Per Load</td>
                        <td>
                        	<input type="number" maxlength="2" name="wmlo_post_count" value="<?php echo wml_fill_up_form($shortcodeDetails, 'wmlo_post_count'); ?>" class="required small digits" /><br/>
                            <em>No of post you want to load at first. Same number of more <br/>posts are loaded when Load More btn is clicked.</em>
                        </td>
                    </tr>
                    
                    <tr>
                    	<td>Order By</td>
                        <td>
                        	<select name="wmlo_order_by" class="required medium">
                            	<option value="0"> Default </option>
                                <?php foreach ( $order_by as $order_key => $order_value ) { ?>
									<option value="<?php echo $order_key; ?>" <?php echo wml_fill_up_form($shortcodeDetails, 'wmlo_order_by', $order_key); ?>><?php echo $order_value; ?></option>
								<?php }	?>
                            </select>
                        </td>
                    </tr>
                    
                    <tr>
                    	<td>Order</td>
						<td>
                        	<select name="wmlo_order" class="required medium">
                            	<option value="0" <?php echo wml_fill_up_form($shortcodeDetails, 'wmlo_order', '0'); ?>> Default </option>
                               	<option value="ASC" <?php echo wml_fill_up_form($shortcodeDetails, 'wmlo_order', 'ASC'); ?>>Ascending</option>
                                <option value="DESC" <?php echo wml_fill_up_form($shortcodeDetails, 'wmlo_order', 'DESC'); ?>>Descending</option>
                                
                            </select>
                        </td>
                    </tr>
                    
                    <tr>
                    	<td>Pagination Style</td>
						<td>
                        
                        	<select name="wmlo_pagination_style" class="required medium">
                            	<option value=""> -- Select -- </option>
                                <option value="ajax_load_btn" <?php echo wml_fill_up_form($shortcodeDetails, 'wmlo_pagination_style', 'ajax_load_btn'); ?>>Ajax Load More Button</option>
                               	<option value="infinity_scroll" disabled="disabled" <?php echo wml_fill_up_form($shortcodeDetails, 'wmlo_pagination_style', 'infinity_scroll'); ?>>Infinity Scroll (Available in Pro Version Only)</option>
                            </select>
                        </td>
                    </tr>
                    
                    <tr>
                    	<td>Responsive</td>
						<td>
                        	<select name="wmlo_responsive" class="required medium">
                            	<option value=""> -- Select -- </option>
                                <option value="no" <?php echo wml_fill_up_form($shortcodeDetails, 'wmlo_responsive', 'no'); ?>>No</option>
                                <option  disabled="disabled" value="yes" <?php echo wml_fill_up_form($shortcodeDetails, 'wmlo_responsive', 'yes'); ?>>Yes (Available in Pro Version Only)</option>
                            </select>
                        </td>
                    </tr>
                    
                     <tr>
                    	<td>Use lightbox</td>
						<td>
                        	<select name="wmlo_use_lightbox" class="required medium">
                            	<option value=""> -- Select -- </option>
                                <option value="no" <?php echo wml_fill_up_form($shortcodeDetails, 'wmlo_use_lightbox', 'no'); ?>>No</option>
                                <option  disabled="disabled" value="yes" <?php echo wml_fill_up_form($shortcodeDetails, 'wmlo_use_lightbox', 'yes'); ?>>Yes (Available in Pro Version Only)</option>
                            </select>
                        </td>
                    </tr>
                    
                    <tr>        
                        <td>&nbsp;</td>
                        <td><input type="submit" name="submit-wmp-shortcode" class="button-primary small" value="Save" /></td>
                    </tr> 
                </table>	
            </form>	
                

			<script>
                jQuery("#add_edit_shortcode_form").validate();
				function openHidePostCategory(){
					if (jQuery('#wmlo_post_type').val() == 'post'){
						jQuery('#post_category_holder').slideDown('slow');
					} else {
						jQuery('#post_category_holder').slideUp('slow');
					}
					
					if (jQuery('#wmlo_post_type').val() == 'page'){
						jQuery('#page_parent_holder').slideDown('slow');
					} else {
						jQuery('#page_parent_holder').slideUp('slow');
					}
					
					if (jQuery('#wmlo_post_type').val() == 'product'){
						jQuery('#product_category_holder').slideDown('slow');
					} else {
						jQuery('#product_category_holder').slideUp('slow');
					}
					
					
					product_category_holder
				}
				openHidePostCategory();
            </script>
        </td>
        </tr>
        </tbody>
        </table><br/>