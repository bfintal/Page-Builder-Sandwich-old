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
			'label' => __( 'Media / URL Embed', 'pbsandwich' ),
			'listItemImage' => 'dashicons-admin-media',
			'attrs' => array(
				array(
					'label' => __( 'Media URL', 'pbsandwich' ),
					'attr' => 'content',
					'type' => 'text',
					'description' => __( "Enter the URL of the media you wish to embed. Supported sites include Animoto, Blip, CollegeHumor, DailyMotion, Flickr, FunnyOrDie, Hulu, Imgur, Instagram, iSnare, Issuu, Meetup, EmbedArticles, Mixcloud, Photobucket, PollDaddy, Rdio, Revision3, Scribd, SlideShare, SmugMug, SoundCloud, Spotify, TED, Vimeo, Vine, WordPress.tv and YouTube. If you have Jetpack's Shortcode Embeds module enabled, you can also embed Facebook, Github Gist, Google+, and Medium links", 'pbsandwich' ),
				),
			),
		)
	);
	
}


/**
 * Inside the admin, the embed shortcode doesn't work like normal shortcodes.
 * At the start it is unset, then gets added in to parse the post content.
 * This fixes this by adding the embed shortcode before the preview is rendered.
 */
add_action( 'shortcode_ui_before_do_shortcode', 'embed_fix_shortcode_ui_before_do_shortcode' );
function embed_fix_shortcode_ui_before_do_shortcode( $shortcode ) {
	if ( ! preg_match( '/\[embed\s/', $shortcode ) ) {
		return;
	}
	if ( ! is_admin() ) {
		return;
	}
	
	$wpEmbed = new WP_Embed();
	add_shortcode( 'embed', array( $wpEmbed, 'shortcode' ) );
}