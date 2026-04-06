<?php

namespace myproject;

abstract class MultilingualCollection {
  public function build() {
    foreach ($this->items() as $file) {
      $file->build();
    }
  }
  public function items() {
    $ret = [];
    foreach (scandir($this->sourceDir(), SCANDIR_SORT_ASCENDING) as $filename) {
      if (substr($filename, -3) === '.md') {
        $ret[] = new ($this->itemClass())($this->sourceDir() . '/' . $filename);
      }
    }
    return $ret;
  }
  abstract protected function sourceDir(): string;
  abstract protected function itemClass(): string;
}
