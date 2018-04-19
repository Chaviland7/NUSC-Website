<?php
// Exit if accessed directly
if( !defined( 'ABSPATH' ) ) {
	exit;
}
?>
<?php get_header(); ?>
<div class="blog-post content-container">
	<div class="single-post-container main-content">
  <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
		<h1 class="title"><?=get_the_title();?></h1>
		<hr />
		<div class="post-body-container">
			<?php if ( get_the_content() != '' ):?>
				<p><?=do_shortcode(get_the_content());?></p>
			<?php else: ?>
				<h2 style="text-align: center;">Whoops!</h2>
				<p style="text-align: center;">It appears this post has no content, we apologize for the confusion!</p>
			<?php endif; ?>
		</div>
		<hr />
		<p class="date-author">Posted on: <?=the_date();?> by <?php the_author();?></p>
		<?php endwhile; else: ?>
		<p>
			<?php _e('Sorry, no posts matched your criteria.'); ?>
		</p>
	<?php endif; ?>
	</div>
</div>
<?php get_footer(); ?>
