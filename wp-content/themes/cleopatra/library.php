<?php
class cleopatra_lib
{
	public function tag_decoder($content)
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
	
	public function wordpress_header()
	{
		ob_start();
		do_action('wp_head');
		$html=ob_get_contents();
		ob_end_clean();
		return $html;
	}
	
	public function wordpress_footer()
	{
		ob_start();
		do_action('wp_footer');
		$html=ob_get_contents();
		ob_end_clean();
		return $html;
	}
}