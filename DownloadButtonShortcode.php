<?php
/**
 * Download Button Shortcode
 *
 * Adds a shortcode to your WordPress for a nice download button.
 *
 * @package Ppfeufer\Plugin\WordPressTweaks
 * @author H. Peter Pfeufer
 * @license GPL-3.0-or-later
 * @version 2.4
 *
 * @wordpress-plugin
 * Plugin name: Download Button Shortcode
 * Plugin URI: https://github.com/ppfeufer/download-button-shortcode
 * Author: H. Peter Pfeufer
 * Author URI: https://ppfeufer.de
 * Version: 2.4
 * Description: Adds a shortcode to your WordPress for a nice download button. <code>&#91;dl url="" title="" desc="" type="" align=""&#93;</code>. Graphics made by: Kai Köpke.
 * Text Domain: download-button-shortcode
 * Domain Path: /l10n
 */

namespace Ppfeufer\Plugin\DownloadButtonShortcode;

// phpcs:disable

/**
 * Constants should be namespace-aware.
 *
 * Constants defined via `define()` are not namespaced by default
 * and need to be namespaced manually.
 *
 * Constants defined via `const` are namespaced by default and
 * don't need any special treatment.
 *
 * Example:
 * Namespaced constant definition via `define()`:
 * ```
 * define(constant_name: __NAMESPACE__ . '\CONSTANT_NAME', value: 'value');
 * ```
 */

/**
 * Plugin basename
 */
define(
    constant_name: __NAMESPACE__ . '\PLUGIN_BASENAME',
    value: plugin_basename(file: __FILE__)
);

/**
 * Plugin directory path relative to wp-content/plugins (without trailing slash)
 */
define(
    constant_name: __NAMESPACE__ . '\PLUGIN_REL_PATH',
    value: dirname(PLUGIN_BASENAME)
);

/**
 * Plugin directory URL (with trailing slash)
 */
define(
    constant_name: __NAMESPACE__ . '\PLUGIN_DIR_URL',
    value: plugin_dir_url(file: __FILE__)
);

/**
 * Plugin directory path (without trailing slash)
 */
const PLUGIN_DIR_PATH = __DIR__;

/**
 * Plugin source path (without trailing slash)
 */
const PLUGIN_SOURCE_PATH = PLUGIN_DIR_PATH . '/Sources';

/**
 * Plugin library path (without trailing slash)
 */
const PLUGIN_LIBRARY_PATH = PLUGIN_SOURCE_PATH . '/Libs';

// Include the plugin autoloader
require_once PLUGIN_SOURCE_PATH . '/autoload.php';

// Include the library autoloader
require_once PLUGIN_LIBRARY_PATH . '/autoload.php';
// phpcs:enable

// Load the plugin's main class.
new Main();
