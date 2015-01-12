<?php
/*
Plugin Name: Icono - Pure css icons
Plugin URI: http://status301.net/wordpress-plugins/icono/
Description: Add the Icono pure css icons stylesheet to your WordPress site.
Author: RavanH, Saeed Alipoor
Version: 0.2
Author URI: http://saeedalipoor.github.io/icono/

Credits:
	The Font Awesome icon set was created by Saeed Alipoor http://git.io/icono

License:
  Copyright (C) 2015  Rolf Allard van Hagen
	This program is free software: you can redistribute it and/or modify
	it under the terms of the GNU General Public License as published by
	the Free Software Foundation, either version 3 of the License, or
	(at your option) any later version.
	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.
	You should have received a copy of the GNU General Public License
	along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/

/** 
 * SCRIPT
 * 
 * since v 0.1
 */
function icono_enqueue_scripts()
{
	wp_enqueue_style( 'icono-style', 'http://saeedalipoor.github.io/icono/icono.min.css' );
}
add_action( 'wp_enqueue_scripts', 'icono_enqueue_scripts' );

/** 
 * SHORTCODE
 * 
 * since v 0.1
 */
function icono_shortcode( $atts ) 
{
	if ( is_feed() )
		return '';

	// filter icon name
	if ( array_key_exists('name',$atts) )
		$name = $atts['name'];
	elseif ( array_key_exists('icono',$atts) )
		$name = $atts['icono'];
	else
		$name = isset($atts[0]) ? $atts[0] : 'home';
		
	// filter styles
	$color = array_key_exists('color',$atts) ? 'color:'.$atts['color'] : '';
	if ( array_key_exists('style',$atts) )
		$style = ' style="'.$atts['style'].$color.'"';
	elseif ( !empty($color) )
		$style = ' style="'.$color.'"';
	else	
		$style = '';

	return '<i class="icono ' . ( strpos($name,'icono-') === 0 ? $name : 'icono-'.$name ) . '"' . $style . '></i>';	
}
add_shortcode( 'icon', 'icono_shortcode' );
add_shortcode( 'icono', 'icono_shortcode' );

// allow shortcode in text widgets
add_filter('widget_text', 'do_shortcode');

// TODO: add tinymce button for easier icon creation
