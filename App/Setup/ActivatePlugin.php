<?php
/**
 * @package ruijinen-skin-r001-corp-a
 * @author mgn
 * @license GPL-2.0+
 */

namespace Ruijinen\Skin\R001_CORP_A\App\Setup;

class ActivatePlugin{

	/**
	 * Constructor.
	 */
	public function __construct() {
		add_action( 'admin_init',  array( $this, 'plugin_activate' ) );
	}

	/**
	 * Check the required environmen and Plugin Activation.
	 */
	public function plugin_activate() {
		if ( !is_admin() || !current_user_can( 'activate_plugins' ) ) {
			return;
		}
		$theme = wp_get_theme( get_template() );
		if ( 'snow-monkey' !== $theme->template && 'snow-monkey/resources' !== $theme->template ) {
			deactivate_plugins( RJE_SKIN_R001_CORP_A_BASENAME ); //プラグインを無効化
			add_action( 'admin_notices', [ $this, 'admin_notice_no_snow_monkey' ] );
			if ( isset( $_GET['activate'] ) ) {
				unset( $_GET['activate'] );
			}
			return;
		} 
	}

	//Snow Monkeyテーマが有効化されてなかった場合のエラーメッセージ
	public function admin_notice_no_snow_monkey() {
		?>
		<div class="error">
			<p><?php esc_html_e( '[RUI-JIN-EN Block Patterns] This Plugin must need the premium theme Snow Monkey.', RJE_SKIN_R001_CORP_A_TEXTDOMAIN ); ?></p>
		</div>
		<?php
	}
}