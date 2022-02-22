<?php
/**
 * @package ruijinen-skin-r001-corp-a
 * @author mgn
 * @license GPL-2.0+
 */

namespace Ruijinen\Skin\R001_CORP_A\App\ThemesCustomize;

class EntryHeader{

	/**
	 * Properties.
	 */
	private $meta_key = 'rje_r002lp_a_sub_title';

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
		add_action(
			'admin_menu',
			function() {
				add_meta_box( 
					$this->meta_key,
					'[類人猿] ページのサブタイトル',
					function() {
						global $post;
						echo '<input type="text" name="'.$this->meta_key.'" value="'.get_post_meta($post->ID, $this->meta_key, true).'" style="100%" />';
					},
					'page',
					'side',
					'high'
				);
			}
		);
		add_action(
			'save_post',
			function( $post_id ) {
				if ( !empty($_POST[$this->meta_key]) ) {
					update_post_meta($post_id, $this->meta_key, $_POST[$this->meta_key] );
				}else{
					delete_post_meta($post_id, $this->meta_key);
				}
			}
		);
	}

	/**
	 * ページタイトル上部にサブタイトルを追記するためのフック追加.
	 */
	public function view_subtitle() {
		add_filter( 'snow_monkey_template_part_render_template-parts/archive/entry/header/header', array( $this, 'add_sub_title'), 10, 3);
		add_filter( 'snow_monkey_template_part_render_template-parts/content/entry/header/header', array( $this, 'add_sub_title'), 10, 3);

		//投稿アーカイブのタイトルを書換
		add_filter( 
			'rje_r002lp_a_page_sub_title',
			function( $text ) {
				if ( is_category() || is_tag() ) {
					$text = 'NEWS';
				}
				return $text;
			}
		);
	}

	/**
	 * ページタイトル上部にサブタイトルを追記する.
	 */
	public function add_sub_title( $html, $name, $vars ) {
		$subtitle = $this->get_subtitle();
		if ( $subtitle ) {
			$before = '/<h1 class="c-entry__title">/';
			$after = '<div class="rje-r002lp-a_entry_subtitle">'.$subtitle.'</div><h1 class="c-entry__title">';
			$html = preg_replace( $before, $after, $html );
		}
		return $html;
	}

	private function get_subtitle() {
		$text = NULL;
		if ( is_post_type_archive() ) {
			$text = strtoupper( get_query_var('post_type') );
		} elseif ( is_tax() ) {
			$tax  = get_query_var('taxonomy');
			$text = strtoupper( get_taxonomy($tax)->object_type[0] );
		} elseif ( is_home() || is_page() ) {
			$queried_object = get_queried_object();
			if ( $queried_object->ID ) {
				$text = get_post_meta( $queried_object->ID, $this->meta_key, true);
			}
		}
		$subtitle = apply_filters( 'rje_r002lp_a_page_sub_title', $text );
		return $subtitle;
	}

}


