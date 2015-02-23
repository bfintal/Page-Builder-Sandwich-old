<?php

$numberOfColumns = array();
for ( $i = 1; $i <= 6; $i++ ) {
	$numberOfColumns[ $i ] = $i;
}

$displayOrder['author'] = __( 'Author', 'pbsandwich' );
$displayOrder['date'] = __( 'Post Date', 'pbsandwich' );
$displayOrder['title'] = __( 'Post Title', 'pbsandwich' );
$displayOrder['rand'] = __( 'Randomized', 'pbsandwich' );

$displayDir['ASC'] = __( 'Ascending', 'pbsandwich' );
$displayDir['DESC'] = __( 'Descending', 'pbsandwich' );

add_action( 'init', 'sandwich_jetpack_portfolio', 11 );

function sandwich_jetpack_portfolio() {

	// Check if Shortcake exists
	if ( ! function_exists( 'shortcode_ui_register_for_shortcode') ) {
		return;
	}
	
	shortcode_ui_register_for_shortcode(
        'portfolio',
        array(
            'label' => __( 'Jetpack' , 'jetpack' ) . ' ' . _x( 'Portfolio', 'Module Name', 'jetpack' ),
            'listItemImage' => 'dashicons-portfolio',
            'attrs' => array(
                array(
                    'label' => __( 'Display Portfolio Types', 'pbsandwich' ),
                    'attr'  => 'display_types',
                    'type'  => 'checkbox',
                ),
                array(
                    'label' => __( 'Display Portfolio Tags', 'pbsandwich' ),
                    'attr'  => 'display_tags',
                    'type'  => 'checkbox',
                ),
                array(
                    'label' => __( 'Display Portfolio Content', 'pbsandwich' ),
                    'attr'  => 'display_content',
                    'type'  => 'checkbox',
                ),
                array(
                    'label' => __( 'Entry Types to display', 'pbsandwich' ),
                    'attr'  => 'include_type',
                    'type'  => 'text',
					'description' => __( 'Display Portfolio Types', 'pbsandwich' )'Enter slug names of entries to display. If none entered, defaults to displaying all entries',
                ),
                array(
                    'label' => __( 'Tags to display', 'pbsandwich' ),
                    'attr'  => 'include_tag',
                    'type'  => 'text',
					'description' => __( 'Enter slug names of tags to display. If none entered, defaults to displaying all tags', 'pbsandwich' ),
                ),
                array(
                    'label' => __( 'Columns to display', 'pbsandwich' ),
                    'attr'  => 'columns',
                    'type'  => 'select',
					'description' => __( 'Enter slug names of entries to display. If none entered, defaults to displaying all entries', 'pbsandwich' ),
					'options' => $numberOfColumns,
					'default' => '2',
                ),
                array(
                    'label' => __( 'Amount of posts to display', 'pbsandwich' ),
                    'attr'  => 'showposts',
                    'type'  => 'text',
					'description' => __( 'Enter the number of entries to display. If none entered, defaults to 5', 'pbsandwich' ),
					'value' => '5',
                ),
                array(
                    'label' => __( 'Display Order', 'pbsandwich' ),
                    'attr'  => 'order',
                    'type'  => 'select',
					'description' => __( 'Choose sorting direction. Ascending takes your latest post to the last page, while Descending displays your latest post first.', 'pbsandwich' ),
					'options' => $displayDir,
					'default' => 'DESC',
                ),
                array(
                    'label' => __( 'Display Order criteria', 'pbsandwich' ),
                    'attr'  => 'orderby',
                    'type'  => 'select',
					'description' => __( 'Choose the criteria of entries to be sorted by.', 'pbsandwich' ),
					'options' => $displayOrder,
					'default' => 'date',
                ),
			),
        )
    );
	
	// Make sure Jetpack is activated
	if ( ! class_exists( 'Jetpack' ) ) {
		add_action( 'print_media_templates', 'sandwich_jetpack_disabled' );
		return;
	}

	// Make sure the contact form module is turned on
	if ( ! Jetpack::is_module_active( 'custom-content-types' ) ) {
		add_action( 'print_media_templates', 'sandwich_jetpack_portfolio_disabled' );
		return;
	}
	
}

function sandwich_jetpack_disabled() {
	GambitPBSandwich::printDisabledShortcakeStlyes( 'portfolio', "Requires Jetpack plugin and its Custom Content Type module" );
}

function sandwich_jetpack_portfolio_disabled() {
	GambitPBSandwich::printDisabledShortcakeStlyes( 'portfolio', "Requires Jetpack's Custom Content Type module" );
}