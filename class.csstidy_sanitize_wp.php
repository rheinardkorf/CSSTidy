<?php

// Copied from: http://wordpress.stackexchange.com/questions/53970/sanitize-user-entered-css

if( ! class_exists( 'CSSTidy_Sanitize_WP' ) ) {
	class CSSTidy_Sanitize_WP {

		public static function sanitize_css( $css ) {
			if( ! class_exists( 'csstidy' ) ) {
				require_once('class.csstidy.php');
			}
			$csstidy = new csstidy();
			$csstidy->set_cfg( 'remove_bslash', FALSE );
			$csstidy->set_cfg( 'compress_colors', FALSE );
			$csstidy->set_cfg( 'compress_font-weight', FALSE );
			$csstidy->set_cfg( 'discard_invalid_properties', TRUE );
			$csstidy->set_cfg( 'merge_selectors', FALSE );
			$csstidy->set_cfg( 'remove_last_;', FALSE );
			$csstidy->set_cfg( 'css_level', 'CSS3.0' );
			$csstovalidateindiv = 'div {' . $css . '}';
			$csstovalidateindiv = preg_replace( '/\\\\([0-9a-fA-F]{4})/', '\\\\\\\\$1', $csstovalidateindiv );
			$csstovalidateindiv = wp_kses_split( $csstovalidateindiv, array(), array() );
			$csstidy->parse( $csstovalidateindiv );
			$csstovalidateindiv = $csstidy->print->plain();
			$csstovalidateindiv = str_replace( array( "\n", "\r", "\t" ), '', $csstovalidateindiv );
			preg_match( "/^div\s*{(.*)}\s*$/", $csstovalidateindiv, $matches );
			$cssvalidated = !empty( $matches[1] ) ? $matches[1] : false;

			return $cssvalidated;
		}

	}
}