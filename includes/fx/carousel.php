<?php
if (!function_exists('spark_generate_carousel')) {
	function spark_generate_carousel(array $slides, $return = false) {
		$template = <<<EOH
<div class="orbit" role="region" data-orbit>
	<div class="orbit-wrapper">
		<div class="orbit-controls">
			<button class="orbit-previous"><span class="show-for-sr">Previous Slide</span>&#9664;&#xFE0E;</button>
			<button class="orbit-next"><span class="show-for-sr">Next Slide</span>&#9654;&#xFE0E;</button>
		</div>
		<ul class="orbit-container">
			%s
		</ul>
	</div>
	<nav class="orbit-bullets">
		%s
	</nav>
</div>
EOH;

		$orbit_slides = '';
		$orbit_bullets = '';
		$i = 0;
		foreach ($slides as $slide) {
			$orbit_slides .= '<li class="orbit-slide">'.$slide.'</li>';
			$orbit_bullets .= '<button data-slide="'.$i++.'"></button>';
		}

		$output = sprintf($template, $orbit_slides, $orbit_bullets);

		if ($return) {
			return $output;
		} else {
			echo $output;
		}
	}
}
