<?php
$sportspress_presets = array(
	'Baseball' => array(
		'team' =>
			'P' . ': $played' . "\r\n" .
			'W' . ': $wins' . "\r\n" .
			'D' . ': $ties' . "\r\n" .
			'L' . ': $losses' . "\r\n" .
			'F' . ': $for' . "\r\n" .
			'A' . ': $against' . "\r\n" .
			'GD' . ': $for - $against' . "\r\n" .
			'PTS' . ': 3 * $wins + $ties',
		'event' =>
			'1' . ': $first' . "\r\n" .
			'2' . ': $second' . "\r\n" .
			'3' . ': $third' . "\r\n" .
			'4' . ': $fourth' . "\r\n" .
			'5' . ': $fifth' . "\r\n" .
			'6' . ': $sixth' . "\r\n" .
			'7' . ': $seventh' . "\r\n" .
			'8' . ': $eigth' . "\r\n" .
			'9' . ': $ninth' . "\r\n" .
			'&nbsp;' . ': $extra' . "\r\n" .
			'R' . ': $runs' . "\r\n" .
			'H' . ': $hits' . "\r\n" .
			'E' . ': $errors:' . "\r\n" .
			'LOB' . ': $lob',
		'player' =>
			array(
				'G' . ': $played' . "\r\n" .
				'AB' . ': $ab' . "\r\n" .
				'R' . ': $runs' . "\r\n" .
				'H' . ': $hits' . "\r\n" .
				'2B' . ': $double' . "\r\n" .
				'3B' . ': $triple' . "\r\n" .
				'HR' . ': $hr' . "\r\n" .
				'RBI' . ': $rbi' . "\r\n" .
				'BB' . ': $bb' . "\r\n" .
				'SO' . ': $so' . "\r\n" .
				'SB' . ': $sb' . "\r\n" .
				'CS' . ': $cs' . "\r\n" .
				'AVG' . ': $hits / $ab' . "\r\n" .
				'OBP' . ': ( $hits + $bb + $hbp ) / ( $ab + $bb + $hbp + $sf )' . "\r\n" .
				'SLG' . ': $tb / $ab' . "\r\n" .
				'OPS' . ': ( $hits + $bb + $hbp ) / ( $ab + $bb + $hbp + $sf ) + ( $tb / $ab )'
			),
			array(
				'W' . ': $wins' . "\r\n" .
				'L' . ': $losses' . "\r\n" .
				'ERA' . ': ( $er * 9 ) / $ip' . "\r\n" .
				'G' . ': $played' . "\r\n" .
				'GS' . ': $gs' . "\r\n" .
				'SV' . ': $sv' . "\r\n" .
				'SVO' . ': $svo' . "\r\n" .
				'IP' . ': $ip' . "\r\n" .
				'H' . ': $hits' . "\r\n" .
				'R' . ': $runs' . "\r\n" .
				'ER' . ': $er' . "\r\n" .
				'HR' . ': $hr' . "\r\n" .
				'BB' . ': $bb' . "\r\n" .
				'SO' . ': $so' . "\r\n" .
				'AVG' . ': $hits / $ab' . "\r\n" .
				'WHIP' . ': ( $hits + $walks ) / $ip'
			),
			array(
				'W' . ': $wins' . "\r\n" .
				'L' . ': $losses' . "\r\n" .
				'ERA' . ': ( $er * 9 ) / $ip' . "\r\n" .
				'G' . ': $played' . "\r\n" .
				'AB' . ': $ab' . "\r\n" .
				'R' . ': $runs' . "\r\n" .
				'H' . ': $hits' . "\r\n" .
				'2B' . ': $double' . "\r\n" .
				'3B' . ': $triple' . "\r\n" .
				'HR' . ': $hr' . "\r\n" .
				'RBI' . ': $rbi' . "\r\n" .
				'BB' . ': $bb' . "\r\n" .
				'SO' . ': $so' . "\r\n" .
				'SB' . ': $sb' . "\r\n" .
				'CS' . ': $cs' . "\r\n" .
				'AVG' . ': $hits / $ab' . "\r\n" .
				'OBP' . ': ( $hits + $bb + $hbp ) / ( $ab + $bb + $hbp + $sf )' . "\r\n" .
				'SLG' . ': $tb / $ab' . "\r\n" .
				'OPS' . ': ( $hits + $bb + $hbp ) / ( $ab + $bb + $hbp + $sf ) + ( $tb / $ab )'
			)
	),
	'Basketball' => array(
		'team' =>
			'W' . ': $wins' . "\r\n" .
			'L' . ': $losses' . "\r\n" .
			'Pct' . ': $wins / $played' . "\r\n" .
			'GB' . ': ( $leadwins - $leadlosses + $wins - $losses ) / 2' . "\r\n" .
			'Home' . ': $homewins $homelosses' . "\r\n" .
			'Road' . ': $awaywins $awaylosses' . "\r\n" .
			'L10' . ': $lastten' . "\r\n" .
			'Streak' . ': $streak',
		'event' =>
			'Goals' . ': $goals' . "\r\n" .
			'Assists' . ': $assists' . "\r\n" .
			'Yellow Cards' . ': $yellowcards' . "\r\n" .
			'Red Cards' . ': $redcards',
		'player' =>
			'Attendances' . ': $played' . "\r\n" .
			'Goals' . ': $goals' . "\r\n" .
			'Assists' . ': $assists' . "\r\n" .
			'Yellow Cards' . ': $yellowcards' . "\r\n" .
			'Red Cards' . ': $redcards'
	),
	'Cricket' => array(
		'team' =>
		'P' . ': $played' . "\r\n" .
		'W' . ': $wins' . "\r\n" .
		'D' . ': $ties' . "\r\n" .
		'L' . ': $losses' . "\r\n" .
		'F' . ': $for' . "\r\n" .
		'A' . ': $against' . "\r\n" .
		'GD' . ': $for - $against' . "\r\n" .
			'PTS' . ': 3 * $wins + $ties',
		'event' =>
		'Goals' . ': $goals' . "\r\n" .
		'Assists' . ': $assists' . "\r\n" .
		'Yellow Cards' . ': $yellowcards' . "\r\n" .
		'Red Cards' . ': $redcards',
		'player' =>
		'Attendances' . ': $played' . "\r\n" .
		'Goals' . ': $goals' . "\r\n" .
		'Assists' . ': $assists' . "\r\n" .
		'Yellow Cards' . ': $yellowcards' . "\r\n" .
		'Red Cards' . ': $redcards'
	),
	'Football (USA)' => array(
		'team' =>
		'P' . ': $played' . "\r\n" .
		'W' . ': $wins' . "\r\n" .
		'D' . ': $ties' . "\r\n" .
		'L' . ': $losses' . "\r\n" .
		'F' . ': $for' . "\r\n" .
		'A' . ': $against' . "\r\n" .
		'GD' . ': $for - $against' . "\r\n" .
			'PTS' . ': 3 * $wins + $ties',
		'event' =>
		'Goals' . ': $goals' . "\r\n" .
		'Assists' . ': $assists' . "\r\n" .
		'Yellow Cards' . ': $yellowcards' . "\r\n" .
		'Red Cards' . ': $redcards',
		'player' =>
		'Attendances' . ': $played' . "\r\n" .
		'Goals' . ': $goals' . "\r\n" .
		'Assists' . ': $assists' . "\r\n" .
		'Yellow Cards' . ': $yellowcards' . "\r\n" .
		'Red Cards' . ': $redcards'
	),
	'Footy (Australia)' => array(
		'team' =>
		'P' . ': $played' . "\r\n" .
		'W' . ': $wins' . "\r\n" .
		'D' . ': $ties' . "\r\n" .
		'L' . ': $losses' . "\r\n" .
		'F' . ': $for' . "\r\n" .
		'A' . ': $against' . "\r\n" .
		'GD' . ': $for - $against' . "\r\n" .
			'PTS' . ': 3 * $wins + $ties',
		'event' =>
		'Goals' . ': $goals' . "\r\n" .
		'Assists' . ': $assists' . "\r\n" .
		'Yellow Cards' . ': $yellowcards' . "\r\n" .
		'Red Cards' . ': $redcards',
		'player' =>
		'Attendances' . ': $played' . "\r\n" .
		'Goals' . ': $goals' . "\r\n" .
		'Assists' . ': $assists' . "\r\n" .
		'Yellow Cards' . ': $yellowcards' . "\r\n" .
		'Red Cards' . ': $redcards'
	),
	'Hockey' => array(
		'team' =>
		'P' . ': $played' . "\r\n" .
		'W' . ': $wins' . "\r\n" .
		'D' . ': $ties' . "\r\n" .
		'L' . ': $losses' . "\r\n" .
		'F' . ': $for' . "\r\n" .
		'A' . ': $against' . "\r\n" .
		'GD' . ': $for - $against' . "\r\n" .
			'PTS' . ': 3 * $wins + $ties',
		'event' =>
		'Goals' . ': $goals' . "\r\n" .
		'Assists' . ': $assists' . "\r\n" .
		'Yellow Cards' . ': $yellowcards' . "\r\n" .
		'Red Cards' . ': $redcards',
		'player' =>
		'Attendances' . ': $played' . "\r\n" .
		'Goals' . ': $goals' . "\r\n" .
		'Assists' . ': $assists' . "\r\n" .
		'Yellow Cards' . ': $yellowcards' . "\r\n" .
		'Red Cards' . ': $redcards'
	),
	'Rugby' => array(
		'team' =>
		'P' . ': $played' . "\r\n" .
		'W' . ': $wins' . "\r\n" .
		'D' . ': $ties' . "\r\n" .
		'L' . ': $losses' . "\r\n" .
		'F' . ': $for' . "\r\n" .
		'A' . ': $against' . "\r\n" .
		'GD' . ': $for - $against' . "\r\n" .
			'PTS' . ': 3 * $wins + $ties',
		'event' =>
		'Goals' . ': $goals' . "\r\n" .
		'Assists' . ': $assists' . "\r\n" .
		'Yellow' . ' Cards: $yellowcards' . "\r\n" .
		'Red' . ' Cards: $redcards',
		'player' =>
		'Attendances' . ': $played' . "\r\n" .
		'Goals' . ': $goals' . "\r\n" .
		'Assists' . ': $assists' . "\r\n" .
		'Yellow' . ' Cards: $yellowcards' . "\r\n" .
		'Red' . ' Cards: $redcards'
	),
	'soccer' => array(
		'team' =>
		'P' . ': $played' . "\r\n" .
		'W' . ': $wins' . "\r\n" .
		'D' . ': $ties' . "\r\n" .
		'L' . ': $losses' . "\r\n" .
		'F' . ': $for' . "\r\n" .
		'A' . ': $against' . "\r\n" .
		'GD' . ': $for - $against' . "\r\n" .
			'PTS' . ': 3 * $wins + $ties',
		'event' =>
		'Goals' . ': $goals' . "\r\n" .
		'Assists' . ': $assists' . "\r\n" .
		'Yellow Cards' . ': $yellowcards' . "\r\n" .
		'Red Cards' . ': $redcards',
		'player' =>
		'Attendances' . ': $played' . "\r\n" .
		'Goals' . ': $goals' . "\r\n" .
		'Assists' . ': $assists' . "\r\n" .
		'Yellow Cards' . ': $yellowcards' . "\r\n" .
		'Red Cards' . ': $redcards'
	),
	'Tennis' => array(
		'team' =>
		'P' . ': $played' . "\r\n" .
		'W' . ': $wins' . "\r\n" .
		'D' . ': $ties' . "\r\n" .
		'L' . ': $losses' . "\r\n" .
		'F' . ': $for' . "\r\n" .
		'A' . ': $against' . "\r\n" .
		'GD' . ': $for - $against' . "\r\n" .
			'PTS' . ': 3 * $wins + $ties',
		'event' =>
		'Goals' . ': $goals' . "\r\n" .
		'Assists' . ': $assists' . "\r\n" .
		'Yellow' . ' Cards: $yellowcards' . "\r\n" .
		'Red' . ' Cards: $redcards',
		'player' =>
		'Attendances' . ': $played' . "\r\n" .
		'Goals' . ': $goals' . "\r\n" .
		'Assists' . ': $assists' . "\r\n" .
		'Yellow' . ' Cards: $yellowcards' . "\r\n" .
		'Red' . ' Cards: $redcards'
	),
	'Volleyball' => array(
		'team' =>
		'P' . ': $played' . "\r\n" .
		'W' . ': $wins' . "\r\n" .
		'D' . ': $ties' . "\r\n" .
		'L' . ': $losses' . "\r\n" .
		'F' . ': $for' . "\r\n" .
		'A' . ': $against' . "\r\n" .
		'GD' . ': $for - $against' . "\r\n" .
			'PTS' . ': 3 * $wins + $ties',
		'event' =>
		'Goals' . ': $goals' . "\r\n" .
		'Assists' . ': $assists' . "\r\n" .
		'Yellow' . ' Cards: $yellowcards' . "\r\n" .
		'Red' . ' Cards: $redcards',
		'player' =>
		'Attendances' . ': $played' . "\r\n" .
		'Goals' . ': $goals' . "\r\n" .
		'Assists' . ': $assists' . "\r\n" .
		'Yellow' . ' Cards: $yellowcards' . "\r\n" .
		'Red' . ' Cards: $redcards'
	)
);
?>