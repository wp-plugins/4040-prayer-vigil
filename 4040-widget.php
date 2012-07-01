<?php
/**
 * 40/40 Prayer Vigil Widget.
 * 
 * This contains the widget code for the 40/40 Prayer Vigil plug-in.
 * 
 * @author Daniel J. Summers <daniel@djs-consulting.com>
 * @package FortyFortyPlugin
 * @version $Id$
 */
class FortyForty_Widget extends WP_Widget {
	
	/**
	 * Constructor.
	 */
	public function __construct() {
		parent::__construct( '4040_widget', 'FortyForty_Widget',
				array( description => __( "Displays the current day or hour's prayer guide", 'fortyforty_plugin' ) ) );
	}
	
	/**
	 * Display the widget.
	 * 
	 * @see WP_Widget::widget()
	 * 
	 * @param array $args Widget arguments
	 * @param array $instance Options from the database
	 */
	public function widget( $args, $instance ) {
		
		// If the guide is blank, do not display this widget.
		$fortyForty = new FortyForty();
		$guide = $fortyForty->PrayerGuide();
		
		if ( empty( $guide ) )
			return;
        
		$title = apply_filters( 'widget_title', $instance[ 'title' ] );
		
		echo $args [ 'before_widget' ];
		
        if ( ! empty( $title ) )
			echo $args [ 'before_title' ] . $title . $args[ 'after_title' ];
		
		echo $guide . $args [ 'after_widget' ];
	}	
	
	/**
	 * Update the widget's options.
	 * 
	 * @see WP_Widget::update()
	 * 
	 * @param array $new_instance The new values being updated
	 * @param array $old_instance The previous values
	 */
	public function update( $new_instance, $old_instance ) {
		return array( title => strip_tags( $new_instance[ 'title' ] ) );
	}
	
	/**
	 * Display a form to update the widget's options.
	 * 
	 * @see WP_Widget::form()
	 * 
	 * @param array $instance The previous values for this widget
	 */
	public function form( $instance ) {
		
		$title = ( isset( $instance[ 'title' ] ) )
				? $instance[ 'title' ]
				: __( '40/40 Prayer Vigil', 'fortyforty_plugin' ); ?>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label> 
			<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>"
				name="<?php echo $this->get_field_name( 'title' ); ?>" type="text"
				value="<?php echo esc_attr( $title ); ?>" />
		</p><?php
	}
}

add_action( 'widgets_init', create_function( '', 'register_widget( "FortyForty_widget" );' ) );
