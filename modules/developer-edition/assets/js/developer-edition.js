document.addEventListener( 'DOMContentLoaded', () => {
	document.body.classList.add( 'elementor-dev' );

	if ( elementorDevUiTheme ) {
		document.body.classList.add( `elementor-dev-theme-${ elementorDevUiTheme }` )
	}

	const routeCommandsToShowDevBadge = [
		'panel/elements/global',
		'panel/elements/categories',
		'panel/menu',
	]

	$e.routes.on( 'run:after', () => {
		document.getElementById( 'elementor-panel-header-title' )
			.classList
			.toggle( 'elementor-dev-badge', routeCommandsToShowDevBadge.includes( $e.routes.current.panel ) )
	} );
} );
