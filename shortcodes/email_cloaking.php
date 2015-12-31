<?php

function usv_email_cloaking_shortcode($atts)
{

    $code = (shortcode_atts(
        array(
            'mail' => '',
            'display' => '0'
        ), $atts)
    );

    if (!is_email($code['mail'])) {
        return;
    }

    if ($code['display'] == '0') {
        $code['display'] = $code['mail'];
    }

    $mailto = '<a href="mailto:' . antispambot($code['mail']) . '">' . $code['display'] . '</a>';

    return $mailto;
}

add_shortcode('mail', 'usv_email_cloaking_shortcode');

?>