<?php
/*
Plugin Name: Flatdesign Social Media Widget 
Plugin 
Description: SM Widget
Author: Max Klenk
Version: 0.1
Author URI: 
*/
class Flatdesign_SocialMediaWidget extends WP_Widget {

	/**
	 * Register widget with WordPress.
	 */
	function __construct() {
		parent::__construct(
			'flatdesign_social-media-widget', // Base ID
			'Social Media Widget', // Name
			array( 'description' => __( 'Widget for flatdesign theme. Supports Facebook, GooglePlus, Twitter and RSS', 'flatdesign-sm' ), ) // Args
		);
	}

	/**
	 * Front-end display of widget.
	 *
	 * @see WP_Widget::widget()
	 *
	 * @param array $args     Widget arguments.
	 * @param array $instance Saved values from database.
	 */
	public function widget( $args, $instance ) {
		$plugin_dir = plugin_dir_url( __FILE__ );
		wp_enqueue_style("flatdesign-sm-css", $plugin_dir."flatdesign-sm.css"); 

		for ($i = 0; $i < 4; $i++) {
			if (isset($instance['button_'.$i.'_url']) && isset($instance['button_'.$i.'_icon'])) {
			?>
				<div id="flatdesign_social-media-widget-button-<? echo $i ?>" class="widget widget_flatdesign_social-media-widget sixcol clearfix <? echo ($i%2 == 0) ? 'first' : ''?>  ">
					<a href="<? echo $instance['button_'.$i.'_url'] ?>"><img src="<? echo $plugin_dir."icons/".$instance['button_'.$i.'_icon'] ?>"></img></a>
				</div>
			<?php
			}
		}
	}

	/**
	 * Back-end widget form.
	 *
	 * @see WP_Widget::form()
	 *
	 * @param array $instance Previously saved values from database.
	 */
	public function form( $instance ) {

		// icon files
		$root = plugin_dir_url( __FILE__ );
		$url = plugins_url("", __FILE__);
        $url = substr($url, strpos($url, "wp-content"));
        $icons_glob = glob("../$url/icons/*.*");
        $icon_paths = array();
        foreach ($icons_glob as $icon_glob) {
			$icon_path = $root.substr($icon_glob, 3);
			$icon_path = substr($icon_path, strpos($icon_path, "/icons/") + 7);
			$icon_paths[] = $icon_path;

		}

		// get all button values
		$button_urls = array();
		$button_icons = array();
		for ($i = 0; $i < 4; $i++) { 
			$button_urls[$i] = isset($instance['button_'.$i.'_url'])
				? $instance['button_'.$i.'_url']
				: "url";
			$button_icons[$i] = isset($instance['button_'.$i.'_icon'])
				? $instance['button_'.$i.'_icon']
				: "";
		}

		for ($i = 0; $i < 4; $i++) {
			$name_url = 'button_'.$i.'_url';
			$name_icon = 'button_'.$i.'_icon';
		?>
			<p>
			<label for="<?php echo $this->get_field_name( $name_url ); ?>"><?php _e( 'URL '.($i+1).':' ); ?></label> 
			<input class="widefat" id="<?php echo $this->get_field_id( $name_url ); ?>" name="<?php echo $this->get_field_name( $name_url ); ?>" type="text" value="<?php echo esc_attr( $button_urls[$i] ); ?>" />
			</p>
			<p>
			<label for="<?php echo $this->get_field_name( $name_icon ); ?>"><?php _e( 'Icon '.($i+1).':' ); ?></label> 
			<select class="widefat" id="<?php echo $this->get_field_id( $name_icon ); ?>" name="<?php echo $this->get_field_name( $name_icon ); ?>" >
				<option value=""><?php _e( 'Select Icon' ); ?></option>
				<?php 
				foreach ($icon_paths as $icon_path) {
				?>
					<option <?php if ($button_icons[$i] == $icon_path) echo 'selected="selected"' ?> value="<?php echo $icon_path; ?>"><?php echo $icon_path; ?></option>
				<?php
				}
				?>
			</select> 
			</p>
		<?php
		}
		?>
		
		<?php 
	}

	/**
	 * Sanitize widget form values as they are saved.
	 *
	 * @see WP_Widget::update()
	 *
	 * @param array $new_instance Values just sent to be saved.
	 * @param array $old_instance Previously saved values from database.
	 *
	 * @return array Updated safe values to be saved.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = array();

		for ($i = 0; $i < 4; $i++) { 
			$instance['button_'.$i.'_url'] = ( ! empty( $new_instance['button_'.$i.'_url'] ) ) 
				? strip_tags( $new_instance['button_'.$i.'_url'] ) 
				: '';
			$instance['button_'.$i.'_icon'] = ( ! empty( $new_instance['button_'.$i.'_icon'] ) ) 
				? strip_tags( $new_instance['button_'.$i.'_icon'] ) 
				: '';
		}

		return $instance;
	}


} // class Flatdesign_SocialMediaWidget


// register Foo_Widget widget
function register_flatdesign_social_media_widget() {
    register_widget( 'Flatdesign_SocialMediaWidget' );
}
add_action( 'widgets_init', 'register_flatdesign_social_media_widget' );
