<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {die;}
/**
 *
 * @link              https://www.neagencija.si
 * @package           nea_block_features
 *
 * @wordpress-plugin
 * Plugin Name:       NEA dodatne funkcionalnosti Gutenberg blokov
 * Plugin URI:        https://github.com/gregamer/nea-block-features
 * GitHub Plugin URI: https://github.com/gregamer/nea-block-features
 * Primary Branch:    main
 * Description:       Front-end modifikacije za razlicne plugine in funkcionalnosti
 * Version:           0.1.1
 * Author:            Gregor Mercina
 * Author URI:        https://www.neagencija.si
 * Text Domain:       nea_block_features
 * Domain Path:       /lang
 */




$isAdmin = is_admin();

class nea_block_features {
	public $plugin_slug = "nea_block_features";
    public $plugin_name = "NEA dodatne funkcionalnosti Gutenberg blokov";
	//public $plugin_slug;
	
	public function __construct() {
		// load lang
		load_plugin_textdomain('nea_block_features', false, plugin_dir_path( __FILE__ ) . '/lang');

		// Hook into the admin
		add_action( 'admin_menu', array( $this, 'add_plugin_menu' ), 999 );
		add_action( 'admin_init', array( $this, 'register_settings' ) );
		// add_action( 'admin_menu', array( $this, 'add_gfonts_menu' ) );
	}

	public function add_plugin_menu() {	
		// Add the menu item and page
		$page_title = $this->plugin_name;
		$menu_title = $this->plugin_name;
		$capability = 'administrator';
		$slug 		= $this->plugin_slug;
		$callback 	= array( $this, 'create_admin_page' );
		$icon 		= 'dashicons-plugins';
		$position 	= 100;
		
		$this->ensure_neagencija_menu();
		add_submenu_page('neagencija', $page_title, $menu_title, $capability, $slug, $callback );
	}

	private function ensure_neagencija_menu() {
		global $admin_page_hooks;
		if ( empty( $admin_page_hooks['neagencija'] ) ) {
			add_menu_page(
				__('neAGENCIJA', 'nea_block_features'),
				__('neAGENCIJA', 'nea_block_features'),
				'manage_options',
				'neagencija',
				array($this, 'render_neagencija_root'),
				'dashicons-admin-generic',
				2
			);
		}
	}

	public function render_neagencija_root() {
		echo '<div class="wrap"><h1>neAGENCIJA</h1><p>Izberite orodje iz podmenija.</p></div>';
	}
		public function create_admin_page() {
			include( plugin_dir_path( __FILE__ ) . '/options/_main.php');
		}


	public function settings_link($links){
		$settigns_link = '<a href="admin.php?page='.$this->plugin_slug.'">Settings</a>';
		array_push($links,$settigns_link);
		return $links;
	}


	public function register_settings() {
		register_setting( 'nea_block_features_options', 'nea_block_features_enable_block_visibility' );
		register_setting( 'nea_block_features_options', 'nea_block_features_enable_mobile_reverse' );
		register_setting( 'nea_block_features_options', 'nea_block_features_mobile_breakpoint' );
		register_setting( 'nea_block_features_options', 'nea_block_features_enable_column_link' );
		register_setting( 'nea_block_features_options', 'nea_block_features_enable_reusable_blocks' );
	}
	
}
new nea_block_features();

include( plugin_dir_path( __FILE__ ) . '/includes/_media.php');

if ( get_option( 'nea_block_features_enable_block_visibility', '1' ) ) {
	include( plugin_dir_path( __FILE__ ) . '/includes/_block-visibility-options.php');
}

if ( get_option( 'nea_block_features_enable_mobile_reverse', '1' ) ) {
	include( plugin_dir_path( __FILE__ ) . '/includes/_block-columns-mobile-reverse.php');
}

if ( get_option( 'nea_block_features_enable_column_link', '1' ) ) {
	include( plugin_dir_path( __FILE__ ) . '/includes/_block-column-link.php');
}

if ( get_option( 'nea_block_features_enable_reusable_blocks', '1' ) ) {
	include( plugin_dir_path( __FILE__ ) . '/includes/_shortcode-pattern.php');
}

?>