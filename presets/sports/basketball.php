<?php
global $sportspress_sports;

$sportspress_sports['basketball'] = array(
	'name' => __( 'Basketball', 'sportspress' ),
	'terms' => array(
		// Positions
		'sp_position' => array(
			array(
				'name' => 'Point Guard',
				'slug' => 'pointguard',
			),
			array(
				'name' => 'Shooting Guard',
				'slug' => 'shootingguard',
			),
			array(
				'name' => 'Small Forward',
				'slug' => 'smallforward',
			),
			array(
				'name' => 'Power Forward',
				'slug' => 'powerforward',
			),
			array(
				'name' => 'Center',
				'slug' => 'center',
			),
		),
	),
	'posts' => array(
		// Results
		'sp_result' => array(
			array(
				'post_title' => '1',
				'post_name' => 'one',
			),
			array(
				'post_title' => '2',
				'post_name' => 'two',
			),
			array(
				'post_title' => '3',
				'post_name' => 'three',
			),
			array(
				'post_title' => '4',
				'post_name' => 'four',
			),
			array(
				'post_title' => 'OT',
				'post_name' => 'ot',
			),
			array(
				'post_title' => 'T',
				'post_name' => 't',
			),
		),
		// Outcomes
		'sp_outcome' => array(
			array(
				'post_title' => 'W',
				'post_name' => 'w',
			),
			array(
				'post_title' => 'L',
				'post_name' => 'l',
			),
		),
		// Table Columns
		'sp_column' => array(
			array(
				'post_title' => 'W',
				'post_name' => 'w',
				'meta' => array(
					'sp_equation' => '$w',
				),
			),
			array(
				'post_title' => 'L',
				'post_name' => 'l',
				'meta' => array(
					'sp_equation' => '$l',
				),
			),
			array(
				'post_title' => 'Pct',
				'post_name' => 'pct',
				'meta' => array(
					'sp_equation' => '$w / $eventsplayed * 100',
				),
			),
			array(
				'post_title' => 'GB',
				'post_name' => 'gb',
				'meta' => array(
					'sp_equation' => '( $wmax + $l - $w - $lmax ) / 2',
				),
			),
			array(
				'post_title' => 'L10',
				'post_name' => 'lten',
				'meta' => array(
					'sp_equation' => '$last10',
				),
			),
			array(
				'post_title' => 'Streak',
				'post_name' => 'streak',
				'meta' => array(
					'sp_equation' => '$streak',
				),
			),
			array(
				'post_title' => 'PF',
				'post_name' => 'pf',
				'meta' => array(
					'sp_equation' => '$tfor',
				),
			),
			array(
				'post_title' => 'PA',
				'post_name' => 'pa',
				'meta' => array(
					'sp_equation' => '$tagainst',
				),
			),
			array(
				'post_title' => 'DIFF',
				'post_name' => 'diff',
				'meta' => array(
					'sp_equation' => '$tfor - $tagainst',
				),
			),
		),
		// Player Metrics
		'sp_metric' => array(
			array(
				'post_title' => 'Height',
				'post_name' => 'height',
			),
			array(
				'post_title' => 'Weight',
				'post_name' => 'weight',
			),
			array(
				'post_title' => 'Experience',
				'post_name' => 'experience',
			),
		),
		// Player Statistics
		'sp_statistic' => array(
			array(
				'post_title' => 'MIN',
				'post_name' => 'min',
				'tax_input' => array(
					'sp_position' => array(
						'slug' => 'pointguard',
						'slug' => 'shootingguard',
						'slug' => 'smallforward',
						'slug' => 'powerforward',
						'slug' => 'center',
					),
				),
				'meta' => array(
					'sp_calculate' => 'average',
				),
			),
			array(
				'post_title' => 'FGM',
				'post_name' => 'fgm',
				'tax_input' => array(
					'sp_position' => array(
						'slug' => 'pointguard',
						'slug' => 'shootingguard',
						'slug' => 'smallforward',
						'slug' => 'powerforward',
						'slug' => 'center',
					),
				),
				'meta' => array(
					'sp_calculate' => 'average',
				),
			),
			array(
				'post_title' => 'FGA',
				'post_name' => 'fga',
				'tax_input' => array(
					'sp_position' => array(
						'slug' => 'pointguard',
						'slug' => 'shootingguard',
						'slug' => 'smallforward',
						'slug' => 'powerforward',
						'slug' => 'center',
					),
				),
				'meta' => array(
					'sp_calculate' => 'average',
				),
			),
			array(
				'post_title' => '3PM',
				'post_name' => '3pm',
				'tax_input' => array(
					'sp_position' => array(
						'slug' => 'pointguard',
						'slug' => 'shootingguard',
						'slug' => 'smallforward',
						'slug' => 'powerforward',
						'slug' => 'center',
					),
				),
				'meta' => array(
					'sp_calculate' => 'average',
				),
			),
			array(
				'post_title' => '3PA',
				'post_name' => '3pa',
				'tax_input' => array(
					'sp_position' => array(
						'slug' => 'pointguard',
						'slug' => 'shootingguard',
						'slug' => 'smallforward',
						'slug' => 'powerforward',
						'slug' => 'center',
					),
				),
				'meta' => array(
					'sp_calculate' => 'average',
				),
			),
			array(
				'post_title' => 'FTM',
				'post_name' => 'ftm',
				'tax_input' => array(
					'sp_position' => array(
						'slug' => 'pointguard',
						'slug' => 'shootingguard',
						'slug' => 'smallforward',
						'slug' => 'powerforward',
						'slug' => 'center',
					),
				),
				'meta' => array(
					'sp_calculate' => 'average',
				),
			),
			array(
				'post_title' => 'FTA',
				'post_name' => 'fta',
				'tax_input' => array(
					'sp_position' => array(
						'slug' => 'pointguard',
						'slug' => 'shootingguard',
						'slug' => 'smallforward',
						'slug' => 'powerforward',
						'slug' => 'center',
					),
				),
				'meta' => array(
					'sp_calculate' => 'average',
				),
			),
			array(
				'post_title' => 'OFF',
				'post_name' => 'off',
				'tax_input' => array(
					'sp_position' => array(
						'slug' => 'pointguard',
						'slug' => 'shootingguard',
						'slug' => 'smallforward',
						'slug' => 'powerforward',
						'slug' => 'center',
					),
				),
				'meta' => array(
					'sp_calculate' => 'average',
				),
			),
			array(
				'post_title' => 'DEF',
				'post_name' => 'def',
				'tax_input' => array(
					'sp_position' => array(
						'slug' => 'pointguard',
						'slug' => 'shootingguard',
						'slug' => 'smallforward',
						'slug' => 'powerforward',
						'slug' => 'center',
					),
				),
				'meta' => array(
					'sp_calculate' => 'average',
				),
			),
			array(
				'post_title' => 'REB',
				'post_name' => 'reb',
				'tax_input' => array(
					'sp_position' => array(
						'slug' => 'pointguard',
						'slug' => 'shootingguard',
						'slug' => 'smallforward',
						'slug' => 'powerforward',
						'slug' => 'center',
					),
				),
				'meta' => array(
					'sp_calculate' => 'average',
				),
			),
			array(
				'post_title' => 'AST',
				'post_name' => 'ast',
				'tax_input' => array(
					'sp_position' => array(
						'slug' => 'pointguard',
						'slug' => 'shootingguard',
						'slug' => 'smallforward',
						'slug' => 'powerforward',
						'slug' => 'center',
					),
				),
				'meta' => array(
					'sp_calculate' => 'average',
				),
			),
			array(
				'post_title' => 'STL',
				'post_name' => 'stl',
				'tax_input' => array(
					'sp_position' => array(
						'slug' => 'pointguard',
						'slug' => 'shootingguard',
						'slug' => 'smallforward',
						'slug' => 'powerforward',
						'slug' => 'center',
					),
				),
				'meta' => array(
					'sp_calculate' => 'average',
				),
			),
			array(
				'post_title' => 'BLK',
				'post_name' => 'blk',
				'tax_input' => array(
					'sp_position' => array(
						'slug' => 'pointguard',
						'slug' => 'shootingguard',
						'slug' => 'smallforward',
						'slug' => 'powerforward',
						'slug' => 'center',
					),
				),
				'meta' => array(
					'sp_calculate' => 'average',
				),
			),
			array(
				'post_title' => 'TO',
				'post_name' => 'to',
				'tax_input' => array(
					'sp_position' => array(
						'slug' => 'pointguard',
						'slug' => 'shootingguard',
						'slug' => 'smallforward',
						'slug' => 'powerforward',
						'slug' => 'center',
					),
				),
				'meta' => array(
					'sp_calculate' => 'average',
				),
			),
			array(
				'post_title' => 'PF',
				'post_name' => 'pf',
				'tax_input' => array(
					'sp_position' => array(
						'slug' => 'pointguard',
						'slug' => 'shootingguard',
						'slug' => 'smallforward',
						'slug' => 'powerforward',
						'slug' => 'center',
					),
				),
				'meta' => array(
					'sp_calculate' => 'average',
				),
			),
			array(
				'post_title' => 'PTS',
				'post_name' => 'pts',
				'tax_input' => array(
					'sp_position' => array(
						'slug' => 'pointguard',
						'slug' => 'shootingguard',
						'slug' => 'smallforward',
						'slug' => 'powerforward',
						'slug' => 'center',
					),
				),
				'meta' => array(
					'sp_calculate' => 'average',
				),
			),
		),
	),
);
