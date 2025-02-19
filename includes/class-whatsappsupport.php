<?php

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    WhatsAppSupport
 * @subpackage WhatsAppSupport/includes
 */
class WhatsAppSupport {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      WhatsAppSupport_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		if ( defined( 'WHATSAPPSUPPORT_VERSION' ) ) {
			$this->version = WHATSAPPSUPPORT_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'whatsappsupport';

		$this->load_dependencies();
		$this->set_locale();
		is_admin() ? $this->define_admin_hooks() : $this->define_public_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - WhatsAppSupport_Loader. Orchestrates the hooks of the plugin.
	 * - WhatsAppSupport_i18n. Defines internationalization functionality.
	 * - WhatsAppSupport_Admin. Defines all hooks for the admin area.
	 * - WhatsAppSupport_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-whatsappsupport-loader.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-whatsappsupport-i18n.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-whatsappsupport-admin.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-whatsappsupport-public.php';

		$this->loader = new WhatsAppSupport_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the WhatsAppSupport_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new WhatsAppSupport_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new WhatsAppSupport_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_init',            $plugin_admin, 'settings_init' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
		$this->loader->add_action( 'admin_menu',            $plugin_admin, 'add_menu' );
		$this->loader->add_action( 'add_meta_boxes',        $plugin_admin, 'add_meta_boxes' );
		$this->loader->add_action( 'save_post',             $plugin_admin, 'save_post' );

		$this->loader->add_filter( "plugin_action_links_whatsapp-support/{$this->plugin_name}.php", $plugin_admin, 'settings_link' );

	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new WhatsAppSupport_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp',                 $plugin_public, 'get_settings' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );
		$this->loader->add_action( 'wp_footer',          $plugin_public, 'footer_html' );

	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    WhatsAppSupport_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}
