<?php
/*
 * Plugin Name: WordPress ShortCode
 * Description: Create your WordPress shortcode.
 * Version: 1.0
 * Author: InkThemes
 * Author URI: http://inkthemes.com
 */
 

// Example 1 : WP Shortcode to display form on any page or post.
 function form_creation(){
	 ob_start();
?>
<form>
First name: <input type="text" name="firstname"><br>
Last name:  <input type="text" name="lastname"><br>
Message:    <textarea name="message"> Enter text here...</textarea>
</form>
<?php
	$html=ob_get_contents();
	ob_end_clean();
	return $html;
}
add_shortcode('cleopatra_shortcode', 'form_creation');
function wp_header_custom(){
	ob_start();
	wp_head();
	$html=ob_get_contents();
	ob_end_clean();
	return $html;
}
add_shortcode('wp_header_custom', 'wp_header_custom');
function wp_footer_custom(){
	ob_start();
	wp_footer();
	$html=ob_get_contents();
	ob_end_clean();
	return $html;
}
add_shortcode('wp_footer_custom', 'wp_footer_custom');

/*Working Shortcodes*/
function header_login_menu(){
	ob_start();
	if(isset($_SESSION['user'])){
		if($_SESSION['user']['type']=='customer'){
?>
	<li class="menu-has-children"><a href="javascript:void(0);">My Account</a>
		<ul>
			 <li><a href="<?php echo get_site_url(); ?>/my-account-customer/">Account Edit</a></li>
			 <li><a href="<?php echo get_site_url(); ?>/change-password-customer/">Change Password</a></li>
			<!-- <li><a href="javascript:void(0);">Cart</a></li>-->
			 <li><a href="<?php echo get_site_url(); ?>/order/">Orders</a></li>
			 <li><a href="<?php echo get_site_url(); ?>/customer-logout/">Logout</a></li>
		</ul>
	</li>
<?php
		}
		else if($_SESSION['user']['type']=='corporate'){
?>
	<li class="menu-has-children"><a href="javascript:void(0);">My Account</a>
		<ul>
			 <li><a href="<?php echo get_site_url(); ?>/my-account-corporate/">Account Edit</a></li>
			 <li><a href="<?php echo get_site_url(); ?>/change-password-corporate/">Change Password</a></li>
			 <!--<li><a href="javascript:void(0);">Cart</a></li>-->
			 <li><a href="<?php echo get_site_url(); ?>/order/">Orders</a></li>
			 <li><a href="<?php echo get_site_url(); ?>/corporate-logout/">Logout</a></li>
		</ul>
	</li>
<?php
		}
		
		else if($_SESSION['user']['type']=='travelagency'){
?>
	<li class="menu-has-children"><a href="javascript:void(0);">My Account</a>
		<ul>
			 <li><a href="<?php echo get_site_url(); ?>/my-account-travel-agent/">Account Edit</a></li>
			 <li><a href="<?php echo get_site_url(); ?>/change-password-travel-agent/">Change Password</a></li>
			 <li><a href="<?php echo get_site_url(); ?>/commission-distribution/">Commision Distribution</a></li>
			<!-- <li><a href="javascript:void(0);">Cart</a></li>-->
			 <li><a href="<?php echo get_site_url(); ?>/order/">Orders</a></li>
			 <li><a href="<?php echo get_site_url(); ?>/travel-agent-logout/">Logout</a></li>
		</ul>
	</li>
<?php
		}else if($_SESSION['user']['type']=='progotiagent'){
			?>
	<li class="menu-has-children"><a href="javascript:void(0);">Progoti Agent</a>
		<ul>
			 <li><a href="<?php echo get_site_url(); ?>/loan-customer-list/">Loan Customer List</a></li>
			 <li><a href="<?php echo get_site_url(); ?>/progoti-agent-logout//">Logout</a></li>
		</ul>
	</li>
<?php
		}else{
			echo 'Working';
			}
	}else{
?>
	<li class="menu-has-children"><a href="javascript:void(0);">LogIn</a>
		<ul>
			 <li><a href="<?php echo get_site_url(); ?>/customer-login/">Customer</a></li>
			 <li><a href="<?php echo get_site_url(); ?>/travel-agent-login">Travel Agents</a></li>
			 <li><a href="<?php echo get_site_url(); ?>/corporate-login/">Corporate</a></li>
			 <li><a href="<?php echo get_site_url(); ?>/progoti-agent-login/">Progoti Agent Login</a></li>
			 <li><a href="https://tatrooms.com/wp-admin/">Hotel</a></li>
		</ul>
	</li>
	<li><a href="javascript:void(0);">Register</a>
	  <ul>
		 <li><a href="<?php echo get_site_url(); ?>/customer-registration/">Customer</a></li>
		 <li><a href="<?php echo get_site_url(); ?>/travel-agent-registration/">Travel Agents</a></li>
		 <li><a href="<?php echo get_site_url(); ?>/corporate-registration/">Corporate</a></li>
		 <li><a href="https://tatrooms.com/vendor-registration/">Hotel</a></li>
	  </ul>
	</li>
<?php
	}
	$html=ob_get_contents();
	ob_end_clean();
	return $html;
}
add_shortcode('cleopatra_header_login_menu', 'header_login_menu');

