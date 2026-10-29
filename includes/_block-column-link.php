<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {die;}

if ( class_exists( 'WP_HTML_Tag_Processor' ) ) {
	class Nea_Column_Link_Tag_Processor extends WP_HTML_Tag_Processor {
		public function token_offset() {
			try {
				$property = new ReflectionProperty( 'WP_HTML_Tag_Processor', 'token_starts_at' );
				if ( PHP_VERSION_ID < 80100 ) {
					$property->setAccessible( true );
				}
				$offset = $property->getValue( $this );
				return is_int( $offset ) ? $offset : null;
			} catch ( Throwable $error ) {
				return null;
			}
		}
	}
}

class Block_Column_Link {

	public function __construct() {
		add_filter( 'register_block_type_args', array( $this, 'register_attribute' ), 10, 2 );
		add_action( 'enqueue_block_editor_assets', array( $this, 'enqueue_editor_assets' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_frontend_assets' ) );
		add_filter( 'render_block_core/column', array( $this, 'render' ), 10, 2 );
	}

	public function register_attribute( $args, $block_type ) {
		if ( 'core/column' !== $block_type ) {
			return $args;
		}

		if ( ! isset( $args['attributes'] ) || ! is_array( $args['attributes'] ) ) {
			$args['attributes'] = array();
		}

		$args['attributes']['neaColumnLink'] = array(
			'type'    => 'object',
			'default' => array(
				'url'           => '',
				'title'         => '',
				'opensInNewTab' => false,
				'nofollow'      => false,
			),
		);

		return $args;
	}

	public function enqueue_editor_assets() {
		$script_path = plugin_dir_path( __FILE__ ) . 'block-column-link.js';

		wp_enqueue_script(
			'nea-block-features-column-link',
			plugin_dir_url( __FILE__ ) . 'block-column-link.js',
			array( 'wp-blocks', 'wp-hooks', 'wp-element', 'wp-compose', 'wp-components', 'wp-block-editor', 'wp-i18n' ),
			file_exists( $script_path ) ? filemtime( $script_path ) : false,
			true
		);

		// Core blocks register in wp-block-library. This script must run first,
		// otherwise the extra attribute is stripped before the post is parsed.
		$scripts = wp_scripts();
		if ( isset( $scripts->registered['wp-block-library'] ) ) {
			$deps = $scripts->registered['wp-block-library']->deps;
			if ( ! in_array( 'nea-block-features-column-link', $deps, true ) ) {
				$scripts->registered['wp-block-library']->deps[] = 'nea-block-features-column-link';
			}
		}
	}

	public function enqueue_frontend_assets() {
		$css_path = dirname( __DIR__ ) . '/assets/column-link.css';

		wp_enqueue_style(
			'nea-block-features-column-link',
			plugins_url( 'assets/column-link.css', dirname( __DIR__ ) . '/nea-block-features.php' ),
			array(),
			file_exists( $css_path ) ? filemtime( $css_path ) : false
		);
	}

	public function render( $block_content, $block ) {
		if ( ! is_string( $block_content ) || '' === $block_content ) {
			return $block_content;
		}

		$link = isset( $block['attrs']['neaColumnLink'] ) ? $block['attrs']['neaColumnLink'] : null;
		if ( ! is_array( $link ) ) {
			return $block_content;
		}

		$url = ( isset( $link['url'] ) && is_string( $link['url'] ) ) ? trim( $link['url'] ) : '';
		if ( '' === $url ) {
			return $block_content;
		}

		$href = esc_url( $url );
		if ( '' === $href ) {
			return $block_content;
		}

		$opens_in_new_tab = ! empty( $link['opensInNewTab'] );
		$nofollow         = ! empty( $link['nofollow'] );
		$rel              = array();

		if ( $opens_in_new_tab ) {
			$rel[] = 'noopener';
			$rel[] = 'noreferrer';
		}
		if ( $nofollow ) {
			$rel[] = 'nofollow';
		}
		$rel = array_values( array_unique( $rel ) );

		$title = ( isset( $link['title'] ) && is_string( $link['title'] ) ) ? trim( wp_strip_all_tags( $link['title'] ) ) : '';
		$label = '' !== $title ? $title : $url;
		if ( $opens_in_new_tab ) {
			$label .= ' (' . __( 'odpre se v novem zavihku', 'nea-block-features' ) . ')';
		}

		$attributes = sprintf( ' href="%s" aria-label="%s"', $href, esc_attr( $label ) );
		if ( $opens_in_new_tab ) {
			$attributes .= ' target="_blank"';
		}
		if ( $rel ) {
			$attributes .= sprintf( ' rel="%s"', esc_attr( implode( ' ', $rel ) ) );
		}

		$anchor = '<a class="nea-column-link"' . $attributes . '></a>';

		return $this->inject_link( $block_content, $anchor );
	}

	private function inject_link( $html, $anchor ) {
		if ( ! class_exists( 'Nea_Column_Link_Tag_Processor' ) ) {
			return $html;
		}

		$processor = new Nea_Column_Link_Tag_Processor( $html );
		if ( ! $processor->next_tag( array( 'tag_name' => 'DIV' ) ) ) {
			return $html;
		}

		$processor->add_class( 'nea-column-has-link' );
		$html = $processor->get_updated_html();

		$locator = new Nea_Column_Link_Tag_Processor( $html );
		if ( ! $locator->next_tag( array( 'tag_name' => 'DIV' ) ) ) {
			return $html;
		}

		// The first DIV is the column wrapper. Insert the link before its matching closer
		// so inner markup, including any literal "</div>" in text, stays untouched.
		$depth = 1;
		while ( $depth > 0 && $locator->next_tag( array( 'tag_closers' => 'visit' ) ) ) {
			if ( $locator->is_tag_closer() ) {
				$depth--;
				if ( 0 === $depth && 'DIV' === $locator->get_tag() ) {
					$offset = $locator->token_offset();
					if ( is_int( $offset ) ) {
						return substr( $html, 0, $offset ) . $anchor . substr( $html, $offset );
					}
					break;
				}
				continue;
			}

			if ( ! $this->is_void_element( $locator->get_tag() ) ) {
				$depth++;
			}
		}

		$fallback = strrpos( $html, '</div>' );
		if ( false === $fallback ) {
			return $html;
		}

		return substr( $html, 0, $fallback ) . $anchor . substr( $html, $fallback );
	}

	private function is_void_element( $tag ) {
		return in_array(
			$tag,
			array( 'AREA', 'BASE', 'BR', 'COL', 'EMBED', 'HR', 'IMG', 'INPUT', 'LINK', 'META', 'PARAM', 'SOURCE', 'TRACK', 'WBR' ),
			true
		);
	}
}

new Block_Column_Link();
