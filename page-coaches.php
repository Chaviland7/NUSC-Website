<?php
/**
 * Template Name: Coaches Page
 */
 // Exit if accessed directly
 if( !defined( 'ABSPATH' ) ) {
 	exit;
 }
 get_header();?>
 <div class="content-container">
 	<div class="main-content coaches">
   <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
 		<h1 class="title"><?=get_the_title();?></h1>
 		<hr />
 		<p><?=do_shortcode(get_the_content());?></p>
 		<?php endwhile; else: ?>
 		<p>
 			<?php _e('Sorry, no posts matched your criteria.'); ?>
 		</p>
 	<?php endif;
  echo get_coaches();?>
  </div>
 </div>

 <!--<div class="modal fade in" id="CoachModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
   <div class="modal-dialog swimmer" role="document">
     <div class="modal-content">
       <div class="modal-header">
         <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
         <h4 class="modal-title" id="myModalLabel">Coach</h4>
       </div>
       <div class="modal-body">
   	    <div class="modal-body-container">
 	        <div class="row swimmer_modal_top">
     	      <div class="img_container col-md-3">
         	    <img src="">
      	      </div>
       	    <div class="row info_container col-md-9">
           	  <div class="swimmer_page_info_row col-lg-12 stroke">
                 <h3>Freestyle</h3>
           	  </div>
           	  <div class="swimmer_page_info_row col-lg-12 height">
                 <h3>6' 1"</h3>
           	  </div>
           	  <div class="swimmer_page_info_row col-lg-12 year">
                 <h3>Middler</h3>
           	  </div>
   			      <div class="swimmer_page_info_row col-lg-12 high_school">
                 <h3>Hopkinton High School</h3>
           	  </div>
   			      <div class="swimmer_page_info_row col-lg-12 hometown">
                 <h3>Hopkinton, MA</h3>
           	  </div>
       	    </div>
           </div>
   	      <div class="row swimmer_modal_times">
            <div class="bio">
              <p>Ed has been with NUSC for 4 years...</p>
            </div>
           </div>
         </div>
       </div>
       <div class="modal-footer">

       </div>
     </div>
   </div>
 </div>-->
<?php get_footer(); ?>
