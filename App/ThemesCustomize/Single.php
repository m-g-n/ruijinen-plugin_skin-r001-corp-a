<?php
/**
 * @package ruijinen-skin-r001-corp-a
 * @author mgn
 * @license GPL-2.0+
 */

namespace Ruijinen\Skin\R001_CORP_A\App\ThemesCustomize;
use \Framework\Helper;

class Single{

	/**
	 * Constructor.
	 */
	public function __construct() {
		remove_action( 'snow_monkey_entry_meta_items', 'snow_monkey_entry_meta_items_author', 30 ); //author表示の削除
		add_filter( 'snow_monkey_get_template_part_args_template-parts/content/prev-next-nav', array( $this, 'prev_next_nav_args'));
		add_filter( 'snow_monkey_template_part_render_template-parts/content/prev-next-nav', array( $this, 'prev_next_nav_html'), 10, 3);
		add_filter('snow_monkey_get_template_part_args_template-parts/content/entry/footer/footer', array( $this, 'change_entry_footer_args'));
		add_filter('snow_monkey_template_part_render_templates/layout/footer/footer', array( $this, 'add_related_posts'));
		add_filter('snow_monkey_template_part_render_header', array( $this, 'add_contents_header'));
		add_filter('snow_monkey_template_part_render_template-parts/content/related-posts', array( $this, 'add_entries_class'));
		add_shortcode('sns_share_btn', array( $this, 'sns_share_btn'));
	}

	/**
	 * 前へ次へのナビゲーション表記を変更.
	 * @param array $args
	 * @return array
	 */
	public function prev_next_nav_args( $args ) {
		$args['vars']['_next_label'] = '前の記事';
		$args['vars']['_prev_label'] = '次の記事';
		return $args;
	}

	/**
	 * 前へ次へナビゲーションの出力タグを変更
	 * @param $html テンプレートパーツの出力HTML
	 * @param $name テンプレートパーツの名前
	 * @param $vars テンプレートパーツのリクエスト配列
	 */
	public function prev_next_nav_html( $html, $name, $vars ) {
		$target_words = array(
			'figure' => array(
				'before' => '/<div class="c-prev-next-nav__item-figure">(.*?)<\/div>/s',
				'after'  => '',
			),
			'title' => array(
				'before' => '/<div class="c-prev-next-nav__item-title">(.*?)<\/div>/s',
				'after' => '',
			),
			// 'separate' => array(
			// 	'before' => '/<div class="c-prev-next-nav__item c-prev-next-nav__item--prev/',
			// 	'after'  => '<span class="rje-r002lp-a_prev_next_nav__separate"></span><div class="c-prev-next-nav__item c-prev-next-nav__item--prev',
			// ),
			'root_class' => array(
				'before' => '/c-prev-next-nav/',
				'after' => 'rje-r002lp-a_prev_next_nav',
			),
			'arrow_icon' => array(
				'before' => '/class="fas fa-angle-/',
				'after' => 'class="rje-r002lp-a_pagination_arrow --',
			),
		);
		foreach ( $target_words as $word ) {
			$html = preg_replace( $word['before'], $word['after'], $html );
		}
		return $html;
	}

	/**
	* カスタマイザーで設定したSNSシェアボタンを任意の位置に呼び出すショートコード
	*/
	function sns_share_btn() {
		$share_buttons = get_option( 'mwt-share-buttons-buttons' );
		$html = NULL;
		if ( $share_buttons ) {
			ob_start();
			Helper::get_template_part( 'template-parts/content/share-buttons' );
			$html = ob_get_contents();
			ob_end_clean();
		}
		return $html;
	}

	/**
	* 記事フッターのargの値変更
	*/
	function change_entry_footer_args ($args) {
		$args['vars']['_display_related_posts'] = false; //関連記事表示位置変更のため正規の位置側は非表示
		return $args;
	}

	/**
	* single時にフッター上部に関連記事を表示
	*/
	function add_related_posts ( $html ) {
		global $post;
		$display_related_posts = get_option( 'mwt-display-related-posts' );
		if ( !is_single() || !$display_related_posts ) {
			return $html;
		}
		$related_posts_query = Helper::get_related_posts_query( get_the_ID() );
		if ( get_option( 'mwt-google-matched-content' ) || $related_posts_query->have_posts() ) {
			$vars = [
				'_title'       => __( 'Related posts', 'snow-monkey' ),
				'_posts_query' => $related_posts_query,
				'_code'        => get_option( 'mwt-google-matched-content' ),
			];
			ob_start();
			Helper::get_template_part(
				'template-parts/content/related-posts',
				get_post_type( $post ),
				$vars
			);
			$related_posts = ob_get_contents();
			ob_end_clean();
			$html = str_replace(
				'<footer ',
				'<div class="rje-r001corp-a_related_posts"><div class="c-container">'.$related_posts.'</div></div><footer ',
				$html
			);
		}
		return $html;
	}

	/**
	 * Entries original class add.
	 * @param array $args
	 * @return array
	 */
	public function add_entries_class( $html) {
		$before = 'p-related-posts ';
		$after = 'p-related-posts is-style-RJE_R001CORP_recent_posts ';
		$html = str_replace( $before, $after, $html );
		return $html;
	}


	/**
	 * Entries original class add.
	 * @param array $args
	 * @return array
	 */
	public function add_contents_header( $html) {
		$post_type = get_post_type() ? get_post_type() : 'post';
		if ( !is_single() ) {
			return $html;
		} else {
			$eyecatch_position = get_theme_mod( $post_type . '-eyecatch' );
			if ( 'title-on-page-header' === $eyecatch_position || 'page-header'  === $eyecatch_position ) {
				return $html;
			}
		}
		$cat_title = ( 'post' === $post_type) ? 'NEWS' : strtoupper($post_type);
		$cat_subtitle = ( 'post' === $post_type) ? 'お知らせ' : '';
		$before = '</header>';
		$after = '</header><div class="rje-r001corp-a_entry_header"><div class="rje-r001corp-a_entry_header__title">'.$cat_title.'</div><div class="rje-r001corp-a_entry_subtitle">'.$cat_subtitle.'</div></div>';
		$html = str_replace( $before, $after, $html );
		return $html;
	}
}
