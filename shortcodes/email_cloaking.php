<?php

function usv_email_cloaking_shortcode( $atts ) {

	$code = ( shortcode_atts(
		array(
			'email'   => '',
			'display' => '0'
		), $atts )
	);

	if ( ! is_email( $code['email'] ) ) {
		return;
	}

	if ( $code['display'] == '0' ) {
		$code['display'] = $code['email'];
	}

	$mailto = '<a href="mailto:' . antispambot( $code['email'] ) . '">' . $code['display'] . '</a>';

	return $mailto;
}

add_shortcode( 'mail', 'usv_email_cloaking_shortcode' );

if ( function_exists( shortcode_ui_register_for_shortcode ) ) {
	shortcode_ui_register_for_shortcode(
		'mail',
		array(
			'label'         => 'Spamsichere Email',
			'listItemImage' => 'dashicons-email',
			'attrs'         => array(
				array(
					'label'       => 'Email',
					'attr'        => 'email',
					'type'        => 'text',
					'description' => 'Email Adresse eintragen',
				),
				array(
					'label'       => 'Display',
					'attr'        => 'display',
					'type'        => 'text',
					'description' => 'Anzeigetext eintragen (ansonsten wird nochmal die Email Adresse ausgegeben)',
				),
			),
			'post_type'     => array( 'post', 'page' ),
		)
	);
}

?>