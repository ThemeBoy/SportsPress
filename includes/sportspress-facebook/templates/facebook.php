<?php
/**
 * Facebook
 *
 * @author 		ThemeBoy
 * @package 	SportsPress_Facebook
 * @version     2.2.7
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

$defaults = array(
	'url' => 'https://www.facebook.com/themeboy/',
	'tabs' => array( 'timeline' ),
);

extract( $defaults, EXTR_SKIP );

if ( $url ) {
	?>
	<div class="sp-template sp-template-facebook">
		<div class="sp-facebook">
			<div class="fb-page"
				data-href="<?php echo esc_url( $url ); ?>"
				data-tabs="<?php echo implode( ',', $tabs ); ?>"
				data-small-header="false"
				data-adapt-container-width="true"
				data-hide-cover="false"
				data-show-facepile="true"
			></div>
		</div>
	</div>
	<?php
}
