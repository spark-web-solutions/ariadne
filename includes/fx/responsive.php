<?php
if (!function_exists('spark_interchange')) {
	/**
	 * Generates a Foundation Interchange HTML element
	 * @see https://get.foundation/sites/docs/interchange.html
	 * @param array|string $args
	 * @param boolean $echo
	 * @return void|string
	 */
	function spark_interchange($args, $echo = true) {
		if (!is_array($args)) {
			parse_str($args, $args);
		}
		extract($args);
		if (!$small || !$medium || !$large) {
			return;
		}
		if (empty($element)) {
			$element = 'img';
		}

		$html = '<'.$element.' data-interchange="['.$small.', small], ['.$medium.', medium], ['.$large.', large]" src="'.$large.'"';
		if (!empty($attrs)) {
			if (is_array($attrs)) {
				$attr_string = '';
				foreach ($attrs as $attr => $value) {
					$attr_string .= ' '.$attr.'="'.$value.'"';
				}
			} else {
				$attr_string = $attrs;
			}
			$html .= ' '.$attr_string;
		}
		$html .= '>';
		if ($echo) {
			echo $html."\n";
		} else {
			return $html;
		}
	}
}

if (!function_exists('spark_srcset')) {
	/**
	 * Generates an image element with srcset attribute
	 * @param array|string $args {
	 *     @type string     $src    Primary/fallback image source. Required.
	 *     @type string     $s      Image source for small screens. Defaults to value of $src.
	 *     @type string     $m      Image source for medium screens. Defaults to value of $src.
	 *     @type string     $l      Image source for large screens. Defaults to value of $src.
	 *     @type boolean    $echo   Whether to echo the result. Default true.
	 *     @type string     $id     Value of id attribute. Default is a randomly generated string.
	 *     @type string     $alt    Value of alt attribute. Default empty.
	 * }
	 * @return string|void HTML image element if $echo is false
	 */
	function spark_srcset($args) {
		$defaults = array(
				'id' => wp_generate_password(8, false),
				'alt' => '',
				'echo' => true,
		);
		$args = wp_parse_args($args, $defaults);

		if (empty($args['s'])) {
			$args['s'] = $args['src'];
		}
		if (empty($args['m'])) {
			$args['m'] = $args['src'];
		}
		if (empty($args['l'])) {
			$args['l'] = $args['src'];
		}

		$img = '<img id='.$args['id'].' src="'.$args['src'].'" srcset="'.$args['s'].' 640w, '.$args['m'].' 1024w, '.$args['l'].' 1600w" alt="'.$args['alt'].'">';
		if ($echo) {
			echo $img;
		} else {
			return $img;
		}
	}
}
