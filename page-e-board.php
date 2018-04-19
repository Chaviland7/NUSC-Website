<?php
/**
 * Template Name: E-Board Page
 */
 // Exit if accessed directly
 if( !defined( 'ABSPATH' ) ) {
 	exit;
 }
 get_header();?>
 <div class="content-container">
 	<div class="main-content eboard">
   <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
 		<h1 class="title"><?=get_the_title();?></h1>
 		<hr />
 		<p><?=do_shortcode(get_the_content());?></p>
 		<?php endwhile; else: ?>
 		<p>
 			<?php _e('Sorry, no posts matched your criteria.'); ?>
 		</p>
 	<?php endif;
  ?>
  <div id="meet_eboard">
    <h3>Mens E-Board</h4>
    <div class="row front_eboard_row">
      <?=get_front_eboard_row("M");?>
    </div>
    <h3>Womens E-Board</h4>
    <div class="row front_eboard_row">
      <?=get_front_eboard_row("F");?>
    </div>
  </div>

  </div>
 </div>
<?php get_footer(); ?>
