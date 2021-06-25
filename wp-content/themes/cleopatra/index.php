<?php 

/**

 * Template Name: Cleopatra frame page

 **/

 ?>
<?php
include_once('library.php');

$post_id=get_the_ID();

if($post_id==''){
	$request_uri_arr = explode('/',$_SERVER['REQUEST_URI']);
	$post_type = $request_uri_arr[1];
	$post_slug = $request_uri_arr[2];
	
	global $wpdb; 
	$page = $wpdb->get_var( $wpdb->prepare( "SELECT ID FROM $wpdb->posts WHERE post_name = %s AND post_type= %s AND post_status = 'publish'", $post_slug, $post_type ) );
   
	$post = get_post($page, $output);
	$post_id = $post->ID;

	$index_file_path = __FILE__;
	$details_template_path = str_replace('themes/cleopatra/index.php','plugins/'.$post_type.'/blog_details.html',$index_file_path);
	$details_template = file_get_contents($details_template_path);
	$details_template = str_replace('{post_id}',$post_id,$details_template);
	
	$cleopatra_lib=new cleopatra_lib();
	$content=$cleopatra_lib->tag_decoder($details_template);

	echo $content;
}else{
	$content_post = get_post($post_id);
	$content = $content_post->post_content;
	
	if($content_post->post_type=='page' || $content_post->post_type=='post'){
		$cleopatra_lib=new cleopatra_lib();
		$content=$cleopatra_lib->tag_decoder($content);

		echo $content;
	}else{
		$index_file_path = __FILE__;
		$details_template_path = str_replace('themes/cleopatra/index.php','plugins/'.$content_post->post_type.'/'.$content_post->post_type.'_details.html',$index_file_path);
		$details_template = file_get_contents($details_template_path);
		$details_template = str_replace('{post_id}',$post_id,$details_template);
		
		$cleopatra_lib=new cleopatra_lib();
		$content=$cleopatra_lib->tag_decoder($details_template);

		echo $content;
	}
}
?>



