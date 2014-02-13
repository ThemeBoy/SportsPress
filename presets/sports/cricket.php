<?php
global $sportspress_sports;

$sportspress_sports['cricket'] = array(
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
);
