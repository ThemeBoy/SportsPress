<?php
global $sportspress_sports;

$sportspress_sports['racing'] = array(
	'name' => __( 'Racing', 'sportspress' ),
	'posts' => array(
		// Table Columns
		'sp_column' => array(
		),
		// Statistics
		'sp_statistic' => array(
			array(
				'post_title' => 'Pts',
				'post_name' => 'pts',
				'meta'       => array(
					'sp_equation'     => '$ptsfor',
				),
			),
			array(
				'post_title' => 'B',
				'post_name' => 'b',
				'meta'       => array(
					'sp_equation'     => '$ptsmax - $ptsfor',
				),
			),
			array(
				'post_title' => 'S',
				'post_name' => 's',
				'meta'       => array(
					'sp_equation'     => '$eventsplayed',
				),
			),
			array(
				'post_title' => 'W',
				'post_name' => 'w',
				'meta'       => array(
					'sp_equation'     => '$w',
					'sp_priority'     => '1',
					'sp_order'        => 'DESC',
				),
			),
			array(
				'post_title' => 'DNF',
				'post_name' => 'dnf',
				'meta'       => array(
					'sp_equation'     => '$dnf',
				),
			),
		),
		// Results
		'sp_result' => array(
		),
		// Outcomes
		'sp_outcome' => array(
		),
	),
);
