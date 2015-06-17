<html>
<head>
<title>Pikawall</title>
<?php wp_head(); ?>
<link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>">
<link rel="stylesheet" href="<?php bloginfo('stylesheet_directory'); ?>/css/font-awesome.min.css">
<link href='http://fonts.googleapis.com/css?family=Source+Sans+Pro:400,600,700' rel='stylesheet' type='text/css'>
</head>
<body>
		<div id="header">
			<div id="nav-bg">
				<div id="nav">
					<div class="wrap">
						<ul id="contact">
							<li><i class="fa fa-phone"></i> Call: 03134567890</li>
							<li><i class="fa fa-envelope"></i> Mail: info@picawall.com</li>
						</ul>
						<ul id="cp">
							<li><a href="#">Inloggen <i class="fa fa-chevron-down"></i></a></li>
						</ul>
					</div>
				</div>
			</div>
			<div class="wrap">
				<div id="header-bottom">
					<div class="logo">
						<img src="<?php bloginfo('stylesheet_directory'); ?>/img/logo.png" alt="Pikawall Logo"/>
					</div>
					<a href="#">
						<div id="afspraak">
							<i class="fa fa-calendar"></i> Maak een afspraak
						</div>
					</a>
				</div>
				<div id="menu-bottom">
					<?php wp_nav_menu( array( 'pikawall' => 'Header Menu' ) ); ?>
				</div>
			</div>
		</div>