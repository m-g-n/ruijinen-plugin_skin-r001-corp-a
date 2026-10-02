<?php
/**
 * Singleページ関連のカスタマイズ.
 *
 * @package ruijinen-skin-r001-corp-a
 * @author mgn
 * @license GPL-2.0+
 */

namespace Ruijinen\Skin\R001_CORP_A\App\ThemesCustomize;

use Framework\Helper;

/**
 * 投稿ページの表示をカスタマイズする.
 */
class Single {

	/**
	 * Constructor.
	 */
	public function __construct() {
		remove_action( 'snow_monkey_entry_meta_items', 'snow_monkey_entry_meta_items_author', 30 ); // author表示の削除.
		add_filter( 'snow_monkey_get_template_part_args_template-parts/content/prev-next-nav', array( $this, 'prev_next_nav_args' ) );
		add_filter( 'snow_monkey_template_part_render_template-parts/content/prev-next-nav', array( $this, 'prev_next_nav_html' ) );
		add_filter( 'snow_monkey_get_template_part_args_template-parts/content/entry/footer/footer', array( $this, 'change_entry_footer_args' ) );
		add_filter( 'snow_monkey_template_part_render_templates/layout/footer/footer', array( $this, 'add_related_posts' ) );
		add_filter( 'snow_monkey_template_part_render_header', array( $this, 'add_contents_header' ) );
		add_filter( 'snow_monkey_template_part_render_template-parts/content/related-posts', array( $this, 'add_entries_class' ) );
		add_shortcode( 'sns_share_btn', array( $this, 'sns_share_btn' ) );
	}

	/**
	 * 前へ次へのナビゲーション表記を変更.
	 *
	 * @param array $args テンプレートパーツの引数.
	 * @return array
	 */
	public function prev_next_nav_args( $args ) {
		$args['vars']['_next_label'] = '前の記事';
		$args['vars']['_prev_label'] = '次の記事';
		return $args;
	}

	/**
	 * 前へ次へナビゲーションの出力タグを変更.
	 *
	 * @param string $html テンプレートパーツの出力HTML.
	 * @return string
	 */
	public function prev_next_nav_html( $html ) {
		$replacements = array(
			'/<div class="c-prev-next-nav__item-figure">(.*?)<\/div>/s' => '', // アイキャッチ画像.
			'/<div class="c-prev-next-nav__item-title">(.*?)<\/div>/s' => '', // 記事タイトル.
			'/c-prev-next-nav/'      => 'rje-r002lp-a_prev_next_nav', // ルートのclass.
			'/class="fas fa-angle-/' => 'class="rje-r002lp-a_pagination_arrow --', // 矢印アイコン.
		);
		return preg_replace( array_keys( $replacements ), array_values( $replacements ), $html );
	}

	/**
	 * カスタマイザーで設定したSNSシェアボタンを任意の位置に呼び出すショートコード.
	 *
	 * @return string
	 */
	public function sns_share_btn() {
		if ( ! get_option( 'mwt-share-buttons-buttons' ) ) {
			return '';
		}
		ob_start();
		Helper::get_template_part( 'template-parts/content/share-buttons' );
		return ob_get_clean();
	}

	/**
	 * 記事フッターのargの値変更.
	 *
	 * @param array $args テンプレートパーツの引数.
	 * @return array
	 */
	public function change_entry_footer_args( $args ) {
		$args['vars']['_display_related_posts'] = false; // 関連記事表示位置変更のため正規の位置側は非表示.
		return $args;
	}

	/**
	 * Single時にフッター上部に関連記事を表示.
	 *
	 * @param string $html テンプレートパーツの出力HTML.
	 * @return string
	 */
	public function add_related_posts( $html ) {
		if ( ! is_single() || ! get_option( 'mwt-display-related-posts' ) ) {
			return $html;
		}
		$related_posts_query = Helper::get_related_posts_query( get_the_ID() );
		$google_code         = get_option( 'mwt-google-matched-content' );
		if ( ! $google_code && ! $related_posts_query->have_posts() ) {
			return $html;
		}

		ob_start();
		Helper::get_template_part(
			'template-parts/content/related-posts',
			get_post_type(),
			array(
				'_title'       => __( 'Related posts', 'snow-monkey' ), // phpcs:ignore WordPress.WP.I18n.TextDomainMismatch -- Snow Monkey の翻訳を利用する.
				'_posts_query' => $related_posts_query,
				'_code'        => $google_code,
			)
		);
		$related_posts = ob_get_clean();

		return str_replace(
			'<footer ',
			'<div class="rje-r001corp-a_related_posts"><div class="c-container">' . $related_posts . '</div></div><footer ',
			$html
		);
	}

	/**
	 * 関連記事に独自のclassを追加.
	 *
	 * @param string $html テンプレートパーツの出力HTML.
	 * @return string
	 */
	public function add_entries_class( $html ) {
		return str_replace(
			'p-related-posts ',
			'p-related-posts is-style-RJE_R001CORP_recent_posts ',
			$html
		);
	}

	/**
	 * Single時にヘッダー下部に投稿タイプ名のヘッダーを追加.
	 *
	 * @param string $html テンプレートパーツの出力HTML.
	 * @return string
	 */
	public function add_contents_header( $html ) {
		if ( ! is_single() ) {
			return $html;
		}
		$post_type         = get_post_type() ? get_post_type() : 'post';
		$eyecatch_position = get_theme_mod( $post_type . '-eyecatch' );
		if ( 'title-on-page-header' === $eyecatch_position || 'page-header' === $eyecatch_position ) {
			return $html;
		}

		$cat_title    = ( 'post' === $post_type ) ? 'NEWS' : strtoupper( $post_type );
		$cat_subtitle = ( 'post' === $post_type ) ? 'お知らせ' : '';
		return str_replace(
			'</header>',
			'</header><div class="rje-r001corp-a_entry_header"><div class="rje-r001corp-a_entry_header__title">' . esc_html( $cat_title ) . '</div><div class="rje-r001corp-a_entry_subtitle">' . esc_html( $cat_subtitle ) . '</div></div>',
			$html
		);
	}
}
