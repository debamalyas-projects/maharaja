<?php
function smtpmail_generate_plugin_activation_url($plugin)
{
    // the plugin might be located in the plugin folder directly

    if (strpos($plugin, '/')) {
        $plugin = str_replace('/', '%2F', $plugin);
    }

    $activateUrl = sprintf(admin_url('plugins.php?action=activate&plugin=%s&plugin_status=all&paged=1&s'), $plugin);

    // change the plugin request to the plugin to pass the nonce check
    //$_REQUEST['plugin'] = $plugin;
    $activateUrl = wp_nonce_url($activateUrl, 'activate-plugin_' . $plugin);

    return $activateUrl;
}

// BEGIN OF SMTP Mail Recommend CF7 Review
function smtpmail_recommend_cf7_review_editor_panels( $panels = array() )
{
	global $PBOne;
	
	if( $PBOne->check_plugin_active('cf7-review/index.php') == false && is_array($panels) && count($panels) ) {
		$temp = array();

		foreach ($panels as $key => $value) {
			$temp[$key] = $value;
			if( $key == 'form-panel' ) {
				$temp['review-panel'] = array(
					'title' => __( 'Preview', 'smtp-mail' ),
					'callback' => 'smtpmail_recommend_cf7_review_tab_example',
				);
			}
		}
		
		return $temp;
	}

	return $panels;
}
add_filter( 'wpcf7_editor_panels', 'smtpmail_recommend_cf7_review_editor_panels', 10, 99 );

function smtpmail_recommend_cf7_review_tab_example()
{
	global $PBOne;
	
	$file = 'cf7-review/index.php';
	
	$popup_link = admin_url('plugin-install.php?tab=plugin-information&plugin=cf7-review&TB_iframe=true');

	$html_visit = ' <a rel="bookmark" href="'. $popup_link .'" class="button button-second thickbox" target="_top">'
					. __('Visit plugin', 'smtp-mail')
					. '</a>';
	
	$plugin_link = admin_url('plugin-install.php?s=cf7-preview&tab=search&type=tag');

	$html_visit = ' <a rel="bookmark" href="'. $plugin_link .'" class="button button-second" target="_blank">'
					. __('Visit plugin', 'smtp-mail')
					. '</a>';
	
	
	echo '<div id="wpcf7_review_tab_example" style="min-height: 400px;">';
	
	if( file_exists( ABSPATH . 'wp-content/plugins/' . $file ) == false ) {
		
		$action = 'install-plugin';
		$slug 	= 'cf7-review';
		$install_link = wp_nonce_url(
							add_query_arg(
								array(
									'action' => $action,
									'plugin' => $slug
								),
								admin_url( 'update.php' )
							),
							$action.'_'.$slug
						);

		// $text = '<a rel="bookmark" target="_blank" class="thickbox" href="'. $link .'">' 
		// 		. __('Contact Form 7 Preview', 'smtp-mail')
		// 		. '</a>';

		_e('Please install `Contact Form 7 Preview` plugin to preview form (live).', 'smtp-mail');

		echo '<br /><br />';

		echo '<a rel="bookmark" href="'. $install_link .'" class="button button-primary" target="_parent">'
			. __('Install Now', 'smtp-mail')
			. '</a>';

		echo $html_visit;
		
		echo '<br /><br />';
	
	} else {
		
		$action = 'activate';
		$activate_link = wp_nonce_url(
							add_query_arg(
								array(
									'action' => $action,
									'plugin' => 'cf7-review/index.php',
									'plugin_status' => 'all',
									'paged' => 1,
									's' => '',
								),
								admin_url( 'plugins.php' )
							),
							$action.'-plugin_cf7-review_index.php'
						);
		
		// $activate_link = smtpmail_generate_plugin_activation_url('cf7-review/index.php');
		
		$activate_link = admin_url( 'plugins.php' );
		
		_e('Please activate `Contact Form 7 Preview` plugin to preview form (live).', 'smtp-mail');
		
		echo '<br /><br />';

		echo '<a rel="bookmark" href="'. $activate_link .'" class="button button-primary" target="_parent">'
			. __('Activate Now', 'smtp-mail')
			. '</a>';
		
		echo $html_visit;
		
		echo '<br /><br />';
		
	}
	
	echo '</div>';
}



/**
 * cf7 preview notices
 *
 * @since 1.0
 *
 */
function smtpmail_recommend_cf7_preview_notices()
{
	$file = 'cf7-review/index.php';

	if( defined('WPCF7_VERSION') == false ) {
		return;
	}

	$day = (int) current_time( 'Ymd' );

	if( 
		file_exists( ABSPATH . 'wp-content/plugins/' . $file ) == false && 
		(
			$day > 20190729
			|| smtpmail_compare_version( WPCF7_VERSION, '5.1.3', '>' )
		)
	) {

		$action = 'install-plugin';
		$slug 	= 'cf7-review';
		$install_link = wp_nonce_url(
							add_query_arg(
								array(
									'action' => $action,
									'plugin' => $slug
								),
								admin_url( 'update.php' )
							),
							$action.'_'.$slug
						);
		
		$plugin_link = admin_url('plugin-install.php?s=cf7-preview&tab=search&type=tag');

		?>
		<div class="smtpmail-notice notice notice-info is-dismissible" data-name="cf7_preview">
			<p>
				<strong><?php _e( 'Contact Form 7 Preview', 'smtp-mail' ); ?>:</strong>
					
				<?php _e( 'You have new feature.', 'smtp-mail' ); ?>
				
				<a rel="bookmark" href="<?php echo $install_link ?>">
					<strong><?php _e( 'Please install now', 'smtp-mail' ); ?></strong>
				</a>.
				
			</p>
		</div>
		<?php
	}

}
if( smtpmail_recommend_option('cf7p_noti') != 3 ) {
	add_action( 'admin_notices', 'smtpmail_recommend_cf7_preview_notices', 12 );
}

/**
 * cf7 preview notices ajax
 *
 * @since 1.0
 *
 */
function smtpmail_recommend_cf7_preview_notices_ajax()
{
	// Make your response and echo it.
	smtpmail_recommend_option('cf7p_noti', 3);

	// Don't forget to stop execution afterward.
	wp_die();
}
add_action( 'wp_ajax_cf7_preview', 'smtpmail_recommend_cf7_preview_notices_ajax' );

/**
 * SMTP Mail Recommend CF7 Review Option
 *
 * @since 1.0
 *
 */
function smtpmail_recommend_option( $key = '', $set = '' )
{
	$key = 'smtpmail_recommend_' . $key;
	
	$value = 1;

	if( $set!='' ) {
		update_option( $key, $set );
	} else {
		$value = (int) get_option( $key );
	}

	return $value;
}

// END OF SMTP Mail Recommend CF7 Review