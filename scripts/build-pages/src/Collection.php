<?php

namespace myproject;

class Collection {
  protected $id;
  protected $callback;

  public function __construct($id, $callback) {
    $this->id = $id;
    $this->callback = $callback;
  }

  public function callback() {
    return $this->callback;
  }

  public function id() {
    return $this->id;
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
