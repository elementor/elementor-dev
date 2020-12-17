document.addEventListener( 'DOMContentLoaded', () => {
	document.body.classList.add( 'elementor-beta' );

	if ( elementorBetaUiTheme ) {
		document.body.classList.add( `elementor-beta-theme-${ elementorBetaUiTheme }` )
	}

	const routeCommandsToShowDevBadge = [
		'panel/elements/global',
		'panel/elements/categories',
		'panel/menu',
	]

	$e.routes.on( 'run:after', () => {
		document.getElementById( 'elementor-panel-header-title' )
			.classList
			.toggle( 'elementor-beta-badge', routeCommandsToShowDevBadge.includes( $e.routes.current.panel ) )
	} );
} );
