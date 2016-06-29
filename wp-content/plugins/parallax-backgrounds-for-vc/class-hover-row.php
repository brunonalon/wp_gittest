<?php

if ( ! class_exists( 'GambitVCHoverRow' ) ) {

class GambitVCHoverRow {
	
	function __construct() {
		add_filter( 'init', array( $this, 'createRowShortcodes' ), 999 );
		
		add_shortcode( 'hover_row', array( $this, 'createShortcode' ) );
		
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
		    "name" => __( 'Hover Row Background', GAMBIT_VC_PARALLAX_BG ),
		    "base" => "hover_row",
			"icon" => plugins_url( 'images/vc-hover.png', __FILE__ ),
			"description" => __( 'Add a hover bg to your row.', GAMBIT_VC_PARALLAX_BG ),
			"category" => __( 'Row Adjustments', GAMBIT_VC_PARALLAX_BG ),
		    "params" => array(
				array(
					"type" => "attach_image",
					"class" => "",
					"heading" => __( "Background Image", GAMBIT_VC_PARALLAX_BG ),
					"param_name" => "image",
					"description" => __( "Select your background image. <strong>Make sure that your image is of high resolution, we will resize the image to make it fit.</strong><br><strong>For optimal performance, try keeping your images close to 1600 x 900 pixels</strong>", GAMBIT_VC_PARALLAX_BG ),
				),
				array(
					"type" => "dropdown",
					"class" => "",
					"heading" => __( "Hover Type", GAMBIT_VC_PARALLAX_BG ),
					"param_name" => "type",
					"value" => array(
						__( "Move", GAMBIT_VC_PARALLAX_BG ) => "move",
						__( "Tilt", GAMBIT_VC_PARALLAX_BG ) => "tilt",
					),
					"description" => __( "Choose the type of effect when the row is hovered on.", GAMBIT_VC_PARALLAX_BG ),
				),
				array(
					"type" => "textfield",
					"class" => "",
					"heading" => __( "Move/Tilt Amount", GAMBIT_VC_PARALLAX_BG ),
					"param_name" => "amount",
					"value" => "30",
					"description" => __( "The move (pixels) or tilt (degrees) amount when the background is hovered on. For tilt types, the maximum allowed amount is <code>45 degrees</code>", GAMBIT_VC_PARALLAX_BG ),
				),
				array(
					"type" => "textfield",
					"class" => "",
					"heading" => __( "Opacity", GAMBIT_VC_PARALLAX_BG ),
					"param_name"  => "opacity",
					"value" => "100",
					"description" => __( "You may set the opacity level for your background. You can add a background color to your row and add an opacity here to tint your background. <strong>Please choose an integer value between 1 and 100.</strong>", GAMBIT_VC_PARALLAX_BG ),
				),
				array(
					"type" => "checkbox",
					"class" => "",
					"heading" => __( "Invert Move/Tilt Movement", GAMBIT_VC_PARALLAX_BG ),
					"param_name" => "inverted",
					"value" => array( __( "Check this to invert the movement of the effect with regards the direction of the mouse", GAMBIT_VC_PARALLAX_BG ) => "inverted" ),
				),
			),
		) );
	}
	
	
	public function createShortcode( $atts, $content = null ) {
        $defaults = array(
			'image' => '',
			'type' => 'move',
			'amount' => '30',
			'opacity' => '100',
			'inverted' => '',
        );
		if ( empty( $atts ) ) {
			$atts = array();
		}
		$atts = array_merge( $defaults, $atts );
		
		if ( empty( $atts['image'] ) ) {
			return '';
		}
		
        wp_enqueue_script( 'gambit_parallax', plugins_url( 'js/min/script-min.js', __FILE__ ), array( 'jquery' ), VERSION_GAMBIT_VC_PARALLAX_BG, true );
        wp_enqueue_style( 'gambit_parallax', plugins_url( 'css/style.css', __FILE__ ), array(), VERSION_GAMBIT_VC_PARALLAX_BG );

		// Jetpack issue, Photon is not giving us the image dimensions
		// This snippet gets the dimensions for us
		add_filter( 'jetpack_photon_override_image_downsize', '__return_true' );
		$imageInfo = wp_get_attachment_image_src( $atts['image'], 'full' );
		remove_filter( 'jetpack_photon_override_image_downsize', '__return_true' );
		
		$attachmentImage = wp_get_attachment_image_src( $atts['image'], 'full' );
		if ( empty( $attachmentImage ) ) {
			return '';
		}
		
		$bgImageWidth = $imageInfo[1];
		$bgImageHeight = $imageInfo[2];
		$bgImage = $attachmentImage[0];
		
		return  "<div class='gambit_hover_row' " .
			"data-bg-image='" . esc_url( $bgImage ) . "' " .
			"data-type='" . esc_attr( $atts['type'] ) . "' " .
			"data-amount='" . esc_attr( $atts['amount'] ) . "' " .
	        "data-opacity='" . esc_attr( $atts['opacity'] ) . "' " .
			"data-inverted='" . esc_attr( empty( $atts['inverted'] ) ? 'false' : 'true' ) . "' " .
			"style='display: none'></div>";
	}
}

new GambitVCHoverRow();

}