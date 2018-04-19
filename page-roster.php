<?php
/**
 * Template Name: Roster Page
 */

 // Exit if accessed directly
 if( !defined( 'ABSPATH' ) ) {
 	exit;
 }

 get_header();?>

 <div class="row roster-page-row">
   <div class="col-xl-2 col-lg-1">
   </div>
   <div class="col-xl-8 col-lg-10 roster-container main-content">
     <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); $sex = ($post->ID == 19) ? 'M' : 'F';?>
   		<h1 class="roster_header"><?=the_title();?></h1>
   		<?php endwhile; else: ?>
   		<p>
   			<?php _e('Sorry, no posts matched your criteria.'); ?>
   		</p>
   	<?php endif; ?>
    </hr>

     <?=get_roster($sex);?>
   </div>
   <div class="col-xl-2 col-lg-1">
   </div>
 </div>
<?php get_footer(); ?>
