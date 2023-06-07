<?php
if (!function_exists('spark_get_card')) {
	/**
	 * Generate a card
	 * @param integer|array|string $args
	 * @return void|string
	 */
	function spark_get_card($args, $echo = true) {
		if (!is_array($args)) {
			parse_str($args, $args);
		}
		extract($args);

		// set defaults
		if (is_int($args)) {
			$ID = $args;
		}
		if (!is_array($args) && !strstr($args, '&')) {
			$card = $args;
		}
		if (!isset($card) && !isset($ID)) {
			return;
		}
		if (!isset($ID)) {
			$ID = $card;
		}
		if (!isset($card)) {
			$card = 'post-preview';
		}

		ob_start();

		/* translators: %s: ID of the card being displayed. */
		echo '<!-- '.sprintf(__('START Card %s'), $ID).' -->'."\n";

		// Need to grab the template file and include it ourselves so that our variables get passed through
		$template_files = array(
				'templates/cards/'.$card.'.php',
		);
		if (is_int($ID)) {
			$post_type = get_post_type($ID);
			array_unshift($template_files, 'templates/cards/'.$card.'-'.$post_type.'.php');
		}
		$card_template = locate_template($template_files, false, false);
		if ($card_template) {
			include($card_template);
		}

		/* translators: %s: ID of the card being displayed. */
		echo '<!-- '.sprintf(__('END Card %s'), $ID).' -->'."\n";

		$ob = ob_get_clean();

		if ($echo) {
			echo $ob;
		} else {
			return $ob;
		}
	}
}
