<?php
class SP_Widget_Player_list extends WP_Widget {

	function __construct() {
		$widget_ops = array('classname' => 'widget_sportspress widget_player_list widget_sp_player_list', 'description' => __( 'Display a list of players.', 'sportspress' ) );
		parent::__construct('sportspress-player-list', __( 'Player List', 'sportspress' ), $widget_ops);
	}

	function widget( $args, $instance ) {
		extract($args);
		$id = empty($instance['id']) ? 0 : $instance['id'];
		if ( $id <= 0 ) return;
		$title = apply_filters('widget_title', empty($instance['title']) ? '' : $instance['title'], $instance, $this->id_base);
		$caption = empty($instance['caption']) ? null : $instance['caption'];
		$number = empty($instance['number']) ? null : $instance['number'];
		$columns = $instance['columns'];
		$orderby = empty($instance['orderby']) ? 'default' : $instance['orderby'];
		$order = empty($instance['order']) ? 'ASC' : $instance['order'];
		$show_all_players_link = empty($instance['show_all_players_link']) ? false : $instance['show_all_players_link'];

		do_action( 'sportspress_before_widget', $args, $instance, 'player-list' );
		echo $before_widget;

		if ( $title )
			echo $before_title . $title . $after_title;

		// Action to hook into
		do_action( 'sportspress_before_widget_template', $args, $instance, 'player-list' );

		sp_get_template( 'player-list.php', array( 'id' => $id, 'title' => $caption, 'number' => $number, 'columns' => $columns, 'orderby' => $orderby, 'order' => $order, 'grouping' => 0, 'show_all_players_link' => $show_all_players_link ) );

		// Action to hook into
		do_action( 'sportspress_after_widget_template', $args, $instance, 'player-list' );

		echo $after_widget;
		do_action( 'sportspress_after_widget', $args, $instance, 'player-list' );
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['id'] = intval($new_instance['id']);
		$instance['caption'] = strip_tags($new_instance['caption']);
		$instance['number'] = intval($new_instance['number']);
		$instance['columns'] = (array)$new_instance['columns'];
		$instance['orderby'] = strip_tags($new_instance['orderby']);
		$instance['order'] = strip_tags($new_instance['order']);
		$instance['show_all_players_link'] = $new_instance['show_all_players_link'];

		// Filter to hook into
		$instance = apply_filters( 'sportspress_widget_update', $instance, $new_instance, $old_instance, 'player-list' );

		return $instance;
	}

	function form( $instance ) {
		$instance = wp_parse_args( (array) $instance, array( 'title' => '', 'id' => '', 'caption' => '', 'number' => 5, 'columns' => null, 'orderby' => 'default', 'order' => 'ASC', 'show_all_players_link' => true ) );
		$title = strip_tags($instance['title']);
		$id = intval($instance['id']);
		$caption = strip_tags($instance['caption']);
		$number = intval($instance['number']);
		$columns = $instance['columns'];
		$orderby = strip_tags($instance['orderby']);
		$order = strip_tags($instance['order']);
		$show_all_players_link = $instance['show_all_players_link'];

		// Action to hook into
		do_action( 'sportspress_before_widget_template_form', $this, $instance, 'player-list' );
		?>
		<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e( 'Title:', 'sportspress' ); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" /></p>

		<p><label for="<?php echo $this->get_field_id('caption'); ?>"><?php _e( 'Heading:', 'sportspress' ); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('caption'); ?>" name="<?php echo $this->get_field_name('caption'); ?>" type="text" value="<?php echo esc_attr($caption); ?>" /></p>

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
		if ( ! sp_dropdown_pages( $args ) ):
			sp_post_adder( 'sp_list', __( 'Add New', 'sportspress' ) );
		endif;
		?>
		</p>

		<p><label for="<?php echo $this->get_field_id('number'); ?>"><?php _e( 'Number of players to show:', 'sportspress' ); ?></label>
		<input id="<?php echo $this->get_field_id('number'); ?>" name="<?php echo $this->get_field_name('number'); ?>" type="text" value="<?php echo esc_attr($number); ?>" size="3"></p>

		<p class="sp-prefs">
			<?php _e( 'Performance:', 'sportspress' ); ?><br>
			<?php 
			$args = array(
				'post_type' => array( 'sp_metric', 'sp_performance', 'sp_statistic' ),
				'numberposts' => -1,
				'posts_per_page' => -1,
				'orderby' => 'menu_order',
				'order' => 'ASC'
			);
			$the_columns = get_posts( $args );

			$field_name = $this->get_field_name('columns') . '[]';
			$field_id = $this->get_field_id('columns');
			?>
			<?php foreach ( $the_columns as $column ): ?>
				<label class="button"><input name="<?php echo $field_name; ?>" type="checkbox" id="<?php echo $field_id . '-' . $column->post_name; ?>" value="<?php echo $column->post_name; ?>" <?php if ( $columns === null || in_array( $column->post_name, $columns ) ): ?>checked="checked"<?php endif; ?>><?php echo $column->post_title; ?></label>
			<?php endforeach; ?>
		</p>

		<p><label for="<?php echo $this->get_field_id('orderby'); ?>"><?php _e( 'Sort by:', 'sportspress' ); ?></label>
		<?php
		$args = array(
			'prepend_options' => array(
				'default' => __( 'Default', 'sportspress' ),
				'number' => __( 'Squad Number', 'sportspress' ),
				'name' => __( 'Name', 'sportspress' ),
				'eventsplayed' => __( 'Played', 'sportspress' )
			),
			'post_type' => array( 'sp_metric', 'sp_performance', 'sp_statistic' ),
			'name' => $this->get_field_name('orderby'),
			'id' => $this->get_field_id('orderby'),
			'selected' => $orderby,
			'values' => 'slug',
			'class' => 'sp-select-orderby widefat',
		);
		if ( ! sp_dropdown_pages( $args ) ):
			sp_post_adder( 'sp_list', __( 'Add New', 'sportspress' ) );
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
		// Action to hook into
		do_action( 'sportspress_after_widget_template_form', $this, $instance, 'player-list' );
	}
}

register_widget( 'SP_Widget_Player_list' );
