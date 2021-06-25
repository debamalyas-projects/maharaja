<?php
defined('ABSPATH') or die();

$pagenow 	= sanitize_text_field( isset($GLOBALS['pagenow'])?$GLOBALS['pagenow']:'' );
if( $pagenow == 'plugins.php' ){
	
	function smtpmail_plugin_actions( $actions, $plugin_file, $plugin_data, $context ) {
		$url_setting = admin_url('options-general.php?page=smtpmail-setting');
		
		array_unshift($actions, '<a href="https://paypal.me/photoboxone" target="_blank">'.__("Donate")."</a>");
		array_unshift($actions, '<a href="'.smtpmail_pbone_url('contact').'" target="_blank">'.__("Support")."</a>");
		array_unshift($actions, "<a href=\"$url_setting\">".__("Settings")."</a>");
		return $actions;
	}
	
	add_filter("plugin_action_links_".plugin_basename(smtpmail_index()), "smtpmail_plugin_actions", 10, 4);
}

/* ADD SETTINGS PAGE
------------------------------------------------------*/
function smtpmail_add_options_page() {
	add_options_page(
		'SMTP Mail Settings',
		'SMTP Mail',
		'manage_options',
		'smtpmail-setting',
		'smtpmail_setting_display'
	);
}
add_action('admin_menu','smtpmail_add_options_page');

/* SECTIONS - FIELDS
------------------------------------------------------*/
function smtpmail_init_theme_opotion() 
{
	// add Setting
	add_settings_section(
		'smtpmail_options_section',
		'SMTP Mail Options',		
		'smtpmail_options_section_display',
		'smtpmail-options-section'
	);
	
	register_setting( 'smtpmail_settings','smtpmail_options');
	
	// pbone Styles
	wp_enqueue_style( 	'pbone', smtpmail_url('/media/pbone.css'), '', smtpmail_ver() );
	
	// Styles
	wp_enqueue_style( 	'smtpmail-admin-style', smtpmail_url('/media/admin.css'), '', smtpmail_ver() );
	wp_enqueue_script( 	'smtpmail-admin-script', smtpmail_url('/media/admin.js'), array('jquery'), smtpmail_ver(), true );
	
}
add_action('admin_init', 'smtpmail_init_theme_opotion');

/* CALLBACK
------------------------------------------------------*/
function smtpmail_setting_display()
{	
	$options = smtpmail_options();
	// extract($options);

	$tab 		= sanitize_text_field( isset($_GET['tab']) ? $_GET['tab'] : '' );
	$orderby 	= sanitize_text_field( isset($_GET['orderby']) ? $_GET['orderby'] : '' );
	$active  	= 1;

	if( $orderby != '' ) {
		$tab = 'list';
	}

	// global $PBOne;
	// $plugins = $PBOne ? $PBOne->get_plugins('smtp-mail') : '';
	$url_setting = admin_url('options-general.php?page=smtpmail-setting');
?>
	<h2><?php _e( 'SMTP Mail Settings', 'smtp-mail' ); ?></h2>
	<?php 
			// smtpmail_help_links();
			// smtpmail_donate_text();
		?>
	<div class="wrap smtpmail_settings clearfix">
		<div class="smtpmail_advanced clearfix">
			<div class="smtpmail_tabmenu clearfix">
				<ul>
					<li <?php echo $tab==''?' class="active"':'';?>>
						<a href="<?php echo $url_setting?>"><?php _e( 'General', 'smtp-mail' ); ?></a>
					</li>
					<li <?php echo $tab=='test'?' class="active"':'';?>>
						<a href="<?php echo $url_setting.'&tab=test'?>"><?php _e( 'Send test', 'smtp-mail' ); ?></a>
					</li>
					<li <?php echo $tab=='list'?' class="active"':'';?>>
						<a href="<?php echo $url_setting.'&tab=list'?>"><?php _e( 'Data', 'smtp-mail' ); ?></a>
					</li>
					<li <?php echo $tab=='more'?' class="active"':'';?>>
						<a href="<?php echo $url_setting.'&tab=more'?>"><?php _e( 'Support', 'smtp-mail' ); ?></a>
					</li>
				</ul>
			</div>
			<div class="smtpmail_tabitems clearfix">
				<div class="smtpmail_tabitem item-1<?php echo $tab==''?' active':'';?>">
					<?php smtpmail_setting_form($options); ?>
				</div>
				<div class="smtpmail_tabitem item-2<?php echo $tab=='test'?' active':'';?>">
					<?php smtpmail_sendmail_form($options); ?>
				</div>
				<div class="smtpmail_tabitem item-3<?php echo $tab=='list'?' active':'';?>">
					<?php smtpmail_data_list($options); ?>
				</div>
				<div class="smtpmail_tabitem item-4<?php echo $tab=='more'?' active':'';?>">
					<?php 
					// echo $plugins; 
					smtpmail_include('plugins.html');
					?>
				</div>
			</div>
		</div>
		<?php if( $tab != 'list' && $tab != 'more' ):?>
		<div class="smtpmail_sidebar clearfix">
			<?php 
				smtpmail_help_links(); 
				smtpmail_donate_text();
			?>
		</div>
		<?php 
		endif;
		?>
	</div>
	<?php 
	if( $tab == 'list' || $tab == 'more' ) {
		echo '<div class="wrap smtpmail_settings smtpmail_settings_footer">';
		smtpmail_help_links(); 
		smtpmail_donate_text(); 
		echo '</div>';
	}
	?>
<?php
}

