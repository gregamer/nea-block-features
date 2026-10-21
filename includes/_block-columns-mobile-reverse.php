<?php // Silence is golden
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {die;}


class Block_Columns_Mobile_Reverse {

    public function __construct() {
        add_action( 'enqueue_block_editor_assets', [ $this, 'enqueue_block_editor_assets' ] );
        add_action( 'enqueue_block_assets', [ $this, 'enqueue_frontend_assets' ] );
    }

    public function enqueue_block_editor_assets() {
        wp_enqueue_script(
            'block-columns-mobile-reverse',
            plugin_dir_url( __FILE__ ) . 'block-columns-mobile-reverse.js',
            [ 'wp-blocks', 'wp-element', 'wp-editor', 'wp-components' ],
            '1.0.0',
            true
        );
    }

    public function enqueue_frontend_assets() {
        wp_register_style(
            'block-columns-mobile-reverse',
            plugins_url('/nea-block-features/assets/block-columns-mobile-reverse.css'),
            array(),
            false,
            'all'
        );
        wp_enqueue_style( 'block-columns-mobile-reverse' );

        $mobile_breakpoint = (int) get_option( 'nea_block_features_mobile_breakpoint', 781 );
        if ( $mobile_breakpoint < 1 ) {
            $mobile_breakpoint = 781;
        }

        $dynamic_css = "
@media (max-width: {$mobile_breakpoint}px) {
    .wp-block-columns.mobile-reverse,
    .wp-block-media-text.mobile-reverse {
        display: flex;
        flex-direction: column-reverse;
    }
}
";
        wp_add_inline_style( 'block-columns-mobile-reverse', $dynamic_css );
    }
}

new Block_Columns_Mobile_Reverse();

