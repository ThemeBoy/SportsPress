<?php
/**
 * Sponsors
 *
 * @author 		ThemeBoy
 * @package 	SportsPress Sponsors
 * @version   2.6.5
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

$defaults = array(
	'title' => null,
	'level' => 0,
	'limit' => -1,
	'width' => 256,
	'height' => 128,
	'orderby' => 'menu_order',
	'order' => 'ASC',
	'size' => 'sportspress-fit-icon',
);

extract( $defaults, EXTR_SKIP );

if ( 'rand' == $orderby ):
	?>
	<div class="sp-sponsors">
		<?php if ( ! empty( $title ) ): ?>
			<h3 class="sp-sponsors-title"><?php echo $title; ?></h3>
		<?php endif; ?>
		<div class="sp-sponsors-loader"
			data-nonce="<?php echo wp_create_nonce( 'sp_sponsors' ); ?>"
			data-ajaxurl="<?php echo admin_url( 'admin-ajax.php' ); ?>"
			data-level="<?php echo $level; ?>"
			data-limit="<?php echo $limit; ?>"
			data-width="<?php echo $width; ?>"
			data-height="<?php echo $height; ?>"
			data-size="<?php echo $size; ?>"></div>
	</div>
	<?php
	return;
else:
?>
<div class="sp-sponsors">
	<?php if ( ! empty( $title ) ): ?>
		<h3 class="sp-sponsors-title"><?php echo $title; ?></h3>
	<?php endif; ?>
	<?php sp_get_template( 'sponsors-content.php', array( 'level' => $level, 'limit' => $limit, 'width' => $width, 'height' => $height, 'orderby' => $orderby, 'order' => $order, 'size' => $size ), '', SP_SPONSORS_DIR . 'templates/' ); ?>
</div>
<?php
endif;
