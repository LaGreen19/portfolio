<?php

function ascreen_setup(){
	global $content_width;

	$lang = get_template_directory(). '/languages';
	load_theme_textdomain('ascreen', $lang);
	
	$template_directory = get_template_directory();
	require_once( $template_directory . '/includes/customize/customize.php' );
	require_once( $template_directory . '/includes/tgm-plugin.php' );	

	add_theme_support('post-thumbnails');
	set_post_thumbnail_size( 185, 135 );
	$args = array();
	$header_args = array(
	    'default-image'          => '',
		'default-repeat' => 'no-repeat',
        'default-text-color'     => '00BAE1',
		'url'                    => '',
        'width'                  => 1920,
        'height'                 => 89,
        'flex-height'            => true
     );
	$background_args = array(
		'default-color' => 'f7f8f8',
	);	 
		 
	add_theme_support( 'custom-background', $background_args );
	add_theme_support( 'custom-header', $header_args );
	add_theme_support( 'automatic-feed-links' );//

	add_theme_support( 'custom-logo', array(
			   'height'      => 50,
			   'width'       => 50,
			   'flex-width' => true,
			) );

	//register menus
	register_nav_menus(
					   array(
						'header-menu' => __( 'Header Menu', 'ascreen' ) ,
					   	'footer-menu' => __( 'Footer Menu', 'ascreen' )	
					   )
					   );					   
					   
	
	add_theme_support( "title-tag" );//
	add_editor_style("editor-style.css");
	if ( !isset( $content_width ) ) $content_width = 1170;	
}
add_action( 'after_setup_theme', 'ascreen_setup' );

	
function ascreen_custom_scripts()
{
	global $is_IE;
	$theme_info = wp_get_theme();
	

	wp_enqueue_style('ascreen-base',  get_template_directory_uri() .'/css/base.css', false, $theme_info->get( 'Version' ), false);			


	wp_enqueue_style('ascreen-main', get_stylesheet_uri(), false, $theme_info->get( 'Version' ) );	
	
			
	wp_enqueue_script('ascreen-main', get_template_directory_uri().'/js/main.js', array( 'jquery' ),$theme_info->get( 'Version' ), false );			

	//video
	wp_enqueue_script('jquery.mb.YTPlayer.js', get_template_directory_uri().'/js/jquery.mb.YTPlayer.js', array( 'jquery' ), $theme_info->get( 'Version' ), false );	

	
	$mods = get_theme_mods();
	$enable_query_loader = !empty($mods['ascreen_option']['enable_query_loader']) ? $mods['ascreen_option']['enable_query_loader']:0;	
	if( $enable_query_loader == 1)
	{
		wp_enqueue_script( 'queryloader2', get_template_directory_uri().'/js/queryloader2.min.js', array( 'jquery' ), '', false );
		wp_enqueue_script( 'ascreen-loader', get_template_directory_uri().'/js/loader.js', array( 'jquery' ), '', false );		
	}
	
	wp_localize_script( 'ascreen-main', 'ascreen_params', array(
		'ajaxurl'        => admin_url('admin-ajax.php'),
		'themeurl' => get_template_directory_uri(),
		
	)  );		
	
		
		
}

add_action( 'wp_enqueue_scripts', 'ascreen_custom_scripts' );


