<?php

use WPLumber\PluginHandler;
use WPLumber\LogHandler;

class WPLumber {
  private static ?PluginHandler $pluginHandler = null;
  public static function init() {
    if (is_null(self::$pluginHandler)) {
      self::$pluginHandler = new PluginHandler();
      add_action("plugins_loaded", function () {
        do_action("WPLumber/init", self::$pluginHandler);
      });
    }
  }
  public static function __callStatic($name, $arguments) {
    return self::$pluginHandler->$name(...$arguments);
  }
  public static function getPluginHandler(): PluginHandler {
    return self::$pluginHandler;
  }
}

WPLumber::init();

add_action("WPLumber/init", function ($pluginHandler) {
  $pluginHandler->registerHandler("log", new LogHandler($pluginHandler));
});
