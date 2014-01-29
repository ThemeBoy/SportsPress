<?php
function sportspress_plugin_action_links( $links ) { 
  $settings_link = '<a href="options-general.php?page=sportspress">' . __( 'Settings', 'sportspress' ) . '</a>';
//  $docs_link = '<a href="http://docs.themeboy.com/sportspress" target="_blank">' . __( 'Docs', 'sportspress' ) . '</a>';
//  $themes_link = '<a href="http://themeboy.com/themes?plugin=sportspress" target="_blank">' . __( 'Themes', 'sportspress' ) . '</a>';

  array_push( $links, $settings_link );

  return $links;
}
 
$plugin = SPORTSPRESS_PLUGIN_BASENAME; 
add_filter( "plugin_action_links_$plugin", 'sportspress_plugin_action_links' );