/*
add widgets to wp-admin
*/
function ascreen_widgets_init() {

	register_sidebar( array(
		'name' => __('Sidebar','ascreen'),
		'id' => 'sidebar',
		'before_widget' => '<div class="sidebar-section"><ul class="blog-category">
',
		'after_widget' => '</ul></div> <!-- end .sidebar-section -->',
		'before_title' => '<h2>',
		'after_title' => '</h2>',
	) );

	
	register_sidebar( array(
		'name' => __('Footer Area #1','ascreen'),		
		'id' => 'sidebar-1',
		'before_widget' => '<dl id="%1$s" class="%2$s col-md-4">',
		'after_widget' => '</dl>',
		'before_title' => '<dt class="title">',
		'after_title' => '</dt>',
	) );

	register_sidebar( array(
		'name' => __('Footer Area #2','ascreen'),			
		'id' => 'sidebar-2',
		'before_widget' => '<dl id="%1$s" class="%2$s col-md-4">',
		'after_widget' => '</dl>',
		'before_title' => '<dt class="title">',
		'after_title' => '</dt>',
	) );

	register_sidebar( array(
		'name' => __('Footer Area #3','ascreen'),	
		'id' => 'sidebar-3',
		'before_widget' => '<dl id="%1$s" class=" %2$s col-md-4">',
		'after_widget' => '</dl>',
		'before_title' => '<dt class="title">',
		'after_title' => '</dt>',
	) );

	register_sidebar( array(
		'name' => __('Footer Area #4','ascreen'),	
		'id' => 'sidebar-4',
		'before_widget' => '<dl id="%1$s" class=" %2$s col-md-4">',
		'after_widget' => '</dl>',
		'before_title' => '<dt class="title">',
		'after_title' => '</dt>',
	) );
}
add_action( 'widgets_init', 'ascreen_widgets_init' );

function ascreen_paging_nav(){
	global $wp_query;
	$pages = $wp_query->max_num_pages; 
	if ( $pages >= 2 ): 
		$big = 999999999; 
		$paginate = paginate_links( array(
			'base' => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
			'format' => '?paged=%#%',
			'current' => max( 1, get_query_var('paged') ),
			'total' => $wp_query->max_num_pages,
			'end_size' => 13, 
			'type' => 'array' 
			));
		echo '<div class="ct_page_nav"><ul class="pagination">';
		foreach ($paginate as $value)
		{
			echo '<li>'.$value.'</li>';
		}
		echo '</ul></div>';
	endif;
}


function ascreen_better_comments($comment, $args, $depth)
{
	$GLOBALS['comment'] = $comment;
?>	
 
    <li <?php comment_class(); ?> id="li-comment-<?php comment_ID() ?>">
        <article id="comment-<?php comment_ID(); ?>" class="comment-body">
            <div class="comment_avatar">
                <?php echo get_avatar($comment, $size = '45'); ?>
            </div>

            <div class="comment_postinfo">
                <?php printf(__('<cite class="fn">%s</cite> <span class="says"> on </span>','ascreen'), get_comment_author_link()) ?>
                <span class="comment_date"><?php printf(__('%1$s at %2$s','ascreen'), get_comment_date(),  get_comment_time()) ?></span><?php edit_comment_link(__('(Edit)','ascreen'),'  ','') ?>
            </div> <!-- .comment_postinfo -->

            <div class="comment_area">				
                <div class="comment-content clearfix">
                	<?php if ($comment->comment_approved == '0') : ?>
                     	<em><?php _e('Your comment is awaiting moderation.','ascreen') ?></em>
                     	<br />
                    <?php endif; ?>
                    
                    <?php comment_text() ?>
                    
                    
                    <div class="reply-container">
                        <?php comment_reply_link(array_merge( $args, array('depth' => $depth, 'max_depth' => $args['max_depth']))) ?>
                    </div>
                      
                </div> <!-- end comment-content-->
            </div> <!-- end comment_area-->
        </article> <!-- .comment-body -->
        
<?php
}


function ascreen_get_author_info() 
{
	?>
    <div class="author">
        <a href="<?php echo get_author_posts_url(get_the_author_meta('ID')); ?>"><?php the_author(); ?></a> . 
        <time><?php the_date();?></time> . 
        <?php
        $categories = get_the_category();
        if(! empty( $categories ))
        {
				$i = 0;
            foreach($categories as $category){
				$i++;
				if($i == 6){ break;}
                $category->cat_ID;
                $category->cat_name;
				$cat_links=get_category_link($category->cat_ID );
				?>
				<a href="<?php echo esc_url($cat_links); ?>" title="<?php echo esc_html($category->cat_name); ?>">#<?php echo esc_html($category->cat_name);?>&nbsp;</a>
                <?php
            } 
        }
        ?>                        
    </div>
	<?php
}