function footer_login_menu(){
	ob_start();
	if(isset($_SESSION['user'])){
	    if($_SESSION['user']['type']=='customer'){
?>
    <li><a href="<?php echo get_site_url(); ?>/my-account-customer/">Account Edit</a></li>
    <li><a href="<?php echo get_site_url(); ?>/change-password-customer/">Change Password</a></li>
   <!-- <li><a href="javascript:void(0);">Cart</a></li>-->
    <li><a href="javascript:void(0);">Orders</a></li>
    <li><a href="<?php echo get_site_url(); ?>/customer-logout/">Logout</a></li>
<?php
	}else if($_SESSION['user']['type']=='corporate'){
		?>
			<li class="menu-has-children"><a href="javascript:void(0);">My Account</a>
				<ul>
					 <li><a href="<?php echo get_site_url(); ?>/my-account-corporate/">Account Edit</a></li>
					 <li><a href="<?php echo get_site_url(); ?>/change-password-corporate/">Change Password</a></li>
					 <!--<li><a href="javascript:void(0);">Cart</a></li>-->
					 <li><a href="<?php echo get_site_url(); ?>/order/">Orders</a></li>
					 <li><a href="<?php echo get_site_url(); ?>/corporate-logout/">Logout</a></li>
				</ul>
			</li>
		<?php
	}
	else if($_SESSION['user']['type']=='travelagency'){
		?>
			<li class="menu-has-children"><a href="javascript:void(0);">My Account</a>
				<ul>
					 <li><a href="<?php echo get_site_url(); ?>/my-account-travel-agent/">Account Edit</a></li>
					 <li><a href="<?php echo get_site_url(); ?>/change-password-travel-agent/">Change Password</a></li>
					 <li><a href="<?php echo get_site_url(); ?>/commission-distribution/">Commision Distribution</a></li>
					<!-- <li><a href="javascript:void(0);">Cart</a></li>-->
					 <li><a href="<?php echo get_site_url(); ?>/order/">Orders</a></li>
					 <li><a href="<?php echo get_site_url(); ?>/travel-agent-logout/">Logout</a></li>
				</ul>
			</li>
		<?php
	}
	else if($_SESSION['user']['type']=='progotiagent'){
		?>
		<li class="menu-has-children"><a href="javascript:void(0);">Progoti Agent</a>
		<ul>
			<li><a href="<?php echo get_site_url(); ?>/loan-customer-list/">Loan Customer List</a></li>
			<li><a href="<?php echo get_site_url(); ?>/progoti-agent-logout//">Logout</a></li>
		</ul>
		</li>
<?php
	}
	else{
            echo 'working';
        }
	}else{
?>
	<li><a href="<?php echo get_site_url(); ?>/customer-login/">Customer login</a></li>
	<li><a href="<?php echo get_site_url(); ?>/corporate-login/">Corporate login</a></li>
	<li><a href="<?php echo get_site_url(); ?>/travel-agent-login/">Travel Agents login</a></li>
	<li><a href="<?php echo get_site_url(); ?>/progoti-agent-login/">Progoti Agent Login</a></li>
	<li><a href="https://tatrooms.com/wp-admin/">Hotel login</a></li>
<?php
	}
	$html=ob_get_contents();
	ob_end_clean();
	return $html;
}
add_shortcode('cleopatra_footer_login_menu', 'footer_login_menu');

