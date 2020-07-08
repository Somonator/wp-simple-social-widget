<?php
/*
Plugin Name: Simple Soc Widget
Plugin URI: none
Description: Beatiful social button for your site, without social Api.
Version: 1.0
Author: Somonator
Author URI: http://vk.com/somonator 
 
    Copyright 2016  Alexsandr  (email: somonator@gmail.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

class Ssw_Widget extends WP_Widget {
	function __construct() {
		parent::__construct(
			'ssw_widget', 
			__('Simple Social Widget','simple-social-widget'),
			array( 'description' => __('Beatiful social button for your site, without social Api.','simple-social-widget'))
		);
	}

	function widget( $args, $instance ) {
		$title = apply_filters( 'widget_title', $instance['title'] );
		$link =@ $instance['link']; 
        $border =@ $instance['border'];
        $background =@ $instance['background'];
        $color =@ $instance['color'];
        $image =@ $instance['image'];
        $text =@ $instance['text'];
        $texthover =@ $instance['texthover'];

		echo $args['before_widget'];
		if ( ! empty( $title ) ) {
			echo $args['before_title'] . $title . $args['after_title'];
		}
		echo "<a href='$link'>
  <div class='ssw'> 
    <div class='ssw-block' style='background: $background;  border: 4px solid $border;  color: $color;'>
      <img  src='$image'>
        <div class='ssw-content'>
		  <div class='ssw-text'>$text</div> 
		  <div class='ssw-text2'>$texthover</div>
     </div>
  </div> 
</div>
</a>";
		echo $args['after_widget'];
	}

	function form( $instance ) {
		$title = @ $instance['title'] ?: '';
		$link = @ $instance['link'] ?: 'https://vk.com/';
		$border = @ $instance['border'] ?: ' #3F7BAA';
		$background = @ $instance['background'] ?: '#3F7BAA';
		$color = @ $instance['color'] ?: '#fff';
		$image = @ $instance['image'] ?: plugins_url( 'images/vk.png', __FILE__ );
		$text = @ $instance['text'] ?: 'Vkontakte';
		$texthover = @ $instance['texthover'] ?: 'social network';
		?>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:','simple-social-widget' ); ?></label> 
			<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'link' ); ?>"><?php _e( 'Link:','simple-social-widget' ); ?></label> 
			<input class="widefat" id="<?php echo $this->get_field_id( 'link' ); ?>" name="<?php echo $this->get_field_name( 'link' ); ?>" type="text" value="<?php echo esc_attr( $link ); ?>">
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'border' ); ?>"><?php _e( 'Color of border:','simple-social-widget' ); ?></label> 
			<input class="widefat" id="<?php echo $this->get_field_id( 'border' ); ?>" name="<?php echo $this->get_field_name( 'border' ); ?>" type="text" value="<?php echo esc_attr( $border ); ?>">
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'background' ); ?>"><?php _e( 'Color of background:','simple-social-widget' ); ?></label> 
			<input class="widefat" id="<?php echo $this->get_field_id( 'background' ); ?>" name="<?php echo $this->get_field_name( 'background' ); ?>" type="text" value="<?php echo esc_attr( $background ); ?>">
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'color' ); ?>"><?php _e( 'Color of text:','simple-social-widget' ); ?></label> 
			<input class="widefat" id="<?php echo $this->get_field_id( 'color' ); ?>" name="<?php echo $this->get_field_name( 'color' ); ?>" type="text" value="<?php echo esc_attr( $color ); ?>">
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'image' ); ?>"><?php _e( 'Image link:','simple-social-widget' ); ?></label> 
			<input class="widefat" id="<?php echo $this->get_field_id( 'image' ); ?>" name="<?php echo $this->get_field_name( 'image' ); ?>" type="text" value="<?php echo esc_attr( $image ); ?>">
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'text' ); ?>"><?php _e( 'Text:','simple-social-widget' ); ?></label> 
			<input class="widefat" id="<?php echo $this->get_field_id( 'text' ); ?>" name="<?php echo $this->get_field_name( 'text' ); ?>" type="text" value="<?php echo esc_attr( $text ); ?>">
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'texthover' ); ?>"><?php _e( 'Text on hover:','simple-social-widget' ); ?></label> 
			<input class="widefat" id="<?php echo $this->get_field_id( 'texthover' ); ?>" name="<?php echo $this->get_field_name( 'texthover' ); ?>" type="text" value="<?php echo esc_attr( $texthover ); ?>">
		</p>
		<?php 
	}

	function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
		$instance['link'] = ( ! empty( $new_instance['link'] ) ) ? strip_tags( $new_instance['link'] ) : '';
		$instance['border'] = ( ! empty( $new_instance['border'] ) ) ? strip_tags( $new_instance['border'] ) : '';
		$instance['background'] = ( ! empty( $new_instance['background'] ) ) ? strip_tags( $new_instance['background'] ) : '';
		$instance['color'] = ( ! empty( $new_instance['color'] ) ) ? strip_tags( $new_instance['color'] ) : '';
		$instance['image'] = ( ! empty( $new_instance['image'] ) ) ? strip_tags( $new_instance['image'] ) : '';
		$instance['text'] = ( ! empty( $new_instance['text'] ) ) ? strip_tags( $new_instance['text'] ) : '';
		$instance['texthover'] = ( ! empty( $new_instance['texthover'] ) ) ? strip_tags( $new_instance['texthover'] ) : '';

		return $instance;
	}

} 

function register_sww_widget_wp() {
	register_widget( 'ssw_Widget' );
}
add_action( 'widgets_init', 'register_sww_widget_wp' );

add_action( 'plugins_loaded', 'lang_load_plugin_ssw' );
 
function lang_load_plugin_ssw() {
	load_plugin_textdomain( 'simple-social-widget', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' ); 
}

function add_styls_widget_ssw() {
	wp_enqueue_style( 'style-dict', plugin_dir_url(__FILE__ ). 'css/style.css' );
}
add_action( 'wp_enqueue_scripts', 'add_styls_widget_ssw' );