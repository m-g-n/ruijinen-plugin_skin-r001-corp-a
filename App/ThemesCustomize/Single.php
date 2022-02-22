<?php
/**
 * @package ruijinen-skin-r001-corp-a
 * @author mgn
 * @license GPL-2.0+
 */

namespace Ruijinen\Skin\R001_CORP_A\App\ThemesCustomize;

class Single{

	/**
	 * Constructor.
	 */
	public function __construct() {
		remove_action( 'snow_monkey_entry_meta_items', 'snow_monkey_entry_meta_items_author', 30 ); //author表示の削除
		add_filter( 'snow_monkey_get_template_part_args_template-parts/content/prev-next-nav', array( $this, 'prev_next_nav_args'));
		add_filter( 'snow_monkey_template_part_render_template-parts/content/prev-next-nav', array( $this, 'prev_next_nav_html'), 10, 3);
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
}
