<?php
/**
 * Simple Masonry Gallery
 * 
 * @package    Simple Masonry Gallery
 * @subpackage SimpleMasonryAdmin Management screen
    Copyright (c) 2014- Katsushi Kawamori (email : dodesyoswift312@gmail.com)
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

class SimpleMasonryAdmin {

	/* ==================================================
	 * Add a "Settings" link to the plugins page
	 * @since	1.0
	 */
	function settings_link( $links, $file ) {
		static $this_plugin;
		if ( empty($this_plugin) ) {
			$this_plugin = SIMPLEMASONRY_PLUGIN_BASE_FILE;
		}
		if ( $file == $this_plugin ) {
			$links[] = '<a href="'.admin_url('options-general.php?page=simplemasonry').'">'.__( 'Settings').'</a>';
		}
			return $links;
	}

	/* ==================================================
	 * Settings page
	 * @since	1.0
	 */
	function plugin_menu() {
		add_options_page( 'Simple Masonry Gallery Options', 'Simple Masonry Gallery', 'manage_options', 'simplemasonry', array($this, 'plugin_options') );
	}

	/* ==================================================
	 * Add Css and Script
	 * @since	2.0
	 */
	function load_custom_wp_admin_style() {
		wp_enqueue_style( 'jquery-responsiveTabs', SIMPLEMASONRY_PLUGIN_URL.'/css/responsive-tabs.css' );
		wp_enqueue_style( 'jquery-responsiveTabs-style', SIMPLEMASONRY_PLUGIN_URL.'/css/style.css' );
		wp_enqueue_style( 'simple-masonry-gallery',  SIMPLEMASONRY_PLUGIN_URL.'/css/simple-masonry-gallery.css' );
		wp_enqueue_script('jquery');
		wp_enqueue_script( 'jquery-responsiveTabs', SIMPLEMASONRY_PLUGIN_URL.'/js/jquery.responsiveTabs.min.js' );
	}

	/* ==================================================
	 * Add Css and Script on footer
	 * @since	2.0
	 */
	function load_custom_wp_admin_style2() {
		echo $this->add_jscss();
	}

	/* ==================================================
	 * Settings page
	 * @since	1.0
	 */
	function plugin_options() {

		if ( !current_user_can( 'manage_options' ) )  {
			wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
		}

		if( !empty($_POST) ) { 
			if ( !empty($_POST['ShowToPage']) ) {
				$this->options_updated();
				echo '<div class="updated"><ul><li>'.__('Settings saved.').'</li></ul></div>';
			}
			if ( !empty($_POST['UpdateSimpleMasonryApply']) ) {
				$this->post_meta_updated();
				echo '<div class="updated"><ul><li>'.__('Apply Masonry to the selected content.', 'simplemasonry').'</li></ul></div>';
			}
		}
		$scriptname = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH).'?page=simplemasonry';

		$simplemasonry_mgsettings = get_option('simplemasonry_mgsettings');
		$pagemax =$simplemasonry_mgsettings['pagemax'];

		?>

		<div class="wrap">
		<h2>Simple Masonry Gallery</h2>

	<div id="simplemasonry-admin-tabs">
	  <ul>
	    <li><a href="#simplemasonry-admin-tabs-1"><?php _e('Settings'); ?></a></li>
		<li><a href="#simplemasonry-admin-tabs-2"><?php _e('Caution:'); ?></a></li>
		<li><a href="#simplemasonry-admin-tabs-3"><?php _e('Donate to this plugin &#187;'); ?></a></li>
	<!--
		<li><a href="#simplemasonry-admin-tabs-4">FAQ</a></li>
	 -->
	  </ul>

	  <div id="simplemasonry-admin-tabs-1">
		<div class="wrap">
		<h2><?php _e('Settings'); ?></h2>
			<form method="post" action="<?php echo $scriptname; ?>">

			<div class="submit">
			  <input type="submit" name="UpdateSimpleMasonryApply" class="button-primary button-large" value="<?php _e('Save Changes') ?>" />
			</div>

			<div style="float:left;"><?php _e('Number of items per page:'); ?><input type="text" name="simplemasonry_mgsettings_pagemax" value="<?php echo $pagemax; ?>" size="3" /></div>
			<input type="submit" class="button" name="ShowToPage" value="<?php _e('Save') ?>" />
			<div style="clear:both"></div>

			<?php
			$args = array(
				'post_type' => 'any',
				'numberposts' => -1,
				'orderby' => 'date',
				'order' => 'DESC'
				); 

			$postpages = get_posts($args);

			$pageallcount = 0;
			// pagenation
			foreach ( $postpages as $postpage ) {
				++$pageallcount;
			}
			if (!empty($_GET['p'])){
				$page = $_GET['p'];
			} else {
				$page = 1;
			}
			$count = 0;
			$pagebegin = (($page - 1) * $pagemax) + 1;
			$pageend = $page * $pagemax;
			$pagelast = ceil($pageallcount / $pagemax);

			if ( $pagelast > 1 ) {
				$this->pagenation($page, $pagebegin, $pageend, $pagelast, $scriptname);
			}
			?>
			<div style="border-bottom: 1px solid; padding-top: 5px; padding-bottom: 5px;">
				<div>
				<?php
				_e('Apply'); ?> -
				<?php
				_e('Title'); ?> -
				<?php
				_e('Type'); ?> -
				<?php
				_e('Date/Time'); ?> -
				<?php
				echo __('Columns').__('Width').'(px)'; ?>
				</div>
			</div>

			<?php

			if ($postpages) {
				foreach ( $postpages as $postpage ) {
					++$count;
				    $apply = get_post_meta( $postpage->ID, 'simplemasonry_apply', true );
				    $width = get_post_meta( $postpage->ID, 'simplemasonry_width', true );
					if ( $pagebegin <= $count && $count <= $pageend ) {
						$title = $postpage->post_title;
						$link = $postpage->guid;
						$posttype = $postpage->post_type;
						$date = $postpage->post_date;
					?>

					<div style="border-bottom: 1px solid; padding-top: 5px; padding-bottom: 5px;">
						<div style="float: left;">
					    <input type="hidden" class="group_simplemasonry" name="simplemasonry_applys[<?php echo $postpage->ID; ?>]" value="false">
					    <input type="checkbox" class="group_simplemasonry" name="simplemasonry_applys[<?php echo $postpage->ID; ?>]" value="true" <?php if ( $apply == true ) { echo 'checked'; }?>>
						<a style="color: #4682b4;" title="<?php _e('View');?>" href="<?php echo $link; ?>" target="_blank"><?php echo $title; ?></a>
						<span style="margin-right: 1em;"></span>
						<?php echo $posttype; ?>
						<span style="margin-right: 1em;"></span>
						<?php echo $date; ?>
						<input type="text" name="simplemasonry_widths[<?php echo $postpage->ID; ?>]" value="<?php echo $width; ?>" size="4">
						</div>
						<div style="clear:both"></div>
					</div>
					<?php
					} else {
					?>
					    <input type="hidden" name="simplemasonry_applys[<?php echo $postpage->ID; ?>]" value="<?php echo $apply; ?>">
					<?php
					}
				}
			}
			if ( $pagelast > 1 ) {
				$this->pagenation($page, $pagebegin, $pageend, $pagelast, $scriptname);
			}
			?>

			<div class="submit">
			  <input type="submit" name="UpdateSimpleMasonryApply" class="button-primary button-large" value="<?php _e('Save Changes') ?>" />
			</div>

			</form>
		</div>
	  </div>

	  <div id="simplemasonry-admin-tabs-2">
		<div class="wrap">
			<h2><?php _e('Caution:'); ?></h2>
			<li><h3><?php _e('Meta-box of Simple Masonry Gallery will be added to [Edit Post] and [Edit Page]. Please apply it. Also, please enter the column width.', 'simplemasonry'); ?></h3></li>
			<img src = "<?php echo SIMPLEMASONRY_PLUGIN_URL.'/png/apply-width.png'; ?>">
		</div>
	  </div>

		<div id="simplemasonry-admin-tabs-3">
		<div class="wrap">
			<?php
			$plugin_datas = get_file_data( SIMPLEMASONRY_PLUGIN_BASE_DIR.'/simplemasonry.php', array('version' => 'Version') );
			$plugin_version = __('Version:').' '.$plugin_datas['version'];
			?>
			<h4 style="margin: 5px; padding: 5px;">
			<?php echo $plugin_version; ?> |
			<a style="text-decoration: none;" href="https://wordpress.org/support/plugin/simple-masonry-gallery" target="_blank"><?php _e('Support Forums') ?></a> |
			<a style="text-decoration: none;" href="https://wordpress.org/support/view/plugin-reviews/simple-masonry-gallery" target="_blank"><?php _e('Reviews', 'mediafromftp') ?></a>
			</h4>
			<div style="width: 250px; height: 170px; margin: 5px; padding: 5px; border: #CCC 2px solid;">
			<h3><?php _e('Please make a donation if you like my work or would like to further the development of this plugin.', 'simplemasonry'); ?></h3>
			<div style="text-align: right; margin: 5px; padding: 5px;"><span style="padding: 3px; color: #ffffff; background-color: #008000">Plugin Author</span> <span style="font-weight: bold;">Katsushi Kawamori</span></div>
	<a style="margin: 5px; padding: 5px;" href='https://pledgie.com/campaigns/28307' target="_blank"><img alt='Click here to lend your support to: Various Plugins for WordPress and make a donation at pledgie.com !' src='https://pledgie.com/campaigns/28307.png?skin_name=chrome' border='0' ></a>
			</div>
		</div>
		</div>

	<!--
	  <div id="simplemasonry-admin-tabs-4">
		<div class="wrap">
		<h2>FAQ</h2>

		</div>
	  </div>
	-->

	</div>

		</div>
		<?php
	}

	/* ==================================================
	 * Pagenation
	 * @since	1.0
	 * string	$page
	 * string	$pagebegin
	 * string	$pageend
	 * string	$pagelast
	 * string	$scriptname
	 * return	$html
	 */
	function pagenation($page, $pagebegin, $pageend, $pagelast, $scriptname){

		$pageprev = $page - 1;
		$pagenext = $page + 1;
		$scriptnamefirst = add_query_arg( array('p' => '1'), $scriptname);
		$scriptnameprev = add_query_arg( array('p' => $pageprev), $scriptname);
		$scriptnamenext = add_query_arg( array('p' => $pagenext), $scriptname);
		$scriptnamelast = add_query_arg( array('p' => $pagelast), $scriptname);
		?>
		<div class="simple-masonry-gallery-pages">
		<span class="simple-masonry-gallery-links">
		<?php
		if ( $page <> 1 ){
			?><a title='<?php _e('Go to the first page'); ?>' href='<?php echo $scriptnamefirst; ?>'>&laquo;</a>
			<a title='<?php _e('Go to the previous page'); ?>' href='<?php echo $scriptnameprev; ?>'>&lsaquo;</a>
		<?php
		}
		echo $page; ?> / <?php echo $pagelast;
		?>
		<?php
		if ( $page <> $pagelast ){
			?><a title='<?php _e('Go to the next page'); ?>' href='<?php echo $scriptnamenext; ?>'>&rsaquo;</a>
			<a title='<?php _e('Go to the last page'); ?>' href='<?php echo $scriptnamelast; ?>'>&raquo;</a>
		<?php
		}
		?>
		</span>
		</div>
		<?php

	}

	/* ==================================================
	 * Update wp_options table.
	 * @since	1.0
	 */
	function options_updated(){

		$mgsettings_tbl = array(
						'pagemax' => intval($_POST['simplemasonry_mgsettings_pagemax'])
						);
		update_option( 'simplemasonry_mgsettings', $mgsettings_tbl );

	}

	/* ==================================================
	 * Update wp_postmeta table for admin settings.
	 * @since	1.0
	 */
	function post_meta_updated() {

		$simplemasonry_applys = $_POST['simplemasonry_applys'];
		$simplemasonry_widths = $_POST['simplemasonry_widths'];

		foreach ( $simplemasonry_applys as $key => $value ) {
			if ( $value === 'true' ) {
		    	update_post_meta( $key, 'simplemasonry_apply', $value );
				if( empty($simplemasonry_widths[$key]) ) { $simplemasonry_widths[$key] = 200; }
		    	update_post_meta( $key, 'simplemasonry_width', $simplemasonry_widths[$key] );
			} else {
				delete_post_meta( $key, 'simplemasonry_apply' );
				delete_post_meta( $key, 'simplemasonry_width' );
			}
		}

	}

	/* ==================================================
	 * Add custom box.
	 * @since	1.0
	 */
	function add_apply_simplemasonry_custom_box() {
	    add_meta_box('simplemasonry', 'Simple Masonry Gallery', array(&$this,'apply_simplemasonry_custom_box'), 'page', 'side', 'high');
	    add_meta_box('simplemasonry', 'Simple Masonry Gallery', array(&$this,'apply_simplemasonry_custom_box'), 'post', 'side', 'high');

		$args = array(
		   'public'   => true,
		   '_builtin' => false
		);
		$custom_post_types = get_post_types( $args, 'objects', 'and' ); 
		foreach ( $custom_post_types as $post_type ) {
		    add_meta_box('simplemasonry', 'Simple Masonry Gallery', array(&$this,'apply_simplemasonry_custom_box'), $post_type->name, 'side', 'high');
		}

	}
	 
	/* ==================================================
	 * Custom box.
	 * @since	1.0
	 */
	function apply_simplemasonry_custom_box() {

		if ( isset($_GET['post']) ) {
			$get_post = $_GET['post'];
		} else {
			$get_post = NULL;
		}

		$simplemasonry_apply = get_post_meta( $get_post, 'simplemasonry_apply' );
		$simplemasonry_width = get_post_meta( $get_post, 'simplemasonry_width' );

		?>
		<table>
		<tbody>
			<tr>
				<td>
					<div>
						<?php
						if (!empty($simplemasonry_apply)) {
						?>
							<input type="radio" name="simplemasonry_apply" value="true" <?php if ($simplemasonry_apply[0] === 'true') { echo 'checked'; }?>><?php _e('Apply'); ?>&nbsp;&nbsp;
							<input type="radio" name="simplemasonry_apply" value="false" <?php if ($simplemasonry_apply[0] <> 'true') { echo 'checked'; }?>><?php _e('None');
						} else {
						?>
							<input type="radio" name="simplemasonry_apply" value="true"><?php _e('Apply'); ?>&nbsp;&nbsp;
							<input type="radio" name="simplemasonry_apply" value="false" checked><?php _e('None');
						}
						?>
					</div>
				</td>
			</tr>
			<tr>
				<td>
					<div>
						<?php
						if (!empty($simplemasonry_width)) {
							echo __('Columns').__('Width'); ?><input type="text" name="simplemasonry_width" value="<?php echo $simplemasonry_width[0]; ?>" size="4">px<?php
						} else {
							echo __('Columns').__('Width'); ?><input type="text" name="simplemasonry_width" value="" size="4">px<?php
						}
						?>
					</div>
				</td>
			</tr>
		</tbody>
		</table>
		<?php

	}

	/* ==================================================
	 * Update wp_postmeta table.
	 * @since	1.0
	 */
	function save_apply_simplemasonry_postdata( $post_id ) {

		if ( isset($_POST['simplemasonry_apply']) ) {
			$dataapply = $_POST['simplemasonry_apply'];
			if ( $dataapply === 'true' ) {
				add_post_meta( $post_id, 'simplemasonry_apply', $dataapply, true );
				$datawidth = $_POST['simplemasonry_width'];
				if ( empty($datawidth) ) { $datawidth = 200; }
				if ( "" == get_post_meta( $post_id, 'simplemasonry_width') ) {
					add_post_meta( $post_id, 'simplemasonry_width', $datawidth, true );
				} else if ( $datawidth != get_post_meta( $post_id, 'simplemasonry_width' ) ) {
					update_post_meta( $post_id, 'simplemasonry_width', $datawidth );
				}
			} else if ( $dataapply === '' || $dataapply === 'false' ) {
				delete_post_meta( $post_id, 'simplemasonry_apply' );
				delete_post_meta( $post_id, 'simplemasonry_width' );
			}
		}

	}

	/* ==================================================
	 * Posts columns menu
	 * @since	1.0
	 */
	function posts_columns_simplemasonry($columns){
	    $columns['column_simplemasonry'] = __('Simple Masonry Gallery');
	    return $columns;
	}

	/* ==================================================
	 * Posts columns
	 * @since	1.0
	 */
	function posts_custom_columns_simplemasonry($column_name, $post_id){
		if($column_name === 'column_simplemasonry'){
			$simplemasonry_apply = get_post_meta( $post_id, 'simplemasonry_apply' );
			$simplemasonry_width = get_post_meta( $post_id, 'simplemasonry_width' );
			if ( !empty($simplemasonry_apply) ) {
				if ($simplemasonry_apply[0] === 'true'){
					?>
					<div><?php _e('Apply'); ?></div>
					<div><?php echo __('Columns').__('Width').'&nbsp;&nbsp;'.$simplemasonry_width[0].'px'; ?></div>
					<?php
				} else {
					_e('None');
				}
			} else {
				_e('None');
			}
	    }
	}

	/* ==================================================
	 * Pages columns menu
	 * @since	1.0
	 */
	function pages_columns_simplemasonry($columns){
	    $columns['column_simplemasonry'] = __('Simple Masonry Gallery');
	    return $columns;
	}

	/* ==================================================
	 * Pages columns
	 * @since	1.0
	 */
	function pages_custom_columns_simplemasonry($column_name, $post_id){
		if($column_name === 'column_simplemasonry'){
			$simplemasonry_apply = get_post_meta( $post_id, 'simplemasonry_apply' );
			$simplemasonry_width = get_post_meta( $post_id, 'simplemasonry_width' );
			if ( !empty($simplemasonry_apply) ) {
				if ($simplemasonry_apply[0] === 'true'){
					?>
					<div><?php _e('Apply'); ?></div>
					<div><?php echo __('Columns').__('Width').'&nbsp;&nbsp;'.$simplemasonry_width[0].'px'; ?></div>
					<?php
				} else {
					_e('None');
				}
			} else {
				_e('None');
			}
	    }
	}

	/* ==================================================
	 * Add js css
	 * @since	2.0
	 */
	function add_jscss(){

// JS
$simplemasonry_add_jscss = <<<SIMPLEMASONRYGALLERY

<!-- BEGIN: Simple Masonry Gallery -->
<script type="text/javascript">
jQuery('#simplemasonry-admin-tabs').responsiveTabs({
  startCollapsed: 'accordion'
});
</script>
<script type="text/javascript">
	jQuery(function(){
		jQuery('.simplemasonry-admin-checkAll').on('change', function() {
			jQuery('.' + this.id).prop('checked', this.checked);
		});
	});
</script>
<!-- END: Simple Masonry Gallery -->

SIMPLEMASONRYGALLERY;

		return $simplemasonry_add_jscss;

	}

}

?>