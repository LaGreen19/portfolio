<?php
/**
 * The template for displaying Comments
 *
 * The area of the page that contains comments and the comment form.
 *
 * @package WordPress
 * @subpackage Twenty_Fourteen
 * @since Twenty Fourteen 1.0
 */
/*
 * If the current post is protected by a password and the visitor has not yet
 * entered the password we will return early without loading the comments.
 */
 if ( is_singular() ) wp_enqueue_script( "comment-reply" ); 
if ( post_password_required() ) {
	return;
}
?>
<div class="blog-comments">
<div id="comments" class="comments-area ct_comments">
	<?php if ( have_comments() ) : ?>
	<h2 class="comments-title">
		<?php
			printf(
			 _n( 'One Comment on "%2$s"', '%1$s Comments on "%2$s"','ascreen'),number_format_i18n(get_comments_number()),get_the_title() );
		?>
	</h2>
    
	<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : ?>
	<nav id="comment-nav-above" class="navigation comment-navigation" role="navigation">
		<h1 class="screen-reader-text"><?php _e( 'Comment navigation', 'ascreen' ); ?></h1>
		<div class="nav-previous"><?php previous_comments_link( __( '<- Older Comments', 'ascreen' ) ); ?></div>
		<div class="nav-next"><?php next_comments_link( __( 'Newer Comments ->', 'ascreen' ) ); ?></div>
	</nav><!-- #comment-nav-above -->
	<?php endif; // Check for comment navigation. ?>
    
	<ol class="comment-list">
		<?php
			wp_list_comments( array(
				'style'      => 'ol',
				'short_ping' => true,
				'avatar_size'=> 34,
				'callback' => 'ascreen_better_comments',
			) );
		?>
	</ol><!-- .comment-list -->
    
    
	<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : ?>
	<nav id="comment-nav-below" class="navigation comment-navigation" role="navigation">
		<h1 class="screen-reader-text"><?php _e( 'Comment navigation', 'ascreen' ); ?></h1>
		<div class="nav-previous"><?php previous_comments_link( __( '<- Older Comments', 'ascreen' ) ); ?></div>
		<div class="nav-next"><?php next_comments_link( __( 'Newer Comments ->', 'ascreen' ) ); ?></div>
	</nav><!-- #comment-nav-below -->
	<?php endif; // Check for comment navigation. ?>
    
	<?php if ( ! comments_open() ) : ?>
	<p class="no-comments"><?php _e( 'Comments are closed.', 'ascreen' ); ?></p>
	<?php endif; ?>
	<?php endif; // have_comments() ?>
	<?php comment_form(); ?>
</div></div><!-- #comments -->