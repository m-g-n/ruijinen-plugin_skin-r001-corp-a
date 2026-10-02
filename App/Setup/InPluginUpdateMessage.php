<?php
/**
 * 更新アラートメッセージの追加.
 *
 * @package ruijinen-skin-r001-corp-a
 * @author mgn
 * @license GPL-2.0+
 */

namespace Ruijinen\Skin\R001_CORP_A\App\Setup;

/**
 * プラグイン一覧の更新アラートボックスにお知らせを追加する.
 */
class InPluginUpdateMessage {

	/**
	 * お知らせJSONのキャッシュ用トランジェント名.
	 *
	 * @var string
	 */
	const TRANSIENT_KEY = 'rje_skin_r001_corp_a_update_notice';

	/**
	 * Constructor.
	 */
	public function __construct() {
		add_action( 'in_plugin_update_message-' . RJE_SKIN_R001_CORP_A_BASENAME, array( $this, 'in_plugin_update_message' ), 10, 2 );
	}

	/**
	 * 更新画面のアラートボックスにメッセージを追加.
	 *
	 * @param array  $data     プラグインのデータ.
	 * @param object $response 更新情報のレスポンス.
	 */
	public function in_plugin_update_message( $data, $response ) { // phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter.FoundAfterLastUsed
		if ( empty( $data['new_version'] ) ) {
			return;
		}
		$notice = $this->get_the_notice( $data['new_version'] );
		if ( empty( $notice['message'] ) ) {
			return;
		}
		echo '<br>' . wp_kses_post( $notice['message'] );
		if ( ! empty( $notice['url'] ) ) {
			echo '<a href="' . esc_url( $notice['url'] ) . '" target="_blank" rel="noopener"> &#62;&#62;詳細を見る</a>';
		}
	}

	/**
	 * JSONからデータを取得して指定バージョンのメッセージ情報を返す.
	 *
	 * @param string $version バージョン.
	 * @return array|false メッセージ情報. 該当がない場合は false.
	 */
	private function get_the_notice( $version ) {
		$notices = $this->get_notices();
		return isset( $notices[ $version ] ) && is_array( $notices[ $version ] ) ? $notices[ $version ] : false;
	}

	/**
	 * お知らせJSONを取得してバージョンをキーにした配列で返す.
	 *
	 * @return array
	 */
	private function get_notices() {
		$notices = get_transient( self::TRANSIENT_KEY );
		if ( is_array( $notices ) ) {
			return $notices;
		}

		$notices  = array();
		$response = wp_remote_get(
			'https://rui-jin-en.com/update-notice/' . RJE_SKIN_R001_CORP_A_KEY . '.json',
			array( 'timeout' => 5 )
		);
		if ( ! is_wp_error( $response ) && 200 === wp_remote_retrieve_response_code( $response ) ) {
			$body = mb_convert_encoding( wp_remote_retrieve_body( $response ), 'UTF-8', 'ASCII,JIS,UTF-8,EUC-JP,SJIS-win' ); // 文字コードをUTF-8に変換.
			$json = json_decode( $body, true );
			if ( is_array( $json ) ) {
				// 階層が1つ深いため配列の階層を1つ浅くする.
				foreach ( $json as $item ) {
					if ( is_array( $item ) ) {
						$notices = array_merge( $notices, $item );
					}
				}
			}
		}

		// 取得失敗時も短時間キャッシュし、管理画面表示のたびに外部通信しないようにする.
		set_transient( self::TRANSIENT_KEY, $notices, $notices ? HOUR_IN_SECONDS : 5 * MINUTE_IN_SECONDS );
		return $notices;
	}
}
