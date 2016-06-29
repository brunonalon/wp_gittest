<?php

if ( ! class_exists( 'GambitVCBackgroundRow' ) ) {

class GambitVCBackgroundRow {
	
	function __construct() {
		add_filter( 'init', array( $this, 'createRowShortcodes' ), 999 );
		
		add_shortcode( 'background_row', array( $this, 'createShortcode' ) );
		
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
		    "name" => __( 'Row Background', GAMBIT_VC_PARALLAX_BG ),
		    "base" => "background_row",
			"icon" => plugins_url( 'images/vc-background.png', __FILE__ ),
			"description" => __( 'Add a background image or color to your row.', GAMBIT_VC_PARALLAX_BG ),
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
					"type" => "colorpicker",
					"class" => "",
					"heading" => __( "Background Color", GAMBIT_VC_PARALLAX_BG ),
					"param_name" => "color",
					"value" => '',
					"description" => __( "Choose a background color.", GAMBIT_VC_PARALLAX_BG ),
				),
				array(
					"type" => "dropdown",
					"class" => "",
					"heading" => __( "Background Position", GAMBIT_VC_PARALLAX_BG ),
					"param_name" => "background_position",
					"value" => array(
						__( "Center", GAMBIT_VC_PARALLAX_BG ) => "center",
						__( "Theme Default", GAMBIT_VC_PARALLAX_BG ) => "",
						__( "Left Top", GAMBIT_VC_PARALLAX_BG ) => "left top",
						__( "Left Center", GAMBIT_VC_PARALLAX_BG ) => "left center",
						__( "Left Bottom", GAMBIT_VC_PARALLAX_BG ) => "left bottom",
						__( "Right Top", GAMBIT_VC_PARALLAX_BG ) => "right top",
						__( "Right Center", GAMBIT_VC_PARALLAX_BG ) => "right center",
						__( "Right Bottom", GAMBIT_VC_PARALLAX_BG ) => "right bottom",
						__( "Center Top", GAMBIT_VC_PARALLAX_BG ) => "center top",
						__( "Center Bottom", GAMBIT_VC_PARALLAX_BG ) => "center bottom",
					),
				),
				array(
					"type" => "dropdown",
					"class" => "",
					"heading" => __( "Background Image Size", GAMBIT_VC_PARALLAX_BG ),
					"param_name" => "background_size",
					"value" => array(
						__( "Cover", GAMBIT_VC_PARALLAX_BG ) => "cover",
						__( "Theme Default", GAMBIT_VC_PARALLAX_BG ) => "",
						__( "Contain", GAMBIT_VC_PARALLAX_BG ) => "contain",
						__( "No Repeat", GAMBIT_VC_PARALLAX_BG ) => "no-repeat",
						__( "Repeat", GAMBIT_VC_PARALLAX_BG ) => "repeat",
					),
				),
			),
		) );
	}
	
	
	public function createShortcode( $atts, $content = null ) {
        $defaults = array(
			'image' => '',
			'color' => '',
			'background_size' => 'cover',
			'background_position' => 'center',
        );
		if ( empty( $atts ) ) {
			$atts = array();
		}
		$atts = array_merge( $defaults, $atts );
		
		if ( empty( $atts['image'] ) && empty( $atts['color'] ) ) {
			return '';
		}
		
        wp_enqueue_script( 'gambit_parallax', plugins_url( 'js/min/script-min.js', __FILE__ ), array( 'jquery' ), VERSION_GAMBIT_VC_PARALLAX_BG, true );
		
		$attachmentImage = wp_get_attachment_image_src( $atts['image'], 'full' );
		$imageURL = '';
		if ( ! empty( $attachmentImage ) ) {
			$imageURL = $attachmentImage[0];
		}
		
		$style = 'display: none;';
		if ( ! empty( $imageURL ) ) {
			$style .= 'background-image: url(' . esc_url( $imageURL ) . ');';
		}
		if ( ! empty( $atts['color'] ) ) {
			$style .= 'background-color: ' . esc_attr( $atts['color'] ) . ';';
		}
		if ( ! empty( $atts['background_size'] ) ) {
			if ( in_array( $atts['background_size'], array( 'cover', 'contain' ) ) ) {
				$style .= 'background-size: ' . esc_attr( $atts['background_size'] ) . ';';
			} else {
				$style .= 'background-repeat: ' . esc_attr( $atts['background_size'] ) . ';';
			}
		}
		if ( ! empty( $atts['background_position'] ) ) {
			$style .= 'background-position: ' . esc_attr( $atts['background_position'] ) . ';';
		}
		
		return  "<div class='gambit_background_row' style='{$style}'></div>";
	}
}

new GambitVCBackgroundRow();

}