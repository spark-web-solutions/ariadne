<?php
add_filter('oembed_dataparse', 'spark_wrap_oembed', 99, 3);
if (!function_exists('spark_wrap_oembed')) {
	function spark_wrap_oembed($return, $data, $url) {
		if ($data->type == 'video') {
			$mod = '';
			if (isset($data->width) && isset($data->height)) {
				if (round($data->width/$data->height) == round(16/9)) {
					$mod .= ' widescreen';
				} elseif ($data->width == $data->height) {
					$mod .= ' square';
				} elseif (round($data->height/$data->width) == round(16/9)) {
					$mod .= ' vertical';
				} elseif (round($data->width/$data->height) == round(256/81)) {
					$mod .= ' panorama';
				}
			}
			if (strpos($url, 'vimeo') !== false) {
				$mod .= ' vimeo';
			}
			$return = '<div class="responsive-embed '.$mod.'">'.$return.'</div>';
		}

		return $return;
	}
}
