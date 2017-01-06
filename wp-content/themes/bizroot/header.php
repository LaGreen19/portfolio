<?php
/**
 * The header for our theme.
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Bizroot
 */

?><?php
	/**
	 * Hook - bizroot_action_doctype.
	 *
	 * @hooked bizroot_doctype -  10
	 */
	do_action( 'bizroot_action_doctype' );
?>
<head>
	<?php
	/**
	 * Hook - bizroot_action_head.
	 *
	 * @hooked bizroot_head -  10
	 */
	do_action( 'bizroot_action_head' );
	?>

<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>

	<?php
	/**
	 * Hook - bizroot_action_before.
	 *
	 * @hooked bizroot_page_start - 10
	 * @hooked bizroot_skip_to_content - 15
	 */
	do_action( 'bizroot_action_before' );
	?>

    <?php
	  /**
	   * Hook - bizroot_action_before_header.
	   *
	   * @hooked bizroot_header_start - 10
	   */
	  do_action( 'bizroot_action_before_header' );
	?>
		<?php
		/**
		 * Hook - bizroot_action_header.
		 *
		 * @hooked bizroot_site_branding - 10
		 */
		do_action( 'bizroot_action_header' );
		?>
    <?php
	  /**
	   * Hook - bizroot_action_after_header.
	   *
	   * @hooked bizroot_header_end - 10
	   */
	  do_action( 'bizroot_action_after_header' );
	?>

	<?php
	/**
	 * Hook - bizroot_action_before_content.
	 *
	 * @hooked bizroot_add_breadcrumb - 7
	 * @hooked bizroot_content_start - 10
	 */
	do_action( 'bizroot_action_before_content' );
	?>
    <?php
	  /**
	   * Hook - bizroot_action_content.
	   */
	  do_action( 'bizroot_action_content' );
	?>
