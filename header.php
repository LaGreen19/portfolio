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
	<link href="https://fonts.googleapis.com/css?family=Cabin+Condensed" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css?family=Dancing+Script" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css?family=Cookie" rel="stylesheet">
	<link rel="stylesheet" href="fonts/font-awesome/css/font-awesome.min.css">
	<base target="_self">
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
	<div class="name-title">
	<h1 class="name">Laura Greenberg</h1>
	<h2 class="header-job-title">Freelance Wordpresser &amp; <br> <span>Front-End Developer </span></h2>
</div>
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
