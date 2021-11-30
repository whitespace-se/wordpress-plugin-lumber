<?php

add_filter("timber/loader/paths", function ($paths) {
  $paths[] = WP_LUMBER_PATH . "/templates";
  return $paths;
});
