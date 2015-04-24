<?php

/**
 * Function files used by all Shortcode Template
 * These files are reusable and are used by many shortcake modules.
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Commonly-used variables stored here for better management.
 */

function sandwich_functions_display_order() {
	$output = array();
	$output['author'] = __( 'Author', 'pbsandwich' );
	$output['date'] = __( 'Item Date', 'pbsandwich' );
	$output['title'] = __( 'Title', 'pbsandwich' );
	$output['rand'] = __( 'Randomized', 'pbsandwich' );
	return $output;
}

function sandwich_functions_display_dir() {
	$output = array();
	$output['ASC'] = __( 'Ascending', 'pbsandwich' );
	$output['DESC'] = __( 'Descending', 'pbsandwich' );
	return $output;
}

/**
 * Encodes the list of posts in a given post type into an array variable.
 * Choose between array, comma-separated string or slug.
 * To output the ID of the post beside the title (for coherence purposes, set $id to true in its arguments)
 */

function sandwich_functions_posttype_list( $type = "forum", $id = "false" ) {
	$args = array(
		'post_type' => $type,
		'posts_per_page' => '-1'
	);
	$loop = new WP_Query( $args );
	
	$output = array(
		0 => sprintf( '— %s —', __( 'Select', 'pbsandwich' ) )
	);
	
	if ( $loop->have_posts() ) {
		while ( $loop->have_posts() ) : $loop->the_post();
			$fieldout = get_the_title();
			if ( $id != "false" ) {
				$fieldout .= " (" . get_the_ID() . ")";
			}
			$output[ get_the_ID() ] = $fieldout;
		endwhile;
	}
	wp_reset_postdata();

	return $output;
}

/**
 * Retrieves a list of Taxonomies
 * Set type to tag to fetch tags. Any other values are treated as taxonomies.
 */

function sandwich_functions_taxonomy_list( $type = "taxonomy" ) {
	$output = array(
		0 => sprintf( '— %s —', __( 'Select', 'pbsandwich' ) )
	);
	foreach ( get_taxonomies() as $taxonomy ) {
		$tax = get_taxonomy( $taxonomy );
		if ( ( ! $tax->show_tagcloud || empty( $tax->labels->name ) ) && $type == "tag" ) {
			continue;
		}
		$output[ esc_attr( $taxonomy ) ] = esc_attr( $tax->labels->name );
	}
	return $output;
}
	

/**
 * Encodes the list of terms in a given taxonomy into an array variable.
 * Choose between array, comma-separated string or slug.
 */
function sandwich_functions_term_list( $taxonomyName = 'post_tag' ) {
	$terms = get_terms( $taxonomyName, array( 
		'parent' => 0,
		'hide_empty' => false,
	) );
	
	$output = array(
		0 => sprintf( '— %s —', __( 'Select', 'pbsandwich' ) )
	);
	
	if ( is_wp_error( $terms ) ) {
		return $output;
	}

	foreach( $terms as $term ) {
		
		$output[ $term->slug ] = $term->name;
		$term_children = get_term_children( $term->term_id, $taxonomyName );
		
		if ( is_wp_error( $term_children ) ) {
			continue;
		}
		
		foreach( $term_children as $term_child_id ) {
			
			$term_child = get_term_by( 'id', $term_child_id, $taxonomyName );
			
			if ( is_wp_error( $term_child ) ) {
				continue;
			}
			
			$output[ $term_child->slug ] = $term_child->name;
		}
		
	}
	
	return $output;
}