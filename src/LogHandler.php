<?php
namespace WPLumber;

class LogHandler {
  private PluginHandler $pluginHandler;

  public function __construct(PluginHandler $plugin_handler) {
    $this->pluginHandler = $plugin_handler;
    add_action("WPLumber/activate", [$this, "onActivate"]);
    add_action("admin_menu", [$this, "onAdminMenu"]);
  }

  public function onActivate() {
    $this->addLog("Logging activated");
  }

  public function onAdminMenu() {
    add_submenu_page(
      "tools.php",
      __("Lumber logs", "lumber"),
      __("Lumber logs", "lumber"),
      "manage_options",
      "lumber_logs",
      [$this, "adminPage"]
    );
  }

  public function adminPage() {
    $items = $this->pluginHandler->getDatabase()->getLogs();
    $this->pluginHandler->render("logs-table.twig", [
      "items" => $items,
    ]);
  }

  public function addLog($message) {
    $this->pluginHandler->getDatabase()->insertLog(["message" => $message]);
  }
}
