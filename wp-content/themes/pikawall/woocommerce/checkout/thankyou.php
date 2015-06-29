<?php
/**
 * Thankyou page
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.2.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
} ?>
<div class="text-row" style="text-align: left;">
	<h3>Uw Bestelling</h3>

	<p>Hartelijk dank voor uw bestelling!</p>

	<p>Binnen een aantal minuten ontvangt u op het door u aangegeven emailadres een bevestiging.</p>
</div>

<div class="row">
	<a id="terug-knop" href="<?php echo $permalink = get_permalink( 46 ); ?>">Verder winkelen</a>
</div>