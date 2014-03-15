<?php
global $sportspress_sports;

$sportspress_sports['baseball'] = array(
	'name' => __( 'Baseball', 'sportspress' ),
	'posts' => array(
		// Columns
		'sp_column' => array(
			array(
				'post_title' => 'W',
				'post_name' => 'w',
				'meta'       => array(
					'sp_equation'     => '$w',
					'sp_format'       => 'integer',
					'sp_precision'    => 0,
					'sp_priority'     => 1,
					'sp_order'        => 'DESC',
				),
			),
			array(
				'post_title' => 'L',
				'post_name' => 'l',
				'meta'       => array(
					'sp_equation'     => '$l',
					'sp_format'       => 'integer',
					'sp_precision'    => 0,
					'sp_priority'     => 2,
					'sp_order'        => 'ASC',
				),
			),
			array(
				'post_title' => 'Pct',
				'post_name' => 'pct',
				'meta'       => array(
					'sp_equation'     => '$w / $eventsplayed',
					'sp_format'       => 'decimal',
					'sp_precision'    => 2,
				),
			),
			array(
				'post_title' => 'RS',
				'post_name' => 'rs',
				'meta'       => array(
					'sp_equation'     => '$rfor',
					'sp_format'       => 'integer',
					'sp_precision'    => 0,
					'sp_priority'     => 3,
					'sp_order'        => 'DESC',
				),
			),
			array(
				'post_title' => 'RA',
				'post_name' => 'ra',
				'meta'       => array(
					'sp_equation'     => '$ragainst',
					'sp_format'       => 'integer',
					'sp_precision'    => 0,
				),
			),
			array(
				'post_title' => 'Strk',
				'post_name' => 'strk',
				'meta'       => array(
					'sp_equation'     => '$streak',
					'sp_format'       => 'integer',
					'sp_precision'    => 0,
				),
			),
		),
		// Statistics
		'sp_statistic' => array(
		),
		// Results
		'sp_result' => array(
			array(
				'post_title' => '1',
				'post_name' => 'first',
				'meta'       => array(
				),
			),
			array(
				'post_title' => '2',
				'post_name' => 'second',
				'meta'       => array(
				),
			),
			array(
				'post_title' => '3',
				'post_name' => 'third',
				'meta'       => array(
				),
			),
			array(
				'post_title' => '4',
				'post_name' => 'fourth',
				'meta'       => array(
				),
			),
			array(
				'post_title' => '5',
				'post_name' => 'fifth',
				'meta'       => array(
				),
			),
			array(
				'post_title' => '6',
				'post_name' => 'sixth',
				'meta'       => array(
				),
			),
			array(
				'post_title' => '7',
				'post_name' => 'seventh',
				'meta'       => array(
				),
			),
			array(
				'post_title' => '8',
				'post_name' => 'eighth',
				'meta'       => array(
				),
			),
			array(
				'post_title' => '9',
				'post_name' => 'ninth',
				'meta'       => array(
				),
			),
			array(
				'post_title' => '&nbsp;',
				'post_name' => 'extra',
				'meta'       => array(
				),
			),
			array(
				'post_title' => 'R',
				'post_name' => 'r',
				'meta'       => array(
				),
			),
			array(
				'post_title' => 'H',
				'post_name' => 'h',
				'meta'       => array(
				),
			),
			array(
				'post_title' => 'E',
				'post_name' => 'e',
				'meta'       => array(
				),
			),
		),
		// Outcomes
		'sp_outcome' => array(
			array(
				'post_title' => 'Win',
				'post_name' => 'w',
				'meta'       => array(
				),
			),
			array(
				'post_title' => 'Loss',
				'post_name' => 'l',
				'meta'       => array(
				),
			),
		),
	),
);
