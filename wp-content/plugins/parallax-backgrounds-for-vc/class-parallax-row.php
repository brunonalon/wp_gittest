<?php

if ( ! class_exists( 'GambitVCParallaxRow' ) ) {

class GambitVCParallaxRow {
	
	function __construct() {
		add_filter( 'init', array( $this, 'createRowShortcodes' ), 999 );
		
		add_shortcode( 'parallax_row', array( $this, 'createShortcode' ) );
		
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
		    "name" => __( 'Parallax Row Background', GAMBIT_VC_PARALLAX_BG ),
		    "base" => "parallax_row",
			"icon" => plugins_url( 'images/vc-parallax.png', __FILE__ ),
			"description" => __( 'Add a parallax bg to your row.', GAMBIT_VC_PARALLAX_BG ),
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
					"heading" => __( "Background Image Parallax", GAMBIT_VC_PARALLAX_BG ),
					"param_name" => "direction",
					"value" => array(
						"Up" => "up",
						"Down" => "down",
						"Left" => "left",
						"Right" => "right",
						"Fixed" => "fixed",
					),
					"description" => __( "Choose the direction of your parallax. <strong>Note that browsers render fixed directions very slow since they aren't hardware accelerated.</strong>", GAMBIT_VC_PARALLAX_BG ),
				),
				array(
					"type" => "textfield",
					"class" => "",
					"heading" => __( "Parallax Speed", GAMBIT_VC_PARALLAX_BG ),
					"param_name" => "speed",
					"value" => "0.3",
					"description" => __( "The movement speed, value should be between 0.1 and 1.0. A lower number means slower scrolling speed.", GAMBIT_VC_PARALLAX_BG ),
				),
				array(
					"type" => "dropdown",
					"class" => "",
					"heading" => __( "Background Style / Repeat", GAMBIT_VC_PARALLAX_BG ),
					"param_name" => "background_repeat",
					"value" => array(
						__( "Cover Whole Row (covers the whole row)", GAMBIT_VC_PARALLAX_BG ) => "",
						__( "Repeating Image Pattern", GAMBIT_VC_PARALLAX_BG ) => "repeat",
					),
					"description" => __( "Select whether the background image above should cover the whole row, or whether the image is a background seamless pattern.", GAMBIT_VC_PARALLAX_BG ),
				),
				array(
					"type" => "dropdown",
					"class" => "",
					"heading" => __( "Background Position / Alignment", GAMBIT_VC_PARALLAX_BG ),
					"param_name" => "background_position",
					"value" => array(
						__( "Centered", GAMBIT_VC_PARALLAX_BG ) => "",
						__( "Left (only applies to up, down parallax or fixed)", GAMBIT_VC_PARALLAX_BG ) => "left",
						__( "Right (only applies to up, down parallax or fixed)", GAMBIT_VC_PARALLAX_BG ) => "right",
						__( "Top (only applies to left or right parallax)", GAMBIT_VC_PARALLAX_BG ) => "top",
						__( "Bottom (only applies to left or right parallax)", GAMBIT_VC_PARALLAX_BG ) => "bottom",
					),
					"description" => __( "The alignment of the background / parallax image. Note that this most likely will only be noticeable in smaller screens, if the row is large enough, the image will most likely be fully visible. Use this if you want to ensure that a certain area will always be visible in your parallax in smaller screens.", GAMBIT_VC_PARALLAX_BG ),
				),
				array(
					"type" => "textfield",
					"class" => "",
					"heading" => __( "Opacity", GAMBIT_VC_PARALLAX_BG ),
					"param_name"  => "opacity",
					"value" => "100",
					"description" => __( "You may set the opacity level for your parallax. You can add a background color to your row and add an opacity here to tint your parallax. <strong>Please choose an integer value between 1 and 100.</strong>", GAMBIT_VC_PARALLAX_BG ),
				),
				array(
					"type" => "checkbox",
					"class" => "",
					"param_name" => "enable_mobile",
					"value" => array( __( "Check this to enable the parallax effect in mobile devices", GAMBIT_VC_PARALLAX_BG ) => "parallax-enable-mobile" ),
					"description" => __( "Parallax effects would most probably cause slowdowns when your site is viewed in mobile devices. If the device width is less than 980 pixels, then it is assumed that the site is being viewed in a mobile device.", GAMBIT_VC_PARALLAX_BG ),
				),
			),
		) );
	}
	
	
	public function createShortcode( $atts, $content = null ) {
        $defaults = array(
			'image' => '',
			'direction' => 'up',
			'speed' => '0.3',
			'background_repeat' => '',
			'background_position' => '',
			'opacity' => '100',
			'enable_mobile' => '',
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
		
		return  "<div class='gambit_parallax_row' " .
			"data-bg-align='" . esc_attr( $atts['background_position'] ) . "' " .
			"data-direction='" . esc_attr( $atts['direction'] ) . "' " .
	        "data-opacity='" . esc_attr( $atts['opacity'] ) . "' " .
			"data-velocity='" . esc_attr( (float) $atts['speed'] * -1 ) . "' " .
			"data-mobile-enabled='" . esc_attr( $atts['enable_mobile'] ) . "' " .
			"data-bg-height='" . esc_attr( $bgImageHeight ) . "' " .
			"data-bg-width='" . esc_attr( $bgImageWidth ) . "' " .
			"data-bg-image='" . esc_attr( $bgImage ) . "' " .
			"data-bg-repeat='" . esc_attr( empty( $atts['background_repeat'] ) ? 'false' : 'true' ) . "' " .
			"style='display: none'></div>";
	}
}

new GambitVCParallaxRow();

}