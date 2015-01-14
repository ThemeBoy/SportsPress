<?php
/**
 * Sponsors
 *
 * @author 		ThemeBoy
 * @package 	SportsPress Sponsors
 * @version     1.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

$defaults = array(
	'title' => null,
	'limit' => -1,
	'width' => 256,
	'height' => 128,
	'orderby' => 'menu_order',
	'order' => 'ASC',
	'size' => 'sportspress-fit-icon',
);

extract( $defaults, EXTR_SKIP );

if ( $size == 'sportspress-fit-icon' && (int) $height > 128 || (int) $width > 128 )
	$size = 'sportspress-fit-medium';

$args = array(
	'post_type' => 'sp_sponsor',
	'numberposts' => $limit,
	'posts_per_page' => $limit,
	'orderby' => $orderby,
	'order' => $order,
);

$sponsors = get_posts( $args );

if ( $sponsors ):
	?>
	<div class="sp-sponsors">
		<?php if ( ! empty( $title ) ): ?>
			<h3 class="sp-sponsors-title"><?php echo $title; ?></h3>
		<?php endif; ?>
		<?php foreach( $sponsors as $sponsor ): ?>
			<?php if ( $sponsor->post_content == '' ): $url = get_post_meta( $sponsor->ID, 'sp_url', true ); ?>
				<a class="sponsor sp-sponsor"<?php if ( $url ): ?> href="<?php echo $url; ?>" data-nonce="<?php echo wp_create_nonce( 'sp_clicks_' . $sponsor->ID ); ?>" data-ajaxurl="<?php echo admin_url( 'admin-ajax.php' ); ?>" data-post="<?php echo $sponsor->ID; ?>"<?php endif; if ( get_option( 'sportspress_sponsor_site_target_blank', 'no' ) == 'yes' ) { ?> target="_blank"<?php } ?>>
			<?php else: ?>
				<a class="sponsor sp-sponsor" href="<?php echo get_post_permalink( $sponsor->ID ); ?>">
			<?php endif; ?>
			<?php
			if ( has_post_thumbnail( $sponsor->ID ) ):
				if ( $width && $height ):
					echo get_the_post_thumbnail( $sponsor->ID, $size, array( 'class' => 'sp-sponsor-logo', 'title' => $sponsor->post_title, 'style' => 'max-width:' . $width . 'px; max-height:' . $height . 'px;' ) );
				else:
					echo get_the_post_thumbnail( $sponsor->ID, $size, array( 'class' => 'sp-sponsor-logo', 'title' => $sponsor->post_title ) );
				endif;
			else:
				echo $sponsor->post_title;
			endif;
			sp_set_post_impressions( $sponsor->ID );
			?>
			</a>
		<?php endforeach; ?>
	</div>
<?php
endif;
