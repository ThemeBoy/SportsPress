<h3 class="title"><?php _e( 'Player Settings', 'sportspress' ); ?></h3>
<?php
settings_fields( 'sportspress_players' );
do_settings_sections( 'sportspress_players' );
submit_button();

$args = array(
	'post_type' => 'sp_metric',
	'numberposts' => -1,
	'posts_per_page' => -1,
		'orderby' => 'menu_order',
		'order' => 'ASC'
);
$data = get_posts( $args );
?>
<h3 class="title"><?php _e( 'Metrics', 'sportspress' ); ?></h3>
<table class="widefat sp-admin-config-table">
	<thead>
		<tr>
			<th scope="col"><?php _e( 'Label', 'sportspress' ); ?></th>
			<th scope="col"><?php _e( 'Positions', 'sportspress' ); ?></th>
			<th scope="col">&nbsp;</th>
		</tr>
	</thead>
	<tfoot>
		<tr>
			<th scope="col"><?php _e( 'Label', 'sportspress' ); ?></th>
			<th scope="col"><?php _e( 'Positions', 'sportspress' ); ?></th>
			<th scope="col">&nbsp;</th>
		</tr>
	</tfoot>
	<?php $i = 0; foreach ( $data as $row ): ?>
		<tr<?php if ( $i % 2 == 0 ) echo ' class="alternate"'; ?>>
			<td class="row-title"><?php echo $row->post_title; ?></td>
			<td><?php echo get_the_terms ( $row->ID, 'sp_position' ) ? the_terms( $row->ID, 'sp_position' ) : '&mdash;'; ?></td>
			<td>&nbsp;</td>
		</tr>
	<?php $i++; endforeach; ?>
</table>
<div class="tablenav bottom">
	<div class="alignleft actions">
		<a class="button" id="doaction" href="<?php echo admin_url( 'edit.php?post_type=sp_metric' ); ?>"><?php _e( 'Edit', 'sportspress' ); ?></a>
		<a class="button" id="doaction2" href="<?php echo admin_url( 'post-new.php?post_type=sp_metric' ); ?>"><?php _e( 'Add New', 'sportspress' ); ?></a>
	</div>
	<br class="clear">
</div>
<?php
$args = array(
	'post_type' => 'sp_statistic',
	'numberposts' => -1,
	'posts_per_page' => -1,
	'orderby' => 'menu_order',
	'order' => 'ASC'
);
$data = get_posts( $args );
?>
<h3 class="title"><?php _e( 'Statistics', 'sportspress' ); ?></h3>
<table class="widefat sp-admin-config-table">
	<thead>
		<tr>
			<th scope="col"><?php _e( 'Label', 'sportspress' ); ?></th>
			<th scope="col"><?php _e( 'Positions', 'sportspress' ); ?></th>
			<th scope="col"><?php _e( 'Calculate', 'sportspress' ); ?></th>
		</tr>
	</thead>
	<tfoot>
		<tr>
			<th scope="col"><?php _e( 'Label', 'sportspress' ); ?></th>
			<th scope="col"><?php _e( 'Positions', 'sportspress' ); ?></th>
			<th scope="col"><?php _e( 'Calculate', 'sportspress' ); ?></th>
		</tr>
	</tfoot>
	<?php $i = 0; foreach ( $data as $row ): ?>
		<tr<?php if ( $i % 2 == 0 ) echo ' class="alternate"'; ?>>
			<td class="row-title"><?php echo $row->post_title; ?></td>
			<td><?php echo get_the_terms ( $row->ID, 'sp_position' ) ? the_terms( $row->ID, 'sp_position' ) : '&mdash;'; ?></td>
			<td><?php echo sportspress_get_post_calculate( $row->ID ); ?></td>
		</tr>
	<?php $i++; endforeach; ?>
</table>
<div class="tablenav bottom">
	<div class="alignleft actions">
		<a class="button" id="doaction" href="<?php echo admin_url( 'edit.php?post_type=sp_statistic' ); ?>"><?php _e( 'Edit', 'sportspress' ); ?></a>
		<a class="button" id="doaction2" href="<?php echo admin_url( 'post-new.php?post_type=sp_statistic' ); ?>"><?php _e( 'Add New', 'sportspress' ); ?></a>
	</div>
	<br class="clear">
</div>