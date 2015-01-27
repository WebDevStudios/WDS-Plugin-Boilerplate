<?php
/**
 * Plugin Name: WebDevStudios Plugin Boilerplate
 * Plugin URI: http://webdevstudios.com
 * Description: A plugin boilerplate for wd_s.
 * Author: WebDevStudios
 * Author URI: http://webdevstudios.com
 * Version: 1.0.0
 * License: GPLv2
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'WDS_Plugin_Boilerplate' ) ) {

	class WDS_Plugin_Boilerplate {

		/**
		 * Construct function to get things started.
		 */
		public function __construct() {
			// Setup some base variables for the plugin
			$this->basename       = plugin_basename( __FILE__ );
			$this->directory_path = plugin_dir_path( __FILE__ );
			$this->directory_url  = plugins_url( dirname( $this->basename ) );

			// Include any required files
			add_action( 'init', array( $this, 'includes' ) );

			// Load Textdomain
			load_plugin_textdomain( '_s', false, dirname( $this->basename ) . '/languages' );

			// Activation/Deactivation Hooks
			register_activation_hook( __FILE__, array( $this, 'activate' ) );
			register_deactivation_hook( __FILE__, array( $this, 'deactivate' ) );

			// Make sure we have our requirements, and disable the plugin if we do not have them.
			add_action( 'admin_notices', array( $this, 'maybe_disable_plugin' ) );

		}

		/**
		 * Include our plugin dependencies.
		 */
		public function includes() {
			if ( $this->meets_requirements() ) {
			}
		}

		/**
		 * Register CPTs & taxonomies.
		 */
		public function do_hooks() {
			if ( $this->meets_requirements() ) {
				add_action( 'init', array( $this, 'register_post_types' ), 9 );
				add_action( 'init', array( $this, 'register_taxonomies' ), 9 );
			}
		}

		/**
		 * Activation hook for the plugin.
		 */
		public function activate() {
			// If requirements are available, run our activation functions
			if ( $this->meets_requirements() ) {
			}
		}

		/**
		 * Deactivation hook for the plugin.
		 */
		public function deactivate() {

		}

		/**
		 * Check that all plugin requirements are met
		 *
		 * @return boolean
		 */
		public static function meets_requirements() {
			// Make sure we have register_via_cpt_core so we can use it
			if ( ! function_exists( 'register_via_cpt_core' ) ) {
				return false;
			}

			// Make sure we have register_via_taxonomy_core so we can use it
			if ( ! function_exists( 'register_via_taxonomy_core' ) ) {
				return false;
			}

			// We have met all requirements
			return true;
		}

		/**
		 * Check if the plugin meets requirements and
		 * disable it if they are not present.
		 */
		public function maybe_disable_plugin() {
			if ( ! $this->meets_requirements() ) {
				// Display our error
				echo '<div id="message" class="error">';
				echo '<p>' . sprintf( __( 'Sample plugin is missing requirements and has been <a href="%s">deactivated</a>. Please make sure all requirements are available.', '_s' ), admin_url( 'plugins.php' ) ) . '</p>';
				echo '</div>';

				// Deactivate our plugin
				deactivate_plugins( $this->basename );
			}
		}

		/**
		 * Register custom post-types.
		 */
		public function register_post_types() {
			register_via_cpt_core(
				array(
					__( 'Sample', '_s' ),  // Single
					__( 'Samples', '_s' ), // Plural
					'_s-samples'           // Registered slug
				),
				array(
					'menu_icon' => 'dashicons-smiley' // Choose a custom dashicon (http://melchoyce.github.io/dashicons/) or pass an image URI
				)
			);
		}

		/**
		 * Register custom taxonomies.
		 */
		public function register_taxonomies() {
			register_via_taxonomy_core(
				array(
					__( 'Sample Tag', '_s' ),  // Single
					__( 'Sample Tags', '_s' ), // Plural
					'_s-sample-tags'           // Registered slug
				),
				array(
					'hierarchical' => false
				),
				array(
					'_s-samples'
				)
			);
		}
	}

	$_GLOBALS['wds_plugin_boilerplate'] = new WDS_Plugin_Boilerplate;
	$_GLOBALS['wds_plugin_boilerplate']->do_hooks();
}
