<?php
class SP_Widget_Player_Gallery extends WP_Widget {

	function __construct() {
		$widget_ops = array(
			'classname'   => 'widget_sportspress widget_player_gallery widget_sp_player_gallery',
			'description' => esc_attr__( 'Display a gallery of players.', 'sportspress' ),
		);
		parent::__construct( 'sportspress-player-gallery', esc_attr__( 'Player Gallery', 'sportspress' ), $widget_ops );
	}

	function widget( $args, $instance ) {
		extract( $args );

		$id = empty( $instance['id'] ) ? 0 : $instance['id'];

		if ( $id <= 0 ) {
			return;
		}

		if ( 'yes' == get_option( 'sportspress_widget_unique', 'no' ) && get_the_ID() === $id ) {
			$format = get_post_meta( $id, 'sp_format', true );
			if ( 'gallery' == $format ) {
				return;
			}
		}

		$title                 = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base );
		$caption               = empty( $instance['caption'] ) ? null : $instance['caption'];
		$number                = empty( $instance['number'] ) ? null : $instance['number'];
		$columns               = empty( $instance['columns'] ) ? null : $instance['columns'];
		$orderby               = empty( $instance['orderby'] ) ? 'default' : $instance['orderby'];
		$order                 = empty( $instance['order'] ) ? 'ASC' : $instance['order'];
		$show_all_players_link = empty( $instance['show_all_players_link'] ) ? false : $instance['show_all_players_link'];

		do_action( 'sportspress_before_widget', $args, $instance, 'player-gallery' );
		echo wp_kses_post( $before_widget );

		if ( $title ) {
			echo wp_kses_post( $before_title . $title . $after_title );
		}

		// Action to hook into
		do_action( 'sportspress_before_widget_template', $args, $instance, 'player-gallery' );

		sp_get_template(
			'player-gallery.php',
			array(
				'id'                    => $id,
				'title'                 => $caption,
				'number'                => $number,
				'columns'               => $columns,
				'orderby'               => $orderby,
				'order'                 => $order,
				'grouping'              => 0,
				'show_all_players_link' => $show_all_players_link,
			)
		);

		// Action to hook into
		do_action( 'sportspress_after_widget_template', $args, $instance, 'player-gallery' );

