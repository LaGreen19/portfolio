<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package LaLaBird
 */

?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="profile" href="http://gmpg.org/xfn/11">
<link href="https://fonts.googleapis.com/css?family=Cabin+Condensed|Cookie" rel="stylesheet">
	<link rel="stylesheet" href="fonts/font-awesome/css/font-awesome.min.css">
<?php wp_head(); ?>
</head>


<body <?php body_class(); ?>>
<div id="page" class="site">
	<a class="skip-link screen-reader-text" href="#content"><?php esc_html_e( 'Skip to content', 'lalabird' ); ?></a>

	<header id="masthead" class="page-header" role="banner">


		<div class="name-title page-name-title">
			<a href="http://laura-greenberg.com">
				<h1 class="name">Laura Greenberg</h1>
				<h2 class="header-job-title">Freelance WordPresser &amp; <br> <span>Front-End Developer </span></h2>
			</a>
		</div>

		<ol class="breadcrumbs" typeof="BreadcrumbList" vocab="https://schema.org/">
			<?php if(function_exists('bcn_display_list'))
			{
				bcn_display_list();
			}?>
		</ol>

	</header><!-- #masthead -->

	<div id="content" class="site-content">
