<?php

namespace myproject;

use myproject\Articles\Article;

class Articles extends MultilingualCollection {
  public function sourceDir() : string {
    return '/app/docs/_data/articles';
  }
  protected function itemClass(): string {
    return Article::class;
  }
}
