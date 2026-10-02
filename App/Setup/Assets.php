<?php
/**
 * CSS・JSの読み込み.
 *
 * @package ruijinen-skin-r001-corp-a
 * @author mgn
 * @license GPL-2.0+
 */

namespace Ruijinen\Skin\R001_CORP_A\App\Setup;

/**
 * スキン用のスタイルを読み込む.
 */
class Assets {

	/**
	 * スタイルファイルのプラグインルートからの相対パス.
	 *
	 * @var string
	 */
	const STYLE_FILE = 'dist/css/style.css';

	/**
	 * Constructor.
	 */
	public function __construct() {
		add_action( 'wp_enqueue_scripts', array( $this, 'wp_enqueue_scripts' ), 1000 );
		add_action( 'enqueue_block_editor_assets', array( $this, 'enqueue_editor_style' ) );
	}

	/**
	 * Enqueue front assets.
	 */
	public function wp_enqueue_scripts() {
		wp_enqueue_style(
			RJE_SKIN_R001_CORP_A_BASENAME,
			RJE_SKIN_R001_CORP_A_URL . self::STYLE_FILE,
			\Framework\Helper::get_main_style_handle(),
			$this->get_style_version()
		);
	}

	/**
	 * Enqueue editor assets.
	 */
	public function enqueue_editor_style() {
		wp_enqueue_style(
			'r001-corp-a-editor',
			RJE_SKIN_R001_CORP_A_URL . self::STYLE_FILE,
			\Framework\Helper::get_main_style_handle(),
			$this->get_style_version()
		);
	}

	/**
	 * スタイルファイルの更新日時をバージョンとして返す.
	 *
	 * @return int|null ファイルが存在しない場合は null.
	 */
	private function get_style_version() {
		$path = RJE_SKIN_R001_CORP_A_PATH . self::STYLE_FILE;
		return file_exists( $path ) ? filemtime( $path ) : null;
	}
}
