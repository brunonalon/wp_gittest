<?php

if ( ! class_exists('GambitSmoothScroll') ) {

	class GambitSmoothScroll {
		
		const SETTINGS_PAGE = 'general';
		const SETTINGS_SECTION = 'gambit_smoothscroll';
		const OPTION_NAME = 'gambit_smoothscroll_options';
		
		function __construct() {

			// Loads the smooth scrolling plugin throughout
			add_action( 'wp_enqueue_scripts', array( $this, 'enqueueSmoothScrollScript' ) );

			// Settings page
			add_action( 'admin_init', array( $this, 'createGeneralSettings' ) );

			// Print our settings
			add_action( 'wp_head', array( $this, 'printScrollingSettings' ) );
			
		}


		/**
		 * Includes the smooth scrolling script
		 *
		 * @return	void
		 * @since	1.0
		 */
		public function enqueueSmoothScrollScript() {
			wp_enqueue_script( __CLASS__, plugins_url( 'smooth_scroll/js/min/gambit-smoothscroll-min.js', __FILE__ ), array(), VERSION_GAMBIT_SMOOTH_SCROLLING_PLUGIN );
		}


		/**
		 * Create the options for the plugin
		 *
		 * @return	void
		 * @since	1.1
		 **/
		public function createGeneralSettings() {
			add_settings_section(
				self::SETTINGS_SECTION,
				__( 'Smooth Scroll Settings', GAMBIT_SMOOTH_SCROLLING_PLUGIN ),
				false,
				self::SETTINGS_PAGE
			);

			register_setting(
				self::SETTINGS_PAGE,
				self::OPTION_NAME,
				array( $this, 'validateOptions' )
			);

			add_settings_field(
				'gambit_smoothscroll_speed_new',
				__( 'Scroll Speed', GAMBIT_SMOOTH_SCROLLING_PLUGIN ),
				array( $this, 'displaySpeedOption' ),
				self::SETTINGS_PAGE,
				self::SETTINGS_SECTION
			);

			add_settings_field(
				'gambit_smoothscroll_amount',
				__( 'Scroll Tick Amount', GAMBIT_SMOOTH_SCROLLING_PLUGIN ),
				array( $this, 'displayAmountOption' ),
				self::SETTINGS_PAGE,
				self::SETTINGS_SECTION
			);
		}


		/**
		 * Display our speed option
		 *
		 * @return	void
		 * @since	1.1
		 **/
		public function displaySpeedOption() {
			$options = get_option( self::OPTION_NAME );
			$value = empty( $options['speed'] ) ? '900' : $options['speed'];

			?>
			<input id='speed' name='<?php echo self::OPTION_NAME ?>[speed]' type='number' min='400' max='2000' step='100' class='small-text' value='<?php echo esc_attr( $value ); ?>' placeholder='12'/>
			<?php _e( 'The mouse wheel scroll speed. A higher number will scroll the page slower.', GAMBIT_SMOOTH_SCROLLING_PLUGIN ) ?>
			<?php
		}


		/**
		 * Display our amountosition rate option
		 *
		 * @return	void
		 * @since	1.1
		 **/
		public function displayAmountOption() {
			$options = get_option( self::OPTION_NAME );
			$value = empty( $options['amount'] ) ? '150' : $options['amount'];

			?>
			<input id='amount' name='<?php echo self::OPTION_NAME ?>[amount]' type='number' min='50' max='300' step='1' value='<?php echo esc_attr( $value ); ?>' placeholder='0.94' />
			<?php _e( 'The scroll amount per mouse wheel tick. A larger number here will scroll the page farther per wheel.', GAMBIT_SMOOTH_SCROLLING_PLUGIN ) ?>
			<?php
		}


		/**
		 * Validate & sanitize our input options
		 *
		 * @return	void
		 * @since	1.1
		 **/
		public function validateOptions( $input ) {
			$valid = array();

			$valid['speed'] = sanitize_text_field( $input['speed'] );
			if ( ! is_numeric( $valid['speed'] ) && $valid['speed'] != '' ) {
				add_settings_error(
					'gambit_smoothscroll_speed_new',
					'gambit_smoothscroll_speed_new_error',
					__( 'Scroll speed should be a number', GAMBIT_SMOOTH_SCROLLING_PLUGIN ),
					'error'
				);
			}

			$valid['amount'] = sanitize_text_field( $input['amount'] );
			if ( ! is_numeric( $valid['amount'] ) && $valid['amount'] != '' ) {
				add_settings_error(
					'gambit_smoothscroll_amount',
					'gambit_smoothscroll_amount_error',
					__( 'Scroll amount should be a number', GAMBIT_SMOOTH_SCROLLING_PLUGIN ),
					'error'
				);
			}

			return $valid;
		}


		/**
		 * Prints out the smooth scrolling settings
		 *
		 * @return	void
		 * @since	1.1
		 **/
		public function printScrollingSettings() {
			$options = get_option( self::OPTION_NAME );

			$script = '';
			if ( ! empty( $options['speed'] ) ) {
				$script .= "speed: " . esc_attr( $options['speed'] );
			}
			if ( ! empty( $options['amount'] ) ) {
				$script .= ! empty( $script ) ? ',' : '';
				$script .= "amount: " . esc_attr( $options['amount'] );
			}
			if ( ! empty( $script ) ) {
				$script = "{" . $script . "}";
			}
		
			echo "<script>new GambitSmoothScroll($script);</script>";
		}
	}
	
	new GambitSmoothScroll();
	
}