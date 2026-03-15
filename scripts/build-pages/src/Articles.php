<?php

namespace myproject;

class Articles {
  const SOURCEDIR = '/app/docs/_articles/_posts';
  public function build() {
    foreach ($this->articles() as $file) {
      $file->build();
    }
  }
  public function articles() {
    $ret = [];
    foreach (scandir(self::SOURCEDIR, SCANDIR_SORT_ASCENDING) as $filename) {
      if (substr($filename, -3) === '.md') {
        $ret[] = new Article(self::SOURCEDIR . '/' . $filename);
      }
    }
    return $ret;
  }
}
