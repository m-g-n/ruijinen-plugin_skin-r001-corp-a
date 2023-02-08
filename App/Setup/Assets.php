<?php
/**
 * @package ruijinen-skin-r001-corp-a
 * @author mgn
 * @license GPL-2.0+
 */

namespace Ruijinen\Skin\R001_CORP_A\App\Setup;

class Assets {

	/**
	 * Constructor.
	 */
	public function __construct() {
		add_action( 'wp_enqueue_scripts', [ $this, 'wp_enqueue_scripts' ], 1000, 2 );
		add_action( 'enqueue_block_editor_assets', [ $this, 'enqueue_editor_style' ] );
	}

	/**
	 * Enqueue front assets
	 */
	public function wp_enqueue_scripts() {
		$sm_style_handles = \Framework\Helper::get_main_style_handle();
		wp_enqueue_style(
			RJE_SKIN_R001_CORP_A_BASENAME,
			RJE_SKIN_R001_CORP_A_URL . '/dist/css/style.css',
			$sm_style_handles,
			filemtime( RJE_SKIN_R001_CORP_A_PATH . '/dist/css/style.css' )
		);
	}

	/**
	 * Enqueue editor assets
	 */
	public function enqueue_editor_style() {
		$sm_style_handles = \Framework\Helper::get_main_style_handle();
		$editor_filename = RJE_SKIN_R001_CORP_A_URL .'dist/css/style.css';
		$editor_filetime = ( file_exists( RJE_SKIN_R001_CORP_A_PATH . $editor_filename ) ) ? filemtime( RJE_SKIN_R001_CORP_A_PATH . $editor_filename ) : NULL;
		wp_enqueue_style( 'r001-corp-a-editor', $editor_filename, $sm_style_handles, $editor_filetime );
	}

}
