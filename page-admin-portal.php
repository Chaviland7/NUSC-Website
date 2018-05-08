<?php
/**
 * Template Name: E-Board Page
 */
 if( !defined( 'ABSPATH' ) ) {
   exit;
 }
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
  <div class="row">
    <div class="col-md-3">
      <?=get_admin_page(); ?>
    </div>
    <div class="col-md-9">
      <?=get_manual_results_table();?>
    </div>
<?php } ?>

  </div>
</div>

<?php get_footer(); ?>
<script>
  $(document).ready( function () {
    $('#manual_results').DataTable();
  });
  $('#add_result_form').on('submit', (e) => {
    const formData = new FormData(e.target);
    insert_result(formData)
    // Now you can use formData.get('foo'), for example.
    // Don't forget e.preventDefault() if you want to stop normal form .submission
  });
</script>