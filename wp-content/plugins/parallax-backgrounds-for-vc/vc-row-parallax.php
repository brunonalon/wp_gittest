<?php
/*
Plugin Name: Parallax Backgrounds for VC (shared on themelord.com)
Description: Adds new options to Visual Composer for adding parallax scrolling images & video backgrounds.
Author: Benjamin Intal - Gambit
Version: 4.1
Author URI: http://gambit.ph
Plugin URI: http://gambit.ph/downloads/parallax-backgrounds-for-vc/
Text Domain: gambit-vc-parallax-bg
Domain Path: /languages
SKU: PRLX
*/

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

defined( 'VERSION_GAMBIT_VC_PARALLAX_BG' ) or define( 'VERSION_GAMBIT_VC_PARALLAX_BG', '4.1' );
defined( 'GAMBIT_VC_PARALLAX_BG' ) or define( 'GAMBIT_VC_PARALLAX_BG', 'gambit-vc-parallax-bg' );

// Disable rating for the Smooth Mousewheel plugin since we're including the plugin with parallax
defined( 'GAMBIT_DISABLE_SMOOTH_SCROLLING_RATING' ) or define( 'GAMBIT_DISABLE_SMOOTH_SCROLLING_RATING', '1' );

require_once( 'class-admin-license.php' );

require_once( 'inc/otf_regen_thumbs.php' );
require_once( 'class-fullwidth-row.php' );
require_once( 'class-fullheight-row.php' );
require_once( 'class-parallax-row.php' );
require_once( 'class-video-row.php' );
require_once( 'class-hover-row.php' );
require_once( 'class-background-row.php' );

if ( ! class_exists( 'GambitVCParallaxBackgrounds' ) ) {

	/**
	 * Parallax Background Class
	 *
	 * @since	1.0
	 */
	class GambitVCParallaxBackgrounds {

		/**
		 * Constructor, checks for Visual Composer and defines hooks
		 *
		 * @return	void
		 * @since	1.0
		 */
		function __construct() {

			// Admin pointer reminders for automatic updates
			require_once( 'class-admin-pointers.php' );
			if ( class_exists( 'GambitAdminPointers' ) ) {
				new GambitAdminPointers( array (
					'pointer_name' => 'gambitprlx', // This should also be placed in uninstall.php
					'header' => __( 'Automatic Updates', GAMBIT_VC_PARALLAX_BG ),
					'body' => __( 'Keep your Parallax Backgrounds for VC plugin updated by entering your purchase code here.', GAMBIT_VC_PARALLAX_BG ),
				) );
			}
			
			// Our translations
			add_action( 'plugins_loaded', array( $this, 'loadTextDomain' ), 1 );

			// Gambit links
			add_filter( 'plugin_row_meta', array( $this, 'pluginLinks' ), 10, 2 );

			// Add plugin specific filters and actions here
			add_action( 'wp_head', array( $this, 'ie9Detector' ) );
		}

		public function ie9Detector() {
			echo "<!--[if IE 9]> <script>var _gambitParallaxIE9 = true;</script> <![endif]-->";
		}


		/**
		 * Loads the translations
		 *
		 * @return	void
		 * @since	1.0
		 */
		public function loadTextDomain() {
			load_plugin_textdomain( GAMBIT_VC_PARALLAX_BG, false, basename( dirname( __FILE__ ) ) . '/languages/' );
		}


		/**
		 * Adds plugin links
		 *
		 * @access	public
		 * @param	array $plugin_meta The current array of links
		 * @param	string $plugin_file The plugin file
		 * @return	array The current array of links together with our additions
		 * @since	2.6
		 **/
		public function pluginLinks( $plugin_meta, $plugin_file ) {
			if ( $plugin_file == plugin_basename( __FILE__ ) ) {
				$pluginData = get_plugin_data( __FILE__ );

				$plugin_meta[] = sprintf( "<a href='%s' target='_blank'>%s</a>",
					"http://support.gambit.ph?utm_source=" . urlencode( $pluginData['Name'] ) . "&utm_medium=plugin_link",
					__( "Get Customer Support", GAMBIT_VC_PARALLAX_BG )
				);
				$plugin_meta[] = sprintf( "<a href='%s' target='_blank'>%s</a>",
					"http://codecanyon.net/user/GambitTech/portfolio?utm_source=" . urlencode( $pluginData['Name'] ) . "&utm_medium=plugin_link",
					__( "Get More Plugins", GAMBIT_VC_PARALLAX_BG )
				);
			}
			return $plugin_meta;
		}
	}


	new GambitVCParallaxBackgrounds();
}