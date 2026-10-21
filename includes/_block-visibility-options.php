<?php // Silence is golden
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {die;}


class Block_Visibility_Options {

    public function __construct() {
        add_action( 'enqueue_block_editor_assets', [ $this, 'enqueue_block_editor_assets' ] );
        add_action( 'enqueue_block_assets', [ $this, 'enqueue_frontend_assets' ] );
    }

    public function enqueue_block_editor_assets() {
        wp_enqueue_script(
            'block-visibility-options',
            plugin_dir_url( __FILE__ ) . 'block-visibility-options.js',
            [ 'wp-blocks', 'wp-element', 'wp-editor', 'wp-components' ],
            '1.0.0',
            true
        );
    }

    public function enqueue_frontend_assets() {
        wp_enqueue_style( 'block-visibility-options');
	    wp_register_style('block-visibility-options', plugins_url('/nea-block-features/assets/block-visibility-options.css'), array(), false, 'all');
    }
}

new Block_Visibility_Options();

