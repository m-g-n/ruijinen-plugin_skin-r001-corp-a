<?php
/**
 * @package ruijinen-skin-r001-corp-a
 * @author mgn
 * @license GPL-2.0+
 */

namespace Ruijinen\Skin\R001_CORP_A\App\ThemesCustomize;

class Archives{

	/**
	 * Constructor.
	 */
	public function __construct() {
		add_filter( 'inc2734_wp_basis_posts_pagination_args', array( $this, 'change_arrow' ));
	}

	/**
	 * Pagenation Customize.
	 * @see https://developer.wordpress.org/reference/functions/get_the_posts_pagination/
	 * @param array $args
	 * @return array
	 */
	public function change_arrow( $args ) {
		$args = array(
			'prev_text' => '<i class="rje-r002lp-a_pagination_arrow --left" aria-hidden="true"></i>',
			'next_text' => '<i class="rje-r002lp-a_pagination_arrow --right" aria-hidden="true"></i>'
		);
		return $args;
	}
}