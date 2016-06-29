<?php

if ( ! class_exists( 'GambitVCParallaxFullheightRow' ) ) {

class GambitVCParallaxFullheightRow {
	
	function __construct() {
		add_filter( 'init', array( $this, 'createRowShortcodes' ), 999 );
		
		add_shortcode( 'fullheight_row', array( $this, 'createShortcode' ) );
		
		add_action( 'admin_enqueue_scripts', array( $this, 'adminEnqueueScripts' ) );
	}
	
	
	public function adminEnqueueScripts() {
        wp_enqueue_style( 'gambit_parallax_admin', plugins_url( 'css/admin.css', __FILE__ ), array(), VERSION_GAMBIT_VC_PARALLAX_BG );
	}
	
	
	public function createRowShortcodes() {
		if ( ! is_admin() ) {
			return;
		}
		if ( ! function_exists( 'vc_map' ) ) {
			return;
		}

		vc_map( array(
		    "name" => __( 'Full-Height Row', GAMBIT_VC_PARALLAX_BG ),
		    "base" => "fullheight_row",
			"icon" => plugins_url( 'images/vc-fullheight.png', __FILE__ ),
			"description" => __( 'Add this to a row to make it full-height.', GAMBIT_VC_PARALLAX_BG ),
			"category" => __( 'Row Adjustments', GAMBIT_VC_PARALLAX_BG ),
		    "params" => array(
				array(
					"type" => "dropdown",
					"heading" => __( 'Row Content Location', GAMBIT_VC_PARALLAX_BG ),
					"param_name" => "content_location",
					"value" => array(
						__( 'Center', GAMBIT_VC_PARALLAX_BG ) => 'center',
						__( 'Top', GAMBIT_VC_PARALLAX_BG ) => 'top',
						__( 'Bottom', GAMBIT_VC_PARALLAX_BG ) => 'bottom',
					),
                    "description" => __( 'When your row height gets stretched, your content can be smaller than your row height. Choose the location here.<br><br><em>Please remove your row&apos;s top and bottom margins to make this work correctly.</em>', GAMBIT_VC_PARALLAX_BG ),
				),
			),
		) );
	}
	
	
	public function createShortcode( $atts, $content = null ) {
        $defaults = array(
			'content_location' => 'center',
        );
		if ( empty( $atts ) ) {
			$atts = array();
		}
		$atts = array_merge( $defaults, $atts );
		
        wp_enqueue_script( 'gambit_parallax', plugins_url( 'js/min/script-min.js', __FILE__ ), array( 'jquery' ), VERSION_GAMBIT_VC_PARALLAX_BG, true );
        wp_enqueue_style( 'gambit_parallax', plugins_url( 'css/style.css', __FILE__ ), array(), VERSION_GAMBIT_VC_PARALLAX_BG );
		
		// We just add a placeholder for this
		return '<div class="gambit_fullheight_row" data-content-location="' . esc_attr( $atts['content_location'] ) . '" style="display: none"></div>';
	}
}

new GambitVCParallaxFullheightRow();

}