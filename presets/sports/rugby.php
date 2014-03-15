<?php
global $sportspress_sports;

$sportspress_sports['rugby'] = array(
	'name' => __( 'Rugby', 'sportspress' ),
	'posts' => array(
		// Results
		'sp_result' => array(
			array(
				'post_title' => 'Points',
				'post_name'  => 'points',
			),
			array(
				'post_title' => 'Bonus',
				'post_name'  => 'bonus',
			),
		),
		// Outcomes
		'sp_outcome' => array(
			array(
				'post_title' => 'Win',
				'post_name'  => 'w',
			),
			array(
				'post_title' => 'Draw',
				'post_name'  => 'd',
			),
			array(
				'post_title' => 'Loss',
				'post_name'  => 'l',
			),
		),
		// Table Columns
		'sp_column' => array(
			array(
				'post_title' => 'P',
				'post_name'  => 'p',
				'meta'       => array(
					'sp_equation'     => '$eventsplayed',
					'sp_precision'    => 0,
				),
			),
			array(
				'post_title' => 'W',
				'post_name'  => 'w',
				'meta'       => array(
					'sp_equation'     => '$w',
					'sp_precision'    => 0,
				),
			),
			array(
				'post_title' => 'D',
				'post_name'  => 'd',
				'meta'       => array(
					'sp_equation'     => '$d',
					'sp_precision'    => 0,
				),
			),
			array(
				'post_title' => 'L',
				'post_name'  => 'l',
				'meta'       => array(
					'sp_equation'     => '$l',
					'sp_precision'    => 0,
				),
			),
			array(
				'post_title' => 'B',
				'post_name'  => 'b',
				'meta'       => array(
					'sp_equation'     => '$bonus',
					'sp_precision'    => 0,
				),
			),
			array(
				'post_title' => 'F',
				'post_name'  => 'f',
				'meta'       => array(
					'sp_equation'     => '$pointsfor',
					'sp_precision'    => 0,
				),
			),
			array(
				'post_title' => 'A',
				'post_name'  => 'a',
				'meta'       => array(
					'sp_equation'     => '$pointsagainst',
					'sp_precision'    => 0,
				),
			),
			array(
				'post_title' => '+/-',
				'post_name'  => 'pd',
				'meta'       => array(
					'sp_equation'     => '$pointsfor - $pointsagainst',
					'sp_precision'    => 0,
				),
			),
			array(
				'post_title' => 'Pts',
				'post_name'  => 'pts',
				'meta'       => array(
					'sp_equation'     => '( $w + $bonus ) * 2 + $d',
					'sp_precision'    => 0,
					'sp_priority'     => '1',
					'sp_order'        => 'DESC',
				),
			),
		),
		// Player Metrics
		'sp_metric' => array(
			array(
				'post_title' => 'Height',
				'post_name'  => 'height',
			),
			array(
				'post_title' => 'Weight',
				'post_name'  => 'weight',
			),
		),
		// Player Statistics
		'sp_statistic' => array(
			array(
				'post_title' => 'Points',
				'post_name'  => 'points',
				'meta'       => array(
					'sp_calculate'     => 'total',
				),
			),
			array(
				'post_title' => 'Tries',
				'post_name'  => 'tries',
				'meta'       => array(
					'sp_calculate'     => 'total',
				),
			),
			array(
				'post_title' => 'Conversions',
				'post_name'  => 'conversions',
				'meta'       => array(
					'sp_calculate'     => 'total',
				),
			),
			array(
				'post_title' => 'Penalty Goals',
				'post_name'  => 'penaltygoals',
				'meta'       => array(
					'sp_calculate'     => 'total',
				),
			),
			array(
				'post_title' => 'Drop Goals',
				'post_name'  => 'dropgoals',
				'meta'       => array(
					'sp_calculate'     => 'total',
				),
			),
		),
	),
);
