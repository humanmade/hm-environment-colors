<?php

namespace HM\EnvironmentColors;


use function Altis\get_environment_type;

/**
 * @return void
 */
function bootstrap() : void {
	// Undo admin colour scheme removal
	remove_action( 'admin_init', 'Altis\\CMS\\Branding\\remove_wp_admin_color_schemes' );
	// Undo defaulting to altis for new users
	remove_filter( 'insert_user_meta', 'Altis\\CMS\\Branding\\insert_user_meta');
	remove_filter( 'get_user_option_admin_color', 'Altis\\CMS\\Branding\\override_default_color_scheme' );

	// Add per environment default colours
	add_filter( 'get_user_option_admin_color', __NAMESPACE__ . '\\override_default_color_scheme' );
}

function override_default_color_scheme( $value ) : string {
	if ( $value ) {
		return $value;
	}

	$fallback = [
		'production' => 'altis',
		'staging' => 'ectoplasm',
		'development' => 'coffee',
		'local' => 'fresh',
	];

	$environment = get_environment_type();
	$scheme = $fallback[ $environment ];

	return apply_filters( 'custom_override_default_color_scheme', $scheme, $environment );
}