function ascreen_get_the_category() 
{
	
	$categories_all = get_the_category();
	$str = '';
	$i = 0;
	
	foreach($categories_all as $categories ){
		$i++;
		if($i == 6){ break;}
		if ( ! empty( $categories ) ) {
			$cat_name = $categories->cat_name;
			$cat_ID = $categories->cat_ID;
		}
		
		$cat_links=get_category_link($cat_ID);
		
		$str .= "<a href=\"".esc_url($cat_links)."\" class=\"newsflash\">#".esc_html($cat_name)."</a>";
	}
	
	echo $str;
}

function ascreen_get_share_url() 
{	
	$mods = get_theme_mods();

	$twitter_url = !empty($mods['ascreen_option']['twitter_url']) ? $mods['ascreen_option']['twitter_url']:'#';
	$google_plus_url = !empty($mods['ascreen_option']['google_plus_url']) ? $mods['ascreen_option']['google_plus_url']:'#';	
	$facebook_url = !empty($mods['ascreen_option']['facebook_url']) ? $mods['ascreen_option']['facebook_url']:'#';	

	?>
	<div class="share">
		<?php if( $twitter_url != ''){?>
		<a target="_blank" href="<?php echo esc_url($twitter_url);?>" class="share-twitter cbutton cbutton--effect-jagoda"><i class="icon-twitter"></i></a>
		<?php }?>
		<?php if( $google_plus_url != ''){?>
		<a target="_blank" href="<?php echo esc_url($google_plus_url);?>" class="share-google-plus cbutton cbutton--effect-jagoda"><i class="icon-google-plus"></i></a>
		<?php }?>
		<?php if( $facebook_url != ''){?>                                
		<a target="_blank" href="<?php echo esc_url($facebook_url);?>" class="share-facebook cbutton cbutton--effect-jagoda"><i class="icon-facebook"></i></a>
		<?php }?>
	</div>
	<?php	
}
/**
 * Convert Hex Code to RGB   #FFFFFF -> 255 255 255
 * @param  string $hex Color Hex Code
 * @return array       RGB values
 
 ascreen_hex2rgb( '#FFFFFF')
 */
function ascreen_hex2rgb( $hex )
{
	if ( strpos( $hex,'rgb' ) !== FALSE )
	{
		$rgb_part = strstr( $hex, '(' );
		$rgb_part = trim($rgb_part, '(' );
		$rgb_part = rtrim($rgb_part, ')' );
		$rgb_part = explode( ',', $rgb_part );
		
		$rgb = array($rgb_part[0], $rgb_part[1], $rgb_part[2], $rgb_part[3]);
		
	}
	elseif( $hex == 'transparent' )
	{
		$rgb = array( '255', '255', '255', '0' );
	}
	else
	{
		$hex = str_replace( '#', '', $hex );	
		if( strlen( $hex ) == 3 )
		{
			$r = hexdec( substr( $hex, 0, 1 ) . substr( $hex, 0, 1 ) );
			$g = hexdec( substr( $hex, 1, 1 ) . substr( $hex, 1, 1 ) );
			$b = hexdec( substr( $hex, 2, 1 ) . substr( $hex, 2, 1 ) );
		}
		else
		{
			$r = hexdec( substr( $hex, 0, 2 ) );
			$g = hexdec( substr( $hex, 2, 2 ) );
			$b = hexdec( substr( $hex, 4, 2 ) );
		}
		$rgb = array( $r, $g, $b );
	}

	return $rgb; // returns an array with the rgb values
}

