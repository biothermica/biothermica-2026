<?php

namespace myproject;

use myproject\MultilingualPages\SourceFile;

class MultilingualPages {
  protected $type;
  public function __construct($type) {
    $this->type = $type;
  }
  public function build() {
    foreach ($this->sourceFiles() as $file) {
      $file->build();
    }
  }
  public function sourceDir() {
    return "/app/docs/_data/{$this->type}";
  }
  public function sourceFiles() {
    $ret = [];
    foreach (scandir($this->sourceDir(), SCANDIR_SORT_ASCENDING) as $filename) {
      if (substr($filename, -4) === '.yml') {
        $ret[] = new SourceFile($this->sourceDir() . '/' . $filename, $this->type);
      }
    }
    return $ret;
  }
}
