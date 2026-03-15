<?php

namespace myproject;

class Article {
  protected string $filepath;
  public function __construct(string $filepath) {
    $this->filepath = $filepath;
  }
  public function has($param) {
    if (!$param) {
      throw new \Exception('Missing parameter');
    }
    $ret = array_key_exists($param, $this->structure());
    return $ret;
  }
  public function build() {
    foreach ([
      new ArticleFr($this),
      new ArticleEn($this),
    ] as $articleInLanguage) {
      $articleInLanguage->build();
    }
  }
  public function structure() {
    return yaml_parse_file($this->filepath);
  }
  public function date() {
    $ret = mb_substr(basename($this->filepath), 0, strlen('YYYY-MM-DD'));
    return $ret;
  }
}
