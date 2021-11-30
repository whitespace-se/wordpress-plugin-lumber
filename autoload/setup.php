<?php

function wp_lumber_activate() {
  add_option("wp_lumber_activated", true);
}

add_action(
  "WPLumber/init",
  function () {
    if (empty(get_option("wp_lumber_activated"))) {
      return;
    }
    do_action("WPLumber/activate");
    delete_option("wp_lumber_activated");
  },
  20
);

register_activation_hook(WP_LUMBER_PLUGIN_FILE, "wp_lumber_activate");
