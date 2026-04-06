<?php

namespace myproject;

class Projects {
  const SOURCEDIR = '/app/docs/_data/projects';
  public function build() {
    foreach ($this->sourceFiles() as $file) {
      $file->build();
    }
  }
  public function sourceFiles() {
    $ret = [];
    foreach (scandir(self::SOURCEDIR, SCANDIR_SORT_ASCENDING) as $filename) {
      if (substr($filename, -4) === '.yml') {
        $ret[] = new Project(self::SOURCEDIR . '/' . $filename);
      }
    }
    return $ret;
  }
}
