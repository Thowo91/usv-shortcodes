<?php

function usv_pagination_shortcode() {
	$out       = '';
	$pages     = '';
	$range     = 2;
	$showitems = ( $range * 2 ) + 1;
	global $paged;
	if ( empty( $paged ) ) {
		$paged = 1;
	}

	global $wp_query;
	$pages = $wp_query->max_num_pages;
	if ( ! $pages ) {
		$pages = 1;
	}

	if ( $pages != 1 ) {
		$out .= "<nav class=\"pagination\"><ul>";
		if ( $paged > 2 && $paged > $range + 1 && $showitems < $pages ) {
			$out .= "<li><a href='" . get_pagenum_link( 1 ) . "'>&laquo;</a></li>";
		}
		if ( $paged > 1 && $showitems < $pages ) {
			$out .= "<li><a href='" . get_pagenum_link( $paged - 1 ) . "'>&lsaquo;</a></li>";
		}

		for ( $i = 1; $i <= $pages; $i ++ ) {
			if ( 1 != $pages && ( ! ( $i >= $paged + $range + 1 || $i <= $paged - $range - 1 ) || $pages <= $showitems ) ) {
				$out .= ( $paged == $i ) ? "<li><span class=\"current\">" . $i . "</span></li>" : "<li><a href='" . get_pagenum_link( $i ) . "' class=\"inactive \">" . $i . "</a></li>";
			}
		}

		if ( $paged < $pages && $showitems < $pages ) {
			$out .= "<li><a href=\"" . get_pagenum_link( $paged + 1 ) . "\">&rsaquo;</a></li>";
		}
		if ( $paged < $pages - 1 && $paged + $range - 1 < $pages && $showitems < $pages ) {
			$out .= "<li><a href='" . get_pagenum_link( $pages ) . "'>&raquo;</a></li>";
		}
		$out .= "</ul></nav>\n";

		return $out;
	}
}

add_shortcode( 'pagination', 'usv_pagination_shortcode' );
