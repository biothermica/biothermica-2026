<?php

namespace myproject;

class ArticleFr extends ArticleSingleLanguage {
  public function titleField() {
    return 'title';
  }
  public function bodyField() {
    return 'body';
  }
  public function prefix() {
    return '/fr/nouvelles';
  }
  public function redirectsKey() {
    return 'redirects';
  }
}