function smtpmail_help_links( $show = false )
{
?>
	<div class="smtpmail_sidebar_box" >
		<h4><?php _e( 'Do you need help?', 'smtp-mail' ); ?></h4>
		<ol>
			<li>
				<a href="<?php echo smtpmail_pbone_url('how-to-configure-an-smtp-mail-plugin');?>" target="_blank" rel="help" title="<?php _e( 'How to configure an SMTP Mail plugin?', 'smtp-mail' ); ?>">
					<?php _e( 'Help', 'smtp-mail' ); ?>
				</a>
			</li>
			<li>
				<a href="<?php echo smtpmail_pbone_url('contact');?>" target="_blank" rel="help">			
					<?php _e( 'Support', 'smtp-mail' ); ?>
				</a>
			</li>
			<li>
				<a href="<?php echo smtpmail_pbone_url();?>" target="_blank" rel="author">
					<?php _e( 'About', 'smtp-mail' ); ?>
				</a>
			</li>
		</ol>
	</div>
<?php
}

function smtpmail_donate_text()
{
?>
	<div class="smtpmail_sidebar_box">
		<h4>
			<?php _e( 'You can donate to us by visiting our website. Thank you for watching.', 'smtp-mail' ); ?>
		</h4>
		<p>
			<div class="smtpmail-icon-click">
				<div class="dashicons dashicons-arrow-right-alt"></div>
			</div>
			<a href="<?php echo smtpmail_pbone_url('how-to-configure-an-smtp-mail-plugin/?donate=smtp-mail');?>" target="_blank" rel="help">
				<?php //_e( 'How to configure an SMTP Mail plugin?', 'smtp-mail' ); ?>
				<?php _e( 'Visiting our website', 'smtp-mail' ); ?>
			</a>
		</p>
		<p>
			<?php _e( 'You can donate by PayPal.', 'smtp-mail' ); ?>
		</p>
		<p align=center>
			<a href="https://paypal.me/photoboxone" target="_blank" rel="help" class="button button-primary">	
				<?php _e( 'Donate', 'smtp-mail' ); ?>
			</a>
		</p>
		<p>
			<?php _e( 'Thank you for using SMTP Mail.', 'smtp-mail' ); ?>
		</p>
	</div>
<?php
}

