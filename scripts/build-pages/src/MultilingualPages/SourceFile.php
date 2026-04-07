<?php

namespace myproject\MultilingualPages;

class SourceFile {
  protected string $filepath;
  public function __construct(string $filepath) {
    $this->filepath = $filepath;
  }
  public function build() {
    foreach ($this->paths() as $lang => $path) {
      $this->put(
        $lang,
        $path,
        'generated_pages',
        'march2026',
      );
    }
    foreach ($this->redirects() as $lang => $paths) {
      foreach ($paths as $path) {
        $this->put(
          $lang,
          $path,
          'generated_redirects',
          'redirect',
        );
      }
    }
  }
  public function pathAsDir(string $path) {
    if (!str_ends_with($path, '/')) {
      $path .= '/';
    }
    return $path;
  }
  public function pathAsFile(string $path) {
    $path_no_slash = $path;
    if (str_ends_with($path, '/')) {
      $path_no_slash = substr($path, 0, -1);
    }
    return $path_no_slash ? $path_no_slash . '.html' : '';
  }
  public function paths() {
    $structure = $this->structure();
    $paths = $structure['paths'] ?? [];
    $ret = [];
    foreach ($paths as $lang => $path) {
      $ret[$lang] = $this->pathAsDir($path);
    }
    return $ret;
  }
  public function redirects() {
    $structure = $this->structure();
    $redirects = $structure['redirects'] ?? [];
    $paths = $this->paths();
    foreach ($paths as $lang => $path) {
      if ($candidate = $this->pathAsFile($path)) {
        $redirects[$lang][] = $candidate;
      }
    }
    return $redirects;
  }
  public function put(
    string $lang,
    string $path,
    string $generated_directory,
    string $layout,
  ) {
    if (!file_exists('/app/docs/' . $generated_directory . '/_posts')) {
      mkdir('/app/docs/' . $generated_directory . '/_posts', 0777, TRUE);
    }
    file_put_contents(
      '/app/docs/' . $generated_directory . '/_posts/' . $this->date() . '-' . md5(json_encode([$lang, $path, $generated_directory, $layout])) . '.md',
      $this->toYaml($lang, $path, $layout),
    );
  }
  public function date() {
    $structure = $this->structure();
    // Random date of not available.
    return $structure['date'] ?? '2020-01-01';
  }
  public function toYaml(string $lang, string $path, string $layout) {
    return yaml_emit([
      'layout' => $layout,
      'lang' => $lang,
      'data' => $this->id(),
      'title' => $this->title($lang),
      'permalink' => $path,
    ]);
  }
  public function title(string $lang) {
    $structure = $this->structure();
    return $structure['titles'][$lang] ?? '';
  }
  public function structure() {
    return yaml_parse_file($this->filepath);
  }
  public function id() {
    return basename($this->filepath, '.yml');
  }

}
