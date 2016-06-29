<?php
/*
Plugin Name: Smooth MouseWheel (Shared on themelord.com)
Description: Make your whole site scroll up and down smoothly when scrolling using the mouse wheel.
Author: Gambit Technologies
Version: 3.0.1
Author URI: http://gambit.ph
Plugin URI: http://codecanyon.net/item/smooth-mousewheel-wordpress-plugin/9225552
Text Domain: gambit-smooth-scrolling
Domain Path: /languages
SKU: SMOUSE
*/

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

defined( 'VERSION_GAMBIT_SMOOTH_SCROLLING_PLUGIN' ) or define( 'VERSION_GAMBIT_SMOOTH_SCROLLING_PLUGIN', '3.0.1' );

defined( 'GAMBIT_SMOOTH_SCROLLING_PLUGIN' ) or define( 'GAMBIT_SMOOTH_SCROLLING_PLUGIN', 'gambit-smooth-scrolling-plugin' );

require_once( 'class-admin-license.php' );
require_once( 'class-smooth-scroll.php' );

if ( ! class_exists('GambitSmoothScrollPlugin') ) {

	class GambitSmoothScrollPlugin {

		/**
		 * Hook into WordPress
		 *
		 * @return	void
		 * @since	1.0
		 */
		function __construct() {

			// Admin pointer reminders for automatic updates
			require_once( 'class-admin-pointers.php' );
			if ( class_exists( 'GambitAdminPointers' ) ) {
				new GambitAdminPointers( array (
					'pointer_name' => 'gambitsmouse', // This should also be placed in uninstall.php
					'header' => __( 'Automatic Updates', GAMBIT_SMOOTH_SCROLLING_PLUGIN ),
					'body' => __( 'Keep your Smooth MouseWheel plugin updated by entering your purchase code here.', GAMBIT_SMOOTH_SCROLLING_PLUGIN ),
				) );
			}
			
			// Add settings link
			add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), array( $this, 'pluginSettingsLink' ) );

			// Our translations
			add_action( 'plugins_loaded', array( $this, 'loadTextDomain' ), 1 );

			// Gambit links
			add_filter( 'plugin_row_meta', array( $this, 'pluginLinks' ), 10, 2 );

		}


		/**
		 * Loads the translations
		 *
		 * @return	void
		 * @since	1.0
		 */
		public function loadTextDomain() {
			load_plugin_textdomain( GAMBIT_SMOOTH_SCROLLING_PLUGIN, false, basename( dirname( __FILE__ ) ) . '/languages/' );
		}


		/**
		 * Adds plugin settings link
		 *
		 * @access	public
		 * @param	array $links The current set of links
		 * @since	1.1
		 **/
		public function pluginSettingsLink( $links ) {

			$settingsURL = admin_url( 'options-general.php' );

			array_unshift( $links, '<a href="' . $settingsURL . '">' . __( 'Settings', GAMBIT_SMOOTH_SCROLLING_PLUGIN ) . '</a>' );
			return $links;
		}


		/**
		 * Adds plugin links
		 *
		 * @access	public
		 * @param	array $plugin_meta The current array of links
		 * @param	string $plugin_file The plugin file
		 * @return	array The current array of links together with our additions
		 * @since	1.0
		 **/
		public function pluginLinks( $plugin_meta, $plugin_file ) {
			if ( $plugin_file == plugin_basename( __FILE__ ) ) {
				$pluginData = get_plugin_data( __FILE__ );

				$plugin_meta[] = sprintf( "<a href='%s' target='_blank'>%s</a>",
					"http://support.gambit.ph?utm_source=" . urlencode( $pluginData['Name'] ) . "&utm_medium=plugin_link",
					__( "Get Customer Support", GAMBIT_SMOOTH_SCROLLING_PLUGIN )
				);
				$plugin_meta[] = sprintf( "<a href='%s' target='_blank'>%s</a>",
					"https://gambit.ph/plugins?utm_source=" . urlencode( $pluginData['Name'] ) . "&utm_medium=plugin_link",
					__( "Get More Plugins", GAMBIT_SMOOTH_SCROLLING_PLUGIN )
				);
			}
			return $plugin_meta;
		}
	}

	new GambitSmoothScrollPlugin();
}