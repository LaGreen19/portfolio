<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Bizroot
 */

	/**
	 * Hook - bizroot_action_after_content.
	 *
	 * @hooked bizroot_content_end - 10
	 */
	do_action( 'bizroot_action_after_content' );
?>

	<?php
	/**
	 * Hook - bizroot_action_before_footer.
	 *
	 * @hooked bizroot_add_footer_contact_section - 5
	 * @hooked bizroot_footer_start - 10
	 */
	do_action( 'bizroot_action_before_footer' );
	?>
    <?php
	  /**
	   * Hook - bizroot_action_footer.
	   *
	   * @hooked bizroot_footer_copyright - 10
	   */
	  do_action( 'bizroot_action_footer' );
	?>
	<?php
	/**
	 * Hook - bizroot_action_after_footer.
	 *
	 * @hooked bizroot_footer_end - 10
	 */
	do_action( 'bizroot_action_after_footer' );
	?>

<?php
	/**
	 * Hook - bizroot_action_after.
	 *
	 * @hooked bizroot_page_end - 10
	 * @hooked bizroot_footer_goto_top - 20
	 */
	do_action( 'bizroot_action_after' );
?>

<?php wp_footer(); ?>
</body>
</html>
