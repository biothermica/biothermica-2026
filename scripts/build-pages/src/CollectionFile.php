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
    if (!file_exists('/app/docs/_data/generated_pages/')) {
      mkdir('/app/docs/_data/generated_pages/', 0777, TRUE);
    }
    file_put_contents(
      '/app/docs/_data/generated_pages/' . $this->collection->id() . '-' . md5($this->filepath) . '.yml',
      $this->toYaml(),
    );
  }

  public function structure() {
    return yaml_parse_file($this->filepath);
  }

  public function toYaml() {
    return yaml_emit([
      '_original' => $this->structure()
    ]);
  }

}
