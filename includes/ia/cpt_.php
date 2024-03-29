<?php
namespace Spark_Theme;
class cptClass {
	function __construct($singular, $plural, array $args = array(), $slug = '') {
		$this->plural = $plural;
		$this->singular = $singular;
		$this->slug = !empty($slug) ? $slug : str_replace(' ', '', strtolower($singular));
		$this->args = $args;

		add_action('init', array($this, 'register_cpt'));
		add_filter('post_updated_messages', array($this, 'messages'));
	}

	function register_cpt() {
		$labels = array(
				'name' => _x(ucwords($this->plural), 'Post type general name', 'spark_theme'),
				'singular_name' => _x(ucwords($this->singular), 'Post type singular name', 'spark_theme'),
				'add_new' => _x('Add New', ucwords($this->singular), 'spark_theme'),
				'add_new_item' => __('Add New '.ucwords($this->singular), 'spark_theme'),
				'edit_item' => __('Edit '.ucwords($this->singular), 'spark_theme'),
				'new_item' => __('New '.ucwords($this->singular), 'spark_theme'),
				'view_item' => __('View '.ucwords($this->singular), 'spark_theme'),
				'view_items' => __('View '.ucwords($this->plural), 'spark_theme'),
				'search_items' => __('Search '.ucwords($this->plural), 'spark_theme'),
				'not_found' => __('No '.ucwords($this->plural).' found', 'spark_theme'),
				'not_found_in_trash' => __('No '.ucwords($this->plural).' found in the Trash', 'spark_theme'),
				'parent_item_colon' => __('Parent '.ucwords($this->singular), 'spark_theme'),
				'all_items' => __('All '.ucwords($this->plural), 'spark_theme'),
				'archives' => __(ucwords($this->plural).' Archives', 'spark_theme'),
				'attributes' => __(ucwords($this->singular).' Attributes', 'spark_theme'),
				'insert_into_item' => __('Insert into '.ucwords($this->singular), 'spark_theme'),
				'uploaded_to_this_item' => __('Uploaded to this '.ucwords($this->singular), 'spark_theme'),
				'filter_items_list' => __('Filter '.ucwords($this->plural).' list', 'spark_theme'),
				'items_list_navigation' => __(ucwords($this->plural).' list navigation', 'spark_theme'),
				'items_list' => __(ucwords($this->plural).' list', 'spark_theme'),
				'item_published' => __(ucwords($this->singular).' published', 'spark_theme'),
				'item_published_privately' => __(ucwords($this->singular).' published privately', 'spark_theme'),
				'item_reverted_to_draft' => __(ucwords($this->singular).' reverted to draft', 'spark_theme'),
				'item_scheduled' => __(ucwords($this->singular).' scheduled', 'spark_theme'),
				'item_updated' => __(ucwords($this->singular).' updated', 'spark_theme'),
		);

		$default_args = array(
				'labels' => $labels,
				'description' => 'Holds our ' . ucwords($this->singular) . ' posts',
				'public' => true,
				'menu_position' => 20,
				'supports' => array(
						'title',
						'editor',
						'thumbnail',
						'excerpt',
						'comments',
						'page-attributes',
				),
				'has_archive' => true,
				'show_in_rest' => true,
				'hierarchical' => true,
		);

		$args = array_replace_recursive($default_args, $this->args);

		register_post_type($this->slug, $args);
	}

	function messages($messages) {
		global $post, $post_type_object, $post_ID;

		$permalink = get_permalink($post_ID);
		if (!$permalink) {
			$permalink = '';
		}

		$preview_post_link_html   = '';
		$scheduled_post_link_html = '';
		$view_post_link_html      = '';
		$preview_url = get_preview_post_link($post);
		$viewable = is_post_type_viewable($post_type_object);

		if ($viewable) {
			// Preview post link.
			$preview_post_link_html = sprintf(
					' <a target="_blank" href="%1$s">%2$s</a>',
					esc_url( $preview_url ),
					__( 'Preview post', 'spark_theme' )
			);

			// Scheduled post preview link.
			$scheduled_post_link_html = sprintf(
					' <a target="_blank" href="%1$s">%2$s</a>',
					esc_url( $permalink ),
					__( 'Preview post', 'spark_theme' )
			);

			// View post link.
			$view_post_link_html = sprintf(
					' <a href="%1$s">%2$s</a>',
					esc_url( $permalink ),
					__( 'View post', 'spark_theme' )
			);
		}

		$scheduled_date = sprintf(
				/* translators: Publish box date string. 1: Date, 2: Time. */
				__( '%1$s at %2$s', 'spark_theme' ),
				/* translators: Publish box date format, see https://www.php.net/manual/datetime.format.php */
				date_i18n( _x( 'M j, Y', 'publish box date format', 'spark_theme' ), strtotime( $post->post_date ) ),
				/* translators: Publish box time format, see https://www.php.net/manual/datetime.format.php */
				date_i18n( _x( 'H:i', 'publish box time format', 'spark_theme' ), strtotime( $post->post_date ) )
		);

		$singular = $post_type_object->labels->singular_name;
		$messages[$post_type_object->name] = array(
				0 => '', // Unused. Messages start at index 1.
				1 => __($singular.' updated.', 'spark_theme').$view_post_link_html,
				2 => __('Custom field updated.', 'spark_theme'),
				3 => __('Custom field deleted.', 'spark_theme'),
				4 => __($singular.' updated.', 'spark_theme'),
				/* translators: %s: Date and time of the revision */
				5 => isset($_GET['revision']) ? sprintf(__($singular.' restored to revision from %s', 'spark_theme'), wp_post_revision_title((int)$_GET['revision'], false)) : false,
				6 => __($singular.' published.', 'spark_theme').$view_post_link_html,
				7 => __($singular.' saved.', 'spark_theme'),
				8 => __($singular.' submitted.', 'spark_theme').$preview_post_link_html,
				/* translators: %s: Scheduled date for the post. */
				9 => sprintf(__($singular.' scheduled for: %s.', 'spark_theme'), '<strong>'.$scheduled_date.'</strong>' ).$scheduled_post_link_html,
				10 => __($singular.' draft updated.', 'spark_theme').$preview_post_link_html,
		);

		return $messages;
	}
}
