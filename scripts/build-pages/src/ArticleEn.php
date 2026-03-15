<?php

namespace myproject;

class ArticleEn extends ArticleSingleLanguage {
  public function titleField() {
    return 'title_en';
  }
  public function bodyField() {
    return 'body_en';
  }
  public function prefix() {
    return '/news';
  }
  public function redirectsKey() {
    return 'redirects_en';
  }

}
