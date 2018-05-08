<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta name="google-site-verification" content="cF60Wx_6A4n86hjI1n9cHvgsAhMoEm72_3vl2Xt2TyE" />
		<title><?php echo bloginfo('name').' '.wp_title('|',true,'');?></title>


		<!-- Le styles -->
		<link href="<?php bloginfo('url'); ?>/wp-content/themes/<?=get_stylesheet()?>/bootstrap/css/bootstrap.min.css" rel="stylesheet"> <!-- Get Bootstrap CSS -->
		<link href="<?php bloginfo('url'); ?>/wp-content/themes/<?=get_stylesheet()?>/bootstrap/css/bootstrap-social.css" rel="stylesheet"> <!-- Get Bootstrap CSS -->
		<link href="<?php bloginfo('url'); ?>/wp-content/themes/<?=get_stylesheet()?>/font-awesome/css/font-awesome.min.css" rel="stylesheet"> <!-- Get Font Awesome CSS -->
		<link href="<?php bloginfo('stylesheet_url');?>" rel="stylesheet"> <!-- Get my CSS -->
		<link rel="shortcut icon" href="<?php bloginfo('url'); ?>/wp-content/themes/<?=get_stylesheet()?>/media/images/favicon.png">

		<meta name="viewport" content="width=device-width, initial-scale=1.0">


		<meta name="description" content="<?php bloginfo('description'); ?>" />
		<!-- Twitter Card data -->
		<meta name="twitter:card" content="summary">
		<meta name="twitter:site" content="@NUSwimClub">
		<meta name="twitter:title" content="<?php echo str_replace('  ','',wp_title('',true,''));?>">
		<meta name="twitter:description" content="<?php bloginfo('description'); ?>">
		<meta name="twitter:creator" content="@author_handle">
		<meta name="twitter:image" content="<?php echo nusc_get_featured_image_url(get_the_ID(),'full'); ?>">

		<!-- Open Graph data -->
		<meta property="og:title" content="<?php echo str_replace('  ','',wp_title('',true,''));?>" />
		<meta property="og:type" content="article" />
		<meta property="og:url" content="<?php bloginfo('url'); ?>" />
		<meta property="og:image" content="<?php
		if (nusc_get_featured_image_url(get_the_ID(),'full')) {
			echo nusc_get_featured_image_url(get_the_ID(),'full');
		} else {
			echo 'https://www.northeastern.edu/clubswimming/wp-content/uploads/Slideshow9.jpg';
		}
		?>" />
		<meta property="og:description" content="<?php bloginfo('description'); ?>" />
		<meta property="og:site_name" content="<?php echo bloginfo('name');?>" />
		<meta property="fb:admins" content="829871710364210" />





		<!--<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">-->
		<link href="//netdna.bootstrapcdn.com/bootstrap/3.0.0-rc2/css/bootstrap-glyphicons.css" rel="stylesheet" />
		<?php wp_enqueue_script("jquery"); ?>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.2/jquery.min.js"></script>

		<script src="//cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
		<link href="//cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css" rel="stylesheet">
		
		<!--<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>-->
		<?php wp_head();?>
		<script>
			function display_year(year){
				if (year == 1) {
					return 'Freshman';
				}
				else if (year == 2) {
					return 'Sophomore';
				}
				else if (year == 3) {
					return 'Middler';
				}
				else if (year == 4) {
					return 'Junior';
				}
				else if (year == 5) {
					return 'Senior';
				}
				else if (year == 6) {
					return 'Graduate Student';
				}
			}
			function display_height(inches) {
			  var new_inches = inches % 12;
			  var new_feet = Math.floor(inches / 12);
			  return new_feet+" ft "+new_inches+' in';
			}
			function resizeSwimmerModal() {
				var modalimageheight = jQuery('.swimmer_modal_top .img_container img').css('height');
				var infoheight = modalimageheight.replace("px","");
				infoheight = infoheight / 5;
				if (jQuery(window).width() > 767) {
					jQuery('.swimmer_modal_top .swimmer_page_info_row').css('height',infoheight+"px");
				}
				else {
					jQuery('.swimmer_modal_top .swimmer_page_info_row').css('height',"inherit");
				}
			}
			jQuery(window).load(function() {
				if (jQuery(window).width() > 1186 && window.location.href=="https://www.northeastern.edu/clubswimming/") {
					var front_page_row_height = parseInt(jQuery('.row.front-page-row .blog-carousel-container').css('height').replace('px',''));
 			  	jQuery('.front-page-row .side_bar').animate({height:front_page_row_height},500);

					var top_section_height = parseInt(jQuery('.row.front-page-row .side_bar_content.logo').css('height').replace('px',''));

					var left_sidebar_height = parseInt(jQuery('.row.front-page-row .side_bar.left').css('height').replace('px',''));

					var fb_height = left_sidebar_height - top_section_height;

					jQuery('div.fb-page').data('height', fb_height);
				}
			});
			jQuery(document).ready(function() {
				jQuery(function() {
				  jQuery('a[href*="#"]:not([href="#"])').click(function() {
				    if (location.pathname.replace(/^\//,'') == this.pathname.replace(/^\//,'') && location.hostname == this.hostname) {
				      var target = jQuery(this.hash);
				      target = target.length ? target : jQuery('[name=' + this.hash.slice(1) +']');
				      if (target.length) {
				        jQuery('html, body').animate({
				          scrollTop: target.offset().top - 200
				        }, 1000);
				        return false;
				      }
				    }
				  });
				});
				jQuery('body').scrollspy({offset:200});
				jQuery(function () {
				  jQuery('[data-toggle="tooltip"]').tooltip()
				});
				jQuery('#SwimmerModal').on('show.bs.modal', function (event) {
					var button = jQuery(event.relatedTarget) // Button that triggered the modal
					var first_name = button.data('first')
					var last_name = button.data('last')
					var height = button.data('height')
					var current_year = button.data('currentyear')
					var hometown = button.data('hometown')
					var high_school = button.data('hs')
					var img_url = button.data('img_url')
					var stroke1 = button.data('stroke1')
					var stroke2 = button.data('stroke2')
					var stroke = stroke1;
					if (stroke2.length) stroke += "/"+stroke2;
					var major1 = button.data('major1')
					var major2 = button.data('major2')
					var major = major1;
					if (major2.length) major += "/"+major2;

					var id = button.data('athlete')

					jQuery('#tobeloaded').css("display","none");
					jQuery('#loading').css("display","block");

					var directory = "<?php bloginfo('url') ?>" + "/times/";
					jQuery("#tobeloaded").load(directory + " #timestable", {ID:id},
		        function(data){
							jQuery('#tobeloaded').css("display","block");
							jQuery('#loading').css("display","none");
							resizeSwimmerModal();
							jQuery('[data-toggle="tooltip"]').tooltip()
		      	}
					);

					var modal = jQuery(this)
					modal.find('.swimmer_page_info_row.stroke h3').html('<b>Stroke:</b> '+stroke)
					modal.find('.swimmer_page_info_row.height h3').html('<b>Height:</b> '+display_height(height))
					modal.find('.swimmer_page_info_row.year h3').html('<b>Year:</b> '+display_year(current_year))
					modal.find('.swimmer_page_info_row.high_school h3').html('<b>High School:</b> '+high_school)
					modal.find('.swimmer_page_info_row.hometown h3').html('<b>Hometown:</b> '+hometown)
					modal.find('.swimmer_page_info_row.major h3').html('<b>Major:</b>'+major);
					modal.find('.swimmer_modal_top .img_container img').attr('src',img_url)
					modal.find('h4.modal-title').html(first_name+' '+last_name)



					//window.history.pushState("object or string", "Title", window.location.href + last_name+"_"+first_name);
				});
				jQuery('#SwimmerModal').on('hide.bs.modal', function (event) {

					var modal = jQuery(this)
					//modal.find('.swimmer_page_info_row.name h3').empty()
					modal.find('.swimmer_page_info_row.stroke h3').empty()
					modal.find('.swimmer_page_info_row.height h3').empty()
					modal.find('.swimmer_page_info_row.year h3').empty()
					modal.find('.swimmer_page_info_row.high_school h3').empty()
					modal.find('.swimmer_page_info_row.hometown h3').empty()
					modal.find('.swimmer_modal_top .img_container img').empty()
					modal.find('h4.modal-title').empty()
					modal.find('#tobeloaded').empty()

					//window.history.pushState("object or string", "Title", window.location.href.replace(last_name+"_"+first_name,''));
				});

				jQuery('#Announcements').on('show.bs.modal', function (event) {
					var button = jQuery(event.relatedTarget); // Button that triggered the modal
					var title = button.data('title');
					var author = button.data('author');
					var date = button.data('date');
					var body_url = button.data('url') + ' .post-body-container';

					jQuery("#Announcements div.modal-body div#tobeloaded").load(body_url,
		        function(data){
							jQuery('#Announcements #tobeloaded').css("display","block");
							jQuery('#Announcements #loading').css("display","none");
		      	}
					);

					var modal = jQuery(this);
					modal.find("div.modal-header h4.modal-title").html(title);
					modal.find("div.modal-footer p.date-author").html('Posted by: ' + author);

					//window.history.pushState("object or string", "Title", "/" + last_name+"_"+first_name);
				});
				jQuery('#Announcements').on('hide.bs.modal', function (event) {

					var modal = jQuery(this)
					modal.find('h4.modal-title').empty()
					modal.find('#tobeloaded').empty()
					modal.find('#loading').css("display","block");

					//window.history.pushState("object or string", "Title", "/" + last_name+"_"+first_name);
				});

				if (jQuery(window).width() > 992 && window.location.href=="https://www.northeastern.edu/clubswimming/") {
					setTimeout(function() {
					var front_page_row_height = parseInt(jQuery('.row.front-page-row .blog-carousel-container').css('height').replace('px',''));
					if (jQuery(window).width() < 1200) {
						jQuery('.front-page-row .side_bar.right').animate({height:front_page_row_height},1000);
					}},2000)
				}
			});
			jQuery(window).resize(function() {
				resizeSwimmerModal();

				if (jQuery(window).width() > 992 && window.location.href == "https://www.northeastern.edu/clubswimming/") {
					var front_page_row_height = parseInt(jQuery('.row.front-page-row .blog-carousel-container').css('height').replace('px',''));
					if (jQuery(window).width() < 1200) {
						jQuery('.front-page-row .side_bar.right').css('height',front_page_row_height);
						jQuery('.front-page-row .side_bar.left').css('height','auto');
					} else {
						jQuery('.front-page-row .side_bar').css('height',front_page_row_height);
					}
				} else {
					jQuery('.front-page-row .side_bar').css('height','auto');
				}
			});
		</script>
  </head>
  <body>
		<nav class="navbar navbar-default navbar-fixed-top">
		  <div class="container-fluid">
		    <!-- Brand and toggle get grouped for better mobile display -->
		    <div class="navbar-header">
		      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
		        <span class="sr-only">Toggle navigation</span>
		        <span class="icon-bar"></span>
		        <span class="icon-bar"></span>
		        <span class="icon-bar"></span>
		      </button>
					<a class="navbar-brand" href="<?php bloginfo('url'); ?>">
						<img class="navbar-image" src="https://www.northeastern.edu/clubswimming/wp-content/uploads/2016/05/NU.png">
					</a>
		    </div>

		    <!-- Collect the nav links, forms, and other content for toggling -->
		    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
		      <ul class="nav navbar-nav">
			      <?=get_header_row()?>
		      </ul>
					<ul class="nav navbar-nav navbar-right">
					  <li>
					    <button type="button" class="donate btn">Donate</button>
					  </li>
					  <li>
							<a href="mailto:nuclubswimteam@gmail.com?Subject=Question%20About%20NUSC" target="_blank">Contact Us</a>
					  </li>
							<?php if (is_user_logged_in()) { ?>
								<li>
									<a href="<?=get_permalink(690);?>">Admin</a>
								</li>
							<?php } else { ?>
								<li>
									<a href="<?= get_bloginfo('url') + '/wp-admin';?>">Login</a>
								</li>
							<?php } ?>
					</ul>
		    </div><!-- /.navbar-collapse -->
		  </div><!-- /.container-fluid -->
		</nav>-->
		<div class="main-container">
