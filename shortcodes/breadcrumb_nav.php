<?php

function usv_get_category_parents( $id ) {
	$chain  = '';
	$parent = get_term( $id, 'category' );

	if ( $parent->parent && ( $parent->parent != $parent->term_id ) ) {
		$chain .= usv_get_category_parents( $parent->parent );
	}

	$chain .= "\n<li itemscope itemtype=\"http://data-vocabulary.org/Breadcrumb\"><a itemprop=\"url\" href=\"" . esc_url( get_category_link( $parent->term_id ) ) . "\"><span itemprop=\"title\">" . $parent->name . "</span></a></li>";

	return $chain;
}

function usv_breadcrumb_shortcode() {

	$out        = '';
	$voc_before = "\n<li itemscope itemtype=\"http://data-vocabulary.org/Breadcrumb\">";
	$voc_after  = '</span></a></li>';
	$voc_title  = '<span itemprop="title">';
	$last     = $voc_before . '<a itemprop="url" href=""><span class="current-page" itemprop="title">';


	if ( ! is_home() && ! is_front_page() && ! is_paged() ) {

		$out .= "<nav class=\"breadcrumb\">\n<ol>";

		global $post;
		$homeLink = get_bloginfo( 'url' );
		$out .= $voc_before . '<a itemprop="url" href="' . $homeLink . '">' . $voc_title . 'Home' . $voc_after;

		if ( is_category() ) {
			global $wp_query;
			$cat_obj   = $wp_query->get_queried_object();
			$thisCat   = $cat_obj->term_id;
			$thisCat   = get_category( $thisCat );
			$parentCat = get_category( $thisCat->parent );
			if ( $thisCat->parent != 0 ) {
				$out .= usv_get_category_parents( $parentCat ) . $voc_after;
			}
			$out .= $last . single_cat_title( '', false ) . $voc_after;

		} elseif ( is_day() ) {
			$out .= $voc_before . '<a itemprop="url" href="' . get_year_link( get_the_time( 'Y' ) ) . '">' . $voc_title . get_the_time( 'Y' ) . $voc_after;
			$out .= $voc_before . '<a itemprop="url" href="' . get_month_link( get_the_time( 'Y' ), get_the_time( 'm' ) ) . '">' . $voc_title . get_the_time( 'F' ) . $voc_after;
			$out .= $last . get_the_time( 'd' ) . $voc_after;

		} elseif ( is_month() ) {
			$out .= $voc_before . '<a itemprop="url" href="' . get_year_link( get_the_time( 'Y' ) ) . '">' . $voc_title . get_the_time( 'Y' ) . $voc_after;
			$out .= $last . get_the_time( 'F' ) . $voc_after;

		} elseif ( is_year() ) {
			$out .= $last . get_the_time( 'Y' ) . $voc_after;

		} elseif ( is_single() && ! is_attachment() ) {
			if ( get_post_type() != 'post' ) {
				$post_type = get_post_type_object( get_post_type() );
				$slug      = $post_type->rewrite;
				$out .= $voc_before . '<a itemprop="url" href="' . $homeLink . '/' . $slug['slug'] . '/">' . $voc_title . $post_type->labels->singular_name . $voc_after;
				$out .= $last . get_the_title() . $voc_after;
				if ( get_the_title() == '' ) { //custom link
					$out = '';
				}
			} else {
				$cat     = get_the_category();
				$cat     = $cat[0];
				$catname = usv_get_category_parents( $cat );
				$out .= $voc_before . '<a itemprop="url" href="' . $homeLink . '/category/' . $catname . '/">' . $voc_title . $catname . $voc_after;
				$out .= $last . get_the_title() . $voc_after;
			}

		} elseif ( ! is_single() && ! is_page() && get_post_type() != 'post' && ! is_404() ) {
			$post_type = get_post_type_object( get_post_type() );
			$out .= $last . $post_type->labels->singular_name . $voc_after;
			if ( get_post_type() == 'page' ) { // custom link
				$out = '';
			}

		} elseif ( is_attachment() ) {
			$parent = get_post( $post->post_parent );
			$cat    = get_the_category( $parent->ID );
			$cat    = $cat[0];
			$out .= usv_get_category_parents( $cat );
			$out .= $voc_before . '<a itemprop="url" href="' . get_permalink( $parent ) . '">' . $voc_title . $parent->post_title . $voc_after;
			$out .= $last . get_the_title() . $voc_after;

		} elseif ( is_page() && ! $post->post_parent ) {
			$out .= $last . get_the_title() . $voc_after;

		} elseif ( is_page() && $post->post_parent ) {
			$parent_id   = $post->post_parent;
			$breadcrumbs = array();
			while ( $parent_id ) {
				$page          = get_post( $parent_id );
				$breadcrumbs[] = $voc_before . '<a itemprop="url" href="' . get_permalink( $page->ID ) . '">' . $voc_title . get_the_title( $page->ID ) . $voc_after;
				$parent_id     = $page->post_parent;
			}
			$breadcrumbs = array_reverse( $breadcrumbs );
			foreach ( $breadcrumbs as $crumb ) {
				$out .= $crumb;
			}
			$out .= $last . get_the_title() . $voc_after;

		} elseif ( is_search() ) {
			$out .= $last . 'Ergebnisse für Ihre Suche nach "' . get_search_query() . '"' . $voc_after;

		} elseif ( is_tag() ) {
			$out .= $last . 'Beiträge mit dem Schlagwort "' . single_tag_title( '', false ) . '"' . $voc_after;

		} elseif ( is_404() ) {
			$out .= $last . 'Fehler 404' . $voc_after;
		}

		$out .= "\n</ol>\n</nav>";

		return $out;

	}
}

add_shortcode( 'breadcrumb', 'usv_breadcrumb_shortcode' );
