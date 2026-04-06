<?php

namespace myproject;

use myproject\Sections\Section;

class Sections {
  const SOURCEDIR = '/app/docs/_includes/sections';
  public function build() {
    foreach ($this->sections() as $file) {
      $file->build();
    }
  }
  public function sections() {
    $ret = [];
    foreach (scandir(self::SOURCEDIR, SCANDIR_SORT_ASCENDING) as $filename) {
      if (substr($filename, -5) === '.html') {
        $ret[] = new Section(self::SOURCEDIR . '/' . $filename);
      }
    }
    return $ret;
  }
}
