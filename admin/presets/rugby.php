<?php
global $sportspress_sports;

$sportspress_sports['rugby'] = array(
	'name' => __( 'Rugby', 'sportspress' ),
	'posts' => array(
		// Table Columns
		'sp_column' => array(
			array(
				'post_title' => 'P',
				'post_name'  => 'p',
				'meta'       => array(
					'sp_equation'     => '$eventsplayed',
				),
			),
			array(
				'post_title' => 'W',
				'post_name'  => 'w',
				'meta'       => array(
					'sp_equation'     => '$w',
				),
			),
			array(
				'post_title' => 'D',
				'post_name'  => 'd',
				'meta'       => array(
					'sp_equation'     => '$d',
				),
			),
			array(
				'post_title' => 'L',
				'post_name'  => 'l',
				'meta'       => array(
					'sp_equation'     => '$l',
				),
			),
			array(
				'post_title' => 'B',
				'post_name'  => 'b',
				'meta'       => array(
					'sp_equation'     => '$b',
				),
			),
			array(
				'post_title' => 'F',
				'post_name'  => 'f',
				'meta'       => array(
					'sp_equation'     => '$ptsfor',
				),
			),
			array(
				'post_title' => 'A',
				'post_name'  => 'a',
				'meta'       => array(
					'sp_equation'     => '$ptsagainst',
				),
			),
			array(
				'post_title' => '+/-',
				'post_name'  => 'pd',
				'meta'       => array(
					'sp_equation'     => '$ptsfor - $ptsagainst',
				),
			),
			array(
				'post_title' => 'Pts',
				'post_name'  => 'pts',
				'meta'       => array(
					'sp_equation'     => '( $w + $b ) * 2 + $d',
					'sp_priority'     => '1',
					'sp_order'        => 'DESC',
				),
			),
		),
		// Statistics
		'sp_statistic' => array(
		),
		// Results
		'sp_result' => array(
		),
		// Outcomes
		'sp_outcome' => array(
		),
	),
);