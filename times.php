<?php
/**
 * Template Name: Times Table Page
 */
// Exit if accessed directly
if( !defined( 'ABSPATH' ) ) {
	exit;
}
?>
<?php get_header(); ?>
<?=get_times_table($_POST['ID']);?>
<?php get_footer(); ?>
