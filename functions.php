<?php

$current_season = $wpdb->get_results("SELECT YEAR(DATE_ADD(current_date(), INTERVAL 6 MONTH)) as CurrentSeason")[0];
$events = $wpdb->get_results("SELECT DISTANCE,STROKE
                              FROM RESULTS WHERE I_R = 'I'
                              GROUP BY DISTANCE,STROKE
                              ORDER BY CASE
                              WHEN STROKE = 'Free' THEN 1
                                WHEN STROKE = 'Back' THEN 2
                                WHEN STROKE = 'Breast' THEN 3
                                WHEN STROKE = 'Fly' THEN 4
                                WHEN STROKE = 'IM' THEN 5
                                ELSE STROKE END, CAST(DISTANCE as unsigned) ASC");

add_theme_support( 'post-thumbnails' );
add_action( 'init', 'create_post_type' );
function create_post_type() {
  register_post_type( 'swim_meet',
    array(
      'labels' => array(
				'name' => __( 'Swim Meets' ),
        'singular_name' => __( 'Swim Meet' ),
        'add_new' => __( 'Add New Meet' ),
          'add_new_item' => __( 'Add New Meet' ),
          'edit_item' => __( 'Edit Meet' ),
          'new_item' => __( 'Add New Meet' ),
          'view_item' => __( 'View Meet' ),
          'search_items' => __( 'Search Meet' ),
          'not_found' => __( 'No meets found' ),
          'not_found_in_trash' => __( 'No meets found in trash' )
      ),
			'taxonomies' => array('category'),
      'public' => true,
      'supports' => array( 'title', 'editor', 'thumbnail', 'comments', 'author'),
      'capability_type' => 'post',
      'rewrite' => array("slug" => "meets"),
      'menu_position' => 5,
      'register_meta_box_cb' => 'add_events_metaboxes'
  	)
  );
}
flush_rewrite_rules();
add_filter( 'rwmb_meta_boxes', 'your_prefix_meta_boxes' );
function your_prefix_meta_boxes( $meta_boxes ) {
    $meta_boxes[] = array(
        'title'      => __( 'Meet Statistics', 'textdomain' ),
        'post_types' => 'swim_meet',
        'fields'     => array(
						array('id' => 'team_1_name','name' => __( 'Team 1 Name', 'textdomain' ),'type' => 'text'),
						array('id' => 'team_1_score','name' => __( 'Team 1 Score', 'textdomain' ),'type' => 'text'),
						array('id' => 'team_2_name','name' => __( 'Team 2 Name', 'textdomain' ),'type' => 'text'),
						array('id' => 'team_2_score','name' => __( 'Team 2 Score', 'textdomain' ),'type' => 'text'),
						array('id' => 'team_3_name','name' => __( 'Team 3 Name', 'textdomain' ),'type' => 'text'),
						array('id' => 'team_3_score','name' => __( 'Team 3 Score', 'textdomain' ),'type' => 'text'),
						array('id' => 'team_4_name','name' => __( 'Team 4 Name', 'textdomain' ),'type' => 'text'),
						array('id' => 'team_4_score','name' => __( 'Team 4 Score', 'textdomain' ),'type' => 'text'),
            array('id' => 'location','name' => __( 'Meet Location', 'textdomain' ),'type'    => 'radio','options' => array('Home' => __( 'Home', 'textdomain' ),'Away' => __( 'Away', 'textdomain' ))),
            array('id' => 'date','name' => __( 'Meet Date', 'textdomain' ),'type' => 'date'),
            array('id' => 'signup_url','name' => __( 'Entries URL', 'textdomain' ),'type' => 'text'),
            array('id' => 'entries_date','name' => __( 'Entries Due By', 'textdomain' ),'type' => 'date'),
            array('id' => 'results_url','name' => __( 'Results PDF url', 'textdomain' ),'type' => 'text')
        ),
    );

    return $meta_boxes;
}

function display_time_from_seconds($time_in_seconds){
	if ($time_in_seconds == 99999) {
		return 'Time Error';
	}
    $seconds = $time_in_seconds;
    $minutes = floor($seconds / 60);
    $seconds_trunc = (int) $seconds;
    $sub_seconds_dec = $seconds - $seconds_trunc;
    $sub_seconds_long = $sub_seconds_dec * 100;



    $sub_seconds = round($sub_seconds_long);
    $seconds = ($seconds % 60);
    return sprintf("%02d", $minutes).':'.sprintf("%02d", $seconds).'.'.sprintf("%02d",$sub_seconds);
}

function time_to_seconds($time_string) {
  $nums = explode(":",$time_string);
  $minutes = (int)$nums[0];
  $seconds = (float)$nums[1];
  return ($minutes * 60) + $seconds;
}

function get_hover_images($sub_title) {
  global $wpdb;
	$html = '';
	$array_to_use = null;
  $gender = '';
	if ($sub_title == 'Male Athletes') {
    $gender = 'M';
	}
	else if ($sub_title == 'Female Athletes') {
    $gender = 'F';
	}
	else if ($sub_title == 'E-Board') {
		$eboard_names = array(
			'foley_meg',
			'haviland_charlie',
			'gridley_henry',
			'niemi_sarah',
			'pinkes_samantha',
			'mcgann_dan',
			'philbrick_maya',
			'fong_hanalei',
			'fleischer_ryan'
		);
		$array_to_use = $eboard_names;
	}
	else if ($sub_title == 'Coaches') {
		$coaches_names = array(
			'renaud_alice'
		);
		$array_to_use = $coaches_names;
	}
	else {
		return '';
	}
  if ($array_to_use) {
    foreach($array_to_use as $name) {
  		$html .= '<img class="navbar-dropdown-image '.str_replace(' ','',$sub_title).'" src="https://www.northeastern.edu/clubswimming/wp-content/uploads/'.$name.'.jpg" alt="'.$name.'"/>';
  	}
  }
  else {
    $random_swimmers = $wpdb->get_results("SELECT CASE WHEN Pref != '' THEN Pref ELSE First END as First, Last FROM Athlete WHERE Sex = \"$gender\" AND Inactive = 0 ORDER BY RAND() LIMIT 20");
    $count = 0;
    foreach($random_swimmers as $swimmer) {
      if ($count < 10) {
        $source = $_SERVER['DOCUMENT_ROOT'].'/clubswimming/wp-content/uploads/'.strtolower(str_replace(array("-"," "),"_",str_replace("'","",$swimmer->Last)).'_'.$swimmer->First).'.jpg';
        $src = get_bloginfo('url').'/wp-content/uploads/'.strtolower(str_replace(array("-"," "),"_",str_replace("'","",$swimmer->Last)).'_'.$swimmer->First).'.jpg';
        if (file_exists($source)) {
          $html .= '<img class="navbar-dropdown-image '.str_replace(' ','',$sub_title).'" src="'.$src.'" alt="'.$swimmer->Last.'"/>';
          $count++;
        }
      }
  	}
  }
	return $html;
}

function nusc_get_meta_tags() {
  global $post;
	$html = '';
  $is_home = (get_the_ID() == $home_ID) ? 1 : 0 ;
	$is_page = (is_page()) ? 1 : 0 ;
  /*<meta name="description" content="<?php bloginfo('description'); ?>" />
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
  <meta property="og:image" content="<?php echo nusc_get_featured_image_url(get_the_ID(),'full'); ?>" />
  <meta property="og:description" content="<?php bloginfo('description'); ?>" />
  <meta property="og:site_name" content="<?php echo bloginfo('name');?>" />
  <meta property="fb:admins" content="829871710364210" />*/






	$is_child = (is_page() && count(get_post_ancestors($post->ID)) == 1 ) ? 1 : 0 ;
	$pg = (isset($_GET['pg'])) ? $_GET['pg'] : false ;
	$pg_title = (isset($_GET['pg'])) ? str_replace(array('-','/'),' ',$pg) : false ;
	$pg_object = (isset($_GET['pg'])) ? get_page_by_path($pg) : false ;
	$pg_id = (isset($_GET['pg'])) ? $pg_object->ID : false ;

	$category = get_the_category();
	$tag = (is_tag()) ? '' : '' ;
	$meta_id = (!$category && !is_single()) ? isset($_GET['pg']) ? $pg_id : get_the_ID() : $category[0]->term_id ;

	$the_meta = (!$category) ? get_post_meta($meta_id,'_jj_dscrpt_kywds',TRUE) : "" ;
	$meta_description = (!$category) ? ($the_meta) ? $the_meta['description'] : $default_desc : "Learn all about ".get_bloginfo('name')."'s ".$category[0]->cat_name." by reading posts from their blog." ;
	$meta_name = ($is_home) ? get_bloginfo('name') : (isset($short_title) ? $short_title.' | '.get_the_title() : get_bloginfo('name').' | '.get_the_title());
	$featured_image = (!has_post_thumbnail()) ? (!wp_get_post_parent_id(get_the_ID())) ? get_bloginfo('url').'/wp-content/themes/'.get_stylesheet().'/media/images/default-meta.jpg' : wp_get_attachment_url(get_post_thumbnail_id(wp_get_post_parent_id(get_the_ID()))) : wp_get_attachment_url(get_post_thumbnail_id(get_the_ID()));
	$excerpt = (is_singular('post')) ? wp_trim_words( $post->post_content, $num_words = 35, $more = null ) : $meta_description;

	$html .= '<meta http-equiv="content-type" content="text/html;charset=utf-8" />'."\n\t";
	$html .= '<meta name="google-site-verification" content="'.$google_ver.'" />'."\n\t";
	$html .= '<meta name="viewport" content="width=device-width, initial-scale=1.0" />'."\n\t";
	$html .= '<meta name="format-detection" content="telephone=no" />'."\n\t";
	$html .= '<meta name="description" content="'.$meta_description.'" />'."\n\t";
	$html .= '<link href="'.get_bloginfo('url').'/wp-content/themes/'.get_stylesheet().'/media/images/apple-touch-icon-144-precomposed.png" sizes="144x144" rel="apple-touch-icon-precomposed" />'."\n\t";
	$html .= '<link rel="shortcut icon" href="'.get_stylesheet_directory_uri().'/favicon.ico" />'."\n\t";
	$html .= '<link rel="stylesheet" href="'.get_bloginfo('stylesheet_url').'" type="text/css" media="screen, projection" />'."\n\t";
	$html .= '<meta property="og:title" content="'.$meta_name.'" />'."\n\t";
	$html .= '<meta itemprop="name" content="'.$meta_name.'" />'."\n\t";
	$html .= '<meta property="og:type" content="article" />'."\n\t";
	$html .= '<meta property="og:url" content="'.get_permalink().'"/>'."\n\t";
	$html .= '<meta property="og:image" content="'.$featured_image.'" />'."\n\t";
	$html .= '<meta itemprop="image" content="'.$featured_image.'" />'."\n\t";
	$html .= '<meta property="og:description" content="'.$excerpt.'" />'."\n\t";
	$html .= '<meta property="og:site_name" content="'.get_bloginfo('name').'" />'."\n\t";
	$html .= '<script type="text/javascript">'."\n\t\t";
		$html .= "var is_dev = $is_dev;"."\n\t\t";
		$html .= "var is_home = $is_home;"."\n\t\t";
		$html .= "var is_page = $is_page;"."\n\t\t";
		$html .= "var is_calendar = $is_calendar;"."\n\t\t";
		$html .= "var is_subscriptions = $is_subscriptions;"."\n\t\t";
		$html .= "var is_child = $is_child;"."\n\t\t";
		$html .= "var is_payment_form = $is_payment_form;"."\n\t\t";
		$html .= "var pg = '$pg';"."\n\t\t";
		$html .= "var title = '$pg_title';"."\n\t\t";
		$html .= "var page_id = '$pg_id';"."\n\t\t";
		$html .= "var pathArray = window.location.pathname.split( '/' );"."\n\t\t";
		$html .= "var slug = pathArray[1+(+is_dev)];"."\n\t\t";
		$html .= "var page_url = window.location.protocol+window.location.pathname;"."\n\t";
	$html .= '</script>';
}

function get_header_row() {
	$html = '';
	$count = 0;
	$header_args = array('post_type' => 'page',
						 'sort_order' => 'desc',
						 'include' => array(7,17,13,11));
	$pages = get_pages($header_args);

	foreach ($pages as $page) {
		$id = $page->ID;
		$title = get_the_title($id);
		$html .= '<li class="dropdown">';
		$html .= '<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">'.$title.'<span class="caret"></span></a>';
		$html .= '<ul class="dropdown-menu '.str_replace(' ','',$title).'">';

		$sub_header_args = array('parent' => $id,
														'sort_order' => 'desc',
														'exclude' => 48);
		$sub_pages = get_pages($sub_header_args);
		foreach ($sub_pages as $sub_page) {
			$sub_id = $sub_page->ID;
			$sub_title = get_the_title($sub_id);
			$featured_img = wp_get_attachment_image_src(get_post_thumbnail_id( $sub_id ), $size = 'full');

			$html .= '<li><a href="'.get_permalink($sub_id).'">';
			$html .= '<h3>'.$sub_title.'</h3>';
			//$html .= '<img class="navbar-dropdown-image" src="'.$featured_img[0].'">';


      if ($sub_title == 'Male Athletes' || $sub_title == 'Female Athletes' || $sub_title == 'E-Board' || $sub_title == 'Coaches') {
        $html .= '<div class="hs-wrapper animated">';
  			$html .= get_hover_images($sub_title);
      }
      else {
        $html .= '<div class="hs-wrapper">';
        $html .= '<img class="navbar-dropdown-image '.str_replace(' ','',$sub_title).'" src="'.nusc_get_featured_image_url($sub_id,'full').'" alt="'.$sub_title.'"/>';
      }
			$html .= '</div>';



			$html .= '</a></li>';
		}
		$html .= '</ul>';
		$html .= '</li>';
		$count++;
	}
	return $html;
}

function nusc_get_featured_image_url($page_ID,$size) {
	$featured_img_id = get_post_thumbnail_id( $page_ID );
	$thumb_url = wp_get_attachment_image_src($featured_img_id, $size );
	return $thumb_url[0];
}

function get_front_page_slideshow() {
	$html = '';
	$posts_args = array(
	'category_name'    => 'Front Page',
	'orderby'          => 'date',
	'order'            => 'DESC',
	'post_type'        => array('post','swim_meet'),
	'post_status'      => 'publish'
  );
	$posts = get_posts($posts_args);
	$count = 0;

	$html .= '<ol class="carousel-indicators">';
	foreach($posts as $post) {
    if (nusc_get_featured_image_url($post->ID,'full')) {
  		$html .= '<li data-target="#headlines-carousel" data-slide-to="'.$count.'" ';
  		$html .= ($count == 0) ? 'class="active"></li>' : '></li>';
  		$count++;
    }
	}
	$html .= '</ol>';
	$html .= '<div class="carousel-inner" role="listbox">';
		$count = 0;
		foreach($posts as $post) {
      if (nusc_get_featured_image_url($post->ID,'full')) {
  			$type = get_post_type($post);
  			$post_id = $post->ID;
  			$post_title = get_the_title($post_id);
  			$post_content = apply_filters('the_content', $post->post_content);
  			$post_uri = get_permalink($post_id);
  			$post_img = nusc_get_featured_image_url($post_id,'full');

  			$html .= ($count == 0) ? '<div class="item active">' : '<div class="item">';
  				$html .= '<img src="'.$post_img.'" alt="'.$post_title.' Image">';
  				$html .= '<div class="carousel-caption">';
  					$html .= '<a href="'.$post_uri.'">'.$post_title.'</a>';
  				$html .= '</div>';
  			$html .= '</div>';
  			$count++;
      }
		}
	$html .= '</div>';

	$html .= '<a class="left carousel-control" data-target="#headlines-carousel" role="button" data-slide="prev">
		<span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
		<span class="sr-only">Previous</span>
	</a>
	<a class="right carousel-control" data-target="#headlines-carousel" role="button" data-slide="next">
		<span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
		<span class="sr-only">Next</span>
	</a>';
	return $html;
}

function get_upcoming_meets() {
  $html = '';
	$posts_args = array(
	'category_name'    => 'Upcoming Meet',
	'orderby'          => 'date',
	'order'            => 'ASC',
	'post_type'        => 'swim_meet',
	'post_status'      => 'publish'
  );
	$meets = get_posts($posts_args);

  $html .='<div class="upcoming_meets_container">';
  foreach($meets as $meet) {
    $meet_id = $meet->ID;
    $meet_title = get_the_title($meet_id);
    $meet_uri = get_permalink($meet_id);
    $meet_img = nusc_get_featured_image_url($meet_id,'full');
    $date_string = '';
    $date = '';
    $entries_date = '';
    $diff = '';
    $tooltipString = '';
    $signupby = '';
    if (get_post_meta($meet_id,'date')) {
      $date = get_post_meta($meet_id,'date')[0]; //date of the meet
      $date = DateTime::createFromFormat("Y-m-d", $date); //string to date conversion
      $date_string .= $date->format('D, M d');
      $date_string = '<p>'.$date_string.'</p>';

      $now = new DateTime('now'); //date-time object for now

      if (get_post_meta($meet_id,'entries_date')) {
        $entries_date = get_post_meta($meet_id,'entries_date')[0]; //date entries close
        $entries_date = DateTime::createFromFormat("Y-m-d", $entries_date); //string to date conversion
        $diff = $now->diff($entries_date);
        $tooltipString = 'Entries close in '.str_replace('+','',$diff->format('%R%a')).' day';
        $tooltipString .= ($diff == 1) ? '' : 's';
      }
    }

    $html .= '<div class="row meet">';
    $html .= '<div class="row col-xs-9 name">';
    $html .= '<div class="col-xs-9 title"><p>';
    $html .= $meet_title;
    $html .= '</p></div>';
    $html .= '<div class="col-xs-3 signup">';
    if (get_post_meta($meet_id,'signup_url') && get_post_meta($meet_id,'entries_date') && get_post_meta($meet_id,'date') && (str_replace('+','',$diff->format('%R%a')) > 0)) {
      $url = get_post_meta($meet_id,'signup_url')[0];
      $html .= '<p class="signup"><a data-toggle="tooltip" data-placement="top" title="'.$tooltipString.'" target="_blank" href="'.$url.'">Sign Up</a></p>';
    }
    $html .= '</div>';
    $html .= '</div>';
    $html .= '<div class="col-xs-3 date">';
    $html .= $date_string;
    $html .= '</div>';
    $html .= '</div>';
    $html .= '<hr>';
  }
  $html .='</div>';
  return $html;
}

function get_announcements() {
  $html = '';
	$posts_args = array(
	'category_name'    => 'Announcements',
	'orderby'          => 'date',
	'order'            => 'DESC',
	'post_type'        => 'post',
	'post_status'      => 'publish'
  );
	$posts = get_posts($posts_args);

  $html .='<div class="team_announcements_container">';
  foreach($posts as $post) {
    $post_id = $post->ID;
    $post_title = get_the_title($post_id);
    $post_uri = get_permalink($post_id);
    $post_img = nusc_get_featured_image_url($post_id,'full');

    $post_author_id = get_post_field( 'post_author', $post_id );
    $post_author_name = get_userdata($post_author_id)->display_name;

    $post_date = get_the_time('Y-m-d', $post_id);

    $html .= '<a type="button" class="launch_announcements_modal" data-toggle="modal" data-target="#Announcements"';
    $html .= ' data-url="'.$post_uri.'"';
    $html .= ' data-title="'.$post_title.'"';
    $html .= ' data-author="'.$post_author_name.'"';
    $html .= ' data-date="'.$post_date.'"';
    $html .= '><div class="row announcement"><div class="col-xs-11 title"><p>'.$post_title.'</p></div>';
    $html .= '<div class="col-xs-1 popout">';
    $html .= '<span class="glyphicon glyphicon-new-window" aria-hidden="true"></span>';
    $html .= '</div></a>';
    $html .= '</div>';
    $html .= '<hr>';
  }
  $html .='</div>';
  return $html;
}

function get_swimmer_links() {
  $html = '';
	$posts_args = array(
	'category_name'    => 'Swimmer Links',
	'orderby'          => 'date',
	'order'            => 'DESC',
	'post_type'        => 'post',
	'post_status'      => 'publish'
  );
	$posts = get_posts($posts_args);

  $html .='<div class="team_announcements_container">';
  foreach($posts as $post) {
    $post_id = $post->ID;
    $post_title = get_the_title($post_id);
    $post_uri = get_permalink($post_id);
    $post_img = nusc_get_featured_image_url($post_id,'full');

    $post_author_id = get_post_field( 'post_author', $post_id );
    $post_author_name = get_userdata($post_author_id)->display_name;

     $post_date = get_the_time('Y-m-d', $post_id);

    $html .= '';
    $html .= '<a type="button" class="launch_announcements_modal" data-toggle="modal" data-target="#Announcements"';
    $html .= ' data-url="'.$post_uri.'"';
    $html .= ' data-title="'.$post_title.'"';
    $html .= ' data-author="'.$post_author_name.'"';
    $html .= ' data-date="'.$post_date.'"';
    $html .= '><div class="row announcement"><div class="col-xs-11 title"><p>'.$post_title.'</p></div>';
    $html .= '<div class="col-xs-1 popout">';
    $html .= '<span class="glyphicon glyphicon-new-window" aria-hidden="true"></span>';
    $html .= '</div></a>';
    $html .= '</div>';
    $html .= '<hr>';
  }
  $html .='</div>';
  return $html;
}

function display_eboard($eboard) {
  if ($eboard == 1) {return '(P)';}
  else if ($eboard == 2) {return '(VP)';}
  else if ($eboard == 3) {return '(T)';}
  else if ($eboard == 4) {return '(S)';}
  else if ($eboard == 5) {return '(SID)';}
}

function display_year($year) {
  if ($year == 1) {return 'Freshman';}
  else if ($year == 2) {return 'Sophomore';}
  else if ($year == 3) {return 'Middler';}
  else if ($year == 4) {return 'Junior';}
  else if ($year == 5) {return 'Senior';}
  else if ($year == 6) {return 'Grad Student';}
}

function display_height($inches) {
  $new_inches = $inches % 12;
  $new_feet = $inches / 12;
  return 'Height: '.$new_feet." ft ".$new_inches.' in';
}

function get_modal_picture($swimmer) {
  $html = '<a class="launch_swimmer_modal" type="button" data-toggle="modal" data-target="#SwimmerModal" ';
  foreach($swimmer as $key=>$attribute) {
    $html .= 'data-'.$key.'="'.$attribute.'"';
  }
  $src = get_bloginfo('url').'/wp-content/uploads/';
  $src .= strtolower(str_replace(array("-"," "),"_",str_replace("'","",$swimmer->Last)).'_'.$swimmer->First).'.jpg';
  $html .= 'data-img_url="'.get_swimmer_img_url($swimmer,$src).'"';
  $html .= '>';
  $html .= '<img src="'.get_swimmer_img_url($swimmer, $src).'" alt="'.$swimmer->First." ".$swimmer->Last.'"/>';
  $html .= '</a>';
  return $html;
}

function get_modal_name($swimmerID, $name) {
  global $wpdb;
  $swimmer_active = $wpdb->get_results("SELECT *
                                        FROM Athlete AS A
                                        JOIN Athlete_CusFields AF on A.ID_NO = AF.`ID#`
                                        WHERE A.Athlete = $swimmerID");
  if ($swimmer_active) {
    $swimmer = $wpdb->get_results("SELECT
      Athlete,CASE WHEN Pref != '' THEN Pref ELSE First END as First,
      Last,
      Sex,
      Inactive,
      Height,
      CurrentYear,
      EBoard,
      Stroke1,
      Stroke2,
      Major1,
      Major2,
      Medication as HS,
      CONCAT(City, ', ', State) AS Hometown
      FROM
      (SELECT Athlete,ID_NO,First,Pref,Last,Sex,Inactive,Medication,CusValue1,CusValue2,CusValue3,CusValue4,CusValue5,CusValue6,CusValue7,City, State FROM Athlete
      UNION ALL
      SELECT Athlete,null,First,Pref,Last,Sex,Inactive,null,CusValue1,CusValue2,CusValue3,CusValue4,CusValue5,CusValue6,CusValue7,City, State FROM MANUAL_Athlete)
      AS A JOIN Athlete_CusFields AF on A.ID_NO = AF.ID_NO
      WHERE A.Athlete = $swimmerID")[0];
    $html = '<a class="launch_swimmer_modal" type="button" data-toggle="modal" data-target="#SwimmerModal" ';
    foreach($swimmer as $key=>$attribute) {
      $html .= 'data-'.$key.'="'.$attribute.'"';
    }
    $src = get_bloginfo('url').'/wp-content/themes/'.get_stylesheet();
    $src .= '/media/images/headshots/'.strtolower(str_replace(array("-"," "),"_",str_replace("'","",$swimmer->Last)).'_'.$swimmer->First).'.jpg';

    $html .= 'data-img_url="'.$src.'"';
    $html .= '>';
  }
  $html .= '<p>'.$name.'</p>';
  $html .= '</a>';
  return $html;
}

function get_swimmer_img_url($swimmer, $src) {
  $source = $_SERVER['DOCUMENT_ROOT'].'/clubswimming/wp-content/uploads/'.strtolower(str_replace(array("-"," "),"_",str_replace("'","",$swimmer->Last)).'_'.$swimmer->First).'.jpg';
  return (file_exists($source)) ? $src : get_bloginfo('url').'/wp-content/uploads/athlete_placeholder.jpg';
}

function get_coaches() {
  $html = '';
  $html .= '<div class="row">';
    $html .= '<div class="col-sm-6 coach">';
      $html .= '<div class="coach_container">';
        $html .= '<img src="https://www.northeastern.edu/clubswimming/wp-content/uploads/darmody_kip.jpg">';
        $html .= '<h3>Kip Darmody</h3>';
      $html .='</div>';
    $html .='</div>';
    $html .= '<div class="col-sm-6 coach">';
      $html .= '<div class="coach_container">';
        $html .= '<img src="https://www.northeastern.edu/clubswimming/wp-content/uploads/renaud_alice.jpg">';
        $html .= '<h3>Alice Renaud</h3>';
      $html .='</div>';
    $html .='</div>';
  $html .='</div>';
  return $html;
}

function get_roster($sex) {
	global $wpdb;
  $swimmers = $wpdb->get_results("SELECT Athlete,
                                  CASE WHEN Pref != '' THEN Pref ELSE First END as First,
                                  A.Last,
                                  A.Sex,
                                  AF.Height,
                                  AF.CurrentYear,
                                  AF.EBoard,
                                  AF.Stroke1,
                                  AF.Stroke2,
                                  AF.Major1,
                                  AF.Major2,
                                  A.Medication as HS,
                                  A.City,
                                  A.State,
                                  A.Cntry,
                                  CONCAT(A.City, ', ', A.State) AS Hometown
                                  FROM Athlete A JOIN Athlete_CusFields AF on A.ID_NO = AF.`ID#`
                                  WHERE Sex = '".$sex."' AND Inactive = 0 ORDER BY Last ASC");
	$html = '';
	foreach ($swimmers as $swimmer) {
    $stroke = (empty($swimmer->Stroke2)) ? $swimmer->Stroke1 : $swimmer->Stroke1.'/'.$swimmer->Stroke2;
    $major = (empty($swimmer->Major2)) ? $swimmer->Major1 : $swimmer->Major1.'/'.$swimmer->Major2;
    $hometown = (empty($swimmer->State) || $swimmer->Cntry != 'USA') ? $swimmer->City.', '.$swimmer->Cntry : $swimmer->Hometown;
		$full_name = $swimmer->First.' '.$swimmer->Last;
		$html .= '<div class="row roster-row">';
			$html .= '<div class="col-sm-2 pic">';
      $html .= get_modal_picture($swimmer);
			$html .= '</div>';
			$html .= '<div class="row inner-roster-row col-sm-10">';
				$html .= '<div class="col-sm-4 name">';
					$html .= '<p>';
          $html .= (empty($swimmer->EBoard)) ? $full_name : $full_name.' '.display_eboard($swimmer->EBoard);
          $html .= '</p>';
				$html .= '</div>';
				$html .= '<div class="col-sm-4 year">';
          $html .= (empty($swimmer->CurrentYear)) ? '<p class="unknown">Year In School</p>' : '<p>'.display_year($swimmer->CurrentYear).'</p>';
				$html .= '</div>';
				$html .= '<div class="col-sm-4 hometown">';
          $html .= (empty($hometown)) ? '<p class="unknown">Hometown</p>' : '<p>'.$hometown.'</p>';
				$html .= '</div>';
        $html .= '<div class="col-sm-4 stroke">';
          $html .= (empty($stroke)) ? '<p class="unknown">Stroke</p>' : '<p>'.$stroke.'</p>';
				$html .= '</div>';
        $html .= '<div class="col-sm-4 high_school">';
          $html .= (empty($swimmer->HS)) ? '<p class="unknown">High School</p>' : '<p>'.$swimmer->HS.'</p>';
				$html .= '</div>';
        $html .= '<div class="col-sm-4 major">';
          $html .= (empty($major)) ? '<p class="unknown">Major</p>' : '<p>'.$major.'</p>';
        $html .= '</div>';
			$html .= '</div>';
		$html .= '</div>';
	}
	return $html;
}

function get_times_table($athlete_id) {
  global $wpdb;
  $html = '';
  $season_count = 0;
  $seasons = $wpdb->get_results("SELECT YEAR(DATE_ADD(MEET.Start, INTERVAL 6 MONTH)) as year FROM
    (SELECT ATHLETE, MEET FROM RESULTS WHERE ATHLETE = $athlete_id AND I_R = 'I'
    UNION ALL
    SELECT ATHLETE, MEET FROM MANUAL_RESULTS WHERE ATHLETE = $athlete_id AND I_R = 'I'
    UNION ALL
    SELECT RESULTS.ATHLETE, RESULTS.MEET FROM RESULTS
		JOIN RELAY ON RESULTS.ATHLETE = RELAY.RELAY
        WHERE RELAY.`ATH(1)` = $athlete_id
			      OR RELAY.`ATH(2)` = $athlete_id
            OR RELAY.`ATH(3)` = $athlete_id
            OR RELAY.`ATH(4)` = $athlete_id)
    AS RESULTS NATURAL JOIN MEET WHERE ATHLETE = $athlete_id GROUP BY year ORDER BY year DESC");
  $html .='<div id="timestable">';

  if (empty($seasons)){
    $html.='<div class="row best_time">
      <div class="no_times">No Times Available</div>
    </div>';
  }

  foreach($seasons as $season) {
    $results = $wpdb->get_results("SELECT RESULTS.MEET,
      Athlete.ATHLETE,
      Athlete.SEX as Sex,
     MIN(TRIM(RESULTS.SCORE)) as SCORE,
     RESULTS.DISTANCE,
     RESULTS.STROKE,
     RESULTS.PLACE,
     RESULTS.I_R,
     MName,
     Start,
     Location,
     date_format(Start,'%M %D, %Y') as meetdate,
     QUALIFYING_TIMES.FemaleTime as FemaleTime,
     QUALIFYING_TIMES.MaleTime as MaleTime FROM (
       SELECT MEET,
	     ATHLETE,
       SUBSTRING(CAST(
		CASE
		WHEN SCORE IS NULL THEN 0
		WHEN LOCATE(\":\",SCORE) = 0 THEN TIME_FORMAT(SCORE,'%i:%s.%f')
		WHEN LOCATE(\":\",SCORE) >= 3 THEN TIME_FORMAT(SCORE,'%h:%i.%f')
		ELSE \"panda\"
		END
		AS char),1,8) AS SCORE,
       DISTANCE,
       STROKE,
       PLACE,
       I_R
    FROM RESULTS
    WHERE I_R = 'I'
        OR I_R = 'L'
    UNION ALL
    SELECT MEET,
    	     ATHLETE,
           TRIM(SCORE) as SCORE,
           DISTANCE,
           STROKE,
           PLACE,
           I_R
    FROM MANUAL_RESULTS
    WHERE I_R = 'I'
        OR I_R = 'L') as RESULTS
    JOIN MEET ON MEET.MEET = RESULTS.MEET
    JOIN QUALIFYING_TIMES on concat(RESULTS.DISTANCE,' ',RESULTS.STROKE) = QUALIFYING_TIMES.Event
    JOIN Athlete on Athlete.Athlete = RESULTS.Athlete
    JOIN (SELECT ATHLETE, DISTANCE, STROKE, MIN(LPAD(TRIM(`SCORE`),8,'00:')) AS SCORE
		FROM (SELECT MEET,
			ATHLETE,
			LPAD(TRIM(`SCORE`),8,'00:') AS SCORE,
			DISTANCE,
			STROKE
			FROM RESULTS
			WHERE SCORE != '00:00:00'
				AND SCORE != ''
				AND (I_R = 'I' OR I_R = 'L')
				AND ATHLETE = $athlete_id
		UNION ALL
		SELECT MEET,
			ATHLETE,
			LPAD(TRIM(`SCORE`),8,'00:') AS SCORE,
			DISTANCE,
			STROKE
			FROM MANUAL_RESULTS
			WHERE SCORE != '00:00:00'
				AND SCORE != ''
				AND (I_R = 'I' OR I_R = 'L')
				AND ATHLETE = $athlete_id) RESULTS
		JOIN MEET ON RESULTS.MEET = MEET.MEET
        WHERE (YEAR(DATE_ADD(MEET.Start, INTERVAL 6 MONTH)) = $season->year)
			GROUP BY ATHLETE, DISTANCE, STROKE) RESULTS_2
		ON RESULTS.SCORE = RESULTS_2.SCORE
			AND RESULTS.STROKE = RESULTS_2.STROKE
			AND RESULTS.DISTANCE = RESULTS_2.DISTANCE
    WHERE RESULTS.ATHLETE = $athlete_id
    AND (YEAR(DATE_ADD(MEET.Start, INTERVAL 6 MONTH)) = $season->year)
    AND RESULTS.SCORE != ''
    GROUP BY RESULTS.DISTANCE, RESULTS.STROKE
    ORDER BY CASE
  	WHEN RESULTS.STROKE = 'Free' THEN 1
    WHEN RESULTS.STROKE = 'Back' THEN 2
    WHEN RESULTS.STROKE = 'Breast' THEN 3
    WHEN RESULTS.STROKE = 'Fly' THEN 4
    WHEN RESULTS.STROKE = 'IM' THEN 5
    ELSE RESULTS.STROKE END, CAST(RESULTS.DISTANCE as unsigned) ASC");

    $relays = $wpdb->get_results("SELECT RESULTS.MEET AS MEET,
      RELAY.RELAY AS RELAY,
      RELAY.`ATH(1)` AS ATHLETE_1,
	  RELAY.`ATH(2)` AS ATHLETE_2,
      RELAY.`ATH(3)` AS ATHLETE_3,
      RELAY.`ATH(4)` AS ATHLETE_4,
      RELAY.SEX as Sex,
     MIN(TRIM(RESULTS.SCORE)) as SCORE,
     RESULTS.DISTANCE AS DISTANCE,
     RESULTS.STROKE AS STROKE,
     RESULTS.PLACE AS PLACE,
     RESULTS.I_R AS I_R,
     MName,
     Start,
     Location,
     date_format(Start,'%M %D, %Y') as meetdate FROM (
       SELECT MEET,
	     ATHLETE,
       SUBSTRING(CAST(
		CASE
		WHEN SCORE IS NULL THEN 0
		WHEN LOCATE(\":\",SCORE) = 0 THEN TIME_FORMAT(SCORE,'%i:%s.%f')
		WHEN LOCATE(\":\",SCORE) >= 3 THEN TIME_FORMAT(SCORE,'%h:%i.%f')
		ELSE \"panda\"
		END
		AS char),1,8) AS SCORE,
       DISTANCE,
       STROKE,
       PLACE,
       I_R
    FROM RESULTS
    WHERE I_R = 'R'
    UNION ALL
    SELECT MEET,
    	     ATHLETE,
           TRIM(SCORE) as SCORE,
           DISTANCE,
           STROKE,
           PLACE,
           I_R
    FROM MANUAL_RESULTS
    WHERE I_R = 'R') as RESULTS
    JOIN MEET ON MEET.MEET = RESULTS.MEET
    JOIN RELAY ON RELAY.RELAY = RESULTS.Athlete
	JOIN (SELECT DISTANCE, STROKE, MIN(LPAD(TRIM(`SCORE`),8,'00:')) AS SCORE
		FROM (SELECT RESULTS.MEET,
			ATHLETE,
			LPAD(TRIM(`SCORE`),8,'00:') AS SCORE,
			RESULTS.DISTANCE,
			RESULTS.STROKE
			FROM RESULTS
            JOIN RELAY ON RESULTS.ATHLETE = RELAY.RELAY
			WHERE RESULTS.SCORE != '00:00:00'
				AND RESULTS.SCORE != ''
				AND RESULTS.I_R = 'R'
                AND (RELAY.`ATH(1)` = $athlete_id
					OR RELAY.`ATH(2)` = $athlete_id
                    OR RELAY.`ATH(3)` = $athlete_id
                    OR RELAY.`ATH(4)` = $athlete_id)
		UNION ALL
		SELECT MANUAL_RESULTS.MEET,
			ATHLETE,
			LPAD(TRIM(`SCORE`),8,'00:') AS SCORE,
			MANUAL_RESULTS.DISTANCE,
			MANUAL_RESULTS.STROKE
			FROM MANUAL_RESULTS
            JOIN RELAY ON MANUAL_RESULTS.ATHLETE = RELAY.RELAY
			WHERE SCORE != '00:00:00'
				AND SCORE != ''
				AND I_R = 'R'
				AND (RELAY.`ATH(1)` = $athlete_id
					OR RELAY.`ATH(2)` = $athlete_id
                    OR RELAY.`ATH(3)` = $athlete_id
                    OR RELAY.`ATH(4)` = $athlete_id)) RESULTS
		JOIN MEET ON RESULTS.MEET = MEET.MEET
        WHERE (YEAR(DATE_ADD(MEET.Start, INTERVAL 6 MONTH)) = $season->year)
			GROUP BY DISTANCE, STROKE) RESULTS_2
		ON RESULTS.SCORE = RESULTS_2.SCORE
			AND RESULTS.STROKE = RESULTS_2.STROKE
			AND RESULTS.DISTANCE = RESULTS_2.DISTANCE
    AND (YEAR(DATE_ADD(MEET.Start, INTERVAL 6 MONTH)) = $season->year)
    AND RESULTS.SCORE != ''
    GROUP BY RESULTS.DISTANCE, RESULTS.STROKE
    ORDER BY CASE
  	WHEN RESULTS.STROKE = 'Free' THEN 1
    WHEN RESULTS.STROKE = 'Back' THEN 2
    WHEN RESULTS.STROKE = 'Breast' THEN 3
    WHEN RESULTS.STROKE = 'Fly' THEN 4
    WHEN RESULTS.STROKE = 'IM' THEN 5
    ELSE RESULTS.STROKE END, CAST(RESULTS.DISTANCE as unsigned) ASC");

    if (!empty($results)) {
      $html .='<div class="season_header dark_blue">
      <b><big>'.($season_count == 0 ? 'Current' : (($season->year - 1).' - '.$season->year)).' Season</big></b>
      </div>
      <div class="row dark_blue column_headings">
      <div class="event"><strong>Event</strong></div>
      <div class="time"><strong>Time</strong></div>
      <div class="meet"><strong>Meet</strong></div>
      <div class="venue"><strong>Venue</strong></div>
      <div class="place"><strong>Place</strong></div>
      <div class="date"><strong>Date</strong></div>
      </div>';
    }

    $isCurrentSeason = ($season_count == 0) ? true : false;
    foreach($results as $result) {
      $qual_time = ($result->Sex == 'M') ? $result->MaleTime : $result->FemaleTime;
      $html .= '<div class="row best_time">';
    	  $html .= '<div class="event';
        $html .= (($result->SCORE < $qual_time) && $isCurrentSeason) ? ' qualified' : "";
        $html .= '">';
        $html .= $result->DISTANCE.' '.$result->STROKE;
        $html .= ($result->I_R == "R") ? " Relay" : "";
        $html .= '</div>';
        $html .= '<div class="time';
        if ($isCurrentSeason) {
          if ($result->SCORE < $qual_time) {
            $html .= ' qualified';
          } else {
            $timeToDrop = time_to_seconds($result->SCORE) - time_to_seconds($qual_time);
            $tooltipString = "Drop ".$timeToDrop." seconds to qualify!";
            $html .= '" data-toggle="tooltip" data-placement="top" title="'.$tooltipString;
          }
        }
        $html .= '">';
        $html .= $result->SCORE;
        $html .= ($result->PLACE < 20 && strpos($result->MName, 'Nationals') !== false) ? /*'^'*/'' : '';
        $html .= '</div>';
    	  $html .= '<div class="meet">'.$result->MName.'</div>';
    	  $html .= '<div class="venue">'.$result->Location.'</div>';
    	  $html .= '<div class="place">'.$result->PLACE.'</div>';
      	$html .= '<div class="date">'.$result->meetdate.'</div>';
      $html .= '</div>';
    }
    foreach($relays as $relay) {
      $html .= '<div class="row best_time">';
      $html .= '<div class="event">';
      $html .= $relay->DISTANCE.' ';
      $html .= ($relay->STROKE == "IM") ? "Medley Relay" : $relay->STROKE.' Relay';
      $html .= '</div>';
      $html .= '<div class="time">';
      $html .= $relay->SCORE;
      $html .= ($relay->PLACE < 20 && strpos($relay->MName, 'Nationals') !== false) ? /*'^'*/'' : '';
      $html .= '</div>';
      $html .= '<div class="meet">'.$relay->MName.'</div>';
      $html .= '<div class="venue">'.$relay->Location.'</div>';
      $html .= '<div class="place">'.$relay->PLACE.'</div>';
      $html .= '<div class="date">'.$relay->meetdate.'</div>';
      $html .= '</div>';
    }
    $season_count++;
  }
  $html .= '</div>';
  return $html;
}

function get_eboard($sex) {
  global $wpdb;
  $eboard = $wpdb->get_results("SELECT
    Athlete,
    CASE WHEN Pref != '' THEN Pref ELSE First END as First,
    Last,
    Sex,
    Inactive,
    Height,
    CurrentYear,
    EBoard,
    Stroke1,
    Stroke2,
    Medication as HS,
    CONCAT(City, ', ', State) AS Hometown
    FROM Athlete A JOIN Athlete_CusFields AF on A.ID_NO = AF.`ID#`
    WHERE Sex = \"$sex\" and EBoard != ''
    AND Inactive = 0
    ORDER BY EBoard ASC");
  return $eboard;
}

function get_front_eboard_row($sex) {
  $html = '';
  $eboard = get_eboard($sex);
  foreach ($eboard as $member) {
    $html .= '<div class="front_eboard_member">';
    $html .= get_modal_picture($member);
    $html .= '</div>';
  }
  return $html;
}

function get_past_seasons() {
  $html = '';
  global $wpdb;
  global $current_season;
  $seasons = $wpdb->get_results("SELECT YEAR(MEET.Start) as FirstYear, YEAR(MEET.Start) + 1 as SecondYear FROM MEET
  WHERE YEAR(MEET.Start) < (SELECT
  CASE WHEN MONTH(CURRENT_TIMESTAMP()) <= 6 THEN YEAR(CURRENT_TIMESTAMP()) - 1
  ELSE YEAR(CURRENT_TIMESTAMP())
  END as FirstYear)
  GROUP BY FirstYear ORDER BY FirstYear DESC");


  $html .= '<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">';

  $count = 0;
  foreach($seasons as $season) {
    $aria_expanded = ($count == 0) ? array('true','',' in','') : array('false','collapsed','',' style="height: 0px;"');
    $html .= '<div class="panel panel-default">
      <div class="panel-heading" role="tab" id="headingOne">
        <h4 class="panel-title">
          <a role="button" data-toggle="collapse" href="#collapse'.$season->FirstYear.'" aria-expanded="'.$aria_expanded[0].'" aria-controls="collapseOne" class="'.$aria_expanded[1].'">
            '.$season->FirstYear.' - '.$season->SecondYear.' Season
          </a>
        </h4>
      </div>
      <div id="collapse'.$season->FirstYear.'" class="panel-collapse collapse'.$aria_expanded[2].'" role="tabpanel" aria-labelledby="headingOne" aria-expanded="false"'.$aria_expanded[3].'>
        <div class="panel-body past-season">';
        $meets = $wpdb->get_results("SELECT MEET,
                                        	   MName,
                                               YEAR(Start) as Year,
                                               MONTH(Start) as Month,
                                               date_format(MEET.Start,'%M %D, %Y') as meetdate
                                               FROM MEET
                                               WHERE ((YEAR(MEET.Start) = $season->FirstYear AND MONTH(MEET.Start) > 6) OR (YEAR(MEET.Start) = $season->SecondYear AND MONTH(MEET.Start) <= 6))");
        foreach($meets as $meet) {
          $html .= '<div class="row meet_row">
            <div class="col-md-2 logo">
              <div class="logo_container">
                <img src="https://www.northeastern.edu/clubswimming/wp-content/uploads/2016/05/NU.png">
              </div>
            </div>
            <div class="col-md-5 meet">
              <p>'.$meet->MName.'</p>
            </div>
            <div class="col-md-3 date">
              <p>'.$meet->meetdate.'</p>
            </div>
            <div class="col-md-2 results">
              <p>Results</p>

            </div>
          </div>';
        }
        $html .= '</div>';
      $html .= '</div>';
    $html .= '</div>';
    $count++;
  }
  $html .= '</div>';
  return $html;
}

function get_current_season() {
  $html = '';
  global $wpdb;
  global $current_season;
  $aria_expanded = array('true','',' in','');
  $html .= '<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">';
    $html .= '<div class="panel panel-default">
      <div class="panel-heading" role="tab" id="headingOne">
        <h4 class="panel-title">
          <a role="button" data-toggle="collapse" href="#collapse'.$current_season->CurrentSeason.'" aria-expanded="'.$aria_expanded[0].'" aria-controls="collapseOne" class="'.$aria_expanded[1].'">
            '.$current_season->CurrentSeason.' Season
          </a>
        </h4>
      </div>
      <div id="collapse'.$current_season->CurrentSeason.'" class="panel-collapse collapse'.$aria_expanded[2].'" role="tabpanel" aria-labelledby="headingOne" aria-expanded="false"'.$aria_expanded[3].'>
        <div class="panel-body past-season">';
        $meets = $wpdb->get_results("SELECT MEET,
                                        	   MName,
                                             date_format(MEET.Start,'%M %D, %Y') as meetdate
                                             FROM (SELECT MEET,MName,Start FROM MEET UNION ALL SELECT MEET,MName,Start FROM MANUAL_MEET) as MEET
                                             WHERE (YEAR(DATE_ADD(MEET.Start, INTERVAL 6 MONTH)) = $current_season->CurrentSeason)");
        if ($meets) {
          foreach($meets as $meet) {
            $html .= '<div class="row meet_row">
              <div class="col-md-2 logo">
                <div class="logo_container">
                  <img src="https://www.northeastern.edu/clubswimming/wp-content/uploads/2016/05/NU.png">
                </div>
              </div>
              <div class="col-md-5 meet">
                <p>'.$meet->MName.'</p>
              </div>
              <div class="col-md-3 date">
                <p>'.$meet->meetdate.'</p>
              </div>
              <div class="col-md-1 results">
                <p>Results</p>
              </div>
            </div>';
          }
        }
        else {
          $html .= '<div class="row meet_row">
            <div class="col-md-2 logo">
              <div class="logo_container">
                <img src="https://www.northeastern.edu/clubswimming/wp-content/uploads/2016/05/NU.png">
              </div>
            </div>
            <div class="col-md-9 meet">
              <p>We have not yet competed in any meets this season</p>
            </div>
          </div>';
        }
        $html .= '</div>';
      $html .= '</div>';
    $html .= '</div>';
  $html .= '</div>';
  return $html;
}

function get_top_ten() {
  $html = '';
  global $wpdb;
  $events = $wpdb->get_results("SELECT DISTANCE,STROKE FROM RESULTS WHERE I_R = 'I' GROUP BY DISTANCE,STROKE ORDER BY CASE
                                                                                                    	WHEN STROKE = 'Free' THEN 1
                                                                                                        WHEN STROKE = 'Back' THEN 2
                                                                                                        WHEN STROKE = 'Breast' THEN 3
                                                                                                        WHEN STROKE = 'Fly' THEN 4
                                                                                                        WHEN STROKE = 'IM' THEN 5
                                                                                                        ELSE STROKE END, CAST(DISTANCE as unsigned) ASC");

  $html .= '<div id="team_records_container">';
  $html .= '<div class="dark_blue">';
  $html .= '<h3>Record Board</h3>';
  $html .= '</div>';

  foreach($events as $event) {
    $mens_times = $wpdb->get_results("SELECT ATHLETE,
	                                        MIN(SCORE) as SCORE,
	                                        DISTANCE,
	                                        STROKE,
	                                        YEAR(Start) as YEAR,
	                                        CASE WHEN Pref != '' THEN Pref ELSE First END as First,
	                                        Last,
	                                        SEX
                                        FROM (SELECT C_RESULTS.MEET,
	                                        C_RESULTS.ATHLETE,
                                            LPAD(TRIM(C_RESULTS.SCORE),8,'00:') AS SCORE,
                                            C_RESULTS.DISTANCE,
                                            C_RESULTS.STROKE,
                                            C_RESULTS.PLACE,
                                            C_RESULTS.I_R,
                                            C_RESULTS.EX
                                            FROM (
		                                        SELECT MEET,
		                                        	ATHLETE,
                                                    LPAD(TRIM(`SCORE`),8,'00:') AS SCORE,
                                                    DISTANCE,
                                                    STROKE,
                                                    PLACE,
                                                    I_R,
                                                    EX
			                                        FROM RESULTS
		                                        UNION ALL
                                                SELECT MEET,
			                                        ATHLETE,
                                                    LPAD(TRIM(`SCORE`),8,'00:') AS SCORE,
                                                    DISTANCE,
                                                    STROKE,
                                                    PLACE,
                                                    I_R,
                                                    \"\" AS EX
                                                    FROM MANUAL_RESULTS) C_RESULTS
		                                        JOIN (SELECT ATHLETE, DISTANCE, STROKE, MIN(LPAD(TRIM(`SCORE`),8,'00:')) AS SCORE
		                                        	FROM (SELECT MEET,
		                                        		ATHLETE,
		                                        		LPAD(TRIM(`SCORE`),8,'00:') AS SCORE,
		                                        		DISTANCE,
		                                        		STROKE
		                                        		FROM RESULTS
                                                        WHERE SCORE != '00:00:00'
		                                        			AND SCORE != ''
                                                        AND (I_R = 'I' OR I_R = 'L')
		                                        	UNION ALL
		                                        	SELECT MEET,
		                                        		ATHLETE,
		                                        		LPAD(TRIM(`SCORE`),8,'00:') AS SCORE,
		                                        		DISTANCE,
		                                        		STROKE
		                                        		FROM MANUAL_RESULTS
		                                        		WHERE SCORE != '00:00:00'
		                                        			AND SCORE != ''
                                                        AND (I_R = 'I' OR I_R = 'L')) RESULTS
                                                        GROUP BY ATHLETE, DISTANCE, STROKE) RESULTS_2
		                                        	ON C_RESULTS.SCORE = RESULTS_2.SCORE
		                                        		AND C_RESULTS.STROKE = RESULTS_2.STROKE
		                                        		AND C_RESULTS.DISTANCE = RESULTS_2.DISTANCE
                                                        AND C_RESULTS.ATHLETE = RESULTS_2.ATHLETE
                                            ) as RESULTS NATURAL JOIN
                                        	(SELECT MEET, Start FROM MEET
                                        	UNION ALL
                                        	SELECT MEET, Start FROM MANUAL_MEET) AS MEET NATURAL JOIN
                                        	(SELECT Athlete,
                                        		First,
                                        		Last,
                                        		Sex,
                                        		Pref
                                        		FROM Athlete
                                        	UNION ALL
                                        	SELECT Athlete,
                                        		First,
                                        		Last,
                                        		Sex,
                                        		Pref
                                        		FROM MANUAL_Athlete) AS Athlete
                                        	WHERE SCORE != '00:00:00'
                                        		AND EX != 'X'
                                        		AND (I_R = 'I' OR I_R = 'L')
                                        		AND SEX = 'M'
                                            AND DISTANCE = \"$event->DISTANCE\"
                                            AND STROKE = \"$event->STROKE\"
                                        		GROUP BY ATHLETE
                                        		ORDER BY SCORE ASC
                                        		LIMIT 10");

    $womens_times = $wpdb->get_results("SELECT ATHLETE,
	                                        MIN(SCORE) as SCORE,
	                                        DISTANCE,
	                                        STROKE,
	                                        YEAR(Start) as YEAR,
	                                        CASE WHEN Pref != '' THEN Pref ELSE First END as First,
	                                        Last,
	                                        SEX
                                        FROM (SELECT C_RESULTS.MEET,
	                                        C_RESULTS.ATHLETE,
                                            LPAD(TRIM(C_RESULTS.SCORE),8,'00:') AS SCORE,
                                            C_RESULTS.DISTANCE,
                                            C_RESULTS.STROKE,
                                            C_RESULTS.PLACE,
                                            C_RESULTS.I_R,
                                            C_RESULTS.EX
                                            FROM (
		                                        SELECT MEET,
		                                        	ATHLETE,
                                                    LPAD(TRIM(`SCORE`),8,'00:') AS SCORE,
                                                    DISTANCE,
                                                    STROKE,
                                                    PLACE,
                                                    I_R,
                                                    EX
			                                        FROM RESULTS
		                                        UNION ALL
                                                SELECT MEET,
			                                        ATHLETE,
                                                    LPAD(TRIM(`SCORE`),8,'00:') AS SCORE,
                                                    DISTANCE,
                                                    STROKE,
                                                    PLACE,
                                                    I_R,
                                                    \"\" AS EX
                                                    FROM MANUAL_RESULTS) C_RESULTS
		                                        JOIN (SELECT ATHLETE, DISTANCE, STROKE, MIN(LPAD(TRIM(`SCORE`),8,'00:')) AS SCORE
		                                        	FROM (SELECT MEET,
		                                        		ATHLETE,
		                                        		LPAD(TRIM(`SCORE`),8,'00:') AS SCORE,
		                                        		DISTANCE,
		                                        		STROKE
		                                        		FROM RESULTS
                                                        WHERE SCORE != '00:00:00'
		                                        			AND SCORE != ''
                                                        AND (I_R = 'I' OR I_R = 'L')
		                                        	UNION ALL
		                                        	SELECT MEET,
		                                        		ATHLETE,
		                                        		LPAD(TRIM(`SCORE`),8,'00:') AS SCORE,
		                                        		DISTANCE,
		                                        		STROKE
		                                        		FROM MANUAL_RESULTS
		                                        		WHERE SCORE != '00:00:00'
		                                        			AND SCORE != ''
                                                        AND (I_R = 'I' OR I_R = 'L')) RESULTS
                                                        GROUP BY ATHLETE, DISTANCE, STROKE) RESULTS_2
		                                        	ON C_RESULTS.SCORE = RESULTS_2.SCORE
		                                        		AND C_RESULTS.STROKE = RESULTS_2.STROKE
		                                        		AND C_RESULTS.DISTANCE = RESULTS_2.DISTANCE
                                                        AND C_RESULTS.ATHLETE = RESULTS_2.ATHLETE
                                            ) as RESULTS NATURAL JOIN
                                        	(SELECT MEET, Start FROM MEET
                                        	UNION ALL
                                        	SELECT MEET, Start FROM MANUAL_MEET) AS MEET NATURAL JOIN
                                        	(SELECT Athlete,
                                        		First,
                                        		Last,
                                        		Sex,
                                        		Pref
                                        		FROM Athlete
                                        	UNION ALL
                                        	SELECT Athlete,
                                        		First,
                                        		Last,
                                        		Sex,
                                        		Pref
                                        		FROM MANUAL_Athlete) AS Athlete
                                        	WHERE SCORE != '00:00:00'
                                        		AND EX != 'X'
                                        		AND (I_R = 'I' OR I_R = 'L')
                                        		AND SEX = 'F'
                                            AND DISTANCE = \"$event->DISTANCE\"
                                            AND STROKE = \"$event->STROKE\"
                                        		GROUP BY ATHLETE
                                        		ORDER BY SCORE ASC
                                        		LIMIT 10");

    $all_times = array();
    $num_records = max(count($womens_times),count($mens_times));
    for ($i = 0; $i < $num_records; $i++) {
      $all_times[$i] = array($womens_times[$i],$mens_times[$i]);
    }

    $html .= '<div id="'.$event->DISTANCE.$event->STROKE.'" class="dark_blue">';
    $html .= '<h4>'.$event->DISTANCE.' '.$event->STROKE.'</h4>';
    $html .= '<div class="row records_header"><div class="col-md-3 person"><p>Women</p></div><div class="col-md-1 time"><p>Time</p></div><div class="col-md-1 year"><p>Year</p></div><div class="col-md-2 rank"><p>Rank</p></div><div class="col-md-3 person"><p>Men</p></div><div class="col-md-1 time"><p>Time</p></div><div class="col-md-1 year"><p>Year</p></div></div>';
    $html .= '</div>';

    $count = 1;


    foreach ($all_times as $times) {
    $html .= '<div class="row records_row">';
    //$html .= '<div class="col-md-3 person"><p>'.$times[0]->First.' '.$times[0]->Last.'</p></div>';
    $html .= '<div class="col-md-3 person">'.get_modal_name($times[0]->ATHLETE, $times[0]->First.' '.$times[0]->Last).'</div>';
    $html .= '<div class="col-md-1 time"><p>'.$times[0]->SCORE.'</p></div>';
    $html .= '<div class="col-md-1 year"><p>'.$times[0]->YEAR.'</p></div>';
    $html .= '<div class="col-md-2 event"><p>'.$count.'</p></div>';
    $html .= '<div class="col-md-3 person">'.get_modal_name($times[1]->ATHLETE, $times[1]->First.' '.$times[1]->Last).'</div>';
    $html .= '<div class="col-md-1 time"><p>'.$times[1]->SCORE.'</p></div>';
    $html .= '<div class="col-md-1 year"><p>'.$times[1]->YEAR.'</p></div>';
    $html .= '</div>';
    $count++;
    }
  }
  $html .= '</div>';
  return $html;
}

function get_top_ten_sidebar() {
  $html = '';
  global $wpdb;
  global $events;


  foreach($events as $event) {
    $html .= '<li>';
    $html .= '<a href="#'.$event->DISTANCE.$event->STROKE.'">'.$event->DISTANCE.' '.$event->STROKE.'</a>';
    $html .= '</li>';
  }


  return $html;
}

function get_admin_page() {
  $html = '';
  global $wpdb;
  $athletes = $wpdb->get_results("SELECT Athlete,
                                         Pref,
                                         First,
                                         Last FROM
                                         (SELECT Athlete, Pref, First, Last FROM Athlete
                                         UNION ALL
                                         SELECT Athlete, \"\" AS Pref, First, Last FROM MANUAL_Athlete)
                                         AS Athlete");
  $meets = $wpdb->get_results("SELECT Meet,
                                     MName FROM
                                     (SELECT Meet, MName FROM MEET
                                     UNION ALL
                                     SELECT Meet, MName FROM MANUAL_MEET)
                                     AS Meet");
  $html .= '<form id="add_result_form">';
    //Athlete
    $html .= '<div class="input-group mb-3">
      <div class="input-group-prepend">
        <span class="input-group-text" id="basic-addon1">Athlete:</span>
      </div>
      <input name="athlete" type="text" class="form-control" placeholder="Athlete" aria-label="Athlete" aria-describedby="basic-addon1" list="athletes">
    </div>';

    //Meet
    $html .= '<div class="input-group mb-3">
      <div class="input-group-prepend">
        <span class="input-group-text" id="basic-addon2">Meet:</span>
      </div>
      <input name="meet" type="text" class="form-control" placeholder="Meet" aria-label="Meet" aria-describedby="basic-addon2" list="meets">
    </div>';

    //Time
    $html .= '<div class="input-group mb-3">
      <div class="input-group-prepend">
        <span class="input-group-text" id="basic-addon3">Time:</span>
      </div>
      <input name="score" type="text" class="form-control" placeholder="Time" aria-label="Time" aria-describedby="basic-addon3" pattern="\d{2}:?\d{2}.?\d{2}">
    </div>';

    //Distance
    $html .= '<div class="input-group mb-3">
      <div class="input-group-prepend">
        <span class="input-group-text" id="basic-addon4">Distance:</span>
      </div>
      <select name="distance" class="form-control" aria-label="Distance" aria-describedby="basic-addon4">
        <option value="50">50</option>
        <option value="100">100</option>
        <option value="200">200</option>
        <option value="400">400</option>
        <option value="500">500</option>
        <option value="1000">1000</option>
        <option value="1650">1650</option>
      </select>
    </div>';

    //Stroke
    $html .= '<div class="input-group mb-3">
      <div class="input-group-prepend">
        <span class="input-group-text" id="basic-addon5">Stroke:</span>
      </div>
      <select name="stroke" class="form-control" aria-label="Stroke" aria-describedby="basic-addon5">
        <option value="Free">Freestyle</option>
        <option value="Back">Backstroke</option>
        <option value="Breast">Breaststroke</option>
        <option value="Fly">Butterfly</option>
        <option value="IM">IM</option>
      </select>
    </div>';

    //Place
    $html .= '<div class="input-group mb-3">
      <div class="input-group-prepend">
        <span class="input-group-text" id="basic-addon6">Place:</span>
      </div>
      <input name="place" type="number" class="form-control" placeholder="Place" aria-label="Place" aria-describedby="basic-addon6">
    </div>';

    //I_R
    $html .= '<div class="input-group mb-3">
      <div class="input-group-prepend">
        <span class="input-group-text" id="basic-addon7">I_R:</span>
      </div>
      <input name="i_r" type="radio" value="I">Individual<br>
      <input name="i_r" type="radio" value="R">Relay<br>
      <input name="i_r" type="radio" value="L">Relay Leadoff<br>
    </div>';

    //Submit
  $html .= '<button id="add_result_btn" class="btn" type="submit">Add Time</button>';
  $html .= '</form>';

  //Data Lists
  $html .= '<datalist id="athletes">';
  foreach($athletes as $athlete) {
    $name = ($athlete->Pref != '') ? $athlete->Pref : $athlete->First;
    $name .= ' '.$athlete->Last;
    $html .= '<option value="'.$athlete->Athlete.'">'.$name.'</option>';
  }
  $html .= '</datalist>';
  $html .= '<datalist id="meets">';
  foreach($meets as $meet) {
    $html .= '<option value="'.$meet->Meet.'">'.$meet->MName.'</option>';
  }
  $html .= '</datalist>';
  return $html;
}

function get_manual_results_table() {
  global $wpdb;
  $html = '
  <table id="manual_results">
    <thead>
      <tr>
        <th>Athlete</th>
        <th>Meet</th>
        <th>Event</th>
        <th>Time</th>
      </tr>
    </thead>
    <tbody>';
    $manual_results = $wpdb->get_results("SELECT A.First, A.Last, A.Pref, M.MName, R.Distance, R.Stroke, R.Score
                                          FROM MANUAL_RESULTS R NATURAL JOIN
                                          (SELECT Athlete, First, Last, Pref FROM Athlete UNION ALL SELECT Athlete, First, Last, \"\" AS Pref FROM MANUAL_Athlete) AS A NATURAL JOIN
                                          (SELECT Meet, MName FROM MEET UNION ALL SELECT Meet, MName FROM MANUAL_MEET) AS M");
    foreach($manual_results as $result) {
      $name = ($result->Pref == '') ? $result->First.' '.$result->Last : $result->Pref.' '.$result->Last;
      $html .= "<tr><td>$name</td><td>$result->MName</td><td>$result->Distance $result->Stroke</td><td>$result->Score</td></tr>";
    }
    $html .= '</tbody>
  </table>';
  return $html;
}

add_action( 'wp_ajax_add_result', 'nusc_ajax_add_result' );

wp_register_script( 'theme-js', get_template_directory_uri() . '/theme.js', array('jquery'), '1.0', true );

function nusc_start_ajax() {
	wp_enqueue_script( 'theme-js', get_template_directory_uri() . '/theme.js', array('jquery'), '1.0', true );
	wp_localize_script('theme-js', 'my_ajax_script', array('ajaxurl' => admin_url('admin-ajax.php')));
}
add_action('template_redirect','nusc_start_ajax');

function nusc_ajax_add_result() {
  global $wpdb;
  $wpdb->insert('MANUAL_RESULTS',array(
    'MEET' => $_POST['meet'],
    'ATHLETE' => $_POST['athlete'],
    'I_R' => $_POST['i_r'],
    'SCORE' => $_POST['score'],
    'DISTANCE' => $_POST['distance'],
    'STROKE' => $_POST['stroke'],
    'PLACE' => $_POST['place']),
    array( '%d', '%d', '%s', '%s', '%s', '%s', '%d')
  );
}

function get_team_records() {
  $html = '';
  $strokeCount = 0;
  global $wpdb;
  global $events;
  $strokes = $wpdb->get_results("SELECT STROKE FROM RESULTS WHERE I_R = 'I' GROUP BY STROKE ORDER BY CASE
      WHEN STROKE = 'Free' THEN 1
      WHEN STROKE = 'Back' THEN 2
      WHEN STROKE = 'Breast' THEN 3
      WHEN STROKE = 'Fly' THEN 4
      WHEN STROKE = 'IM' THEN 5
      ELSE STROKE END, CAST(DISTANCE as unsigned) ASC");
  foreach($strokes as $stroke) {
    $stroke = $stroke->STROKE;

    $html .= '<div class="dark_blue">';
    $html .= ($strokeCount == 0) ? '<h3>Record Board</h3>' : '';
    $html .= '<h4>'.$stroke.'</h4>';
    $html .= '<div class="row records_header">
                <div class="col-md-3 person"><p>Women</p></div>
                <div class="col-md-1 time"><p>Time</p></div>
                <div class="col-md-1 year"><p>Year</p></div>
                <div class="col-md-2 event"><p>Event</p></div>
                <div class="col-md-3 person"><p>Men</p></div>
                <div class="col-md-1 time"><p>Time</p></div>
                <div class="col-md-1 year"><p>Year</p></div>
              </div>';
    $html .= '</div>';

    $distances = $wpdb->get_results("SELECT DISTANCE FROM RESULTS WHERE I_R = 'I' AND STROKE = \"$stroke\" GROUP BY DISTANCE ORDER BY CAST(DISTANCE as unsigned) ASC");
    foreach($distances as $distance) {
      if (!($distance == 400 && $stroke == "Free")) {
        $distance = $distance->DISTANCE;

        $records = array();
        foreach(array('F','M') as $sex) {
          $record = $wpdb->get_results("SELECT
                                        CONCAT(Athlete.First, ' ', Athlete.Last) AS NAME,
                                        Athlete.ATHLETE AS ATHLETE,
                                        SCORE,
                                        YEAR(Start) as YEAR
                                        FROM
                                        (SELECT MEET, ATHLETE, LPAD(TRIM(`SCORE`),8,'00:') AS SCORE, DISTANCE, STROKE, PLACE, I_R, EX FROM RESULTS
                                        UNION ALL
                                        SELECT MEET, ATHLETE, LPAD(TRIM(`SCORE`),8,'00:') AS SCORE, DISTANCE, STROKE, PLACE, I_R, \"\" AS EX FROM MANUAL_RESULTS) AS RESULTS
                                        JOIN (SELECT MEET, Start FROM MEET
                                            UNION ALL
                                            SELECT MEET, Start FROM MANUAL_MEET) AS MEET
                                        ON MEET.MEET = RESULTS.MEET
                                        JOIN (SELECT Athlete,
                                        Athlete.First,Pref,Last,Sex,Inactive,CusValue1,CusValue2,CusValue3,CusValue4,CusValue5,CusValue6,CusValue7,City, State FROM Athlete
                                        UNION ALL
                                        SELECT Athlete, First,Pref,Last,Sex,Inactive,CusValue1,CusValue2,CusValue3,CusValue4,CusValue5,CusValue6,CusValue7,City, State FROM MANUAL_Athlete) AS Athlete ON RESULTS.ATHLETE = Athlete.ATHLETE
                                        WHERE I_R = \"I\" AND DISTANCE = $distance AND STROKE = \"$stroke\" AND SCORE != '00:00:00' AND EX != 'X' AND Sex = \"$sex\" ORDER BY SCORE ASC LIMIT 1");
          array_push($records, $record);
        }
        $html .= '<div class="row records_row">';
        $html .= '<div class="col-md-3 col-xs-6 col-xs-push-2 person">'.get_modal_name($records[0][0]->ATHLETE, $records[0][0]->NAME).'</div>';
        $html .= '<div class="col-md-1 col-xs-4 col-xs-push-2 time"><p>'.$records[0][0]->SCORE.'</p></div>';
        $html .= '<div class="col-md-1 hidden-xs year"><p>'.$records[0][0]->YEAR.'</p></div>';
        $html .= '<div class="col-md-2 col-xs-12 event"><p>'.$distance.' '.$stroke.'</p></div>';
        $html .= '<div class="col-md-3 col-xs-6 col-xs-push-2 person"><p>'.get_modal_name($records[1][0]->ATHLETE, $records[1][0]->NAME).'</p></div>';
        $html .= '<div class="col-md-1 col-xs-4 col-xs-push-2 time"><p>'.$records[1][0]->SCORE.'</p></div>';
        $html .= '<div class="col-md-1 hidden-xs year"><p>'.$records[1][0]->YEAR.'</p></div>';
        $html .= '</div>';
        $records = null;
      }
    }
    $strokeCount++;
  }
  return $html;
}
?>
