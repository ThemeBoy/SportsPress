<?php
class SP_Widget_Sponsors extends WP_Widget {

	function __construct() {
		$widget_ops = array('classname' => 'widget_sportspress widget_sponsors widget_sp_sponsors', 'description' => __( 'A list of sponsors.', 'sportspress' ) );
		parent::__construct('sportspress-sponsors', __( 'Sponsors', 'sportspress' ), $widget_ops);
	}

	function widget( $args, $instance ) {
		extract($args);
		$title = apply_filters('widget_title', empty($instance['title']) ? '' : $instance['title'], $instance, $this->id_base);
		$level = empty($instance['level']) ? null : $instance['level'];
		$limit = empty($instance['limit']) ? null : $instance['limit'];
		$width = empty($instance['width']) ? null : $instance['width'];
		$height = empty($instance['height']) ? null : $instance['height'];
		$orderby = empty($instance['orderby']) ? 'default' : $instance['orderby'];
		$order = empty($instance['order']) ? 'ASC' : $instance['order'];

		do_action( 'sportspress_before_widget', $args, $instance, 'sponsors' );
		echo $before_widget;

		if ( $title )
			echo $before_title . $title . $after_title;

		// Action to hook into
		do_action( 'sportspress_before_widget_template', $args, $instance, 'sponsors' );

		sp_get_template( 'sponsors.php', array( 'level' => $level, 'limit' => $limit, 'width' => $width, 'height' => $height, 'orderby' => $orderby, 'order' => $order ), '', SP_SPONSORS_DIR . 'templates/' );

		// Action to hook into
		do_action( 'sportspress_after_widget_template', $args, $instance, 'sponsors' );

		echo $after_widget;
		do_action( 'sportspress_after_widget', $args, $instance, 'sponsors' );
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['level'] = intval($new_instance['level']);
		$instance['limit'] = intval($new_instance['limit']);
		$instance['width'] = intval($new_instance['width']);
		$instance['height'] = intval($new_instance['height']);
		$instance['orderby'] = strip_tags($new_instance['orderby']);
		$instance['order'] = strip_tags($new_instance['order']);

		// Filter to hook into
		$instance = apply_filters( 'sportspress_widget_update', $instance, $new_instance, $old_instance, 'sponsors' );

		return $instance;
	}

	function form( $instance ) {
		$instance = wp_parse_args( (array) $instance, array( 'title' => '', 'level' => 0, 'limit' => 5, 'width' => 256, 'height' => 128, 'orderby' => 'menu_order', 'order' => 'ASC' ) );
		$title = strip_tags($instance['title']);
		$level = intval($instance['level']);
		$limit = intval($instance['limit']);
		$width = intval($instance['width']);
		$height = intval($instance['height']);
		$orderby = strip_tags($instance['orderby']);
		$order = strip_tags($instance['order']);

		// Action to hook into
		do_action( 'sportspress_before_widget_template_form', $this, $instance, 'sponsors' );
		?>
		<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e( 'Title:', 'sportspress' ); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" /></p>

		<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e( 'Level', 'sportspress' ); ?>:</label>
		<?php
		$args = array(
			'taxonomy' => 'sp_level',
			'name' => $this->get_field_name('level'),
			'id' => $this->get_field_id('level'),
			'selected' => $level,
			'values' => 'term_id',
			'show_option_all' => __( 'All', 'sportspress' ),
			'class' => 'widefat',
		);
		sp_dropdown_taxonomies( $args );
		?></p>

		<p><label for="<?php echo $this->get_field_id('limit'); ?>"><?php _e( 'Number of sponsors to show:', 'sportspress' ); ?></label>
		<input id="<?php echo $this->get_field_id('limit'); ?>" name="<?php echo $this->get_field_name('limit'); ?>" type="text" value="<?php echo esc_attr($limit); ?>" size="3"></p>

		<p><label for="<?php echo $this->get_field_id('orderby'); ?>"><?php _e( 'Sort by:', 'sportspress' ); ?></label>
		<select name="<?php echo $this->get_field_name('orderby'); ?>" id="<?php echo $this->get_field_id('orderby'); ?>" class="sp-select-orderby widefat">
			<option value="menu_order" <?php selected( 'menu_order', $orderby ); ?>><?php _e( 'Menu Order', 'sportspress' ); ?></option>
			<option value="date" <?php selected( 'date', $orderby ); ?>><?php _e( 'Date', 'sportspress' ); ?></option>
			<option value="title" <?php selected( 'title', $orderby ); ?>><?php _e( 'Name', 'sportspress' ); ?></option>
			<option value="rand" <?php selected( 'rand', $orderby ); ?>><?php _e( 'Random', 'sportspress' ); ?></option>
		</select></p>

		<p><label for="<?php echo $this->get_field_id('order'); ?>"><?php _e( 'Sort Order:', 'sportspress' ); ?></label>
		<select name="<?php echo $this->get_field_name('order'); ?>" id="<?php echo $this->get_field_id('order'); ?>" class="sp-select-order widefat">
			<option value="ASC" <?php selected( 'ASC', $order ); ?>><?php _e( 'Ascending', 'sportspress' ); ?></option>
			<option value="DESC" <?php selected( 'DESC', $order ); ?>><?php _e( 'Descending', 'sportspress' ); ?></option>
		</select></p>

		<p><label for="<?php echo $this->get_field_id('width'); ?>"><?php _e( 'Max Width:', 'sportspress' ); ?></label>
		<input id="<?php echo $this->get_field_id('width'); ?>" name="<?php echo $this->get_field_name('width'); ?>" placeholder="256" type="number" value="<?php echo esc_attr($width); ?>" class="small-text"></p>
		
		<p><label for="<?php echo $this->get_field_id('height'); ?>"><?php _e( 'Max Height:', 'sportspress' ); ?></label>
		<input id="<?php echo $this->get_field_id('height'); ?>" name="<?php echo $this->get_field_name('height'); ?>" placeholder="128" type="number" value="<?php echo esc_attr($height); ?>" class="small-text"></p>

		<?php
		// Action to hook into
		do_action( 'sportspress_after_widget_template_form', $this, $instance, 'sponsors' );
	}
}

register_widget( 'SP_Widget_Sponsors' );
