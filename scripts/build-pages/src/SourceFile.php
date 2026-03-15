<?php

namespace myproject;

class SourceFile {
  protected string $filepath;
  public function __construct(string $filepath) {
    $this->filepath = $filepath;
  }
  public function build(string $betaBasePath) {
    foreach ($this->paths() as $lang => $path) {
      $this->put($betaBasePath, $lang, $path, $this->id());
    }
  }
  public function id() {
    return basename($this->filepath, '.yml');
  }
  public function put(string $betaBasePath, string $name, string $path, string $id) {
    if (!file_exists('/app/docs' . $betaBasePath . $path)) {
      mkdir('/app/docs' . $betaBasePath . $path, 0777, TRUE);
    }
    file_put_contents(
      '/app/docs' . $betaBasePath . $path . 'index.html',
      $this->toYaml($name, $id),
    );
  }
  public function toYaml(string $lang, string $id) {
    return yaml_emit([
      'layout' => 'march2026',
      'lang' => $lang,
      'data' => $id,
      'title' => $this->title($lang),
    ]);
  }
  public function title(string $lang) {
    $structure = $this->structure();
    return $structure['titles'][$lang] ?? '';
  }
  public function paths() {
    $structure = $this->structure();
    return $structure['paths'] ?? [];
  }
  public function structure() {
    return yaml_parse_file($this->filepath);
  }
}