		echo wp_kses_post( $after_widget );
		do_action( 'sportspress_after_widget', $args, $instance, 'player-gallery' );
	}

	function update( $new_instance, $old_instance ) {
		$instance                          = $old_instance;
		$instance['title']                 = strip_tags( $new_instance['title'] );
		$instance['id']                    = intval( $new_instance['id'] );
		$instance['caption']               = strip_tags( $new_instance['caption'] );
		$instance['number']                = intval( $new_instance['number'] );
		$instance['columns']               = isset( $new_instance['columns'] ) ? intval( $new_instance['columns'] ) : 2;
		$instance['orderby']               = isset( $new_instance['orderby'] ) ? strip_tags( $new_instance['orderby'] ) : 'default';
		$instance['order']                 = isset( $new_instance['order'] ) ? strip_tags( $new_instance['order'] ) : 'ASC';
		$instance['show_all_players_link'] = isset( $new_instance['show_all_players_link'] ) ? $new_instance['show_all_players_link'] : false;

		// Filter to hook into
		$instance = apply_filters( 'sportspress_widget_update', $instance, $new_instance, $old_instance, 'player-gallery' );

		return $instance;
	}

	function form( $instance ) {
		$instance              = wp_parse_args(
			(array) $instance,
			array(
				'title'                 => '',
				'id'                    => '',
				'caption'               => '',
				'number'                => 5,
				'columns'               => 2,
				'orderby'               => 'default',
				'order'                 => 'ASC',
				'show_all_players_link' => true,
			)
		);
		$title                 = strip_tags( $instance['title'] );
		$id                    = intval( $instance['id'] );
		$caption               = strip_tags( $instance['caption'] );
		$number                = intval( $instance['number'] );
		$columns               = intval( $instance['columns'] );
		$orderby               = strip_tags( $instance['orderby'] );
		$order                 = strip_tags( $instance['order'] );
		$show_all_players_link = $instance['show_all_players_link'];

		// Action to hook into
		do_action( 'sportspress_before_widget_template_form', $this, $instance, 'player-gallery' );
		?>
		<p><label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_attr_e( 'Title:', 'sportspress' ); ?></label>
		<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" /></p>

		<p><label for="<?php echo esc_attr( $this->get_field_id( 'caption' ) ); ?>"><?php esc_attr_e( 'Heading:', 'sportspress' ); ?></label>
		<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'caption' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'caption' ) ); ?>" type="text" value="<?php echo esc_attr( $caption ); ?>" /></p>

		<p><label for="<?php echo esc_attr( $this->get_field_id( 'id' ) ); ?>"><?php printf( esc_attr__( 'Select %s:', 'sportspress' ), esc_attr__( 'Player List', 'sportspress' ) ); ?></label>
		<?php
		$args = array(
			'post_type' => 'sp_list',
			'name'      => $this->get_field_name( 'id' ),
			'id'        => $this->get_field_id( 'id' ),
			'selected'  => $id,
			'values'    => 'ID',
			'class'     => 'widefat',
		);
		if ( ! sp_dropdown_pages( $args ) ) :
			sp_post_adder( 'sp_list', esc_attr__( 'Add New', 'sportspress' ) );
		endif;
		?>
		</p>

		<p><label for="<?php echo esc_attr( $this->get_field_id( 'number' ) ); ?>"><?php esc_attr_e( 'Number of players to show:', 'sportspress' ); ?></label>
		<input id="<?php echo esc_attr( $this->get_field_id( 'number' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'number' ) ); ?>" type="text" value="<?php echo esc_attr( $number ); ?>" size="3"></p>

		<p><label for="<?php echo esc_attr( $this->get_field_id( 'columns' ) ); ?>"><?php esc_attr_e( 'Columns:', 'sportspress' ); ?></label>
		<input id="<?php echo esc_attr( $this->get_field_id( 'columns' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'columns' ) ); ?>" type="text" value="<?php echo esc_attr( $columns ); ?>" size="3"></p>

		<p><label for="<?php echo esc_attr( $this->get_field_id( 'orderby' ) ); ?>"><?php esc_attr_e( 'Sort by:', 'sportspress' ); ?></label>
		<?php
		$args = array(
			'prepend_options' => array(
				'default'      => esc_attr__( 'Default', 'sportspress' ),
				'number'       => esc_attr__( 'Squad Number', 'sportspress' ),
				'name'         => esc_attr__( 'Name', 'sportspress' ),
				'eventsplayed' => esc_attr__( 'Played', 'sportspress' ),
			),
			'append_options'  => array(
				'rand' => esc_attr__( 'Random', 'sportspress' ),
			),
			'post_type'       => 'sp_performance',
			'name'            => $this->get_field_name( 'orderby' ),
			'id'              => $this->get_field_id( 'orderby' ),
			'selected'        => $orderby,
			'values'          => 'slug',
			'class'           => 'sp-select-orderby widefat',
		);
		if ( ! sp_dropdown_pages( $args ) ) :
			sp_post_adder( 'sp_list', esc_attr__( 'Add New', 'sportspress' ) );
		endif;
		?>
		</p>

		<p><label for="<?php echo esc_attr( $this->get_field_id( 'order' ) ); ?>"><?php esc_attr_e( 'Sort Order:', 'sportspress' ); ?></label>
		<select name="<?php echo esc_attr( $this->get_field_name( 'order' ) ); ?>" id="<?php echo esc_attr( $this->get_field_id( 'order' ) ); ?>" class="sp-select-order widefat" <?php disabled( $orderby, 'default' ); ?>>
			<option value="ASC" <?php selected( 'ASC', $order ); ?>><?php esc_attr_e( 'Ascending', 'sportspress' ); ?></option>
			<option value="DESC" <?php selected( 'DESC', $order ); ?>><?php esc_attr_e( 'Descending', 'sportspress' ); ?></option>
		</select></p>

		<p><input class="checkbox" type="checkbox" id="<?php echo esc_attr( $this->get_field_id( 'show_all_players_link' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'show_all_players_link' ) ); ?>" value="1" <?php checked( $show_all_players_link, 1 ); ?>>
		<label for="<?php echo esc_attr( $this->get_field_id( 'show_all_players_link' ) ); ?>"><?php esc_attr_e( 'Display link to view all players', 'sportspress' ); ?></label></p>

		<?php
		// Action to hook into
		do_action( 'sportspress_after_widget_template_form', $this, $instance, 'player-gallery' );
	}
}

register_widget( 'SP_Widget_Player_Gallery' );
