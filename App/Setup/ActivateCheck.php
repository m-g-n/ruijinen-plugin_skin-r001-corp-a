<?php
/**
 * アクティベートチェック.
 *
 * @package ruijinen-skin-r001-corp-a
 * @author mgn
 * @license GPL-2.0+
 */

namespace Ruijinen\Skin\R001_CORP_A\App\Setup;

/**
 * 必要なテーマ・プラグインが有効化されているかをチェックする.
 */
class ActivateCheck {
	/**
	 * エラーメッセージ.
	 *
	 * @var string[]
	 */
	public $messages = array();

	/**
	 * Constructor.
	 */
	public function __construct() {
		$this->check_snow_monkey_activate();
	}

	/**
	 * Snow Monkeyテーマが有効かチェック.
	 */
	public function check_snow_monkey_activate() {
		$theme = wp_get_theme( get_template() );
		if ( 'snow-monkey' !== $theme->template && 'snow-monkey/resources' !== $theme->template ) {
			$this->messages['snow_monkey'] = 'Snow Monkeyテーマが必要です';
		}
	}

	/**
	 * 必要なパッケージがアクティベートされてない場合のエラーメッセージ.
	 */
	public function make_alert_message() {
		?>
		<div class="notice notice-warning is-dismissible">
			<p><strong>[類人猿企業スキン]</strong></p>
			<?php foreach ( $this->messages as $text ) : ?>
				<p><?php echo esc_html( $text ); ?></p>
			<?php endforeach; ?>
		</div>
		<?php
	}
}
