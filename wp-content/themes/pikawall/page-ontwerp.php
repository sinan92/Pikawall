<?php
/**
 * Template Name: Ontwerp
 */
?>
 <?php get_header(); ?>
<div id="main">
<div class="wrap">
<div id="content">
	<div class="row">
		<div id="slider">
			<?php echo do_shortcode("[metaslider id=5]"); ?>
		</div>
	</div>
	<div class="row">
		<div class="text-row">
			<h1>Ontdenkt en personaliseert</h1>
			<h2>Displaying deals & promotions</h2>
		</div>
	</div>
	<div class="row" id="tiles">
		<div class="tile">
			<a href="#">
				<img src="<?php bloginfo('stylesheet_directory'); ?>/img/tile1.png" />
				<div class="tile-layer">
					<span>PICAWALL</span>
					<span class="bold">EST SITT IPSUM</span>
				</div>
			</a>
			<p>
				Sed ut perspiciatis unde omnisito ist <br />
				Te natus error sit
			</p>
		</div>
		<div class="tile">
			<a href="#">
				<img src="<?php bloginfo('stylesheet_directory'); ?>/img/tile2.png" />
				<div class="tile-layer">
					<span>PICAWALL</span>
					<span class="bold">EST SITT IPSUM</span>
				</div>
			</a>
			<p>
				Sed ut perspiciatis unde omnisito ist <br />
				Te natus error sit
			</p>
		</div>
		<div class="tile">
			<a href="#">
				<img src="<?php bloginfo('stylesheet_directory'); ?>/img/tile3.png" />
				<div class="tile-layer">
					<span>PICAWALL</span>
					<span class="bold">EST SITT IPSUM</span>
				</div>
			</a>
			<p>
				Sed ut perspiciatis unde omnisito ist <br />
				Te natus error sit
			</p>
		</div>
	</div>
	<div class="row">
		<div class="text-row">
			<h1>Ontdenkt uit onze galerij</h1>
			<h2>Sed ut perspiciatis unde omnisito ist Te natus error sit</h2>
		</div>
	</div>
	<div class="row">
		<?php 
			echo do_shortcode('[wmls name="Gallery" id="1"]');
		?>
	</div>
</div>
<?php get_sidebar(); ?>
<div id="delimiter">
</div>
<?php get_footer(); ?>