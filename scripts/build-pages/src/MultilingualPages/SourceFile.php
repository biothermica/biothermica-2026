<?php

namespace myproject\MultilingualPages;

class SourceFile {
  protected string $filepath;
  public function __construct(string $filepath) {
    $this->filepath = $filepath;
  }
  public function build() {
    foreach ($this->paths() as $lang => $path) {
      $this->put($lang, $path, $this->id());
    }
  }
  public function id() {
    return basename($this->filepath, '.yml');
  }
  public function put(string $name, string $path, string $id) {
    if (!file_exists('/app/docs' . $path)) {
      mkdir('/app/docs' . $path, 0777, TRUE);
    }
    if (!str_ends_with($path, '/')) {
      $path .= '/';
    }
    file_put_contents(
      '/app/docs' . $path . 'index.html',
      $this->toYaml($name, $id),
    );
    $path_no_slash = substr($path, 0, -1);
    if ($path_no_slash) {
      file_put_contents(
        '/app/docs' . $path_no_slash . '.html',
        yaml_emit([
          'layout' => 'redirect',
          'redirectTo' => $path,
        ]),
      );
    }
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
