		<div id="footer">
			<div class="row">
				<div id="nieuwsbrief">
					<form action="" method="post">
						<span style='width:10%'><i class="fa fa-envelope"></i></span>
						<span style='width:30%'>Nieuwsbrief</span>
						<span style='width:40%'><input type="text" name="email" id="email" /></span>
						<span style='width:20%'><input type="submit" name="submit" id="submit" value="Inschrijven" /></span>
					</form>
				</div>
				<div id="social">
					<span style="margin-right: 40px;">Volg ons</span>
					<span class="social-button"><a href="#"><i style="color: #5b88b3;" class="fa fa-facebook"></i></a></span>
					<span class="social-button"><a href="#"><i style="color: #3d9db2;" class="fa fa-twitter"></i></a></span>
					<span class="social-button"><a href="#"><i style="color: #b84141;" class="fa fa-google"></i></a></span>
					<span class="social-button"><a href="#"><i style="color: #42709d;" class="fa fa-linkedin"></i></a></span>
				</div>
			</div>
			<div class="row">
				<div id="footer-nav">
					<div class="footer-item">
						<h4>Overzicht</h4>
						<?php wp_nav_menu( array( 'pikawall' => 'Header Menu' ) ); ?>
					</div>
					<div class="footer-item">
						<h4>Mijn account</h4>
						<ul>
							<li><a href="#">Mijn account</a></li>
							<li><a href="#">Mijn winkelwagen</a></li>
							<li><a href="#">Mijn wenslijst</a></li>
							<li><a href="#">Mijn afspraken</a></li>
						</ul>
					</div>
					<div class="footer-item">
						<h4>Kontakteer ons</h4>
						<div class="contact-block">
							<span><i style="color:#f75b5b" class="fa fa-map-marker"></i></span>
							<span>Megnor Com Pvt Ltd, 507-Union Trade Center, Udhana Darwaja</span>
						</div>
						<div class="contact-block">
							<span><i style="color:#f75b5b" class="fa fa-envelope"></i></span>
							<span><a href="mailto:info@oumniwall.nl">info@oumniwall.nl</a></span>
						</div>
					</div>
					<div class="footer-item">
						<h4>Betaal methode</h4>
						<img src="<?php bloginfo('stylesheet_directory'); ?>/img/ideal.png" />
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<?php wp_footer(); ?>
</body>
</html>