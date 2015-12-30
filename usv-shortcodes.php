<?php
/*
Plugin Name:        usv-shortcodes
Plugin URI:         https://github.com/Thowo91/usv-shortcodes
Description:        Plugin der gesammelten Shortcodes für die Seite des Unterfränkischen Schachverbandes
Version:            0.1
Author:             Thomas Worofsky
License:            GNU General Public License v2 or later
License URI:        http://www.gnu.org/licenses/gpl-2.0.html
 */

function email_cloaking_shortcode( $atts ) {

	// Attributes
	extract( shortcode_atts(
			array(
				'pre'     => '',
				'suf'     => '',
				'dom'     => '',
				'display' => ''
			), $atts )
	);

	// Code
	return '<span class="cloakMail" data-pre="' . $pre . '" data-suf="' . $suf . '" data-dom="' . $dom . '" data-display="' . $display . '">' . $display . '</span><noscript><br>"Diese E-Mail-Adresse ist vor Spambots geschützt! Zur Anzeige muss JavaScript eingeschaltet sein!"</noscript>';
}

add_shortcode( 'mail', 'email_cloaking_shortcode' );

?>