function message(){
    ob_start();
    if(isset($_SESSION['message'])){
?>
    <section id="contact">
    	<div class="container">
    	  <h2 class="h2 ac" style="color: green !important; font-weight: bold !important;"><?php echo $_SESSION['message']; ?></h2>
    	</div>
    </section>
<?php
    unset($_SESSION['message']);
    }else if(isset($_SESSION['search_message'])){
?>
    <section id="contact">
    	<div class="container">
    	  <h2 class="h2 ac" style="color: red !important; font-weight: bold !important;"><?php echo $_SESSION['search_message']; ?></h2><br>
    	  <a href="<?php echo get_site_url(); ?>" style="color: blue;">Search Again</a>
    	</div>
    </section>
<?php
    unset($_SESSION['search_message']);
        }else{
?>
    <section id="contact">
    	<div class="container">
    	  <h2 class="h2 ac" style="color: yellow !important; font-weight: bold !important;">Are you looking for something? If so, contact us.</h2>
    	</div>
    </section>
<?php
    }
    $html=ob_get_contents();
	ob_end_clean();
	return $html;
}

add_shortcode('cleopatra_message', 'message');

function ajax(){
    ob_start();
    if($_POST['channel']=='get_room_by_id'){
        get_room_by_id($_POST);
    }else{
        echo 'Unauthorized page.';
    }
    $html=ob_get_contents();
	ob_end_clean();
	return $html;
}

add_shortcode('cleopatra_ajax', 'ajax');

function get_room_by_id($post){
    $room_id = $post['room_id'];
    global $wpdb;
    $table_name = $wpdb->prefix . "nlt_hotel_room_listing";
    
    $rows = $wpdb->get_results("SELECT * from $table_name WHERE `id`='".$room_id."'");
    
    $array = array();
    foreach($rows AS $key=>$val){
        $array[$key] = $val;
    }
    
    echo html_entity_decode(json_encode($array));
}

// This is function for progoti agents

function progoti_client_list(){
	ob_start();
	global $wpdb;
	
	$agent_id = $_SESSION['user']['user_id'];

	$table_name1 = $wpdb->prefix . "money_collection";
	$table_name2 = $wpdb->prefix . "progati_loan";
	$table_name3 = $wpdb->prefix . "progoti_collection_egent";
		
	$rows = $wpdb->get_results("SELECT * from $table_name1,$table_name2,$table_name3 WHERE $table_name1.`loan_taker_id`=$table_name2.`id` AND $table_name1.`agent_id`=$table_name3.`id` AND $table_name1.`agent_id`='".$agent_id."'");


	?>
	<head>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.css">
	<link rel="stylesheet" href="https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap4.min.css">
	<script src="https://code.jquery.com/jquery-3.3.1.js"></script>
	<script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
	<script src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js"></script>
	<style>
		.btn{
			background-color:#7163e2;
			padding: 6px;
			border: none;
			cursor: pointer;
			font-size: 18px;
			width:100px;
			border-radius:6px;
			color:white;
			border:2px solid white;
		}
		a{
			text-decoration:none;
		}
		.btn:hover{
			color:#7163e2;
			background-color:white;
			border:2px solid #7163e2;
		}
	</style>
	</head>
	<body>
	<script>
	$(document).ready(function() {
	    $('#example').DataTable();
	} );
	</script>
	<table id="example" class="table table-striped table-bordered" style="width:100%">
		<thead>
            <tr align="center">
				<th align="center">Serial Number</th>
				<th align="center">Client Name</th>
                <th align="center">Loan Amount</th>
				<th align="center">EMI Due</th>
                <th align="center">Paid Amount</th>
				<th align="center">Action</th>
            </tr>
        </thead>
    	<tbody>
		<?php
	
		$i=1;
		foreach($rows as $row){
			
			$file_ulpoad_database = $row->file_ulpoad;
			$file_ulpoad = "/wp-content/plugins/Progoti Loan/upload/".$row->file_ulpoad;
			$base_url = "https://tatrooms.com";
			$file_ulpoad = $base_url.$file_ulpoad;
			$wp_agent_collection = $wpdb->prefix . "agent_collection";
			$query = $wpdb->get_results("SELECT * from $wp_agent_collection WHERE `loan_taker_id`='".$row->loan_taker_id."'");
			$count = count($query);
			?>
			<tr>
			<td align="center"><?php echo $i; ?></td>
			<td align="center"><?php echo $row->client_first_name.' '.$row->client_last_name; ?></td>
			<td align="center"><?php echo $row->loan_amount; ?></td>
			<td align="center"><?php echo $row->emi_due; ?></td>
			<?php
			
			if($count>0){
				?>
				<td align="center"><?php echo $row->amount_paid+$query[0]->emi_amount; ?></td>
				<td align="center"><?php echo Paid; ?></td>
				<?php
			}else{
				?>
				<td align="center"><?php echo $row->amount_paid; ?></td>
				<td align="center"><a href="https://tatrooms.com/loan-collection/?id=<?php echo $row->loan_taker_id.','.$row->emi_due; ?>" class="btn">Pay</a></td>
				<?php
			}
			?>
			</tr>
			<?php
			$i++;
		}
		?>
			<script>
			function printInvoice(id){
				var prtContent = document.getElementById(id);
				var WinPrint = window.open('', '', 'left=0,top=0,width=800,height=900,toolbar=0,scrollbars=0,status=0');
				WinPrint.document.write(prtContent.innerHTML);
				WinPrint.document.close();
				WinPrint.focus();
				WinPrint.print();
				WinPrint.close();
			}
			</script>
		</tbody>
		</table>
		</body>
	<?php

	$html=ob_get_contents();
	ob_end_clean();
	return $html;
}
add_shortcode('cleopatra_loan_listing', 'progoti_client_list');

