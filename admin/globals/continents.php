<?php
function sportspress_define_continents_global() {
	global $sportspress_continents;

	$sportspress_continents = array(
		__( 'Africa', 'countries' ) => array('AO','BF','BI','BJ','BW','CD','CF','CG','CI','CM','CV','DJ','DZ','EG','EH','ER','ET','GA','GH','GM','GN','GQ','GW','KE','KM','LR','LS','LY','MA','MG','ML','MR','MU','MZ','NA','NE','NG','RW','SC','SD','SL','SN','SO','ST','SZ','TD','TG','TN','TZ','UG','ZA','ZM','ZW'),
		__( 'Asia', 'countries' ) => array('AE','AF','AM','AZ','BD','BH','BN','BT','CN','CY','GE','HK','IL','IN','IQ','IR','JO','JP','KG','KH','KP','KR','KW','KZ','LA','LB','LK','MM','MN','MO','MV','MY','NP','OM','PH','PK','QA','SA','SG','TH','TJ','TM','TW','UZ','VN','YE'),
		__( 'Europe', 'countries' ) => array('AD','AL','AT','BA','BE','BG','BY','CH','CZ','DE','DK','EE','EN','ES','FI','FR','GB','GR','HR','HU','IE','IS','IT','LI','LT','LU','LV','MC','MD','ME','MK','MT','MW','NB','NL','NO','PL','PT','RO','RS','RU','SE','SF','SI','SK','SM','TR','UA','VA','WA'),
		__( 'North America', 'countries' ) => array('AG','BB','BS','BZ','CA','CR','CU','DM','DO','GD','GT','HN','HT','JM','KN','LC','MX','NI','PA','SV','US','VC'),
		__( 'Oceania', 'countries' ) => array('AU','TL','FJ','FM','ID','KI','MH','NR','NZ','PG','PW','SB','TO','TV','VU','WS'),
		__( 'South America', 'countries' ) => array('AR','BO','BR','CL','CO','EC','GY','PE','PY','SR','TT','UY','VE'),
	);
}
add_action( 'init', 'sportspress_define_continents_global' );
