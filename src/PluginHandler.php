<?php

namespace WPLumber;

use Timber\Timber;

class PluginHandler {
  public const OPTION_NAME = "lumber";
  public const OPTION_GROUP = "lumber";
  public const OPTION_SECTIONS = "lumber";

  private $handlers;
  private $db;

  public function __construct() {
    add_action("admin_menu", [$this, "onAdminMenu"]);
    add_action("admin_init", [$this, "onAdminInit"]);
    $this->handlers = [];
    $this->db = new Database();
    $this->timber = new Timber();
    add_action("WPLumber/activate", [$this, "onActivate"]);
  }

  public function onActivate() {
    $this->db->install();
  }

  public function registerHandler($name, $handler) {
    $this->handlers[$name] = $handler;
  }

  public function getHandler($name) {
    return $this->handlers[$name] ?? null;
  }

  public function getDatabase() {
    return $this->db;
  }

  public function getTimber(): Timber {
    return $this->timber;
  }

  public function render($files, ...$args) {
    $files = (array) $files;
    $files = array_map(function ($file) {
      return WP_LUMBER_PATH . "/templates/" . $file;
    }, $files);
    return $this->timber->render($files, ...$args);
  }

  public function onAdminMenu() {
    add_options_page(
      __("Lumber settings", "lumber"),
      __("Lumber", "lumber"),
      "manage_options",
      __("lumber"),
      [$this, "renderOptionsPage"]
    );
  }
  public function renderOptionsPage(): void {
    ?>
      <div class="wrap">
          <h1><?php _e("Lumber settings", "lumber"); ?></h1>
          <form method="post" action="options.php">
          <?php
          // This prints out all hidden setting fields
          settings_fields(self::OPTION_GROUP);
          do_settings_sections(self::OPTION_SECTIONS);
          submit_button();?>
          </form>
      </div>
      <?php
  }

  public function onAdminInit() {
    register_setting(self::OPTION_GROUP, self::OPTION_NAME, [
      $this,
      "sanitizeOption",
    ]);
  }

  public function sanitizeOption($input) {
    $input = apply_filters("WPLumber/sanitize_option", $input);
    return $input;
  }

  public function getOption(string $name = null, $default = null) {
    $option_value = get_option(self::OPTION_NAME, []);
    if ($name) {
      $option_value = $option_value[$name] ?? $default;
    }
    return $option_value;
  }

  public function setOption(string $name, $value): bool {
    $option_value = get_option(self::OPTION_NAME, []);
    $option_value[$name] = $value;
    return update_option(self::OPTION_NAME, $option_value);
  }
}
