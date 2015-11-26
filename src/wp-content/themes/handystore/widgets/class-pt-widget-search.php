<?php
/**
 * Plumtree Search
 *
 * Configurable search widget, set custom input text and submit button text.
 *
 * @author StartBox Extended By TransparentIdeas
 * @package StartBox
 * @subpackage Widgets
 * @since 0.01
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

add_action( 'widgets_init', create_function( '', 'register_widget( "pt_search_widget");' ) );

class pt_search_widget extends WP_Widget {
	
	public function __construct() {
		parent::__construct(
	 		'pt_search_widget', // Base ID
			__('PT Search', 'plumtree'), // Name
			array('description' => __( "Plum Tree special widget. A search form for your site.", "plumtree" ), ) 
		);
	}

	public function form($instance) {
		$defaults = array(
			'title' => 'Search Field',
			'search-input' => 'Text here...',
			'search-button' => 'Find'
		);
		$instance = wp_parse_args( (array) $instance, $defaults );
	?>
		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e( 'Title: ', 'plumtree' ) ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id('title') ); ?>" name="<?php echo esc_attr( $this->get_field_name('title') ); ?>" type="text" value="<?php echo esc_attr( $instance['title'] ); ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e( 'Input Text: ', 'plumtree' ) ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id('search-input') ); ?>" name="<?php echo esc_attr( $this->get_field_name('search-input') ); ?>" type="text" value="<?php echo esc_attr( $instance['search-input'] ); ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e( 'Button Title Text: ', 'plumtree' ) ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id('search-button') ); ?>" name="<?php echo esc_attr( $this->get_field_name('search-button') ); ?>" type="text" value="<?php echo esc_attr( $instance['search-button'] ); ?>" />
		</p>
	<?php
	}

	public function update($new_instance, $old_instance) {
		$instance = $old_instance;

		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['search-input'] = strip_tags( $new_instance['search-input'] );
		$instance['search-button'] = strip_tags( $new_instance['search-button'] );

		return $instance;
	}

	public function widget($args, $instance) {
		extract($args);

		$title = apply_filters('widget_title', $instance['title'] );
		$text = ( isset($instance['search-input']) ? $instance['search-input'] : 'Text here...' );
		$button = ( isset($instance['search-button']) ? $instance['search-button'] : 'Find' );

		echo $before_widget;
		if ($title) { echo $before_title . $title . $after_title; }
	?>

	<form class="pt-searchform" method="get" action="<?php echo esc_url( home_url() ); ?>">
		<input id="s" name="s" type="text" class="searchtext" value="" placeholder="<?php echo esc_attr( $text ); ?>" tabindex="1">
		<button id="searchsubmit" class="search-button" title="<?php echo esc_attr( $button ); ?>" tabindex="2"><i class="fa fa-search"></i></button>
	</form>		

	<?php
		echo $after_widget;
	}

}