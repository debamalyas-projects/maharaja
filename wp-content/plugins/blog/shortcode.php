<?php
/*
 * Plugin Name: Blog
 * Description: This plugin is used to Create, Read, Update, Delete BLOG.
 * Version: 1.0
 * Author: Syncxini Infosystem
 * Author URI: http://syncxini.com
*/

// Creates Blog Custom Post Type
function blog_init()
{
    $args = array(
        'label' => 'Blog',
        'public' => true,
        'exclude_from_search' => true,
        'show_in_nav_menus' => false,
        'publicly_queryable' => true,
        'show_ui' => true,
        'capability_type' => 'post',
        'hierarchical' => true,
        'rewrite' => array(
            'slug' => 'blog'
        ) ,
        'query_var' => true,
        'menu_icon' => 'dashicons-admin-page',
        'supports' => array(
            'title',
            'editor',
            'author'
        )
    );
    register_post_type('blog', $args);
}
add_action('init', 'blog_init');
add_filter('default_content', 'blog_content', 10, 2);
function blog_content($content, WP_Post $post)
{
    if ($post->post_type == 'blog')
    {
        $plugin_file_path = __FILE__;
        $list_part_template_path = str_replace('shortcode.php', 'list_part_template.html', $plugin_file_path);
        $content = file_get_contents($list_part_template_path);
    }
    else
    {
        $content = '';
    }
    return $content;
}
function bloglisting_view()
{
    ob_start();
    $args = array(
	  'numberposts' => -1,
	  'post_type'   => 'blog'
	);
	$blog_posts = get_posts( $args );
	
    for ($j = 0;$j < count($blog_posts);$j++)
    {
		$content = str_replace('{post_id}',$blog_posts[$j]->ID,$blog_posts[$j]->post_content);
		
		$permalink = get_permalink($blog_posts[$j]->ID);
		$content = str_replace('{permalink}',$permalink,$content);
		
        $content = tag_decoder($content);
        echo $content;
    } 
?>
<?php 
	$html = ob_get_contents();
    ob_end_clean();
	$base_url = get_site_url();
	$pagination_js = $base_url.'/wp-content/plugins/blog/pagination/jquery.paginate.js';
	$pagination_css = $base_url.'/wp-content/plugins/blog/pagination/jquery.paginate.css';
	
	$plugin_file_path = __FILE__;
	$list_template_path = str_replace('shortcode.php', 'list_template.html', $plugin_file_path);
	$main_html = file_get_contents($list_template_path);
	$main_html = str_replace('{pagination_js}',$pagination_js,$main_html);
	$main_html = str_replace('{pagination_css}',$pagination_css,$main_html);
	$main_html = str_replace('{content}',$html,$main_html);
	
	
    return $main_html;
}
add_shortcode('cleopatra_bloglisting', 'bloglisting_view');

