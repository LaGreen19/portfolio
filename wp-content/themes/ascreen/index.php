<?php 
//post page

get_header(); ?>  

<?php
	$mods = get_theme_mods();
	// print_r($mods['ascreen_option']);
	$enable_home_section = !empty($mods['ascreen_option']['enable_home_section']) ? $mods['ascreen_option']['enable_home_section']:'0';
	$video_youtube_id = !empty($mods['ascreen_option']['video_youtube_id']) ? $mods['ascreen_option']['video_youtube_id']:'e1c-n1dRxwc';	
	
	$video_title = !empty($mods['ascreen_option']['video_title']) ? $mods['ascreen_option']['video_title']: __('Ascreen One Page Full Screen', 'ascreen');	
	$video_decription = !empty($mods['ascreen_option']['video_decription']) ? $mods['ascreen_option']['video_decription']: __('Awesome one-page full screen WordPress theme. Support home page slides, and video background too.', 'ascreen');	
	
	$video_button_one = !empty($mods['ascreen_option']['video_button_one']) ? $mods['ascreen_option']['video_button_one']: __('View Features', 'ascreen');	
	$video_button_one_link = !empty($mods['ascreen_option']['video_button_one_link']) ? $mods['ascreen_option']['video_button_one_link']: '#';	
	
	$video_button_two = !empty($mods['ascreen_option']['video_button_two']) ? $mods['ascreen_option']['video_button_two']: __('View Features', 'ascreen');	
	$video_button_two_link = !empty($mods['ascreen_option']['video_button_two_link']) ? $mods['ascreen_option']['video_button_two_link']: '#';		
	
	
	$contact_title = !empty($mods['ascreen_option']['contact_title']) ? $mods['ascreen_option']['contact_title']: __('CONTACT US', 'ascreen');	
	$contact_decription = !empty($mods['ascreen_option']['contact_decription']) ? $mods['ascreen_option']['contact_decription']: __('Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere c.Etiam ut dui eVestibulum ante ipsum primi', 'ascreen');		
	$contact_email = !empty($mods['ascreen_option']['contact_email']) ? $mods['ascreen_option']['contact_email']: get_bloginfo( 'admin_email');	

	if($enable_home_section == '1'){
?>
<style>
.ascreen_video_background{background-image:url(<?php echo get_template_directory_uri();?>/images/banner.jpg);background-repeat: repeat;background-position: top left;background-attachment: scroll; background-size: 100% auto;}
section.ascreen_section_0 .section_title{text-align:center ;}
section.ascreen_section_0 .section_title{font-family: "Microsoft YaHei", "Open Sans", "PingFangSC-Light", Arial, "Hiragino Sans GB","STHeiti", "WenQuanYi Micro Hei", SimSun, sans-serif;font-size:64px;font-weight:normal;color:#ffffff;}

	section.ascreen_section_0 .section_content,section.ascreen_section_0 p.section_text,

	section.ascreen_section_0 p{font-family: "Microsoft YaHei", "Open Sans", "PingFangSC-Light", Arial, "Hiragino Sans GB","STHeiti", "WenQuanYi Micro Hei", SimSun, sans-serif;font-size:18px;font-weight:normal;color:#ffffff;}
section.ascreen_section_0 .section_content{text-align:center}
section.ascreen_section_0 {background-attachment:fixed;background-position:50% 0;background-repeat:repeat;
padding:30px 0;
}
#video_button_wrapper{display:none;}
/*css for this section*/
section.ascreen_video .section_title{ margin-bottom:30px;}
#video_button_wrapper{position:fixed; top:10px; right:10px; z-index:10;}

/*css for video*/
.ascreen_video a.btn{
	-webkit-border-radius: 6;
	-moz-border-radius: 6;
	border-radius: 5px;
	color: #fff;
	font-size: 24px;
	padding: 10px 20px;
	margin: 1% 0 0 0;
	border: 1px solid #badddd;
	background-color: #00c8f2;
	text-transform: uppercase;
	background: rgba(0,200,242,0.3);
	height:auto;
	filter:progid:DXImageTransform.Microsoft.gradient(startColorstr=#99ffffff, endColorstr=#99ffffff);	
	font-family: "Microsoft YaHei", "Open Sans", "PingFangSC-Light", Arial, "Hiragino Sans GB","STHeiti", "WenQuanYi Micro Hei", SimSun, sans-serif;font-size:24px;font-weight:normal;color:#ffffff;}
.ascreen_video  a.btn:hover{	background-color: #00c8f2;/*#00c8f2*/text-decoration: none;color: #fff;border: 1px solid #00c8f2;}

.ascreen_video a.color1{background-color:rgba(0,200,242,0.7);border: 1px solid #badddd;}

section.ascreen_video p{font-size:2em;}

*:focus{
    outline: none;
}

button, .button {
    transition: all .4s;
    display: inline-block;
    padding: 0px 10px;
    font-size: 11px;
    font-weight: bold;
    text-transform: uppercase;
    cursor: pointer;
    background-color: rgba(248, 248, 248, 0.4);
    box-shadow: 0 0 4px rgba(0,0,0,0.4);
    color:#000;

    border: 1px solid transparent;

    text-decoration: none;
    line-height: 30px;
    margin: 3px;
    border-radius: 10px;
}

button:hover, .button:hover {
    background-color: rgb(0, 0, 0);
    color: #FFF;
}

#togglePlay{
    margin: 80px 22px 0 0;
    font-size: 18px;
    width: 40px;
    height: 40px;
    line-height: 1em;
	font-weight:normal;
    border-radius: 100%;
}

#togglePlay.pause{
    background-color: rgba(0,179,223,0.4);
    color: #fff;
}

#togglePlay.pause:hover{
    background-color: #fff;
    color: #333;
}

#togglePlay.play{
    background-color: rgba(0,179,223,0.4);
    color: #fff;
}

#togglePlay.play:hover{
    background-color: #fff;
    color: #111;
}

/*css for response*/
@media screen and (max-width:1136px){
section.ascreen_section_0 .section_title,.section_title{font-size:5em;}
}

@media screen and (max-width:800px){
	
section#ascreen_video .section_title{ font-size:3em;}

}

@media screen and (max-width:640px){
section#ascreen_video .section_text{ display:none;}
}

@media screen and (max-width:500px){
section#ascreen_video .section_title{ font-size:2em;}
}


@media screen and (max-width:320px){
section#ascreen_video .section_title{ font-size:1.5em;}
	
}


/*css for video end*/
</style>


<div id="video_button_wrapper">
    <div class="goto">
        <button id="togglePlay" class="command pause" onclick="jQuery('.ascreen_video_background').YTPTogglePlay(changeLabel)">&times;</button>
    </div>
</div>

<section id="ascreen_video" class="ascreen_section ascreen_video ascreen_section_0 ascreen_video_background player  ascreen-parallax" data-property="{videoURL:'<?php echo $video_youtube_id;?>', mute:false,opacity:1,containment:'.ascreen_video_background',loop:true,showControls:false,vol:30,autoPlay:true, optimizeDisplay:true, stopMovieOnBlur:false}">
	<div  id="video" class="section_content section_content_video section_width">

		<h1 class="section_title "><span><?php echo $video_title;?></span></h1>
		<p class="section_text"><?php echo $video_decription;?></p>
     	<p><a class="btn btn-lg btn-primary" href="<?php echo $video_button_one_link;?>" role="button"><?php echo $video_button_one;?></a>&nbsp;&nbsp;&nbsp;<a class="btn btn-lg btn-primary color1" href="<?php echo $video_button_two_link;?>" role="button"><?php echo $video_button_two;?></a></p>

	</div>
	<div class="clear"></div>
</section><!--id="ascreen_video" class="ascreen_section ascreen_video "-->

<?php }?>







	<div class="blog-content">
        <div class="wrap">
            
          	<?php if ( function_exists('ascreen_breadcrumbs') ) {echo ascreen_breadcrumbs();} ?>         
            <div class="main">
           
                <!--article-->
                <ul class="blog-article-list">
                	<?php if(have_posts()) : ?><?php while(have_posts()) : the_post(); ?>
                
                    <li id="post-1176">
                        <a href="<?php the_permalink(); ?>"  class="images newsflash">
						<?php 
							if(has_post_thumbnail())
							{
								the_post_thumbnail( array(185, 135) );
                             }else{
								 
						?>
                        
                        <img width="185"  src="<?php echo esc_url(get_template_directory_uri());?>/images/default.jpg" class="attachment-185x135 size-185x135 wp-post-image" sizes="(max-width: 185px) 100vw, 185px" />  
                        <?php 
								 
								}
						?>
                        </a>
                        
                		<div class="info">
                    		<h3><a href="<?php the_permalink(); ?>"><?php esc_html(the_title()); ?></a></h3>
                            
							<?php ascreen_get_author_info();?>

                    		<div class="quote">
                            	<p><?php the_excerpt(); ?></p>
							</div>
                		</div>
            		</li>
                    
                    <?php endwhile;endif; ?>                
                    
            	</ul><!--ul class="blog-article-list"-->
 
 
 				<?php //ascreen_paging_nav(); ?>

                <?php 
					the_posts_pagination( array(
						'mid_size' => 2,
						'prev_text' => __( 'Previous ', 'ascreen' ),
						'next_text' => __( ' Next', 'ascreen' ),
						'screen_reader_text' => __( ' ', 'ascreen' ),
						
					) );
				?>
 
            </div><!--div class="main"-->

            <!--siedbar-->
            <?php get_sidebar(); ?>
            
            
    	</div><!--div class="wrap"-->
	</div><!--div class="blog-content"-->
<?php
	if($enable_home_section == '1'){
?>



<style>
section.ascreen_section_8 .section_title{font-family: "Microsoft YaHei", "Open Sans", "PingFangSC-Light", Arial, "Hiragino Sans GB","STHeiti", "WenQuanYi Micro Hei", SimSun, sans-serif;font-size:36px;font-weight:normal;color:#99999;}
section.ascreen_section_8 .section_title{text-align:center ;}

	section.ascreen_section_8 .section_content,section.ascreen_section_8 p.section_text,
	section.ascreen_section_8 p{font-family: "Microsoft YaHei", "Open Sans", "PingFangSC-Light", Arial, "Hiragino Sans GB","STHeiti", "WenQuanYi Micro Hei", SimSun, sans-serif;font-size:14px;font-weight:normal;color:#999999;}
section.ascreen_section_8 .section_content{text-align:center; }
section.ascreen_section_8 {background-image:url(<?php echo get_template_directory_uri();?>/images/contact-us.png);background-repeat: repeat;background-position: top left;background-attachment: scroll;background-color:rgba(255,255,255,1); background-size: 100% auto;padding:50px 0;
}



/*css for contact form*/
.section_text {
	padding-bottom: 30px;
}

.contact-area {
margin: 0 auto;
width: 100%;
max-width: 500px;
text-align: center;
overflow: hidden;
}
.contact-form {
margin: 0 auto;
}

.contact-form input, .contact-form select, .contact-form textarea {
	font-size: 15px;
	width: 100%;
	background-color: rgba(0,200,242,0.02);
	border: 1px solid #0094B3;
	padding: 7px;
	overflow: hidden;
	margin-bottom: 30px;
}

input:invalid {
	box-shadow: none;
	background-color:rgba(0,200,242,0.17);
}

.noticefailed {
    color:#FF0000;
}
.noticesuccess {
    color:#009F00;
}
#submit{ color:#ffffff;}

</style>
<section id="ascreen_contact" class="ascreen_section ascreen_contact ascreen_section_8 ">
	<div  id="contact_us" class="section_content ">
    	  
		<h1 class="section_title "><?php echo $contact_title;?></h1>
		<p class="section_text"><?php echo $contact_decription;?></p>
     
        <div class="ascreen_contact_form">
            <div class="contact-area">
                <form class="contact-form" action="" method="post">
                    <input id="name" tabindex="1" name="name" size="22" type="text" value="" placeholder="Name" >
                    <input id="email" tabindex="2" name="email" size="22" type="email" value="" placeholder="Email">
                    <textarea id="message" tabindex="4" cols="39" name="x-message" rows="7" placeholder="Message"></textarea>
                    <input id="sendto" name="sendto" type="hidden" value="<?php echo $contact_email;?>">
                    <input id="submit" name="submit" type="button" value="Post">
                </form>
            </div>
        </div>
        	</div>
	<div class="clear"></div>
</section>



<?php }?>

<?php get_footer(); ?>