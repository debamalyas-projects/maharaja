<?php
defined('ABSPATH') or die();

function smtpmail_url( $path = '' )
{
	return plugins_url( $path, smtpmail_index());
}

function smtpmail_assets_url( $path = '' )
{
	return smtpmail_url( '/media/'.$path );
}

function smtpmail_ver()
{
	/*
	 * since 
	 * '2018.12.24.10.20'; 
	 * '2019.01.12.10.20'; 
	 * '2019.05.12.10.20'; 
	 * '2019.06.09.10.47';
	 */
	return '2019.06.20.21.33';
}

function smtpmail_path( $path = '' )
{
	return dirname(smtpmail_index()) . ( substr($path,0,1) !== '/' ? '/' : '' ) . $path;
}

function smtpmail_include( $path_file = '' )
{
	if( $path_file!='' && file_exists( $p = smtpmail_path('includes/'.$path_file ) ) ) {
		require $p;
		return true;
	}
	return false;
}

function smtpmail_pbone_url( $path = '' )
{
	$site = 'http://photoboxone.com/';

	$utm = 'utm_term=smtp-mail&utm_medium=smtp-mail&utm_source=' . urlencode( $_SERVER['HTTP_HOST'] );

	if( strpos( $path, '?' ) > -1 ) {
		$path .= '&';
	} else {
		$path .= '?';
	}
	
	return esc_url( $site . $path . $utm );
}



// SMTP Mail send mail
/*
 * Since 1.1.2
 */
function smtpmail_sendmail( $info ){
	
	extract($info);
	
	$body = '';
	
	if( empty($subject) ) {
		$subject = 'Information at '.date('Y-m-d H:i:s') . ' - '.get_bloginfo('name');
	}
	
	$td_style = 'padding: 10px; border-bottom: 1px solid #ddd; border-right: 1px solid #ddd;';
	
	$body .= '<table style="width: 600px; max-width: 600px; border: 0; border-top: 1px solid #ddd; border-left: 1px solid #ddd; padding: 0; margin: 0 auto; border-spacing: 0; color: #ee3380; font-size: 20px; font-family: Tahoma; line-height: 24px; ">';
	foreach( $info as $key => $value ):
		$body .= "<tr>";
			$body .= '<td style="'.$td_style.'">';
			$body .= ucwords($key)." :";
			$body .= "</td>";
			$body .= '<td style="'.$td_style.'">';
			$body .= $value;
			$body .= "</td>";
		$body .= "</tr>";
	endforeach;
	$body .= "</table>";
	
	// die($body);
	
	if( isset($email) && $email!='' ) {
		if( empty($name) ) {
			$name = ucwords( array_shift( explode('@',$email) ) );
		}
		$headers[] = "From: $name <$email>";
	} else {
		$headers[] = 'From: '.get_bloginfo('name').' <noreply@'.$_SERVER['HOST_NAME'].'>';
	}
	
	return wp_mail( $email, $subject, $body, $headers );	
}

// SMTP Mail options [default]
function smtpmail_options( $key = '' )
{	
	$options = shortcode_atts(array(
		'isSMTP'		=> 1,
		'Port'			=> 25,
		'Host' 			=> 'localhost',
		'Username' 		=> '',
		'Password' 		=> '',
		'SMTPAuth' 		=> 0,// 1; // Force it to use Username and Password to authenticate
		'SMTPSecure' 	=> "",// ssl, tls // Choose SSL or TLS, if necessary for your server
		'SMTPAutoTLS' 	=> 0,
		'From' 			=> '',
		'FromName' 		=> '',
		'IsHTML' 		=> true,
		'SMTPDebug' 	=> 0, // 1: errors and messages; 2: messages only
		'save_data' 	=> 0, // Save data submit 
		'checked' 		=> 0, // Checked
	), (array)get_option('smtpmail_options'));

	if( $key!='' && isset($options[$key]) ) {
		return $options[$key];
	}

	return $options;
}

function smtpmail_update_option( $key = '', $value = '' )
{
	$options = smtpmail_options();

	if( $key!='' && isset($options[$key]) ) {
		$options[$key] = $value;
		update_option( 'smtpmail_options', $options );
	}
	
}

function smtpmail_phpmailer_setting( $phpmailer = false ) {
	
	if( $phpmailer == false ) return $phpmailer;
	
	//$phpmailer = null;
	//require ( ABSPATH . 'wp-includes/class-phpmailer.php');
	//$phpmailer = new PHPMailer;
	
	$options = smtpmail_options();
	extract($options);
	
	$phpmailer->ClearCustomHeaders();
	
	if( $isSMTP ) {
		$phpmailer->isSMTP();
	}
	
	foreach( $options as $key => $value ) {
		if( isset($phpmailer->$key) && $value!='' ) {
			$phpmailer->$key = $value;
		}
	}
	
	if( $IsHTML ) {
		$phpmailer->IsHTML(true);
		$message = $phpmailer->Body;
		if( $message == strip_tags($message) ) {
			// no contains HTML
			$phpmailer->Body = '<p>'.str_replace("\n", '</p><p>',$message).'</p>';
		}
	}

	// setup data before send mail
	if( isset($save_data) && $save_data == 1 ) {
		smtpmail_phpmailer_before_send( $phpmailer );
	}
	
	return $phpmailer;
}
add_action( 'phpmailer_init', 'smtpmail_phpmailer_setting', 10, 99 );

