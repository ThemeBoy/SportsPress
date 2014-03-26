<?php
/**
 * SportsPress sports
 *
 * The SportsPress sports class stores preset sport data.
 *
 * @class 		SP_Sports
 * @version		0.7
 * @package		SportsPress/Classes
 * @category	Class
 * @author 		ThemeBoy
 */
class SP_Sports {

	/** @var array Array of sports */
	private $data;

	/**
	 * Constructor for the sports class - defines all preset sports.
	 *
	 * @access public
	 * @return void
	 */
	public function __construct() {
		$this->data = apply_filters( 'sportspress_sports', array(
			'baseball' => array(
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
			),
			'basketball' => array(
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
			),
			'cricket' => array(
				'name' => __( 'Cricket', 'sportspress' ),
				'posts' => array(
					// Table Columns
					'sp_column' => array(
						array(
							'post_title' => 'M',
							'post_name'  => 'm',
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
							'post_title' => 'L',
							'post_name'  => 'l',
							'meta'       => array(
								'sp_equation'     => '$l',
							),
						),
						array(
							'post_title' => 'T',
							'post_name'  => 't',
							'meta'       => array(
								'sp_equation'     => '$t',
							),
						),
						array(
							'post_title' => 'N/R',
							'post_name'  => 'nr',
							'meta'       => array(
								'sp_equation'     => '$nr',
							),
						),
						array(
							'post_title' => 'Pts',
							'post_name'  => 'pts',
							'meta'       => array(
								'sp_equation'     => '$w * 2 + $nr',
								'sp_priority'     => '1',
								'sp_order'        => 'DESC',
							),
						),
						array(
							'post_title' => 'RR',
							'post_name'  => 'rr',
							'meta'       => array(
								'sp_equation'     => '( $rfor / $oagainst ) - ( $ragainst / $ofor )',
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
			),
			'football' => array(
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
							'post_title' => 'Win',
							'post_name'  => 'w',
						),
						array(
							'post_title' => 'Loss',
							'post_name'  => 'l',
						),
						array(
							'post_title' => 'Tie',
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
							'meta' => array(
								'sp_calculate' => 'total',
							),
							'tax_input' => array(
								'sp_position' => array(
									'quarterback',
								),
							),
						),
						array(
							'post_title' => 'Att',
							'post_name' => 'att',
							'meta' => array(
								'sp_calculate' => 'total',
							),
							'tax_input' => array(
								'sp_position' => array(
									'quarterback',
									'runningback',
								),
							),
						),
						array(
							'post_title' => 'Pct',
							'post_name' => 'pct',
							'meta' => array(
								'sp_calculate' => 'average',
							),
							'tax_input' => array(
								'sp_position' => array(
									'quarterback',
									'kickoffkicker',
								),
							),
						),
						array(
							'post_title' => 'Att/G',
							'post_name' => 'attg',
							'meta' => array(
								'sp_calculate' => 'total',
							),
							'tax_input' => array(
								'sp_position' => array(
									'quarterback',
									'runningback',
								),
							),
						),
						array(
							'post_title' => 'Rec',
							'post_name' => 'rec',
							'meta' => array(
								'sp_calculate' => 'total',
							),
							'tax_input' => array(
								'sp_position' => array(
									'widereceiver',
									'tightend',
								),
							),
						),
						array(
							'post_title' => 'Comb',
							'post_name' => 'comb',
							'meta' => array(
								'sp_calculate' => 'total',
							),
							'tax_input' => array(
								'sp_position' => array(
									'defensivelineman',
									'linebacker',
									'defensiveback',
								),
							),
						),
						array(
							'post_title' => 'Total',
							'post_name' => 'total',
							'meta' => array(
								'sp_calculate' => 'total',
							),
							'tax_input' => array(
								'sp_position' => array(
									'defensivelineman',
									'linebacker',
									'defensiveback',
								),
							),
						),
						array(
							'post_title' => 'Ast',
							'post_name' => 'ast',
							'meta' => array(
								'sp_calculate' => 'total',
							),
							'tax_input' => array(
								'sp_position' => array(
									'defensivelineman',
									'linebacker',
									'defensiveback',
								),
							),
						),
						array(
							'post_title' => 'Sck',
							'post_name' => 'scktackles',
							'meta' => array(
								'sp_calculate' => 'total',
							),
							'tax_input' => array(
								'sp_position' => array(
									'defensivelineman',
									'linebacker',
									'defensiveback',
								),
							),
						),
						array(
							'post_title' => 'SFTY',
							'post_name' => 'sfty',
							'meta' => array(
								'sp_calculate' => 'total',
							),
							'tax_input' => array(
								'sp_position' => array(
									'defensivelineman',
									'linebacker',
									'defensiveback',
								),
							),
						),
						array(
							'post_title' => 'PDef',
							'post_name' => 'pdef',
							'meta' => array(
								'sp_calculate' => 'total',
							),
							'tax_input' => array(
								'sp_position' => array(
									'defensivelineman',
									'linebacker',
									'defensiveback',
								),
							),
						),
						array(
							'post_title' => 'TDs',
							'post_name' => 'tds',
							'meta' => array(
								'sp_calculate' => 'total',
							),
							'tax_input' => array(
								'sp_position' => array(
									'defensivelineman',
									'linebacker',
									'defensiveback',
								),
							),
						),
						array(
							'post_title' => 'KO',
							'post_name' => 'ko',
							'meta' => array(
								'sp_calculate' => 'total',
							),
							'tax_input' => array(
								'sp_position' => array(
									'kickoffkicker',
								),
							),
						),
						array(
							'post_title' => 'Ret',
							'post_name' => 'ret',
							'meta' => array(
								'sp_calculate' => 'total',
							),
							'tax_input' => array(
								'sp_position' => array(
									'kickreturner',
									'puntreturner',
								),
							),
						),
						array(
							'post_title' => 'Punts',
							'post_name' => 'punts',
							'meta' => array(
								'sp_calculate' => 'total',
							),
							'tax_input' => array(
								'sp_position' => array(
									'punter',
								),
							),
						),
						array(
							'post_title' => 'Yds',
							'post_name' => 'yds',
							'meta' => array(
								'sp_calculate' => 'total',
							),
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
						),
						array(
							'post_title' => 'Net Yds',
							'post_name' => 'netyds',
							'meta' => array(
								'sp_calculate' => 'total',
							),
							'tax_input' => array(
								'sp_position' => array(
									'punter',
								),
							),
						),
						array(
							'post_title' => 'Avg',
							'post_name' => 'avg',
							'meta' => array(
								'sp_calculate' => 'average',
							),
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
						),
						array(
							'post_title' => 'Net Avg',
							'post_name' => 'netavg',
							'meta' => array(
								'sp_calculate' => 'average',
							),
							'tax_input' => array(
								'sp_position' => array(
									'punter',
								),
							),
						),
						array(
							'post_title' => 'Blk',
							'post_name' => 'blk',
							'meta' => array(
								'sp_calculate' => 'total',
							),
							'tax_input' => array(
								'sp_position' => array(
									'punter',
								),
							),
						),
						array(
							'post_title' => 'OOB',
							'post_name' => 'oob',
							'meta' => array(
								'sp_calculate' => 'total',
							),
							'tax_input' => array(
								'sp_position' => array(
									'kickoffkicker',
									'punter',
								),
							),
						),
						array(
							'post_title' => 'Dn',
							'post_name' => 'dn',
							'meta' => array(
								'sp_calculate' => 'total',
							),
							'tax_input' => array(
								'sp_position' => array(
									'punter',
								),
							),
						),
						array(
							'post_title' => 'IN 20',
							'post_name' => 'in20',
							'meta' => array(
								'sp_calculate' => 'total',
							),
							'tax_input' => array(
								'sp_position' => array(
									'punter',
								),
							),
						),
						array(
							'post_title' => 'TB',
							'post_name' => 'tb',
							'meta' => array(
								'sp_calculate' => 'total',
							),
							'tax_input' => array(
								'sp_position' => array(
									'punter',
								),
							),
						),
						array(
							'post_title' => 'FC',
							'post_name' => 'fc',
							'meta' => array(
								'sp_calculate' => 'total',
							),
							'tax_input' => array(
								'sp_position' => array(
									'kickreturner',
									'punter',
									'puntreturner',
								),
							),
						),
						array(
							'post_title' => 'Ret',
							'post_name' => 'retpunt',
							'meta' => array(
								'sp_calculate' => 'total',
							),
							'tax_input' => array(
								'sp_position' => array(
									'kickoffkicker',
									'kickreturner',
									'punter',
									'puntreturner',
								),
							),
						),
						array(
							'post_title' => 'RetY',
							'post_name' => 'rety',
							'meta' => array(
								'sp_calculate' => 'total',
							),
							'tax_input' => array(
								'sp_position' => array(
									'kickreturner',
									'punter',
									'puntreturner',
								),
							),
						),
						array(
							'post_title' => 'Yds/G',
							'post_name' => 'ydsg',
							'meta' => array(
								'sp_calculate' => 'total',
							),
							'tax_input' => array(
								'sp_position' => array(
									'quarterback',
									'runningback',
									'widereceiver',
									'tightend',
								),
							),
						),
						array(
							'post_title' => 'TD',
							'post_name' => 'TD',
							'meta' => array(
								'sp_calculate' => 'total',
							),
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
						),
						array(
							'post_title' => 'Int',
							'post_name' => 'int',
							'meta' => array(
								'sp_calculate' => 'total',
							),
							'tax_input' => array(
								'sp_position' => array(
									'quarterback',
									'defensivelineman',
									'linebacker',
									'defensiveback',
								),
							),
						),
						array(
							'post_title' => '1st',
							'post_name' => 'first',
							'meta' => array(
								'sp_calculate' => 'total',
							),
							'tax_input' => array(
								'sp_position' => array(
									'quarterback',
									'runningback',
									'widereceiver',
									'tightend',
								),
							),
						),
						array(
							'post_title' => '1st%',
							'post_name' => 'firstpct',
							'meta' => array(
								'sp_calculate' => 'average',
							),
							'tax_input' => array(
								'sp_position' => array(
									'quarterback',
									'runningback',
									'widereceiver',
									'tightend',
								),
							),
						),
						array(
							'post_title' => 'Lng',
							'post_name' => 'lng',
							'meta' => array(
								'sp_calculate' => 'total',
							),
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
						),
						array(
							'post_title' => '20+',
							'post_name' => 'twentyplus',
							'meta' => array(
								'sp_calculate' => 'total',
							),
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
						),
						array(
							'post_title' => '40+',
							'post_name' => 'fourtyplus',
							'meta' => array(
								'sp_calculate' => 'total',
							),
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
						),
						array(
							'post_title' => 'Sck',
							'post_name' => 'sck',
							'meta' => array(
								'sp_calculate' => 'total',
							),
							'tax_input' => array(
								'sp_position' => array(
									'quarterback',
								),
							),
						),
						array(
							'post_title' => 'Rate',
							'post_name' => 'rate',
							'meta' => array(
								'sp_calculate' => 'total',
							),
							'tax_input' => array(
								'sp_position' => array(
									'quarterback',
								),
							),
						),
						array(
							'post_title' => 'FUM',
							'post_name' => 'fum',
							'meta' => array(
								'sp_calculate' => 'total',
							),
							'tax_input' => array(
								'sp_position' => array(
									'runningback',
									'widereceiver',
									'tightend',
									'kickreturner',
									'puntreturner',
								),
							),
						),
						array(
							'post_title' => 'FF',
							'post_name' => 'ff',
							'meta' => array(
								'sp_calculate' => 'total',
							),
							'tax_input' => array(
								'sp_position' => array(
									'defensivelineman',
									'linebacker',
									'defensiveback',
								),
							),
						),
						array(
							'post_title' => 'Rec',
							'post_name' => 'recfum',
							'meta' => array(
								'sp_calculate' => 'total',
							),
							'tax_input' => array(
								'sp_position' => array(
									'defensivelineman',
									'linebacker',
									'defensiveback',
								),
							),
						),
						array(
							'post_title' => 'TD',
							'post_name' => 'tdfum',
							'meta' => array(
								'sp_calculate' => 'total',
							),
							'tax_input' => array(
								'sp_position' => array(
									'kickoffkicker',
								),
							),
						),
						array(
							'post_title' => 'Avg',
							'post_name' => 'avgpunt',
							'meta' => array(
								'sp_calculate' => 'average',
							),
							'tax_input' => array(
								'sp_position' => array(
									'kickoffkicker',
									'kickreturner',
									'puntreturner',
								),
							),
						),
						array(
							'post_title' => 'Lng',
							'post_name' => 'lngpunt',
							'meta' => array(
								'sp_calculate' => 'total',
							),
							'tax_input' => array(
								'sp_position' => array(
									'kickreturner',
									'puntreturner',
								),
							),
						),
						array(
							'post_title' => 'TD',
							'post_name' => 'tdpunt',
							'meta' => array(
								'sp_calculate' => 'total',
							),
							'tax_input' => array(
								'sp_position' => array(
									'kickoffkicker',
									'kickreturner',
									'punter',
									'puntreturner',
								),
							),
						),
						array(
							'post_title' => '20+',
							'post_name' => 'twentypluspunt',
							'meta' => array(
								'sp_calculate' => 'total',
							),
							'tax_input' => array(
								'sp_position' => array(
									'kickreturner',
									'puntreturner',
								),
							),
						),
						array(
							'post_title' => '40+',
							'post_name' => 'fourtypluspunt',
							'meta' => array(
								'sp_calculate' => 'total',
							),
							'tax_input' => array(
								'sp_position' => array(
									'kickreturner',
									'puntreturner',
								),
							),
						),
						array(
							'post_title' => 'FC',
							'post_name' => 'fcpunt',
							'meta' => array(
								'sp_calculate' => 'total',
							),
							'tax_input' => array(
								'sp_position' => array(
									'kickreturner',
									'puntreturner',
								),
							),
						),
						array(
							'post_title' => 'FUM',
							'post_name' => 'fumpunt',
							'meta' => array(
								'sp_calculate' => 'total',
							),
							'tax_input' => array(
								'sp_position' => array(
									'kickreturner',
									'puntreturner',
								),
							),
						),
						array(
							'post_title' => 'OSK',
							'post_name' => 'osk',
							'meta' => array(
								'sp_calculate' => 'total',
							),
							'tax_input' => array(
								'sp_position' => array(
									'kickoffkicker',
								),
							),
						),
						array(
							'post_title' => 'OSKR',
							'post_name' => 'oskr',
							'meta' => array(
								'sp_calculate' => 'total',
							),
							'tax_input' => array(
								'sp_position' => array(
									'kickoffkicker',
								),
							),
						),
					),
				),
			),
			'footy' => array(
				'name' => __( 'Australian Rules Football', 'sportspress' ),
				'posts' => array(
					// Table Columns
					'sp_column' => array(
						array(
							'post_title' => 'P',
							'post_name' => 'p',
							'meta'       => array(
								'sp_equation'     => '$eventsplayed',
							)
						),
						array(
							'post_title' => 'W',
							'post_name' => 'w',
							'meta'       => array(
								'sp_equation'     => '$w',
							)
						),
						array(
							'post_title' => 'L',
							'post_name' => 'l',
							'meta'       => array(
								'sp_equation'     => '$l',
							)
						),
						array(
							'post_title' => 'D',
							'post_name' => 'd',
							'meta'       => array(
								'sp_equation'     => '$d',
							)
						),
						array(
							'post_title' => 'F',
							'post_name' => 'f',
							'meta'       => array(
								'sp_equation'     => '$ptsfor',
							)
						),
						array(
							'post_title' => 'A',
							'post_name' => 'a',
							'meta'       => array(
								'sp_equation'     => '$ptsagainst',
							)
						),
						array(
							'post_title' => 'Pct',
							'post_name' => 'pct',
							'meta'       => array(
								'sp_equation'     => '( $w / $eventsplayed ) * 10 * 10',
							)
						),
						array(
							'post_title' => 'Pts',
							'post_name' => 'pts',
							'meta'       => array(
								'sp_equation'     => '$pts',
							)
						)
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
			),
			'gaming' => array(
				'name' => __( 'Competitive Gaming', 'sportspress' ),
				'posts' => array(
					// Table Columns
					'sp_column' => array(
						array(
							'post_title' => 'W',
							'post_name' => 'w',
							'meta'       => array(
								'sp_equation'     => '$w',
							),
						),
						array(
							'post_title' => 'L',
							'post_name' => 'l',
							'meta'       => array(
								'sp_equation'     => '$l',
							),
						),
						array(
							'post_title' => 'Pct',
							'post_name' => 'pct',
							'meta'       => array(
								'sp_equation'     => '$w / $eventsplayed',
							),
						),
						array(
							'post_title' => 'Strk',
							'post_name' => 'strk',
							'meta'       => array(
								'sp_equation'     => '$strk',
							),
						),
						array(
							'post_title' => 'XP',
							'post_name' => 'xp',
							'meta'       => array(
								'sp_equation'     => '$xp',
							),
						),
						array(
							'post_title' => 'Rep',
							'post_name' => 'rep',
							'meta'       => array(
								'sp_equation'     => '$rep / $eventsplayed',
							),
						),
						array(
							'post_title' => 'Ping',
							'post_name' => 'ping',
							'meta'       => array(
								'sp_equation'     => '$ping / $eventsplayed',
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
			),
			'golf' => array(
				'name' => __( 'Golf', 'sportspress' ),
				'posts' => array(
					// Table Columns
					'sp_column' => array(
					),
					// Statistics
					'sp_statistic' => array(
						array(
							'post_title' => 'Events',
							'post_name' => 'events',
							'meta'       => array(
								'sp_equation'     => '$eventsplayed',
							),
						),
						array(
							'post_title' => 'Avg',
							'post_name' => 'avg',
							'meta'       => array(
								'sp_equation'     => '$ptsfor / $eventsplayed',
							),
						),
						array(
							'post_title' => 'Total',
							'post_name' => 'total',
							'meta'       => array(
								'sp_equation'     => '$ptsfor',
							),
						),
						array(
							'post_title' => 'PL',
							'post_name' => 'lost',
							'meta'       => array(
								'sp_equation'     => '$ptsagainst',
							),
						),
						array(
							'post_title' => 'PG',
							'post_name' => 'gained',
							'meta'       => array(
								'sp_equation'     => '$ptsfor',
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
			),
			'hockey' => array(
				'name' => __( 'Hockey', 'sportspress' ),
				'posts' => array(
					// Table Columns
					'sp_column' => array(
						array(
							'post_title' => 'GP',
							'post_name'  => 'gp',
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
							'post_title' => 'L',
							'post_name'  => 'l',
							'meta'       => array(
								'sp_equation'     => '$l',
							),
						),
						array(
							'post_title' => 'OT',
							'post_name'  => 'ot',
							'meta'       => array(
								'sp_equation'     => '$ot',
							),
						),
						array(
							'post_title' => 'P',
							'post_name'  => 'p',
							'meta'       => array(
								'sp_equation'     => '$w * 2 + $ot',
							),
						),
						array(
							'post_title' => 'GF',
							'post_name'  => 'gf',
							'meta'       => array(
								'sp_equation'     => '$gfor',
							),
						),
						array(
							'post_title' => 'GA',
							'post_name'  => 'ga',
							'meta'       => array(
								'sp_equation'     => '$gagainst',
							),
						),
						array(
							'post_title' => 'Strk',
							'post_name'  => 'strk',
							'meta'       => array(
								'sp_equation'     => '$streak',
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
						array(
							'post_title' => 'Win',
							'post_name'  => 'w'
						),
						array(
							'post_title' => 'Loss',
							'post_name'  => 'l'
						),
						array(
							'post_title' => 'Overtime',
							'post_name'  => 'ot'
						),
					),
				),
			),
			'racing' => array(
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
			),
			'rugby' => array(
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
			),
			'soccer' => array(
				'name' => __( 'Soccer (Association Football)', 'sportspress' ),
				'terms' => array(
					// Positions
					'sp_position' => array(
						array(
							'name' => 'Goalkeeper',
							'slug' => 'goalkeeper',
						),
						array(
							'name' => 'Defender',
							'slug' => 'defender',
						),
						array(
							'name' => 'Midfielder',
							'slug' => 'midfielder',
						),
						array(
							'name' => 'Forward',
							'slug' => 'forward',
						),
					),
				),
				'posts' => array(
					// Results
					'sp_result' => array(
						array(
							'post_title' => '1st Half',
							'post_name'  => 'firsthalf',
						),
						array(
							'post_title' => '2nd Half',
							'post_name'  => 'secondhalf',
						),
						array(
							'post_title' => 'Goals',
							'post_name'  => 'goals',
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
							'post_title' => 'F',
							'post_name'  => 'f',
							'meta'       => array(
								'sp_equation'     => '$goalsfor',
								'sp_precision'    => 0,
								'sp_priority'     => '3',
								'sp_order'        => 'DESC',
							),
						),
						array(
							'post_title' => 'A',
							'post_name'  => 'a',
							'meta'       => array(
								'sp_equation'     => '$goalsagainst',
								'sp_precision'    => 0,
							),
						),
						array(
							'post_title' => 'GD',
							'post_name'  => 'gd',
							'meta'       => array(
								'sp_equation'     => '$goalsfor - $goalsagainst',
								'sp_precision'    => 0,
								'sp_priority'     => '2',
								'sp_order'        => 'DESC',
							),
						),
						array(
							'post_title' => 'Pts',
							'post_name'  => 'pts',
							'meta'       => array(
								'sp_equation'     => '$w * 3 + $d',
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
							'tax_input' => array(
								'sp_position' => array(
									'goalkeeper',
									'defender',
									'midfielder',
									'forward',
								),
							),
						),
						array(
							'post_title' => 'Weight',
							'post_name'  => 'weight',
							'tax_input' => array(
								'sp_position' => array(
									'goalkeeper',
									'defender',
									'midfielder',
									'forward',
								),
							),
						),
					),
					// Player Statistics
					'sp_statistic' => array(
						array(
							'post_title' => 'Goals',
							'post_name'  => 'goals',
							'meta'       => array(
								'sp_calculate'     => 'total',
							),
							'tax_input' => array(
								'sp_position' => array(
									'goalkeeper',
									'defender',
									'midfielder',
									'forward',
								),
							),
						),
						array(
							'post_title' => 'Assists',
							'post_name'  => 'assists',
							'meta'       => array(
								'sp_calculate'     => 'total',
							),
							'tax_input' => array(
								'sp_position' => array(
									'goalkeeper',
									'defender',
									'midfielder',
									'forward',
								),
							),
						),
						array(
							'post_title' => 'Yellow Cards',
							'post_name'  => 'yellowcards',
							'meta'       => array(
								'sp_calculate'     => 'total',
							),
							'tax_input' => array(
								'sp_position' => array(
									'goalkeeper',
									'defender',
									'midfielder',
									'forward',
								),
							),
						),
						array(
							'post_title' => 'Red Cards',
							'post_name'  => 'redcards',
							'meta'       => array(
								'sp_calculate'     => 'total',
							),
							'tax_input' => array(
								'sp_position' => array(
									'goalkeeper',
									'defender',
									'midfielder',
									'forward',
								),
							),
						),
					),
				),
			),
		));
	}

	public function __get( $key ) {
		if ( 'options' == $key ):
			$option = array();
			foreach ( $this->data as $slug => $data ):
				$options[ $slug ] = $data['name'];
			endforeach;
			return $options;
		endif;
		return ( array_key_exists( $key, $this->data ) ? $this->data[ $key ] : null );
	}

	public function __set( $key, $value ){
		$this->data[ $key ] = $value;
	}
}
