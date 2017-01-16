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
		<link rel="stylesheet" href="css/main.css">

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

<header class="page-header">
	<h1 class="portfolio-name">Laura Greenberg</h1>
	<h2 class="portfolio-header-job-title">Freelance Wordpresser &amp; <br> <span>Front-End Developer </span></h2>
</header>
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
