<?php
/*
Plugin Name: Lobot Slider Administrator
Plugin URI: http://www.40digits.com/blog/lobot-slider-administrator-released-today/
Description: Creates a slide management interface in the WordPress backend and provides template tags for fetching the slides' information.
Author: 40 Digits
Author URI: http://40digits.com/
Version: 0.6.0
*/
add_action('adminmenu','fourty_slider_setup_page_meta');	// Sets up the meta
add_action('save_post','fourty_slider_save_data');			// saves the informations
add_action('admin_menu', 'fourty_slider_admin_menu'); 		// create hook for new submenu

function fourty_slider_setup_page_meta(){
	global $post;
	
	if($post->ID==get_option('fourty_slider_page'))
		add_meta_box('fourty_slider_post_info', 'Slider Administration', 'fourty_slider_page_meta_info', 'page', 'normal', 'high');
}

// title of page, name of option in menu bar, which function lays out the html
function fourty_slider_admin_menu()
{
        add_options_page('Slider Administrator', 'Slider Administrator', 5, basename(__FILE__), 'fourty_slider_options_page');
}

function fourty_slider_options_page()
{
        $updated = false;
        if (isset($_POST['fourty_slider_page']))
        {
                $fourty_slider_page = $_POST['fourty_slider_page'];
                update_option('fourty_slider_page', $fourty_slider_page);
                $updated = true;
        }
        $fourty_slider_page = get_option('fourty_slider_page');
        if ($updated)
        {
                ?>
                <div class="updated"><p><strong>Options saved.</strong></p></div>
                <?php
        }
        ?>
        <div class="wrap">
                <h2>Lobot Slider Administrator Settings</h2>
                <form name="form1" method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
                        <fieldset class="options">
                                <label>Page on which the slider appears:</label><br />
								<?php wp_dropdown_pages( array('name'=>'fourty_slider_page', 'id'=>'fourty_slider_page', 'selected'=>$fourty_slider_page )); ?>
                        </fieldset>
                        <p class="submit"><input type="submit" name="Submit" value="Update Options &raquo;" /></p>
                </form> 
                <p>That's it! Do you have a feature request, a complaint, <del>or some celebrity gossip</del>? Please, please, PLEASE <a href="http://wordpress.org/tags/lobot-slider-administrator?forum_id=10#postform">send us some feedback on the WordPress Plugin forum</a>!</p>
        </div>
        <?php
}

function fourty_slider_page_meta_info() {
	global $post;
	$orig_post = $post;
	?>
<style>
	#slide_image_disabled{
		display:block;
		float:left;
		background: #eee;
		border: 1px solid #ccc;
		width: 322px;
		height:139px;
		padding: 150px 0 0 100px;
		font-size:16px;
		font-style:italic;
		margin-right:31px;
	}
	.slide_image_preview{
		float:left;
		margin-right:25px;
		position:relative;
		width: 400px;
		min-height:300px;
		padding:11px;
		background: #eee;
		border: 1px solid #ccc;
	}
	.slide_image_preview img{
		width:400px;
	}
	.slide_image_preview span{
		position:absolute;
		z-index:50;
		display:block;
		left:1px;
		top:1px;
		width:266px;
		height:219px;
		line-height:20px;
		padding:110px 5px 25px 150px;
		background:#fff;
		font-size:16px;
		font-style:italic;
		opacity:0.8;
		filter:alpha(opacity=80);
		cursor:pointer;
	}
	
	
	#slide_image_browse{
		height:300px;
		padding:15px 0 15px 15px;
		overflow:auto;
		border: 3px solid #bbb;
		background: #f6f6f6;
	}
	
	
	#slide_admin_wrap{
		padding:10px;
	}
	#slide_options{
		float:left;
		padding-bottom: 15px;
	}
	#slide_select{
		list-style:none;
		width: 424px;
		padding-top: 5px;
	}
	#slide_select li{
		background: #bbb;
		border:1px solid #999;
		float:left;
		padding:6px 10px;
		margin: 0 2px 1px 0;
		cursor: pointer;
		color:#333;
	}
	#slide_select li.cur{
		background:#eee;
		font-weight:bold;
		color:#777;
		border:1px solid #bbb;
	}
	.slide_wrap textarea{
		height:100px;
	}
	.slide_wrap div{
		padding-right:10px;
	}
	.slide_wrap input[type="text"], .slide_wrap textarea{
		margin: 5px 0 10px;
		width:390px;
	}
	.slide_wrap label.top{
		font-weight:bold;
	}
	.slide_enabled{
		font-size:15px;
		padding-bottom:10px;
	}
	.slide_wrap{
		background: #eee;
		border: 1px solid #ccc;
		padding: 10px 0 0 10px;
		width:412px;
	}
	#add_new_slide{
		float:left;
	}
