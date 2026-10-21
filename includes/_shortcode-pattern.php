<?php // Silence is golden
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {die;}

function nea_block_features_reusable_block_shortcode( $atts ) {
    $a = shortcode_atts( array(
		'id' => false,
        'slug' => false
	), $atts );

    $block_id = $a['id'];
    $block_slug = $a['slug'];

    // get id from slug
    if( !$block_id && $block_slug){
        $post = get_page_by_path($block_slug, OBJECT, 'wp_block');
        // printR($post);
        if ($post) {
            $block_id = $post->ID;
        }
    }
    
    // check if wpml is active and get translation id
    if(defined( 'ICL_SITEPRESS_VERSION')){
        $block_id = apply_filters( 'wpml_object_id', $block_id, 'wp_block' );
    }
    
    if ( empty( $block_id ) || $block_id == false ) {
        return;
    }

    $content = get_post_field( 'post_content', $block_id );
    $content = apply_filters( 'the_content', $content );
    return $content;
}
add_shortcode( 'show_pattern', 'nea_block_features_reusable_block_shortcode' );