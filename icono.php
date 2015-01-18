<?php
/*
Plugin Name: Icono - Pure CSS icons
Plugin URI: http://status301.net/wordpress-plugins/icono/
Description: Add the Icono pure CSS icons by Saeed Alipoor to your WordPress site. Use shortcode [icon name] in posts and text widgets. See http://git.io/icono for available icons and their names.
Author: RavanH, Saeed Alipoor
Version: 0.3
Author URI: http://saeedalipoor.github.io/icono/

Credits:
	The Icono pure CSS icons set was created by Saeed Alipoor http://git.io/icono

Plugin License:
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


Icono stylesheet license:
  Copyright (c) 2014-2015 Saeed Alipoor
	Permission is hereby granted, free of charge, to any person obtaining a copy
	of this software and associated documentation files (the "Software"), to deal
	in the Software without restriction, including without limitation the rights
	to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
	copies of the Software, and to permit persons to whom the Software is
	furnished to do so, subject to the following conditions:

	The above copyright notice and this permission notice shall be included in all
	copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.
*/

/** 
 * STYLES
 * 
 * since v 0.1
 */
function icono_enqueue_scripts()
{
	wp_enqueue_style( 'icono-style', 'http://saeedalipoor.github.io/icono/icono.min.css' );
}
add_action( 'wp_enqueue_scripts', 'icono_enqueue_scripts' );

function icono_styles_compat() {
	echo '
<style type="text/css">
.icon:before, .icon:after {
	box-sizing: content-box;
}
</style>
';
}
add_action( 'wp_head', 'icono_styles_compat' );

/** 
 * SHORTCODE
 * 
 * since v 0.1
 */
function icono_shortcode( $atts ) 
{
	// not in feeds
	if ( is_feed() )
		return '';
		
	$atts = (array)$atts;

	// filter icon name
	if ( array_key_exists('name',$atts) )
		$name = esc_attr($atts['name']);
	elseif ( array_key_exists('icono',$atts) )
		$name = esc_attr($atts['icono']);
	else
		$name = isset($atts[0]) ? esc_attr($atts[0]) : 'heart';
		
	// filter styles
	$styles = array();
	$style = '';

	if ( array_key_exists('style',$atts) ) {
		foreach ( explode( ';', $atts['style'] ) as $key => $value ) {
			$pair = explode( ':', $value );
			if ( isset($pair[1]) )
				$styles = array_merge( $styles, array( esc_attr($pair[0]) => esc_attr($pair[1]) ) );
		}
	}
		
	if ( array_key_exists('color',$atts) )
		$styles['color'] = esc_attr($atts['color']);

	if ( array_key_exists('scale',$atts) )
		$styles['transform'] = isset($styles['transform']) ? $styles['transform'].' scale('.esc_attr($atts['scale']).')' : 'scale('.esc_attr($atts['scale']).')';

	if ( array_key_exists('rotate',$atts) )
		$styles['transform'] = isset($styles['transform']) ? $styles['transform'].' rotate('.esc_attr($atts['rotate']).'deg)' : 'rotate('.esc_attr($atts['rotate']).'deg)';

	if ( isset($styles['transform']) ) {
			$styles['-webkit-transform'] = $styles['transform']; // Opera
			$styles['-ms-transform'] = $styles['transform']; // IE9
	}
	
	// build style
	foreach ( $styles as $key => $value  )
		$style .= !empty($value) ? trim($key).':'.trim($value).';' : '';

	return '<i class="icon ' . ( strpos($name,'icono-') === 0 ? $name : 'icono-'.$name ) . '"' . ( !empty($style) ? ' style="'.$style.'"' : '' ) . '></i>';	
}
add_shortcode( 'icon', 'icono_shortcode' );
add_shortcode( 'icono', 'icono_shortcode' );

// allow shortcode in text widgets
add_filter('widget_text', 'do_shortcode');

// TODO: add tinymce button for easier icon creation
