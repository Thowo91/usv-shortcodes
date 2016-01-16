<?php

function usv_breadcrumb_shortcode() {

	$out        = '';
	$delimiter  = '&raquo;';
	$home       = 'Home';
	$voc_before = '<li itemscope itemtype="http://data-vocabulary.org/Breadcrumb">';
	$voc_after  = '</li>';
	$voc_url    = ' itemprop="url"';
	$voc_title  = '<span itemprop="title">';
	$before     = $voc_before . '<a href=""' . $voc_url . '></a><span class="current-page" itemprop="title">';
	$after      = '</span>';


	if ( ! is_home() && ! is_front_page() && ! is_paged() ) {

		$out .= '<nav class="breadcrumb"><ol>';

		global $post;
		$homeLink = get_bloginfo( 'url' );
		$out .= $voc_before . '<a href="' . $homeLink . '"' . $voc_url . '>' . $voc_title . $home . $after . '</a>' . $voc_after . $delimiter . ' ';

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
			$out .= $voc_before . '<a href="' . get_year_link( get_the_time( 'Y' ) ) . '"' . $voc_url . '>' . $voc_title . get_the_time( 'Y' ) . $after . '</a> ' . $voc_after . $delimiter . ' ';
			$out .= $voc_before . '<a href="' . get_month_link( get_the_time( 'Y' ), get_the_time( 'm' ) ) . '"' . $voc_url . '>' . $voc_title . get_the_time( 'F' ) . $after . '</a> ' . $voc_after . $delimiter . ' ';
			$out .= $before . get_the_time( 'd' ) . $after;

		} elseif ( is_month() ) {
			$out .= $voc_before . '<a href="' . get_year_link( get_the_time( 'Y' ) ) . '"' . $voc_url . '>' . $voc_title . get_the_time( 'Y' ) . $after . '</a> ' . $voc_after . $delimiter . ' ';
			$out .= $before . get_the_time( 'F' ) . $after;

		} elseif ( is_year() ) {
			$out .= $before . get_the_time( 'Y' ) . $after;

		} elseif ( is_single() && ! is_attachment() ) {
			if ( get_post_type() != 'post' ) {
				$post_type = get_post_type_object( get_post_type() );
				$slug      = $post_type->rewrite;
				$out .= $voc_before . '<a href="' . $homeLink . '/' . $slug['slug'] . '/"' . $voc_url . '>' . $voc_title . $post_type->labels->singular_name . $after . '</a> ' . $voc_after . $delimiter . ' ';
				$out .= $before . get_the_title() . $after;
				if ( get_the_title() == '' ) {
					$out = '';
				}
			} else {
				$cat     = get_the_category();
				$cat     = $cat[0];
				$catname = get_category_parents( $cat, false, '' );
				$out .= $voc_before . '<a href="' . $homeLink . '/category/' . $catname . '/"' . $voc_url . '>' . $voc_title . $catname . '</a>' . $voc_after . $delimiter . ' ';
				$out .= $before . get_the_title() . $after;
			}

		} elseif ( ! is_single() && ! is_page() && get_post_type() != 'post' && ! is_404() ) {
			$post_type = get_post_type_object( get_post_type() );
			$out .= $before . $post_type->labels->singular_name . $after;
			if ( get_post_type() == 'page' ) {
				$out = '';
			}

		} elseif ( is_attachment() ) {
			$parent = get_post( $post->post_parent );
			$cat    = get_the_category( $parent->ID );
			$cat    = $cat[0];
			$out .= get_category_parents( $cat, true, ' ' . $delimiter . ' ' );
			$out .= $voc_before . '<a href="' . get_permalink( $parent ) . '"' . $voc_url . '>' . $voc_title . $parent->post_title . $after . '</a> ' . $voc_after . $delimiter . ' ';
			$out .= $before . get_the_title() . $after;

		} elseif ( is_page() && ! $post->post_parent ) {
			$out .= $before . get_the_title() . $after;

		} elseif ( is_page() && $post->post_parent ) {
			$parent_id   = $post->post_parent;
			$breadcrumbs = array();
			while ( $parent_id ) {
				$page          = get_post( $parent_id );
				$breadcrumbs[] = $voc_before . '<a href="' . get_permalink( $page->ID ) . '"' . $voc_url . '>' . $voc_title . get_the_title( $page->ID ) . $after . '</a>' . $voc_after;
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

		$out .= '</ol></nav>';

		return $out;

	}
}

add_shortcode( 'breadcrumb', 'usv_breadcrumb_shortcode' );
