<?php
/**
 * Template Name: Past Seasons Page
 */
 // Exit if accessed directly
 if( !defined( 'ABSPATH' ) ) {
 	exit;
 }
 get_header();?>
 <div class="content-container">
 	<div class="single-post-container main-content seasons">
   <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
 		<h1 class="title"><?=get_the_title();?></h1>
 		<hr />
 		<p><?=do_shortcode(get_the_content());?></p>
 		<?php endwhile; else: ?>
 		<p>
 			<?php _e('Sorry, no posts matched your criteria.'); ?>
 		</p>
 	<?php endif;
  echo get_past_seasons();?>

  </div>
 </div>
<?php get_footer(); ?>