function loan_collection(){
    ob_start();
	global $wpdb;

	$agent_id = $_SESSION['user']['user_id'];
	$loan_taker_id_emi=$_GET['id'];
	$loan_taker_id_emi=explode(',',$loan_taker_id_emi);
	$loan_taker_id = $loan_taker_id_emi[0];
	$emi = $loan_taker_id_emi[1];
	$status = 1;
	$paid_amount_status = $status.','.$emi;
	
	$date = date("Y/m/d");
	
	$query = $wpdb->query($wpdb->prepare("INSERT INTO `".$wpdb->prefix."agent_collection` (`date`,`emi_amount`,`agent_id`,`loan_taker_id`) VALUES (%s,%s,%s,%s)",$date,$emi,$agent_id,$loan_taker_id));


	/*$email_template = "
			Dear Mr/Mrs ".$loan_taker_id. " your emi of ₹ ".$emi." has been paid to Mr/Mrs ".$agent_id
			;
	$headers = "MIME-Version: 1.0" . "\r\n";
	$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

	// More headers
	$headers .= 'From: <admin@winon11.com>' . "\r\n";
	
	$email = "pamirghosh1998@gmail.com";
	mail($email,'User enquery email',$email_template,$headers);*/

	$url = get_site_url().'/loan-customer-list/';
	wp_redirect( $url );
			
    $html=ob_get_contents();
	ob_end_clean();
	return $html;
}

add_shortcode('cleopatra_loan_collection', 'loan_collection');
// This is function for progoti agents

