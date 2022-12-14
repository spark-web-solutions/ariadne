<?php
if (!function_exists('spark_convert_colour')) {
	/**
	 * Converts colours from RGB to Hex and vice-versa
	 * @param string|array $colour Colour as hex string or RGB array
	 * @param string $as Optional. Defines the type of value returned. Accepted values are 'string' or 'array' (Hex to RBG only).
	 * @return string|array
	 */
	function spark_convert_colour($colour, $as = 'string') {
		// Make sure we've got the right data
	    if (strpos($colour, ',')) {
	        $colour = explode(',', $colour);
	    }
	    if ((is_string($colour) && strpos($colour, '#') === false) || (is_array($colour) && count($colour) != 3)) {
	        return;
	    }
	    if ('string' != $as && 'array' != $as) {
	        $as = 'string';
	    }

	    if (is_string($colour)) {
		    // Convert Hex to RGB
	        $hex = str_replace('#', '', $colour);

	        if (strlen($hex) == 3) {
	            $r = hexdec(substr($hex, 0, 1).substr($hex, 0, 1));
	            $g = hexdec(substr($hex, 1, 1).substr($hex, 1, 1));
	            $b = hexdec(substr($hex, 2, 1).substr($hex, 2, 1));
	        } else {
	            $r = hexdec(substr($hex, 0, 2));
	            $g = hexdec(substr($hex, 2, 2));
	            $b = hexdec(substr($hex, 4, 2));
	        }
	        $rgb = array(
	                $r,
	                $g,
	                $b,
	        );

	        if ($as == 'string') {
	            return implode(',', $rgb); // Returns the rgb values separated by commas
	        }
	        if ($as == 'array') {
	            return $rgb; // Returns an array with the rgb values
	        }
	    } else {
		    // Convert RGB to Hex
	        $hex = '#';
	        $hex .= str_pad(dechex($colour[0]), 2, '0', STR_PAD_LEFT);
	        $hex .= str_pad(dechex($colour[1]), 2, '0', STR_PAD_LEFT);
	        $hex .= str_pad(dechex($colour[2]), 2, '0', STR_PAD_LEFT);

	        return $hex; // Returns the hex value including the hash
	    }
	    return false;
	}
}

if (!function_exists('spark_colour_opacity')) {
	/**
	 * Convert a hex colour to a semi-transparent RGBA
	 * @param string $hex
	 * @param float $opacity
	 * @return string
	 */
	function spark_colour_opacity($hex, $opacity) {
	    $rgba = 'rgba('.spark_convert_colour($hex).', '.$opacity.')';
	    return $rgba;
	}
}

if (!function_exists('spark_colour_darker')) {
	/**
	 * Darken a colour
	 * @param string|array $colour Colour as hex string or RGB array
	 * @param number $change Amount to change colour by. Default 30.
	 * @param boolean $echo Whether to echo the result. Default true.
	 * @return array|null RGB array or null, based on value of $echo
	 */
	function spark_colour_darker($colour, $change = 30, $echo = true) {
	    return spark_colour_change($colour, -($change), $echo);
	}
}

if (!function_exists('spark_colour_lighter')) {
	/**
	 * Lighten a colour
	 * @param string|array $colour Colour as hex string or RGB array
	 * @param number $change Amount to change colour by. Default 30.
	 * @param boolean $echo Whether to echo the result. Default true.
	 * @return array|null RGB array or null, based on value of $echo
	 */
	function spark_colour_lighter($colour, $change = 30, $echo = true) {
	    return spark_colour_change($colour, $change, $echo);
	}
}

if (!function_exists('spark_colour_change')) {
	/**
	 * Make a colour lighter or darker
	 * @param string|array $colour Colour as hex string or RGB array
	 * @param number $change Amount to change colour by. Positive values will produce a lighter colour; negative will produce a darker colour. Default 30.
	 * @param boolean $echo Whether to echo the result. Default true.
	 * @return array|null RGB array or null, based on value of $echo
	 */
	function spark_colour_change($colour, $change = 30, $echo = true) {
	    if (!is_array($colour)) {
	        $colour = spark_convert_colour($colour, 'array');
	    }
	    for ($i = 0; $i < 3; $i++) {
	        $colour[$i] = $colour[$i] + $change;
	        if ($colour[$i] < 0) {
	            $colour[$i] = 0;
	        } elseif ($colour[$i] > 255) {
	            $colour[$i] = 255;
	        }
	    }
	    if ($echo) {
	        $colour = implode(',', $colour);
	        echo $colour;
	    } else {
	        return $colour;
	    }
	}
}
