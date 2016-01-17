<?php
/*
Plugin Name:        usv-shortcodes
Plugin URI:         https://github.com/Thowo91/usv-shortcodes
Description:        Plugin der gesammelten Shortcodes für die Seite des Unterfränkischen Schachverbandes
Version:            0.3
Author:             Thomas Worofsky
License:            GNU General Public License v2 or later
License URI:        http://www.gnu.org/licenses/gpl-2.0.html
 */

include_once ABSPATH . 'wp-admin/includes/plugin.php';
require_once dirname( __FILE__ ) . '/shortcodes/email_cloaking.php';
require_once dirname( __FILE__ ) . '/shortcodes/pagination.php';
require_once dirname( __FILE__ ) . '/shortcodes/breadcrumb_nav.php';
