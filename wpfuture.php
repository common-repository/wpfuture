<?php
/**
Plugin Name:	wpFuture
Plugin URI:		http://www.guido-muehlwitz.de/
Description:	Enhanced functionality for Future Posts.
Text Domain: 	wpfuture
Domain Path: 	/languages
Version:		0.2.3
Author:			Guido Mühlwitz
Author URI:		http://www.muehlwitz.de
License:		GPLv2
Last change: 	12.09.2011
*/

/**
License:
==============================================================================
Copyright 2011 Guido Mühlwitz  (email : g.muehlwitz@gmx.de)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/

$poedit_scanner = __('Enhanced functionality for Future Posts.');


/**
 * Including Modules
 */
require_once( 'includes/settings.php' );
require_once( 'includes/article-list.php' );
 
/**
 * Avoid direct calls to this file, because now WP core and framework has been used
 */
if ( !function_exists('add_action') ) {
	header('Status: 403 Forbidden');
	header('HTTP/1.1 403 Forbidden');
	exit();
} elseif ( version_compare(phpversion(), '5.0.0', '<') ) {
	$exit_msg = 'The plugin require PHP 5 or newer';
	header('Status: 403 Forbidden');
	header('HTTP/1.1 403 Forbidden');
	exit($exit_msg);
}

/**
 * Define Constants
 */
define( 'WPFUTURE_BASENAME', plugin_basename(__FILE__) );
define( 'WPFUTURE_BASEDIR', dirname( plugin_basename(__FILE__) ) );
define( 'WPFUTURE_TEXTDOMAIN', 'wpfuture' );
define( 'WPFUTURE_VERSION', '0.2.1' );

/**
 * Admin Dashboad Widget
 */
function wpfuture_dashboard_widget_setup() {
	if( !is_admin() ) return false;
	// Modification of Article List
	$options = get_option( 'wpfuture' );
	if( $options == false ) { $isenabled = true; } else { $isenabled = $options['enable_dashboard_widget']; }
	if( $isenabled ) {
		wp_add_dashboard_widget('wpfuture_dashboard_widget', __( 'Planned Posts', WPFUTURE_TEXTDOMAIN ), 'wpfuture_dashboard_widget');
	}
}

function wpfuture_dashboard_widget()  {
GLOBAL $time_format_widget;
	
	if( !is_admin() ) return false;
	// Query for all Future Posts of the Active user
	$future_posts = new WP_Query( array(
		'post_type' => 'post',
		'what_to_show' => 'posts',
		'post_status' => 'future',
		'author' => $GLOBALS['current_user']->ID,
		'orderby' => 'published',
		'order' => 'DESC'
	) );
	$future_posts = $future_posts->posts;
	// Ouput of all Planned Posts as HTML
	if ( $future_posts && is_array( $future_posts ) ) {
		print "<ul>";
		foreach( $future_posts as $post ) {
			//
			$url = get_edit_post_link( $post->ID );
			$title = _draft_or_post_title( $post->ID );
			//
			print "<li>";
			print '<h5 style="margin:0;">';
			echo get_the_time(__( 'l, m/d/Y H:i', WPFUTURE_TEXTDOMAIN ), $post);
			echo "</h5>";
			echo "<h4>";
			?> 
            <a href="<?= $url ?>" title="<?= _e('Edit') ?> <?= attribute_escape( $title ) ?>"><?= $title ?></a> 
			<?php
			print "</h4>";
			print "</li>";
		}
		print "</ul>";
	}
	// Button for Article List of all Planned Posts
	?>
	<p class="textright">
    	<a href="edit.php?post_status=future" class="button"><?php _e( 'All Planned Posts', WPFUTURE_TEXTDOMAIN ); ?></a>
    </p>
    <?php
}

/**
 * WordPress Hooks
 */
function wpfuture_activation() {
	$options = array(
		'enable_article_list' => true,
		'enable_dashboard_widget' => true
	); 
	add_option( 'wpfuture', $options, '', 'no' );
}

function wpfuture_deactivation() {
	delete_option( 'wpfuture' ); 
}

function wpfuture_lang() {
	load_plugin_textdomain( WPFUTURE_TEXTDOMAIN, false, WPFUTURE_BASEDIR . '/languages' );
}

if( is_admin() ) {
	// I18N
	add_action( 'init', 'wpfuture_lang' );
	// Activation / Deactivation
	register_activation_hook( __FILE__, 'wpfuture_activation' ); 
	register_deactivation_hook( __FILE__, 'wpfuture_deactivation' ); 
	// Settings Page
	add_action( 'admin_head', 'wpfuture_add_admin_css' );
	add_action( 'admin_menu', 'wpfuture_admin_page' ); 
	// Changes in Article List
	add_action( 'admin_init', 'wpfuture_article_list' );
	// Dashboard Widget
	add_action('wp_dashboard_setup', 'wpfuture_dashboard_widget_setup');
}

?>