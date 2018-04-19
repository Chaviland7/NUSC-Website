<?php
// Exit if accessed directly
if( !defined( 'ABSPATH' ) ) {
	exit;
}
?>
<?php get_header(); ?>
<div class="blog-post content-container">
	<div class="single-post-container main-content">
  <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
		<h1 class="title"><?=get_the_title();?></h1>

		<?php
		$html = '<div class="row meet_meta_container">';
		$num_teams = 0;
		for ($i = 1; $i < 5; $i++) {
			if (get_post_meta($post->ID,'team_'.$i.'_name')&& get_post_meta($post->ID,'team_'.$i.'_score')) {
				$num_teams += 1;
			}
		}
		$col_size = 12 / $num_teams;
		for ($i = 1; $i <= $num_teams; $i++) {
			if (get_post_meta($post->ID,'team_'.$i.'_name')&& get_post_meta($post->ID,'team_'.$i.'_score')) {
				$html .= '<div class="col-lg-'.$col_size.' col-md-6">';
				$html .= '<p>'.get_post_meta($post->ID,'team_'.$i.'_name')[0].' Score: '.get_post_meta($post->ID,'team_'.$i.'_score')[0]."</p>";
				$html .= '</div>';
			}
		}
		if (get_post_meta($post->ID,'date')) {
			$meet_date = new DateTime(get_post_meta($post->ID,'date')[0]);
			$date_string = $meet_date->format('M d, Y');
			$html .= '<div class="col-md-6">';
			$html .= '<p>Meet Date: '.$date_string."<p>";
			$html .= '</div>';
		}
		$html .= '<div class="col-md-6">';
		$html .= (get_post_meta($post->ID,'location')) ? '<p>Meet Location: '.get_post_meta($post->ID,'location')[0]."</p>" : '';
		$html .= '</div>';
		$html .= '<div class="col-md-12 results">';
		$html .= (get_post_meta($post->ID,'results_url')) ? '<a target=_blank" href="'.get_post_meta($post->ID,'results_url')[0].'">Full Meet Results</a>' : '';
		$html .= '</div>';
		$html .= '</div>';
		echo $html;
		?>

		<hr />
		<p><?=do_shortcode(get_the_content());?></p>
		<p class="date-author">Posted on: <?=the_date();?> by <?php the_author();?></p>
		<?php endwhile; else: ?>
		<p>
			<?php _e('Sorry, no posts matched your criteria.'); ?>
		</p>
	<?php endif; ?>
	</div>
</div>
<?php get_footer(); ?>
