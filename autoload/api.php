<?php

function wp_lumber_log($message) {
  WPLumber::getHandler("log")->addLog($message);
}
