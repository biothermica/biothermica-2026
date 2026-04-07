<?php

namespace myproject;

class Collection {
  protected $id;
  protected $options;

  public function __construct($id, $options) {
    $this->id = $id;
    $this->options = $options;
  }

  public function build() {
    foreach ($this->items() as $file) {
      $file->build();
    }
  }

  public function sourceDir() {
    return "/app/docs/_data/multi/{$this->id}";
  }

  public function items() {
    $ret = [];
    foreach (scandir($this->sourceDir(), SCANDIR_SORT_ASCENDING) as $filename) {
      if (substr($filename, -3) === '.md') {
        $ret[] = new CollectionFile($this->sourceDir() . '/' . $filename, $this);
      }
    }
    return $ret;
  }
}
