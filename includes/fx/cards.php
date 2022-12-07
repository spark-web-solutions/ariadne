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
			$card = 'search';
		}
		if (!isset($max)) {
			$max = 70;
		}
		if (!empty($type)) {
			$card .= '.'.$type;
		}

		$transient = ns_.'card_'.$ID.'_'.$card;
		if (!empty($string)) {
			$transient .= '_'.$string;
		}
		if (false === ($ob = get_transient($transient)) || !Spark_Transients::use_transients()) {
			ob_start();

			/* translators: %s: ID of the card being displayed. */
			echo '<!-- '.sprintf(__('START Card %s'), $ID).' -->'."\n";

			locate_template('templates/cards/'.$card.'.php', true, false);

			/* translators: %s: ID of the card being displayed. */
			echo '<!-- '.sprintf(__('END Card %s'), $ID).' -->'."\n";

			$ob = ob_get_clean();
			if (Spark_Transients::use_transients($ob)) {
				Spark_Transients::set($transient, $ob);
			}
		}

		if ($echo) {
			echo $ob;
		} else {
			return $ob;
		}
	}
}