function tag_decoder($content)
{
	preg_match_all("/\[([^\]]*)\]/", $content, $matches);


	$tags_arr=$matches[1];

	for($i=0;$i<count($tags_arr);$i++)
	{
		$shortcode_decode_arr=explode('=',$tags_arr[$i]);
		
		if($shortcode_decode_arr[0]=='post')
		{
			$post_content_obj=get_post($shortcode_decode_arr[1]);
			$post_content = $post_content_obj->post_content;
			
			$post_content=$this->tag_decoder($post_content);

			$content=str_replace('['.$tags_arr[$i].']',$post_content,$content);
		}
		else if($shortcode_decode_arr[0]=='wp')
		{
			if($shortcode_decode_arr[1]=='header')
			{
				ob_start();
				wp_head();
				$head_html=ob_get_contents();
				ob_end_clean();
				
				$content=str_replace('['.$tags_arr[$i].']',$head_html,$content);
			}
			else if($shortcode_decode_arr[1]=='ptitle')
			{
				ob_start();
				wp_title('');
				$ptitle=ob_get_contents();
				ob_end_clean();
				$content=str_replace('['.$tags_arr[$i].']',$ptitle,$content);
			}
			else if($shortcode_decode_arr[1]=='pxtitle')
			{
				ob_start();
				the_title();
				$pxtitle=ob_get_contents();
				ob_end_clean();
				$content=str_replace('['.$tags_arr[$i].']',$pxtitle,$content);
			}
			else
			{
				ob_start();
				wp_footer();
				$footer_html=ob_get_contents();
				ob_end_clean();
				$content=str_replace('['.$tags_arr[$i].']',$footer_html,$content);
			}
		}
		else if($shortcode_decode_arr[0]=='contact-form-7 id'){
			$shortcode_content = do_shortcode('['.$tags_arr[$i].']');
			$content=str_replace('['.$tags_arr[$i].']',$shortcode_content,$content);
		}
		else if($shortcode_decode_arr[0]=='shortcode')
		{
			if(count($shortcode_decode_arr)>2){
				$shortcode_string = '';
				for($k=1;$k<count($shortcode_decode_arr);$k++){
					$shortcode_string .= $shortcode_decode_arr[$k].'=';
				}
				$shortcode_string = rtrim($shortcode_string,'=');
				$shortcode_content=do_shortcode('['.$shortcode_string.']');
			}else{
				$shortcode_content=do_shortcode('['.$shortcode_decode_arr[1].']');
			}
			$content=str_replace('['.$tags_arr[$i].']',$shortcode_content,$content);
		}
		else if($shortcode_decode_arr[0]=='acf')
		{
			$field_params=$shortcode_decode_arr[1];
			$fields_params_arr=explode('|',$field_params);
			
			$field_shortcode=$fields_params_arr[0];
			
			if(isset($fields_params_arr[1]))
			{
				$field_post_id=$fields_params_arr[1];
			
				$shortcode_content=get_field($field_shortcode,$field_post_id);
			}
			else
			{
				$shortcode_content=get_field($field_shortcode);
			}
			$content=str_replace('['.$tags_arr[$i].']',$shortcode_content,$content);
		}
		else if($shortcode_decode_arr[0]=='acf_repeater')
		{
			$fields_params=$shortcode_decode_arr[1];
			$fields_params_arr=explode('||',$fields_params);
			
			$repeater_template_shortcode_arr=explode('|',$fields_params_arr[1]);
			
			if(isset($repeater_template_shortcode_arr[1]))
			{
				$repeater_template=get_field($repeater_template_shortcode_arr[0],$repeater_template_shortcode_arr[1]);
			}
			else
			{
				$repeater_template=get_field($repeater_template_shortcode_arr[0]);
			}
			
			$repeater_arr=explode('|',$fields_params_arr[0]);
			$repeater_shortcode=$repeater_arr[0];
			
			if(isset($repeater_arr[1]))
			{
				$field_post_id=$repeater_arr[1];
				
				preg_match_all("/\[([^\]]*)\]/", $repeater_template, $matches_rep);


				$tags_rep_arr=$matches_rep[1];
				
				if( have_rows($repeater_shortcode, $field_post_id) ):
					$out='';
					while( have_rows($repeater_shortcode, $field_post_id) ): the_row();
					
					$out2=$repeater_template;
					
					for($j=0;$j<count($tags_rep_arr);$j++)
					{
						$sub_field=get_sub_field($tags_rep_arr[$j]);
						$out2=str_replace('['.$tags_rep_arr[$j].']',$sub_field,$out2);
					}
					
					$out.=$out2;
					
					endwhile;
					
				endif;
			}
			else
			{
				preg_match_all("/\[([^\]]*)\]/", $repeater_template, $matches_rep);


				$tags_rep_arr=$matches_rep[1];
				
				if( have_rows($repeater_shortcode) ):
					$out='';
					while( have_rows($repeater_shortcode) ): the_row();
					
					$out2=$repeater_template;
					
					for($j=0;$j<count($tags_rep_arr);$j++)
					{
						$sub_field=get_sub_field($tags_rep_arr[$j]);
						$out2=str_replace('['.$tags_rep_arr[$j].']',$sub_field,$out2);
					}
					
					$out.=$out2;
					
					endwhile;
					
				endif;
			}
			
			
			$content=str_replace('['.$tags_arr[$i].']',$out,$content);
			
		}
	}
	
	return $content;
}
?>
