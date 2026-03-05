<?php

namespace myproject;

class MultilingualPages {
  const SOURCEDIR = '/app/docs/_data/pages';
  public function build(string $betaBasePath) {
    foreach ($this->sourceFiles() as $file) {
      $file->build($betaBasePath);
    }
  }
  public function sourceFiles() {
    $ret = [];
    foreach (scandir(self::SOURCEDIR, SCANDIR_SORT_ASCENDING) as $filename) {
      if (substr($filename, -4) === '.yml') {
        $ret[] = new SourceFile(self::SOURCEDIR . '/' . $filename);
      }
    }
    return $ret;
  }
}
