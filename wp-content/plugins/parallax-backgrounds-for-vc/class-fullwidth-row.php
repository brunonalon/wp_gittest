<?php

if ( ! class_exists( 'GambitVCParallaxFullwidthRow' ) ) {

class GambitVCParallaxFullwidthRow {
	
	function __construct() {
		add_filter( 'init', array( $this, 'createRowShortcodes' ), 999 );
		
		add_shortcode( 'fullwidth_row', array( $this, 'createShortcode' ) );
		
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

		global $content_width;

		vc_map( array(
		    "name" => __( 'Full-Width Row', GAMBIT_VC_PARALLAX_BG ),
		    "base" => "fullwidth_row",
			"icon" => plugins_url( 'images/vc-fullwidth.png', __FILE__ ),
			"description" => __( 'Add this to a row to make it full-width.', GAMBIT_VC_PARALLAX_BG ),
			"category" => __( 'Row Adjustments', GAMBIT_VC_PARALLAX_BG ),
		    "params" => array(
				array(
					"type" => "textfield",
					"heading" => __( 'Row Content Width', GAMBIT_VC_PARALLAX_BG ),
					"param_name" => "content_width",
					"value" => "",
                    "description" => __( 'When your row gets stretched, your content will by default be adjusted to the <strong>content width</strong> defined by your theme. Enter a value here with units (px or %) to adjust the width of your full-width content.<br>e.g. Use <code>100%</code> to stretch your row content to the entire full-width,<br>Use <code>50%</code> to make your content exactly half of the page,<br>Use <code>700px</code> to ensure your content is at maximum 700 pixels wide,<br>or leave <strong>blank</strong> to follow the default row content width,<br>', GAMBIT_VC_PARALLAX_BG ),
				),
			),
		) );
	}
	
	
	public function createShortcode( $atts, $content = null ) {
		global $content_width;
		
        $defaults = array(
			'content_width' => $content_width,
        );
		if ( empty( $atts ) ) {
			$atts = array();
		}
		$atts = array_merge( $defaults, $atts );
		
        wp_enqueue_script( 'gambit_parallax', plugins_url( 'js/min/script-min.js', __FILE__ ), array( 'jquery' ), VERSION_GAMBIT_VC_PARALLAX_BG, true );
		
		// We just add a placeholder for this
		return '<div class="gambit_fullwidth_row" data-content-width="' . esc_attr( $atts['content_width'] ) . '" style="display: none"></div>';
	}
}

new GambitVCParallaxFullwidthRow();

}