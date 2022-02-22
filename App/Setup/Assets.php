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
		add_action( 'wp_enqueue_scripts', [ $this, 'wp_enqueue_scripts' ] );
	}

	/**
	 * Enqueue front assets
	 */
	public function wp_enqueue_scripts() {
		wp_enqueue_style(
			RJE_SKIN_R001_CORP_A_BASENAME,
			RJE_SKIN_R001_CORP_A_URL . '/dist/css/style.css',
			[],
			filemtime( RJE_SKIN_R001_CORP_A_PATH . '/dist/css/style.css' )
		);
	}
}