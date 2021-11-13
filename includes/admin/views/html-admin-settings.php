<div class="wrap sportspress">
	<h2><?php echo wp_kses_post( apply_filters( 'sportspress_logo', '<img src="' . plugin_dir_url( SP_PLUGIN_FILE ) . 'assets/images/modules/sportspress' . ( class_exists( 'SportsPress_Pro' ) ? '-pro' : '' ) . '.png" alt="' . esc_attr__( 'SportsPress', 'sportspress' ) . '" class="sp-settings-logo">' ) ); ?></h2>
	<form method="post" id="mainform" action="" enctype="multipart/form-data">
		<h2 class="nav-tab-wrapper sp-nav-tab-wrapper">
			<?php
			foreach ( $tabs as $name => $label ) :
				?>
				<a href="<?php echo esc_attr( admin_url( 'admin.php?page=sportspress&tab=' . $name ) ); ?>" class="nav-tab <?php echo ( $current_tab == $name ? 'nav-tab-active' : '' ); ?>"><?php echo esc_html( $label ); ?></a><?php endforeach; ?>
			<?php do_action( 'sportspress_settings_tabs' ); ?>
		</h2>
		<?php
			do_action( 'sportspress_sections_' . $current_tab );
			do_action( 'sportspress_settings_' . $current_tab );
			do_action( 'sportspress_settings_tabs_' . $current_tab ); // @deprecated hook
		?>
		<p class="submit">
			<?php if ( ! isset( $GLOBALS['hide_save_button'] ) ) : ?>
				<input name="save" class="button-primary" type="submit" value="<?php esc_attr_e( 'Save Changes', 'sportspress' ); ?>" />
			<?php endif; ?>
			<input type="hidden" name="subtab" id="last_tab" />
			<?php wp_nonce_field( 'sportspress-settings' ); ?>
		</p>
	</form>
	<?php do_action( 'sportspress_settings_page' ); ?>
</div>
