<?php
/**
 * Plugin name: 類人猿 企業サイト向けパターン スキンA
 * Description: 企業サイト向けパターンに合ったスキンです
 * Version: 1.8.0
 * Tested up to: 6.1.1
 * Requires at least: 6.1
 * Requires PHP: 5.6
 * Author: mgn Inc.,
 * Author URI: https://rui-jin-en.com/
 * License: GPL2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: ruijinen-skin-r001-corp-a
 *
 * @package ruijinen-skin-r001-corp-a
 * @author mgn
 * @license GPL-2.0+
 */

namespace Ruijinen\Skin\R001_CORP_A;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * declaration constant.
 */
define( 'RJE_SKIN_R001_CORP_A_KEY', 'RJE_SKIN_R001CORP_A' ); //このプラグインのKey.
define( 'RJE_SKIN_R001_CORP_A_URL', untrailingslashit( plugins_url( '', __FILE__ ) ) . '/' );  //このプラグインのURL.
define( 'RJE_SKIN_R001_CORP_A_PATH', untrailingslashit( plugin_dir_path( __FILE__ ) ) . '/' ); //このプラグインのパス.
define( 'RJE_SKIN_R001_CORP_A_BASENAME', plugin_basename( __FILE__ ) ); //このプラグインのベースネーム.
define( 'RJE_SKIN_R001_CORP_A_TEXTDOMAIN', 'ruijinen-skin-r001-corp-a-a' ); //テキストドメイン名.

/**
 * include files.
 */
require_once(RJE_SKIN_R001_CORP_A_PATH . 'vendor/autoload.php'); //アップデート用composer.

//各処理用のクラスを読み込む
foreach (glob(RJE_SKIN_R001_CORP_A_PATH.'App/**/*.php') as $filename) {
	require_once $filename;
}

/**
 * 初期設定.
 */
class Bootstrap {
	/**
	 * Constructor.
	 */
	public function __construct() {
		add_action( 'plugins_loaded', [ $this, 'bootstrap' ] );
		add_action( 'init', [ $this, 'load_textdomain' ] );
		add_action( 'after_setup_theme', [ $this, 'themes_customize' ] );
	}

	/**
	 * Bootstrap.
	 */
	public function bootstrap() {
		new App\Setup\AutoUpdate(); //自動更新機能.
		new App\Setup\InPluginUpdateMessage(); //更新アラートメッセージに追加でメッセージを表示

		//アクティベートチェックを行い問題がある場合はメッセージを出し離脱する.
		$activate_check = new App\Setup\ActivateCheck();
		if ( !empty( $activate_check->messages ) ) {
			add_action('admin_notices', array( $activate_check,'make_alert_message'));
			return;
		}
		//CSS・JSの読み込み.
		new App\Setup\Assets();
	}

	/**
	 * Load Textdomain.
	 */
	public function load_textdomain() {
		new App\Setup\TextDomain();
	}

	/**
	 * Snow Monkeyテーマのカスタマイズ.
	 */
	public function themes_customize() {
		new App\ThemesCustomize\Archives(); //投稿アーカイブ関連のカスタマイズ.
		new App\ThemesCustomize\Single(); //singleページ関連のカスタマイズ.
		new App\ThemesCustomize\EntryHeader(); //コンテンツヘッダーのカスタマイズ.
	}
}

new \Ruijinen\Skin\R001_CORP_A\Bootstrap();