function smtpmail_setting_form( $options = array() )
{
	extract($options);
	// var_dump($options);
?>
	<form action="options.php" method="post" class="smtpmail_setting_form">
		<?php settings_fields('smtpmail_settings' ); ?>
		<p>
			<label for="smtpmail_options_isSMTP"><?php _e( 'SMPT Enable', 'smtp-mail' ); ?>:</label>
			<select name="smtpmail_options[isSMTP]" id="smtpmail_options_isSMTP">
				<option value="0"><?php _e( 'No', 'smtp-mail' ); ?></option>
				<option value="1"<?php echo ($isSMTP?" selected":"");?>><?php _e( 'Yes', 'smtp-mail' ); ?></option>
			</select>
		</p>
		<p>
			<label for="smtpmail_options_SMTPSecure"><?php _e( 'SMTP Secure', 'smtp-mail' ); ?>:</label>
			<select name="smtpmail_options[SMTPSecure]" id="smtpmail_options_SMTPSecure">
				<option value="">None</option>
				<option value="ssl"<?php echo ($SMTPSecure=='ssl'?" selected":"");?>>SSL</option>
				<option value="tls"<?php echo ($SMTPSecure=='tls'?" selected":"");?>>TLS</option>
			</select>
		</p>
		<p>
			<label for="smtpmail_options_SMTPAuth"><?php _e( 'SMTP Auth', 'smtp-mail' ); ?>:</label>
			<select name="smtpmail_options[SMTPAuth]" id="smtpmail_options_SMTPAuth">
				<option value="0"><?php _e( 'No', 'smtp-mail' ); ?></option>
				<option value="1"<?php echo ($SMTPAuth?" selected":"");?>><?php _e( 'Yes', 'smtp-mail' ); ?></option>
			</select>
		</p>
		<p>
			<label for="smtpmail_options_Port"><?php _e( 'Port', 'smtp-mail' ); ?>: (25, 465, 587)</label>
			<input value="<?php echo $Port;?>" type="number" name="smtpmail_options[Port]" id="smtpmail_options_Port" class="inputbox" placeholder="25, 465, 587" />
		</p>
		<p>
			<label for="smtpmail_options_Host"><?php _e( 'Host (Server)', 'smtp-mail' ); ?>:</label>
			<input value="<?php echo $Host;?>" type="text" name="smtpmail_options[Host]" id="smtpmail_options_Host" class="inputbox" placeholder="mail.domain.com" />
		</p>
		<p>
			<label for="smtpmail_options_Username"><?php _e( 'Username', 'smtp-mail' ); ?>:</label>
			<input value="<?php echo $Username;?>" type="text" name="smtpmail_options[Username]" id="smtpmail_options_Username" class="inputbox" placeholder="username or noreply@domain.com" />
		</p>
		<p>
			<label for="smtpmail_options_Password"><?php _e( 'Password', 'smtp-mail' ); ?>:</label>
			<input value="<?php echo $Password;?>" type="password" name="smtpmail_options[Password]" id="smtpmail_options_Password" class="inputbox" placeholder="pass@2371627"/>
		</p>
		<p>
			<label for="smtpmail_options_From"><?php _e( 'From Email', 'smtp-mail' ); ?>:</label>
			<input value="<?php echo $From;?>" type="email" name="smtpmail_options[From]" id="smtpmail_options_From" class="inputbox" placeholder="noreply@domain.com" />
		</p>
		<p>
			<label for="smtpmail_options_FromName"><?php _e( 'From Name', 'smtp-mail' ); ?>:</label>
			<input value="<?php echo $FromName;?>" type="text" name="smtpmail_options[FromName]" id="smtpmail_options_FromName" class="inputbox" placeholder="<?php _e( 'Site name', 'smtp-mail' ); ?>" />
		</p>
		<p>
			<label for="smtpmail_options_IsHTML"><?php _e( 'Use HTML content', 'smtp-mail' ); ?>:</label>
			<select name="smtpmail_options[IsHTML]" id="smtpmail_options_IsHTML">
				<option value="0"><?php _e( 'No', 'smtp-mail' ); ?></option>
				<option value="1"<?php echo ($IsHTML?" selected":"");?>><?php _e( 'Yes', 'smtp-mail' ); ?></option>
			</select>
		</p>
		<p>
			<label for="smtpmail_options_SMTPAutoTLS"><?php _e( 'SMTP Auto TLS', 'smtp-mail' ); ?>:</label>
			<select name="smtpmail_options[SMTPAutoTLS]" id="smtpmail_options_SMTPAutoTLS">
				<option value="0"><?php _e( 'No', 'smtp-mail' ); ?></option>
				<option value="1"<?php echo ($SMTPAutoTLS?" selected":"");?>><?php _e( 'Yes', 'smtp-mail' ); ?></option>
			</select>
		</p>
		<p>
			<label for="smtpmail_options_SMTPDebug"><?php _e( 'SMTP Debug', 'smtp-mail' ); ?>:</label>
			<select name="smtpmail_options[SMTPDebug]" id="smtpmail_options_SMTPDebug">
				<option value="0"><?php _e( 'No', 'smtp-mail' ); ?></option>
				<option value="1"<?php echo ($SMTPDebug==1?" selected":"");?>>Errors, Messages</option>
				<option value="2"<?php echo ($SMTPDebug==2?" selected":"");?>>Messages only</option>
			</select>
		</p>
		<p>
			<label for="smtpmail_options_save_data"><?php _e( 'Save Data SendMail', 'smtp-mail' ); ?>:</label>
			<select name="smtpmail_options[save_data]" id="smtpmail_options_save_data">
				<option value="0"><?php _e( 'No', 'smtp-mail' ); ?></option>
				<option value="1"<?php echo ($save_data?" selected":"");?>><?php _e( 'Yes', 'smtp-mail' ); ?></option>
			</select>
		</p>
		
		<?php submit_button(); ?>
	</form>
<?php
}

