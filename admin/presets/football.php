<?php
global $sportspress_sports;

$sportspress_sports['football'] = array(
	'name' => __( 'American Football', 'sportspress' ),
	'terms' => array(
		// Positions
		'sp_position' => array(
			array(
				'name' => 'Quarterback',
				'slug' => 'quarterback',
			),
			array(
				'name' => 'Running Back',
				'slug' => 'runningback',
			),
			array(
				'name' => 'Wide Receiver',
				'slug' => 'widereceiver',
			),
			array(
				'name' => 'Tight End',
				'slug' => 'tightend',
			),
			array(
				'name' => 'Defensive Lineman',
				'slug' => 'defensivelineman',
			),
			array(
				'name' => 'Linebacker',
				'slug' => 'linebacker',
			),
			array(
				'name' => 'Defensive Back',
				'slug' => 'defensiveback',
			),
			array(
				'name' => 'Kickoff Kicker',
				'slug' => 'kickoffkicker',
			),
			array(
				'name' => 'Kick Returner',
				'slug' => 'kickreturner',
			),
			array(
				'name' => 'Punter',
				'slug' => 'punter',
			),
			array(
				'name' => 'Punt Returner',
				'slug' => 'puntreturner',
			),
			array(
				'name' => 'Field Goal Kicker',
				'slug' => 'fieldgoalkicker',
			),
		),
	),
	'posts' => array(
		// Results
		'sp_result' => array(
			array(
				'post_title' => '1',
				'post_name'  => 'one',
			),
			array(
				'post_title' => '2',
				'post_name'  => 'two',
			),
			array(
				'post_title' => '3',
				'post_name'  => 'three',
			),
			array(
				'post_title' => '4',
				'post_name'  => 'four',
			),
			array(
				'post_title' => 'TD',
				'post_name'  => 'td',
			),
			array(
				'post_title' => 'T',
				'post_name'  => 't',
			),
		),
		// Outcomes
		'sp_outcome' => array(
			array(
				'post_title' => 'W',
				'post_name'  => 'w',
			),
			array(
				'post_title' => 'L',
				'post_name'  => 'l',
			),
			array(
				'post_title' => 'T',
				'post_name'  => 't',
			),
		),
		// Table Columns
		'sp_column' => array(
			array(
				'post_title' => 'W',
				'post_name' => 'w',
				'meta' => array(
					'sp_equation'     => '$w',
				),
			),
			array(
				'post_title' => 'L',
				'post_name' => 'l',
				'meta' => array(
					'sp_equation'     => '$l',
				),
			),
			array(
				'post_title' => 'T',
				'post_name' => 't',
				'meta' => array(
					'sp_equation'     => '$t',
				),
			),
			array(
				'post_title' => 'Pct',
				'post_name' => 'pct',
				'meta' => array(
					'sp_equation'     => '$w / $eventsplayed',
				),
			),
			array(
				'post_title' => 'PF',
				'post_name' => 'pf',
				'meta' => array(
					'sp_equation'     => '$tfor',
				),
			),
			array(
				'post_title' => 'PA',
				'post_name' => 'pa',
				'meta' => array(
					'sp_equation'     => '$tagainst',
				),
			),
			array(
				'post_title' => 'Net Pts',
				'post_name' => 'netpts',
				'meta' => array(
					'sp_equation'     => '$tfor - $tagainst',
				),
			),
			array(
				'post_title' => 'TD',
				'post_name' => 'td',
				'meta' => array(
					'sp_equation'     => '$td',
				),
			),
			array(
				'post_title' => 'Strk',
				'post_name' => 'strk',
				'meta' => array(
					'sp_equation'     => '$streak',
				),
			),
			array(
				'post_title' => 'Last 5',
				'post_name' => 'last5',
				'meta' => array(
					'sp_equation'     => '$last5',
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
				'post_title' => 'Comp',
				'post_name' => 'comp',
				'tax_input' => array(
					'sp_position' => array(
						'quarterback',
					),
				),
				'meta' => array(
					'sp_calculate' => 'sum',
				),
			),
			array(
				'post_title' => 'Att',
				'post_name' => 'att',
				'tax_input' => array(
					'sp_position' => array(
						'quarterback',
						'runningback',
					),
				),
				'meta' => array(
					'sp_calculate' => 'sum',
				),
			),
			array(
				'post_title' => 'Pct',
				'post_name' => 'pct',
				'tax_input' => array(
					'sp_position' => array(
						'quarterback',
						'kickoffkicker',
					),
				),
				'meta' => array(
					'sp_calculate' => 'average',
				),
			),
			array(
				'post_title' => 'Att/G',
				'post_name' => 'attg',
				'tax_input' => array(
					'sp_position' => array(
						'quarterback',
						'runningback',
					),
				),
				'meta' => array(
					'sp_calculate' => 'sum',
				),
			),
			array(
				'post_title' => 'Rec',
				'post_name' => 'rec',
				'tax_input' => array(
					'sp_position' => array(
						'widereceiver',
						'tightend',
					),
				),
				'meta' => array(
					'sp_calculate' => 'sum',
				),
			),
			array(
				'post_title' => 'Comb',
				'post_name' => 'comb',
				'tax_input' => array(
					'sp_position' => array(
						'defensivelineman',
						'linebacker',
						'defensiveback',
					),
				),
				'meta' => array(
					'sp_calculate' => 'sum',
				),
			),
			array(
				'post_title' => 'Total',
				'post_name' => 'total',
				'tax_input' => array(
					'sp_position' => array(
						'defensivelineman',
						'linebacker',
						'defensiveback',
					),
				),
				'meta' => array(
					'sp_calculate' => 'sum',
				),
			),
			array(
				'post_title' => 'Ast',
				'post_name' => 'ast',
				'tax_input' => array(
					'sp_position' => array(
						'defensivelineman',
						'linebacker',
						'defensiveback',
					),
				),
				'meta' => array(
					'sp_calculate' => 'sum',
				),
			),
			array(
				'post_title' => 'Sck',
				'post_name' => 'scktackles',
				'tax_input' => array(
					'sp_position' => array(
						'defensivelineman',
						'linebacker',
						'defensiveback',
					),
				),
				'meta' => array(
					'sp_calculate' => 'sum',
				),
			),
			array(
				'post_title' => 'SFTY',
				'post_name' => 'sfty',
				'tax_input' => array(
					'sp_position' => array(
						'defensivelineman',
						'linebacker',
						'defensiveback',
					),
				),
				'meta' => array(
					'sp_calculate' => 'sum',
				),
			),
			array(
				'post_title' => 'PDef',
				'post_name' => 'pdef',
				'tax_input' => array(
					'sp_position' => array(
						'defensivelineman',
						'linebacker',
						'defensiveback',
					),
				),
				'meta' => array(
					'sp_calculate' => 'sum',
				),
			),
			array(
				'post_title' => 'TDs',
				'post_name' => 'tds',
				'tax_input' => array(
					'sp_position' => array(
						'defensivelineman',
						'linebacker',
						'defensiveback',
					),
				),
				'meta' => array(
					'sp_calculate' => 'sum',
				),
			),
			array(
				'post_title' => 'KO',
				'post_name' => 'ko',
				'tax_input' => array(
					'sp_position' => array(
						'kickoffkicker',
					),
				),
				'meta' => array(
					'sp_calculate' => 'sum',
				),
			),
			array(
				'post_title' => 'Ret',
				'post_name' => 'ret',
				'tax_input' => array(
					'sp_position' => array(
						'kickreturner',
						'puntreturner',
					),
				),
				'meta' => array(
					'sp_calculate' => 'sum',
				),
			),
			array(
				'post_title' => 'Punts',
				'post_name' => 'punts',
				'tax_input' => array(
					'sp_position' => array(
						'punter',
					),
				),
				'meta' => array(
					'sp_calculate' => 'sum',
				),
			),
			array(
				'post_title' => 'Yds',
				'post_name' => 'yds',
				'tax_input' => array(
					'sp_position' => array(
						'quarterback',
						'runningback',
						'widereceiver',
						'tightend',
						'defensivelineman',
						'linebacker',
						'defensiveback',
						'kickoffkicker',
						'kickreturner',
						'punter',
						'puntreturner',
					),
				),
				'meta' => array(
					'sp_calculate' => 'sum',
				),
			),
			array(
				'post_title' => 'Net Yds',
				'post_name' => 'netyds',
				'tax_input' => array(
					'sp_position' => array(
						'punter',
					),
				),
				'meta' => array(
					'sp_calculate' => 'sum',
				),
			),
			array(
				'post_title' => 'Avg',
				'post_name' => 'avg',
				'tax_input' => array(
					'sp_position' => array(
						'quarterback',
						'runningback',
						'widereceiver',
						'tightend',
						'kickoffkicker',
						'kickreturner',
						'punter',
						'puntreturner',
					),
				),
				'meta' => array(
					'sp_calculate' => 'average',
				),
			),
			array(
				'post_title' => 'Net Avg',
				'post_name' => 'netavg',
				'tax_input' => array(
					'sp_position' => array(
						'punter',
					),
				),
				'meta' => array(
					'sp_calculate' => 'average',
				),
			),
			array(
				'post_title' => 'Blk',
				'post_name' => 'blk',
				'tax_input' => array(
					'sp_position' => array(
						'punter',
					),
				),
				'meta' => array(
					'sp_calculate' => 'sum',
				),
			),
			array(
				'post_title' => 'OOB',
				'post_name' => 'oob',
				'tax_input' => array(
					'sp_position' => array(
						'kickoffkicker',
						'punter',
					),
				),
				'meta' => array(
					'sp_calculate' => 'sum',
				),
			),
			array(
				'post_title' => 'Dn',
				'post_name' => 'dn',
				'tax_input' => array(
					'sp_position' => array(
						'punter',
					),
				),
				'meta' => array(
					'sp_calculate' => 'sum',
				),
			),
			array(
				'post_title' => 'IN 20',
				'post_name' => 'in20',
				'tax_input' => array(
					'sp_position' => array(
						'punter',
					),
				),
				'meta' => array(
					'sp_calculate' => 'sum',
				),
			),
			array(
				'post_title' => 'TB',
				'post_name' => 'tb',
				'tax_input' => array(
					'sp_position' => array(
						'punter',
					),
				),
				'meta' => array(
					'sp_calculate' => 'sum',
				),
			),
			array(
				'post_title' => 'FC',
				'post_name' => 'fc',
				'tax_input' => array(
					'sp_position' => array(
						'kickreturner',
						'punter',
						'puntreturner',
					),
				),
				'meta' => array(
					'sp_calculate' => 'sum',
				),
			),
			array(
				'post_title' => 'Ret',
				'post_name' => 'retpunt',
				'tax_input' => array(
					'sp_position' => array(
						'kickoffkicker',
						'kickreturner',
						'punter',
						'puntreturner',
					),
				),
				'meta' => array(
					'sp_calculate' => 'sum',
				),
			),
			array(
				'post_title' => 'RetY',
				'post_name' => 'rety',
				'tax_input' => array(
					'sp_position' => array(
						'kickreturner',
						'punter',
						'puntreturner',
					),
				),
				'meta' => array(
					'sp_calculate' => 'sum',
				),
			),
			array(
				'post_title' => 'Yds/G',
				'post_name' => 'ydsg',
				'tax_input' => array(
					'sp_position' => array(
						'quarterback',
						'runningback',
						'widereceiver',
						'tightend',
					),
				),
				'meta' => array(
					'sp_calculate' => 'sum',
				),
			),
			array(
				'post_title' => 'TD',
				'post_name' => 'TD',
				'tax_input' => array(
					'sp_position' => array(
						'quarterback',
						'runningback',
						'widereceiver',
						'tightend',
						'defensivelineman',
						'linebacker',
						'defensiveback',
						'kickreturner',
						'puntreturner',
					),
				),
				'meta' => array(
					'sp_calculate' => 'sum',
				),
			),
			array(
				'post_title' => 'Int',
				'post_name' => 'int',
				'tax_input' => array(
					'sp_position' => array(
						'quarterback',
						'defensivelineman',
						'linebacker',
						'defensiveback',
					),
				),
				'meta' => array(
					'sp_calculate' => 'sum',
				),
			),
			array(
				'post_title' => '1st',
				'post_name' => 'first',
				'tax_input' => array(
					'sp_position' => array(
						'quarterback',
						'runningback',
						'widereceiver',
						'tightend',
					),
				),
				'meta' => array(
					'sp_calculate' => 'sum',
				),
			),
			array(
				'post_title' => '1st%',
				'post_name' => 'firstpct',
				'tax_input' => array(
					'sp_position' => array(
						'quarterback',
						'runningback',
						'widereceiver',
						'tightend',
					),
				),
				'meta' => array(
					'sp_calculate' => 'average',
				),
			),
			array(
				'post_title' => 'Lng',
				'post_name' => 'lng',
				'tax_input' => array(
					'sp_position' => array(
						'quarterback',
						'runningback',
						'widereceiver',
						'tightend',
						'defensivelineman',
						'linebacker',
						'defensiveback',
						'kickreturner',
						'punter',
						'puntreturner',
					),
				),
				'meta' => array(
					'sp_calculate' => 'sum',
				),
			),
			array(
				'post_title' => '20+',
				'post_name' => 'twentyplus',
				'tax_input' => array(
					'sp_position' => array(
						'quarterback',
						'runningback',
						'widereceiver',
						'tightend',
						'kickreturner',
						'puntreturner',
					),
				),
				'meta' => array(
					'sp_calculate' => 'sum',
				),
			),
			array(
				'post_title' => '40+',
				'post_name' => 'fourtyplus',
				'tax_input' => array(
					'sp_position' => array(
						'quarterback',
						'runningback',
						'widereceiver',
						'tightend',
						'kickreturner',
						'puntreturner',
					),
				),
				'meta' => array(
					'sp_calculate' => 'sum',
				),
			),
			array(
				'post_title' => 'Sck',
				'post_name' => 'sck',
				'tax_input' => array(
					'sp_position' => array(
						'quarterback',
					),
				),
				'meta' => array(
					'sp_calculate' => 'sum',
				),
			),
			array(
				'post_title' => 'Rate',
				'post_name' => 'rate',
				'tax_input' => array(
					'sp_position' => array(
						'quarterback',
					),
				),
				'meta' => array(
					'sp_calculate' => 'sum',
				),
			),
			array(
				'post_title' => 'FUM',
				'post_name' => 'fum',
				'tax_input' => array(
					'sp_position' => array(
						'runningback',
						'widereceiver',
						'tightend',
						'kickreturner',
						'puntreturner',
					),
				),
				'meta' => array(
					'sp_calculate' => 'sum',
				),
			),
			array(
				'post_title' => 'FF',
				'post_name' => 'ff',
				'tax_input' => array(
					'sp_position' => array(
						'defensivelineman',
						'linebacker',
						'defensiveback',
					),
				),
				'meta' => array(
					'sp_calculate' => 'sum',
				),
			),
			array(
				'post_title' => 'Rec',
				'post_name' => 'recfum',
				'tax_input' => array(
					'sp_position' => array(
						'defensivelineman',
						'linebacker',
						'defensiveback',
					),
				),
				'meta' => array(
					'sp_calculate' => 'sum',
				),
			),
			array(
				'post_title' => 'TD',
				'post_name' => 'tdfum',
				'tax_input' => array(
					'sp_position' => array(
						'kickoffkicker',
					),
				),
				'meta' => array(
					'sp_calculate' => 'sum',
				),
			),
			array(
				'post_title' => 'Avg',
				'post_name' => 'avgpunt',
				'tax_input' => array(
					'sp_position' => array(
						'kickoffkicker',
						'kickreturner',
						'puntreturner',
					),
				),
				'meta' => array(
					'sp_calculate' => 'average',
				),
			),
			array(
				'post_title' => 'Lng',
				'post_name' => 'lngpunt',
				'tax_input' => array(
					'sp_position' => array(
						'kickreturner',
						'puntreturner',
					),
				),
				'meta' => array(
					'sp_calculate' => 'sum',
				),
			),
			array(
				'post_title' => 'TD',
				'post_name' => 'tdpunt',
				'tax_input' => array(
					'sp_position' => array(
						'kickoffkicker',
						'kickreturner',
						'punter',
						'puntreturner',
					),
				),
				'meta' => array(
					'sp_calculate' => 'sum',
				),
			),
			array(
				'post_title' => '20+',
				'post_name' => 'twentypluspunt',
				'tax_input' => array(
					'sp_position' => array(
						'kickreturner',
						'puntreturner',
					),
				),
				'meta' => array(
					'sp_calculate' => 'sum',
				),
			),
			array(
				'post_title' => '40+',
				'post_name' => 'fourtypluspunt',
				'tax_input' => array(
					'sp_position' => array(
						'kickreturner',
						'puntreturner',
					),
				),
				'meta' => array(
					'sp_calculate' => 'sum',
				),
			),
			array(
				'post_title' => 'FC',
				'post_name' => 'fcpunt',
				'tax_input' => array(
					'sp_position' => array(
						'kickreturner',
						'puntreturner',
					),
				),
				'meta' => array(
					'sp_calculate' => 'sum',
				),
			),
			array(
				'post_title' => 'FUM',
				'post_name' => 'fumpunt',
				'tax_input' => array(
					'sp_position' => array(
						'kickreturner',
						'puntreturner',
					),
				),
				'meta' => array(
					'sp_calculate' => 'sum',
				),
			),
			array(
				'post_title' => 'OSK',
				'post_name' => 'osk',
				'tax_input' => array(
					'sp_position' => array(
						'kickoffkicker',
					),
				),
				'meta' => array(
					'sp_calculate' => 'sum',
				),
			),
			array(
				'post_title' => 'OSKR',
				'post_name' => 'oskr',
				'tax_input' => array(
					'sp_position' => array(
						'kickoffkicker',
					),
				),
				'meta' => array(
					'sp_calculate' => 'sum',
				),
			),
		),
	),
);
