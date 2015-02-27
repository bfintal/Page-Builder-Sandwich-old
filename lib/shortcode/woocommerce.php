<?php

/**
 * Initialization for WooCommerce shortcodes
 * @see http://docs.woothemes.com/document/woocommerce-shortcodes/
 */

add_action( 'init', 'sandwich_woocommerce_init_editor_styles', 11 );
function sandwich_woocommerce_init_editor_styles() {
	if ( ! class_exists( 'WC_Frontend_Scripts' ) ) {
		return;
	}

	if ( $enqueue_styles = WC_Frontend_Scripts::get_styles() ) {
		foreach ( $enqueue_styles as $handle => $args ) {
			
			// Don't include WC's small screen css because it bugs the display out
			if ( preg_match( '/woocommerce-smallscreen/', $args['src'] ) ) {
				continue;
			}
			
			add_editor_style( $args['src'] );
		}
	}
}

/**
 * Commonly-used variables stored here for better management.
 */

function sandwich_woocommerce_display_order () {
	$output['author'] = __( 'Author', 'pbsandwich' );
	$output['date'] = __( 'Item Date', 'pbsandwich' );
	$output['title'] = __( 'Title', 'pbsandwich' );
	$output['rand'] = __( 'Randomized', 'pbsandwich' );
	return $output;
}

function sandwich_woocommerce_display_dir () {
	$output['ASC'] = __( 'Ascending', 'pbsandwich' );
	$output['DESC'] = __( 'Descending', 'pbsandwich' );
	return $output;
}

/**
 * Encodes the list of products into an array variable.
 * Choose between array, comma-separated string or slug.
 */

function sandwich_woocommerce_product_list ($type = "array") {
 	$args = array(
 		'post_type' => 'product',
 		'posts_per_page' => '-1'
 		);
 	$loop = new WP_Query( $args );
 	if ( $loop->have_posts() ) {
 		while ( $loop->have_posts() ) : $loop->the_post();
 			$output[get_the_ID()] = get_the_title() . " (" . get_the_ID() . ")";
 		endwhile;
 	} else {
 			$output[0] = "No Products found!";
 	}
 	wp_reset_postdata();

 	return $output;
}

function sandwich_woocommerce_product_category_list() {
	$taxonomyName = 'product_cat';
	$terms = get_terms( $taxonomyName, array('parent' => 0) );
	foreach($terms as $term) {
		$output[$term->slug] = $term->name;
    	$term_children = get_term_children($term->term_id,$taxonomyName);
    	foreach($term_children as $term_child_id) {
        	$term_child = get_term_by('id',$term_child_id,$taxonomyName);
			$output[$term_child->slug] = "-" . $term_child->name;
    	}
	}
	return $output;
}