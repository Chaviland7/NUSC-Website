<?php
// Exit if accessed directly
if( !defined( 'ABSPATH' ) ) {
	exit;
}
?>
<?php get_header(); ?>
<div class="content-container">
	<div class="main-content single-post-container">
  <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
		<h1 class="title"><?=get_the_title();?></h1>
		<hr />
		<p><?=do_shortcode(get_the_content());?></p>
		<?php endwhile; else: ?>
		<p>
			<?php _e('Sorry, no posts matched your criteria.'); ?>
		</p>
	<?php endif; ?>
	</div>
</div>
<?php get_footer(); ?>