// Generating Live CSS
function ascreen_customize_css()
{
    ?>
		<style  id='ascreen_customize_css' type="text/css">
	<?php	
			$ascreen_custom_css = "";
			$header_image       = get_header_image();
			if (isset($header_image) && ! empty( $header_image ))
			{
				$ascreen_custom_css .= "header#header{background:url(".esc_url($header_image). ");}\n";
			}
			
			$mods = get_theme_mods();
			//print_r($mods);
		
			//echo $fixed_header = !empty($mods['ascreen_option']['fixed_header']) ? $mods['ascreen_option']['fixed_header']:1;
			$fixed_header = $mods['ascreen_option']['fixed_header'];
			//echo '-----';
			//$box_header_center = !empty($mods['ascreen_option']['box_header_center']) ? $mods['ascreen_option']['box_header_center']:0;		
			//$enable_home_section = !empty($mods['ascreen_option']['enable_home_section']) ? $mods['ascreen_option']['enable_home_section']:'0';	
			
			$box_header_center = $mods['ascreen_option']['box_header_center'];		
			$enable_home_section =  $mods['ascreen_option']['enable_home_section'];				
			
			if( $fixed_header !=1)
			{
				$ascreen_custom_css .= "#header{position: inherit;}.blog-content{padding-top:30px;}.admin_bar_fix {margin-top:0;}";
			}
			if($enable_home_section == '1'){
				$ascreen_custom_css .= ".blog-content{padding-top:50px;}";
			}
			if( $box_header_center == 0)
			{
				$ascreen_custom_css .= "#header .wrap{max-width:98%;}";
			}			
			
			
			if ( 'blank' != get_header_textcolor() && '' != get_header_textcolor() )
			{
				$header_textcolor  =  ' color:#'.get_header_textcolor().';';
				
				$ascreen_custom_css .=  '#logo .blogdescription,#logo .blogname {'.esc_attr($header_textcolor).'}';
				
				$rbg_t = ascreen_hex2rgb( '#'.get_header_textcolor() );
				
				$ascreen_custom_css .=  '#logo .blogdescription,#logo .blogname {'.esc_attr($header_textcolor).'}';
 				$ascreen_custom_css .=  '.header-nav>ul>li>a, .header-nav .btn>a,.header-nav > button{'.esc_attr($header_textcolor).'}';
 				$ascreen_custom_css .=  '.header-nav>button#header-nav-btn {
											'.esc_attr($header_textcolor).'
											border: #'.get_header_textcolor().' 1px solid;
											box-shadow: 0 0 4px rgba('.$rbg_t[0].','.$rbg_t[1].','.$rbg_t[2].',0.4);
										
										}
										.header-nav>button:hover{
											background-color: rgba('.$rbg_t[0].','.$rbg_t[1].','.$rbg_t[2].',0.1);
										}';				
													
				$ascreen_custom_css .=  '.header-nav>ul>li>a:hover, .header-nav .btn a:hover,.header-nav .sub-menu>li>a:hover{color:'.get_header_textcolor().';}';
				
 				$ascreen_custom_css .=  '.header-nav .btn>a{border:#'.get_header_textcolor().' 1px solid;}';					
 									
				
 
			}
			if(!is_home() ){
				
				$ascreen_custom_css .=  '#header {  position: inherit;}.blog-content {  padding-top: 20px;}.admin_bar_fix {margin-top:0;}';	
				
				}	
				
			if(has_custom_logo() ){ $ascreen_custom_css .=  '@media screen and (max-width: 850px){ .ascreen_logo_text{ display:none;} }';}	
				
							
			$ascreen_custom_css .="body{ background-color: #f7f8f8; }";
			

			$ascreen_custom_css   = esc_html($ascreen_custom_css);
			
			$ascreen_custom_css   = str_replace('&gt;','>',$ascreen_custom_css);			

			echo $ascreen_custom_css;
				
			?></style>
    <?php
}

add_action( 'wp_head', 'ascreen_customize_css');
add_action( 'customize_controls_print_styles', 'ascreen_customize_css' );


