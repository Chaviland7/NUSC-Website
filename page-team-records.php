<?php
/**
 * Template Name: Team Records Page
 */
 // Exit if accessed directly
 if( !defined( 'ABSPATH' ) ) {
 	exit;
 }
?>
<?php get_header(); ?>
<div class="content-container">
	<div class="single-post-container main-content">
  <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
		<h1 class="title"><?=get_the_title();?></h1>
		<hr />
		<?=do_shortcode(get_the_content());?>
		<?php endwhile; else: ?>
		<p>
			<?php _e('Sorry, no posts matched your criteria.'); ?>
		</p>
	<?php endif; ?>

  <div id="team_records_container">
    <?=get_team_records();?>
  </div>



	</div>
</div>
<?php get_footer(); ?>
