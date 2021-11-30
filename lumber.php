<?php

/**
 * Plugin Name: Lumber
 * Description: Allows you to log to the database
 * Version: 0.1.0
 * Author: Whitespace AB
 * Text Domain: lumber
 * Domain Path: /languages/
 */

define("WP_LUMBER_PLUGIN_FILE", __FILE__);
define("WP_LUMBER_PATH", dirname(__FILE__));
define("WP_LUMBER_AUTOLOAD_PATH", WP_LUMBER_PATH . "/autoload");

add_action("init", function () {
  $path = plugin_basename(dirname(__FILE__)) . "/languages";
  load_plugin_textdomain("lumber", false, $path);
  load_muplugin_textdomain("lumber", $path);
});

array_map(static function () {
  include_once func_get_args()[0];
}, glob(WP_LUMBER_AUTOLOAD_PATH . "/*.php"));
