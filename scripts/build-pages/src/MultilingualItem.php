<?php

namespace myproject;

abstract class MultilingualItem {
  protected string $filepath;
  public function __construct(string $filepath) {
    $this->filepath = $filepath;
  }
  public function has($param) {
    if (!$param) {
      throw new \Exception('Empty param in has()');
    }
    $ret = array_key_exists($param, $this->structure());
    return $ret;
  }
  public function structure() {
    return yaml_parse_file($this->filepath);
  }
  public function date() {
    $ret = mb_substr(basename($this->filepath), 0, strlen('YYYY-MM-DD'));
    return $ret;
  }
}
