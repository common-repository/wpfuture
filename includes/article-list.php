<?php

/**
 * Modifications of Article List
 */

/**
 * WordPress Hook
 */
function wpfuture_article_list() {
	if( !is_admin() ) return false;
	// Modification of Article List
	$options = get_option( 'wpfuture' );
	if( $options == false ) { $isenabled = true; } else { $isenabled = $options['enable_article_list']; }
	if( $isenabled ) {
		add_filter('manage_posts_columns', 'wpfuture_column' );
		add_action('manage_posts_custom_column', 'wpfuture_column_content' );
		add_action('admin_head', 'wpfuture_custom_colors');
	}
}
 
 /**
  * Add custom Column to Article List
  */
function wpfuture_column( $defaults ) {
	$defaults['wpfuture'] = __('Planned', WPFUTURE_TEXTDOMAIN);
	return $defaults;
}

/**
 * Fill custom Column with Content
 */
function wpfuture_column_content( $column_name, $post_id="" ) {
	if( $column_name == 'wpfuture' ) {
		$post = get_post( $post_id );
		if( $post->post_status == "future" ) {
			$time = strtotime($post->post_date);
			?>
            <abbr title="<?= __( date('l',$time) ) ?>"><?= __( date('l',$time) ) ?></abbr>
            <br/>
            <?= date('H:i',$time) ?> 
            <?php // No "Clock" after Time in English Speaking Countries ?>
            <?php if( !strpos( get_bloginfo('language','raw'), 'en-' ) === false ):  ?>
            	<?= __( 'Clock', WPFUTURE_TEXTDOMAIN ) ?>
            <?php endif; ?>
			<?php
		} else {
			echo "-";
		}
    }
}

/**
 * Highlighting of Rows with planned Posts
 */
function wpfuture_custom_colors() {
	?>
	<style type="text/css">
		tr.status-future { background-color: #FFFBCC; }
	</style>
	<?php
}

?>