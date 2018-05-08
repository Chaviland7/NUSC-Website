<?php
global $wpdb;
$meet = $_POST['meet'];
$athlete = $_POST['athlete'];
$i_r = $_POST['i_r'];
$score = $_POST['score'];
$distance = $_POST['distance'];
$stroke = $_POST['stroke'];
$place = $_POST['place'];

$wpdb->insert('MANUAL_RESULTS', array('Meet' => $meet, 'Athlete' => $athlete, 'I_R' => $i_r, 'Score' => $score, 'Distance' => $distance, 'Stroke' => $stroke, 'Place' => $place));

get_header();

get_footer();
?>
