<?php
/**
 * Extension advertisements
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;


/**
 * PB Sandwich Column Class
 */
class GambitPBSandwichExternalAds {

	function __construct() {
		add_filter( 'pbs_modal_tabs', array( $this, 'addBackgroundTabAd' ), 0 );
	}
	
	public function addBackgroundTabAd( $modalTabs ) {
		$modalTabs['parallax'] = array(
			'template' => PBS_PATH . 'lib/templates/column-row-backgrounds.php',
			'template_id' => 'pbs-row-backgrounds-modal-ad',
			'name' => __( 'Parallax & Video Backgrounds', 'pbsandwich_backgrounds' ),
			'shortcode' => 'row',
		);
		return $modalTabs;
	}

}
new GambitPBSandwichExternalAds();
?>