function ascreen_customize_scripts() {
	wp_enqueue_script( 'ascreen_customizer_custom', get_template_directory_uri() . '/js/customizer-custom-scripts.js', array( 'customize-controls', 'iris', 'underscore', 'wp-util' ), '20150630', true );

	$clean_box_misc_links = array(
							'upgrade_link' 				=> esc_url( 'https://www.coothemes.com/themes/ascreen.php' ),
							'upgrade_text'	 			=> __( 'Upgrade To Pro &raquo;', 'ascreen' ),
							'WP_version'				=> get_bloginfo( 'version' ),
							'old_version_message'		=> __( 'Some settings might be missing or disorganized in this version of WordPress. So we suggest you to upgrade to version 4.0 or better.', 'ascreen' )
		);
	if ( !(defined( 'ASCREEN_THEME_PRO_USED' ) && ASCREEN_THEME_PRO_USED ))
	{
		wp_localize_script( 'ascreen_customizer_custom', 'clean_box_misc_links', $clean_box_misc_links );
	}
	

	wp_enqueue_style( 'ascreen_customizer_custom_css', get_template_directory_uri() . '/css/customizer.css');
}
add_action( 'customize_controls_enqueue_scripts', 'ascreen_customize_scripts');



function ascreen_breadcrumbs() {
	//$delimiter = '»';	
	$delimiter = "&raquo;";
	$before = '<span class="current">';
	$after = '</span>'; 
	if ( !is_home() && !is_front_page() || is_paged() ) {
		echo '<div itemscope itemtype="http://schema.org/WebPage" id="crumbs">';
		global $post;
		$homeLink = home_url();
		echo ' <a itemprop="breadcrumb" href="' . $homeLink . '">' . __( 'Home' , 'ascreen' ) . '</a> ' . $delimiter . ' ';
		if ( is_category() ) { 
			global $wp_query;
			$cat_obj = $wp_query->get_queried_object();
			$thisCat = $cat_obj->term_id;
			$thisCat = get_category($thisCat);
			$parentCat = get_category($thisCat->parent);
			if ($thisCat->parent != 0){
				$cat_code = get_category_parents($parentCat, TRUE, ' ' . $delimiter . ' ');
				echo $cat_code = str_replace ('<a','<a itemprop="breadcrumb"', $cat_code );
			}
			echo $before . '' . single_cat_title('', false) . '' . $after;
		} elseif ( is_day() ) {
			echo '<a itemprop="breadcrumb" href="' . get_year_link(get_the_time('Y')) . '">' . get_the_time('Y') . '</a> ' . $delimiter . ' ';
			echo '<a itemprop="breadcrumb"  href="' . get_month_link(get_the_time('Y'),get_the_time('m')) . '">' . get_the_time('F') . '</a> ' . $delimiter . ' ';
			echo $before . get_the_time('d') . $after;
		} elseif ( is_month() ) { 
			echo '<a itemprop="breadcrumb" href="' . get_year_link(get_the_time('Y')) . '">' . get_the_time('Y') . '</a> ' . $delimiter . ' ';
			echo $before . get_the_time('F') . $after;
		} elseif ( is_year() ) { 
			echo $before . get_the_time('Y') . $after;
		} elseif ( is_single() && !is_attachment() ) { 
			if ( get_post_type() != 'post' ) { 
				$post_type = get_post_type_object(get_post_type());
				$slug = $post_type->rewrite;
				echo '<a itemprop="breadcrumb" href="' . $homeLink . '/' . $slug['slug'] . '/">' . $post_type->labels->singular_name . '</a> ' . $delimiter . ' ';
				echo $before . get_the_title() . $after;
			} else { 
				$cat = get_the_category(); $cat = $cat[0];
				$cat_code = get_category_parents($cat, TRUE, ' ' . $delimiter . ' ');
				echo $cat_code = str_replace ('<a','<a itemprop="breadcrumb"', $cat_code );
				echo $before . get_the_title() . $after;
			}
		} elseif ( !is_single() && !is_page() && get_post_type() != 'post' ) {
			$post_type = get_post_type_object(get_post_type());
			echo $before . $post_type->labels->singular_name . $after;
		} elseif ( is_attachment() ) { 
			$parent = get_post($post->post_parent);
			$cat = get_the_category($parent->ID); $cat = $cat[0];
			echo '<a itemprop="breadcrumb" href="' . get_permalink($parent) . '">' . $parent->post_title . '</a> ' . $delimiter . ' ';
			echo $before . get_the_title() . $after;
		} elseif ( is_page() && !$post->post_parent ) { 
			echo $before . get_the_title() . $after;
		} elseif ( is_page() && $post->post_parent ) { 
			$parent_id  = $post->post_parent;
			$breadcrumbs = array();
			while ($parent_id) {
				$page = get_page($parent_id);
				$breadcrumbs[] = '<a itemprop="breadcrumb" href="' . get_permalink($page->ID) . '">' . get_the_title($page->ID) . '</a>';
				$parent_id  = $page->post_parent;
			}
			$breadcrumbs = array_reverse($breadcrumbs);
			foreach ($breadcrumbs as $crumb) echo $crumb . ' ' . $delimiter . ' ';
			echo $before . get_the_title() . $after;
		} elseif ( is_search() ) { 
			echo $before ;
			printf( __( 'Search Results for: %s', 'ascreen' ),  get_search_query() );
			echo  $after;
		} elseif ( is_tag() ) { 
			echo $before ;
			printf( __( 'Tag Archives: %s', 'ascreen' ), single_tag_title( '', false ) );
			echo  $after;
		} elseif ( is_author() ) { 
			global $author;
			$userdata = get_userdata($author);
			echo $before ;
			printf( __( 'Author Archives: %s', 'ascreen' ),  $userdata->display_name );
			echo  $after;
		} elseif ( is_404() ) { 
			echo $before;
			_e( 'Not Found', 'ascreen' );
			echo  $after;
		}
		if ( get_query_var('paged') ) { 
			if ( is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author() )
				echo sprintf( __( '( Page %s )', 'ascreen' ), get_query_var('paged') );
		}
		echo '</div>';
	}
}

	/*	
	*	send email
	*	---------------------------------------------------------------------
	*/
