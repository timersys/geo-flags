<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the dashboard.
 *
 * @link       http://wp.timersys.com/geotargeting/
 * @since      1.0.0
 *
 * @package    GeoTarget
 * @subpackage GeoTarget/includes
 */
use GeotFunctions\Setting\GeotSettings;

/**
 * The core plugin class.
 *
 * This is used to define internationalization, dashboard-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    GeoTarget
 * @subpackage GeoTarget/includes
 * @author     Your Name <email@example.com>
 */
class GeoFlags {

	/**
	 * @var GeoTarget_Public $public
	 */
	public $public;


	/**
	 * Plugin Instance
	 * @since 1.0.0
	 * @var The Geot plugin instance
	 */
	protected static $_instance = null;

	private $admin;

	/**
	 * Main Geot Instance
	 *
	 * Ensures only one instance of WSI is loaded or can be loaded.
	 *
	 * @since 1.0.0
	 * @static
	 * @see GEOT()
	 * @return Geot - Main instance
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	/**
	 * Cloning is forbidden.
	 * @since 1.0.0
	 */
	public function __clone() {
		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'wsi' ), '2.1' );
	}

	/**
	 * Unserializing instances of this class is forbidden.
	 * @since 1.0.0
	 */
	public function __wakeup() {
		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'wsi' ), '2.1' );
	}


	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the Dashboard and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {


		$this->load_dependencies();
		GeotSettings::init();

		$this->version = GEOF_VERSION;
		$this->opts = geot_settings();
		$this->define_public_hooks();
		$this->register_shortcodes();
		$this->define_admin_hooks();
	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		require plugin_dir_path( dirname( __FILE__ ) ) . 'vendor/autoload.php';

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'lib/functions.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'lib/class-geotflags-ajax-shortcodes.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'lib/class-geotflags-shortcodes.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'lib/GeoFlagsUpdates.php';

	}


	/**
	 * Register all of the hooks related to the dashboard functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {
		// License and Updates
		add_action( 'admin_init' , [ GeoFlagsUpdates::class , 'handle_updates'], 0 );

	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		add_action( 'wp_enqueue_scripts', [$this, 'enqueue_styles'] );

	}

	/**
	 * Styles needed
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {
		wp_enqueue_style( 'geotf',  plugins_url( 'assets/css/flag-icon.min.css', dirname(__FILE__) ), null, GEOF_VERSION , 'all');
	}

	/**
	 * Register shortcodes
	 * @access   private
	 */
	private function register_shortcodes()
	{
		$shortcodes = new GeoFlags_Shortcodes();
		$ajax_shortcodes = new GeoFlags_Ajax_Shortcodes();

		add_action( 'init', [$shortcodes, 'register_shortcodes'] );
		add_action( 'init', [$ajax_shortcodes, 'register_shortcodes'] );

	}

}