</style>
<fieldset id="fourty-meta-div">
	<div id="slide_admin_wrap">
		<div id="slide_options">
			
			<input type="button" id="add_new_slide" value=" + Add Slide " />
			<div class="clear"></div>
			<ul id="slide_select">
			<?php
				$slides = get_post_meta(get_option('fourty_slider_page'), 'fourty_slider_slides', true);
				$nav = count($slides);
				for ($i = 0; $i < $nav; $i++)
					echo '<li id="slide_nav_' . ($i + 1) . '">' . ($i + 1) . '</li>';
			?>
			</ul>
			<div class="clear"></div>
			<div id="lobot-container">
				<?php
				$count = array();
				$num = 0;
				for($i = 1; $i < ($nav + 1); $i++){
					$cur_slide = $slides[$i-1];
					$count[] = $num++;
					?>
					<div id="slide_<?php echo $i?>" class="slide_wrap" style="display:none">
						<div class="slide_enabled">
							<input type="checkbox" name="slide_enabled[]" id="slide_enabled_<?php echo $i?>" value="1" <?php if($cur_slide['enabled']) echo 'checked="checked"'; ?> />
							<label for="slide_enabled_<?php echo $i?>">Enabled</label>
						</div>
						<div class="clear"></div>
						<div class="alignleft">
							<label class="top" for="slide_title_<?php echo $i?>">Title:</label><br />
							<input type="text" name="slide_title[]" id="slide_title_<?php echo $i?>" value="<?php echo stripslashes($cur_slide['title'])?>" /><br/>

							<label class="top" for="slide_caption_<?php echo $i?>">Caption:</label><br />
							<textarea name="slide_caption[]" id="slide_caption_<?php echo $i?>"><?php echo stripslashes($cur_slide['caption'])?></textarea><br/>
						</div>
						<div class="alignleft">
							<label class="top">Link:</label><br />
							<input type="radio" name="slide_link_type_<?php echo $i?>" value="page" id="page_<?php echo $i?>" <?php echo $cur_slide['link_type']== 'page' || empty($cur_slide['link_type']) ? 'checked' : '' ?>><label for="page_<?php echo $i?>"> Page</label><br />
							<?php wp_dropdown_pages( array('name'=>'slide_link_page[]', 'id'=>'slide_link_page_'.$i, 'selected'=>$cur_slide['link_page'] )); ?><br />

							<input type="radio" name="slide_link_type_<?php echo $i?>" value="post" id="post_<?php echo $i?>" <?php echo $cur_slide['link_type']== 'post' ? 'checked' : '' ?>><label for="post_<?php echo $i?>"> News Item</label><br />

							<select name="slide_link_post_<?php echo $i?>" id="slide_link_post_<?php echo $i?>">
							<?php
							$my_query = new WP_Query('type=posts&orderby=title&order=ASC');
							
							$p_count=0;
							while ( $my_query->have_posts() ) {
								$my_query->the_post();
								$more = 0; 
							?>
								<option value="<?php the_ID() ?>" <?php echo $cur_slide['link_post']== get_the_ID() ? 'selected' : '' ?>><?php the_title() ?></option>
							<?php
								$p_count++;
							}
							if($p_count==0) echo '<option value="0">No News Items Exist</option>';
							?>
							</select><br />
							<input type="radio" name="slide_link_type_<?php echo $i?>" value="url" id="url_<?php echo $i?>" <?php echo $cur_slide['link_type']== 'url' ? 'checked' : '' ?>><label for="url_<?php echo $i?>"> Custom URL</label><br />
							<input name="slide_link_url[]" id="slide_link_url_<?php echo $i?>" type="text" value="<?php echo $cur_slide['link_url']?>" />
						</div>
						<input type="hidden" name="slide_image_<?php echo $i?>" id="slide_image_<?php echo $i?>" value="<?php echo $cur_slide['image']?>" />
						<div class="clear"></div>
					</div>
				<?php	
				}
				?>
			</div>
			<input type="hidden" id="total_num_slides" name="total_num_slides" value="<?php echo (count($count) > 0) ? implode(';', $count) : '' ; ?>" />
		</div>
		<div class="clear"></div>
		<div class="slide_image_preview">
			<img id="slide_image" src="" />
			<span class="hove" style="display:none">Select Image</span>
		</div>
		<span id="slide_image_disabled" style="display:none">Current slide is disabled.</span>
		<div class="clear"></div> 
	</div>
