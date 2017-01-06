<!-- Widget Title -->
<?php  
  $titleLink = $this->getLink($widgetPost, 'title'); 
  if( !empty( $titleLink ) ) :
?>
<div class="otw_portfolio_manager-portfolio-title-wrapper">
	<h3><a href="<?php echo $titleLink;?>" class="otw-widget-title"><?php echo $widgetPost->post_title;?></a></h3>
</div>
<?php else: ?>
<div class="otw_portfolio_manager-portfolio-title-wrapper">
  <h3 class="otw-widget-title"><?php echo $widgetPost->post_title;?></h3>
</div>
<?php endif; ?>
<!-- End Widget Title -->
