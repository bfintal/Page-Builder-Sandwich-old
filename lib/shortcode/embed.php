<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;


/**
 * We will no longer be supporting [embed src="..."][/embed] syntax since WP's default Insert from URL uses [embed]
 * Convert all "src" syntax into [embed]...[/embed] syntax, but only when editing, everything works fine in the frontend anyway
 * Fixes #147: Inserting a video via Insert from URL doesn't work
 */
add_filter( 'content_edit_pre', 'sandwich_embed_change_embed_format' );
function sandwich_embed_change_embed_format( $content ) {
	if ( ! preg_match( '/\[embed[^\]]+\]/', $content ) ) {
		return $content;
	}
	
	// Convert [embed src="..."][/embed]
	$content = preg_replace( '/(\[embed[^\]]+)(src=("|\')?([^"\']+)("|\')?)([^\]]*\])[^\[]*(\[\/embed\])/', '$1$6$4$7', $content );
	
	// Convert [embed src="..."]
	return preg_replace( '/(\[embed[^\]]+)(src=("|\')?([^"\']+)("|\')?)([^\]]*\])/', '$1$6$4[/embed]', $content );
}


add_action( 'init', 'sandwich_embed_media', 11 );

function sandwich_embed_media() {

	// Check if Shortcake exists
	if ( ! function_exists( 'shortcode_ui_register_for_shortcode') ) {
		return;
	}
	
	if ( ! is_admin() ) {
		return;
	}
	
	shortcode_ui_register_for_shortcode(
		'embed',
		array(
			'label' => __( 'Media / URL Embed', 'pbsandwich' ),
			'listItemImage' => 'dashicons-admin-media',
			// 'attrs' => array(
			// ),
			'inner_content' => array(
				'value' => '',
				'type' => 'url',
				'description' => __( "Enter the URL of the media you wish to embed. Supported sites include Animoto, Blip, CollegeHumor, DailyMotion, Flickr, FunnyOrDie, Hulu, Imgur, Instagram, iSnare, Issuu, Meetup, EmbedArticles, Mixcloud, Photobucket, PollDaddy, Rdio, Revision3, Scribd, SlideShare, SmugMug, SoundCloud, Spotify, TED, Vimeo, Vine, WordPress.tv and YouTube. If you have Jetpack's Shortcode Embeds module enabled, you can also embed Facebook, Github Gist, Google+, and Medium links", 'pbsandwich' ),
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