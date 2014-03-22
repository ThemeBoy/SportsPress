<?php
function sportspress_define_globals() {

	// Options
	global $sportspress_options;
	
	$sportspress_options = (array)get_option( 'sportspress', array() );

	// Text
	global $sportspress_text_options;
	
	$sportspress_text_options = array(
		__( 'Article', 'sportspress' ),
		__( 'Current Team', 'sportspress' ),
		__( 'Date', 'sportspress' ),
		__( 'Details', 'sportspress' ),
		__( 'days', 'sportspress' ),
		__( 'Event', 'sportspress' ),
		__( 'Friendly', 'sportspress' ),
		__( 'hrs', 'sportspress' ),
		__( 'League', 'sportspress' ),
		__( 'mins', 'sportspress' ),
		__( 'Nationality', 'sportspress' ),
		__( 'Past Teams', 'sportspress' ),
		__( 'Player', 'sportspress' ),
		__( 'Position', 'sportspress' ),
		__( 'Pos', 'sportspress' ),
		__( 'Preview', 'sportspress' ),
		__( 'Rank', 'sportspress' ),
		__( 'Recap', 'sportspress' ),
		__( 'Results', 'sportspress' ),
		__( 'Season', 'sportspress' ),
		__( 'secs', 'sportspress' ),
		__( 'Staff', 'sportspress' ),
		__( 'Substitute', 'sportspress' ),
		__( 'Team', 'sportspress' ),
		__( 'Teams', 'sportspress' ),
		__( 'Time', 'sportspress' ),
		__( 'Total', 'sportspress' ),
		__( 'Venue', 'sportspress' ),
		__( 'View all players', 'sportspress' ),
		__( 'View all events', 'sportspress' ),
		__( 'View full table', 'sportspress' ),
	);

	sort( $sportspress_text_options );

	// Continents
	global $sportspress_continents;

	$sportspress_continents = array(
		__( 'Africa', 'sportspress' ) => array('AO','BF','BI','BJ','BW','CD','CF','CG','CI','CM','CV','DJ','DZ','EG','EH','ER','ET','GA','GH','GM','GN','GQ','GW','KE','KM','LR','LS','LY','MA','MG','ML','MR','MU','MZ','NA','NE','NG','RW','SC','SD','SL','SN','SO','ST','SZ','TD','TG','TN','TZ','UG','ZA','ZM','ZW'),
		__( 'Asia', 'sportspress' ) => array('AE','AF','AM','AZ','BD','BH','BN','BT','CN','CY','GE','HK','IL','IN','IQ','IR','JO','JP','KG','KH','KP','KR','KW','KZ','LA','LB','LK','MM','MN','MO','MV','MY','NP','OM','PH','PK','QA','SA','SG','TH','TJ','TM','TW','UZ','VN','YE'),
		__( 'Europe', 'sportspress' ) => array('AD','AL','AT','BA','BE','BG','BY','CH','CZ','DE','DK','EE','EN','ES','FI','FR','GB','GR','HR','HU','IE','IS','IT','LI','LT','LU','LV','MC','MD','ME','MK','MT','MW','NB','NL','NO','PL','PT','RO','RS','RU','SE','SF','SI','SK','SM','TR','UA','VA','WA'),
		__( 'North America', 'sportspress' ) => array('AG','BB','BS','BZ','CA','CR','CU','DM','DO','GD','GT','HN','HT','JM','KN','LC','MX','NI','PA','SV','US','VC'),
		__( 'Oceania', 'sportspress' ) => array('AU','TL','FJ','FM','ID','KI','MH','NR','NZ','PG','PW','SB','TO','TV','VU','WS'),
		__( 'South America', 'sportspress' ) => array('AR','BO','BR','CL','CO','EC','GY','PE','PY','SR','TT','UY','VE'),
	);

	// Countries
	global $sportspress_countries;

	$sportspress_countries = array(
		'AD' => __( "Andorra", 'sportspress' ),
	    'AE' => __( "United Arab Emirates", 'sportspress' ),
	    'AF' => __( "Afghanistan", 'sportspress' ),
	    'AG' => __( "Antigua and Barbuda", 'sportspress' ),
	    'AL' => __( "Albania", 'sportspress' ),
	    'AM' => __( "Armenia", 'sportspress' ),
	    'AO' => __( "Angola", 'sportspress' ),
	    'AR' => __( "Argentina", 'sportspress' ),
	    'AT' => __( "Austria", 'sportspress' ),
	    'AU' => __( "Australia", 'sportspress' ),
	    'AZ' => __( "Azerbaijan", 'sportspress' ),
	    'BA' => __( "Bosnia and Herzegovina", 'sportspress' ),
	    'BB' => __( "Barbados", 'sportspress' ),
	    'BD' => __( "Bangladesh", 'sportspress' ),
	    'BE' => __( "Belgium", 'sportspress' ),
	    'BF' => __( "Burkina Faso", 'sportspress' ),
	    'BG' => __( "Bulgaria", 'sportspress' ),
	    'BH' => __( "Bahrain", 'sportspress' ),
	    'BI' => __( "Burundi", 'sportspress' ),
	    'BJ' => __( "Benin", 'sportspress' ),
	    'BN' => __( "Brunei", 'sportspress' ),
	    'BO' => __( "Bolivia", 'sportspress' ),
	    'BR' => __( "Brazil", 'sportspress' ),
	    'BS' => __( "Bahamas", 'sportspress' ),
	    'BT' => __( "Bhutan", 'sportspress' ),
	    'BW' => __( "Botswana", 'sportspress' ),
	    'BY' => __( "Belarus", 'sportspress' ),
	    'BZ' => __( "Belize", 'sportspress' ),
	    'CA' => __( "Canada", 'sportspress' ),
	    'CD' => __( "Democratic Republic of the Congo", 'sportspress' ),
	    'CF' => __( "Central African Republic", 'sportspress' ),
	    'CG' => __( "Republic of the Congo", 'sportspress' ),
	    'CH' => __( "Switzerland", 'sportspress' ),
	    'CI' => __( "Ivory Coast", 'sportspress' ),
	    'CL' => __( "Chile", 'sportspress' ),
	    'CM' => __( "Cameroon", 'sportspress' ),
	    'CN' => __( "China", 'sportspress' ),
	    'CO' => __( "Colombia", 'sportspress' ),
	    'CR' => __( "Costa Rica", 'sportspress' ),
	    'CU' => __( "Cuba", 'sportspress' ),
	    'CV' => __( "Cape Verde", 'sportspress' ),
	    'CY' => __( "Cyprus", 'sportspress' ),
	    'CZ' => __( "Czech Republic", 'sportspress' ),
	    'DE' => __( "Germany", 'sportspress' ),
	    'DJ' => __( "Djibouti", 'sportspress' ),
	    'DK' => __( "Denmark", 'sportspress' ),
	    'DM' => __( "Dominica", 'sportspress' ),
	    'DO' => __( "Dominican Republic", 'sportspress' ),
	    'DZ' => __( "Algeria", 'sportspress' ),
	    'EC' => __( "Ecuador", 'sportspress' ),
	    'EE' => __( "Estonia", 'sportspress' ),
	    'EG' => __( "Egypt", 'sportspress' ),
	    'EH' => __( "Western Sahara", 'sportspress' ),
	    'EN' => __( "England", 'sportspress' ),
	    'ER' => __( "Eritrea", 'sportspress' ),
	    'ES' => __( "Spain", 'sportspress' ),
	    'ET' => __( "Ethiopia", 'sportspress' ),
	    'FI' => __( "Finland", 'sportspress' ),
	    'FJ' => __( "Fiji", 'sportspress' ),
	    'FM' => __( "Micronesia", 'sportspress' ),
	    'FR' => __( "France", 'sportspress' ),
	    'GA' => __( "Gabon", 'sportspress' ),
	    'GB' => __( "United Kingdom", 'sportspress' ),
	    'GD' => __( "Grenada", 'sportspress' ),
	    'GE' => __( "Georgia", 'sportspress' ),
	    'GH' => __( "Ghana", 'sportspress' ),
	    'GM' => __( "Gambia", 'sportspress' ),
	    'GN' => __( "Guinea", 'sportspress' ),
	    'GQ' => __( "Equatorial Guinea", 'sportspress' ),
	    'GR' => __( "Greece", 'sportspress' ),
	    'GT' => __( "Guatemala", 'sportspress' ),
	    'GW' => __( "Guinea-Bissau", 'sportspress' ),
	    'GY' => __( "Guyana", 'sportspress' ),
	    'HK' => __( "Hong Kong", 'sportspress' ),
	    'HN' => __( "Honduras", 'sportspress' ),
	    'HR' => __( "Croatia", 'sportspress' ),
	    'HT' => __( "Haiti", 'sportspress' ),
	    'HU' => __( "Hungary", 'sportspress' ),
	    'ID' => __( "Indonesia", 'sportspress' ),
	    'IE' => __( "Ireland", 'sportspress' ),
	    'IL' => __( "Israel", 'sportspress' ),
	    'IN' => __( "India", 'sportspress' ),
	    'IQ' => __( "Iraq", 'sportspress' ),
	    'IR' => __( "Iran", 'sportspress' ),
	    'IS' => __( "Iceland", 'sportspress' ),
	    'IT' => __( "Italy", 'sportspress' ),
	    'JM' => __( "Jamaica", 'sportspress' ),
	    'JO' => __( "Jordan", 'sportspress' ),
	    'JP' => __( "Japan", 'sportspress' ),
	    'KE' => __( "Kenya", 'sportspress' ),
	    'KG' => __( "Kyrgyzstan", 'sportspress' ),
	    'KH' => __( "Cambodia", 'sportspress' ),
	    'KI' => __( "Kiribati", 'sportspress' ),
	    'KM' => __( "Comoros", 'sportspress' ),
	    'KN' => __( "Saint Kitts and Nevis", 'sportspress' ),
	    'KP' => __( "North Korea", 'sportspress' ),
	    'KR' => __( "South Korea", 'sportspress' ),
	    'KW' => __( "Kuwait", 'sportspress' ),
	    'KZ' => __( "Kazakhstan", 'sportspress' ),
	    'LA' => __( "Laos", 'sportspress' ),
	    'LB' => __( "Lebanon", 'sportspress' ),
	    'LC' => __( "Saint Lucia", 'sportspress' ),
	    'LI' => __( "Liechtenstein", 'sportspress' ),
	    'LK' => __( "Sri Lanka", 'sportspress' ),
	    'LR' => __( "Liberia", 'sportspress' ),
	    'LS' => __( "Lesotho", 'sportspress' ),
	    'LT' => __( "Lithuania", 'sportspress' ),
	    'LU' => __( "Luxembourg", 'sportspress' ),
	    'LV' => __( "Latvia", 'sportspress' ),
	    'LY' => __( "Libya", 'sportspress' ),
	    'MA' => __( "Morocco", 'sportspress' ),
	    'MC' => __( "Monaco", 'sportspress' ),
	    'MD' => __( "Moldova", 'sportspress' ),
	    'ME' => __( "Montenegro", 'sportspress' ),
	    'MG' => __( "Madagascar", 'sportspress' ),
	    'MH' => __( "Marshall Islands", 'sportspress' ),
	    'MK' => __( "Macedonia", 'sportspress' ),
	    'ML' => __( "Mali", 'sportspress' ),
	    'MM' => __( "Burma", 'sportspress' ),
	    'MN' => __( "Mongolia", 'sportspress' ),
	    'MO' => __( "Macau", 'sportspress' ),
	    'MR' => __( "Mauritania", 'sportspress' ),
	    'MT' => __( "Malta", 'sportspress' ),
	    'MU' => __( "Mauritius", 'sportspress' ),
	    'MV' => __( "Maldives", 'sportspress' ),
	    'MW' => __( "Malawi", 'sportspress' ),
	    'MX' => __( "Mexico", 'sportspress' ),
	    'MY' => __( "Malaysia", 'sportspress' ),
	    'MZ' => __( "Mozambique", 'sportspress' ),
	    'NA' => __( "Namibia", 'sportspress' ),
	    'NB' => __( "Northern Ireland", 'sportspress' ),
	    'NE' => __( "Niger", 'sportspress' ),
	    'NG' => __( "Nigeria", 'sportspress' ),
	    'NI' => __( "Nicaragua", 'sportspress' ),
	    'NL' => __( "Netherlands", 'sportspress' ),
	    'NO' => __( "Norway", 'sportspress' ),
	    'NP' => __( "Nepal", 'sportspress' ),
	    'NR' => __( "Nauru", 'sportspress' ),
	    'NZ' => __( "New Zealand", 'sportspress' ),
	    'OM' => __( "Oman", 'sportspress' ),
	    'PA' => __( "Panama", 'sportspress' ),
	    'PE' => __( "Peru", 'sportspress' ),
	    'PG' => __( "Papua New Guinea", 'sportspress' ),
	    'PH' => __( "Philippines", 'sportspress' ),
	    'PK' => __( "Pakistan", 'sportspress' ),
	    'PL' => __( "Poland", 'sportspress' ),
	    'PT' => __( "Portugal", 'sportspress' ),
	    'PW' => __( "Palau", 'sportspress' ),
	    'PY' => __( "Paraguay", 'sportspress' ),
	    'QA' => __( "Qatar", 'sportspress' ),
	    'RO' => __( "Romania", 'sportspress' ),
	    'RS' => __( "Serbia", 'sportspress' ),
	    'RU' => __( "Russia", 'sportspress' ),
	    'RW' => __( "Rwanda", 'sportspress' ),
	    'SA' => __( "Saudi Arabia", 'sportspress' ),
	    'SB' => __( "Solomon Islands", 'sportspress' ),
	    'SC' => __( "Seychelles", 'sportspress' ),
	    'SD' => __( "Sudan", 'sportspress' ),
	    'SE' => __( "Sweden", 'sportspress' ),
	    'SF' => __( "Scotland", 'sportspress' ),
	    'SG' => __( "Singapore", 'sportspress' ),
	    'SI' => __( "Slovenia", 'sportspress' ),
	    'SK' => __( "Slovakia", 'sportspress' ),
	    'SL' => __( "Sierra Leone", 'sportspress' ),
	    'SM' => __( "San Marino", 'sportspress' ),
	    'SN' => __( "Senegal", 'sportspress' ),
	    'SO' => __( "Somalia", 'sportspress' ),
	    'SR' => __( "Suriname", 'sportspress' ),
	    'ST' => __( "Sao Tome and Principe", 'sportspress' ),
	    'SV' => __( "El Salvador", 'sportspress' ),
	    'SZ' => __( "Swaziland", 'sportspress' ),
	    'TD' => __( "Chad", 'sportspress' ),
	    'TG' => __( "Togo", 'sportspress' ),
	    'TH' => __( "Thailand", 'sportspress' ),
	    'TJ' => __( "Tajikistan", 'sportspress' ),
	    'TL' => __( "East Timor", 'sportspress' ),
	    'TM' => __( "Turkmenistan", 'sportspress' ),
	    'TN' => __( "Tunisia", 'sportspress' ),
	    'TO' => __( "Tonga", 'sportspress' ),
	    'TR' => __( "Turkey", 'sportspress' ),
	    'TT' => __( "Trinidad and Tobago", 'sportspress' ),
	    'TV' => __( "Tuvalu", 'sportspress' ),
	    'TW' => __( "Taiwan", 'sportspress' ),
	    'TZ' => __( "Tanzania", 'sportspress' ),
	    'UA' => __( "Ukraine", 'sportspress' ),
	    'UG' => __( "Uganda", 'sportspress' ),
	    'US' => __( "United States", 'sportspress' ),
	    'UY' => __( "Uruguay", 'sportspress' ),
	    'UZ' => __( "Uzbekistan", 'sportspress' ),
	    'VA' => __( "Vatican City", 'sportspress' ),
	    'VC' => __( "Saint Vincent and the Grenadines", 'sportspress' ),
	    'VE' => __( "Venezuela", 'sportspress' ),
	    'VN' => __( "Vietnam", 'sportspress' ),
	    'VU' => __( "Vanuatu", 'sportspress' ),
	    'WL' => __( "Wales", 'sportspress' ),
	    'WS' => __( "Samoa", 'sportspress' ),
	    'YE' => __( "Yemen", 'sportspress' ),
	    'ZA' => __( "South Africa", 'sportspress' ),
	    'ZM' => __( "Zambia", 'sportspress' ),
	    'ZW' => __( "Zimbabwe", 'sportspress' ),
	);

	asort( $sportspress_countries );

	// Formats
	global $sportspress_formats;

	$sportspress_formats = array( 'event' => array(), 'list' => array() );

	$sportspress_formats['event']['league'] = __( 'League', 'sportspress' );
	$sportspress_formats['event']['friendly'] = __( 'Friendly', 'sportspress' );

	$sportspress_formats['calendar']['calendar'] = __( 'Calendar', 'sportspress' );
	$sportspress_formats['calendar']['list'] = __( 'List', 'sportspress' );

	$sportspress_formats['list']['list'] = __( 'List', 'sportspress' );
	$sportspress_formats['list']['gallery'] = __( 'Gallery', 'sportspress' );

	// Sports
	global $sportspress_sports;

	include_once dirname( SPORTSPRESS_PLUGIN_FILE ) . '/presets/sports/soccer.php';
	include_once dirname( SPORTSPRESS_PLUGIN_FILE ) . '/presets/sports/football.php';
	include_once dirname( SPORTSPRESS_PLUGIN_FILE ) . '/presets/sports/footy.php';
	include_once dirname( SPORTSPRESS_PLUGIN_FILE ) . '/presets/sports/baseball.php';
	include_once dirname( SPORTSPRESS_PLUGIN_FILE ) . '/presets/sports/basketball.php';
	include_once dirname( SPORTSPRESS_PLUGIN_FILE ) . '/presets/sports/gaming.php';
	include_once dirname( SPORTSPRESS_PLUGIN_FILE ) . '/presets/sports/cricket.php';
	include_once dirname( SPORTSPRESS_PLUGIN_FILE ) . '/presets/sports/golf.php';
	include_once dirname( SPORTSPRESS_PLUGIN_FILE ) . '/presets/sports/handball.php';
	include_once dirname( SPORTSPRESS_PLUGIN_FILE ) . '/presets/sports/hockey.php';
	include_once dirname( SPORTSPRESS_PLUGIN_FILE ) . '/presets/sports/racing.php';
	include_once dirname( SPORTSPRESS_PLUGIN_FILE ) . '/presets/sports/rugby.php';
	include_once dirname( SPORTSPRESS_PLUGIN_FILE ) . '/presets/sports/swimming.php';
	include_once dirname( SPORTSPRESS_PLUGIN_FILE ) . '/presets/sports/tennis.php';
	include_once dirname( SPORTSPRESS_PLUGIN_FILE ) . '/presets/sports/volleyball.php';

	uasort( $sportspress_sports, 'sportspress_sort_sports' );
}
add_action( 'init', 'sportspress_define_globals' );