function smtpmail_phpmailer_before_send( $phpmailer = false ) 
{
	if( $phpmailer == false ) return $phpmailer;

	$_SERVER['SMTPMAIL_WP_MAIL_SENDING'] = true;

	$list = $phpmailer->getToAddresses();
	$emails = array();
	$names = array();
	if( is_array($list) ) {
		foreach( $list as $array ) {
			$emails[] = $array[0];
			$names[] = $array[1];
		}
	}

	$params = array_merge( $_POST, array(
		'ip' => $_SERVER['SERVER_ADDR'],
		'user_agent' => $_SERVER['HTTP_USER_AGENT'],
	) );
	
	$data = array(
					'from_name' => $phpmailer->FromName,
					'from_email' => $phpmailer->From,
					'to_email' => implode(';', $emails),
					'to_name' => implode(';', $names),
					'message' => $phpmailer->Body,
					'subject' => $phpmailer->Subject,
					'params' => json_encode($params),
					'created' => current_time( 'mysql' ),
				);
	
	smtpmail_insert_data( $data );

	// $_SERVER['SMTPMAIL_DATA'] = $data;
}

function smtpmail_wp_mail_failed( $wp_error = false )
{

	$_SERVER['SMTPMAIL_WP_MAIL_SENDING'] = false;

	$_SERVER['SMTPMAIL_WP_MAIL_FAILED'] = $wp_error;
	$msg = $wp_error->get_error_messages();
	
	$data = array(
		'status' => -1,
		'error' => json_encode($msg),
		//'params' => json_encode($wp_error),
		//'session_id' => json_encode($msg),
		'modified' => current_time( 'mysql' ),
	);
	
	smtpmail_update_data( $data );

}
add_action( 'wp_mail_failed', 'smtpmail_wp_mail_failed' );

function smtpmail_insert_data( $data = array() )
{
	
	if( count($data) == 0 ) return false;

	global $wpdb;

	$table_name = $wpdb->prefix . 'smtpmail_data';

	$formats = array();

	foreach( $data as $value ) {
		$formats[] = is_numeric($value)?'%d':'%s';
	}

	$wpdb->insert( 
		$table_name, 
		$data,
		$formats
	);

	return $_SERVER['SMTPMAIL_INSERT_ID'] = (int) $wpdb->insert_id;
}

function smtpmail_update_data( $data = array() )
{
	$id = intval( isset($_SERVER['SMTPMAIL_INSERT_ID']) ? $_SERVER['SMTPMAIL_INSERT_ID'] : 0 );

	if( count($data) == 0 || $id == 0 ) return false;

	global $wpdb;

	$table_name = $wpdb->prefix . 'smtpmail_data';

	$formats = array();

	foreach( $data as $value ){
		$formats[] = is_numeric($value)?'%d':'%s';
	}

	return $wpdb->update( 
						$table_name,
						$data,
						array( 'id' => $id ),
						$formats,
						array( '%d' )
					);
}

function smtpmail_install() 
{
	global $wpdb, $smtpmail_db_version;
	$table_name = $wpdb->prefix . 'smtpmail_data';
	$smtpmail_db_version = (float) get_option( 'smtpmail_db_version');
	
	$charset_collate = $wpdb->get_charset_collate();

	// DROP TABLE IF EXISTS $table_name;
	// CREATE TABLE IF NOT EXISTS $table_name

	$sql = "CREATE TABLE IF NOT EXISTS $table_name (
		id mediumint(9) NOT NULL AUTO_INCREMENT,
		from_name tinytext NOT NULL,
		from_email tinytext NOT NULL,
		to_name tinytext NOT NULL,
		to_email tinytext NOT NULL,
		subject tinytext NOT NULL,
		message text NOT NULL,
		params text,
		session_id text,
		status tinyint(1) NOT NULL DEFAULT 0,
		error text,
		created datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
		modified datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
		PRIMARY KEY  (id)
	) $charset_collate;";		
	
	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	dbDelta( $sql );

	
	if( $smtpmail_db_version == 1 ) {

		$smtpmail_db_version = 1.1;
		$wpdb->query( "DELETE FROM $table_name;" );
		
		update_option( 'smtpmail_db_version', $smtpmail_db_version );
	}


}
// register_activation_hook( __FILE__, 'smtpmail_install' ); // If root of plugin
register_activation_hook( smtpmail_index(), 'smtpmail_install' );

/**
 * This function runs when WordPress completes its upgrade process
 * It iterates through each plugin updated to see if ours is included
 * @param $upgrader_object Array
 * @param $options Array
 */
function smtpmail_upgrader_process_complete( $upgrader_object, $options ) {
	// The path to our plugin's main file
	$our_plugin = plugin_basename( smtpmail_index() );
	// If an update has taken place and the updated type is plugins and the plugins element exists
	if( $options['action'] == 'update' && $options['type'] == 'plugin' && isset( $options['plugins'] ) ) {
		// Iterate through the plugins being updated and check if ours is there
		foreach( $options['plugins'] as $plugin ) {
			if( $plugin == $our_plugin ) {
				// Set a transient to record that our plugin has just been updated
				// set_transient( 'wp_upe_updated', 1 );

				smtpmail_install();
			}
		}
	}
}
add_action( 'upgrader_process_complete', 'smtpmail_upgrader_process_complete', 10, 2 );

// Class PBOne
smtpmail_include('class-pbone.php');

function smtpmail_compare_version( $version_a = '', $version_b = '', $compare = '>' )
{
	if( $version_a == $version_b ) {
		return false;
	}

	$list_a = explode('.', $version_a);
	$list_b = explode('.', $version_b);

	$n = count($list_b);
	if( $n < count($list_a) ) {
		$n = count($list_a);
	}

	for( $i = 0; $i < $n; $i++ ) {
		$a = intval( isset($list_a[$i]) ? $list_a[$i] : 0 );
		$b = intval( isset($list_b[$i]) ? $list_b[$i] : 0 );
		if( $compare == '>' && $a > $b  ) {
			return true;
		} else if( $compare == '<' && $a < $b ) {
			return true;
		}
	}

	return false;
}