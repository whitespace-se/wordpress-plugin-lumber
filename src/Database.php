<?php

namespace WPLumber;

class Database {
  const DB_VERSION = 1;

  public function __construct() {
    global $wpdb;
    $this->tableName = $wpdb->prefix . "lumber_logs";
  }

  public function install() {
    global $wpdb;
    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE $this->tableName (
      id mediumint(9) UNSIGNED NOT NULL AUTO_INCREMENT,
      message text NOT NULL,
      time datetime NOT NULL,
      PRIMARY KEY (id)
    ) $charset_collate;";

    require_once ABSPATH . "wp-admin/includes/upgrade.php";
    dbDelta($sql);

    add_option("lumber_db_version", self::DB_VERSION);
  }

  public function insertLog($entry) {
    global $wpdb;
    $entry += ["message" => "", "time" => current_time("mysql")];
    $wpdb->insert($this->tableName, $entry);
  }

  public function getLogs() {
    global $wpdb;
    $from = 0;
    $size = 50;
    return $wpdb->get_results(
      "SELECT * from $this->tableName ORDER BY time DESC LIMIT $from, $size",
      ARRAY_A
    );
  }
}
