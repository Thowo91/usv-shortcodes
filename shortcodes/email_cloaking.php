<?php

function usv_email_cloaking_shortcode($atts)
{

    $code = (shortcode_atts(
        array(
            'email' => '',
            'display' => '0'
        ), $atts)
    );

    if (!is_email($code['email'])) {
        return;
    }

    if ($code['display'] == '0') {
        $code['display'] = $code['email'];
    }

    $mailto = '<a href="mailto:' . antispambot($code['email']) . '">' . $code['display'] . '</a>';

    return $mailto;
}

add_shortcode('mail', 'usv_email_cloaking_shortcode');

?>