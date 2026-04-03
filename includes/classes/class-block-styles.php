<?php
/**
 * Block Styles Manager
 *
 * @package GatherPress_References
 */

namespace GatherPress\References;

defined( 'ABSPATH' ) || exit;

/**
 * Block Styles Manager
 *
 * Registers block styles with separate CSS files so they can be
 * individually enqueued/dequeued by themes. Each style gets its own
 * stylesheet that WordPress only loads when the style is active on a page.
 *
 * To disable a style from a theme or plugin:
 *   wp_dequeue_style( 'gatherpress-references-style-classic-serif' );
 *   unregister_block_style( 'gatherpress/references', 'classic-serif' );
 *
 * @since 0.1.0
 */
class Block_Styles {
	/**
	 * Style definitions
	 *
	 * @var array<string, string>
	 */
	private array $styles = array(
		'classic-serif'    => 'Classic Serif',
		'modern-corporate' => 'Modern Corporate',
		'neon-gradient'    => 'Neon Gradient',
		'eco-cyberpunk'    => 'Eco Cyberpunk',
	);

	/**
	 * Register all block styles
	 *
	 * @since 0.1.0
	 * @return void
	 */
	public function register(): void {
		foreach ( $this->styles as $slug => $label ) {
			$this->register_style( $slug, $label );
		}
	}

	/**
	 * Register a single block style with its own stylesheet
	 *
	 * @since 0.1.0
	 * @param string $slug  Style slug.
	 * @param string $label Style label.
	 * @return void
	 */
	private function register_style( string $slug, string $label ): void {
		$style_handle = 'gatherpress-references-style-' . $slug;
		$css_file     = GATHERPRESS_REFERENCES_CORE_PATH . '/src/styles/' . $slug . '.css';

		if ( ! file_exists( $css_file ) ) {
			return;
		}
		do_action('qm/debug', GATHERPRESS_REFERENCES_CORE_PATH);
		do_action('qm/debug', plugin_dir_path( GATHERPRESS_REFERENCES_CORE_PATH ));
		do_action('qm/debug', "Registering block style: $slug with CSS file: $css_file");
		wp_register_style(
			$style_handle,
			plugins_url( 'src/styles/' . $slug . '.css', GATHERPRESS_REFERENCES_CORE_PATH . '/plugin.php' ),
			array(),
			(string) filemtime( $css_file )
		);
		// wp_enqueue_style( $style_handle );

		register_block_style(
			'gatherpress/references',
			array(
				'name'         => $slug,
				'label'        => $label,
				'style_handle' => $style_handle,
			)
		);
	}
}
