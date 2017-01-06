<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
	<?php wp_head(); ?>
    
</head>
<body  <?php body_class(); ?>>

    <header id="header"  class="header <?php if ( is_admin_bar_showing() ) { echo 'admin_bar_fix';  }?>">
        <div class="wrap">
            <h1 id="logo">
			<?php 
                if ( has_custom_logo() )
                {
            ?>
                <div class="ascreen_logo">	
                    <?php the_custom_logo();?>
                </div>
           <?php
                }
            ?> 
            
            <div class="ascreen_logo_text">
                <a href="<?php echo esc_url(home_url('/')); ?>"><span class="blogname"><?php esc_html(bloginfo('name')); ?></span></a><br>
                <a href="<?php echo esc_url(home_url('/')); ?>"><span class="blogdescription"><?php esc_html(bloginfo('description')); ?></span></a>
            </div>
            
            </h1>
            <nav class="header-nav">
                <button type="button" id="header-nav-btn"><i class="icon-menu"></i><i class="icon-cross"></i></button>
                <!-- Mobile button -->
                <ul>
                <?php
					if ( has_nav_menu( 'header-menu' ) ) {
                    	 wp_nav_menu( array( 'theme_location' => 'header-menu', 'items_wrap' => '%3$s','container' => false  ) );
                  	}
				?>

                <?php
                if(is_user_logged_in())
                {
                    ?>
                    <li class="btn"><a href="<?php echo esc_url(home_url('/wp-admin/profile.php')); ?>" ><?php _e('Profile','ascreen')?></a></li>
                    <?php		
                }
                else
                {
                    ?>
                    <li class="btn"><a href="<?php echo wp_login_url( get_permalink() ); ?>" ><?php _e('Login','ascreen')?></a></li> 
                    <?php
                }                
                ?> 
                 
                </ul>
                
            </nav>
        </div>
        
   </header>