</fieldset>
<script type="text/javascript">
	var tb_taken_over = false;
	var cur_field_num;

	curSlide = 0;
	
	jQuery(document).ready(function($){	
		
		//slide photos stuff
		
		$('.slide_image_preview').hover(function(){
				$(this).children('span.hove').show();
			}, function(){
				$(this).children('span.hove').hide();
		});
		
		//slider management
		//------------------------------------------------------------------
		function allowSwitch ()
		{
			$('#slide_select li').click(function(){
				slide_num=$(this).attr('id').substr(10);
				switch_slide(slide_num);
			});
		}
		
		function allowEnable ()
		{
			$('.slide_wrap input:radio, .slide_enabled input').click(function(){
				setup_slide(curSlide);
			});
		}
		//------------------------------------------------------------------
		
		old_send_to_editor=window.send_to_editor;
		function allowPreview ()
		{
			$('.slide_image_preview').click(function() {
				tb_taken_over = true;
				cur_field_num = $(this).attr('id').substr(4);
				formfield = $('#slide_image_'+cur_field_num).attr('name');
				tb_show('', 'media-upload.php?post_id='+$('#post_ID').val()+'&TB_iframe=true');
			
				window.send_to_editor = function(html) {
					var strArr=html.split("'");
					the_url = strArr[1];
					if(typeof(the_url) == 'undefined') the_url = $('img',html).attr('src');
					$('#slide_image_'+cur_field_num).val(the_url);
				
					$('#slide_image').attr('src', the_url).show();
				
					tb_remove();
					window.send_to_editor = old_send_to_editor;
					tb_taken_over = false;
				}
			
				return false;
			});
		}
	
		switch_slide = function(id){
			$('#slide_'+curSlide).hide();
			$('#slide_nav_'+curSlide).removeClass('cur');
			$('.slide_image_preview').attr('id', 'img_'+id);
			curSlide=id;
			setup_slide(id);
			$('#slide_nav_'+id).addClass('cur');
			
			the_url=$('#slide_image_'+curSlide).attr('value');
			if(the_url==undefined) the_url='';
			$('#slide_image').attr('src', the_url);
			if(the_url=='') 
				$('#slide_image').hide();
			else
				$('#slide_image').show();
			$('#slide_'+id).show();
		}
		
		setup_slide = function(id){
			if($('#slide_enabled_'+id).is(':checked')){
				$('#slide_'+id+' div.alignleft').show();
				$('#slide_'+id+' select').each(
				  function() {
					$(this).attr('disabled', 'disabled');
				  }
				);
				$('#slide_'+id+' input:radio:checked').each(
				  function() {
					$(this).next().next().next().removeAttr("disabled");
				  }
				);
				
				$('#slide_image_disabled').hide();
				$('.slide_image_preview').show();
			
			}else{
			
				$('#slide_'+id+' div.alignleft').hide();
				$('#slide_image_disabled').show();
				$('.slide_image_preview').hide();
			
			}
		}
		
		function updateSlideCount ()
		{
			var values = "";
			$("#total_num_slides").val("");
			$("#slide_select").children("li").each(function (i) {
				values += i + ";";
			});
			$("#total_num_slides").val(values);
		}
		
		function slideCount ()
		{
			var count = 0;
			$("#slide_select").children("li").each(function (i) {
				count++;
			});
			return count;
		}
		
		var num = slideCount();
		$("#add_new_slide").click(function (event) { 
			event.preventDefault();
			num += 1;
			$("#slide_select").append('<li id="slide_nav_' + num + '">' + num + '</li>');
			updateSlideCount();
			generateSlide(num);
			switch_slide(num);
		});
		
		function generateSlide (slideID)
		{
			var before = '<div id="slide_' + slideID + '" class="slide_wrap" style="">' +
						'<div class="slide_enabled">' +
							'<input type="checkbox" name="slide_enabled[]" id="slide_enabled_' + num + '" value="1" checked="checked" />' +
							' <label for="slide_enabled_' + num + '">Enabled</label>' +
						'</div>' +
						'<div class="clear"></div>' +
						'<div class="alignleft">' +
							'<label class="top" for="slide_title_' + num + '">Title:</label><br />' +
							'<input type="text" name="slide_title[]" id="slide_title_' + num + '" value="" /><br/>' +
							'<label class="top" for="slide_caption_' + num + '">Caption:</label><br />' +
							'<textarea name="slide_caption[]" id="slide_caption_' + num + '"></textarea><br/>' +
						'</div>' +
						'<div class="alignleft">' + 
							'<label class="top">Link:</label><br />' +
							'<input type="radio" name="slide_link_type_' + num + '" value="page" id="page_' + num + '">' +
							'<label for="page_' + num + '"> Page</label><br />';
				before +=   "<?php echo addslashes(str_replace(array("\r\n", "\n", "\r"), "", wp_dropdown_pages( array("name"=>"slide_link_page[]", "echo" => 0))))?><br />";
				before +=	'<input type="radio" name="slide_link_type_' + num + '" value="post" id="post_' + num + '">' +
							'<label for="post_' + num + '"> News Item</label><br /><select name="slide_link_post_' + num + '" id="slide_link_post_' + num + '">';
							<?php
								$my_query = new WP_Query('type=posts&orderby=title&order=ASC');
								
								$p_count=0;
								while ( $my_query->have_posts() ) {
									$my_query->the_post();
									$more = 0; 
								?>
				before +=		'<option value="<?php the_ID() ?>" <?php echo $cur_slide['link_post']== get_the_ID() ? 'selected' : '' ?>><?php the_title() ?></option>';
								<?php
									$p_count++;
								}
								if($p_count==0) echo '<option value="0">No News Items Exist</option>';
							?>				
				before +=	'</select><br />' +
							'<input type="radio" name="slide_link_type_' + num + '" value="url" id="url_' + num + '">' +
							'<label for="url_' + num + '"> Custom URL</label><br /><input name="slide_link_url[]" id="slide_link_url_' + num + '" type="text" value="" />' +
							'</div><input type="hidden" name="slide_image_' + num + '" id="slide_image_' + num + '" value="" /><div class="clear"></div></div>';
			$('#lobot-container').append(before);
			allowSwitch();
			allowEnable();
		}
		allowEnable();
		allowPreview();
		allowSwitch();
		
		switch_slide(1);
	});
