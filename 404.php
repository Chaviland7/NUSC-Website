<?php
// Exit if accessed directly
if( !defined( 'ABSPATH' ) ) {
	exit;
}
?>
<?php get_header(); ?>
<div class="content-container">
	<div class="single-post-container pg-not-fnd">
		<h3>404 Error: Page not Found</h3>
	  <p>We apologize for the inconvenience. Please return to the main page <a href="<?=bloginfo('url');?>">here</a>.</p>
	</div>
</div>
<?php get_footer(); ?>