function smtpmail_sendmail_form()
{	
	$lips = array('Lorem ipsum dolor sit amet, consectetur adipiscing elit.','Ut fermentum magna quis mauris dictum, in elementum diam maximus.','Praesent pulvinar erat in velit tincidunt, quis fermentum mauris maximus.','Cras vulputate metus id ornare vehicula.','Morbi ultricies neque a rutrum euismod.','Sed varius nisi sit amet nunc tincidunt facilisis.','Maecenas consequat tellus sit amet massa facilisis tincidunt.','Etiam at eros congue, feugiat nisl commodo, interdum metus.','Duis iaculis massa sed nisl euismod sollicitudin.','Ut vestibulum ex sit amet odio eleifend bibendum.','Nam ultrices dolor vel ipsum aliquam venenatis.','Fusce vel lacus ac justo sollicitudin vestibulum.','Nullam vel lectus quis libero tempus pharetra maximus sed ipsum.','Nam non arcu sed dui blandit varius eget ac arcu.','Aliquam congue felis in efficitur vulputate.','Curabitur venenatis mauris eget tristique iaculis.','Donec in lectus interdum, rutrum massa nec, malesuada diam.','Mauris tempus odio in ultrices iaculis.','Quisque vitae arcu ornare, volutpat eros porttitor, rutrum purus.','Integer ac mauris rutrum erat luctus consequat.','Sed non nisl nec nibh aliquet dapibus.','Morbi sit amet lacus lacinia, pulvinar quam et, hendrerit diam.','Nunc dapibus lacus id vehicula tempus.','Pellentesque sit amet quam faucibus lacus cursus convallis at sed ipsum.','Nam consectetur massa a semper eleifend.','Proin fringilla ante ut dui aliquam venenatis.','Phasellus accumsan ante sit amet velit imperdiet efficitur.','Vivamus posuere arcu non sem cursus commodo.');
	
	$current_user = wp_get_current_user();
	if ( !($current_user instanceof WP_User) ) return '';
	
	$name 		= sanitize_text_field( isset($_POST['name']) ? $_POST['name'] : $current_user->display_name );
	$email 		= sanitize_email( isset($_POST['email']) ? $_POST['email'] : $current_user->user_email );
	//$subject 	= sanitize_text_field( isset($_POST['subject']) ? $_POST['subject'] : '' );
	//$message 	= sanitize_text_field( isset($_POST['message']) ? $_POST['message'] : '' );
	
	$page = sanitize_text_field( isset($_REQUEST['page'])?$_REQUEST['page']:'' );
	
	?>
	<form action="<?php echo esc_url( admin_url('options-general.php?page='.$page.'&tab=test' ) )?>" method="post" class="smtpmail_sendtest_form">
		<p>
			<label><?php _e( 'Name', 'smtp-mail' ); ?>:</label>
			<input name="name" type="text" value="<?php echo $name; ?>" class="inputbox required" />
		</p>
		<p>
			<label><?php _e( 'Email', 'smtp-mail' ); ?>:</label>
			<input name="email" type="email" value="<?php echo $email; ?>" class="inputbox required" />
		</p>
		<p>
			<label><?php _e( 'Subject', 'smtp-mail' ); ?>:</label>
			<input name="subject" type="text" value="<?php echo get_bloginfo('name').' test at '. date('Y-m-d H:i:s'); ?>" class="inputbox required" />
		</p>
		<p>
			<label><?php _e( 'Message', 'smtp-mail' ); ?>:</label>
			<textarea name="message" id="message" rows="8" cols="40" class="textareabox required"><?php echo $lips[rand(0, count($lips)-1)]; ?></textarea>
		</p>
		<p class="buttons">
            <label> </label>
            <input type="submit" name="send_test" id="send_test" class="button button-primary" value="Send">
        </p>
		<?php // submit_button('Send', 'submit', 'send_test' ); ?>
	</form>
<?php
}