</script>
<?php
}

//
//
// called after a post or page is saved
//
//
function fourty_slider_save_data($postID)
{
	// check autosave
	if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
	 return $postID;
	}

	if($parent_id = wp_is_post_revision($postID)) $postID = $parent_id;
	if($postID == get_option('fourty_slider_page')){
		
		$slides = array();
		for($i = 0; $i < count(explode(';', $_POST['total_num_slides'])) - 1; $i++){
			$slides[$i]=array(
				'enabled'	=>	$_POST['slide_enabled'][$i],
				'title'		=>	$_POST['slide_title'][$i],
				'caption'	=>	$_POST['slide_caption'][$i],
				'link_type'	=>	$_POST['slide_link_type_'.($i+1)],
				'link_post'	=>	$_POST['slide_link_post_'.($i+1)],
				'link_page'	=>	$_POST['slide_link_page'][$i],
				'link_url'	=>	$_POST['slide_link_url'][$i],
				'image'		=>	$_POST['slide_image_'.($i+1)],
			);
		}
		update_post_meta($postID, 'fourty_slider_slides', $slides);
	}
	return $postID;
}

function fourty_slider_get_slides(){
	global $wpdb, $blog_id;
	$the_page_id = get_option('fourty_slider_page');
	$slides = get_post_meta($the_page_id, 'fourty_slider_slides', true);
	$slide_return = array();
	foreach($slides as $raw_slide){

		$cur_slide = array(
			'title'		=>	stripslashes($raw_slide['title']),
			'caption'	=>	stripslashes($raw_slide['caption']),
			'link'		=>	$raw_slide['link_type']=='url' ? $raw_slide['link_url'] : get_permalink($raw_slide['link_'.$raw_slide['link_type']]),
			'image'		=>	$raw_slide['image'],
			'external'	=>	$raw_slide['link_type']=='url'
		);
		if($raw_slide['enabled']) array_push($slide_return, $cur_slide);
	}
	
	return $slide_return;
}
?>