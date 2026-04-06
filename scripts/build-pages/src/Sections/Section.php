<?php

namespace myproject\Sections;

class Section {
  protected string $filename;
  public function __construct(string $filename) {
    $this->filename = $filename;
  }
  public function build() {
    file_put_contents(
      '/app/docs/_data/pages/style-guide--sections--' . $this->id() . '.yml',
      $this->toYaml(),
    );
  }
  public function toYaml() {
    return yaml_emit($this->struct());
  }
  public function id() {
    return pathinfo($this->filename, PATHINFO_FILENAME);
  }
  public function struct() {
    $filename = $this->filename;
    if (!$lines = file($filename)) {
      return '';
    }
    $struct = [
      'id' => $filename,
      'paths' => [
        'en' => '/style-guide/sections/' . $this->id() . '/',
        'fr' => '/fr/guide-style/sections/' . $this->id() . '/'
      ],
      'titles' => [
        'en' => 'Section ' . $this->id(),
        'fr' => 'Section ' . $this->id(),
      ],
      'template' => 'style-guide--sections',
      'categories' => [ 'style-guide--sections' ],
    ];
    return array_merge($struct, $this->commentToStruct($lines));
  }
  public function commentToStruct(array $lines) {
    if (count($lines) < 3) {
      return [];
    }
    if (trim($lines[0]) !== '{% comment %}') {
      return [];
    }
    array_shift($lines);
    $yamlLines = [];
    do {
      $yamlLines[] = $lines[0];
      array_shift($lines);
    }
    while ($lines && trim($lines[0]) !== '{% endcomment %}');
    $yaml = implode($yamlLines);
    return yaml_parse($yaml);
  }


}
