<div class="wrap sportspress sp_addons_wrap">
	<h2>
		<?php _e( 'SportsPress Add-ons/Extensions', 'sportspress' ); ?>
		<a href="http://themeboy.com/sportspress/extensions/" class="add-new-h2"><?php _e( 'Browse all extensions', 'sportspress' ); ?></a>
		<a href="http://themeboy.com/themes/sportspress/" class="add-new-h2"><?php _e( 'Browse themes', 'sportspress' ); ?></a>
	</h2>
	<?php if ( $addons ) : ?>
		<ul class="subsubsub">
			<?php
				$links = array(
					''                         => __( 'Popular', 'sportspress' ),
					'payment-gateways'         => __( 'Gateways', 'sportspress' ),
					'shipping-methods'         => __( 'Shipping', 'sportspress' ),
					'import-export-extensions' => __( 'Import/export', 'sportspress' ),
					'product-extensions'       => __( 'Products', 'sportspress' ),
					'marketing-extensions'     => __( 'Marketing', 'sportspress' ),
					'free-extensions'          => __( 'Free', 'sportspress' )
				);

				$i = 0;

				foreach ( $links as $link => $name ) {
					$i ++;
					?><li><a class="<?php if ( $view == $link ) echo 'current'; ?>" href="<?php echo admin_url( 'admin.php?page=sp-addons&view=' . esc_attr( $link ) ); ?>"><?php echo $name; ?></a><?php if ( $i != sizeof( $links ) ) echo ' |'; ?></li><?php
				}
			?>
		</ul>
		<br class="clear" />
		<?php echo $addons; ?>
	<?php else : ?>

		<p><?php printf( __( 'Our catalog of SportsPress Extensions can be found on ThemeBoy.com here: <a href="%s">SportsPress Extensions Catalog</a>', 'sportspress' ), 'http://themeboy.com/sportspress/extensions/' ); ?></p>

	<?php endif; ?>
</div>