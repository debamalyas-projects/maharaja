<?php
defined('ABSPATH') or die();

/**
 * smtpmail notices
 *
 * @since 1.0
 *
 */
function smtpmail_notices()
{
	$file = 'cf7-review/index.php';


    $link = admin_url('options-general.php?page=smtpmail-setting&tab=list&refer=noti');

    ?>
    <div class="smtpmail-notice notice notice-info is-dismissible" data-name="cf7_preview">
        <p>
            <strong><?php _e( 'SMTP Mail', 'smtp-mail' ); ?>:</strong>
                
            <?php _e( 'You have new feature.', 'smtp-mail' ); ?>
            
            <a rel="bookmark" href="<?php echo $link ?>" target="_blank">
                <strong><?php _e( 'View location in data list', 'smtp-mail' ); ?></strong>
            </a>.
        </p>
    </div>
    <?php

}

$refer = sanitize_text_field( isset($_GET['refer']) ? $_GET['refer'] : '' );
if( $refer == 'noti' ) {
	smtpmail_notice_option('ver', 2);
} elseif( smtpmail_notice_option('ver') != 2 ) {
	add_action( 'admin_notices', 'smtpmail_notices', 12 );
}

/**
 * cf7 preview notices ajax
 *
 * @since 1.0
 *
 */
function smtpmail_notice_ajax()
{
	// Make your response and echo it.
	smtpmail_notice_option('ver', 2);

	// Don't forget to stop execution afterward.
	wp_die();
}
add_action( 'wp_ajax_smtpmail_notice', 'smtpmail_notice_ajax' );

/**
 * SMTP Mail Recommend CF7 Review Option
 *
 * @since 1.0
 *
 */
function smtpmail_notice_option( $key = '', $set = '' )
{
	$key = 'smtpmail_notice_' . $key;
	
	$value = 1;

	if( $set!='' ) {
		update_option( $key, $set );
	} else {
		$value = (int) get_option( $key );
	}

	return $value;
}