function cron(){
    ob_start();
    global $wpdb;
    $type=$_GET['type'];
    if($type=='order_claim'){
        $rows_settings = $wpdb->get_results("SELECT * from `wp_settings`");
        $tatroom_commission_percent = $rows_settings[0]->tatroom_commission;
        $vendor_commission_percent = $rows_settings[0]->vendor_commission;
        $agent_commission_percent = $rows_settings[0]->agent_commission;
        $rows = $wpdb->get_results("SELECT * from `wp_order` WHERE  `distribution_status`='0'");
        for($i=0;$i<count($rows);$i++){
            $order_id = $rows[$i]->id;
            $gst_percent = $rows[$i]->gst_percent;
            $type = $rows[$i]->type;
            $amount_type = $rows[$i]->amount_type;
            $amount_paid = $rows[$i]->amount_paid;
            $total_amount = $rows[$i]->total_amount;
            $hotel_id = $rows[$i]->hotel_id;
            $pay_revenue = $rows[$i]->pay_revenue;
            $parent_order_id = $rows[$i]->parent_order_id;
            if($type=='travel_agent'){
                if($amount_type=='Advance' || $amount_type=='Full'){
                    $gst = gstCal($amount_paid,$gst_percent);
                    $net_amount_paid = $amount_paid-$gst;
                    $agent_id = $rows[$i]->type_id;
                    $tatrooms_commision = ($tatroom_commission_percent/100)*$net_amount_paid;
                    $agent_payment = ($agent_commission_percent/100)*$tatrooms_commision;
                    $tatrooms_commision = $tatrooms_commision - $agent_payment;
                    $vendor_commission = ($vendor_commission_percent/100)*$net_amount_paid;
                    $vendor_id = get_post_field('post_author',$hotel_id);
                    
                    if($pay_revenue=='tatrooms'){
                        $vendor_commission = 0-$vendor_commission;
                    }
                    
                    if($pay_revenue=='hotel'){
                        $tatrooms_commision = 0-$tatrooms_commision;
                        $agent_payment = 0-$agent_payment;
                    }
                    
                    $wpdb->insert(
    					'wp_order_claim_part', //table
    					array(
    					'order_id' => $order_id,
    					'vendor_id' => $vendor_id,
    					'agent_id' => $agent_id,
    					'tatrooms_payment' => $tatrooms_commision,
    					'tatrooms_payment_percent' =>  $tatroom_commission_percent,
    					'vendor_payment' =>  $vendor_commission,
    					'vendor_payment_percent' =>  $vendor_commission_percent,
    					'agent_payment' =>  $agent_payment,
    					'agent_payment_percent' => $agent_commission_percent,
    					'gst' => $gst,
    					'gst_order_percent' => $gst_percent,
    					'date' => date("Y/m/d")
    					), //data
    					array(
    					'%s', 
    					'%s', 
    					'%s',
    					'%s', 
    					'%s', 
    					'%s',
    					'%s', 
    					'%s',
    					'%s',
    					'%s',
    					'%s',
    					'%s'
    					) //data format			
    			);
    			$wpdb->update(
                    'wp_order', //table
                    array(
    					'distribution_status' => 1
    					), //data
                    array('id' => $order_id), //where
                    array(
    					'%s'
    					), //data format
                    array('%s') //where format
    			);
                }else{
                    $rows1 = $wpdb->get_results("SELECT * from `wp_order` WHERE  `id`='".$parent_order_id."'");
                    $earlier_amount_paid = $rows1[0]->amount_paid;
                    $amount_paid = $total_amount-$earlier_amount_paid;
                    $gst = gstCal($amount_paid,$gst_percent);
                    $net_amount_paid = $amount_paid-$gst;
                    $agent_id = $rows[$i]->type_id;
                    $tatrooms_commision = ($tatroom_commission_percent/100)*$net_amount_paid;
                    $agent_payment = ($agent_commission_percent/100)*$tatrooms_commision;
                    $tatrooms_commision = $tatrooms_commision - $agent_payment;
                    $vendor_commission = ($vendor_commission_percent/100)*$net_amount_paid;
                    $vendor_id = get_post_field('post_author',$hotel_id);
                    
                    if($pay_revenue=='tatrooms'){
                        $vendor_commission = 0-$vendor_commission;
                    }
                    
                    if($pay_revenue=='hotel'){
                        $tatrooms_commision = 0-$tatrooms_commision;
                        $agent_payment = 0-$agent_payment;
                    }
                    
                    $wpdb->insert(
    					'wp_order_claim_part', //table
    					array(
    					'order_id' => $order_id,
    					'vendor_id' => $vendor_id,
    					'agent_id' => $agent_id,
    					'tatrooms_payment' => $tatrooms_commision,
    					'tatrooms_payment_percent' =>  $tatroom_commission_percent,
    					'vendor_payment' =>  $vendor_commission,
    					'vendor_payment_percent' =>  $vendor_commission_percent,
    					'agent_payment' =>  $agent_payment,
    					'agent_payment_percent' => $agent_commission_percent,
    					'gst' => $gst,
    					'gst_order_percent' => $gst_percent,
    					'date' => date("Y/m/d")
    					), //data
    					array(
    					'%s', 
    					'%s', 
    					'%s',
    					'%s', 
    					'%s', 
    					'%s',
    					'%s', 
    					'%s',
    					'%s',
    					'%s',
    					'%s',
    					'%s'
    					) //data format			
    			);
    			$wpdb->update(
                    'wp_order', //table
                    array(
    					'distribution_status' => 1
    					), //data
                    array('id' => $order_id), //where
                    array(
    					'%s'
    					), //data format
                    array('%s') //where format
    			);
                }
            }else{
                if($amount_type=='Advance' || $amount_type=='Full'){
                    $gst = gstCal($amount_paid,$gst_percent);
                    $net_amount_paid = $amount_paid-$gst;
                    $agent_id = 0;
                    $agent_payment = 0;
                    $tatrooms_commision = ($tatroom_commission_percent/100)*$net_amount_paid;
                    $vendor_commission = ($vendor_commission_percent/100)*$net_amount_paid;
                    $vendor_id = get_post_field('post_author',$hotel_id);
                    
                    if($pay_revenue=='tatrooms'){
                        $vendor_commission = 0-$vendor_commission;
                    }
                    
                    if($pay_revenue=='hotel'){
                        $tatrooms_commision = 0-$tatrooms_commision;
                    }
                    
                    $wpdb->insert(
    					'wp_order_claim_part', //table
    					array(
    					'order_id' => $order_id,
    					'vendor_id' => $vendor_id,
    					'agent_id' => $agent_id,
    					'tatrooms_payment' => $tatrooms_commision,
    					'tatrooms_payment_percent' =>  $tatroom_commission_percent,
    					'vendor_payment' =>  $vendor_commission,
    					'vendor_payment_percent' =>  $vendor_commission_percent,
    					'agent_payment' =>  $agent_payment,
    					'agent_payment_percent' => $agent_commission_percent,
    					'gst' => $gst,
    					'gst_order_percent' => $gst_percent,
    					'date' => date("Y/m/d")
    					), //data
    					array(
    					'%s', 
    					'%s', 
    					'%s',
    					'%s', 
    					'%s', 
    					'%s',
    					'%s', 
    					'%s',
    					'%s',
    					'%s',
    					'%s',
    					'%s'
    					) //data format			
    			);
    			$wpdb->update(
                    'wp_order', //table
                    array(
    					'distribution_status' => 1
    					), //data
                    array('id' => $order_id), //where
                    array(
    					'%s'
    					), //data format
                    array('%s') //where format
    			);
                }else{
                    $rows1 = $wpdb->get_results("SELECT * from `wp_order` WHERE  `id`='".$parent_order_id."'");
                    $earlier_amount_paid = $rows1[0]->amount_paid;
                    $amount_paid = $total_amount-$earlier_amount_paid;
                    $gst = gstCal($amount_paid,$gst_percent);
                    $net_amount_paid = $amount_paid-$gst;
                    $agent_id = 0;
                    $agent_payment = 0;
                    $tatrooms_commision = ($tatroom_commission_percent/100)*$net_amount_paid;
                    $vendor_commission = ($vendor_commission_percent/100)*$net_amount_paid;
                    $vendor_id = get_post_field('post_author',$hotel_id);
                    
                    if($pay_revenue=='tatrooms'){
                        $vendor_commission = 0-$vendor_commission;
                    }
                    
                    if($pay_revenue=='hotel'){
                        $tatrooms_commision = 0-$tatrooms_commision;
                    }
                    
                    $wpdb->insert(
    					'wp_order_claim_part', //table
    					array(
    					'order_id' => $order_id,
    					'vendor_id' => $vendor_id,
    					'agent_id' => $agent_id,
    					'tatrooms_payment' => $tatrooms_commision,
    					'tatrooms_payment_percent' =>  $tatroom_commission_percent,
    					'vendor_payment' =>  $vendor_commission,
    					'vendor_payment_percent' =>  $vendor_commission_percent,
    					'agent_payment' =>  $agent_payment,
    					'agent_payment_percent' => $agent_commission_percent,
    					'gst' => $gst,
    					'gst_order_percent' => $gst_percent,
    					'date' => date("Y/m/d")
    					), //data
    					array(
    					'%s', 
    					'%s', 
    					'%s',
    					'%s', 
    					'%s', 
    					'%s',
    					'%s', 
    					'%s',
    					'%s',
    					'%s',
    					'%s',
    					'%s'
    					) //data format			
    			);
    			$wpdb->update(
                    'wp_order', //table
                    array(
    					'distribution_status' => 1
    					), //data
                    array('id' => $order_id), //where
                    array(
    					'%s'
    					), //data format
                    array('%s') //where format
    			);
                }
            }
        }
    }
    $html=ob_get_contents();
	ob_end_clean();
	return $html;
}

add_shortcode('cleopatra_cron', 'cron');

function gstCal($amount_paid,$gst_percent){
    $gst = ($gst_percent/100)*$amount_paid;
    return $gst;
}