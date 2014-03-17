<?php
class SportsPress_Widget_Player_list extends WP_Widget {

	function __construct() {
		$widget_ops = array('classname' => 'widget_player_list widget_sp_player_list', 'description' => __( 'Display a list of players.', 'sportspress' ) );
		parent::__construct('sp_player_list', __( 'SportsPress Player List', 'sportspress' ), $widget_ops);
	}

	function widget( $args, $instance ) {
		extract($args);
		$title = apply_filters('widget_title', empty($instance['title']) ? '' : $instance['title'], $instance, $this->id_base);
		$id = empty($instance['id']) ? null : $instance['id'];
		$number = empty($instance['number']) ? null : $instance['number'];
		$statistics = $instance['statistics'];
		$orderby = empty($instance['orderby']) ? 'default' : $instance['orderby'];
		$order = empty($instance['order']) ? 'ASC' : $instance['order'];
		$show_all_players_link = empty($instance['show_all_players_link']) ? false : $instance['show_all_players_link'];
		echo $before_widget;
		if ( $title )
			echo $before_title . $title . $after_title;
		echo '<div id="sp_player_list_wrap">';
		echo sportspress_player_list( $id, array( 'number' => $number, 'statistics' => $statistics, 'orderby' => $orderby , 'order' => $order, 'show_all_players_link' => $show_all_players_link ) );
		echo '</div>';
		echo $after_widget;
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['id'] = intval($new_instance['id']);
		$instance['number'] = intval($new_instance['number']);
		$instance['statistics'] = (array)$new_instance['statistics'];
		$instance['orderby'] = strip_tags($new_instance['orderby']);
		$instance['order'] = strip_tags($new_instance['order']);
		$instance['show_all_players_link'] = $new_instance['show_all_players_link'];

		return $instance;
	}

	function form( $instance ) {
		$instance = wp_parse_args( (array) $instance, array( 'title' => '', 'id' => '', 'number' => 5, 'statistics' => null, 'orderby' => 'default', 'order' => 'ASC', 'show_all_players_link' => true ) );
		$title = strip_tags($instance['title']);
		$id = intval($instance['id']);
		$number = intval($instance['number']);
		$statistics = $instance['statistics'];
		$orderby = strip_tags($instance['orderby']);
		$order = strip_tags($instance['order']);
		$show_all_players_link = $instance['show_all_players_link'];
?>
		<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e( 'Title:', 'sportspress' ); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" /></p>

		<p><label for="<?php echo $this->get_field_id('id'); ?>"><?php printf( __( 'Select %s:', 'sportspress' ), __( 'Player List', 'sportspress' ) ); ?></label>
		<?php
		$args = array(
			'post_type' => 'sp_list',
			'name' => $this->get_field_name('id'),
			'id' => $this->get_field_id('id'),
			'selected' => $id,
			'values' => 'ID',
			'class' => 'widefat',
		);
		if ( ! sportspress_dropdown_pages( $args ) ):
			sportspress_post_adder( 'sp_list', __( 'Add New', 'sportspress' ) );
		endif;
		?>
		</p>

		<p><label for="<?php echo $this->get_field_id('number'); ?>"><?php _e( 'Number of players to show:', 'sportspress' ); ?></label>
		<input id="<?php echo $this->get_field_id('number'); ?>" name="<?php echo $this->get_field_name('number'); ?>" type="text" value="<?php echo esc_attr($number); ?>" size="3"></p>

		<p class="sp-prefs">
			<?php _e( 'Statistics:', 'sportspress' ); ?><br>
			<?php 
			$args = array(
				'post_type' => 'sp_statistic',
				'numberposts' => -1,
				'posts_per_page' => -1,
				'orderby' => 'menu_order',
				'order' => 'ASC'
			);
			$the_statistics = get_posts( $args );

			$field_name = $this->get_field_name('statistics') . '[]';
			$field_id = $this->get_field_id('statistics');
			?>
			<label class="button"><input name="<?php echo $field_name; ?>" type="checkbox" id="<?php echo $field_id . '-' . 'eventsplayed'; ?>" value="<?php echo 'eventsplayed'; ?>" <?php if ( is_array( $statistics) && in_array( 'eventsplayed', $statistics ) ): ?>checked="checked"<?php endif; ?>><?php _e( 'Played', 'sportspress' ); ?></label>
			<?php foreach ( $the_statistics as $column ): ?>
				<label class="button"><input name="<?php echo $field_name; ?>" type="checkbox" id="<?php echo $field_id . '-' . $column->post_name; ?>" value="<?php echo $column->post_name; ?>" <?php if ( $statistics === null || in_array( $column->post_name, $statistics ) ): ?>checked="checked"<?php endif; ?>><?php echo $column->post_title; ?></label>
			<?php endforeach; ?>
		</p>

		<p><label for="<?php echo $this->get_field_id('orderby'); ?>"><?php _e( 'Sort by:', 'sportspress' ); ?></label>
		<?php
		$args = array(
			'prepend_options' => array(
				'default' => __( 'Default', 'sportspress' ),
				'number' => __( 'Number', 'sportspress' ),
				'name' => __( 'Name', 'sportspress' ),
				'eventsplayed' => __( 'Played', 'sportspress' )
			),
			'post_type' => 'sp_statistic',
			'name' => $this->get_field_name('orderby'),
			'id' => $this->get_field_id('orderby'),
			'selected' => $orderby,
			'values' => 'slug',
			'class' => 'sp-select-orderby widefat',
		);
		if ( ! sportspress_dropdown_pages( $args ) ):
			sportspress_post_adder( 'sp_list', __( 'Add New', 'sportspress' ) );
		endif;
		?>
		</p>

		<p><label for="<?php echo $this->get_field_id('order'); ?>"><?php _e( 'Sort Order:', 'sportspress' ); ?></label>
		<select name="<?php echo $this->get_field_name('order'); ?>" id="<?php echo $this->get_field_id('order'); ?>" class="sp-select-order widefat" <?php disabled( $orderby, 'default' ); ?>>
			<option value="ASC" <?php selected( 'ASC', $order ); ?>><?php _e( 'Ascending', 'sportspress' ); ?></option>
			<option value="DESC" <?php selected( 'DESC', $order ); ?>><?php _e( 'Descending', 'sportspress' ); ?></option>
		</select></p>

		<p><input class="checkbox" type="checkbox" id="<?php echo $this->get_field_id('show_all_players_link'); ?>" name="<?php echo $this->get_field_name('show_all_players_link'); ?>" value="1" <?php checked( $show_all_players_link, 1 ); ?>>
		<label for="<?php echo $this->get_field_id('show_all_players_link'); ?>"><?php _e( 'Display link to view all players', 'sportspress' ); ?></label></p>
<?php
	}
}
add_action( 'widgets_init', create_function( '', 'return register_widget( "SportsPress_Widget_Player_list" );' ) );