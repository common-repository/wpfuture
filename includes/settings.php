<?php

/**
 * WordPress Settings Page
 */

function wpfuture_admin_page() {
	$page = add_options_page(
				'wpFuture',
				'wpFuture', 
				'manage_options', 
				__FILE__, 
				'wpfuture_options'
			);
}

function wpfuture_add_admin_css() {
	wp_register_style(
		'wpFuture',
		plugins_url('wpfuture/css/admin.css'),
		array(),
		WPFUTURE_VERSION
	);
	wp_print_styles('wpFuture');
}

function wpfuture_options() {
	// Get Options from DB
	$options = get_option( 'wpfuture' );
	if( $options == false ) {
		$options = array(
			'enable_article_list' => true,
			'enable_dashboard_widget' => true
		); 
	}
	// POST
	if ( !empty($_POST) ) {
		check_admin_referer('wpfuture');
		if( $_POST['wpfuture_article_list'] == "1" ) {
			$options['enable_article_list'] = true;
		} else {
			$options['enable_article_list'] = false;
		}
		if( $_POST['wpfuture_dashboard_widget'] == "1" ) {
			$options['enable_dashboard_widget'] = true;
		} else {
			$options['enable_dashboard_widget'] = false;
		}
		//
		update_option('wpfuture',$options);
		?>
            <div id="message" class="updated fade">
                <p><strong>
                	<?php _e('Settings saved') ?>
                </strong></p>
            </div>
        <?php
	}	
	?>
    <div class="wrap">
	    <h2>wpFuture</h2>
        <form method="post" action="">
			<?php wp_nonce_field('wpfuture') ?>
            <div id="poststuff">
                <div class="postbox">
                    <h3><?php _e('Settings') ?></h3>
                    <div class="inside wpfuture">
                        <ul>
                            <li>
                                <input type="checkbox" name="wpfuture_article_list" id="wpfuture_article_list" value="1" <?php checked($options['enable_article_list'], true) ?> />
                                <label for="wpfuture_article_list">
                                    <?php _e('Enhanced Article List', WPFUTURE_TEXTDOMAIN) ?> 
                                </label>
                            </li>
                            <li>
                                <input type="checkbox" name="wpfuture_dashboard_widget" id="wpfuture_dashboard_widget" value="1" <?php checked($options['enable_dashboard_widget'], true) ?> />
                                <label for="wpfuture_dashboard_widget">
                                    <?php _e('Dashboard Widget', WPFUTURE_TEXTDOMAIN) ?> 
                                </label>
                            </li>
                        </ul>
                        <p>
                            <input type="submit" name="wpfuture_submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
                        </p>
                    </div>
                </div>
            </div>
		</form>            
	</div>
    <?php
}
