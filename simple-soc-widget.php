<?php
/*
	Plugin Name: Simple Social Widget
	Description: Beatiful social button for your site, without social Api.
	Version: 1.3.1
	Author: Somonator
	Author URI: mailto:somonator@gmail.com
	Text Domain: simple-social-widget
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

class simple_soc_button extends WP_Widget {
	function __construct() {
		parent::__construct('', __('Simple Social Widget', 'simple-social-widget'), array(
			'description' => __('Constructor simple social buttons for your site, without social Api.', 'simple-social-widget')
		));
	}

	public function form($instance) {
		$title = @ $instance['title']?:null;
		
		echo '<div class="ssw">';
			echo $this->get_field_html('title', 'text', '', $title, __('Title:', 'simple-social-widget'));
			echo '<div class="ssw-forms">';
				$count = empty($instance) ? 1 : count($instance) - 1;
				
				for ($i = 1; $i <= $count; $i++) {
					$link = @ $instance[$i]['link']?:null;
					$background = @ $instance[$i]['background']?:null;
					$color = @ $instance[$i]['color']?:null;
					$image = @ $instance[$i]['image']?:null;
					$text = @ $instance[$i]['text']?:null;
					$texthover = @ $instance[$i]['texthover']?:null;
					$show_del = $count == 1 ? 'style="display: none;"' :null;
					
					echo '<fieldset class="ssw-form">';
						echo '<legend>' . __('Button', 'simple-social-widget') . ' ' . $i . '</legend>';
						echo $this->get_field_html('link-' . $i, 'text', '^(?:http(s)?:\/\/)?[\w.-]+(?:\.[\w\.-]+)+[\w\-\._~:/?#[\]@!\$&\'\(\)\*\+,;=.]+$', $link, __('Link:*', 'simple-social-widget'), true);
						echo $this->get_field_html('background-' . $i, 'text', '', $background, __('Color of background:', 'simple-social-widget'));
						echo $this->get_field_html('color-' . $i, 'text', '', $color, __('Color of text:', 'simple-social-widget'));
						echo $this->get_field_html('image-' . $i, 'img', '', $image, __('Image:', 'simple-social-widget'));
						echo $this->get_field_html('text-' . $i, 'text', '', $text, __('Text:*', 'simple-social-widget'), true);
						echo $this->get_field_html('texthover-' . $i, 'text', '', $texthover, __('Text on hover:', 'simple-social-widget'));
						echo '<div class="dashicons delete" ' . $show_del . '></div>';
					echo '</fieldset>';
				}
				
				echo '<button class="button show-settings add-new">' . __('Add new', 'simple-social-widget') . '</button>';
			echo '</div>';
		echo '</div>';
	}
	
	public function get_field_html($name, $type, $pattern, $val, $translate, $required='') {
		echo '<p>';
		echo '<label for="' . $name . '-' . $this->number . '">' . $translate . '</label>';
		
		if ($type === 'text') {
			$pattern = !empty($pattern) ? ' pattern="' . $pattern . '" ' : null;
			$name = $this->get_field_name($name);
			$val = esc_attr($val);
			$required = $required ? 'required' : null;
			
			echo '<input type="' . $type . '"' . $pattern . 'name="' . $name . '" class="widefat" value="' . $val . '"' . $required . '>';
		} else if ($type === 'img') {
			$default = plugin_dir_url(__FILE__) . 'add/images/default.png';
			$src = !empty($val) ? $val : $default;
			
			echo '<img data-default="' . $default . '" src="' . $src . '" alt="">';
			echo '<input type="hidden" name="' . $this->get_field_name($name) . '" value="' . esc_attr($val) . '">';
			echo '<button class="button edit-image">' . __('Edit', 'simple-social-widget') . '</button>';
			echo '<button class="button delete-image"><span class="dashicons dashicons-no-alt"></span></button>';
		}
		
		echo '</p>';
	}

	public function update($new_instance, $old_instance) {
		foreach ($new_instance as $name => & $val) {
			if ($name == 'title') {
				$instance['title'] = $val;				
			} else {
				$i = intval(preg_replace('/[^0-9]+/', '', $name), 10);
				$new_name = substr($name, 0, -2);
				$instance[$i][$new_name] = $val; 
			}	
		}
		
		return $instance;
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

class ssw_includes {
	function __construct() {
		if (is_active_widget(false, false, 'simple_soc_button') || is_customize_preview()) {
			add_action('wp_enqueue_scripts', array($this, 'add_scripts'));
		}
		
		add_action('admin_enqueue_scripts', array($this, 'add_scripts_to_admin'));
		add_action('plugins_loaded', array($this, 'lang_load'));		
	}
	
	public function add_scripts() {
		wp_enqueue_style('ssw-styles', plugin_dir_url(__FILE__) . 'add/css/ssw-styles.css');
	}	
	
	public function add_scripts_to_admin($page) {
		if ($page == 'widgets.php') {
			wp_enqueue_style('ssw-admin-styles', plugin_dir_url(__FILE__) . 'add/css/ssw-admin-styles.css');			
			wp_enqueue_script('wp-color-picker');
			wp_enqueue_style('wp-color-picker');
			wp_enqueue_media();
			wp_enqueue_script('ssw-admin-scripts', plugin_dir_url(__FILE__) . 'add/js/ssw-admin-scripts.js', array('jquery'));
		}
	}
	
	public function lang_load() {
		load_plugin_textdomain('simple-social-widget', false, dirname(plugin_basename( __FILE__ )) . '/lang/'); 
	}	
}

class ssw_activate_all {
	function __construct() {
		add_action('widgets_init', array($this, 'register_widget'));
		new ssw_includes();
		register_activation_hook(__FILE__, array($this, 'update'));
		add_action('upgrader_process_complete', array($this, 'update'));
	}
	
	public function register_widget() {
		register_widget('simple_soc_button');
	}
	
	public function update($var = '') {
		$widgets = new simple_soc_button();
		$setting = $widgets->get_settings();
		
		if (isset($setting) && !empty($setting)) {
			foreach ($setting as $key => $value) {
				if (isset($setting[$key]['link']) && isset($setting[$key]['text'])) {
					$array = array();
					$array['title'] = $setting[$key]['title'];
					$array[1] = $value;
					unset($array[1]['title']);	
					
					$setting[$key] = $array;
				}
			}
			
			$widgets->save_settings($setting);
		}
	}
}

/**
* Activate all instanses of plugin.
*/
new ssw_activate_all();