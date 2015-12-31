<?php

function usv_email_cloaking_shortcode( $atts ) {

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

add_shortcode( 'mail', 'usv_email_cloaking_shortcode' );

?>