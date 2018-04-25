<?php
/**
 * Template Name: E-Board Page
 */
get_header(); ?>

<div class="content-container">
 <div class="main-content admin">

<?php if( !is_user_logged_in() ) { ?>
  <h1 class="title">Unauthorized</h1>
  <hr />
  <h3>Please return to the home page and log into WordPress to access the Admin Portal</h3>
<?php } else { ?>
  <h1 class="title"><?=get_the_title();?></h1>
  <hr />
  <?php echo get_admin_page();
} ?>

  </div>
</div>

<?php get_footer();

?>