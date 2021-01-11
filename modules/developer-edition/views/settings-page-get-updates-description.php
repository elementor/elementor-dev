<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<p>
	<?php
	/* translators: %s: Plugin name. */
	echo sprintf( __( '%s is a testing tool for new features and should not be used on live sites. Use staging environments only, and backup all your data before updating.', 'elementor-beta' ), __( 'Elementor Developer Edition', 'elementor-beta' ) );
	?>
	<br/>
	<?php
	/* translators: %1$s: Learn more link, %2$s: Plugin name */
	echo sprintf(
		__( '%1$s about %2$s.', 'elementor-beta' ),
		sprintf( '<a href="https://go.elementor.com/wp-dash-developer-edition" target="_blank">%s</a>', __( 'Learn more', 'elementor-beta' ) ),
		__( 'Elementor Developer Edition', 'elementor-beta' )
	)
	?>
</p>

<br/>

<p>
	<?php
	echo sprintf(
		/* translators: %s: email updates link */
		__( '%s to join our first-to-know email updates.', 'elementor-beta' ),
		sprintf( '<a href="#" id="beta-tester-first-to-know">%s</a>', __( 'Click here', 'elementor-beta' ) )
	)
	?>
</p>
