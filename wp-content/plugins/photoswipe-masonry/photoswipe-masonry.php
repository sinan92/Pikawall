<?php
/*
Plugin Name: Photoswipe Masonry
Plugin URI: http://thriveweb.com.au/the-lab/photoswipe/
Description: This is a image gallery plugin for WordPress built using PhotoSwipe from  Dmitry Semenov.  
<a href="http://photoswipe.com/">PhotoSwipe</a>
Author: Dean Oakley
Author URI: http://thriveweb.com.au/
Version: 1.0.4
*/

/*  Copyright 2010  Dean Oakley  (email : dean@thriveweb.com.au)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.
 
    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

if(preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF'])) { 
	die('Illegal Entry');  
}

//============================== PhotoSwipe options ========================//
class photoswipe_plugin_options {

	//Defaults
	public static function pSwipe_getOptions() {
		
		//Pull from WP options database table
		$options = get_option('photoswipe_options');
		
		if (!is_array($options)) {
						
			$options['show_controls'] = false;
			
			$options['thumbnail_width'] = 150;
			$options['thumbnail_height'] = 150;

			$options['max_image_height'] = '2400';
			$options['max_image_width'] = '1800';				
			
			update_option('photoswipe_options', $options);
		}
		return $options;
	}
	
	
	public static function update() {
		
		if(isset($_POST['photoswipe_save'])) {
			
			$options = photoswipe_plugin_options::pSwipe_getOptions();

			$options['thumbnail_width'] = stripslashes($_POST['thumbnail_width']);
			$options['thumbnail_height'] = stripslashes($_POST['thumbnail_height']);			
			
			$options['max_image_width'] = stripslashes($_POST['max_image_width']);
			$options['max_image_height'] = stripslashes($_POST['max_image_height']);
			
			
			if (isset($_POST['show_controls'])) {
				$options['show_controls'] = (bool)true;
			} else {
				$options['show_controls'] = (bool)false;
			} 
			
			update_option('photoswipe_options', $options);

		} else {
			photoswipe_plugin_options::pSwipe_getOptions();
		}

		add_submenu_page( 'options-general.php', 'PhotoSwipe options', 'PhotoSwipe', 'edit_theme_options', basename(__FILE__), array('photoswipe_plugin_options', 'display'));
	}
	

	public static function display() {
		
		$options = photoswipe_plugin_options::pSwipe_getOptions();
		?>
		
		<div id="photoswipe_admin" class="wrap">
		
			<h2>PhotoSwipe Options</h2>
			
			<p>PhotoSwipe is a image gallery plugin for WordPress built using PhotoSwipe from  Dmitry Semenov.  <a href="http://photoswipe.com/">PhotoSwipe</a></p>
							
			<form method="post" action="#" enctype="multipart/form-data">						
				
				<div class="ps_border" ></div>
				
				<p style="font-style:italic; font-weight:normal; color:grey " >Please note: Images that are already on the server will not change size until you regenerate the thumbnails. Use <a title="http://wordpress.org/extend/plugins/ajax-thumbnail-rebuild/" href="http://wordpress.org/extend/plugins/ajax-thumbnail-rebuild/">AJAX thumbnail rebuild</a> </p>
				
				<div class="fl_box">				
					<p>Thumbnail Width</p>
					<p><input type="text" name="thumbnail_width" value="<?php echo($options['thumbnail_width']); ?>" /></p>
				</div>
				
				<div class="fl_box">				
					<p>Thumbnail Height</p>
					<p><input type="text" name="thumbnail_height" value="<?php echo($options['thumbnail_height']); ?>" /></p>
				</div>
				
				<div class="fl_box">
					<p>Max image width</p>
					<p><input type="text" name="max_image_width" value="<?php echo($options['max_image_width']); ?>" /></p>
				</div>
				
				<div class="fl_box">
					<p>Max image height</p>
					<p><input type="text" name="max_image_height" value="<?php echo($options['max_image_height']); ?>" /></p>
				</div>		

				<div class="ps_border" ></div>				
			
				<p><input class="button-primary" type="submit" name="photoswipe_save" value="Save Changes" /></p>
			
			</form>
	
		</div>
		
		<?php
	}  
} 


function pSwipe_getOption($option) {
	global $mytheme;
	return $mytheme->option[$option];
}

// register functions
add_action('admin_menu', array('photoswipe_plugin_options', 'update'));

$options = get_option('photoswipe_options');

//image sizes - No cropping for a nice zoom effect
add_image_size('photoswipe_thumbnails', $options['thumbnail_width'] * 2, $options['thumbnail_height'] * 2, false);
add_image_size('photoswipe_full', $options['max_image_width'], $options['max_image_height'], false);

//Admin CSS
function photoswipe_register_head() {
    
    $url = plugins_url( 'admin.css', __FILE__ );
    
    echo "<link rel='stylesheet' type='text/css' href='$url' />\n";
}
add_action('admin_head', 'photoswipe_register_head');

//============================== insert HTML header tag ========================//

function photoswipe_scripts_method() {
	
	$photoswipe_wp_plugin_path =  plugins_url() . '/photoswipe-masonry' ;
	
	wp_enqueue_style( 'photoswipe-core-css',	$photoswipe_wp_plugin_path . '/photoswipe-dist/photoswipe.css');
	
	
	// Skin CSS file (optional)
    // In folder of skin CSS file there are also:
    // - .png and .svg icons sprite, 
    // - preloader.gif (for browsers that do not support CSS animations)
	wp_enqueue_style( 'photoswipe-default-skin',	$photoswipe_wp_plugin_path . '/photoswipe-dist/default-skin/default-skin.css');
	
	//Core JS file
	wp_enqueue_script( 'photoswipe', 		$photoswipe_wp_plugin_path . '/photoswipe-dist/photoswipe.min.js');
	
	//UI JS file 
	wp_enqueue_script( 'photoswipe-ui-default', 		$photoswipe_wp_plugin_path . '/photoswipe-dist/photoswipe-ui-default.min.js');
	
	//Masonry - re-named to move to header
	wp_enqueue_script( 'photoswipe-masonry', 		$photoswipe_wp_plugin_path . '/masonry.pkgd.min.js','','',false);
	//imagesloaded
	wp_enqueue_script( 'imagesloaded', 		$photoswipe_wp_plugin_path . '/imagesloaded.pkgd.min.js','','',false);
	
}
add_action('wp_enqueue_scripts', 'photoswipe_scripts_method');

add_shortcode( 'gallery', 'photoswipe_shortcode' );
add_shortcode( 'photoswipe', 'photoswipe_shortcode' );


function photoswipe_shortcode( $attr ) {
	
	global $post;
	global $photoswipe_count;
	
	$options = get_option('photoswipe_options');
	
	static $instance = 0;
	$instance++;
	
	if ( ! empty( $attr['ids'] ) ) {
		// 'ids' is explicitly ordered, unless you specify otherwise.
		if ( empty( $attr['orderby'] ) ) {
			$attr['orderby'] = 'post__in';
		}
		$attr['include'] = $attr['ids'];
	}
	
	$args = shortcode_atts(array(
		'id' 				=> intval($post->ID),
		'show_controls' 	=> $options['show_controls'],
		'columns'    => 3,
		'size'       => 'thumbnail',
		'order'      => 'DESC',
		'orderby'    => 'menu_order ID',
		'include'    => '',
		'exclude'    => ''				
	), $attr);
	
	$photoswipe_count += 1;	
	$post_id = intval($post->ID) . '_' . $photoswipe_count;
	
	
	$output_buffer='';	
	    
	    if ( !empty($args['include']) ) { 
			
			//"ids" == "inc"
			
			$include = preg_replace( '/[^0-9,]+/', '', $args['include'] );
			$_attachments = get_posts( array('include' => $args['include'], 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $args['order'], 'orderby' => $args['orderby']) );
	
			$attachments = array();
			foreach ( $_attachments as $key => $val ) {
				$attachments[$val->ID] = $_attachments[$key];
			}
			
		} elseif ( !empty($args['exclude']) ) {
			$exclude = preg_replace( '/[^0-9,]+/', '', $args['exclude'] );
			$attachments = get_children( array('post_parent' => $args['id'], 'exclude' => $args['exclude'], 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $args['order'], 'orderby' => $args['orderby']) );
		} else {
			
			$attachments = get_children( array('post_parent' => $args['id'], 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $args['order'], 'orderby' => $args['orderby']) );
		
		}
		
		$columns = intval($args['columns']);
        $itemwidth = $columns > 0 ? floor(100/$columns) : 100;
		
		
		if( $photoswipe_count < 2){
			
			$output_buffer .= "
			
			<style type='text/css'>
			
				/* PhotoSwipe Plugin */
				.photoswipe_gallery {
					margin: auto;
					padding-bottom:40px;
					
					-webkit-transition: all 0.4s ease;
					-moz-transition: all 0.4s ease;
					-o-transition: all 0.4s ease;
					transition: all 0.4s ease;
	
	
					opacity:0.1;
				}
				
				.photoswipe_gallery.photoswipe_showme{
					opacity:1;
				}
				
				.photoswipe_gallery figure {
					float: left;
					
					text-align: center;
					width: ".$options['thumbnail_width']."px;
					
					padding:5px;
					margin: 0px;
					box-sizing:border-box;
				}
				.photoswipe_gallery img {
					margin:auto;
					max-width:100%;
					width: auto;					
					height: auto;
					border: 0;
				}
				.photoswipe_gallery figure figcaption{
					font-size:13px;
				}			
				
				.msnry{
					margin:auto;	
				}
			</style>";

		}
	
	
		$size_class = sanitize_html_class( $args['size'] );
		$output_buffer .=' <div style="clear:both"></div>	
		
		<div id="photoswipe_gallery_'.$post_id.'" class="photoswipe_gallery gallery-columns-'.$columns.' gallery-size-'.$size_class.'" itemscope itemtype="http://schema.org/ImageGallery" >';
				
				
		if ( !empty($attachments) ) {
			foreach ( $attachments as $aid => $attachment ) {
				
				$thumb = wp_get_attachment_image_src( $aid , 'photoswipe_thumbnails');
				
				$full = wp_get_attachment_image_src( $aid , 'photoswipe_full');
				
				$_post = get_post($aid); 

				$image_title = esc_attr($_post->post_title);
				$image_alttext = get_post_meta($aid, '_wp_attachment_image_alt', true);
				$image_caption = $_post->post_excerpt;
				$image_description = $_post->post_content;	
				
				$output_buffer .='
				<figure class="msnry_item" itemscope itemtype="http://schema.org/ImageObject">
					<a href="'. $full[0] .'" itemprop="contentUrl" data-size="'.$full[1].'x'.$full[2].'">
				        <img src='. $thumb[0] .' itemprop="thumbnail" alt="'.$image_description.'"  />
				    </a>
				    <figcaption class="photoswipe-gallery-caption" >'. $image_caption .'</figcaption>
			    </figure>
				';
						
			} 
		} 					
			
		
		
		$output_buffer .="</div>
		
		<div style='clear:both'></div>	
		
		<script type='text/javascript'>
		
			var container_".$post_id." = document.querySelector('#photoswipe_gallery_".$post_id."');
			var msnry;
			
			// initialize Masonry after all images have loaded
			imagesLoaded( container_".$post_id.", function() {
				
				// initialize Masonry after all images have loaded
				new Masonry( container_".$post_id.", {
				  // options...
				  itemSelector: '.msnry_item',
				  columnWidth: ".$options['thumbnail_width'].",
				  isFitWidth: true
				});
				
				(container_".$post_id.").classList.add('photoswipe_showme');
			});
		
			// PhotoSwipe
			var initPhotoSwipeFromDOM = function(gallerySelector) {
		
		    // parse slide data (url, title, size ...) from DOM elements 
		    // (children of gallerySelector)
		    var parseThumbnailElements = function(el) {
		        var thumbElements = el.childNodes,
		            numNodes = thumbElements.length,
		            items = [],
		            figureEl,
		            linkEl,
		            size,
		            item;
		
		        for(var i = 0; i < numNodes; i++) {
		
		            figureEl = thumbElements[i]; // <figure> element
		
		            // include only element nodes 
		            if(figureEl.nodeType !== 1) {
		                continue;
		            }
		
		            linkEl = figureEl.children[0]; // <a> element
		
		            size = linkEl.getAttribute('data-size').split('x');
		
		            // create slide object
		            item = {
		                src: linkEl.getAttribute('href'),
		                w: parseInt(size[0], 10),
		                h: parseInt(size[1], 10)
		            };
		
		
		
		            if(figureEl.children.length > 1) {
		                // <figcaption> content
		                item.title = figureEl.children[1].innerHTML; 
		            }
		
		            if(linkEl.children.length > 0) {
		                // <img> thumbnail element, retrieving thumbnail url
		                item.msrc = linkEl.children[0].getAttribute('src');
		            } 
		
		            item.el = figureEl; // save link to element for getThumbBoundsFn
		            items.push(item);
		        }
		
		        return items;
		    };
		
		    // find nearest parent element
		    var closest = function closest(el, fn) {
		        return el && ( fn(el) ? el : closest(el.parentNode, fn) );
		    };
		
		    // triggers when user clicks on thumbnail
		    var onThumbnailsClick = function(e) {
		        e = e || window.event;
		        e.preventDefault ? e.preventDefault() : e.returnValue = false;
		
		        var eTarget = e.target || e.srcElement;
		
		        // find root element of slide
		        var clickedListItem = closest(eTarget, function(el) {
		            return el.tagName === 'FIGURE';
		        });
		
		        if(!clickedListItem) {
		            return;
		        }
		
		        // find index of clicked item by looping through all child nodes
		        // alternatively, you may define index via data- attribute
		        var clickedGallery = clickedListItem.parentNode,
		            childNodes = clickedListItem.parentNode.childNodes,
		            numChildNodes = childNodes.length,
		            nodeIndex = 0,
		            index;
		
		        for (var i = 0; i < numChildNodes; i++) {
		            if(childNodes[i].nodeType !== 1) { 
		                continue; 
		            }
		
		            if(childNodes[i] === clickedListItem) {
		                index = nodeIndex;
		                break;
		            }
		            nodeIndex++;
		        }
		
		
		
		        if(index >= 0) {
		            // open PhotoSwipe if valid index found
		            openPhotoSwipe( index, clickedGallery );
		        }
		        return false;
		    };
		
		    // parse picture index and gallery index from URL (#&pid=1&gid=2)
		    var photoswipeParseHash = function() {
		        var hash = window.location.hash.substring(1),
		        params = {};
		
		        if(hash.length < 5) {
		            return params;
		        }
		
		        var vars = hash.split('&');
		        for (var i = 0; i < vars.length; i++) {
		            if(!vars[i]) {
		                continue;
		            }
		            var pair = vars[i].split('=');  
		            if(pair.length < 2) {
		                continue;
		            }           
		            params[pair[0]] = pair[1];
		        }
		
		        if(params.gid) {
		            params.gid = parseInt(params.gid, 10);
		        }
		
		        if(!params.hasOwnProperty('pid')) {
		            return params;
		        }
		        params.pid = parseInt(params.pid, 10);
		        return params;
		    };
		
		    var openPhotoSwipe = function(index, galleryElement, disableAnimation) {
		        var pswpElement = document.querySelectorAll('.pswp')[0],
		            gallery,
		            options,
		            items;
		
		        items = parseThumbnailElements(galleryElement);
		
		        // define options (if needed)
		        options = {
		            index: index,
		
		            // define gallery index (for URL)
		            galleryUID: galleryElement.getAttribute('data-pswp-uid'),
		
		            getThumbBoundsFn: function(index) {
		                // See Options -> getThumbBoundsFn section of documentation for more info
		                var thumbnail = items[index].el.getElementsByTagName('img')[0], // find thumbnail
		                    pageYScroll = window.pageYOffset || document.documentElement.scrollTop,
		                    rect = thumbnail.getBoundingClientRect(); 
		
		                return {x:rect.left, y:rect.top + pageYScroll, w:rect.width};
		            }
		
		        };
		
		        if(disableAnimation) {
		            options.showAnimationDuration = 0;
		        }
		
		        // Pass data to PhotoSwipe and initialize it
		        gallery = new PhotoSwipe( pswpElement, PhotoSwipeUI_Default, items, options);
		        gallery.init();
		    };
		
		    // loop through all gallery elements and bind events
		    var galleryElements = document.querySelectorAll( gallerySelector );
		
		    for(var i = 0, l = galleryElements.length; i < l; i++) {
		        galleryElements[i].setAttribute('data-pswp-uid', i+1);
		        galleryElements[i].onclick = onThumbnailsClick;
		    }
		
		    // Parse URL and open gallery if it contains #&pid=3&gid=1
		    var hashData = photoswipeParseHash();
		    if(hashData.pid > 0 && hashData.gid > 0) {
		        openPhotoSwipe( hashData.pid - 1 ,  galleryElements[ hashData.gid - 1 ], true );
		    }
		};
		
		// execute above function
		initPhotoSwipeFromDOM('.photoswipe_gallery');
	
	</script>
	
	";


	if( $photoswipe_count < 2){
	
		$output_buffer .= '
		
			
			<!-- Root element of PhotoSwipe. Must have class pswp. -->
			<div class="pswp" tabindex="-1" role="dialog" aria-hidden="true">
			
			    <!-- Background of PhotoSwipe. 
			         Its a separate element, as animating opacity is faster than rgba(). -->
			    <div class="pswp__bg"></div>
			
			    <!-- Slides wrapper with overflow:hidden. -->
			    <div class="pswp__scroll-wrap">
			
			        <!-- Container that holds slides. 
			                PhotoSwipe keeps only 3 slides in DOM to save memory. -->
			        <div class="pswp__container">
			            <!-- dont modify these 3 pswp__item elements, data is added later on -->
			            <div class="pswp__item"></div>
			            <div class="pswp__item"></div>
			            <div class="pswp__item"></div>
			        </div>
			
			        <!-- Default (PhotoSwipeUI_Default) interface on top of sliding area. Can be changed. -->
			        <div class="pswp__ui pswp__ui--hidden">
			
			            <div class="pswp__top-bar">
			
			                <!--  Controls are self-explanatory. Order can be changed. -->
			
			                <div class="pswp__counter"></div>
			
			                <button class="pswp__button pswp__button--close" title="Close (Esc)"></button>
			
			                <button class="pswp__button pswp__button--share" title="Share"></button>
			
			                <button class="pswp__button pswp__button--fs" title="Toggle fullscreen"></button>
			
			                <button class="pswp__button pswp__button--zoom" title="Zoom in/out"></button>
			
			                <!-- Preloader demo http://codepen.io/dimsemenov/pen/yyBWoR -->
			                <!-- element will get class pswp__preloader--active when preloader is running -->
			                <div class="pswp__preloader">
			                    <div class="pswp__preloader__icn">
			                      <div class="pswp__preloader__cut">
			                        <div class="pswp__preloader__donut"></div>
			                      </div>
			                    </div>
			                </div>
			            </div>
			
			            <div class="pswp__share-modal pswp__share-modal--hidden pswp__single-tap">
			                <div class="pswp__share-tooltip"></div> 
			            </div>
			
			            <button class="pswp__button pswp__button--arrow--left" title="Previous (arrow left)">
			            </button>
			
			            <button class="pswp__button pswp__button--arrow--right" title="Next (arrow right)">
			            </button>
			
			            <div class="pswp__caption">
			                <div class="pswp__caption__center"></div>
			            </div>
			
			          </div>
			
			        </div>
			
			</div>
			';
		
		}
		
		return $output_buffer;
}