<?php
// Exit if accessed directly
if( !defined( 'ABSPATH' ) ) {
	exit;
}
?>
<?php get_header(); ?>
<div id="fb-root"></div>
<script>
	(function(d, s, id) {
  	var js, fjs = d.getElementsByTagName(s)[0];
  	if (d.getElementById(id)) return;
  	js = d.createElement(s); js.id = id;
  	js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.10";
  	fjs.parentNode.insertBefore(js, fjs);
	}(document, 'script', 'facebook-jssdk'));
</script>
		<div class="row front-page-row">
			<div class="col-lg-3 side_bar left">
				<div class="side_bar_content logo">
						<div class="front-logo-container">
							<img id="front-logo" src="<?=get_bloginfo('url')?>/wp-content/uploads/2016/05/NU.png" />
						</div>
							<h3 id="site_title">Northeastern University Swim Club</h3>
					<hr>
					<a target="_blank" href="https://twitter.com/NUSwimClub" class="btn btn-block btn-social btn-twitter hidden-xxs"><span class="fa fa-twitter"></span>Follow us on Twitter!</a>
					<a target="_blank" href="https://www.facebook.com/NUSwimmingClub/" class="btn btn-block btn-social btn-facebook hidden-xxs"><span class="fa fa-facebook"></span>Like us on Facebook!</a>
					<a target="_blank" href="https://www.instagram.com/nuswimclub/" class="btn btn-block btn-social btn-instagram hidden-xxs"><span class="fa fa-instagram"></span>Follow us on Instagram!</a>
					<a class="btn btn-block btn-social btn-snapchat hidden-xxs" data-toggle="modal" data-target="#Announcements" data-url="https://www.northeastern.edu/clubswimming/nusc-snapchat/" data-title="NUSC Snapchat" data-author="Megan Foley" data-date="2017-09-26"><span class="fa fa-snapchat"></span>Follow us on Snapchat!</a>
					<div class="row">
						<div class="col-xs-4 social-icon-container">
							<a target="_blank" href="https://twitter.com/NUSwimClub" class="btn btn-social-icon btn-lg btn-twitter visible-xxs"><i class="fa fa-twitter"></i></a>
						</div>
						<div class="col-xs-4 social-icon-container">
							<a target="_blank" href="https://www.facebook.com/NUSwimmingClub/" class="btn btn-social-icon btn-lg btn-facebook visible-xxs col-xs-4"><i class="fa fa-facebook"></i></a>
						</div>
						<div class="col-xs-4 social-icon-container">
							<a target="_blank" href="https://www.instagram.com/nuswimclub/" class="btn btn-social-icon btn-lg btn-instagram visible-xxs col-xs-4"><i class="fa fa-instagram"></i></a>
						</div>
					</div>
					<hr>
				</div>
				<div class="side_bar_content facebook hidden-xs hidden-sm hidden-md">
					<div class="fb-page" data-href="https://www.facebook.com/NUSwimmingClub/" data-tabs="timeline" data-small-header="false" data-adapt-container-width="true" data-hide-cover="false" data-show-facepile="true">
						<blockquote cite="https://www.facebook.com/NUSwimmingClub/" class="fb-xfbml-parse-ignore">
							<a href="https://www.facebook.com/NUSwimmingClub/">Northeastern University Swim Club</a>
						</blockquote>
					</div>
				</div>
			</div>
			<div class="col-lg-6 col-md-8 blog-carousel-container main-content">
				<div id="headlines-carousel" class="carousel slide" data-ride="carousel">
				  <?=get_front_page_slideshow();?>
				</div>
				<div id="meet_eboard">
				  <h2>Meet our Board</h3>
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
			<div class="col-lg-3 col-md-4 side_bar right">
				<div class="side_bar_title_container top">
					<h2 class="side_bar_title">Announcements</h2>
				</div>
				<div class="side_bar_content">
					<?=get_announcements();?>
				</div>
				<div class="side_bar_title_container">
					<h2 class="side_bar_title">Upcoming Meets</h2>
				</div>
				<div class="side_bar_content">
					<?=get_upcoming_meets();?>
				</div>
				<div class="side_bar_title_container">
					<h2 class="side_bar_title">Swimmer Links</h2>
				</div>
				<div class="side_bar_content">
					<?=get_swimmer_links();?>
				</div>
			</div>
		</div>
<?php get_footer(); ?>