function ascreen_contact(){
	if(trim($_POST['Name']) === '') {
		$Error = __('Please enter your name.','ascreen');
		$hasError = true;
	} else {
		$name = trim($_POST['Name']);
	}

	if(trim($_POST['Email']) === '')  {
		$Error = __('Please enter your email address.','ascreen');
		$hasError = true;
	} else if (!preg_match("/^[[:alnum:]][a-z0-9_.-]*@[a-z0-9.-]+\.[a-z]{2,4}$/i", trim($_POST['Email']))) {
		$Error = __('You entered an invalid email address.','ascreen');
		$hasError = true;
	} else {
		$email = trim($_POST['Email']);
	}

	if(trim($_POST['Message']) === '') {
		$Error =  __('Please enter a message.','ascreen');
		$hasError = true;
	} else {
		if(function_exists('stripslashes')) {
			$message = stripslashes(trim($_POST['Message']));
		} else {
			$message = trim($_POST['Message']);
		}
	}

	if(!isset($hasError)) {
	   if (isset($_POST['sendto']) && preg_match("/^[[:alnum:]][a-z0-9_.-]*@[a-z0-9.-]+\.[a-z]{2,4}$/i", trim($_POST['sendto']))) {
	     $emailTo = $_POST['sendto'];
	   }
	   else{
	 	 $emailTo = get_option('admin_email');
		}
		 if($emailTo !=""){
		$subject = 'From '.$name;
		$body = "Name: $name \n\nEmail: $email \n\nMessage: $message";
		$headers = 'From: '.$name.' <'.$emailTo.'>' . "\r\n" . 'Reply-To: ' . $email;

		wp_mail($emailTo, $subject, $body, $headers);
		$emailSent = true;
		}
		echo json_encode(array("msg"=>__("Your message has been successfully sent!","ascreen"),"error"=>0));
	}
	else
	{
		echo json_encode(array("msg"=>$Error,"error"=>1));
	}
		die() ;
}
add_action('wp_ajax_ascreen_contact', 'ascreen_contact');
add_action('wp_ajax_nopriv_ascreen_contact', 'ascreen_contact');