function smtpmail_data_list( $options = array() )
{	
	if( smtpmail_include('data-list-table.php') ) {
		smtpmail_render_customer_list_page();
	}

	/*
	extract($options);

	// $page 		= sanitize_text_field( isset($_GET['page']) ? $_GET['page'] : 0 );

	if( isset($save_data) && $save_data == 1 && smtpmail_include('data-list-table.php') ) :
		smtpmail_render_customer_list_page();
	else:
	?>
	<p><?php _e( 'You need enable option `Save data SendMail` in General tab.', 'smtp-mail' ); ?></p>
	<?php
	endif;
	*/
}

function smtpmail_admin_notice() 
{
	$pagenow 	= sanitize_text_field( isset($GLOBALS['pagenow'])?$GLOBALS['pagenow']:'' );
	$send_test 	= sanitize_text_field( isset($_POST['send_test']) ? $_POST['send_test'] : '' );
	
	if( $pagenow!='options-general.php' || count($_POST) == 0 || $send_test!='Send' ) return '';
	
	$name 		= sanitize_text_field( isset($_POST['name']) ? $_POST['name'] : '' );
	$email 		= sanitize_email( isset($_POST['email']) ? $_POST['email'] : '' );
	$subject 	= sanitize_text_field( isset($_POST['subject']) ? $_POST['subject'] : '' );
	$message 	= sanitize_textarea_field( isset($_POST['message']) ? $_POST['message'] : '' );
	
	if( $email!='' ) {
		if( $name == '' ) {
			$name = ucwords( array_shift( explode('@',$email) ) );
		}
		$headers[] = "From: $name <$email>";
	} else {
		$headers[] = 'From: '.get_bloginfo('name').' <noreply@'.$_SERVER['HTTP_HOST'].'>';
	}
	
	if( wp_mail( $email, $subject, $message, $headers ) ): ?>
	<div class="notice notice-success is-dismissible">
		<p><?php _e( 'Send mail successful!', 'smtp-mail' ); ?></p>
	</div>
	<?php else:?>
	<div class="notice notice-error">
		<p><?php _e( 'Send mail error!', 'smtp-mail' ); ?></p>
	</div>
	<?php endif;
}
add_action( 'admin_notices', 'smtpmail_admin_notice' );

global $PBOne;

// var_dump($PBOne);

if( isset($PBOne) && $PBOne && $PBOne->check_plugin_active('contact-form-7/wp-contact-form-7.php') ) {

	smtpmail_include('contact-form-7-extensions.php');

}