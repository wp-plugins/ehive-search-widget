<?php
/*
	Plugin Name: eHive Search widget
	Plugin URI: http://developers.ehive.com/wordpress-plugins/
	Author: Vernon Systems Limited
	Description: Displays an eHive search form. The <a href="http://developers.ehive.com/wordpress-plugins#ehiveaccess" target="_blank">eHiveAccess plugin</a> must be installed.
	Version: 2.1.1
	Author URI: http://vernonsystems.com
	License: GPL2+
*/
/*
	Copyright (C) 2012 Vernon Systems Limited

	This program is free software; you can redistribute it and/or
	modify it under the terms of the GNU General Public License
	as published by the Free Software Foundation; either version 2
	of the License, or (at your option) any later version.
	
	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.
	
	You should have received a copy of the GNU General Public License
	along with this program; if not, write to the Free Software
	Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
*/

add_action( 'widgets_init', 'ehive_search_widget' );

function ehive_search_widget() {
	register_widget( 'EHiveSearch_Widget' );
}

class eHiveSearch_Widget extends WP_Widget {

	public function __construct() {
		parent::__construct('ehivesearch_widget',
				'eHive Search',
				array( 'description' => __('Displays an eHive search form.', 'text_domain'))
		);
	}

	function widget($args, $instance) {

		if (isset($instance['widget_css_enabled'])) {
			wp_register_style($handle = 'eHiveSearchWidgetCSS', $src = plugins_url('eHiveSearch_Widget.css', '/ehive-search-widget/css/eHiveSearch_Widget.css'), $deps = array(), $ver = '0.0.1', $media = 'all');
			wp_enqueue_style( 'eHiveSearchWidgetCSS');
		}

		echo $args['before_widget'];
		echo $args['before_title'] . apply_filters('widget_title', $instance['title']) . $args['after_title'];

		
		global $eHiveAccess, $eHiveSearch;

		$eHiveApi = $eHiveAccess->eHiveApi();	
		$searchOptions = $eHiveSearch->getSearchOptions();
		
		$siteType = $eHiveAccess->getSiteType();
		$accountId = $eHiveAccess->getAccountId();
		$communityId = $eHiveAccess->getCommunityId();
		
		echo '<form class="ehive-search-widget" name="ehive-search-form" action="'. $eHiveAccess->getSearchPageLink() .'" method="get">';
			echo '<input class="field" type="text" name="'. $searchOptions['query_var'] .'" value="" placeholder="' . $instance['placeholder_text'] .'"/>';
			echo '<input class="submit" type="submit" value="Search"/>';
		echo '</form>';

		echo $args['after_widget'];
	}

	function update($new_instance, $old_instance) {
		$instance = $old_instance;

		$instance['title'] = $new_instance['title'];
		$instance['placeholder_text'] = $new_instance['placeholder_text'];
		$instance['widget_css_enabled'] = $new_instance['widget_css_enabled'];
		$instance['css_class'] = $new_instance['css_class'];

		return $instance;
	}

	function form($instance) {

		$defaults = array(
				'title' => 'eHive Search',
				'placeholder_text' => 'Search Collection',
				'widget_css_enabled' => true,
				'css_class' => '');

		$instance = wp_parse_args( $instance, $defaults );

		?>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label>
			<input class="widefat" type="text" value="<?php echo $instance['title']; ?>" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'placeholder_text' ); ?>"><?php _e( 'Placeholder Text:' ); ?></label>
			<input class="widefat" type="text" value="<?php echo $instance['placeholder_text']; ?>" id="<?php echo $this->get_field_id( 'placeholder_text' ); ?>" name="<?php echo $this->get_field_name( 'placeholder_text' ); ?>" />
		</p>			
		<hr class="div"/>				
        <p>
	        <input class="checkbox" type="checkbox" value="1" <?php checked( $instance['widget_css_enabled'], true ); ?> id="<?php echo $this->get_field_id('widget_css_enabled'); ?>" name = "<?php echo $this->get_field_name('widget_css_enabled'); ?>" />
			<label for="<?php echo $this->get_field_id('widget_css_enabled'); ?>"><?php _e( 'Enable widget stylesheet' ); ?></label>        
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'css_class' ); ?>"><?php _e( 'Custom CSS Class:' ); ?></label>
			<input class="widefat" type="text" value="<?php echo $instance['css_class']; ?>" id="<?php echo $this->get_field_id( 'css_class' ); ?>" name="<?php echo $this->get_field_name( 'css_class' ); ?>" />
		</p>				
		<?php 		
	}
}