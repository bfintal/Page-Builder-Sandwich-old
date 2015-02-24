<?php

add_action( 'init', 'sandwich_embed_media', 11 );

function sandwich_embed_media() {

	// Check if Shortcake exists
	if ( ! function_exists( 'shortcode_ui_register_for_shortcode') ) {
		return;
	}
	
	shortcode_ui_register_for_shortcode(
		'embed',
		array(
			'label' => __( 'Jetpack' , 'jetpack' ) . ' ' . _x( 'Embed Video Media', 'Module Name', 'jetpack' ),
			'listItemImage' => 'dashicons-portfolio',
			'attrs' => array(
				array(
					'label' => __( 'Media URL', 'pbsandwich' ),
					'attr' => 'content',
					'type' => 'text',
					'description' => __( 'Enter the URL of the media you wish to embed. Supported sites include Animoto, Blip, CollegeHumor, DailyMotion, Flickr, FunnyOrDie, Hulu, Imgur, Instagram, iSnare, Issuu, Meetup, EmbedArticles, Mixcloud, Photobucket, PollDaddy, Rdio, Revision3, Scribd, SlideShare, SmugMug, SoundCloud, Spotify, TED, Vimeo, Vine, WordPress.tv and YouTube.', 'pbsandwich' ),
				),
			),
		)
	);
	
	// Make sure Jetpack is activated
	if ( ! class_exists( 'Jetpack' ) ) {
		add_action( 'print_media_templates', 'sandwich_embed_media_disabled' );
		return;
	}

	// Make sure the contact form module is turned on
	if ( ! Jetpack::is_module_active( 'custom-content-types' ) ) {
		add_action( 'print_media_templates', 'sandwich_embed_media_disabled' );
		return;
	}
	
}

function sandwich_embed_media_disabled() {
	GambitPBSandwich::printDisabledShortcakeStlyes( 'portfolio', __( "Requires Jetpack's Custom Content Type module", 'pbsandwich' ) );
}