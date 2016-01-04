<?php

function usv_breadcrumb_shortcode() {

	$out       = '';
	$delimiter = '&raquo;';
	$home      = 'Home';
	$before    = '<span class="current-page">';
	$after     = '</span>';

	if ( ! is_home() && ! is_front_page() || is_paged() ) {

		$out .= '<nav class="breadcrumb">Sie sind hier: ';

		global $post;
		$homeLink = get_bloginfo( 'url' );
		$out .= '<a href="' . $homeLink . '">' . $home . '</a> ' . $delimiter . ' ';

		if ( is_category() ) {
			global $wp_query;
			$cat_obj   = $wp_query->get_queried_object();
			$thisCat   = $cat_obj->term_id;
			$thisCat   = get_category( $thisCat );
			$parentCat = get_category( $thisCat->parent );
			if ( $thisCat->parent != 0 ) {
				$out .= ( get_category_parents( $parentCat, true, ' ' . $delimiter . ' ' ) );
			}
			$out .= $before . single_cat_title( '', false ) . $after;

		} elseif ( is_day() ) {
			$out .= '<a href="' . get_year_link( get_the_time( 'Y' ) ) . '">' . get_the_time( 'Y' ) . '</a> ' . $delimiter . ' ';
			$out .= '<a href="' . get_month_link( get_the_time( 'Y' ), get_the_time( 'm' ) ) . '">' . get_the_time( 'F' ) . '</a> ' . $delimiter . ' ';
			$out .= $before . get_the_time( 'd' ) . $after;

		} elseif ( is_month() ) {
			$out .= '<a href="' . get_year_link( get_the_time( 'Y' ) ) . '">' . get_the_time( 'Y' ) . '</a> ' . $delimiter . ' ';
			$out .= $before . get_the_time( 'F' ) . $after;

		} elseif ( is_year() ) {
			$out .= $before . get_the_time( 'Y' ) . $after;

		} elseif ( is_single() && ! is_attachment() ) {
			if ( get_post_type() != 'post' ) {
				$post_type = get_post_type_object( get_post_type() );
				$slug      = $post_type->rewrite;
				$out .= '<a href="' . $homeLink . '/' . $slug['slug'] . '/">' . $post_type->labels->singular_name . '</a> ' . $delimiter . ' ';
				$out .= $before . get_the_title() . $after;
			} else {
				$cat = get_the_category();
				$cat = $cat[0];
				$out .= get_category_parents( $cat, true, ' ' . $delimiter . ' ' );
				$out .= $before . get_the_title() . $after;
			}

		} elseif ( ! is_single() && ! is_page() && get_post_type() != 'post' && ! is_404() ) {
			$post_type = get_post_type_object( get_post_type() );
			$out .= $before . $post_type->labels->singular_name . $after;

		} elseif ( is_attachment() ) {
			$parent = get_post( $post->post_parent );
			$cat    = get_the_category( $parent->ID );
			$cat    = $cat[0];
			$out .= get_category_parents( $cat, true, ' ' . $delimiter . ' ' );
			$out .= '<a href="' . get_permalink( $parent ) . '">' . $parent->post_title . '</a> ' . $delimiter . ' ';
			$out .= $before . get_the_title() . $after;

		} elseif ( is_page() && ! $post->post_parent ) {
			$out .= $before . get_the_title() . $after;

		} elseif ( is_page() && $post->post_parent ) {
			$parent_id   = $post->post_parent;
			$breadcrumbs = array();
			while ( $parent_id ) {
				$page          = get_post( $parent_id );
				$breadcrumbs[] = '<a href="' . get_permalink( $page->ID ) . '">' . get_the_title( $page->ID ) . '</a>';
				$parent_id     = $page->post_parent;
			}
			$breadcrumbs = array_reverse( $breadcrumbs );
			foreach ( $breadcrumbs as $crumb ) {
				$out .= $crumb . ' ' . $delimiter . ' ';
			}
			$out .= $before . get_the_title() . $after;

		} elseif ( is_search() ) {
			$out .= $before . 'Ergebnisse für Ihre Suche nach "' . get_search_query() . '"' . $after;

		} elseif ( is_tag() ) {
			$out .= $before . 'Beiträge mit dem Schlagwort "' . single_tag_title( '', false ) . '"' . $after;

		} elseif ( is_404() ) {
			$out .= $before . 'Fehler 404' . $after;
		}

		if ( get_query_var( 'paged' ) ) {
			if ( is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author() ) {
				$out .= ' (';
			}
			$out .= ': ' . __( 'Seite' ) . ' ' . get_query_var( 'paged' );
			if ( is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author() ) {
				$out .= ')';
			}
		}

		$out .= '</nav>';

		return $out;

	}
}

add_shortcode( 'breadcrumb', 'usv_breadcrumb_shortcode' );