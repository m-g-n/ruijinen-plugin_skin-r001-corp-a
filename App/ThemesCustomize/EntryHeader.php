<?php
/**
 * コンテンツヘッダーのカスタマイズ.
 *
 * @package ruijinen-skin-r001-corp-a
 * @author mgn
 * @license GPL-2.0+
 */

namespace Ruijinen\Skin\R001_CORP_A\App\ThemesCustomize;

/**
 * ページタイトル下にサブタイトルを表示する.
 */
class EntryHeader {

	/**
	 * サブタイトルを保存するメタキー.
	 *
	 * @var string
	 */
	private $meta_key = 'rje_r001corp_a_sub_title';

	/**
	 * サブタイトル保存用 nonce のアクション名.
	 *
	 * @var string
	 */
	private $nonce_action = 'rje_r001corp_a_save_sub_title';

	/**
	 * サブタイトル保存用 nonce のフィールド名.
	 *
	 * @var string
	 */
	private $nonce_name = 'rje_r001corp_a_sub_title_nonce';

	/**
	 * Constructor.
	 */
	public function __construct() {
		$this->input_subtitle();
		$this->view_subtitle();
	}

	/**
	 * ページサブタイトル用の入力ボックスの追加・保存.
	 */
	public function input_subtitle() {
		add_action( 'add_meta_boxes_page', array( $this, 'add_meta_box' ) );
		add_action( 'save_post_page', array( $this, 'save_subtitle' ) );
	}

	/**
	 * サブタイトル入力用のメタボックスを追加.
	 */
	public function add_meta_box() {
		add_meta_box(
			$this->meta_key,
			'[類人猿] ページのサブタイトル',
			array( $this, 'render_meta_box' ),
			'page',
			'side',
			'high'
		);
	}

	/**
	 * サブタイトル入力用のメタボックスを出力.
	 *
	 * @param \WP_Post $post 編集中の投稿.
	 */
	public function render_meta_box( $post ) {
		wp_nonce_field( $this->nonce_action, $this->nonce_name );
		printf(
			'<input type="text" name="%1$s" value="%2$s" style="width:100%%" />',
			esc_attr( $this->meta_key ),
			esc_attr( get_post_meta( $post->ID, $this->meta_key, true ) )
		);
	}

	/**
	 * サブタイトルを保存.
	 *
	 * @param int $post_id 投稿ID.
	 */
	public function save_subtitle( $post_id ) {
		// メタボックスから送信された場合のみ保存する（クイック編集などで値が消えないようにする）.
		if ( ! isset( $_POST[ $this->nonce_name ] ) ) {
			return;
		}
		if ( ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST[ $this->nonce_name ] ) ), $this->nonce_action ) ) {
			return;
		}
		if ( wp_is_post_autosave( $post_id ) || wp_is_post_revision( $post_id ) ) {
			return;
		}
		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}

		$subtitle = isset( $_POST[ $this->meta_key ] ) ? sanitize_text_field( wp_unslash( $_POST[ $this->meta_key ] ) ) : '';
		if ( '' !== $subtitle ) {
			update_post_meta( $post_id, $this->meta_key, $subtitle );
		} else {
			delete_post_meta( $post_id, $this->meta_key );
		}
	}

	/**
	 * ページタイトル上部にサブタイトルを追記するためのフック追加.
	 */
	public function view_subtitle() {
		add_filter( 'snow_monkey_template_part_render_template-parts/archive/entry/header/header', array( $this, 'add_sub_title' ) );
		add_filter( 'snow_monkey_template_part_render_template-parts/content/entry/header/header', array( $this, 'add_sub_title' ) );
		add_filter( 'snow_monkey_template_part_render_template-parts/common/page-header', array( $this, 'add_sub_title' ) );
	}

	/**
	 * 投稿タイトル下部にサブタイトルを追記する.
	 *
	 * @param string $html テンプレートパーツの出力HTML.
	 * @return string
	 */
	public function add_sub_title( $html ) {
		$subtitle = $this->get_subtitle();
		if ( $subtitle ) {
			$html = str_replace(
				'</h1>',
				'</h1><div class="rje-r001corp-a_entry_subtitle">' . esc_html( $subtitle ) . '</div>',
				$html
			);
		}
		return $html;
	}

	/**
	 * 表示中のページに応じたサブタイトルを取得する.
	 *
	 * @return string|null
	 */
	private function get_subtitle() {
		$text = null;
		if ( is_post_type_archive() ) {
			$post_type = get_query_var( 'post_type' );
			$post_type = is_array( $post_type ) ? reset( $post_type ) : $post_type;
			$text      = strtoupper( (string) $post_type );
		} elseif ( is_home() || is_page() ) {
			$queried_object = get_queried_object();
			if ( $queried_object instanceof \WP_Post ) {
				$text = get_post_meta( $queried_object->ID, $this->meta_key, true );
			}
		}
		return apply_filters( 'rje_r001corp_a_page_sub_title', $text );
	}
}
