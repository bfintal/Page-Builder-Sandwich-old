<?php

/**
 * Creates the view for the archives widget
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

add_action( 'init', 'sandwich_jetpack_gravatar_profile_widget', 11 );

function sandwich_jetpack_gravatar_profile_widget() {
	
	add_shortcode( 'pbs_jetpack_gravatar_profile_widget', 'sandwich_jetpack_gravatar_profile_widget_shortcode' );

	// Check if Shortcake exists
	if ( ! function_exists( 'shortcode_ui_register_for_shortcode' ) ) {
		return;
	}
	
	if ( ! is_admin() ) {
		return;
	}
	
	$options = array(
		'-1' => __( 'Custom', 'jetpack' )
	);
	
	$users = get_users();
	foreach ( $users as $user ) {
		$options[ $user->user_email ] = $user->user_nicename;
	}
	
	shortcode_ui_register_for_shortcode(
        'pbs_jetpack_gravatar_profile_widget',
        array(
            'label' => __( 'Jetpack Widget - Gravatar Profile', 'pbsandwich' ),
            'listItemImage' => 'dashicons-wordpress jetpack-logo',
            'attrs' => array(
                array(
                    'label' => __( 'Title', 'pbsandwich' ),
                    'attr' => 'title',
                    'type' => 'text',
					'value' => __( 'Gravatar Profile', 'jetpack' ),
					'description' => __( 'The title to display for this widget', 'pbsandwich' ),
                ),
				array(
					'label' => __( 'Select a user or pick "custom" and enter a custom email address.', 'jetpack' ),
					'attr' => 'email_user',
					'type' => 'select',
					'value' => '-1',
					'options' => $options
				),
				array(
					'label' => __( 'Custom Email Address', 'jetpack' ),
					'attr' => 'email',
					'type' => 'text',
					'value' => '',
				),
				array(
					'label' => __( 'Show Personal Links', 'jetpack' ),
					'attr' => 'show_personal_links',
					'type' => 'checkbox',
					'value' => false,
					'description' => __( 'Links to your websites, blogs, or any other sites that help describe who you are.', 'jetpack' ),
				),
				array(
					'label' => __( 'Show Account Links', 'jetpack' ),
					'attr' => 'show_account_links',
					'type' => 'checkbox',
					'value' => false,
					'description' => __( 'Links to services that you use across the web.', 'jetpack' ),
				),
				array(
					'label' => __( 'Hide widget title', 'pbsandwich' ),
					'attr' => 'hide_title',
					'type' => 'checkbox',
					'value' => false,
				),
			),
        )
    );
	
	if ( ! class_exists( 'Jetpack_Gravatar_Profile_Widget' ) ) {
		add_action( 'print_media_templates', 'sandwich_jetpack_gravatar_profile_widget_shortcode_disabled' );		
		return;
	}
}


function sandwich_jetpack_gravatar_profile_widget_shortcode( $attr, $content ) {
	
	if ( ! class_exists( 'Jetpack_Gravatar_Profile_Widget' ) ) {
		return '';
	}
		
	$attr = wp_parse_args( $attr, array(
        'title' => __( 'Gravatar Profile', 'jetpack' ),
		'email_user' => '-1',
		'email' => 5,
		'show_personal_links' => false,
		'show_account_links' => false,
		'hide_title' => false
    ) );
		
	// In the Jetpack widget, if email_user is -1, the email inputted is used. If the email_user is a user_id, then the email of that user
	// is used. We do that directly from here
	if ( $attr['email_user'] != -1 ) {
		$attr['email'] = $attr['email_user'];
	}
	
	$attr['show_personal_links'] = $attr['show_personal_links'] === 'true' || $attr['show_personal_links'] === true ? true : false;
	$attr['show_account_links'] = $attr['show_account_links'] === 'true' || $attr['show_account_links'] === true ? true : false;
	
	$hideTitleClass = '';
	if ( $attr['hide_title'] ) {
		$hideTitleClass = 'hide_title';
	}
			
	ob_start();
	
	?>
	<div class="sandwich <?php echo $hideTitleClass ?>">
		<?php the_widget( 'Jetpack_Gravatar_Profile_Widget', $attr ); ?>
	</div>
	
	<?php
		
	return ob_get_clean();
}


function sandwich_jetpack_gravatar_profile_widget_shortcode_disabled() {
	GambitPBSandwich::printDisabledShortcakeStlyes( 'pbs_jetpack_gravatar_profile_widget', __( "Requires Jetpack's Extra Sidebar Widgets module", 'pbsandwich' ) );
}