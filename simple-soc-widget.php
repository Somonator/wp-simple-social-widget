<?php
/*
	Plugin Name: Simple Social Widget
	Description: Beatiful social button for your site, without social Api.
	Version: 1.4
	Author: Somonator
	Author URI: mailto:somonator@gmail.com
	Text Domain: ssw
	Domain Path: /lang
*/

/*
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

class simple_soc_link extends WP_Widget {
	var $id_now = 0;

	function __construct() {
		parent::__construct('', __('Simple Social Widget', 'ssw'), [
			'description' => __('Constructor simple social buttons for your site, without social Api.', 'ssw')
		]);
	}

	public function get_field_html($translate, $type, $name, $val, $required = false) {
		$name_backup = $name;
		$name = $this->id_now && $name === 'title' ? $name : $this->id_now . '[' . $name . ']';
		$label_id = $this->get_field_id($name);		
		$translate = $required ? $translate . '*' : $translate;

		$name = $this->get_field_name($name);
		$val = esc_attr($val);		

		echo '<p>';
		echo '<label for="' . $label_id . '">' . $translate . '</label>';
		
		if ($type === 'text' || $type === 'url') {
			$required = $required ? 'required' : null;
			
			echo '<input id="' . $label_id . '" type="' . $type . '" name="' . $name . '" class="widefat" value="' . $val . '"' . $required . '>';
		} else if ($type === 'colorpicker') {			
			echo '<input data-colorpicker="' . $name_backup . '" id="' . $label_id . '" type="text" name="' . $name . '" class="widefat" value="' . $val . '"' . $required . '>';
		} else if ($type === 'img') {
			$default = plugin_dir_url(__FILE__) . 'src/images/default.png';
			$src = !empty($val) ? $val : $default;
			
			echo '<img data-default="' . $default . '" src="' . $src . '" alt="">';
			echo '<input type="hidden" name="' . $name . '" value="' . esc_attr($val) . '">';
			echo '<button class="button edit-image">' . __('Edit', 'ssw') . '</button>';
			echo '<button class="button delete-image"><span class="dashicons dashicons-no-alt"></span></button>';
		}
		
		echo '</p>';
	}

	public function form($instance) {
		$title = @ $instance['title'] ?:null;
		$count = empty($instance) ? 1 : count($instance) - 1; /* without title */
		
		echo '<div class="ssw">';
		echo $this->get_field_html( __('Title:', 'ssw'), 'text', 'title', $title);

		echo '<div class="forms">';

		for ($i = 1; $i <= $count; $i++) {
			$this->id_now = $i;
			$link = @ $instance[$i]['link']?:null;
			$background = @ $instance[$i]['background']?:null;
			$color = @ $instance[$i]['color']?:null;
			$image = @ $instance[$i]['image']?:null;
			$text = @ $instance[$i]['text']?:null;
			$texthover = @ $instance[$i]['texthover']?:null;
			$show_del = $count == 1 ? 'style="display: none;"' :null;
			
			echo '<div class="form">';

			echo '<div class="head">';
			echo '<div class="title">' . __('Button', 'ssw') . ' ' . $i . '</div>';
			echo '<div class="manage" ' . $show_del . '>';
			echo '<div class="move"><span class="dashicons dashicons-move"></span></div>';
			echo '<div class="delete"><span class="dashicons dashicons-no"></span></div>';					
			echo '</div>';
			echo '</div>';

			echo '<div class="content" style="display: none;">';
			echo $this->get_field_html(__('Link:', 'ssw'), 'url', 'link',  $link, true);
			echo $this->get_field_html(__('Color of background:', 'ssw'), 'colorpicker', 'background', $background);
			echo $this->get_field_html(__('Color of text:', 'ssw'), 'colorpicker', 'color',  $color);
			echo $this->get_field_html(__('Image:', 'ssw'), 'img', 'image', $image);
			echo $this->get_field_html(__('Text:', 'ssw'), 'text', 'text', $text, true);
			echo $this->get_field_html(__('Text on hover:', 'ssw'), 'text', 'texthover',  $texthover);
			echo '</div>';

			echo '</div>';
		}
		
		echo '</div>';

		echo '<button class="button show-settings add-new">' . __('Add new', 'ssw') . '</button>';		
		echo '</div>';
	}

	public function update($new_instance, $old_instance) {
		if (isset($new_instance[0]['title'])) { // save title in your place
			$new_instance['title'] = $new_instance[0]['title'];
			unset($new_instance[0]);
		} else {
			$new_instance['title'] = '';
		}

		return $new_instance;
	}
	
	public function widget($args, $instance) {
		$title = apply_filters('widget_title', $instance['title']);

		echo $args['before_widget'];
		
		if (!empty($title)) {
			echo $args['before_title'] . $title . $args['after_title'];
		}
		
		echo '<div class="ssw">';
			
		for ($i = 1; $i <= count($instance) - 1; $i++) {
			$bg = !empty($instance[$i]['background']) ? 'background: ' . $instance[$i]['background'] . ';' : null;
			$color = !empty($instance[$i]['color']) ? 'color: ' . $instance[$i]['color'] . ';' : null;
			$no_image = empty($instance[$i]['image']) ? ' no-image' : null;
			$texthover = !empty($instance[$i]['texthover']) ? ' hover' : null;
			
			echo '<div class="ssw-button" style="' . $bg . '">';
			echo '<a href="' . $instance[$i]['link'] . '" target="_blank" class="link" style="' . $color . '">';

			if (!empty($instance[$i]['image'])) {
				echo '<div class="img-box">';
				echo '<img  src="' . $instance[$i]['image'] . '" alt="">';				
				echo '</div>';
			}
			
			echo '<div class="content' . $no_image . $texthover . '">';
			echo '<div class="text">' .$instance[$i]['text'] . '</div>';
			
			if (!empty($instance[$i]['texthover'])) {
				echo '<div class="text_hover">' . $instance[$i]['texthover'] . '</div>';
			}
				
			echo '</div>';
			echo '</a>';	
			echo '</div>';
		}
		
		echo '</div>';
	
		echo $args['after_widget'];
	}
}

class ssw_activate_all {
	function __construct() {
		add_action('admin_enqueue_scripts', [$this, 'admin_scripts']);
		
		if (is_active_widget(false, false, 'simple_soc_link') || is_customize_preview()) {
			add_action('wp_enqueue_scripts', [$this, 'scripts']);
		}
		
		add_action('plugins_loaded', [$this, 'lang']);
		add_action('widgets_init', [$this, 'init']);
	}

	public function admin_scripts($page) {
		if ($page == 'widgets.php') {
			wp_enqueue_style('ssw-admin-styles', plugin_dir_url(__FILE__) . 'src/css/ssw-admin-styles.css');			
			wp_enqueue_script('wp-color-picker');
			wp_enqueue_style('wp-color-picker');
			wp_enqueue_media();
			wp_enqueue_script('ssw-admin-scripts', plugin_dir_url(__FILE__) . 'src/js/ssw-admin-scripts.js', ['jquery']);
		}
	}	

	public function scripts() {
		wp_enqueue_style('ssw-styles', plugin_dir_url(__FILE__) . 'src/css/ssw-styles.css');
	}
	
	public function lang() {
		load_plugin_textdomain('ssw', false, dirname(plugin_basename( __FILE__ )) . '/lang/'); 
	}

	public function init() {
		register_widget('simple_soc_link');
	}	
}

/**
* Activate all instanses of plugin.
*/
new ssw_activate_all();