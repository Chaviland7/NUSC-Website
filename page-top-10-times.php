<?php
/**
 * Template Name: Top 10 Times Page
 */
 // Exit if accessed directly
 if( !defined( 'ABSPATH' ) ) {
 	exit;
 }
?>
<?php get_header(); ?>

<div id="side-nav-container">
  <nav class="side-nav" id="scrollspy_target">
    <ul class="nav">
      <?=get_top_ten_sidebar();?>
    </ul>
  </nav>
</div>

<div class="content-container">
	<div class="single-post-container main-content">
  <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
		<h1 class="title"><?=get_the_title();?></h1>
		<hr />
		<p><?=do_shortcode(get_the_content());?></p>
		<?php endwhile; else: ?>
		<p>
			<?php _e('Sorry, no posts matched your criteria.'); ?>
		</p>
	<?php endif;
  echo get_top_ten();?>
	</div>
</div>
<?php get_footer(); ?>
