<?php

namespace myproject;

class CollectionFile {
  protected $filepath;
  protected $collection;

  public function __construct($filepath, $collection) {
    $this->filepath = $filepath;
    $this->collection = $collection;
  }

  public function build() {
    $structure = $this->structure();

  }

  public function structure() {
    return yaml_parse_file($this->filepath);
  }

}
