<?php get_header(); ?>
<?php 
	//Haal page op
	$page = get_page( $page_id );
	$titel = $page->post_title;
	$content = $page->post_content;
?>
<div id="main">
<div class="wrap">
<div id="content">
	<div class="row">
		<div id="title-header">
			<h1><i class="fa fa-home"></i><?php echo strtolower($titel) ?></h1>
		</div>
	</div>
	<div class="row">
		<div class="page-content">
		<?php echo apply_filters('the_content',$content);?>
		</div>
	</div>
</div>
<?php get_sidebar(); ?>
<div id="delimiter">
</div>
<?php get_footer(); ?>