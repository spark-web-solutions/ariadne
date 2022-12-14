<?php
/**
 * Auto-update logic for the theme
 *
 * @link	  https://sparkweb.com.au
 * @since	  2.0.0
 */

/**
 * Auto-update logic for the theme
 *
 * Handles checking for updates to the theme and installing them as required
 *
 * @author Spark Web Solutions <solutions@sparkweb.com.au>
 */
class Spark_Theme_Updates {
	private $slug;
	private $username;
	private $repo;
	private $github_api_result;
	private $access_token;

	/**
	 * Class constructor.
	 * @param string $github_username
	 * @param string $github_project_name
	 * @param string $access_token
	 * @return null
	 * @since 2.0.0
	 */
	function __construct($github_username, $github_project_name, $access_token = '') {
		add_filter("pre_set_site_transient_update_themes", array($this, "set_transient"));
		add_filter("themes_api", array($this, "set_theme_info"), 10, 3);
		add_filter("upgrader_post_install", array($this, "post_install"), 10, 3);

		$this->username = $github_username;
		$this->repo = $github_project_name;
		$this->access_token = $access_token;
	}

	/**
	 * Get information regarding our theme from WordPress
	 * @return null
	 * @since 2.0.0
	 */
	private function init_theme_data() {
		$this->slug = basename(dirname(dirname(__FILE__)));
	}

	/**
	 * Get information regarding our theme from GitHub
	 * @return null
	 * @since 2.0.0
	 */
	private function get_repo_release_info() {
		if (!empty($this->github_api_result)) {
			return true;
		}

		$transient_name = 'github_'.$this->username.'/'.$this->repo;
		$result = get_transient($transient_name);
		if ($result === false) {
			// Query the GitHub API
			$url = "https://api.github.com/repos/{$this->username}/{$this->repo}/releases";

			if (!empty($this->access_token)) {
				$url = add_query_arg(array("access_token" => $this->access_token), $url);
			}

			// Get the results
			$result = wp_remote_retrieve_body(wp_remote_get($url));

			if (!empty($result)) {
				$result = @json_decode($result);

				// Use only the latest release
				if (is_array($result)) {
					foreach ($result as $release) {
// 						if (!$release->prerelease) {
							$this->github_api_result = $release;
							set_transient($transient_name, $this->github_api_result, 15*MINUTE_IN_SECONDS);
							return true;
// 						}
					}
				}
			}
		} else {
			$this->github_api_result = $result;
			return true;
		}
		return false;
	}

	/**
	 * Push in theme version information to get the update notification
	 * @param object $transient
	 * @return object
	 * @since 21.0.0
	 */
	public function set_transient($transient) {
		if (empty($transient->checked)) {
			return $transient;
		}

		// Get theme & GitHub release information
		$this->init_theme_data();
		if ($this->get_repo_release_info()) {
			$doUpdate = version_compare($this->github_api_result->tag_name, $transient->checked[$this->slug], '>');

			if ($doUpdate) {
				$package = $this->github_api_result->zipball_url;

				if (!empty($this->access_token)) {
					$package = add_query_arg(array("access_token" => $this->access_token), $package);
				}

				// Theme details
				$theme_details = array(
						'theme' => $this->slug,
						'new_version' => $this->github_api_result->tag_name,
						'package' => $package,
						'requires' => false,
						'requires_php' => false,
				);

				// Gets the required version of WP if available
				$matches = null;
				preg_match("/requires at least:\s([\d\.]+)/i", $this->github_api_result->body, $matches);
				if (!empty($matches)) {
					if (is_array($matches)) {
						if (count($matches) > 1) {
							$theme_details['requires'] = $matches[1];
						}
					}
				}

				// Gets the required version of PHP if available
				$matches = null;
				preg_match("/requires php:\s([\d\.]+)/i", $this->github_api_result->body, $matches);
				if (!empty($matches)) {
					if (is_array($matches)) {
						if (count($matches) > 1) {
							$theme_details['requires_php'] = $matches[1];
						}
					}
				}

				$transient->response[$this->slug] = $theme_details;
			}
		}

		return $transient;
	}

	/**
	 * Push in theme version information to display in the details lightbox
	 * @param false|object|array $override
	 * @param string $action
	 * @param object $args
	 * @return object
	 * @since 2.0.0
	 */
	public function set_theme_info($override, $action, $args) {
		$this->init_theme_data();

		if (empty($args->slug) || $args->slug != $this->slug || $action != 'theme_information') {
			return $override;
		}

		if ($this->get_repo_release_info()) {
			// Add our theme information
			$args->last_updated = $this->github_api_result->created_at;
			$args->theme = $this->slug;
			$args->version = $this->github_api_result->tag_name;
			//		 $args->author = $this->themeData["AuthorName"];
			//		 $args->homepage = $this->themeData["ThemeURI"];

			// This is our release download zip file
			$downloadLink = $this->github_api_result->zipball_url;

			if (!empty($this->access_token)) {
				$downloadLink = add_query_arg(array("access_token" => $this->access_token), $downloadLink);
			}

			$args->download_link = $downloadLink;

			// Create tabs in the lightbox
			$args->sections = array(
			//				 'Description' => $this->themeData["Description"],
					'Changelog' => nl2br($this->github_api_result->body),
			);

			// Gets the required version of WP if available
			$matches = null;
			preg_match("/requires at least:\s([\d\.]+)/i", $this->github_api_result->body, $matches);
			if (!empty($matches)) {
				if (is_array($matches)) {
					if (count($matches) > 1) {
						$args->requires = $matches[1];
					}
				}
			}

			// Gets the tested version of WP if available
			$matches = null;
			preg_match("/requires php:\s([\d\.]+)/i", $this->github_api_result->body, $matches);
			if (!empty($matches)) {
				if (is_array($matches)) {
					if (count($matches) > 1) {
						$args->requires_php = $matches[1];
					}
				}
			}
		}

		return $args;
	}

	/**
	 * Perform additional actions to successfully install our theme
	 * @param boolean $true
	 * @param string $hook_extra
	 * @param object $result
	 * @return object
	 * @since 2.0.0
	 */
	public function post_install($true, $hook_extra, $result) {
		global $wp_filesystem;

		$this->init_theme_data();
		if (!empty($hook_extra['theme']) && $hook_extra['theme'] == $this->slug) {
			// Since we are hosted in GitHub, our theme folder would have a dirname of reponame-tagname
			// So we need to change it back to our original one:
			$themeFolder = WP_CONTENT_DIR.DIRECTORY_SEPARATOR.'themes'.DIRECTORY_SEPARATOR.$this->slug;
			$wp_filesystem->move($result['destination'], $themeFolder);
			$result['destination'] = $themeFolder;
			$result['destination_name'] = $this->slug;
		}

		return $result;
	}
}
